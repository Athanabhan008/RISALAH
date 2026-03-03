<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\SharingProfit;
use App\Models\SharingProfitModel;
use App\Models\vwExportshareprovit;
use App\Models\VwSharingprofit;
use App\Models\Wapu;
use Illuminate\Support\Facades\Log;
use App\Libraries\easyTables;
use App\Libraries\exFPDF;

class ApprovalController extends Controller
{
    public function __construct()
    {
        $this->fpdf = new exFPDF('P', 'cm', 'A4');
    }

    public function index()
    {

        $user = auth()->user()->toArray();
        $today = date('Y-m-d');
        $month = date('Ym');
        $year = date('Y');


        //BOOKING

        return view('approval.index', [
            "active" => 'approval',
            "user" => $user
        ]);
    }

    public function datatable()
    {
        $draw = request()->get('draw');
        $start = request()->get('start');
        $length = request()->get('length');
        $id_user = request()->get('cmb_nip');

        // Dukungan filter: range bulan (prioritas) atau 1 bulan (kompatibilitas lama)
        $periode_start = request()->get('periode_start');
        $periode_end = request()->get('periode_end');
        $periodeSingle = request()->get('periode_pr');

        $user = auth()->user();
        $query = VwSharingprofit::query();

        if ($periode_start && $periode_end) {
            $query->whereBetween('periode', [$periode_start, $periode_end]);
        } elseif ($periodeSingle) {
            $query->where('periode', $periodeSingle);
        }

        if ($id_user) {
            $query->where('nip_user', $id_user);
        }

        // Filter berdasarkan user yang login
        if (!in_array($user->role, ['super_admin', 'admin', 'manager'])) {
            $query->where('id_sales', $user->id);
        }

        $total = $query->count();

        // Apply pagination - jika length adalah -1, null, atau sangat besar (> 10000), ambil semua data tanpa pagination
        // Juga handle jika length adalah 0 atau tidak valid
        if ($length == -1 || $length === null || $length === '' || $length > 10000 || $length <= 0) {
            // Ambil semua data tanpa pagination
            $results = $query->get();
        } else {
            // Apply pagination normal dengan validasi
            $start = $start ?? 0;
            $results = $query->offset($start)
                            ->limit((int)$length)
                            ->get();
        }

        return response()->json([
            'draw' => $draw,
            'recordsTotal' => $total,
            'recordsFiltered' => $total,
            'data' => $results
        ]);
    }

    public function setApprove(Request $request)
    {

        $user = auth()->user()->toArray();

        try {

            $id_user = $user['id'];
            $nama_user = $user['name'];

            // Dukungan rentang bulan atau 1 bulan (mengikuti filter di datatable)
            $periode_start = $request->input('periode_start');
            $periode_end = $request->input('periode_end');
            $periodeSingle = $request->input('periode_pr');

            $query = VwSharingprofit::query();

            if ($periode_start && $periode_end) {
                $query->whereBetween('periode', [$periode_start, $periode_end]);
            } elseif ($periodeSingle) {
                $query->where('periode', $periodeSingle);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Periode belum dipilih'
                ], 422);
            }

            // Ikuti juga filter user seperti di datatable (untuk non manager/admin/super_admin)
            $userObj = auth()->user();
            if (!in_array($userObj->role, ['super_admin', 'admin', 'manager'])) {
                $query->where('id_sales', $userObj->id);
            }

            // Ambil daftar id_projek dari view yang tampil di datatable
            $projekIds = $query->pluck('id_projek')->filter()->unique();

            if ($projekIds->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data tidak ditemukan untuk periode tersebut'
                ], 404);
            }

            // Ambil semua sharing_profit yang terkait dan update statusnya
            $sharingProfits = SharingProfitModel::whereIn('id_projek', $projekIds)->get();

            foreach ($sharingProfits as $sharingProfit) {
                if ($user['role'] == 'admin') {
                    $sharingProfit->update([
                        'is_pengajuan_admin' => 1,
                        'id_admin' => $id_user,
                        'nama_admin' => $nama_user
                    ]);
                } else {
                    $sharingProfit->update([
                        'is_approve' => 1,
                        'id_approve' => $id_user,
                        'user_approve' => $nama_user
                    ]);
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil diupdate',
                'data' => $sharingProfits
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengupdate data',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function cetakPDF(Request $request)
    {


        $periode_start  = $request->get('periode_end');
        $periode_end    = $request->get('periode_end');
        $periodeSingle = $request->get('periode_pr');

         $query = vwExportshareprovit::query();

         if ($periode_start && $periode_end) {
            $query->whereBetween(DB::raw("DATE_FORMAT(tgl_bayar, '%Y%m')"), [
                $periode_start, $periode_end
            ]);
          }

    $data_result = $query->get()->toArray();
    // echo '<pre>';
    // print_r($data_result);

    // if (empty($data_result)) {
    //     abort(404, 'Data tidak ditemukan pada periode tersebut');
    // }
        // echo "<pre>";
        // print_r($data_result);die;

        if (empty($data_result)) {
            return response()->json([
                'message' => 'Data tidak ditemukan pada periode tersebut',
                'periode_start' => $periode_start,
                'periode_end' => $periode_end
            ]);
        }


    	$this->fpdf->SetFont('Arial', '', 12);
        $this->fpdf->AddPage('L', 'A4');

        // Fix the image path - use absolute path from public directory
        $this->fpdf->Image(public_path('admin/assets/img/logos/logo_cv.png'), 2.8, 1, 5, 2);
		$this->fpdf->SetFont('helvetica', '', 10);
		$this->fpdf->SetTextColor(0, 0, 0);


		// Menggeser teks ke kanan dengan menambah Cell kosong yang lebih lebar
        $this->fpdf->SetFont('Arial', 'B', 17);
        $this->fpdf->SetTextColor(4, 28, 80); // Set color to #093FB4
		$this->fpdf->Cell(7, 0.7, '', 0, 0, 'L');
		$this->fpdf->Cell(12, 0.7, "IT SOLUTION PROVIDER", 0, 0, 'L');
        $this->fpdf->SetTextColor(0, 0, 0); // Reset color back to black
		$this->fpdf->Ln(0.8);

        $this->fpdf->SetFont('Arial', 'B', 13);
		$this->fpdf->Cell(7, 0.7, '', 0, 0, 'L');
		$this->fpdf->Cell(12, 0.7, "Hardware - Software - Services", 0, 0, 'L');
		$this->fpdf->Ln(0.5);

        $this->fpdf->SetFont('Arial', 'B', 9);
		$this->fpdf->Cell(7, 0.7, '', 0, 0, 'L');
		$this->fpdf->Cell(12, 0.7, "Jl. Cilengkrang 2 No.144 Kota Bandung - Jawa Barat 40615", 0, 0, 'L');
        $this->fpdf->Ln(0.5);

        $this->fpdf->SetFont('Arial', 'B', 9);
        $this->fpdf->SetTextColor(4, 28, 80); // Set color to #093FB4
		$this->fpdf->Cell(7, 0.7, '', 0, 0, 'L');
		$this->fpdf->Cell(12, 0.7, "Email: Sales@mbsonline.id", 0, 0, 'L');
        $this->fpdf->SetTextColor(0, 0, 0); // Reset color back to black
        $this->fpdf->Ln(0.5);

        $this->fpdf->SetFont('Arial', 'B', 9);
        $this->fpdf->SetTextColor(4, 28, 80); // Set color to #093FB4
		$this->fpdf->Cell(7, 0.7, '', 0, 0, 'L');
		$this->fpdf->Cell(12, 0.7, "Web Site : https://mbsonline.id", 0, 0, 'L');
        $this->fpdf->SetTextColor(0, 0, 0); // Reset color back to black
        $this->fpdf->Ln(0.5);

		$this->fpdf->SetFont('helvetica', 'I', 6);
		$this->fpdf->SetXY(15, 0.5);
		// $this->fpdf->Write(0, "Dicetak pada : " . date("Y-m-d H:i:s"));


		// $this->fpdf->Line(1, 2.8, 20, 2.8);
		$this->fpdf->Ln(4.3);






        $this->fpdf->SetFont('helvetica', 'BU', 16);
        $this->fpdf->Cell(0, 0.7, "Purchase Order", 0, 0, 'C');
		$this->fpdf->Ln(1);



        // $this->fpdf->SetFont('helvetica', '', 11);
        // $this->fpdf->Cell(12, 0.5, "Nomor PO : " . $data_result[0]['nomor_po'], 0, 0, 'L');
        // $this->fpdf->Cell(0, 0.5, "Bandung, " . $this->formatDateIndonesian(), 0, 0, 'R');
		// $this->fpdf->Ln();
        // $this->fpdf->Cell(12, 0.5, "Lampiran : " . $data_result[0]['lampiran'], 0, 0, 'L');
		// $this->fpdf->Ln(1);
        // $this->fpdf->Cell(12, 0.5, "Kepada Yth : " . $data_result[0]['sales_vendor'], 0, 0, 'L');
		// $this->fpdf->Ln();
        // $this->fpdf->Cell(12, 0.5, $data_result[0]['nama_vendor'], 0, 0, 'L');
		// $this->fpdf->Ln(1);
		// $this->fpdf->Ln(0.5);

        // Move address closer to client name - right after the date
        $this->fpdf->Cell(3.5, 0.7, '', 0, 0, 'L');
        // $this->fpdf->Cell(12, 0.5, $data_result[0]['alamat'], 0, 0, 'L');
		$this->fpdf->Ln(1);

        // // Continue with Purchase Order information
        // $this->fpdf->Cell(12.1, 0.5, "Purchase Order", 0, 0, 'R');
        // $this->fpdf->Cell(0.7, 0.5, ":", 0, 0, 'R');
		// $this->fpdf->Ln(0.8);
        // $this->fpdf->Cell(10.2, 0.5, "Date", 0, 0, 'R');
        // $this->fpdf->Cell(2.6, 0.5, ":", 0, 0, 'R');
		// $this->fpdf->Ln(0.1);

        // Buat tabel dengan auto-sizing
        $table = new easyTables($this->fpdf, "{2.5, 8, 10, 20, 8, 11, 11, 11, 11, 11}", 'border:1;font-size:7.9;min-height:0.5;');

        $table->rowStyle('font-style:B;');
        $table->easyCell('NO', 'valign:M;align:C;');
        $table->easyCell('Nama Client', 'valign:M;align:C;');
        $table->easyCell('Nama Projek', 'valign:M;align:C;');
        $table->easyCell('Nomor PR', 'valign:M;align:C;');
        $table->easyCell('Divisi', 'valign:M;align:C;');
        $table->easyCell('Profit Holding', 'valign:M;align:C;');
        $table->easyCell('Profit Leader', 'valign:M;align:C;');
        $table->easyCell('Profit Dir.utama', 'valign:M;align:C;');
        $table->easyCell('Profit SIM', 'valign:M;align:C;');
        $table->easyCell('Profit Keuangan', 'valign:M;align:C;');
        $table->easyCell('Total Profit', 'valign:M;align:C;');
        $table->printRow();
        $this->fpdf->Ln(0);

        // $table->easyCell('PPN', 'valign:M;align:L;colspan:7');
        // $table->printRow();
        $i = 1;
        // $grand_total_ppn = 0; // Initialize grand total
        foreach ($data_result as $value) {
            $table->easyCell($i++, 'valign:M;align:C;');
                $table->easyCell($value['nama_client'], 'valign:M;align:L;');
                $table->easyCell($value['nama_projek'], 'valign:M;align:C;');
                $table->easyCell($value['nomor_pr'], 'valign:M;align:L;');
                $table->easyCell($value['divisi'], 'valign:M;align:L;');
                $table->easyCell($value['profit_holding'], 'valign:M;align:L;');
                $table->easyCell($value['profit_leader'], 'valign:M;align:L;');
                $table->easyCell($value['profit_dirutama'], 'valign:M;align:L;');
                $table->easyCell($value['profit_sim'], 'valign:M;align:L;');
                $table->easyCell($value['profit_keuangan'], 'valign:M;align:L;');
                $table->printRow();
        }
        $table->rowStyle('font-style:B; border:1;');
        $table->easyCell('TOTAL', 'colspan:5;valign:M;align:R;');
        $table->easyCell(array_sum(array_map(function($v) {
            $v = str_replace(['Rp', ' ', '.'], '', $v);
            return intval($v);
        }, array_column($data_result, 'profit_holding'))), 'valign:M;align:L;');
        $table->easyCell(array_sum(array_map(function($v) {
            $v = str_replace(['Rp', ' ', '.'], '', $v);
            return intval($v);
        }, array_column($data_result, 'profit_leader'))), 'valign:M;align:L;');
        $table->easyCell(array_sum(array_map(function($v) {
            $v = str_replace(['Rp', ' ', '.'], '', $v);
            return intval($v);
        }, array_column($data_result, 'profit_dirutama'))), 'valign:M;align:L;');
        $table->easyCell(array_sum(array_map(function($v) {
            $v = str_replace(['Rp', ' ', '.'], '', $v);
            return intval($v);
        }, array_column($data_result, 'profit_sim'))), 'valign:M;align:L;');
        $table->easyCell(array_sum(array_map(function($v) {
            $v = str_replace(['Rp', ' ', '.'], '', $v);
            return intval($v);
        }, array_column($data_result, 'profit_keuangan'))), 'valign:M;align:L;');

        $table->printRow();

        $this->fpdf->Output();
        exit;

    }


}

<?php

namespace App\Http\Controllers\omset;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\VwOmset;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Vwgrafikomzet;
use App\Libraries\easyTables;
use App\Libraries\exFPDF;

class OmsetController extends Controller
{

    public function __construct()
    {
        $this->fpdf = new exFPDF('P', 'cm', 'A4');
    }


    public function index()
    {
        $grafik_omzet = Vwgrafikomzet::query();
        $data_omzet = $grafik_omzet->get()->toArray();

        return view('omset/index',[
            "active" => 'omset',
            "grafik_omzet" => $data_omzet
        ]);
    }

    public function datatable()
    {
    $created_at = request()->get('created_at');
    $cmb_sales = request()->get('cmb_sales');

    $query = VwOmset::query();

    if ($created_at) {
        $year = substr($created_at, 0, 4);
        $month = substr($created_at, 5, 2);

        if (!empty($month)) {
            $startDate = \Carbon\Carbon::createFromFormat('Y-m', "$year-$month")->startOfMonth();
            $endDate = \Carbon\Carbon::createFromFormat('Y-m', "$year-$month")->endOfMonth();
        } else {
            // Jika hanya tahun yang diinput (filter tahunan)
            $startDate = \Carbon\Carbon::createFromFormat('Y', $year)->startOfYear();
            $endDate   = \Carbon\Carbon::createFromFormat('Y', $year)->endOfYear();
        }
        $query->whereBetween('created_at', [$startDate, $endDate]);
    }

    if ($cmb_sales) {
        $query->where('id_sales', $cmb_sales);
    }

    $results = $query->get();

    // Hitung persentase margin
    $results = $results->map(function ($item) {
        $grossProvit = $item->gross_provit ?? 0;
        $validasiPayment = $item->validasi_payment ?? 0;

        if ($validasiPayment > 0) {
            $item->persentase_margin = round(($grossProvit / $validasiPayment) * 100, 2);
        } else {
            $item->persentase_margin = 0;
        }

        return $item;
    });

    return response()->json($results);   // ← KUNCI UTAMA
    }



    public function getSales()
    {
        $result = User::query()
            ->where('role', 'sales')
            ->when(request('q'), function ($query, $term) {
                $query->where('name', 'like', '%' . $term . '%');
            })
            ->orderBy('name')
            ->get(['id', 'name']);

        return response()->json([
            'error' => 0,
            'message' => 'Success',
            'data'=> $result
        ]);
    }
    public function cetakPDF(Request $request)
    {
        $created_at = $request->get('created_at');
        $query = VwOmset::query();
        if ($created_at) {
            $year = substr($created_at, 0, 4);
            $month = substr($created_at, 5, 2);
            if (!empty($month)) {
                $startDate = \Carbon\Carbon::createFromFormat('Y-m', "$year-$month")->startOfMonth();
                $endDate = \Carbon\Carbon::createFromFormat('Y-m', "$year-$month")->endOfMonth();
            } else {
                $startDate = \Carbon\Carbon::createFromFormat('Y', $year)->startOfYear();
                $endDate   = \Carbon\Carbon::createFromFormat('Y', $year)->endOfYear();
            }
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }
        $data_result = $query->get()->toArray();

        if (empty($data_result)) {
            return response()->json([
                'message' => 'Data tidak ditemukan pada periode tersebut',
                'created_at' => $created_at
            ]);
        }


    	$this->fpdf->SetFont('Arial', '', 12);
        $this->fpdf->AddPage('P', 'A4');

        // Fix the image path - use absolute path from public directory
        $this->fpdf->Image(public_path('admin/assets/img/logos/logo_pt.png'), 2.8, 1, 5, 3);
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
        $this->fpdf->Cell(0, 0.7, "Laporan Omset Perusahaan", 0, 0, 'C');
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
        $table = new easyTables($this->fpdf, "{2.5, 12, 15, 15, 8, 11}", 'border:1;font-size:7.9;min-height:0.5;');

        $table->rowStyle('font-style:B;');
        $table->easyCell('NO', 'valign:M;align:C;');
        $table->easyCell('Nama Client', 'valign:M;align:C;');
        $table->easyCell('Nama Projek', 'valign:M;align:C;');
        $table->easyCell('Nomor PR', 'valign:M;align:C;');
        $table->easyCell('AE', 'valign:M;align:C;');
        $table->easyCell('Revenue', 'valign:M;align:C;');
        $table->printRow();
        $this->fpdf->Ln(0);

        // $table->easyCell('PPN', 'valign:M;align:L;colspan:7');
        // $table->printRow();
        $i = 1;
        // $grand_total_ppn = 0; // Initialize grand total
        foreach ($data_result as $value) {
            $table->easyCell($i++, 'valign:M;align:C;');
                $table->easyCell($value['nama_client'], 'valign:M;align:L;');
                $table->easyCell($value['nama_projek'], 'valign:M;align:L;');
                $table->easyCell($value['nomor_pr'], 'valign:M;align:L;');
                $table->easyCell($value['name'], 'valign:M;align:C;');
                $rupiah = 'Rp ' . number_format($value['validasi_payment'], 0, ',', '.');
                $table->easyCell($rupiah, 'valign:M;align:L;');
                $table->printRow();
        }
        $table->rowStyle('font-style:B; border:1;');
        $table->easyCell('TOTAL', 'colspan:5;valign:M;align:R;');

        // Hitung total validasi_payment dan format ke Rupiah
        $totalValidasiPayment = array_sum(array_column($data_result, 'validasi_payment'));
        $totalRupiah = 'Rp ' . number_format($totalValidasiPayment, 0, ',', '.');
        $table->easyCell($totalRupiah, 'valign:M;align:L;');
        $table->printRow();

        $this->fpdf->Output();
        exit;

    }
}

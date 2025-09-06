<?php

namespace App\Http\Controllers\DO;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Cogs;
use App\Models\PaketSoundsystem;
use App\Models\SoundSystem;
use App\Models\DetailSoundSystem;
use App\Models\Po;
use App\Models\PrwapuDetail;
use App\Models\User;
use App\Models\Vwcogs;
use App\Models\VwPr;
use App\Models\vwGetvendorDetailpr;
use App\Models\VwDo;
use App\Models\VwPrwapudetail;
use App\Models\Wapu;
use App\Models\vwExportPoCv;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Libraries\easyTables;
use App\Libraries\exFPDF;
use App\Models\Delivery;
use App\Models\vwExportDoCv;
use App\Models\VWQc;

class DoController extends Controller
{
    public function __construct()
    {
        $this->fpdf = new exFPDF('P', 'cm', 'A4');
    }

    public function index()
    {
        return view('do/index',[

            "active" => 'do'

        ]);
    }

    public function datatable()
    {
        $draw = request()->get('draw');
        $start = request()->get('start');
        $length = request()->get('length');
        $id_user = request()->get('cmb_nip');

        $user = auth()->user();
        $query = VwDo::query();

        // Filter berdasarkan user yang login
        if ($user->role == 'super_admin') {
            // Jika admin (role 1), tampilkan semua data
            // Tidak perlu filter tambahan
        } else {
            // Jika bukan admin, filter berdasarkan id_sales yang sesuai dengan user yang login
            $query->where('id_sales', $user->id);
        }

        $total = $query->count();

        // Apply pagination
        $results = $query->offset($start)
                        ->limit($length)
                        ->get();

        return response()->json([
            'draw' => $draw,
            'recordsTotal' => $total,
            'recordsFiltered' => $total,
            'data' => $results
        ]);
    }

    public function getSales()
    {
        $result = User::all();

        return response()->json([
            'error' => 0,
            'message' => 'Success',
            'data'=> $result
        ]);
    }

    public function getQc(Request $request)
    {
        $id_sales = $request->input('id_sales');
        $data = DB::table('vw_qc')
            ->where('id_sales', $id_sales)
            ->select('id_qc as id', 'nama_client', 'nama_projek')
            ->get();

        return response()->json(['data' => $data]);
    }


    public function create(Request $request)
    {
        try {
            // Validasi input
            // $request->validate([
            //     'nama_projek' => 'required|string|max:255',
            //     'nomor_pr' => 'required|string|unique:prwapus,nomor_pr'
            // ], [
            //     'nama_projek.required' => 'Nama projek wajib diisi',
            //     'nomor_pr.required' => 'Nomor PR wajib diisi',
            //     'nomor_pr.unique' => 'Nomor PR sudah digunakan'
            // ]);

            $po = new Delivery();
            $po->nomor_do = $request->nomor_do;
            $po->id_sales = $request->cmb_sales;
            $po->id_pr = $request->cmb_pr;
            $po->tgl_pengiriman = $request->tgl_pengiriman;
            $po->alamat = $request->alamat;
            $po->updated_at = null;
            $po->save();

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil disimpan',
                'data' => $po
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
                'message' => 'Gagal menyimpan data',
                'error' => $e->getMessage()
            ], 500);
        }
    }




    public function update(Request $request, $id)
    {
        try {
            // Validasi input
            $request->validate([
                'nama_projek' => 'required|string|max:255',
                'nomor_pr' => 'required|string|unique:prwapus,nomor_pr,' . $id
            ], [
                'nama_projek.required' => 'Nama projek wajib diisi',
                'nomor_pr.required' => 'Nomor PR wajib diisi',
                'nomor_pr.unique' => 'Nomor PR sudah digunakan'
            ]);

            $pr_wapu = Wapu::findOrFail($id);
            $pr_wapu->nama_projek = $request->nama_projek;
            // $pr_wapu->nomor_pr = $request->nomor_pr;
            $pr_wapu->save();

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil diupdate',
                'data' => $pr_wapu
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

    public function delete($id)
    {
        try {
            $pr_wapu = Wapu::findOrFail($id);
            $pr_wapu->delete();

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus data',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function generateNomorDO()
    {
        try {
            // Ambil nomor DO terakhir dari semua data (tanpa filter id_sales)
            $lastDo = Delivery::orderByDesc('id')->first();

            if ($lastDo && $lastDo->nomor_do) {
                // Ambil angka dari nomor_do terakhir, format: DO-2024/III/000001
                // Pisahkan dengan explode, ambil bagian ke-3 (000001)
                $parts = explode('/', $lastDo->nomor_do);
                if (count($parts) >= 3) {
                    // Ambil bagian ketiga (nomor urut)
                    $lastNumber = (int)$parts[2];
                    $newNumber = $lastNumber + 1;
                } else {
                    $newNumber = 1;
                }
            } else {
                $newNumber = 1;
            }

            // Format nomor DO berurut 6 digit
            $nomorUrut = str_pad($newNumber, 6, '0', STR_PAD_LEFT);

            // Bulan romawi
            $bulan = date('n');
            $romawi = ['', 'I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII'];
            $bulanRomawi = $romawi[$bulan];

            // Tahun
            $tahun = date('Y');

            // Gabungkan format
            $nomorDo = "DO-{$tahun}/{$bulanRomawi}/{$nomorUrut}";

            return response()->json([
                'success' => true,
                'nomor_do' => $nomorDo
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal generate nomor DO',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    private function numberToRoman($number) {
        $romans = [
            1000 => 'M',
            900 => 'CM',
            500 => 'D',
            400 => 'CD',
            100 => 'C',
            90 => 'XC',
            50 => 'L',
            40 => 'XL',
            10 => 'X',
            9 => 'IX',
            5 => 'V',
            4 => 'IV',
            1 => 'I'
        ];

        $result = '';
        foreach ($romans as $value => $roman) {
            while ($number >= $value) {
                $result .= $roman;
                $number -= $value;
            }
        }
        return $result;
    }

    private function formatDateIndonesian($date = null)
    {
        if ($date === null) {
            $date = date('Y-m-d');
        }

        $bulan = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember'
        ];

        $tanggal = date('j', strtotime($date));
        $bulan_num = date('n', strtotime($date));
        $tahun = date('Y', strtotime($date));

        return $tanggal . ' ' . $bulan[$bulan_num] . ' ' . $tahun;
    }


    public function cetakCV(Request $request)
    {
        $id_do = $request->id_do;

        $query = vwExportDoCv::query();
        $query->where('id_do', $id_do);
        $data_result = $query->get()->toArray();
        // echo "<pre>";
        // print_r($data_result);die;




    	$this->fpdf->SetFont('Arial', '', 12);
        $this->fpdf->AddPage('P', 'A4');

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
		$this->fpdf->Ln(4.2);





        $this->fpdf->SetFont('helvetica', 'B', 16);
        $this->fpdf->Cell(0, 0.7, "Delivery Order", 0, 0, 'C');
		$this->fpdf->Ln(2.4);
        $this->fpdf->Line(1, 5.8, 20, 5.8);
        $this->fpdf->SetLineWidth(0.1);
        $this->fpdf->Line(1, 5.9, 20, 5.9);
        $this->fpdf->SetLineWidth(0);


        $this->fpdf->SetFont('helvetica', 'B', 12);
        $this->fpdf->Cell(4, 0.7, '', 0, 0, 'L');
        $this->fpdf->Cell(6.4, 0.5,$data_result[0]['nama_client'], 0, 0, 'L');
        $this->fpdf->SetFont('helvetica', '', 11);

        $this->fpdf->Cell(1 , 0.5, "Delivery No", 0, 0, 'R');
        $this->fpdf->Cell(5 , 0.5, ": " . $data_result[0]['nomor_do'], 0, 0, 'R');
		$this->fpdf->Ln(0.8);
        $this->fpdf->Cell(10.2, 0.5, "Date", 0, 0, 'R');
        $this->fpdf->Cell(4.8, 0.5, ": " . $this->formatDateIndonesian(), 0, 0, 'R');
		$this->fpdf->Ln(0.4);

        // Move address closer to client name - right after the date
        $this->fpdf->Cell(3.5, 0.7, '', 0, 0, 'L');
        $this->fpdf->Cell(12, 0.5, $data_result[0]['alamat'], 0, 0, 'L');
		$this->fpdf->Ln(1);

        // Continue with Purchase Order information
        $this->fpdf->Cell(12.1, 0.5, "Purchase Order", 0, 0, 'R');
        $this->fpdf->Cell(0.7, 0.5, ":", 0, 0, 'R');
		$this->fpdf->Ln(0.8);
        $this->fpdf->Cell(10.2, 0.5, "Date", 0, 0, 'R');
        $this->fpdf->Cell(2.6, 0.5, ":", 0, 0, 'R');
		$this->fpdf->Ln(1.4);

        // Hitung ruang yang tersedia untuk tabel
        $currentY = $this->fpdf->GetY();
        $pageHeight = $this->fpdf->GetPageHeight();
        $marginBottom = 2.5; // Margin bawah dalam cm
        $signatureHeight = 4; // Tinggi area signature dalam cm
        $shipToHeight = 3; // Tinggi area Ship To dalam cm

        // Ruang yang tersedia untuk tabel
        $availableHeight = $pageHeight - $currentY - $marginBottom - $signatureHeight - $shipToHeight;

        // Hitung tinggi per baris tabel (header + data)
        $rowHeight = 0.6; // Tinggi per baris dalam cm
        $headerHeight = 0.6; // Tinggi header tabel

        // Jumlah baris yang bisa ditampung
        $maxRows = floor($availableHeight / $rowHeight);

        // Jika data terlalu banyak, kurangi spacing dan ukuran font
        $fontSize = 9;
        $lineSpacing = 0.4;

        if (count($data_result) > $maxRows) {
            $fontSize = 8;
            $lineSpacing = 0.3;
            $rowHeight = 0.5;
            $maxRows = floor($availableHeight / $rowHeight);
        }

        $table = new easyTables($this->fpdf, "{2, 17, 2.5, 12, 15}", 'border:1;font-size:' . $fontSize . ';');

        $table->rowStyle('font-style:B;');
        $table->easyCell('NO', 'valign:M;align:C;');
        $table->easyCell('Description', 'valign:M;align:C;');
        $table->easyCell('QTY', 'valign:M;align:C;');
        $table->easyCell('Part No.', 'valign:M;align:C;');
        $table->easyCell('Serial No.', 'valign:M;align:C;');
        $table->printRow();

        $i = 1;
        $grand_total = 0;

        // Batasi jumlah baris yang ditampilkan jika terlalu banyak
        $displayData = array_slice($data_result, 0, $maxRows - 1); // Kurangi 1 untuk header

        foreach ($displayData as $value) {
            $table->easyCell($i++, 'valign:M;align:C;');
            $table->easyCell($value['partnumber_description'], 'valign:M;align:L;');
            $table->easyCell($value['qty'], 'valign:M;align:C;');
            $table->easyCell($value['part_number'], 'valign:M;align:L;');
            $table->easyCell($value['serial_number'], 'valign:M;align:L;');
            $table->printRow();
        }

        // Jika ada data yang tidak ditampilkan, tambahkan catatan
        if (count($data_result) > count($displayData)) {
            $remainingCount = count($data_result) - count($displayData);
            $table->rowStyle('font-style:I;font-size:' . ($fontSize - 1) . ';');
            $table->easyCell('...', 'colspan:5;valign:M;align:C;');
            $table->printRow();
            $table->easyCell('Dan ' . $remainingCount . ' item lainnya', 'colspan:5;valign:M;align:C;');
            $table->printRow();
        }

        // Simpan posisi Y setelah tabel selesai
        $currentY = $this->fpdf->GetY();

        // Tambahkan jarak minimal setelah tabel (dikurangi untuk menghemat ruang)
        $minSpaceAfterTable = 1; // dikurangi dari 2 menjadi 1
        $shipToY = $currentY + $minSpaceAfterTable;

        // Pindah ke posisi Ship To yang dinamis
        $this->fpdf->SetY($shipToY);

        // Gambar garis pemisah
        $this->fpdf->SetLineWidth(0.1);
        $this->fpdf->Line(1, $shipToY, 20, $shipToY);
        $this->fpdf->SetLineWidth(0);
        $this->fpdf->Ln(0.3); // dikurangi dari 0.5

        // Konten Ship To (dikompresi)
        $this->fpdf->SetFont('Arial', 'B', 10); // dikurangi dari 11
        $this->fpdf->Cell(12, 0, "Ship To :", 0, 0, 'L');
        $this->fpdf->Ln(0.6); // dikurangi dari 1
        $this->fpdf->SetFont('Arial', 'B', 11); // dikurangi dari 13
        $this->fpdf->Cell(5, 0.4, $data_result[0]['nama_client'], 0, 0, 'R'); // dikurangi tinggi dari 0.5
        $this->fpdf->Ln(0.6); // dikurangi dari 1

        // Simpan posisi Y setelah Ship To
        $afterShipToY = $this->fpdf->GetY();

        // Cek apakah ada ruang cukup untuk signature di halaman ini
        $signatureHeight = 4.5; // Tinggi yang dibutuhkan untuk section signature
        $pageHeight = 27.7; // Tinggi halaman A4 dalam cm
        $currentY = $this->fpdf->GetY();
        $availableSpace = $pageHeight - $currentY;

        // Jika tidak ada ruang cukup, buat halaman baru
        if ($availableSpace < $signatureHeight) {
            $this->fpdf->AddPage('P', 'A4');
        }

        // Tambahkan jarak minimal sebelum signature (dikurangi)
        $minSpaceBeforeSignature = 0.8; // dikurangi dari 1.5
        $signatureY = $this->fpdf->GetY() + $minSpaceBeforeSignature;

        // Pindah ke posisi signature yang dinamis
        $this->fpdf->SetY($signatureY);

        // Gambar garis pemisah untuk signature
        $this->fpdf->SetLineWidth(0.1);
        $this->fpdf->Line(1, $signatureY, 20, $signatureY);
        $this->fpdf->SetLineWidth(0);
        $this->fpdf->Ln(1.5); // dikurangi dari 2.4

        // Konten signature (dikompresi)
        $this->fpdf->SetFont('Arial', '', 9); // dikurangi dari 11
        $this->fpdf->Cell(8, 0.4, "Received By,", 0, 0, 'L'); // dikurangi tinggi dari 0.5
        $this->fpdf->Cell(0, 0.4, "Shipped By,", 0, 0, 'L');
        $this->fpdf->Cell(0, 0.4, "Approved By,", 0, 0, 'R');
        $this->fpdf->SetFont('Arial', '', 9);

        $this->fpdf->Ln(2.5); // dikurangi dari 3.5
        $this->fpdf->SetFont('Arial', '', 9);
        $this->fpdf->Cell(8, 0.4, "Date : " . $this->formatDateIndonesian($data_result[0]['tgl_pengiriman']), 0, 0, 'L');
        $this->fpdf->Cell(0, 0.4, "Date : " . $this->formatDateIndonesian($data_result[0]['tgl_pengiriman']), 0, 0, 'L');
        $this->fpdf->Cell(0, 0.4, "Date : " . $this->formatDateIndonesian($data_result[0]['tgl_pengiriman']), 0, 0, 'R');
        $this->fpdf->SetFont('Arial', '', 9);
        $this->fpdf->Ln(0.5); // dikurangi dari 1

        $table->endTable(0);

        $this->fpdf->Output();
        exit;

    }
    public function cetakPT(Request $request)
    {
        $id_do = $request->id_do;

        $query = vwExportDoCv::query();
        $query->where('id_do', $id_do);
        $data_result = $query->get()->toArray();
        // echo "<pre>";
        // print_r($data_result);die;




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
		$this->fpdf->Ln(4.2);





        $this->fpdf->SetFont('helvetica', 'B', 16);
        $this->fpdf->Cell(0, 0.7, "Delivery Order", 0, 0, 'C');
		$this->fpdf->Ln(2.4);
        $this->fpdf->Line(1, 5.8, 20, 5.8);
        $this->fpdf->SetLineWidth(0.1);
        $this->fpdf->Line(1, 5.9, 20, 5.9);
        $this->fpdf->SetLineWidth(0);


        $this->fpdf->SetFont('helvetica', 'B', 12);
        $this->fpdf->Cell(4, 0.7, '', 0, 0, 'L');
        $this->fpdf->Cell(6.4, 0.5,$data_result[0]['nama_client'], 0, 0, 'L');
        $this->fpdf->SetFont('helvetica', '', 11);

        $this->fpdf->Cell(1 , 0.5, "Delivery No", 0, 0, 'R');
        $this->fpdf->Cell(5 , 0.5, ": " . $data_result[0]['nomor_do'], 0, 0, 'R');
		$this->fpdf->Ln(0.8);
        $this->fpdf->Cell(10.2, 0.5, "Date", 0, 0, 'R');
        $this->fpdf->Cell(4.8, 0.5, ": " . $this->formatDateIndonesian(), 0, 0, 'R');
		$this->fpdf->Ln(0.4);

        // Move address closer to client name - right after the date
        $this->fpdf->Cell(3.5, 0.7, '', 0, 0, 'L');
        $this->fpdf->Cell(12, 0.5, $data_result[0]['alamat'], 0, 0, 'L');
		$this->fpdf->Ln(1);

        // Continue with Purchase Order information
        $this->fpdf->Cell(12.1, 0.5, "Purchase Order", 0, 0, 'R');
        $this->fpdf->Cell(0.7, 0.5, ":", 0, 0, 'R');
		$this->fpdf->Ln(0.8);
        $this->fpdf->Cell(10.2, 0.5, "Date", 0, 0, 'R');
        $this->fpdf->Cell(2.6, 0.5, ":", 0, 0, 'R');
		$this->fpdf->Ln(1.4);

        // Hitung ruang yang tersedia untuk tabel
        $currentY = $this->fpdf->GetY();
        $pageHeight = $this->fpdf->GetPageHeight();
        $marginBottom = 2.5; // Margin bawah dalam cm
        $signatureHeight = 4; // Tinggi area signature dalam cm
        $shipToHeight = 3; // Tinggi area Ship To dalam cm

        // Ruang yang tersedia untuk tabel
        $availableHeight = $pageHeight - $currentY - $marginBottom - $signatureHeight - $shipToHeight;

        // Hitung tinggi per baris tabel (header + data)
        $rowHeight = 0.6; // Tinggi per baris dalam cm
        $headerHeight = 0.6; // Tinggi header tabel

        // Jumlah baris yang bisa ditampung
        $maxRows = floor($availableHeight / $rowHeight);

        // Jika data terlalu banyak, kurangi spacing dan ukuran font
        $fontSize = 9;
        $lineSpacing = 0.4;

        if (count($data_result) > $maxRows) {
            $fontSize = 8;
            $lineSpacing = 0.3;
            $rowHeight = 0.5;
            $maxRows = floor($availableHeight / $rowHeight);
        }

        $table = new easyTables($this->fpdf, "{2, 17, 2.5, 12, 15}", 'border:1;font-size:' . $fontSize . ';');

        $table->rowStyle('font-style:B;');
        $table->easyCell('NO', 'valign:M;align:C;');
        $table->easyCell('Description', 'valign:M;align:C;');
        $table->easyCell('QTY', 'valign:M;align:C;');
        $table->easyCell('Part No.', 'valign:M;align:C;');
        $table->easyCell('Serial No.', 'valign:M;align:C;');
        $table->printRow();

        $i = 1;
        $grand_total = 0;

        // Batasi jumlah baris yang ditampilkan jika terlalu banyak
        $displayData = array_slice($data_result, 0, $maxRows - 1); // Kurangi 1 untuk header

        foreach ($displayData as $value) {
            $table->easyCell($i++, 'valign:M;align:C;');
            $table->easyCell($value['partnumber_description'], 'valign:M;align:L;');
            $table->easyCell($value['qty'], 'valign:M;align:C;');
            $table->easyCell($value['part_number'], 'valign:M;align:L;');
            $table->easyCell($value['serial_number'], 'valign:M;align:L;');
            $table->printRow();
        }

        // Jika ada data yang tidak ditampilkan, tambahkan catatan
        if (count($data_result) > count($displayData)) {
            $remainingCount = count($data_result) - count($displayData);
            $table->rowStyle('font-style:I;font-size:' . ($fontSize - 1) . ';');
            $table->easyCell('...', 'colspan:5;valign:M;align:C;');
            $table->printRow();
            $table->easyCell('Dan ' . $remainingCount . ' item lainnya', 'colspan:5;valign:M;align:C;');
            $table->printRow();
        }

        // Simpan posisi Y setelah tabel selesai
        $currentY = $this->fpdf->GetY();

         // Tambahkan jarak minimal setelah tabel (dikurangi untuk menghemat ruang)
        $minSpaceAfterTable = 1; // dikurangi dari 2 menjadi 1
        $shipToY = $currentY + $minSpaceAfterTable;

        // Pindah ke posisi Ship To yang dinamis
        $this->fpdf->SetY($shipToY);

        // Gambar garis pemisah
        $this->fpdf->SetLineWidth(0.1);
        $this->fpdf->Line(1, $shipToY, 20, $shipToY);
        $this->fpdf->SetLineWidth(0);
        $this->fpdf->Ln(0.3); // dikurangi dari 0.5

        // Konten Ship To (dikompresi)
        $this->fpdf->SetFont('Arial', 'B', 10); // dikurangi dari 11
        $this->fpdf->Cell(12, 0, "Ship To :", 0, 0, 'L');
        $this->fpdf->Ln(0.6); // dikurangi dari 1
        $this->fpdf->SetFont('Arial', 'B', 11); // dikurangi dari 13
        $this->fpdf->Cell(5, 0.4, $data_result[0]['nama_client'], 0, 0, 'R'); // dikurangi tinggi dari 0.5
        $this->fpdf->Ln(0.6); // dikurangi dari 1

        // Simpan posisi Y setelah Ship To
        $afterShipToY = $this->fpdf->GetY();

        // Cek apakah ada ruang cukup untuk signature di halaman ini
        $signatureHeight = 4.5; // Tinggi yang dibutuhkan untuk section signature
        $pageHeight = 27.7; // Tinggi halaman A4 dalam cm
        $currentY = $this->fpdf->GetY();
        $availableSpace = $pageHeight - $currentY;

        // Jika tidak ada ruang cukup, buat halaman baru
        if ($availableSpace < $signatureHeight) {
            $this->fpdf->AddPage('P', 'A4');
        }

        // Tambahkan jarak minimal sebelum signature (dikurangi)
        $minSpaceBeforeSignature = 0.8; // dikurangi dari 1.5
        $signatureY = $this->fpdf->GetY() + $minSpaceBeforeSignature;

        // Pindah ke posisi signature yang dinamis
        $this->fpdf->SetY($signatureY);

        // Gambar garis pemisah untuk signature
        $this->fpdf->SetLineWidth(0.1);
        $this->fpdf->Line(1, $signatureY, 20, $signatureY);
        $this->fpdf->SetLineWidth(0);
        $this->fpdf->Ln(1.5); // dikurangi dari 2.4

        // Konten signature (dikompresi)
        $this->fpdf->SetFont('Arial', '', 9); // dikurangi dari 11
        $this->fpdf->Cell(8, 0.4, "Received By,", 0, 0, 'L'); // dikurangi tinggi dari 0.5
        $this->fpdf->Cell(0, 0.4, "Shipped By,", 0, 0, 'L');
        $this->fpdf->Cell(0, 0.4, "Approved By,", 0, 0, 'R');
        $this->fpdf->SetFont('Arial', '', 9);

        $this->fpdf->Ln(2.5); // dikurangi dari 3.5
        $this->fpdf->SetFont('Arial', '', 9);
        $this->fpdf->Cell(8, 0.4, "Date : " . $this->formatDateIndonesian($data_result[0]['tgl_pengiriman']), 0, 0, 'L');
        $this->fpdf->Cell(0, 0.4, "Date : " . $this->formatDateIndonesian($data_result[0]['tgl_pengiriman']), 0, 0, 'L');
        $this->fpdf->Cell(0, 0.4, "Date : " . $this->formatDateIndonesian($data_result[0]['tgl_pengiriman']), 0, 0, 'R');
        $this->fpdf->SetFont('Arial', '', 9);
        $this->fpdf->Ln(0.5); // dikurangi dari 1

        $table->endTable(0);

        $this->fpdf->Output();
        exit;

    }



    private function addFooter()
    {
        // Get current Y position
        $currentY = $this->fpdf->GetY();

        // Calculate footer position (bottom of page with some margin)
        $footerY = 26.5; // Adjust this value based on your page layout

        // Only add footer if we have space
        if ($currentY < $footerY) {
            $this->fpdf->SetY($footerY);
        }

        // Add separator line
        $this->fpdf->SetDrawColor(000, 000, 000);
        $this->fpdf->Line(1, $this->fpdf->GetY(), 20, $this->fpdf->GetY());
        $this->fpdf->Ln(0.3);

        // Footer content
        $this->fpdf->SetFont('Arial', '', 11);
        $this->fpdf->SetTextColor(000, 000, 000);

        // Left side - Company info
        $this->fpdf->Cell(10, 0.4, "Workshop", 0, 0, 'L');
        $this->fpdf->Ln(0.4);
        $this->fpdf->Cell(10, 0.4, "Jl. Cilengkrang 2 No.144, Bandung", 0, 0, 'L');


    }

}

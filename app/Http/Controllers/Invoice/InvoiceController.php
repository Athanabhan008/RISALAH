<?php

namespace App\Http\Controllers\Invoice;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\VwPr;
use App\Models\vwGetvendorDetailpr;
use App\Libraries\easyTables;
use App\Libraries\exFPDF;
use App\Models\Invoice;
use App\Models\vwExportInvoiceCv;
use App\Models\VwInvoice;

class InvoiceController extends Controller
{
    public function __construct()
    {
        $this->fpdf = new exFPDF('P', 'cm', 'A4');
    }

    public function index()
    {
        return view('invoice/index',[

            "active" => 'invoice'

        ]);
    }

    public function datatable()
    {
        $draw = request()->get('draw');
        $start = request()->get('start');
        $length = request()->get('length');
        $id_user = request()->get('cmb_nip');

        $user = auth()->user();
        $query = VwInvoice::query();

        // Filter berdasarkan user yang login
        if ($user->role == 'super_admin' || $user->role == 'admin') {
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

    public function getPr()
    {
        $id_sales = request()->get('id_sales');

        $query = VwPr::query();
        $query->where('id_sales', $id_sales);

        // Apply pagination
        $result = $query->get();

        return response()->json([
            'error' => 0,
            'message' => 'Success',
            'data'=> $result
        ]);
    }


    public function getVendor()
    {
        $id_pr = request()->get('id_pr');

        $query = vwGetvendorDetailpr::query();
        $query->where('id_projek', $id_pr);

        // Apply pagination
        $result = $query->get();

        return response()->json([
            'error' => 0,
            'message' => 'Success',
            'data'=> $result
        ]);
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

            $po = new Invoice();
            $po->nomor_invoice = $request->nomor_invoice;
            $po->id_sales = $request->cmb_sales;
            $po->id_pr = $request->cmb_pr;
            $po->terbilang = $request->terbilang;
            $po->alamat = $request->alamat;
            $po->tgl_inv = $request->tgl_inv;
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

            $pr_wapu = Invoice::findOrFail($id);
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
            $pr_wapu = Invoice::findOrFail($id);
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

    public function generateNomorInv()
    {
        try {
            // Ambil invoice terakhir berdasarkan ID terbesar
            $lastInv = Invoice::orderByDesc('id')->first();

            if ($lastInv && $lastInv->nomor_invoice) {
                // Format: INV/MBS/0001/III/2024
                $parts = explode('/', $lastInv->nomor_invoice);
                if (count($parts) >= 4) {
                    // Bagian ke-3 adalah nomor urut
                    $lastNumber = (int)$parts[2];
                    $newNumber = $lastNumber + 1;
                } else {
                    $newNumber = 1;
                }
            } else {
                $newNumber = 1;
            }

            // Format nomor urut 4 digit
            $nomorUrut = str_pad($newNumber, 4, '0', STR_PAD_LEFT);

            // Nama perusahaan default
            $namaPerusahaan = 'MBS';

            // Bulan romawi
            $bulan = date('n');
            $romawi = ['', 'I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII'];
            $bulanRomawi = $romawi[$bulan];

            // Tahun
            $tahun = date('Y');

            // Gabungkan format
            $nomorInv = "INV/{$namaPerusahaan}/{$nomorUrut}/{$bulanRomawi}/{$tahun}";

            return response()->json([
                'success' => true,
                'nomor_invoice' => $nomorInv
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal generate nomor Invoice',
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
        $id_invoice = $request->id_invoice;

        $query = vwExportInvoiceCv::query();
        $query->where('id_invoice', $id_invoice);
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
		$this->fpdf->Ln(4.3);





        $this->fpdf->SetFont('helvetica', 'B', 16);
        $this->fpdf->Cell(0, 0.7, "Invoice", 0, 0, 'C');
		$this->fpdf->Ln(2.4);
        $this->fpdf->Line(1, 5.8, 20, 5.8);
        $this->fpdf->SetLineWidth(0.1);
        $this->fpdf->Line(1, 5.9, 20, 5.9);
        $this->fpdf->SetLineWidth(0);


        $this->fpdf->SetFont('helvetica', 'B', 8);
        $this->fpdf->Cell(0.1, 0.7, '', 0, 0, 'L');
        $this->fpdf->Cell(6.4, 0.5,"Kepada Yth.", 0, 0, 'L');
        $this->fpdf->SetFont('helvetica', '', 11);
        $this->fpdf->Ln(0.4);

        $this->fpdf->SetFont('helvetica', 'B', 7.5);
        $this->fpdf->Cell(0.1, 0.7, '', 0, 0, 'L');
        $this->fpdf->Cell(6.4, 0.5,$data_result[0]['nama_client'], 0, 0, 'L');
        $this->fpdf->SetFont('helvetica', 'B', 7.5);

        $this->fpdf->Cell(4.7 , 0.5, "Invoice No", 0, 0, 'R');
        $this->fpdf->Cell(5 , 0.5, ": " . $data_result[0]['nomor_invoice'], 0, 0, 'R');
		$this->fpdf->Ln(0.5);
        $this->fpdf->Cell(10.4, 0.5, "Date", 0, 0, 'R');
        $this->fpdf->Cell(5.4, 0.5, ": " . $this->formatDateIndonesian($data_result[0]['tgl_inv']), 0, 0, 'R');
		$this->fpdf->Ln(0.7);

        $this->fpdf->Cell(10.7, 0.5, "PO No.", 0, 0, 'R');
        $this->fpdf->Cell(2.6, 0.5, ": ", 0, 0, 'R');
		$this->fpdf->Ln(0.4);

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
        $table = new easyTables($this->fpdf, "{2, 17, 2.5, 12, 15}", 'border:1;font-size:7.9;');

        $table->rowStyle('font-style:B;');
        $table->easyCell('NO', 'valign:M;align:C;');
        $table->easyCell('Description', 'valign:M;align:C;');
        $table->easyCell('QTY', 'valign:M;align:C;');
        $table->easyCell('Unit Price', 'valign:M;align:C;');
        $table->easyCell('Total Price', 'valign:M;align:C;');
        $table->printRow();

        $i = 1;
        $grand_total = 0; // Initialize grand total
        foreach ($data_result as $value) {
            // Hitung grand total
            $total_price_numeric = (float) preg_replace('/[^\d]/', '', $value['total_price']);
            $grand_total += $total_price_numeric;

            $dsc = strlen($value['partnumber_description']);

            if ($this->fpdf->GetY() > 27) {
                if ($dsc > 100) {
                    $table->easyCell($i++, 'valign:M;align:C;');
                    // Bagi menjadi dua bagian
                    $part1 = substr($value['partnumber_description'], 0, 100);
                    $part2 = substr($value['partnumber_description'], 100);

                    // Cetak bagian pertama
                    $table->easyCell($part1, 'valign:M;align:L;');
                    $table->easyCell($value['qty'], 'valign:M;align:C;');
                    $table->easyCell($value['unit_price'], 'valign:M;align:R;');
                    $table->easyCell($value['total_price'], 'valign:M;align:R;');
                    $table->printRow();

                    // Cetak bagian kedua
                    // $this->fpdf->AddPage('P', 'A4');
                    $table->easyCell('', 'valign:M;align:C;');
                    $table->easyCell($part2, 'valign:M;align:L;');
                    $table->easyCell('', 'valign:M;align:C;');
                    $table->easyCell('', 'valign:M;align:C;');
                    $table->easyCell('', 'valign:M;align:C;');
                } else {
                    if ($this->fpdf->GetY() > 25) {
                        if ($dsc > 500) {
                            $table->easyCell($i++, 'valign:M;align:C;');
                            // Bagi menjadi dua bagian
                            $part1 = substr($value['partnumber_description'], 0, 500);
                            $part2 = substr($value['partnumber_description'], 500);

                            // Cetak bagian pertama
                            $table->easyCell($part1, 'valign:M;align:L;');
                            $table->easyCell($value['qty'], 'valign:M;align:C;');
                            $table->easyCell($value['unit_price'], 'valign:M;align:R;');
                            $table->easyCell($value['total_price'], 'valign:M;align:R;');
                            $table->printRow();

                            // Cetak bagian kedua
                            // $this->fpdf->AddPage('P', 'A4');
                            $table->easyCell('', 'valign:M;align:C;');
                            $table->easyCell($part2, 'valign:M;align:L;');
                            $table->easyCell('', 'valign:M;align:C;');
                            $table->easyCell('', 'valign:M;align:C;');
                            $table->easyCell('', 'valign:M;align:C;');
                        } else {
                            $table->easyCell($i++, 'valign:M;align:C;');
                            $table->easyCell($value['partnumber_description'], 'valign:M;align:L;');
                            $table->easyCell($value['qty'], 'valign:M;align:C;');
                            $table->easyCell($value['unit_price'], 'valign:M;align:R;');
                            $table->easyCell($value['total_price'], 'valign:M;align:R;');
                        }

                    } else {
                        if ($this->fpdf->GetY() > 23) {
                            if ($dsc > 900) {
                                $table->easyCell($i++, 'valign:M;align:C;');
                                // Bagi menjadi dua bagian
                                $part1 = substr($value['partnumber_description'], 0, 900);
                                $part2 = substr($value['partnumber_description'], 900);

                                // Cetak bagian pertama
                                $table->easyCell($part1, 'valign:M;align:L;');
                                $table->easyCell($value['qty'], 'valign:M;align:C;');
                                $table->easyCell($value['unit_price'], 'valign:M;align:R;');
                                $table->easyCell($value['total_price'], 'valign:M;align:R;');
                                $table->printRow();

                                // Cetak bagian kedua
                                // $this->fpdf->AddPage('P', 'A4');
                                $table->easyCell('', 'valign:M;align:C;');
                                $table->easyCell($part2, 'valign:M;align:L;');
                                $table->easyCell('', 'valign:M;align:C;');
                                $table->easyCell('', 'valign:M;align:C;');
                                $table->easyCell('', 'valign:M;align:C;');
                            } else {
                                $table->easyCell($i++, 'valign:M;align:C;');
                                $table->easyCell($value['partnumber_description'], 'valign:M;align:L;');
                                $table->easyCell($value['qty'], 'valign:M;align:C;');
                                $table->easyCell($value['unit_price'], 'valign:M;align:R;');
                                $table->easyCell($value['total_price'], 'valign:M;align:R;');
                            }

                        } else {
                            if ($this->fpdf->GetY() > 21) {
                                if ($dsc > 1100) {
                                    $table->easyCell($i++, 'valign:M;align:C;');
                                    // Bagi menjadi dua bagian
                                    $part1 = substr($value['partnumber_description'], 0, 1100);
                                    $part2 = substr($value['partnumber_description'], 1100);

                                    // Cetak bagian pertama
                                    $table->easyCell($part1, 'valign:M;align:L;');
                                    $table->easyCell($value['qty'], 'valign:M;align:C;');
                                    $table->easyCell($value['unit_price'], 'valign:M;align:R;');
                                    $table->easyCell($value['total_price'], 'valign:M;align:R;');
                                    $table->printRow();

                                    // Cetak bagian kedua
                                    // $this->fpdf->AddPage('P', 'A4');
                                    $table->easyCell('', 'valign:M;align:C;');
                                    $table->easyCell($part2, 'valign:M;align:L;');
                                    $table->easyCell('', 'valign:M;align:C;');
                                    $table->easyCell('', 'valign:M;align:C;');
                                    $table->easyCell('', 'valign:M;align:C;');
                                } else {
                                    $table->easyCell($i++, 'valign:M;align:C;');
                                    $table->easyCell($value['partnumber_description'], 'valign:M;align:L;');
                                    $table->easyCell($value['qty'], 'valign:M;align:C;');
                                    $table->easyCell($value['unit_price'], 'valign:M;align:R;');
                                    $table->easyCell($value['total_price'], 'valign:M;align:R;');
                                }

                            } else {
                                if ($this->fpdf->GetY() > 19) {
                                    if ($dsc > 1500) {
                                        $table->easyCell($i++, 'valign:M;align:C;');
                                        // Bagi menjadi dua bagian
                                        $part1 = substr($value['partnumber_description'], 0, 1500);
                                        $part2 = substr($value['partnumber_description'], 1500);

                                        // Cetak bagian pertama
                                        $table->easyCell($part1, 'valign:M;align:L;');
                                        $table->easyCell($value['qty'], 'valign:M;align:C;');
                                        $table->easyCell($value['unit_price'], 'valign:M;align:R;');
                                        $table->easyCell($value['total_price'], 'valign:M;align:R;');
                                        $table->printRow();

                                        // Cetak bagian kedua
                                        // $this->fpdf->AddPage('P', 'A4');
                                        $table->easyCell('', 'valign:M;align:C;');
                                        $table->easyCell($part2, 'valign:M;align:L;');
                                        $table->easyCell('', 'valign:M;align:C;');
                                        $table->easyCell('', 'valign:M;align:C;');
                                        $table->easyCell('', 'valign:M;align:C;');
                                    } else {
                                        $table->easyCell($i++, 'valign:M;align:C;');
                                        $table->easyCell($value['partnumber_description'], 'valign:M;align:L;');
                                        $table->easyCell($value['qty'], 'valign:M;align:C;');
                                        $table->easyCell($value['unit_price'], 'valign:M;align:R;');
                                        $table->easyCell($value['total_price'], 'valign:M;align:R;');
                                    }

                                } else {
                                    if ($this->fpdf->GetY() > 17) {
                                        if ($dsc > 1800) {
                                            $table->easyCell($i++, 'valign:M;align:C;');
                                            // Bagi menjadi dua bagian
                                            $part1 = substr($value['partnumber_description'], 0, 1800);
                                            $part2 = substr($value['partnumber_description'], 1800);

                                            // Cetak bagian pertama
                                            $table->easyCell($part1, 'valign:M;align:L;');
                                            $table->easyCell($value['qty'], 'valign:M;align:C;');
                                            $table->easyCell($value['unit_price'], 'valign:M;align:R;');
                                            $table->easyCell($value['total_price'], 'valign:M;align:R;');
                                            $table->printRow();

                                            // Cetak bagian kedua
                                            // $this->fpdf->AddPage('P', 'A4');
                                            $table->easyCell('', 'valign:M;align:C;');
                                            $table->easyCell($part2, 'valign:M;align:L;');
                                            $table->easyCell('', 'valign:M;align:C;');
                                            $table->easyCell('', 'valign:M;align:C;');
                                            $table->easyCell('', 'valign:M;align:C;');
                                        } else {
                                            $table->easyCell($i++, 'valign:M;align:C;');
                                            $table->easyCell($value['partnumber_description'], 'valign:M;align:L;');
                                            $table->easyCell($value['qty'], 'valign:M;align:C;');
                                            $table->easyCell($value['unit_price'], 'valign:M;align:R;');
                                            $table->easyCell($value['total_price'], 'valign:M;align:R;');
                                        }

                                    } else {
                                        $table->easyCell($i++, 'valign:M;align:C;');
                                        $table->easyCell($value['partnumber_description'], 'valign:M;align:L;');
                                        $table->easyCell($value['qty'], 'valign:M;align:C;');
                                        $table->easyCell($value['unit_price'], 'valign:M;align:R;');
                                        $table->easyCell($value['total_price'], 'valign:M;align:R;');
                                    }
                                }
                            }
                        }
                    }
                }
            } else {
                $table->easyCell($i++, 'valign:M;align:C;');
                $table->easyCell($value['partnumber_description'], 'valign:M;align:L;');
                $table->easyCell($value['qty'], 'valign:M;align:C;');
                $table->easyCell($value['unit_price'], 'valign:M;align:R;');
                $table->easyCell($value['total_price'], 'valign:M;align:R;');
            }


            // Cetak baris
            $table->printRow();
        }

        // End table dan dapatkan posisi Y setelah tabel
        $table->endTable(0);
        $current_y = $this->fpdf->GetY();




        // print_r($this->fpdf->GetY());die;
        if ($this->fpdf->GetY() > 20.56) {
            $this->fpdf->AddPage('P', 'A4');
        }


        $this->fpdf->Ln(1);

        $this->fpdf->SetFont('Arial', 'B', 8);
        $this->fpdf->Cell(14.6, 0, "Subtotal :", 0, 0, 'R');
        $this->fpdf->Cell(3, 0,  number_format($data_result[0]['subtotal_price'], 0, ',', '.'), 0, 0, 'R');
        $this->fpdf->Ln(0.8);

        $this->fpdf->SetFont('Arial', 'B', 8);
        $this->fpdf->Cell(14.6, 0, "PPN 11% :", 0, 0, 'R');
        $this->fpdf->Cell(2.9, 0, number_format($data_result[0]['jumlah_ppn'], 0, ',', ','), 0, 0, 'R');
        $this->fpdf->Ln(0.8);

        $this->fpdf->SetFont('Arial', 'B', 8);
        $this->fpdf->Cell(14.7, 0, "Grand Total :", 0, 0, 'R');
        $this->fpdf->Cell(2.8, 0, number_format($data_result[0]['total_vat'], 0, ',', ','), 0, 0, 'R');
        $this->fpdf->Ln(0.8);



        $this->fpdf->SetFont('Arial', 'B', 10);
        $this->fpdf->Cell(12, 0, "Terbilang :", 0, 0, 'L');
        $this->fpdf->Ln(0.5);

        $this->fpdf->SetFont('Arial', 'BI', 8);
        $this->fpdf->Cell(5, 0.5, $data_result[0]['terbilang'], 0, 0, 'L');
        $this->fpdf->Ln(1);

        $this->fpdf->SetFont('Arial', 'B', 7);
        $this->fpdf->Cell(5, 0.5, "Bank Mandiri Cab. BEC Purnawarman Bandung", 0, 0, 'L');
        $this->fpdf->Ln(0.5);

        $this->fpdf->SetFont('Arial', 'B',7);
        $this->fpdf->Cell(5, 0.5, "A/C: 13000.91.333222", 0, 0, 'L');
        $this->fpdf->Ln(1);


        $this->fpdf->SetFont('Arial', 'BU', 7);
        $this->fpdf->Cell(17, 0.5, "Rika Aulia", 0, 0, 'R');
        $this->fpdf->Ln(0.4); // Increased spacing between name and title
        $this->fpdf->SetFont('Arial', 'B', 7);
        $this->fpdf->Cell(16.9, 0.5, "Finance", 0, 0, 'R');
        $this->fpdf->Ln(1); // Added spacing after signature




        $this->fpdf->Output();
        exit;

    }

    public function cetakPT(Request $request)
    {
        $id_invoice = $request->id_invoice;

        $query = vwExportInvoiceCv::query();
        $query->where('id_invoice', $id_invoice);
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
		$this->fpdf->Ln(4.3);





        $this->fpdf->SetFont('helvetica', 'B', 16);
        $this->fpdf->Cell(0, 0.7, "Invoice", 0, 0, 'C');
		$this->fpdf->Ln(2.4);
        $this->fpdf->Line(1, 5.8, 20, 5.8);
        $this->fpdf->SetLineWidth(0.1);
        $this->fpdf->Line(1, 5.9, 20, 5.9);
        $this->fpdf->SetLineWidth(0);


        $this->fpdf->SetFont('helvetica', 'B', 8);
        $this->fpdf->Cell(0.1, 0.7, '', 0, 0, 'L');
        $this->fpdf->Cell(6.4, 0.5,"Kepada Yth.", 0, 0, 'L');
        $this->fpdf->SetFont('helvetica', '', 11);
        $this->fpdf->Ln(0.4);

        $this->fpdf->SetFont('helvetica', 'B', 7.5);
        $this->fpdf->Cell(0.1, 0.7, '', 0, 0, 'L');
        $this->fpdf->Cell(6.4, 0.5,$data_result[0]['nama_client'], 0, 0, 'L');
        $this->fpdf->SetFont('helvetica', 'B', 7.5);

        $this->fpdf->Cell(4.7 , 0.5, "Invoice No", 0, 0, 'R');
        $this->fpdf->Cell(5 , 0.5, ": " . $data_result[0]['nomor_invoice'], 0, 0, 'R');
		$this->fpdf->Ln(0.5);
        $this->fpdf->Cell(10.4, 0.5, "Date", 0, 0, 'R');
        $this->fpdf->Cell(5.4, 0.5, ": " . $this->formatDateIndonesian($data_result[0]['tgl_inv']), 0, 0, 'R');
		$this->fpdf->Ln(0.7);

        $this->fpdf->Cell(10.7, 0.5, "PO No.", 0, 0, 'R');
        $this->fpdf->Cell(2.6, 0.5, ": ", 0, 0, 'R');
		$this->fpdf->Ln(0.4);

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
        $table = new easyTables($this->fpdf, "{2, 17, 2.5, 12, 15}", 'border:1;font-size:7.9;');

        $table->rowStyle('font-style:B;');
        $table->easyCell('NO', 'valign:M;align:C;');
        $table->easyCell('Description', 'valign:M;align:C;');
        $table->easyCell('QTY', 'valign:M;align:C;');
        $table->easyCell('Unit Price.', 'valign:M;align:C;');
        $table->easyCell('Total Price', 'valign:M;align:C;');
        $table->printRow();

        $i = 1;
        $grand_total = 0; // Initialize grand total
        foreach ($data_result as $value) {
            // Hitung grand total
            $total_price_numeric = (float) preg_replace('/[^\d]/', '', $value['total_price']);
            $grand_total += $total_price_numeric;

            $dsc = strlen($value['partnumber_description']);

            if ($this->fpdf->GetY() > 27) {
                if ($dsc > 100) {
                    $table->easyCell($i++, 'valign:M;align:C;');
                    // Bagi menjadi dua bagian
                    $part1 = substr($value['partnumber_description'], 0, 100);
                    $part2 = substr($value['partnumber_description'], 100);

                    // Cetak bagian pertama
                    $table->easyCell($part1, 'valign:M;align:L;');
                    $table->easyCell($value['qty'], 'valign:M;align:C;');
                    $table->easyCell($value['unit_price'], 'valign:M;align:R;');
                    $table->easyCell($value['total_price'], 'valign:M;align:R;');
                    $table->printRow();

                    // Cetak bagian kedua
                    // $this->fpdf->AddPage('P', 'A4');
                    $table->easyCell('', 'valign:M;align:C;');
                    $table->easyCell($part2, 'valign:M;align:L;');
                    $table->easyCell('', 'valign:M;align:C;');
                    $table->easyCell('', 'valign:M;align:C;');
                    $table->easyCell('', 'valign:M;align:C;');
                } else {
                    if ($this->fpdf->GetY() > 25) {
                        if ($dsc > 500) {
                            $table->easyCell($i++, 'valign:M;align:C;');
                            // Bagi menjadi dua bagian
                            $part1 = substr($value['partnumber_description'], 0, 500);
                            $part2 = substr($value['partnumber_description'], 500);

                            // Cetak bagian pertama
                            $table->easyCell($part1, 'valign:M;align:L;');
                            $table->easyCell($value['qty'], 'valign:M;align:C;');
                            $table->easyCell($value['unit_price'], 'valign:M;align:R;');
                            $table->easyCell($value['total_price'], 'valign:M;align:R;');
                            $table->printRow();

                            // Cetak bagian kedua
                            // $this->fpdf->AddPage('P', 'A4');
                            $table->easyCell('', 'valign:M;align:C;');
                            $table->easyCell($part2, 'valign:M;align:L;');
                            $table->easyCell('', 'valign:M;align:C;');
                            $table->easyCell('', 'valign:M;align:C;');
                            $table->easyCell('', 'valign:M;align:C;');
                        } else {
                            $table->easyCell($i++, 'valign:M;align:C;');
                            $table->easyCell($value['partnumber_description'], 'valign:M;align:L;');
                            $table->easyCell($value['qty'], 'valign:M;align:C;');
                            $table->easyCell($value['unit_price'], 'valign:M;align:R;');
                            $table->easyCell($value['total_price'], 'valign:M;align:R;');
                        }

                    } else {
                        if ($this->fpdf->GetY() > 23) {
                            if ($dsc > 900) {
                                $table->easyCell($i++, 'valign:M;align:C;');
                                // Bagi menjadi dua bagian
                                $part1 = substr($value['partnumber_description'], 0, 900);
                                $part2 = substr($value['partnumber_description'], 900);

                                // Cetak bagian pertama
                                $table->easyCell($part1, 'valign:M;align:L;');
                                $table->easyCell($value['qty'], 'valign:M;align:C;');
                                $table->easyCell($value['unit_price'], 'valign:M;align:R;');
                                $table->easyCell($value['total_price'], 'valign:M;align:R;');
                                $table->printRow();

                                // Cetak bagian kedua
                                // $this->fpdf->AddPage('P', 'A4');
                                $table->easyCell('', 'valign:M;align:C;');
                                $table->easyCell($part2, 'valign:M;align:L;');
                                $table->easyCell('', 'valign:M;align:C;');
                                $table->easyCell('', 'valign:M;align:C;');
                                $table->easyCell('', 'valign:M;align:C;');
                            } else {
                                $table->easyCell($i++, 'valign:M;align:C;');
                                $table->easyCell($value['partnumber_description'], 'valign:M;align:L;');
                                $table->easyCell($value['qty'], 'valign:M;align:C;');
                                $table->easyCell($value['unit_price'], 'valign:M;align:R;');
                                $table->easyCell($value['total_price'], 'valign:M;align:R;');
                            }

                        } else {
                            if ($this->fpdf->GetY() > 21) {
                                if ($dsc > 1100) {
                                    $table->easyCell($i++, 'valign:M;align:C;');
                                    // Bagi menjadi dua bagian
                                    $part1 = substr($value['partnumber_description'], 0, 1100);
                                    $part2 = substr($value['partnumber_description'], 1100);

                                    // Cetak bagian pertama
                                    $table->easyCell($part1, 'valign:M;align:L;');
                                    $table->easyCell($value['qty'], 'valign:M;align:C;');
                                    $table->easyCell($value['unit_price'], 'valign:M;align:R;');
                                    $table->easyCell($value['total_price'], 'valign:M;align:R;');
                                    $table->printRow();

                                    // Cetak bagian kedua
                                    // $this->fpdf->AddPage('P', 'A4');
                                    $table->easyCell('', 'valign:M;align:C;');
                                    $table->easyCell($part2, 'valign:M;align:L;');
                                    $table->easyCell('', 'valign:M;align:C;');
                                    $table->easyCell('', 'valign:M;align:C;');
                                    $table->easyCell('', 'valign:M;align:C;');
                                } else {
                                    $table->easyCell($i++, 'valign:M;align:C;');
                                    $table->easyCell($value['partnumber_description'], 'valign:M;align:L;');
                                    $table->easyCell($value['qty'], 'valign:M;align:C;');
                                    $table->easyCell($value['unit_price'], 'valign:M;align:R;');
                                    $table->easyCell($value['total_price'], 'valign:M;align:R;');
                                }

                            } else {
                                if ($this->fpdf->GetY() > 19) {
                                    if ($dsc > 1500) {
                                        $table->easyCell($i++, 'valign:M;align:C;');
                                        // Bagi menjadi dua bagian
                                        $part1 = substr($value['partnumber_description'], 0, 1500);
                                        $part2 = substr($value['partnumber_description'], 1500);

                                        // Cetak bagian pertama
                                        $table->easyCell($part1, 'valign:M;align:L;');
                                        $table->easyCell($value['qty'], 'valign:M;align:C;');
                                        $table->easyCell($value['unit_price'], 'valign:M;align:R;');
                                        $table->easyCell($value['total_price'], 'valign:M;align:R;');
                                        $table->printRow();

                                        // Cetak bagian kedua
                                        // $this->fpdf->AddPage('P', 'A4');
                                        $table->easyCell('', 'valign:M;align:C;');
                                        $table->easyCell($part2, 'valign:M;align:L;');
                                        $table->easyCell('', 'valign:M;align:C;');
                                        $table->easyCell('', 'valign:M;align:C;');
                                        $table->easyCell('', 'valign:M;align:C;');
                                    } else {
                                        $table->easyCell($i++, 'valign:M;align:C;');
                                        $table->easyCell($value['partnumber_description'], 'valign:M;align:L;');
                                        $table->easyCell($value['qty'], 'valign:M;align:C;');
                                        $table->easyCell($value['unit_price'], 'valign:M;align:R;');
                                        $table->easyCell($value['total_price'], 'valign:M;align:R;');
                                    }

                                } else {
                                    if ($this->fpdf->GetY() > 17) {
                                        if ($dsc > 1800) {
                                            $table->easyCell($i++, 'valign:M;align:C;');
                                            // Bagi menjadi dua bagian
                                            $part1 = substr($value['partnumber_description'], 0, 1800);
                                            $part2 = substr($value['partnumber_description'], 1800);

                                            // Cetak bagian pertama
                                            $table->easyCell($part1, 'valign:M;align:L;');
                                            $table->easyCell($value['qty'], 'valign:M;align:C;');
                                            $table->easyCell($value['unit_price'], 'valign:M;align:R;');
                                            $table->easyCell($value['total_price'], 'valign:M;align:R;');
                                            $table->printRow();

                                            // Cetak bagian kedua
                                            // $this->fpdf->AddPage('P', 'A4');
                                            $table->easyCell('', 'valign:M;align:C;');
                                            $table->easyCell($part2, 'valign:M;align:L;');
                                            $table->easyCell('', 'valign:M;align:C;');
                                            $table->easyCell('', 'valign:M;align:C;');
                                            $table->easyCell('', 'valign:M;align:C;');
                                        } else {
                                            $table->easyCell($i++, 'valign:M;align:C;');
                                            $table->easyCell($value['partnumber_description'], 'valign:M;align:L;');
                                            $table->easyCell($value['qty'], 'valign:M;align:C;');
                                            $table->easyCell($value['unit_price'], 'valign:M;align:R;');
                                            $table->easyCell($value['total_price'], 'valign:M;align:R;');
                                        }

                                    } else {
                                        $table->easyCell($i++, 'valign:M;align:C;');
                                        $table->easyCell($value['partnumber_description'], 'valign:M;align:L;');
                                        $table->easyCell($value['qty'], 'valign:M;align:C;');
                                        $table->easyCell($value['unit_price'], 'valign:M;align:R;');
                                        $table->easyCell($value['total_price'], 'valign:M;align:R;');
                                    }
                                }
                            }
                        }
                    }
                }
            } else {
                $table->easyCell($i++, 'valign:M;align:C;');
                $table->easyCell($value['partnumber_description'], 'valign:M;align:L;');
                $table->easyCell($value['qty'], 'valign:M;align:C;');
                $table->easyCell($value['unit_price'], 'valign:M;align:R;');
                $table->easyCell($value['total_price'], 'valign:M;align:R;');
            }


            // Cetak baris
            $table->printRow();
        }

        // End table dan dapatkan posisi Y setelah tabel
        $table->endTable(0);
        $current_y = $this->fpdf->GetY();




        // print_r($this->fpdf->GetY());die;
        if ($this->fpdf->GetY() > 20.56) {
            $this->fpdf->AddPage('P', 'A4');
        }


        $this->fpdf->Ln(1);

        $this->fpdf->SetFont('Arial', 'B', 8);
        $this->fpdf->Cell(17, 0, "Subtotal :", 0, 0, 'R');
        $this->fpdf->Cell(2.1, 0,  number_format($data_result[0]['subtotal_price'], 0, ',', '.'), 0, 0, 'R');
        $this->fpdf->Ln(0.8);

        $this->fpdf->SetFont('Arial', 'B', 8);
        $this->fpdf->Cell(17, 0, "PPN 11% :", 0, 0, 'R');
        $this->fpdf->Cell(2.1, 0, number_format($data_result[0]['jumlah_ppn'], 0, ',', ','), 0, 0, 'R');
        $this->fpdf->Ln(0.8);

        $this->fpdf->SetFont('Arial', 'B', 8);
        $this->fpdf->Cell(17, 0, "Grand Total :", 0, 0, 'R');
        $this->fpdf->Cell(2.1, 0, number_format($data_result[0]['total_vat'], 0, ',', ','), 0, 0, 'R');
        $this->fpdf->Ln(0.8);



        $this->fpdf->SetFont('Arial', 'B', 10);
        $this->fpdf->Cell(12, 0, "Terbilang :", 0, 0, 'L');
        $this->fpdf->Ln(0.5);

        $this->fpdf->SetFont('Arial', 'BI', 8);
        $this->fpdf->Cell(5, 0.5, $data_result[0]['terbilang'], 0, 0, 'L');
        $this->fpdf->Ln(1);

        $this->fpdf->SetFont('Arial', 'B', 7);
        $this->fpdf->Cell(5, 0.5, "Bank Central Asia / BCA", 0, 0, 'L');
        $this->fpdf->Ln(0.5);

        $this->fpdf->SetFont('Arial', 'B',7);
        $this->fpdf->Cell(5, 0.5, "A/C: 8090.501.616", 0, 0, 'L');
        $this->fpdf->Ln(0.5);

        $this->fpdf->SetFont('Arial', 'B',7);
        $this->fpdf->Cell(5, 0.5, "Mitra Bisnis Sopyan PT", 0, 0, 'L');
        $this->fpdf->Ln(1);


        $this->fpdf->SetFont('Arial', 'BU', 7);
        $this->fpdf->Cell(17, 0.5, "Rika Aulia", 0, 0, 'R');
        $this->fpdf->Ln(0.4); // Increased spacing between name and title
        $this->fpdf->SetFont('Arial', 'B', 7);
        $this->fpdf->Cell(16.9, 0.5, "Finance", 0, 0, 'R');
        $this->fpdf->Ln(1); // Added spacing after signature




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


    }

}

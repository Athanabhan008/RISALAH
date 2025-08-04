<?php

namespace App\Http\Controllers\PO;


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
use App\Models\VwPo;
use App\Models\VwPrwapudetail;
use App\Models\Wapu;
use App\Models\vwExportPoCv;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Libraries\easyTables;
use App\Libraries\exFPDF;

class PoController extends Controller
{
    public function __construct()
    {
        $this->fpdf = new exFPDF('P', 'cm', 'A4');
    }

    public function index()
    {
        return view('po/index',[

            "active" => 'po'

        ]);
    }

    public function datatable()
    {
        $draw = request()->get('draw');
        $start = request()->get('start');
        $length = request()->get('length');
        $id_user = request()->get('cmb_nip');

        $user = auth()->user();
        $query = VwPo::query();

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

            $po = new Po();
            $po->nomor_po = $request->nomor_po;
            $po->id_sales = $request->cmb_sales;
            $po->id_pr = $request->cmb_pr;
            $po->id_vendor = $request->cmb_vendor;
            $po->lampiran = $request->lampiran;
            $po->sales_vendor = $request->sales_vendor;
            $po->note = $request->note;
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

    public function generateNomorPO()
    {
        try {
            // Ambil nomor PO terakhir dari semua data (tanpa filter id_sales)
            $lastPo = Po::orderByDesc('id')->first();

            if ($lastPo && $lastPo->nomor_po) {
                // Ambil angka dari nomor_po terakhir, format: PO-0001/MBS/III/2024
                // Pisahkan dengan explode, ambil bagian ke-1 (0001)
                $parts = explode('/', $lastPo->nomor_po);
                if (count($parts) >= 2) {
                    // Ambil bagian pertama setelah "PO-"
                    $firstPart = str_replace('PO-', '', $parts[0]);
                    $lastNumber = (int)$firstPart;
                    $newNumber = $lastNumber + 1;
                } else {
                    $newNumber = 1;
                }
            } else {
                $newNumber = 1;
            }

            // Format nomor PO berurut 4 digit
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
            $nomorPo = "PO-{$nomorUrut}/{$namaPerusahaan}/{$bulanRomawi}/{$tahun}";

            return response()->json([
                'success' => true,
                'nomor_po' => $nomorPo
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal generate nomor PO',
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


        public function detail_data_prwapu(Request $request)
        {
            try {
                // Panggil multiple SP
                $subtotal = DB::select("CALL sp_subtotal(?)", [$request->id_prwapu]);
                $subtotal_cogs = DB::select("CALL sp_subtotal_cogs(?)", [$request->id_prwapu]);

                $prwapu = Wapu::findOrFail($request->id_prwapu);

                // Ambil pph_bank_fee dari tabel cogs, bukan dari prwapus
                $cogs = Cogs::where('id_prwapu', $request->id_prwapu)->first();
                $pph_bank_fee = $cogs ? $cogs->pph_bank_fee : null;

                // Jika pph_bank_fee kosong, hitung berdasarkan validasi_payment dan subtotal_price
                if (!$pph_bank_fee && $prwapu->validasi_payment && $prwapu->subtotal_price) {
                    $pph_bank_fee = $prwapu->subtotal_price - $prwapu->validasi_payment;
                }

                // Hitung subtotal_price dari data prwapu_detail
                $subtotalPrice = 0;
                $prwapuDetails = PrwapuDetail::where('id_prwapu', $request->id_prwapu)->get();
                foreach ($prwapuDetails as $detail) {
                    $totalPrice = (int) preg_replace('/[^\d]/', '', $detail->total_price);
                    $subtotalPrice += $totalPrice;
                }

                // Hitung subtotal_cost dari data prwapu_detail dan cogs
                $subtotalCost = 0;
                foreach ($prwapuDetails as $detail) {
                    $totalCost = (int) preg_replace('/[^\d]/', '', $detail->total_cost);
                    $subtotalCost += $totalCost;
                }

                // Tambahkan total COGS
                if ($cogs) {
                    $subtotalCost += ($cogs->expedittion ?? 0) +
                                    ($cogs->add_insentif_fe001a ?? 0) +
                                    ($cogs->instalasi_setting ?? 0) +
                                    ($cogs->pph_bank_fee ?? 0) +
                                    ($cogs->other ?? 0);
                }

                // Hitung incentive_sales berdasarkan logika yang sama dengan frontend
                $incentive_sales = 0;
                if ($subtotalPrice > 0 && $subtotalCost > 0) {
                    $subtotalSP2D = $subtotalPrice - $subtotalCost;

                    if ($subtotalCost != 0) {
                        $totalMarginPercent = ($subtotalSP2D / $subtotalCost) * 100;

                        // Kondisi perhitungan incentive sales berdasarkan total_margin
                        if ($totalMarginPercent < 10) {
                            // Jika total_margin < 10%, maka subtotal_sp2d * 6.25%
                            $incentive_sales = $subtotalSP2D * 0.0625;
                        } else if ($totalMarginPercent >= 10 && $totalMarginPercent < 15) {
                            // Jika total_margin >= 10% dan < 15%, maka subtotal_sp2d * 12.5%
                            $incentive_sales = $subtotalSP2D * 0.125;
                        } else if ($totalMarginPercent >= 15 && $totalMarginPercent < 20) {
                            // Jika total_margin >= 15% dan < 20%, maka subtotal_sp2d * 15%
                            $incentive_sales = $subtotalSP2D * 0.15;
                        } else if ($totalMarginPercent >= 20) {
                            // Jika total_margin >= 20%, maka subtotal_sp2d * 20%
                            $incentive_sales = $subtotalSP2D * 0.20;
                        }
                    }
                }

                return view('pr.detail_data_prwapu', [
                    'active'=> 'pr_wapu',
                    'id_prwapu' => $request->id_prwapu,
                    'subtotal' => $subtotal,
                    'subtotal_cogs' => $subtotal_cogs,
                    'validasi_payment' => $prwapu->validasi_payment ?? '',
                    'pph_bank_fee' => $pph_bank_fee ?? '',
                    'incentive_sales' => $incentive_sales ?? 0,
                ]);
            } catch (\Exception $e) {
                return redirect()->back()->with('error', 'Gagal memuat data: ' . $e->getMessage());
            }
        }

    public function datatabledetail(Request $request,)
    {
        $draw = request()->get('draw');
        $start = request()->get('start');
        $length = request()->get('length');
        $id_prwapu = request()->get('id_prwapu');

        $query = VwPrwapudetail::query();
        $query->where('id_prwapu', $id_prwapu);

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

    public function detailCreate(Request $request)
    {
        $user = auth()->user();

        try {
            $pr_wapu = new PrwapuDetail();
            $pr_wapu->id_prwapu = $request->id_prwapu;
            $pr_wapu->jenis_ppn = $request->jenis_ppn;
            $pr_wapu->partnumber_description = $request->partnumber_description;
            $pr_wapu->vendor = $request->vendor;
            $pr_wapu->unit_price = $request->unit_price;
            $pr_wapu->total_price = $request->total_price;
            $pr_wapu->qty = $request->qty;
            $pr_wapu->vendor_price = $request->vendor_price;
            $pr_wapu->unit_price_cv = $request->unit_price_cv;
            $pr_wapu->total_po_cv = $request->total_po_cv;
            $pr_wapu->total_cost = $request->total_cost;
            $pr_wapu->margin = $request->margin;
            $pr_wapu->persentase = $request->persentase;
            $pr_wapu->updated_at = null;
            $pr_wapu->save();

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil disimpan',
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
                'message' => 'Gagal menyimpan data',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function detailUpdate(Request $request)
    {
        try {
            $pr_wapu = PrwapuDetail::findOrFail($request->id);
            $pr_wapu->jenis_ppn = $request->jenis_ppn;
            $pr_wapu->partnumber_description = $request->partnumber_description;
            $pr_wapu->vendor = $request->vendor;
            $pr_wapu->unit_price = $request->unit_price;
            $pr_wapu->total_price = $request->total_price;
            $pr_wapu->qty = $request->qty;
            $pr_wapu->vendor_price = $request->vendor_price;
            $pr_wapu->unit_price_cv = $request->unit_price_cv;
            $pr_wapu->total_po_cv = $request->total_po_cv;
            $pr_wapu->total_cost = $request->total_cost;
            $pr_wapu->margin = $request->margin;
            $pr_wapu->persentase = $request->persentase;
            $pr_wapu->save();

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil diupdate',
                'data' => $pr_wapu
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengupdate data',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function deletedetail($id)
    {
        try {
            $pr_wapu = PrwapuDetail::findOrFail($id);
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

    public function getSubtotal(Request $request)
    {
        try {
            $vid = $request->get('vid');
            $subtotal = DB::select("CALL sp_subtotal(?)", [$vid]);

            return response()->json([
                'success' => true,
                'data' => $subtotal
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil subtotal',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function datatabledetailcogs(Request $request,)
    {
        $draw = request()->get('draw');
        $start = request()->get('start');
        $length = request()->get('length');
        $id_prwapu = request()->get('id_prwapu');

        $query = Vwcogs::query();
        $query->where('id_prwapu', $id_prwapu);

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

    public function createCogs(Request $request)
    {
        $user = auth()->user();

        try {
            $pr_wapu = new Cogs();
            $pr_wapu->id_prwapu = $request->id_prwapu;
            $pr_wapu->expedittion = $request->expedittion;
            $pr_wapu->add_insentif_fe001a = $request->add_insentif_fe001a;
            $pr_wapu->instalasi_setting = $request->instalasi_setting;
            $pr_wapu->other = $request->other;
            $pr_wapu->updated_at = null;
            $pr_wapu->save();

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil disimpan',
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
                'message' => 'Gagal menyimpan data',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function updateTotalPpn(Request $request)
    {
        $request->validate([
            'id_prwapu' => 'required|exists:prwapus,id',
        ]);

        // Hilangkan format Rp dan titik pada input, pastikan hasilnya integer
        $subtotal_price = (int) preg_replace('/[^\d]/', '', $request->subtotal_price);
        $validasi_payment = (int) preg_replace('/[^\d]/', '', $request->validasi_payment);
        $jumlah_ppn = (int) preg_replace('/[^\d]/', '', $request->jumlah_ppn);
        $total_vat = (int) preg_replace('/[^\d]/', '', $request->total_vat);

        $prwapu = Wapu::findOrFail($request->id_prwapu);
        $prwapu->subtotal_price = $subtotal_price;
        $prwapu->validasi_payment = $validasi_payment;
        $prwapu->jumlah_ppn = $jumlah_ppn;
        $prwapu->total_vat = $total_vat;
        $prwapu->save();

        // Jika ingin redirect (seperti sekarang)
        return redirect()->back()->with('success', 'Data berhasil diupdate!');

        // Jika ingin response JSON (uncomment jika perlu)
        /*
        return response()->json([
            'success' => true,
            'message' => 'Data berhasil diupdate!',
            'data' => [
                'total_po_ppn' => $prwapu->total_po_ppn,
                'total_cost_ppn' => $prwapu->total_cost_ppn,
                'total_margin_ppn' => $prwapu->total_margin_ppn,
            ]
        ]);
        */
    }



    public function updateTotalPO(Request $request)
    {
        $request->validate([
            'id_prwapu' => 'required|exists:prwapus,id',
            'total_po_ppn' => 'required',
            'total_cost_ppn' => 'required',
            'total_margin_ppn' => 'required',
        ]);

        // Hilangkan format Rp dan titik pada input, pastikan hasilnya integer
        $total_po_ppn = (int) preg_replace('/[^\d]/', '', $request->total_po_ppn);
        $total_cost_ppn = (int) preg_replace('/[^\d]/', '', $request->total_cost_ppn);
        $total_margin_ppn = (int) preg_replace('/[^\d]/', '', $request->total_margin_ppn);
        $total_po_non_ppn = (int) preg_replace('/[^\d]/', '', $request->total_po_non_ppn);
        $total_cost_non_ppn = (int) preg_replace('/[^\d]/', '', $request->total_cost_non_ppn);
        $total_margin_non_ppn = (int) preg_replace('/[^\d]/', '', $request->total_margin_non_ppn);
        $subtotal_po_cv = (int) preg_replace('/[^\d]/', '', $request->subtotal_po_cv);
        $subtotal_po_cost_cv = (int) preg_replace('/[^\d]/', '', $request->subtotal_po_cost_cv);
        $subtotal_margin_cv = (int) preg_replace('/[^\d]/', '', $request->subtotal_margin_cv);
        $subtotal_persentase_cv = (int) preg_replace('/[^\d]/', '', $request->subtotal_persentase_cv);

        $prwapu = Wapu::findOrFail($request->id_prwapu);
        $prwapu->total_po_ppn = $total_po_ppn;
        $prwapu->total_cost_ppn = $total_cost_ppn;
        $prwapu->total_margin_ppn = $total_margin_ppn;
        $prwapu->total_po_non_ppn = $total_po_non_ppn;
        $prwapu->total_cost_non_ppn = $total_cost_non_ppn;
        $prwapu->total_margin_non_ppn = $total_margin_non_ppn;
        $prwapu->subtotal_po_cv = $subtotal_po_cv;
        $prwapu->subtotal_po_cost_cv = $subtotal_po_cost_cv;
        $prwapu->subtotal_margin_cv = $subtotal_margin_cv;
        $prwapu->subtotal_persentase_cv = $subtotal_persentase_cv;
        $prwapu->save();

        // Jika ingin redirect (seperti sekarang)
        return redirect()->back()->with('success', 'Data berhasil diupdate!');

        // Jika ingin response JSON (uncomment jika perlu)
        /*
        return response()->json([
            'success' => true,
            'message' => 'Data berhasil diupdate!',
            'data' => [
                'total_po_ppn' => $prwapu->total_po_ppn,
                'total_cost_ppn' => $prwapu->total_cost_ppn,
                'total_margin_ppn' => $prwapu->total_margin_ppn,
            ]
        ]);
        */
    }




    public function updateValidasi(Request $request)
    {
        $request->validate([
            'id_prwapu' => 'required|exists:prwapus,id',
            'total_po_ppn' => 'required',
            'total_cost_ppn' => 'required',
            'total_margin_ppn' => 'required',
        ]);

        // Hilangkan format Rp dan titik pada input, pastikan hasilnya integer
        $pph_bank_fee = (int) preg_replace('/[^\d]/', '', $request->pph_bank_fee);

        $prwapu = Wapu::findOrFail($request->id_prwapu);
        $prwapu->pph_bank_fee = $pph_bank_fee;

        $prwapu->save();

        // Jika ingin redirect (seperti sekarang)
        return redirect()->back()->with('success', 'Data berhasil diupdate!');

        // Jika ingin response JSON (uncomment jika perlu)
        /*
        return response()->json([
            'success' => true,
            'message' => 'Data berhasil diupdate!',
            'data' => [
                'total_po_ppn' => $prwapu->total_po_ppn,
                'total_cost_ppn' => $prwapu->total_cost_ppn,
                'total_margin_ppn' => $prwapu->total_margin_ppn,
            ]
        ]);
        */
    }

    public function updateValidasiPayment(Request $request)
    {
        try {
            $request->validate([
                'id_prwapu' => 'required|exists:prwapus,id',
                'validasi_payment' => 'nullable',
                'pph_bank_fee' => 'nullable',
            ]);

            // Clean and parse the input values
            $validasiPayment = $request->validasi_payment;
            $pphBankFee = $request->pph_bank_fee;

            // Remove Rp and dots from validasi_payment if it's not empty
            if (!empty($validasiPayment) && $validasiPayment !== '-') {
                $validasiPayment = (int) preg_replace('/[^\d]/', '', $validasiPayment);
            } else {
                $validasiPayment = null;
            }

            // Remove Rp and dots from pph_bank_fee if it's not empty
            if (!empty($pphBankFee) && $pphBankFee !== '-') {
                $pphBankFee = (int) preg_replace('/[^\d]/', '', $pphBankFee);
            } else {
                $pphBankFee = null;
            }

            // Update prwapus table (validasi_payment)
            $prwapu = Wapu::findOrFail($request->id_prwapu);
            $prwapu->validasi_payment = $validasiPayment;
            $prwapu->save();

            // Update cogs table (pph_bank_fee)
            $cogs = Cogs::where('id_prwapu', $request->id_prwapu)->first();
            if ($cogs) {
                $cogs->pph_bank_fee = $pphBankFee;
                $cogs->save();
            }

            // Hitung subtotal_price dari data prwapu_detail
            $subtotalPrice = 0;
            $prwapuDetails = PrwapuDetail::where('id_prwapu', $request->id_prwapu)->get();
            foreach ($prwapuDetails as $detail) {
                $totalPrice = (int) preg_replace('/[^\d]/', '', $detail->total_price);
                $subtotalPrice += $totalPrice;
            }

            // Hitung subtotal_cost dari data prwapu_detail dan cogs
            $subtotalCost = 0;
            foreach ($prwapuDetails as $detail) {
                $totalCost = (int) preg_replace('/[^\d]/', '', $detail->total_cost);
                $subtotalCost += $totalCost;
            }

            // Tambahkan total COGS
            if ($cogs) {
                $subtotalCost += ($cogs->expedittion ?? 0) +
                                ($cogs->add_insentif_fe001a ?? 0) +
                                ($cogs->instalasi_setting ?? 0) +
                                ($cogs->pph_bank_fee ?? 0) +
                                ($cogs->other ?? 0);
            }

            // Hitung incentive_sales berdasarkan logika yang ada di frontend
            $incentiveSales = 0;
            if ($subtotalPrice > 0 && $subtotalCost > 0) {
                $subtotalSP2D = $subtotalPrice - $subtotalCost;

                if ($subtotalCost != 0) {
                    $totalMarginPercent = ($subtotalSP2D / $subtotalCost) * 100;

                    // Kondisi perhitungan incentive sales berdasarkan total_margin
                    if ($totalMarginPercent < 10) {
                        // Jika total_margin < 10%, maka subtotal_sp2d * 6.25%
                        $incentiveSales = $subtotalSP2D * 0.0625;
                    } else if ($totalMarginPercent >= 10 && $totalMarginPercent < 15) {
                        // Jika total_margin >= 10% dan < 15%, maka subtotal_sp2d * 12.5%
                        $incentiveSales = $subtotalSP2D * 0.125;
                    } else if ($totalMarginPercent >= 15 && $totalMarginPercent < 20) {
                        // Jika total_margin >= 15% dan < 20%, maka subtotal_sp2d * 15%
                        $incentiveSales = $subtotalSP2D * 0.15;
                    } else if ($totalMarginPercent >= 20) {
                        // Jika total_margin >= 20%, maka subtotal_sp2d * 20%
                        $incentiveSales = $subtotalSP2D * 0.20;
                    }
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil diupdate!',
                'validasi_payment' => $validasiPayment,
                'pph_bank_fee' => $pphBankFee,
                'incentive_sales' => $incentiveSales
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengupdate data',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getTotalCogs(Request $request)
    {
        try {
            $id_prwapu = $request->input('id_prwapu');

            if (!$id_prwapu) {
                return response()->json(['total_cogs' => 0]);
            }

            $result = DB::select("CALL sp_subtotal_cogs(?)", [$id_prwapu]);

            // Ambil total dari hasil stored procedure
            $total = 0;
            if (!empty($result)) {
                $total = $result[0]->subtotal_cogs ?? $result[0]->total_cogs ?? 0;
            }

            return response()->json(['total_cogs' => $total]);
        } catch (\Exception $e) {
            Log::error('Error in getTotalCogs: ' . $e->getMessage());
            return response()->json(['total_cogs' => 0]);
        }
    }

    public function updateIncentive(Request $request)
    {
        try {
            $request->validate([
                'id_prwapu' => 'required|exists:prwapus,id',
                // validasi lain jika perlu
            ]);

            $id = $request->input('id_prwapu');
            $prwapu = Wapu::findOrFail($id);

            $parseRupiah = function($val) {
                return (int) preg_replace('/[^\d]/', '', $val ?? '0');
            };

            // $prwapu->angka = $parseRupiah($request->input('angka'));
            $prwapu->persentase_incentive = $request->input('persentase_incentive');
            $prwapu->incentive_fe001a = $parseRupiah($request->input('incentive_fe001a'));
            $prwapu->persentase_fe001a = $request->input('persentase_fe001a');
            $prwapu->approval = $request->input('approval');
            $prwapu->status = $request->input('status');
            $prwapu->save();

            return response()->json(['message' => 'Data berhasil diupdate!']);
        } catch (\Exception $e) {
            // Log error dan return response error
            Log::error($e);
            return response()->json(['message' => 'Gagal update data', 'error' => $e->getMessage()], 500);
        }
    }


    public function detailUpdateCogs(Request $request, $id)
    {
        try {
            $non_ppn = Cogs::findOrFail($id);
            $non_ppn->expedittion = $request->expedittion;
            $non_ppn->add_insentif_fe001a = $request->add_insentif_fe001a;
            $non_ppn->instalasi_setting = $request->instalasi_setting;
            $non_ppn->other = $request->other;
            $non_ppn->save();

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil diupdate',
                'data' => $non_ppn
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
        $id_po = $request->id_po;

        $query = vwExportPoCv::query();
        $query->where('id_po', $id_po);
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
		$this->fpdf->Ln(4.9);





        $this->fpdf->SetFont('helvetica', 'BU', 16);
        $this->fpdf->Cell(0, 0.7, "Purchase Order", 0, 0, 'C');
		$this->fpdf->Ln(1);

        $this->fpdf->SetFont('helvetica', '', 11);
        $this->fpdf->Cell(12, 0.5, "Nomor PO : " . $data_result[0]['nomor_po'], 0, 0, 'L');
         $this->fpdf->Cell(0, 0.5, "Bandung, " . $this->formatDateIndonesian(), 0, 0, 'R');
		$this->fpdf->Ln();
        $this->fpdf->Cell(12, 0.5, "Lampiran : " . $data_result[0]['lampiran'], 0, 0, 'L');
		$this->fpdf->Ln(1);
        $this->fpdf->Cell(12, 0.5, "Kepada Yth : " . $data_result[0]['sales_vendor'], 0, 0, 'L');
		$this->fpdf->Ln();
        $this->fpdf->Cell(12, 0.5, $data_result[0]['nama_vendor'], 0, 0, 'L');
		$this->fpdf->Ln(1);

        $table = new easyTables($this->fpdf, "{5, 12, 12, 4, 12, 15}", 'border:1;font-size:9;');


        $table->rowStyle('font-style:B;');
        $table->easyCell('NO', 'valign:M;align:C;');
        $table->easyCell('Part Number', 'valign:M;align:C;');
        $table->easyCell('Spesifikasi', 'valign:M;align:C;');
        $table->easyCell('QTY', 'valign:M;align:C;');
        $table->easyCell('Harga Satuan', 'valign:M;align:C;');
        $table->easyCell('Jumlah', 'valign:M;align:C;');
        $table->printRow();

        $i = 1;
        $grand_total = 0; // Initialize grand total
        foreach ($data_result as $value) {
            // Calculate grand total by summing total_price
            // Remove any currency formatting and convert to numeric value
            $total_price_numeric = (float) preg_replace('/[^\d]/', '', $value['total_price']);
            $grand_total += $total_price_numeric;

            $table->easyCell($i++, 'valign:M;align:C;');
            $table->easyCell($value['part_number'], 'valign:M;align:L;');
            $table->easyCell($value['partnumber_description'], 'valign:M;align:L;');
            $table->easyCell($value['qty'], 'valign:M;align:C;');
            $table->easyCell($value['unit_price'], 'valign:M;align:L;');
            $table->easyCell($value['total_price'], 'valign:M;align:L;');

            $table->printRow();
        }
        $this->fpdf->Ln(0);

        $table->rowStyle('font-style:B; border:0;');
        $table->easyCell('TOTAL', 'colspan:5;valign:M;align:R;');
        $table->rowStyle('font-style:B; border:1;');
        $table->easyCell(number_format($grand_total, 0, ',', '.'), 'valign:M;align:L;');
        $table->printRow();
        $this->fpdf->Ln(1);

        $this->fpdf->SetFont('Arial', 'I', 11);
        $this->fpdf->Cell(12, 0.5, "Note : " . $data_result[0]['note'], 0, 0, 'L');
		$this->fpdf->Ln(3);
        $this->fpdf->SetFont('Arial', '', 11);
        $this->fpdf->Cell(12, 0.5, "Hormat Kami,", 0, 0, 'L');
		$this->fpdf->Ln(3.5);
        $this->fpdf->Image(public_path('admin/assets/img/TTD_Rika_CV.png'), 0.8, 16, 5, 3);
		$this->fpdf->Ln(0);
        $this->fpdf->SetFont('Arial', 'U', 11);
        $this->fpdf->Cell(12, 0.5, "Rika", 0, 0, 'L');
		$this->fpdf->Ln(0.5);
        $this->fpdf->SetFont('Arial', '', 11);
        $this->fpdf->Cell(12, 0.5, "Admin", 0, 0, 'L');
		$this->fpdf->Ln(1);

        // Remove the commented out code since we've implemented the calculation above
        // $grand_total = 0;
        // // foreach ($data_result as $value) {
        // //     foreach ($value as $key => $data) {
        // //         $grand_total += $data->harga;
        // //     }
        // // }

        // // $table->rowStyle('font-style:B');
        // // $table->easyCell('TOTAL KESELURUHAN', 'colspan:5;valign:M;align:L;');
        // // $table->easyCell('Rp ' . number_format($grand_total, 0, ',', '.'), 'valign:M;align:R;');
        // // $table->printRow();


		$table->endTable(0);

        // Add footer
        $this->addFooter();

        $this->fpdf->Output();
        exit;

    }

    public function cetakPT(Request $request)
    {
        $id_po = $request->id_po;

        $query = vwExportPoCv::query();
        $query->where('id_po', $id_po);
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
		$this->fpdf->Ln(4.9);





        $this->fpdf->SetFont('helvetica', 'BU', 16);
        $this->fpdf->Cell(0, 0.7, "Purchase Order", 0, 0, 'C');
		$this->fpdf->Ln(1);

        $this->fpdf->SetFont('helvetica', '', 11);
        $this->fpdf->Cell(12, 0.5, "Nomor PO : " . $data_result[0]['nomor_po'], 0, 0, 'L');
         $this->fpdf->Cell(0, 0.5, "Bandung, " . $this->formatDateIndonesian(), 0, 0, 'R');
		$this->fpdf->Ln();
        $this->fpdf->Cell(12, 0.5, "Lampiran : " . $data_result[0]['lampiran'], 0, 0, 'L');
		$this->fpdf->Ln(1);
        $this->fpdf->Cell(12, 0.5, "Kepada Yth : " . $data_result[0]['sales_vendor'], 0, 0, 'L');
		$this->fpdf->Ln();
        $this->fpdf->Cell(12, 0.5, $data_result[0]['nama_vendor'], 0, 0, 'L');
		$this->fpdf->Ln(1);

        $table = new easyTables($this->fpdf, "{5, 12, 12, 4, 12, 15}", 'border:1;font-size:9;');


        $table->rowStyle('font-style:B;');
        $table->easyCell('NO', 'valign:M;align:C;');
        $table->easyCell('Part Number', 'valign:M;align:C;');
        $table->easyCell('Spesifikasi', 'valign:M;align:C;');
        $table->easyCell('QTY', 'valign:M;align:C;');
        $table->easyCell('Harga Satuan', 'valign:M;align:C;');
        $table->easyCell('Jumlah', 'valign:M;align:C;');
        $table->printRow();

        $i = 1;
        $grand_total = 0; // Initialize grand total
        foreach ($data_result as $value) {
            // Calculate grand total by summing total_price
            // Remove any currency formatting and convert to numeric value
            $total_price_numeric = (float) preg_replace('/[^\d]/', '', $value['total_price']);
            $grand_total += $total_price_numeric;

            $table->easyCell($i++, 'valign:M;align:C;');
            $table->easyCell($value['part_number'], 'valign:M;align:L;');
            $table->easyCell($value['partnumber_description'], 'valign:M;align:L;');
            $table->easyCell($value['qty'], 'valign:M;align:C;');
            $table->easyCell($value['unit_price'], 'valign:M;align:L;');
            $table->easyCell($value['total_price'], 'valign:M;align:L;');

            $table->printRow();
        }
        $this->fpdf->Ln(0);

        $table->rowStyle('font-style:B; border:0;');
        $table->easyCell('TOTAL', 'colspan:5;valign:M;align:R;');
        $table->rowStyle('font-style:B; border:1;');
        $table->easyCell(number_format($grand_total, 0, ',', '.'), 'valign:M;align:L;');
        $table->printRow();
        $this->fpdf->Ln(1);

        $this->fpdf->SetFont('Arial', 'I', 11);
        $this->fpdf->Cell(12, 0.5, "Note : " . $data_result[0]['note'], 0, 0, 'L');
		$this->fpdf->Ln(3);
        $this->fpdf->SetFont('Arial', '', 11);
        $this->fpdf->Cell(12, 0.5, "Hormat Kami,", 0, 0, 'L');
		$this->fpdf->Ln(3.5);
        $this->fpdf->Image(public_path('admin/assets/img/TTD_Rika_CV.png'), 0.8, 16, 5, 3);
		$this->fpdf->Ln(0);
        $this->fpdf->SetFont('Arial', 'U', 11);
        $this->fpdf->Cell(12, 0.5, "Rika", 0, 0, 'L');
		$this->fpdf->Ln(0.5);
        $this->fpdf->SetFont('Arial', '', 11);
        $this->fpdf->Cell(12, 0.5, "Admin", 0, 0, 'L');
		$this->fpdf->Ln(1);

        // Remove the commented out code since we've implemented the calculation above
        // $grand_total = 0;
        // // foreach ($data_result as $value) {
        // //     foreach ($value as $key => $data) {
        // //         $grand_total += $data->harga;
        // //     }
        // // }

        // // $table->rowStyle('font-style:B');
        // // $table->easyCell('TOTAL KESELURUHAN', 'colspan:5;valign:M;align:L;');
        // // $table->easyCell('Rp ' . number_format($grand_total, 0, ',', '.'), 'valign:M;align:R;');
        // // $table->printRow();


		$table->endTable(0);

        // Add footer
        $this->addFooter();

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

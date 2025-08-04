<?php

namespace App\Http\Controllers\pr;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Cogs;
use App\Models\CogsSwasta;
use App\Models\PaketSoundsystem;
use App\Models\SoundSystem;
use App\Models\DetailSoundSystem;
use App\Models\PrwapuDetail;
use App\Models\Vwcogs;
use App\Models\VwcogsSwasta;
use App\Models\VwPrwapudetail;
use App\Models\Swasta;
use App\Models\Nonppn;
use App\Models\SwastaDetail;
use App\Models\User;
use App\Models\VwSwastadetail;
use App\Models\Wapu;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class SwastaController extends Controller
{
    // public function index()
    // {
    //     $user = auth()->user();
    //     if ($user->id_role == 1) {
    //         $swasta = DB::select("CALL sp_data_swasta(0)");
    //     } else {

    //         $swasta = DB::select("CALL sp_data_swasta($user->id)");
    //     }


    //     return view('swasta.index',[

    //         'swasta' => $swasta,
    //         "active" => 'pr_wapu'

    //     ]);
    // }

    public function datatable()
    {
        $draw = request()->get('draw');
        $start = request()->get('start');
        $length = request()->get('length');
        $id_user = request()->get('cmb_nip');

        $user = auth()->user();
        $query = Swasta::query();

        if ($id_user) {
            $query->where('nip_user', $id_user);
        }

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

    public function create(Request $request)
    {
        $user = auth()->user();

        try {
            // Validasi input
            $request->validate([
                'nama_projek' => 'required|string|max:255',
                'nomor_pr' => 'required|string|unique:prwapus,nomor_pr'
            ], [
                'nama_projek.required' => 'Nama projek wajib diisi',
                'nomor_pr.required' => 'Nomor PR wajib diisi',
                'nomor_pr.unique' => 'Nomor PR sudah digunakan'
            ]);

            $swasta = new Swasta();
            $swasta->id_sales = $user->id;
            $swasta->nama_projek = $request->nama_projek;
            $swasta->nomor_pr = $request->nomor_pr;
            $swasta->updated_at = null;
            $swasta->save();

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil disimpan',
                'data' => $swasta
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

            $swasta = Swasta::findOrFail($id);
            $swasta->nama_projek = $request->nama_projek;
            $swasta->save();

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil diupdate',
                'data' => $swasta
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
            $swasta = Swasta::findOrFail($id);
            $swasta->delete();

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

    public function generateNomorPr()
    {
        $user = Auth::user();
        $idSales = $user->id;

        // Ambil nomor PR terakhir dari Swasta (prwapus)
        $lastPrSwasta = Wapu::where('id_sales', $idSales)
            ->orderByDesc('id')
            ->first();

        // Ambil nomor PR terakhir dari Nonppn
        $lastPrNonPpn = Nonppn::where('id_sales', $idSales)
            ->orderByDesc('id')
            ->first();

        // Ambil angka urut dari masing-masing nomor PR
        $getNumber = function($nomorPr) {
            if (!$nomorPr) return 0;
            $parts = explode('/', $nomorPr);
            return isset($parts[1]) ? (int)$parts[1] : 0;
        };

        $lastNumberSwasta = $getNumber($lastPrSwasta ? $lastPrSwasta->nomor_pr : null);
        $lastNumberNonPpn = $getNumber($lastPrNonPpn ? $lastPrNonPpn->nomor_pr : null);

        // Ambil angka urut terbesar
        $lastNumber = max($lastNumberSwasta, $lastNumberNonPpn);
        $newNumber = $lastNumber + 1;

        // Format nomor PR berurut 4 digit
        $nomorUrut = str_pad($newNumber, 4, '0', STR_PAD_LEFT);

        // Nama user (tanpa spasi, huruf kecil)
        $namaUser = strtolower(str_replace(' ', '', $user->name));

        // Nama perusahaan (tanpa spasi, kapital)
        $namaPerusahaan = strtoupper(str_replace(' ', '', $user->company_name ?? 'MBS'));

        // Bulan romawi
        $bulan = date('n');
        $romawi = ['', 'I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII'];
        $bulanRomawi = $romawi[$bulan];

        // Tahun
        $tahun = date('Y');

        // Gabungkan format
        $nomorPr = "{$namaUser}/{$nomorUrut}/{$namaPerusahaan}/{$bulanRomawi}/{$tahun}";

        return response()->json([
            'success' => true,
            'nomor_pr' => $nomorPr
        ]);
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




    public function datatabledetail(Request $request,)
    {
        $draw = request()->get('draw');
        $start = request()->get('start');
        $length = request()->get('length');
        $id_projek = request()->get('id_projek');

        $query = VwPrwapudetail::query();
        $query->where('id_projek', $id_projek);

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
            $pr_wapu->id_projek = $request->id_projek;
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
        $id_projek = request()->get('id_projek');

        $query = Vwcogs::query();
        $query->where('id_projek', $id_projek);

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
            $pr_wapu->id_projek = $request->id_projek;
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
            'id_projek' => 'required|exists:prwapus,id',
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
        $subtotal_price = (int) preg_replace('/[^\d]/', '', $request->subtotal_price);
        $validasi_payment = (int) preg_replace('/[^\d]/', '', $request->validasi_payment);
        $jumlah_ppn = (int) preg_replace('/[^\d]/', '', $request->jumlah_ppn);
        $total_vat = (int) preg_replace('/[^\d]/', '', $request->total_vat);
        $subtotal_po_cv = (int) preg_replace('/[^\d]/', '', $request->subtotal_po_cv);
        $subtotal_po_cost_cv = (int) preg_replace('/[^\d]/', '', $request->subtotal_po_cost_cv);
        $subtotal_margin_cv = (int) preg_replace('/[^\d]/', '', $request->subtotal_margin_cv);
        $subtotal_persentase_cv = (int) preg_replace('/[^\d]/', '', $request->subtotal_persentase_cv);

        $prwapu = Swasta::findOrFail($request->id_projek);
        $prwapu->total_po_ppn = $total_po_ppn;
        $prwapu->total_cost_ppn = $total_cost_ppn;
        $prwapu->total_margin_ppn = $total_margin_ppn;
        $prwapu->total_po_non_ppn = $total_po_non_ppn;
        $prwapu->total_cost_non_ppn = $total_cost_non_ppn;
        $prwapu->total_margin_non_ppn = $total_margin_non_ppn;
        $prwapu->subtotal_price = $subtotal_price;
        $prwapu->validasi_payment = $validasi_payment;
        $prwapu->jumlah_ppn = $jumlah_ppn;
        $prwapu->total_vat = $total_vat;
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
            'id_projek' => 'required|exists:prwapus,id',
            'total_po_ppn' => 'required',
            'total_cost_ppn' => 'required',
            'total_margin_ppn' => 'required',
        ]);

        // Hilangkan format Rp dan titik pada input, pastikan hasilnya integer
        $pph_bank_fee = (int) preg_replace('/[^\d]/', '', $request->pph_bank_fee);

        $prwapu = Wapu::findOrFail($request->id_projek);
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
                'id_projek' => 'required|exists:swasta,id',
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
            $prwapu = Swasta::findOrFail($request->id_projek);
            $prwapu->validasi_payment = $validasiPayment;
            $prwapu->save();

            // Update cogs table (pph_bank_fee)
            $cogs = Cogs::where('id_projek', $request->id_projek)->first();
            if ($cogs) {
                $cogs->pph_bank_fee = $pphBankFee;
                $cogs->save();
            }

            // Hitung subtotal_price dari data prwapu_detail
            $subtotalPrice = 0;
            $prwapuDetails = PrwapuDetail::where('id_projek', $request->id_projek)->get();
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
            $id_projek = $request->input('id_projek');

            if (!$id_projek) {
                return response()->json(['total_cogs' => 0]);
            }

            $result = DB::select("CALL sp_subtotal_cogs(?)", [$id_projek]);

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
                'id_projek' => 'required|exists:prwapus,id',
                // validasi lain jika perlu
            ]);

            $id = $request->input('id_projek');
            $prwapu = Swasta::findOrFail($id);

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


}

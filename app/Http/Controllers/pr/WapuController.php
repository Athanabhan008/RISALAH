<?php

namespace App\Http\Controllers\pr;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Cogs;
use App\Models\PaketSoundsystem;
use App\Models\SoundSystem;
use App\Models\DetailSoundSystem;
use App\Models\Nonppn;
use App\Models\PrwapuDetail;
use App\Models\SharingProfit;
use App\Models\Swasta;
use App\Models\User;
use App\Models\Vendor;
use App\Models\Vwcogs;
use App\Models\VwPrwapudetail;
use App\Models\Wapu;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class WapuController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        if ($user->id_role == 1) {
            $pr_wapu = DB::select("CALL sp_data_prwapu(0)");
        } else {

            $pr_wapu = DB::select("CALL sp_data_prwapu($user->id)");
        }


        return view('pr/pr_wapu',[

            'pr_wapu' => $pr_wapu,
            "active" => 'pr_wapu'

        ]);
    }

    public function datatable()
    {
        $draw = request()->get('draw');
        $start = request()->get('start');
        $length = request()->get('length');
        $id_user = request()->get('cmb_nip');

        $user = auth()->user();
        $query = Wapu::query();

        if ($id_user) {
            $query->where('nip_user', $id_user);
        }

        // Filter berdasarkan user yang login
        if ($user->role == 'super_admin' || $user->role == 'admin' || $user->role == 'manager') {
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

    public function datatablesharing()
    {
        $draw = request()->get('draw');
        $start = request()->get('start');
        $length = request()->get('length');
        $id_user = request()->get('cmb_nip');

        $user = auth()->user();
        $query = SharingProfit::query();

        if ($id_user) {
            $query->where('nip_user', $id_user);
        }

        // Filter berdasarkan user yang login
        if ($user->role == 'super_admin' || $user->role == 'admin' || $user->role == 'manager') {
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

            $pr_wapu = new Wapu();
            $pr_wapu->id_sales = $user->id;
            $pr_wapu->nip_user = $user->nip;
            $pr_wapu->nama_client = $request->nama_client;
            $pr_wapu->nama_projek = $request->nama_projek;
            $pr_wapu->jenis_pr = $request->jenis_pr;
            $pr_wapu->nomor_pr = $request->nomor_pr;
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
            $pr_wapu->nama_client = $request->nama_client;
            $pr_wapu->nama_projek = $request->nama_projek;
            $pr_wapu->jenis_pr = $request->jenis_pr;
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

    public function generateNomorPr()
    {
        $user = Auth::user();
        $idSales = $user->id;

        // Ambil nomor PR terakhir milik sales ini
        $lastPr = Wapu::where('id_sales', $idSales)
            ->orderByDesc('id')
            ->first();

        if ($lastPr) {
            // Ambil angka dari nomor_pr terakhir, misal format: budi/0001/argana/III/2024
            // Pisahkan dengan explode, ambil bagian ke-1 (0001)
            $parts = explode('/', $lastPr->nomor_pr);
            $lastNumber = isset($parts[1]) ? (int)$parts[1] : 0;
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

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


        public function detail_data_prwapu(Request $request)
        {
            try {
                // Panggil multiple SP
                $subtotal = DB::select("CALL sp_subtotal(?)", [$request->id_projek]);
                $subtotal_cogs = DB::select("CALL sp_subtotal_cogs(?)", [$request->id_projek]);

                $prwapu = Wapu::findOrFail($request->id_projek);

                // Ambil pph_bank_fee dari tabel cogs, bukan dari prwapus
                $cogs = Cogs::where('id_projek', $request->id_projek)->first();
                $pph_bank_fee = $cogs ? $cogs->pph_bank_fee : null;

                // Jika pph_bank_fee kosong, hitung berdasarkan validasi_payment dan subtotal_price
                if (!$pph_bank_fee && $prwapu->validasi_payment && $prwapu->subtotal_price) {
                    $pph_bank_fee = $prwapu->subtotal_price - $prwapu->validasi_payment;
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

                // Ambil data user yang sedang login
                $currentUser = auth()->user();

                return view('pr.detail_data_prwapu', [
                    'active'=> 'pr_wapu',
                    'id_projek' => $request->id_projek,
                    'subtotal' => $subtotal,
                    'subtotal_cogs' => $subtotal_cogs,
                    'validasi_payment' => $prwapu->validasi_payment ?? '',
                    'pph_bank_fee' => $pph_bank_fee ?? '',
                    'incentive_sales' => $incentive_sales ?? 0,
                    'currentUser' => $currentUser, // Tambahkan data user yang login
                ]);
            } catch (\Exception $e) {
                return redirect()->back()->with('error', 'Gagal memuat data: ' . $e->getMessage());
            }
        }


        public function detail_data_swasta(Request $request)
        {
            try {
                // Panggil multiple SP
                $subtotal = DB::select("CALL sp_subtotal(?)", [$request->id_projek]);
                $subtotal_cogs = DB::select("CALL sp_subtotal_cogs(?)", [$request->id_projek]);

                $prwapu = Wapu::findOrFail($request->id_projek);

                // Ambil pph_bank_fee dari tabel cogs, bukan dari prwapus
                $cogs = Cogs::where('id_projek', $request->id_projek)->first();
                $pph_bank_fee = $cogs ? $cogs->pph_bank_fee : null;

                // Jika pph_bank_fee kosong, hitung berdasarkan validasi_payment dan subtotal_price
                if (!$pph_bank_fee && $prwapu->validasi_payment && $prwapu->subtotal_price) {
                    $pph_bank_fee = $prwapu->subtotal_price - $prwapu->validasi_payment;
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

                return view('swasta.detail_data_swasta', [
                    'active'=> 'pr_wapu',
                    'id_projek' => $request->id_projek,
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

        public function detail_data_nonppn(Request $request)
        {
            try {
                // Panggil multiple SP
                $subtotal = DB::select("CALL sp_subtotal(?)", [$request->id_projek]);
                $subtotal_cogs = DB::select("CALL sp_subtotal_cogs(?)", [$request->id_projek]);

                $non_pppn = Wapu::findOrFail($request->id_projek);

                // Ambil pph_bank_fee dari tabel cogs, bukan dari non_pppns
                $cogs = Cogs::where('id_projek', $request->id_projek)->first();
                $pph_bank_fee = $cogs ? $cogs->pph_bank_fee : null;

                // Jika pph_bank_fee kosong, hitung berdasarkan validasi_payment dan subtotal_price
                if (!$pph_bank_fee && $non_pppn->validasi_payment && $non_pppn->subtotal_price) {
                    $pph_bank_fee = $non_pppn->subtotal_price - $non_pppn->validasi_payment;
                }

                // Hitung subtotal_price dari data prwapu_detail
                $subtotalPrice = 0;
                $nonppnDetails = PrwapuDetail::where('id_projek', $request->id_projek)->get();
                foreach ($nonppnDetails as $detail) {
                    $totalPrice = (int) preg_replace('/[^\d]/', '', $detail->total_price);
                    $subtotalPrice += $totalPrice;
                }

                // Hitung subtotal_cost dari data prwapu_detail dan cogs
                $subtotalCost = 0;
                foreach ($nonppnDetails as $detail) {
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

                return view('non_ppn.detail_data_non_ppn', [
                    'active'=> 'pr_wapu',
                    'id_projek' => $request->id_projek,
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
            $pr_wapu->id_vendor = $request->cmb_vendor;
            $pr_wapu->jenis_ppn = $request->jenis_ppn;
            $pr_wapu->part_number = $request->part_number;
            $pr_wapu->partnumber_description = $request->partnumber_description;
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
            $pr_wapu->part_number = $request->part_number;
            $pr_wapu->partnumber_description = $request->partnumber_description;
            $pr_wapu->id_vendor = $request->cmb_vendor;
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
        ]);

        // Hilangkan format Rp dan titik pada input, pastikan hasilnya integer
        $subtotal_price = (int) preg_replace('/[^\d]/', '', $request->subtotal_price);
        $validasi_payment = (int) preg_replace('/[^\d]/', '', $request->validasi_payment);
        $jumlah_ppn = (int) preg_replace('/[^\d]/', '', $request->jumlah_ppn);
        $total_vat = (int) preg_replace('/[^\d]/', '', $request->total_vat);
        $gross_provit = (int) preg_replace('/[^\d]/', '', $request->gross_provit);
        $subtotal_cost = (int) preg_replace('/[^\d]/', '', $request->subtotal_cost);
        $persentase_margin = (int) preg_replace('/[^\d]/', '', $request->persentase_margin);

        $prwapu = Wapu::findOrFail($request->id_projek);
        $prwapu->subtotal_price = $subtotal_price;
        $prwapu->jumlah_ppn = $jumlah_ppn;
        $prwapu->total_vat = $total_vat;
        $prwapu->gross_provit = $gross_provit;
        $prwapu->subtotal_cost = $subtotal_cost;
        $prwapu->persentase_margin = $persentase_margin;
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
        $subtotal_po_cv = (int) preg_replace('/[^\d]/', '', $request->subtotal_po_cv);
        $subtotal_po_cost_cv = (int) preg_replace('/[^\d]/', '', $request->subtotal_po_cost_cv);
        $subtotal_margin_cv = (int) preg_replace('/[^\d]/', '', $request->subtotal_margin_cv);
        $subtotal_persentase_cv = (int) preg_replace('/[^\d]/', '', $request->subtotal_persentase_cv);

        $prwapu = Wapu::findOrFail($request->id_projek);
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


    public function createsharingprovit(Request $request)
    {
        try {
            // Validasi input
            $request->validate([
                'id_projek' => 'required|string|max:255',
                'profit_holding' => 'required|string|max:255',
                'profit_leader' => 'required|string|max:255',
                'profit_dirutama' => 'required|string|max:255',
                'profit_sim' => 'required|string|max:255',
                'profit_keuangan' => 'required|string|max:255',
                'total_profit' => 'required|string|max:255',
            ], [
                'id_projek.required' => 'Id Projek wajib diisi',
                'profit_holding.required' => 'Profit Holding wajib diisi',
                'profit_leader.reuired' => 'Profit Leader Wajib Diisi',
                'profit_dirutama.required' => 'Profit Dirutama Wajib Diisi',
                'profit_sim.required' => 'Profit SIM Wajib Diisi',
                'profit_keuangan.required' => 'Profit Keuangan Wajib Diisi',
                'total_profit.required' => 'Total Profit Wajib Diisi',
            ]);

            $pr_wapu = new SharingProfit();
            $pr_wapu->id_projek = $request->id_projek;
            $pr_wapu->profit_holding = $request->profit_holding;
            $pr_wapu->profit_leader = $request->  profit_leader;
            $pr_wapu->profit_dirutama = $request-> profit_dirutama;
            $pr_wapu->profit_sim = $request->profit_sim;
            $pr_wapu->profit_keuangan = $request->profit_keuangan;
            $pr_wapu->total_profit = $request->total_profit;
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
                'id_projek' => 'required|exists:prwapus,id',
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
            $prwapu = Wapu::findOrFail($request->id_projek);
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
            $prwapu = Wapu::findOrFail($id);

            $parseRupiah = function($val) {
                return (int) preg_replace('/[^\d]/', '', $val ?? '0');
            };

            $prwapu->incentive_sales = $parseRupiah($request->input('incentive_sales'));
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


    public function getvendor()
    {

        $query = Vendor::query();

        // Apply pagination
        $result = $query->get();

        return response()->json([
            'error' => 0,
            'message' => 'Success',
            'data'=> $result
        ]);
    }

}

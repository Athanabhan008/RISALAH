<?php

namespace App\Http\Controllers\qc;


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
use App\Models\Invoice;
use App\Models\Qc;
use App\Models\Qcdetail;
use App\Models\vwExportInvoiceCv;
use App\Models\VwInvoice;
use App\Models\VWQc;
use App\Models\VwQcdetail;

class QcController extends Controller
{
    public function __construct()
    {
        $this->fpdf = new exFPDF('P', 'mm', 'A4');
    }

    public function index()
    {
        return view('qc/index',[

            "active" => 'qc'

        ]);
    }

    public function datatable()
    {
        $draw = request()->get('draw');
        $start = request()->get('start');
        $length = request()->get('length');
        $id_user = request()->get('cmb_nip');

        $user = auth()->user();
        $query = VWQc::query();

        // Filter berdasarkan user yang login
        if ($user->role == 'super_admin' || $user->role == 'teknisi') {
            // Jika admin atau teknisi, tampilkan semua data
            // Tidak perlu filter tambahan
        } else {
            // Jika bukan admin atau teknisi, filter berdasarkan id_sales yang sesuai dengan user yang login
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

    public function getBarang(Request $request)
    {
        $id_pr = $request->input('id_pr');
        $query = PrwapuDetail::query();

        if ($id_pr) {
            $query->where('id_projek', $id_pr);
        }

        if ($request->has('q')) {
            $search = $request->input('q');
            $query->where('partnumber_description', 'like', "%$search%");
        }

        $data = $query->get();

        return response()->json([
            'data' => $data
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

            $qc = new Qc();
            $qc->id_sales   = $request->cmb_sales;
            $qc->id_pr      = $request->cmb_pr;
            $qc->tgl_qc     = $request->tgl_qc;
            $qc->updated_at = null;
            $qc->save();

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil disimpan',
                'data' => $qc
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

    public function detail_data_qc(Request $request)
    {
        try {
            // Ambil data QC berdasarkan id_qc dari request
            $qc = Qc::find($request->id_qc);

            if (!$qc) {
                return redirect()->back()->with('error', 'Data QC tidak ditemukan.');
            }

            // Ambil data PR terkait
            $pr = Wapu::find($qc->id_pr);

            return view('qc.detail_data_qc', [
                'active' => 'qc',
                'id_qc' => $request->id_qc,
                'qc' => $qc,
                'nomor_pr' => $pr ? $pr->nomor_pr : null, // kirim nomor_pr ke view
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
        $id_qc = request()->get('id_qc');

        $query = VwQcdetail::query();
        $query->where('id_qc', $id_qc);

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
            $pr_wapu = new Qcdetail();
            $pr_wapu->id_qc = $request->id_qc;
            $pr_wapu->id_pr = $request->cmb_barang;
            $pr_wapu->serial_number = $request->serial_number;
            // $pr_wapu->partnumber_description = $request->partnumber_description;
            // $pr_wapu->vendor = $request->vendor;
            // $pr_wapu->unit_price = $request->unit_price;
            // $pr_wapu->total_price = $request->total_price;
            // $pr_wapu->qty = $request->qty;
            // $pr_wapu->vendor_price = $request->vendor_price;
            // $pr_wapu->unit_price_cv = $request->unit_price_cv;
            // $pr_wapu->total_po_cv = $request->total_po_cv;
            // $pr_wapu->total_cost = $request->total_cost;
            // $pr_wapu->margin = $request->margin;
            // $pr_wapu->persentase = $request->persentase;
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

}

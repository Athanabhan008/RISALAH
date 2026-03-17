<?php

namespace App\Http\Controllers\menu;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Jenismakanan;
use App\Models\Menu;
use App\Models\Vwmenu;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class MenuController extends Controller
{
    public function index()
{
    // JANGAN di-sort lagi di Laravel
    return view('menu/index', [
        'active'  => 'menu'
    ]);
}

//     public function search(Request $request)
// {
//     $search = $request->q;
//     $vendors = Jenismakanan::where('nama_menu', 'like', "%$search%")->get();
//     return response()->json($vendors);
// }

public function getjenis()
    {
        $searchTerm = request()->get('q', '');

        $query = Jenismakanan::query();

        // Apply search filter if search term is provided
        if (!empty($searchTerm)) {
            $query->where('nama_jenis', 'like', '%' . $searchTerm . '%');
        }

        // Order by nama_vendor for better UX
        $query->orderBy('nama_jenis', 'asc');

        // Apply pagination
        $result = $query->get();

        return response()->json([
            'error' => 0,
            'message' => 'Success',
            'data'=> $result
        ]);
    }

    public function datatable()
    {
        $draw = request()->get('draw');
        $start = request()->get('start');
        $length = request()->get('length');
        $periode_start = request()->get('periode_start');

        $user = auth()->user();
        // Gunakan kolom yang benar-benar ada di view `vw_menu`
        // Jika view tidak memiliki kolom `created_at`, sorting berdasarkan `id` saja
        $query = Vwmenu::query()->orderBy('id', 'desc');

        // Jika ingin filter berdasarkan bulan, pastikan dulu ada kolom tanggal di view,
        // misalnya `tanggal` atau `tgl_transaksi`, lalu ganti di bawah ini.
        // Saat ini blok filter dinonaktifkan agar tidak error karena kolom tidak ada.
        //
        // if ($periode_start) {
        //     $year = substr($periode_start, 0, 4);
        //     $month = substr($periode_start, 5, 2);
        //
        //     $startDate = \Carbon\Carbon::createFromFormat('Y-m', "$year-$month")->startOfMonth();
        //     $endDate = \Carbon\Carbon::createFromFormat('Y-m', "$year-$month")->endOfMonth();
        //
        //     $query->whereBetween('nama_kolom_tanggal_di_view', [$startDate, $endDate]);
        // }

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

    public function create(Request $request)
    {
        try {
            // Validasi input
            $request->validate([
                'nama_menu'  => 'required',
                'keterangan' => 'required',
                'harga'      => 'required',
                'gambar'      => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
            ], [
                'nama_menu.required'  => 'Nama Menu wajib diisi',
                'keterangan.required' => 'Keterangan Wajib Di isi',
                'harga.required'      => 'Harga Wajib Di isi',
                'gambar.required'      => 'Gambar Wajib Di isi'
            ]);

            $menu = new Menu();
            $imagePath = $request->file('gambar')->store('foto_produk', 'public');

            $menu->id_jenis = $request->cmb_jenis;
            $menu->nama_menu = $request->nama_menu;
            $menu->keterangan = $request->keterangan;
            $menu->harga = $request->harga;
            $menu->gambar = $imagePath;
            $menu->updated_at = null;
            $menu->save();

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil disimpan',
                'data' => $menu,
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

        $menu = Menu::findOrFail($id);
        $menu->id_jenis   = $request->cmb_jenis;
        $menu->nama_menu  = $request->nama_menu;
        $menu->harga      = $request->harga;
        $menu->keterangan = $request->keterangan;

        // jika ada upload gambar baru
        if ($request->hasFile('gambar')) {
            $imagePath = $request->file('gambar')->store('foto_produk', 'public');
            $menu->gambar = $imagePath;
        }

        $menu->save();

        return response()->json([
            'success' => true,
            'message' => 'Data berhasil diupdate',
            'data' => $menu
        ]);

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
            $pr_wapu = Menu::findOrFail($id);
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

<?php

namespace App\Http\Controllers\manager;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\Kategoribarang;
use App\Models\StokBarang;
use Illuminate\Support\Facades\DB;
use App\Models\Kategori;
use App\Services\BarangService;

class BarangController extends Controller
{
    public function index()
    {
        $barang = Barang::all();
        return view('manager.barang.home_barang', [
            'barang' => $barang,
            "active" => 'master_barang'
        ]);
    }
    public function datatable()
    {
        $draw = request()->get('draw');
        $start = request()->get('start');
        $length = request()->get('length');
        $search = request()->get('search')['value'];

        $query = Barang::query()->orderBy('id', 'desc');

        // Apply search if any
        if ($search) {
            $query->where('nama', 'like', "%$search%")
                  ->orWhere('harga', 'like', "%$search%")
                  ->orWhere('tgl_masuk', 'like', "%$search%");
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

    public function doSave()
    {
        $kategori = request()->get('id_kategori');
        $nama = request()->get('nama_barang');
        $harga = request()->get('harga');
        $tanggal = request()->get('tgl_masuk');

        $data_tours = DB::select("CALL sp_manager_masterbarang_insert('$nama', '$kategori', '$harga', '$tanggal')");
        $stts = $data_tours[0]->stts;

        return response()->json([
            'stts' => $stts
        ]);

    }

    public function update(Request $request, $id)
    {
        try {

            $data = Barang::findOrFail($id);
            $data->nama_barang = $request->nama_barang;
            $data->id_kategori = $request->id_kategori;
            $data->harga = $request->harga;
            $data->tgl_masuk = $request->tgl_masuk;
            $data->updated_at = date('Y-m-d');
            $data->save();

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil diupdate',
                'data' => $data
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengupdate data',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function detail_barang()
    {
        return view('manager.barang.detail_barang');
    }

    /**
     * Menyimpan data barang dan stok sekaligus
     */
    public function saveBarangWithStok(Request $request)
    {
        try {
            // Mulai database transaction
            DB::beginTransaction();

            // Validasi input
            $request->validate([
                'nama_barang' => 'required|string|max:255',
                'id_kategori' => 'required|exists:kategoribarangs,id',
                'harga' => 'required|numeric|min:0',
                'tgl_masuk' => 'required|date',
                'stok_awal' => 'required|integer|min:0',
                'keterangan_stok' => 'nullable|string'
            ]);

            // Simpan ke tabel barang
            $barang = new Barang();
            $barang->nama_barang = $request->nama_barang;
            $barang->id_kategori = $request->id_kategori;
            $barang->harga = $request->harga;
            $barang->tgl_masuk = $request->tgl_masuk;
            $barang->save();

            // Simpan ke tabel stok_barang
            $stokBarang = new StokBarang();
            $stokBarang->id_barang = $barang->id;
            $stokBarang->stok = $request->stok_awal;
            $stokBarang->keterangan = $request->keterangan_stok ?? 'Stok awal';
            $stokBarang->tanggal = date('Y-m-d');
            $stokBarang->save();

            // Commit transaction jika semua berhasil
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Data barang dan stok berhasil disimpan',
                'data' => [
                    'barang' => $barang,
                    'stok' => $stokBarang
                ]
            ]);

        } catch (\Exception $e) {
            // Rollback transaction jika terjadi error
            DB::rollback();

            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan data',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Menampilkan form untuk input barang dan stok
     */
    public function createBarangWithStok()
    {
        $kategoris = Kategori::all();
        return view('manager.barang.create_barang_with_stok', [
            'kategoris' => $kategoris,
            'active' => 'master_barang'
        ]);
    }

    /**
     * Menyimpan data barang dan stok menggunakan service class
     */
    public function saveBarangWithStokService(Request $request)
    {
        $barangService = new BarangService();
        $result = $barangService->createBarangWithStok($request->all());

        if ($result['success']) {
            return response()->json([
                'success' => true,
                'message' => 'Data barang dan stok berhasil disimpan menggunakan service',
                'data' => $result
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => $result['message']
            ], 400);
        }
    }
}

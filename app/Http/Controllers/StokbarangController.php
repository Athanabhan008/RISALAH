<?php

namespace App\Http\Controllers;

use App\Models\StokBarang;
use Illuminate\Http\Request;
use App\Models\Penyesuaianstok;
use Illuminate\Support\Facades\DB;

class StokbarangController extends Controller
{
    public function index()
    {
        $stok = StokBarang::all();
        return view('stok_barang.home_stokbarang', [
            'stok' => $stok,
            "active" => 'stok_barang'
        ]);
    }


    public function datatable()
    {
        $draw = request()->get('draw');
        $start = request()->get('start');
        $length = request()->get('length');

        $query = Penyesuaianstok::query()->orderBy('id', 'desc');

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

            $cmb_kategori = $request->kategori;
            $cmb_barang = $request->cmb_barang;
            $jenis = $request->cmb_jenis;
            $jumlah = $request->jumlah;
            $keterangan = $request->keterangan;

            $sql = DB::select("CALL sp_penyesuaian_stok_insert(
                $cmb_kategori,
                $cmb_barang,
                '$jenis',
                $jumlah,
                '$keterangan'
            )");
            $stts = $sql[0]->stts;
            

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil disimpan',
                'query' => $sql,
                'data' => $stts
            ]);

            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan data',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request)
    {
        try {

            $id_edit = $request->id;
            $cmb_kategori = $request->kategori;
            $cmb_barang = $request->cmb_barang;
            $jenis = $request->cmb_jenis;
            $jumlah = $request->jumlah;
            $keterangan = $request->keterangan;

            $sql = DB::select("CALL sp_penyesuaian_stok_update(
                $id_edit,
                $cmb_kategori,
                $cmb_barang,
                '$jenis',
                $jumlah,
                '$keterangan'
            )");
            $stts = $sql[0]->stts;
            

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil disimpan',
                'query' => $sql,
                'data' => $stts
            ]);

            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan data',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function delete(Request $request)
    {
        try {

            $id_delete = $request->id;

            $sql = DB::select("CALL sp_penyesuaian_stok_delete(
                $id_delete
            )");
            $stts = $sql[0]->stts;
            

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil disimpan',
                'query' => $sql,
                'data' => $stts
            ]);

            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan data',
                'error' => $e->getMessage()
            ], 500);
        }
    }



}

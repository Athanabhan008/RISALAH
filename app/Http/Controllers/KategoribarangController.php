<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KategoribarangController extends Controller
{
    public function index()
    {
        $kategori = Kategori::all();
        return view('kategori.home_kategori', [
            'kategori' => $kategori,
            "active" => 'kategori_barang'
        ]);
    }
    public function tambah_data_kategori()
    {

        return view('kategori.tambah_data_kategori', [
            "active" => 'kategori_barang'
        ]);
    }

    public function dosave(Request $request)
    {
        $nama_kategori = $request->input('nama_kategori');

        $kategori = DB::select("CALL sp_manager_masterkategori('$nama_kategori')");
        $stts = $kategori[0]->stts;

        return response()->json([
            'stts' => $stts
        ]);
    }
}

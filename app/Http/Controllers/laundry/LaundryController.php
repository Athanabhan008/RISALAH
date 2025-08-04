<?php

namespace App\Http\Controllers\laundry;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Laundry;
use App\Models\Paketlaundry;
use App\Models\VWlaundry;

class LaundryController extends Controller
{
    public function index()
    {
        $laundry = Laundry::all();
        return view('laundry.index', compact('laundry'));
    }

    public function datatable()
    {
        $draw = request()->get('draw');
        $start = request()->get('start');
        $length = request()->get('length');

        $query = VWlaundry::query();

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
        // var_dump(str_replace('.', '', $request->total_harga));die();
        try {
            $laundry = new Laundry();
            $laundry->id_paket_laundry = $request->cmb_laundry;
            $laundry->nama_paket = $request->nama_paket;
            $laundry->harga = str_replace('.', '', $request->harga);
            $laundry->berat = $request->berat;
            $laundry->total_harga = str_replace('.', '', $request->total_harga);
            $laundry->updated_at = null;
            $laundry->save();

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil disimpan',
                'data' => $laundry
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan data',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getpaketlaundry()
    {

        $query = Paketlaundry::query();

        // Apply pagination
        $result = $query->get();

        return response()->json([
            'error' => 0,
            'message' => 'Success',
            'data'=> $result
        ]);
    }
}

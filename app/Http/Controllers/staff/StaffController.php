<?php

namespace App\Http\Controllers\staff;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Karyawan;
use App\Models\Shift;

class StaffController extends Controller
{
    public function index()
    {
        return view('staff.home', [
            "active" => 'staff'

        ]);
    }
    public function datatable()
    {
        $draw = request()->get('draw');
        $start = request()->get('start');
        $length = request()->get('length');

        $query = Karyawan::query();

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
            $data_staff = new Karyawan();
            $data_staff->id_shift = $request->cmb_shift;
            $data_staff->nama_karyawan = $request->nama_karyawan;
            $data_staff->usia = $request->usia;
            $data_staff->jenis_kelamin = $request->jenis_kelamin;
            $data_staff->updated_at = null;
            $data_staff->save();

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil disimpan',
                'data' => $data_staff
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan data',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function getshift()
    {

        $query = Shift::query();

        // Apply pagination
        $result = $query->get();

        return response()->json([
            'error' => 0,
            'message' => 'Success',
            'data'=> $result
        ]);
    }

    public function getItemByShift($id)
    {
        $shift = Shift::where('id', $id)->get()->toArray();
        return response()->json($shift);
    }
}

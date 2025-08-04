<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class ManajemenakunController extends Controller
{
    public function index()
    {
    $akun = User::all();
        return view('manajemen_akun.index', [
            'akun' => $akun,
            "active" => 'akun'
        ]);
    }
    public function datatable()
    {
        $draw    =   request()->get('draw');
        $start   =   request()->get('start');
        $length  =   request()->get('length');

        $query = User::query();

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
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = password_hash($request->password, PASSWORD_DEFAULT);;
            $user->role = $request->role;
            // $user->updated_at = null;
            $user->save();

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil disimpan',
                'data' => $user
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan data',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function delete($id)
    {
        try {
            $data_booking = User::findOrFail($id);
            $data_booking->delete();

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

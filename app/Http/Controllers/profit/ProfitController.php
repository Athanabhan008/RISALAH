<?php

namespace App\Http\Controllers\profit;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\VwProfit;
use App\Models\Wapu;
use App\Models\User;
use App\Models\Cogs;

class ProfitController extends Controller
{
    public function index()
    {
        return view('profit/index',[

            "active" => 'profit'

        ]);
    }

    public function datatable()
    {
        $draw = request()->get('draw');
        $start = request()->get('start');
        $length = request()->get('length');
        $id_user = request()->get('cmb_nip');
        $tgl_bayar = request()->get('tgl_bayar');
        $cmb_sales = request()->get('cmb_sales');

        $user = auth()->user();
        $query = VwProfit::query();

        // Filter berdasarkan bulan (periode_start)
        if ($tgl_bayar) {
            // Format dari frontend: "yyyy-mm" (contoh: "2024-01")
            $year = substr($tgl_bayar, 0, 4);
            $month = substr($tgl_bayar, 5, 2);

            $startDate = \Carbon\Carbon::createFromFormat('Y-m', "$year-$month")->startOfMonth();
            $endDate = \Carbon\Carbon::createFromFormat('Y-m', "$year-$month")->endOfMonth();

            $query->whereBetween('tgl_bayar', [$startDate, $endDate]);
        }

        // Filter berdasarkan sales (cmb_sales)
        if ($cmb_sales) {
            $query->where('id_sales', $cmb_sales);
        }

        // // Filter berdasarkan user yang login
        // if ($user->role == 'super_admin' || $user->role == 'admin' || $user->role == 'manager') {
        //     // Jika admin (role 1), tampilkan semua data
        //     // Tidak perlu filter tambahan
        // } else {
        //     // Jika bukan admin, filter berdasarkan id_sales yang sesuai dengan user yang login
        //     $query->where('id_sales', $user->id);
        // }

        $total = $query->count();

        // Apply pagination
        $results = $query->offset($start)
                        ->limit($length)
                        ->get();

        // Hitung persentase_margin untuk setiap record
        $results = $results->map(function ($item) {
            $grossProvit = $item->gross_provit ?? 0;
            $validasiPayment = $item->validasi_payment ?? 0;

            // Hitung persentase margin: (gross_provit / validasi_payment) * 100
            if ($validasiPayment > 0) {
                $persentaseMargin = ($grossProvit / $validasiPayment) * 100;
                $item->persentase_margin = round($persentaseMargin, 2);
            } else {
                $item->persentase_margin = 0;
            }

            return $item;
        });

        return response()->json([
            'draw' => $draw,
            'recordsTotal' => $total,
            'recordsFiltered' => $total,
            'data' => $results
        ]);
    }

    public function getSales()
    {
        $result = User::query()
            ->where('role', 'sales')
            ->when(request('q'), function ($query, $term) {
                $query->where('name', 'like', '%' . $term . '%');
            })
            ->orderBy('name')
            ->get(['id', 'name']);

        return response()->json([
            'error' => 0,
            'message' => 'Success',
            'data'=> $result
        ]);
    }
}

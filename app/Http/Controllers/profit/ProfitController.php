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
    $created_at = request()->get('created_at');
    $cmb_sales = request()->get('cmb_sales');

    $query = VwProfit::query();

    if ($created_at) {
        $year = substr($created_at, 0, 4);
        $month = substr($created_at, 5, 2);

        $startDate = \Carbon\Carbon::createFromFormat('Y-m', "$year-$month")->startOfMonth();
        $endDate = \Carbon\Carbon::createFromFormat('Y-m', "$year-$month")->endOfMonth();

        $query->whereBetween('created_at', [$startDate, $endDate]);
    }

    if ($cmb_sales) {
        $query->where('id_sales', $cmb_sales);
    }

    $results = $query->get();

    // Hitung persentase margin
    $results = $results->map(function ($item) {
        $grossProvit = $item->gross_provit ?? 0;
        $validasiPayment = $item->validasi_payment ?? 0;

        if ($validasiPayment > 0) {
            $item->persentase_margin = round(($grossProvit / $validasiPayment) * 100, 2);
        } else {
            $item->persentase_margin = 0;
        }

        return $item;
    });

    return response()->json($results);   // ← KUNCI UTAMA
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

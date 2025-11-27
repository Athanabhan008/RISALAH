<?php

namespace App\Http\Controllers\profit;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProfitController extends Controller
{
    public function index()
    {
        return view('profit/index',[

            "active" => 'profit'

        ]);
    }
}

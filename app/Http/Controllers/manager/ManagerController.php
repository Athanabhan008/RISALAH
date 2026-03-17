<?php

namespace App\Http\Controllers\manager;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class ManagerController extends Controller
{
    public function index()
    {

        $today = date('Y-m-d');
        $month = date('Ym');
        $year = date('Y');


        //BOOKING


        return view('menu.index', [
            "active" => 'manager'
        ]);
    }
}

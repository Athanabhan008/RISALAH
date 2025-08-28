<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class StaffController extends Controller
{
    public function index()
    {

        $today = date('Y-m-d');
        $month = date('Ym');
        $year = date('Y');


        //BOOKING


        return view('pr.pr_wapu', [
            "active" => 'pr_wapu'
        ]);
    }
}

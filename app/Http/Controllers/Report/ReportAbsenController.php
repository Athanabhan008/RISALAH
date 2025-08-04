<?php

namespace App\Http\Controllers\Report;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ReportAbsenController extends Controller
{
    public function index()
    {
        return view ('report.absen', [
            "active" => 'report'
        ]);
    }
}

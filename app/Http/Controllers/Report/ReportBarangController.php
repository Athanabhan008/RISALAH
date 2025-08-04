<?php

namespace App\Http\Controllers\Report;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ReportBarangController extends Controller
{
    public function index()
    {
        return view ('report.barang', [
            "active" => 'report'
        ]);
    }
}

<?php

namespace App\Http\Controllers\absen;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AbsenController extends Controller
{
    public function index()
    {
        return view('absen.home', [
            "active" => 'data_absen'

        ]);
    }
}

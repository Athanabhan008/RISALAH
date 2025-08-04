<?php

namespace App\Http\Controllers\manager;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PaketLaundryController extends Controller
{
    public function index()
    {
        return view('laundry.paket_laundry');
    }
    public function tambah_paket()
    {
        return view('laundry.tambah_paket');
    }
    public function proses_tambah_paket()
    {

    }
}

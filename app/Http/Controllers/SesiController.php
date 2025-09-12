<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SesiController extends Controller
{
    public function index()
    {
        return view('login.login');
    }
    public function login(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'password' => 'required'
        ],[
            'name.reuired' => 'name Wajid Diisi',
            'password.reuired' => 'Password Wajid Diisi',
        ]);

        $infologin = [
            'name' => $request->name,
            'password' => $request->password,
        ];
        if(Auth::attempt($infologin)){
            if (Auth::user()->role == 'manager'){
                return redirect('/manager');

            }elseif (Auth::user()->role == 'sales'){
                return redirect('/sales');

            }elseif (Auth::user()->role == 'teknisi'){
                return redirect('/teknisi');

            }elseif (Auth::user()->role == 'super_admin'){
                return redirect('/manager');

            }

        }else{
            return redirect('')->withErrors('Username Dan Password Yang Dimasukkan Tidak Sesuai')->withInput();
        }
    }
    public function logout()
    {
        Auth::logout();
        return redirect('');
    }

    public function error()
    {
        return view('errors.error_login');
    }
}

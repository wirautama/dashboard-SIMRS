<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Session;
use App\Models\User;
use Hash;

class AuthController extends Controller
{
    public function index()
    {
        if (Auth::check()) {
            return redirect('home');
        }else{
            return view('auth.login');
        }
    }

    public function login(Request $request)
    {
        
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required']
        ]);

        if (Auth::Attempt($credentials)) {
            return redirect('/management/home');
        }else{
            Session::flash('message', 'Email atau Password Salah');
            Session::flash('alert-class', 'alert-danger');
            return redirect('/'); //->with(['error' => 'Email atau Password Salah']);
        }
    }

    public function logout() 
    {
        Session::flush();
        Auth::logout();
        return redirect('/');
    }

}
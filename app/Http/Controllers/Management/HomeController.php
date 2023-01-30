<?php

namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HomeController extends Controller
{

    private $menu;

    public function __construct()
    {
        $this->middleware('auth');
        $this->menu = 'sales';    
    }

    public function index(Request $request)
    {
        $modul = $this->menu;
        return view('management.home.index', compact('modul'));
    }
}
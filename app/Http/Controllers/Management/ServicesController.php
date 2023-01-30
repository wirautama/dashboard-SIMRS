<?php

namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ServicesController extends Controller
{
    private $menu;

    public function __construct()
    {
        $this->middleware('auth');
        $this->menu = 'services';  
    }

    public function index(Request $request)
    {
        $modul = $this->menu;
        return view('management.services.index', compact('modul'));
    }
}
<?php

namespace App\Http\Controllers\Util;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use DataTables;
use Carbon;

class UtilController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function getDokter()
    {  
        $doctor = DB::connection('mysql_khanza')->select("select kd_dokter, nm_dokter  from dokter order by kd_dokter");
        return response()->json($doctor);
    }
}
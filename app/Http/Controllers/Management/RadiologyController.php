<?php

namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use DataTables;
use Carbon;

class RadiologyController extends Controller
{

    private $menu;

    public function __construct()
    {
        $this->middleware('auth');
        $this->menu = 'radiology';  
    }

    public function index(Request $request)
    {
        $modul = $this->menu;
        $doctor = DB::connection('mysql_khanza')->select("select kd_dokter, nm_dokter  from dokter order by kd_dokter");
        $perujuk = DB::connection('mysql_khanza')->select("select kd_dokter, nm_dokter  from dokter order by kd_dokter");
        $penjab = DB::connection('mysql_khanza')->select("select kd_pj, png_jawab  from penjab  ");

        return view('management.radiology.index', compact('modul', 'doctor', 'perujuk', 'penjab'));
    }

    public function getTindakanRadiologi(Request $request)
    {   
        if(!empty($request->start)){
            $start = $request->start;
        }else{
            $start=Carbon\Carbon::now()->isoFormat('YYYY-MM-DD');
        }

        if(!empty($request->end)){
            $end = $request->end;
        }else{
            $end=Carbon\Carbon::now()->isoFormat('YYYY-MM-DD');
        }

        $doctor = !empty($request->doctor) ?  $request->doctor : "all";
        $perujuk = !empty($request->perujuk) ?  $request->perujuk : "all";
        $penjamin = !empty($request->penjamin) ?  $request->penjamin : "all";
        

        if(request()->ajax()){
            //Get Tindakan Radiologi
            $sql = "select a.no_rawat,
                    h.no_nota as nota_jalan,
                    i.no_nota as nota_inap, 
                    a.tgl_periksa,
                    d.no_rkm_medis, 
                    e.nm_pasien , 
                    b.nm_dokter , 
                    c.nm_dokter as nm_dokter_perujuk, 
                    f.nm_perawatan , 
                    a.biaya,
                    g.png_jawab ,
                    (Select  X.no_sep from bridging_sep X  where X.no_rawat=a.no_rawat LIMIT 1 ) no_sep, 
                    a.status ,
                    j.nm_poli as unit
                    from periksa_radiologi a 
                    LEFT JOIN dokter b ON a.kd_dokter = b.kd_dokter
                    LEFT JOIN dokter c ON a.dokter_perujuk = c.kd_dokter
                    LEFT JOIN reg_periksa d ON a.no_rawat = d.no_rawat
                    LEFT JOIN pasien e ON d.no_rkm_medis = e.no_rkm_medis
                    LEFT JOIN jns_perawatan_radiologi f ON a.kd_jenis_prw = f.kd_jenis_prw
                    LEFT JOIN penjab g ON d.kd_pj = g.kd_pj
                    LEFT JOIN nota_jalan h ON a.no_rawat = h.no_rawat
                    LEFT JOIN nota_inap i ON a.no_rawat = i.no_rawat
                    LEFT JOIN poliklinik j on d.kd_poli = j.kd_poli
                    LEFT JOIN bridging_sep k ON (a.no_rawat=k.no_rawat)

                    WHERE a.tgl_periksa BETWEEN '".$start."' and '".$end."' ";
                    
            
            $sql = ($doctor!="all") ? $sql."and a.kd_dokter= '".$doctor."' ": $sql." ";
            $sql = ($perujuk!="all") ? $sql."and a.dokter_perujuk= '".$perujuk."' ": $sql." ";
            $sql = ($penjamin!="all") ? $sql."and d.kd_pj= '".$penjamin."' ": $sql." ";
            $sql = $sql." ORDER BY i.no_nota";

            
            $tindakan = DB::connection('mysql_khanza')->select($sql);

            return Datatables::of($tindakan)
                    ->addIndexColumn()
                    ->make(true);

        }            

        $modul = $this->menu;
        return view('management.radiology.index', compact('modul'));
        
    }    
}
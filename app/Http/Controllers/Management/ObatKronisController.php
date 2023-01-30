<?php

namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use DataTables;
use Carbon;

class ObatKronisController extends Controller
{

    private $menu;

    public function __construct()
    {
        $this->middleware('auth');
        $this->menu = 'obat-kronis';  
    }

    public function index(Request $request)
    {
        $modul = $this->menu;
        $doctor = DB::connection('mysql_khanza')->select("select kd_dokter, nm_dokter  from dokter order by kd_dokter");
        $poli = DB::connection('mysql_khanza')->select("select kd_poli, nm_poli  from poliklinik  order by kd_poli ");
        $penjab = DB::connection('mysql_khanza')->select("select kd_pj, png_jawab  from penjab  ");

        return view('management.pharmacy.obatkronis', compact('modul', 'doctor', 'poli', 'penjab'));
    }

    public function getObatKronis(Request $request)
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
        $poli = !empty($request->poli) ?  $request->poli : "all";
        $penjamin = !empty($request->penjamin) ?  $request->penjamin : "all";
        

        if(request()->ajax()){
            //Get Tindakan Obat Kronis
            $sql = "select piutang.tgl_piutang,
            reg_periksa.no_rawat,
            piutang.nm_pasien,
		    bridging_sep.no_sep,
            penjab.png_jawab,
			dokter.nm_dokter,
		    poliklinik.nm_poli,
			reg_periksa.status_lanjut,
            piutang.nota_piutang,  
            piutang.no_rkm_medis,
            detailpiutang.kode_brng, 
            databarang.nama_brng,
            detailpiutang.h_beli, 
            detailpiutang.h_jual, 
            detailpiutang.jumlah, 
            detailpiutang.total as h_jualxjml,
            piutang.sisapiutang as total_piutang
            FROM detailpiutang
            INNER JOIN databarang ON detailpiutang.kode_brng=databarang.kode_brng
            LEFT JOIN piutang ON detailpiutang.nota_piutang=piutang.nota_piutang
            LEFT JOIN reg_periksa on piutang.no_rkm_medis = reg_periksa.no_rkm_medis
			LEFT JOIN bridging_sep on reg_periksa.no_rawat = bridging_sep.no_rawat
            LEFT JOIN penjab on reg_periksa.kd_pj = penjab.kd_pj
			LEFT JOIN dokter ON reg_periksa.kd_dokter = dokter.kd_dokter
			LEFT JOIN poliklinik on reg_periksa.kd_poli=poliklinik.kd_poli
            WHERE piutang.tgl_piutang BETWEEN '".$start."' and '".$end."' ";    

            $sql = ($doctor!="all") ? $sql."and reg_periksa.kd_dokter= '".$doctor."' ": $sql." ";
            $sql = ($poli!="all") ? $sql."and reg_periksa.kd_poli= '".$poli."' ": $sql." ";
            $sql = ($penjamin!="all") ? $sql."and d.kd_pj= '".$penjamin."' ": $sql." ";
            $sql = $sql."ORDER BY piutang.nota_piutang";
            
            $tindakan = DB::connection('mysql_khanza')->select($sql);

            return Datatables::of($tindakan)
                    ->addIndexColumn()
                    ->make(true);

        }            

        $modul = $this->menu;
        return view('management.pharmacy.obatkronis', compact('modul'));
        
    }    
}
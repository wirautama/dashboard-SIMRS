<?php

namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use DataTables;
use Carbon;

class LaboratController extends Controller
{

    private $menu;

    public function __construct()
    {
        $this->middleware('auth');
        $this->menu = 'laborat';  
    }

    public function index(Request $request)
    {
        $modul = $this->menu;
        $perujuk = DB::connection('mysql_khanza')->select("select kd_dokter, nm_dokter  from dokter order by kd_dokter");
        $penjab = DB::connection('mysql_khanza')->select("select kd_pj, png_jawab  from penjab  ");

        return view('management.laborat.index', compact('modul', 'perujuk', 'penjab'));
    }

    public function getTindakanLaborat(Request $request)
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

        $perujuk = !empty($request->perujuk) ?  $request->perujuk : "all";
        $penjamin = !empty($request->penjamin) ?  $request->penjamin : "all";
        

        if(request()->ajax()){
            //Get Tindakan Laborat
            $sql = "select nota_jalan.no_nota as nota_jalan, nota_inap.no_nota AS nota_inap, permintaan_lab.tgl_hasil as tgl_input_hasil_lab, permintaan_lab.noorder, bridging_sep.no_sep, permintaan_lab.no_rawat, reg_periksa.tgl_registrasi, reg_periksa.no_rkm_medis, pasien.nm_pasien, GROUP_CONCAT(template_laboratorium.Pemeriksaan) AS tindakan_lab, dokter.nm_dokter, penjab.png_jawab as jenis_bayar, reg_periksa.status_lanjut,
            ((select SUM(biaya) FROM periksa_lab WHERE periksa_lab.no_rawat=permintaan_lab.no_rawat) + (select SUM(biaya_item) 
            FROM detail_periksa_lab WHERE detail_periksa_lab.no_rawat=permintaan_lab.no_rawat)) as total, reg_periksa.stts
            FROM permintaan_lab
            LEFT JOIN bridging_sep ON permintaan_lab.no_rawat=bridging_sep.no_rawat
            INNER JOIN reg_periksa ON permintaan_lab.no_rawat=reg_periksa.no_rawat
            INNER JOIN pasien ON reg_periksa.no_rkm_medis=pasien.no_rkm_medis
            INNER JOIN detail_periksa_lab ON permintaan_lab.no_rawat=detail_periksa_lab.no_rawat
            INNER JOIN template_laboratorium ON detail_periksa_lab.id_template=template_laboratorium.id_template
            INNER JOIN periksa_lab ON permintaan_lab.no_rawat=periksa_lab.no_rawat
            INNER JOIN dokter ON periksa_lab.dokter_perujuk=dokter.kd_dokter
            INNER JOIN penjab ON reg_periksa.kd_pj=penjab.kd_pj
            LEFT JOIN nota_jalan ON permintaan_lab.no_rawat=nota_jalan.no_rawat
            LEFT JOIN nota_inap ON permintaan_lab.no_rawat=nota_inap.no_rawat
            WHERE DATE(reg_periksa.tgl_registrasi) BETWEEN '".$start."' and '".$end."' ";
                    
            
            $sql = ($perujuk!="all") ? $sql."and a.dokter_perujuk= '".$perujuk."' ": $sql." ";
            $sql = ($penjamin!="all") ? $sql."and d.kd_pj= '".$penjamin."' ": $sql." ";
            $sql = $sql."GROUP BY permintaan_lab.noorder";

            
            $tindakan = DB::connection('mysql_khanza')->select($sql);

            return Datatables::of($tindakan)
                    ->addIndexColumn()
                    ->make(true);

        }            

        $modul = $this->menu;
        return view('management.laborat.index', compact('modul'));
        
    }    
}
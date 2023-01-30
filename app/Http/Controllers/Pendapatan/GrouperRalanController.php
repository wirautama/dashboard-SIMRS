<?php

namespace App\Http\Controllers\Pendapatan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use DataTables;
use Carbon;

class GrouperRalanController extends Controller {
    private $menu;

    public function __construct()
    {
        $this->middleware('auth');
        $this->menu = 'grouper-ralan';    
    }

    public function index()
    {
        $modul = $this->menu;
        $doctor = DB::connection('mysql_khanza')->select("select kd_dokter, nm_dokter  from dokter order by kd_dokter");
        $poli = DB::connection('mysql_khanza')->select("select kd_poli, nm_poli  from poliklinik  order by kd_poli ");
        $penjab = DB::connection('mysql_khanza')->select("select kd_pj, png_jawab  from penjab  ");
        
        return view('pendapatan.grouperralan', compact('modul', 'doctor', 'poli', 'penjab'));
    }

    public function getGrouperRalan(Request $request)
    {   
        // dd($request->start, $request->end);
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
            //Get Obat Ralan PerDokter
            $sql = "select reg_periksa.no_rawat,reg_periksa.status_bayar,
            (
                select no_nota FROM nota_jalan WHERE reg_periksa.no_rawat=nota_jalan.no_rawat
            ) as nota_jalan,
            reg_periksa.tgl_registrasi,reg_periksa.no_rkm_medis,pasien.nm_pasien,pasien.alamat,poliklinik.nm_poli,dokter.nm_dokter,penjab.png_jawab,
            (
                select inacbg_grouping_stage12.no_sep FROM inacbg_grouping_stage12 LEFT JOIN bridging_sep ON inacbg_grouping_stage12.no_sep=bridging_sep.no_sep WHERE bridging_sep.no_rawat=reg_periksa.no_rawat LIMIT 1
            ) as no_sep,
            (
                select inacbg_grouping_stage12.code_cbg FROM inacbg_grouping_stage12 LEFT JOIN bridging_sep ON inacbg_grouping_stage12.no_sep=bridging_sep.no_sep WHERE bridging_sep.no_rawat=reg_periksa.no_rawat LIMIT 1
            ) as code_cbg,
            (
                select inacbg_grouping_stage12.deskripsi FROM inacbg_grouping_stage12 LEFT JOIN bridging_sep ON inacbg_grouping_stage12.no_sep=bridging_sep.no_sep WHERE bridging_sep.no_rawat=reg_periksa.no_rawat LIMIT 1
            ) as deskripsi,
            (
                select piutang_pasien.totalpiutang FROM piutang_pasien WHERE reg_periksa.no_rawat=piutang_pasien.no_rawat
            ) as total_real_rs,
            (
                select inacbg_grouping_stage12.tarif FROM inacbg_grouping_stage12 LEFT JOIN bridging_sep ON inacbg_grouping_stage12.no_sep=bridging_sep.no_sep WHERE bridging_sep.no_rawat=reg_periksa.no_rawat LIMIT 1
            ) as inacbg,
            (
                select (inacbg_grouping_stage12.tarif - piutang_pasien.sisapiutang) as selisih FROM inacbg_grouping_stage12 
                LEFT JOIN bridging_sep ON inacbg_grouping_stage12.no_sep=bridging_sep.no_sep
                LEFT JOIN piutang_pasien ON bridging_sep.no_rawat=piutang_pasien.no_rawat	
                WHERE bridging_sep.no_rawat=reg_periksa.no_rawat LIMIT 1
            ) as selisih,
            (
                select SUM(detail_pemberian_obat.biaya_obat * detail_pemberian_obat.jml) as total_obat FROM detail_pemberian_obat
                WHERE detail_pemberian_obat.no_rawat=reg_periksa.no_rawat
            ) as obat,
            (
                (select SUM(biaya) FROM periksa_lab WHERE reg_periksa.no_rawat=periksa_lab.no_rawat ) + 
                            (select SUM(biaya_item) FROM detail_periksa_lab WHERE detail_periksa_lab.no_rawat=reg_periksa.no_rawat)
            ) as laborat,
            (
                select SUM(biaya) FROM periksa_radiologi WHERE periksa_radiologi.no_rawat=reg_periksa.no_rawat
            ) as radiologi
            FROM reg_periksa
            INNER JOIN pasien INNER JOIN poliklinik INNER JOIN dokter INNER JOIN penjab 
            ON reg_periksa.no_rkm_medis=pasien.no_rkm_medis
            AND reg_periksa.kd_poli=poliklinik.kd_poli
            AND reg_periksa.kd_dokter=dokter.kd_dokter
            AND reg_periksa.kd_pj=penjab.kd_pj
                    AND reg_periksa.status_lanjut='Ralan'
                    AND DATE(reg_periksa.tgl_registrasi)
                    AND NOT reg_periksa.stts='Batal'  
            between '".$start."' and '".$end."'";
            //reg_periksa.tgl_registrasi between '".$start."' and '".$end."' ";
            $sql = ($doctor!="all") ? $sql."and reg_periksa.kd_dokter= '".$doctor."' ": $sql." ";
            $sql = ($poli!="all") ? $sql."and reg_periksa.kd_poli= '".$poli."' ": $sql." ";
            $sql = ($penjamin!="all") ? $sql."and reg_periksa.kd_pj= '".$penjamin."' ": $sql." ";
            $sql = $sql."GROUP BY reg_periksa.no_rawat";
            
            
            $grouperralan = DB::connection('mysql_khanza')->select($sql);
            
            return Datatables::of($grouperralan)
                    ->addIndexColumn()
                    ->make(true);

        }            

        $modul = $this->menu;
        return view('pendapatan.grouperralan', compact('modul'));
        
    }

    


}
?>
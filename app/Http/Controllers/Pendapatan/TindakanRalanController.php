<?php

namespace App\Http\Controllers\Pendapatan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use DataTables;
use Carbon;

class TindakanRalanController extends Controller
{

    private $menu;

    public function __construct()
    {
        $this->middleware('auth');
        $this->menu = 'tindakan-ralan';    
    }

    public function index()
    {
        $modul = $this->menu;
        $doctor = DB::connection('mysql_khanza')->select("select kd_dokter, nm_dokter  from dokter order by kd_dokter");
        $poli = DB::connection('mysql_khanza')->select("select kd_poli, nm_poli  from poliklinik  order by kd_poli ");
        $penjab = DB::connection('mysql_khanza')->select("select kd_pj, png_jawab  from penjab  ");
        
        return view('pendapatan.ralan', compact('modul', 'doctor', 'poli', 'penjab'));
    }

    public function getTindakanRalan(Request $request)
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
            //Get Tindakan Ralan PerDokter
            $sql = "select 
                    reg_periksa.no_rawat, nota_jalan.no_nota, reg_periksa.tgl_registrasi, 
                    pasien.no_rkm_medis, pasien.nm_pasien, 
                    reg_periksa.kd_dokter, dokter.nm_dokter,
                    penjab.png_jawab,(Select  X.no_sep from bridging_sep X  where X.no_rawat=reg_periksa.no_rawat LIMIT 1 ) no_sep,
                    rawat_jl_dr.kd_jenis_prw, jns_perawatan.nm_perawatan,
                    poliklinik.nm_poli, rawat_jl_dr.biaya_rawat 
                    from reg_periksa 
                    inner join rawat_jl_dr
                    inner join jns_perawatan
                    inner join poliklinik
                    inner join pasien
                    inner join penjab 
                    inner join dokter 
                    inner join nota_jalan 
                    on reg_periksa.no_rkm_medis=pasien.no_rkm_medis 
                    and rawat_jl_dr.no_rawat = reg_periksa.no_rawat
                    and reg_periksa.kd_pj=penjab.kd_pj 
                    and reg_periksa.kd_dokter=dokter.kd_dokter 
                    and reg_periksa.kd_poli = poliklinik.kd_poli 
                    and rawat_jl_dr.kd_jenis_prw = jns_perawatan.kd_jenis_prw 
                    and reg_periksa.no_rawat=nota_jalan.no_rawat 
                    where reg_periksa.status_lanjut='Ralan' and 
                    concat(nota_jalan.tanggal,' ',nota_jalan.jam) between '".$start."' and '".$end."' ";
            
            //reg_periksa.tgl_registrasi between '".$start."' and '".$end."' ";
            $sql = ($doctor!="all") ? $sql."and reg_periksa.kd_dokter= '".$doctor."' ": $sql." ";
            $sql = ($poli!="all") ? $sql."and reg_periksa.kd_poli= '".$poli."' ": $sql." ";
            $sql = ($penjamin!="all") ? $sql."and reg_periksa.kd_pj= '".$penjamin."' ": $sql." ";
            $sql = $sql." order by nota_jalan.no_nota";

            
            $tindakan = DB::connection('mysql_khanza')->select($sql);

            return Datatables::of($tindakan)
                    ->addIndexColumn()
                    ->make(true);

        }            

        $modul = $this->menu;
        return view('pendapatan.ralan', compact('modul'));
        
    }

    // public function getTindakanRanap(Request $request)
    // {   
        
    //     if(!empty($request->start)){
    //         $start = $request->start;
    //     }else{
    //         $start=Carbon\Carbon::now()->isoFormat('YYYY-MM-DD');
    //     }

    //     if(!empty($request->end)){
    //         $end = $request->end;
    //     }else{
    //         $end=Carbon\Carbon::now()->isoFormat('YYYY-MM-DD');
    //     }

    //     $doctor = !empty($request->doctor) ?  $request->doctor : "all";
    //     $poli = !empty($request->poli) ?  $request->poli : "all";
    //     $penjamin = !empty($request->penjamin) ?  $request->penjamin : "all";
        

    //     if(request()->ajax()){
    //         //Get Tindakan Ralan PerDokter
    //         $sql = "select e.no_rawat,
    //                 b.no_rkm_medis,
    //                 a.nm_pasien,
    //                 e.kd_jenis_prw,
    //                 c.nm_perawatan,
    //                 e.kd_dokter,
    //                 d.nm_dokter,
    //                 e.tgl_perawatan,
    //                 e.jam_rawat,
    //                 g.png_jawab,
    //                 f.nm_poli, 
    //                 e.material,
    //                 e.tarif_tindakandr,
    //                 e.kso,
    //                 e.menejemen,
    //                 e.biaya_rawat 
    //                 from pasien a
    //                 inner join reg_periksa b
    //                 inner join jns_perawatan c
    //                 inner join dokter d
    //                 inner join rawat_jl_dr e
    //                 inner join poliklinik f
    //                 inner join penjab g
    //                 on e.no_rawat = b.no_rawat
    //                 and b.no_rkm_medis = a.no_rkm_medis 
    //                 and b.kd_pj = g.kd_pj 
    //                 and b.kd_poli = f.kd_poli 
    //                 and e.kd_jenis_prw = c.kd_jenis_prw 
    //                 and b.kd_dokter = d.kd_dokter
    //                 where b.tgl_registrasi between '".$start."' and '".$end."' ";

    //         $sql = ($doctor!="all") ? $sql."and b.kd_dokter= '".$doctor."' ": $sql." ";
    //         $sql = ($poli!="all") ? $sql."and b.kd_poli= '".$poli."' ": $sql." ";
    //         $sql = ($penjamin!="all") ? $sql."and b.kd_pj= '".$penjamin."' ": $sql." ";
    //         $sql = $sql." order by e.no_rawat desc";

            
    //         $tindakan = DB::connection('mysql_khanza')->select($sql);

    //         return Datatables::of($tindakan)
    //                 ->addIndexColumn()
    //                 ->make(true);

    //     }            

    //     $modul = $this->menu;
    //     return view('finance.pendapatan.ralan', compact('modul'));
        
    // }

    
    // public function getGrouperRalan($start, $end)
    // {
    //     $tindakan = DB::connection('mysql_khanza')->select(
    //         "SELECT reg_periksa.no_rawat, nota_jalan.no_nota, reg_periksa.tgl_registrasi, reg_periksa.no_rkm_medis, pasien.nm_pasien, pasien.alamat, poliklinik.nm_poli, dokter.nm_dokter, reg_periksa.stts, penjab.png_jawab, inacbg_grouping_stage12.no_sep, inacbg_grouping_stage12.code_cbg, inacbg_grouping_stage12.deskripsi, piutang_pasien.totalpiutang, inacbg_grouping_stage12.tarif as inacbg, (inacbg_grouping_stage12.tarif - piutang_pasien.totalpiutang) AS selisih, SUM(detail_pemberian_obat.biaya_obat * detail_pemberian_obat.jml) as total_obat
    //         FROM reg_periksa
    //         INNER JOIN poliklinik ON reg_periksa.kd_poli=poliklinik.kd_poli
    //         LEFT JOIN nota_jalan ON reg_periksa.no_rawat=nota_jalan.no_rawat
    //         LEFT JOIN pasien ON reg_periksa.no_rkm_medis=pasien.no_rkm_medis
    //         INNER JOIN dokter ON reg_periksa.kd_dokter=dokter.kd_dokter
    //         LEFT JOIN bridging_sep ON reg_periksa.no_rawat=bridging_sep.no_rawat
    //         LEFT JOIN piutang_pasien ON reg_periksa.no_rawat=piutang_pasien.no_rawat
    //         INNER JOIN penjab ON reg_periksa.kd_pj=penjab.kd_pj
    //         LEFT JOIN inacbg_grouping_stage12 ON bridging_sep.no_sep=inacbg_grouping_stage12.no_sep
    //         LEFT JOIN detail_pemberian_obat ON reg_periksa.no_rawat=detail_pemberian_obat.no_rawat
    //         WHERE reg_periksa.tgl_registrasi BETWEEN '".$start."' AND '".$end."'
    //         AND reg_periksa.kd_pj = 'BPJ'
    //         AND reg_periksa.status_lanjut = 'Ralan'
    //         GROUP BY reg_periksa.no_rawat
            
            
    //         ");

    //         return Datatables::of($tindakan)
    //                 ->addIndexColumn()
    //                 ->make(true);
    // }


}
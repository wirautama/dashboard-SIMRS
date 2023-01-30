<?php

namespace App\Http\Controllers\Pendapatan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use DataTables;
use Carbon;

class TindakanRanapController extends Controller
{

    private $menu;

    public function __construct()
    {
        $this->middleware('auth');
        $this->menu = 'tindakan-ranap';    
    }

    public function index()
    {
        $modul = $this->menu;
        $doctor = DB::connection('mysql_khanza')->select("select kd_dokter, nm_dokter from dokter order by kd_dokter");
        $penjab = DB::connection('mysql_khanza')->select("select kd_pj, png_jawab from penjab");
        
        return view('pendapatan.ranap', compact('modul', 'doctor', 'penjab'));
    }

    

    public function getTindakanRanap(Request $request)
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
        $penjamin = !empty($request->penjamin) ?  $request->penjamin : "all";
        

        if(request()->ajax()){
            //Get Tindakan Ralan PerDokter
            $sql = "select rawat_inap_dr.no_rawat, nota_inap.no_nota,
                    reg_periksa.no_rkm_medis,reg_periksa.tgl_registrasi, 
                    pasien.nm_pasien,
                    rawat_inap_dr.kd_jenis_prw,
                    jns_perawatan_inap.nm_perawatan,
                    rawat_inap_dr.kd_dokter,
                    dokter.nm_dokter,
                    rawat_inap_dr.tgl_perawatan,
                    rawat_inap_dr.jam_rawat,
                    penjab.png_jawab,
                    (Select  X.no_sep from bridging_sep X  where X.no_rawat=reg_periksa.no_rawat LIMIT 1 ) no_sep,
                    (
                        select bangsal.nm_bangsal
                        from kamar_inap 
                        inner join kamar 
                        inner join bangsal 
                        on kamar_inap.kd_kamar=kamar.kd_kamar 
                        and kamar.kd_bangsal=bangsal.kd_bangsal 
                        where kamar_inap.no_rawat=rawat_inap_dr.no_rawat limit 1 
                    )
                    as nm_bangsal, 
                    rawat_inap_dr.biaya_rawat 
                    from pasien inner join reg_periksa inner join jns_perawatan_inap inner join 
                    dokter inner join rawat_inap_dr inner join penjab inner join nota_inap 
                    on rawat_inap_dr.no_rawat=reg_periksa.no_rawat 
                    and reg_periksa.no_rkm_medis=pasien.no_rkm_medis 
                    and reg_periksa.kd_pj=penjab.kd_pj 
                    and rawat_inap_dr.kd_jenis_prw=jns_perawatan_inap.kd_jenis_prw 
                    and rawat_inap_dr.kd_dokter=dokter.kd_dokter 
                    and reg_periksa.no_rawat=nota_inap.no_rawat
                    where concat(nota_inap.tanggal,' ',nota_inap.jam) between '".$start."' and '".$end."' ";
                        

            $sql = ($doctor!="all") ? $sql."and rawat_inap_dr.kd_dokter= '".$doctor."' ": $sql." ";
            $sql = ($penjamin!="all") ? $sql."and reg_periksa.kd_pj= '".$penjamin."' ": $sql." ";
            $sql = $sql." order by nota_inap.no_nota desc";

            
            $tindakan = DB::connection('mysql_khanza')->select($sql);

            return Datatables::of($tindakan)
                    ->addIndexColumn()
                    ->make(true);

        }            

        $modul = $this->menu;
        return view('pendapatan.ranap', compact('modul'));
        
    }


}
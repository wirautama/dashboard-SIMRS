<?php

namespace App\Http\Controllers\Pendapatan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use DataTables;
use Carbon;

class TindakanOperatorController extends Controller
{

    private $menu;

    public function __construct()
    {
        $this->middleware('auth');
        $this->menu = 'tindakan-operator';    
    }

    public function index()
    {
        $modul = $this->menu;
        $doctor = DB::connection('mysql_khanza')->select("select kd_dokter, nm_dokter  from dokter order by kd_dokter");
        $penjab = DB::connection('mysql_khanza')->select("select kd_pj, png_jawab  from penjab  ");
        
        return view('pendapatan.operator', compact('modul', 'doctor', 'penjab'));
    }

    public function getTindakanOperator(Request $request)
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
            //Get Tindakan Operarator
            $sql = "select a.no_rawat, 
                    g.no_nota as nota_rawat_jalan,
                    h.no_nota as nota_rawat_inap,
                    b.no_rkm_medis, 
                    c.nm_pasien,
                    (
                        select kamar.kelas	
                        from kamar_inap 
                        inner join kamar 
                        inner join bangsal
                        inner join rawat_inap_dr 
                        on kamar_inap.kd_kamar=kamar.kd_kamar 
                        and kamar.kd_bangsal=bangsal.kd_bangsal 
                        where kamar_inap.no_rawat=rawat_inap_dr.no_rawat limit 1 
                    )
                    as kelas,
                    i.png_jawab, 
                    (Select  X.no_sep from bridging_sep X  where X.no_rawat=b.no_rawat LIMIT 1 ) no_sep,
                    d.nm_perawatan,
                    a.status,
                    a.tgl_operasi, 
                    a.kategori, 
                    e.nm_dokter as nm_dokter_operator, 
                    a.biayaoperator1 
                    FROM operasi a
                    LEFT JOIN reg_periksa b on a.no_rawat = b.no_rawat
                    LEFT JOIN pasien c on b.no_rkm_medis = c.no_rkm_medis
                    LEFT JOIN paket_operasi d on a.kode_paket = d.kode_paket
                    LEFT JOIN dokter e on a.operator1 = e.kd_dokter
                    LEFT JOIN nota_jalan g on b.no_rawat = g.no_rawat
                    LEFT JOIN nota_inap h on b.no_rawat = h.no_rawat
                    LEFT JOIN penjab i on (b.kd_pj=i.kd_pj)
                    WHERE a.tgl_operasi BETWEEN '".$start."' and '".$end."' ";
                    
            
            $sql = ($doctor!="all") ? $sql."and a.operator1= '".$doctor."' ": $sql." ";
            $sql = ($penjamin!="all") ? $sql."and b.kd_pj= '".$penjamin."' ": $sql." ";
            $sql = $sql." order by g.no_nota ";

            
            $tindakan = DB::connection('mysql_khanza')->select($sql);

            return Datatables::of($tindakan)
                    ->addIndexColumn()
                    ->make(true);

        }            

        $modul = $this->menu;
        return view('pendapatan.operator', compact('modul'));
        
    }

    


}
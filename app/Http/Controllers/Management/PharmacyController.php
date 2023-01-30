<?php

namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use DataTables;
use Carbon;

class PharmacyController extends Controller
{

    private $menu;

    public function __construct()
    {
        $this->middleware('auth');
        $this->menu = 'obat-bebas';  
    }

    public function index(Request $request)
    {
        $modul = $this->menu;
        $doctor = DB::connection('mysql_khanza')->select("select kd_dokter, nm_dokter  from dokter order by kd_dokter");
        $poli = DB::connection('mysql_khanza')->select("select kd_poli, nm_poli  from poliklinik  order by kd_poli ");
        $penjab = DB::connection('mysql_khanza')->select("select kd_pj, png_jawab  from penjab  ");

        return view('management.pharmacy.index', compact('modul', 'doctor', 'poli', 'penjab'));
    }

    public function getObatBebas(Request $request)
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
            //Get Obat Bebas
            $sql = "select tgl_jual,
            reg_periksa.no_rawat,
            bridging_sep.no_sep,
            detailjual.nota_jual,
            penjualan.no_rkm_medis,
            nm_pasien,
            penjab.png_jawab,
            dokter.nm_dokter,
            poliklinik.nm_poli,
            detailjual.kode_brng,
            detailjual.jumlah,
            detailjual.kode_sat,
            databarang.nama_brng,
            detailjual.h_jual,
            detailjual.h_beli,
            ppn,
            detailjual.total,
            keterangan,
            jns_jual,
            nama_bayar,
            penjualan.status
            from penjualan 
            INNER JOIN detailjual on penjualan.nota_jual = detailjual.nota_jual
            LEFT JOIN reg_periksa on penjualan.no_rkm_medis = reg_periksa.no_rkm_medis
            LEFT JOIN penjab on reg_periksa.kd_pj =penjab.kd_pj 
            LEFT JOIN dokter ON reg_periksa.kd_dokter=dokter.kd_dokter
            LEFT JOIN poliklinik ON reg_periksa.kd_poli=poliklinik.kd_poli
            LEFT JOIN bridging_sep on reg_periksa.no_rawat = bridging_sep.no_rawat
            LEFT JOIN databarang ON detailjual.kode_brng=databarang.kode_brng
            WHERE tgl_jual  BETWEEN '".$start."' and '".$end."' ";    

            $sql = ($doctor!="all") ? $sql."and reg_periksa.kd_dokter= '".$doctor."' ": $sql." ";
            $sql = ($poli!="all") ? $sql."and reg_periksa.kd_poli= '".$poli."' ": $sql." ";
            $sql = ($penjamin!="all") ? $sql."and d.kd_pj= '".$penjamin."' ": $sql." ";
            $sql = $sql."ORDER BY nota_jual";
            
            $tindakan = DB::connection('mysql_khanza')->select($sql);

            return Datatables::of($tindakan)
                    ->addIndexColumn()
                    ->make(true);

        }            

        $modul = $this->menu;
        return view('management.pharmacy.index', compact('modul'));
        
    }    
}
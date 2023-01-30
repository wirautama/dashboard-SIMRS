<?php

namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use DataTables;
use Carbon;

class NonKronisController extends Controller
{

    private $menu;

    public function __construct()
    {
        $this->middleware('auth');
        $this->menu = 'non-kronis';  
    }

    public function index(Request $request)
    {
        $modul = $this->menu;
        $doctor = DB::connection('mysql_khanza')->select("select kd_dokter, nm_dokter  from dokter order by kd_dokter");
        $poli = DB::connection('mysql_khanza')->select("select kd_poli, nm_poli  from poliklinik  order by kd_poli ");
        $penjab = DB::connection('mysql_khanza')->select("select kd_pj, png_jawab  from penjab  ");

        return view('management.pharmacy.nonkronis', compact('modul', 'doctor', 'poli', 'penjab'));
    }

    public function getNonKronis(Request $request)
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
            //Get Tindakan Obat Non Kronis
            $sql = "select reg_periksa.tgl_registrasi,
            detail_pemberian_obat.no_rawat,
            bridging_sep.no_sep,
            resep_obat.no_resep,
            reg_periksa.no_rkm_medis,
            pasien.nm_pasien,
            penjab.png_jawab,
            dokter.nm_dokter,
            poliklinik.nm_poli,
            reg_periksa.status_lanjut,
            detail_pemberian_obat.kode_brng,
            databarang.nama_brng,
            detail_pemberian_obat.h_beli, 
            detail_pemberian_obat.biaya_obat as h_jual, 
            detail_pemberian_obat.jml,
            detail_pemberian_obat.total as h_jualxjml,
            (SUM(detail_pemberian_obat.biaya_obat * detail_pemberian_obat.jml)) as total_obat,
            (SUM(detail_pemberian_obat.biaya_obat * detail_pemberian_obat.jml)) * 11 / 100 as ppn,
            (SUM(detail_pemberian_obat.biaya_obat * detail_pemberian_obat.jml)) + ((SUM(detail_pemberian_obat.biaya_obat * 						detail_pemberian_obat.jml)) * 11 / 100) as t_obatppn
            FROM detail_pemberian_obat
            INNER JOIN reg_periksa ON detail_pemberian_obat.no_rawat=reg_periksa.no_rawat
            LEFT JOIN pasien ON reg_periksa.no_rkm_medis=pasien.no_rkm_medis
            LEFT JOIN penjab ON reg_periksa.kd_pj=penjab.kd_pj
            LEFT JOIN dokter ON reg_periksa.kd_dokter=dokter.kd_dokter
            LEFT JOIN poliklinik ON reg_periksa.kd_poli=poliklinik.kd_poli
            LEFT JOIN bridging_sep on reg_periksa.no_rawat = bridging_sep.no_rawat
            LEFT JOIN databarang ON detail_pemberian_obat.kode_brng=databarang.kode_brng
            LEFT JOIN resep_obat ON detail_pemberian_obat.no_rawat=resep_obat.no_rawat
            WHERE reg_periksa.tgl_registrasi  BETWEEN '".$start."' and '".$end."' ";    

            $sql = ($doctor!="all") ? $sql."and reg_periksa.kd_dokter= '".$doctor."' ": $sql." ";
            $sql = ($poli!="all") ? $sql."and reg_periksa.kd_poli= '".$poli."' ": $sql." ";
            $sql = ($penjamin!="all") ? $sql."and d.kd_pj= '".$penjamin."' ": $sql." ";
            $sql = $sql."GROUP BY reg_periksa.no_rawat";
            
            $tindakan = DB::connection('mysql_khanza')->select($sql);

            return Datatables::of($tindakan)
                    ->addIndexColumn()
                    ->make(true);

        }            

        $modul = $this->menu;
        return view('management.pharmacy.nonkronis', compact('modul'));
        
    }    
}
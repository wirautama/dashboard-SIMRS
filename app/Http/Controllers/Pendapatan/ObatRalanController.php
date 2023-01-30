<?php

namespace App\Http\Controllers\Pendapatan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use DataTables;
use Carbon;

class ObatRalanController extends Controller {
    private $menu;

    public function __construct()
    {
        $this->middleware('auth');
        $this->menu = 'obat-ralan';    
    }

    public function index()
    {
        $modul = $this->menu;
        $doctor = DB::connection('mysql_khanza')->select("select kd_dokter, nm_dokter  from dokter order by kd_dokter");
        $poli = DB::connection('mysql_khanza')->select("select kd_poli, nm_poli  from poliklinik  order by kd_poli ");
        $penjab = DB::connection('mysql_khanza')->select("select kd_pj, png_jawab  from penjab  ");
        
        return view('pendapatan.obatralan', compact('modul', 'doctor', 'poli', 'penjab'));
    }

    public function getObatRalan(Request $request)
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
            $sql = "select reg_periksa.tgl_registrasi,
            nota_jalan.no_nota,
            detail_pemberian_obat.no_rawat,
            (Select X.no_sep from bridging_sep as X  where X.jnspelayanan='2' and X.no_rawat=reg_periksa.no_rawat LIMIT 1) no_sep,
            pasien.nm_pasien,
            dokter.nm_dokter,
            penjab.png_jawab,
            reg_periksa.status_lanjut,
            poliklinik.nm_poli,
            detail_pemberian_obat.kode_brng,databarang.nama_brng,
            detail_pemberian_obat.h_beli, detail_pemberian_obat.biaya_obat as h_jual, detail_pemberian_obat.jml,
            detail_pemberian_obat.total as  total_obat,
            ((detail_pemberian_obat.biaya_obat * detail_pemberian_obat.jml) * 11 / 100) as ppn,
            ((detail_pemberian_obat.biaya_obat * detail_pemberian_obat.jml) + (detail_pemberian_obat.biaya_obat * detail_pemberian_obat.jml) * 11 / 100) as total_obat_ppn
            FROM detail_pemberian_obat
            INNER JOIN reg_periksa ON detail_pemberian_obat.no_rawat=reg_periksa.no_rawat
            LEFT JOIN penjab on reg_periksa.kd_pj=penjab.kd_pj
            LEFT JOIN poliklinik on reg_periksa.kd_poli=poliklinik.kd_poli
            LEFT JOIN databarang ON detail_pemberian_obat.kode_brng=databarang.kode_brng
            INNER JOIN nota_jalan ON detail_pemberian_obat.no_rawat=nota_jalan.no_rawat
            LEFT JOIN dokter ON reg_periksa.kd_dokter = dokter.kd_dokter
            LEFT JOIN pasien ON reg_periksa.no_rkm_medis = pasien.no_rkm_medis
            WHERE reg_periksa.tgl_registrasi
            between '".$start."' and '".$end."' and detail_pemberian_obat.status = 'Ralan' ";
            //reg_periksa.tgl_registrasi between '".$start."' and '".$end."' ";
            $sql = ($doctor!="all") ? $sql."and reg_periksa.kd_dokter= '".$doctor."' ": $sql." ";
            $sql = ($poli!="all") ? $sql."and reg_periksa.kd_poli= '".$poli."' ": $sql." ";
            $sql = ($penjamin!="all") ? $sql."and reg_periksa.kd_pj= '".$penjamin."' ": $sql." ";
            $sql = $sql." order by nota_jalan.no_nota";
            
            
            $obatralan = DB::connection('mysql_khanza')->select($sql);
            
            return Datatables::of($obatralan)
                    ->addIndexColumn()
                    ->make(true);

        }            

        $modul = $this->menu;
        return view('pendapatan.obatralan', compact('modul'));
        
    }

    


}
?>
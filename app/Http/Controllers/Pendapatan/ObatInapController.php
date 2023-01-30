<?php

namespace App\Http\Controllers\Pendapatan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use DataTables;
use Carbon;

class ObatInapController extends Controller {
    private $menu;

    public function __construct()
    {
        $this->middleware('auth');
        $this->menu = 'obat-inap';    
    }

    public function index()
    {
        $modul = $this->menu;
        $doctor = DB::connection('mysql_khanza')->select("select kd_dokter, nm_dokter  from dokter order by kd_dokter");
        $penjab = DB::connection('mysql_khanza')->select("select kd_pj, png_jawab  from penjab  ");
        
        return view('pendapatan.obatinap', compact('modul', 'doctor', 'penjab'));
    }

    public function getObatInap(Request $request)
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
        $penjamin = !empty($request->penjamin) ?  $request->penjamin : "all";
        

        if(request()->ajax()){
            //Get Obat Ralan PerDokter
            $sql = "select reg_periksa.tgl_registrasi,
            nota_inap.no_nota as nota_inap,
            detail_pemberian_obat.no_rawat,
            (Select X.no_sep from bridging_sep as X  where X.jnspelayanan='1' and X.no_rawat=reg_periksa.no_rawat LIMIT 1) no_sep,
            pasien.nm_pasien,
            dpjp.nm_dokter as dpjp,
            penjab.png_jawab,
            reg_periksa.status_lanjut,
            detail_pemberian_obat.kode_brng,databarang.nama_brng,
            detail_pemberian_obat.h_beli, detail_pemberian_obat.biaya_obat as h_jual, detail_pemberian_obat.jml,
            detail_pemberian_obat.total as  total_obat,
            ((detail_pemberian_obat.biaya_obat * detail_pemberian_obat.jml) * 11 / 100) as ppn,
            ((detail_pemberian_obat.biaya_obat * detail_pemberian_obat.jml) + (detail_pemberian_obat.biaya_obat * detail_pemberian_obat.jml) * 11 / 100) as total_obat_ppn
            FROM detail_pemberian_obat
            INNER JOIN reg_periksa ON detail_pemberian_obat.no_rawat=reg_periksa.no_rawat
            LEFT JOIN penjab on reg_periksa.kd_pj=penjab.kd_pj
            LEFT JOIN databarang ON detail_pemberian_obat.kode_brng=databarang.kode_brng
            INNER JOIN nota_inap ON detail_pemberian_obat.no_rawat=nota_inap.no_rawat
			LEFT JOIN dpjp_ranap ON reg_periksa.no_rawat = dpjp_ranap.no_rawat
			LEFT JOIN dokter as dpjp ON dpjp_ranap.kd_dokter = dpjp.kd_dokter
			LEFT JOIN dokter as regdr ON reg_periksa.kd_dokter = regdr.kd_dokter
            LEFT JOIN dokter ON reg_periksa.kd_dokter = dokter.kd_dokter
            LEFT JOIN pasien ON reg_periksa.no_rkm_medis = pasien.no_rkm_medis
            WHERE reg_periksa.tgl_registrasi      
            between '".$start."' and '".$end."' and detail_pemberian_obat.status = 'Ranap'";
            
            //reg_periksa.tgl_registrasi between '".$start."' and '".$end."' ";
            $sql = ($doctor!="all") ? $sql."and reg_periksa.kd_dokter= '".$doctor."' ": $sql." ";
            $sql = ($penjamin!="all") ? $sql."and reg_periksa.kd_pj= '".$penjamin."' ": $sql." ";
            $sql = $sql."order by nota_inap.no_nota";
            
            
            $obatinap = DB::connection('mysql_khanza')->select($sql);
            
            return Datatables::of($obatinap)
                    ->addIndexColumn()
                    ->make(true);

        }            

        $modul = $this->menu;
        return view('pendapatan.obatinap', compact('modul'));
        
    }

    


}
?>
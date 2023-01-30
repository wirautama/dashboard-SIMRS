<?php

namespace App\Http\Controllers\Pendapatan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use DataTables;
use Carbon;

class GrouperRanapController extends Controller {
    private $menu;

    public function __construct()
    {
        $this->middleware('auth');
        $this->menu = 'grouper-ranap';    
    }

    public function index()
    {
        $modul = $this->menu;
        $doctor = DB::connection('mysql_khanza')->select("select kd_dokter, nm_dokter  from dokter order by kd_dokter");
        $penjab = DB::connection('mysql_khanza')->select("select kd_pj, png_jawab  from penjab  ");
        
        return view('pendapatan.grouperranap', compact('modul', 'doctor', 'penjab'));
    }

    public function getGrouperRanap(Request $request)
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
            $sql = "select nota_inap.no_nota,kamar_inap.no_rawat, reg_periksa.tgl_registrasi,reg_periksa.no_rkm_medis,pasien.nm_pasien,pasien.alamat,
            ( 
                select dokter.nm_dokter FROM dokter WHERE reg_periksa.kd_dokter=dokter.kd_dokter
            ) AS dokter_register,
            (
                select dokter.nm_dokter FROM dokter WHERE dpjp_ranap.kd_dokter=dokter.kd_dokter
            ) AS dokter_dpjp_ranap,
            penjab.png_jawab,reg_periksa.status_bayar,kamar_inap.diagnosa_awal,diagnosa_akhir,bangsal.nm_bangsal,kamar_inap.lama,
                            (
                        select inacbg_grouping_stage12.no_sep FROM inacbg_grouping_stage12 LEFT JOIN bridging_sep ON inacbg_grouping_stage12.no_sep=bridging_sep.no_sep WHERE bridging_sep.no_rawat=reg_periksa.no_rawat LIMIT 1
                    ) as no_sep,
                    (
                        select inacbg_grouping_stage12.code_cbg FROM inacbg_grouping_stage12 LEFT JOIN bridging_sep ON inacbg_grouping_stage12.no_sep=bridging_sep.no_sep WHERE bridging_sep.no_rawat=reg_periksa.no_rawat LIMIT 1
                    ) as code_cbg,
                    (
                        select inacbg_grouping_stage12.deskripsi FROM inacbg_grouping_stage12 LEFT JOIN bridging_sep ON inacbg_grouping_stage12.no_sep=bridging_sep.no_sep WHERE bridging_sep.no_rawat=reg_periksa.no_rawat LIMIT 1
                    ) as deskripsi,
                            kamar_inap.tgl_masuk,kamar_inap.tgl_keluar,bridging_sep.klsrawat,
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
                        (select SUM(rawat_jl_dr.biaya_rawat) FROM rawat_jl_dr WHERE reg_periksa.no_rawat=rawat_jl_dr.no_rawat ) + 
                                    (select SUM(rawat_jl_pr.biaya_rawat) FROM rawat_jl_pr WHERE reg_periksa.no_rawat=rawat_jl_pr.no_rawat)
                    ) as tindakan_poli,
                    (
                        (select SUM(biaya) FROM periksa_lab WHERE reg_periksa.no_rawat=periksa_lab.no_rawat ) + 
                                    (select SUM(biaya_item) FROM detail_periksa_lab WHERE detail_periksa_lab.no_rawat=reg_periksa.no_rawat)
                    ) as laborat,
                    (
                        select SUM(biaya) FROM periksa_radiologi WHERE periksa_radiologi.no_rawat=reg_periksa.no_rawat
                    ) as radiologi,
                            (
                        select SUM(detail_pemberian_obat.biaya_obat * detail_pemberian_obat.jml) as total_obat FROM detail_pemberian_obat
                        WHERE detail_pemberian_obat.no_rawat=reg_periksa.no_rawat
                    ) as obat,
                            (
                                select SUM(detreturjual.subtotal) FROM detreturjual WHERE (LEFT(detreturjual.no_retur_jual,17))=reg_periksa.no_rawat
                            ) AS retur_obat,
                            (
                            (select SUM(detail_pemberian_obat.biaya_obat * detail_pemberian_obat.jml) as total_obat 
                            FROM detail_pemberian_obat
                    WHERE detail_pemberian_obat.no_rawat=reg_periksa.no_rawat) - 
                            (select SUM(detreturjual.subtotal) FROM detreturjual 				
                            WHERE (LEFT(detreturjual.no_retur_jual,17))=reg_periksa.no_rawat)
                            ) as total_obat_setelah_retur_belum_ppn
            FROM kamar_inap
            INNER JOIN reg_periksa ON kamar_inap.no_rawat=reg_periksa.no_rawat
            LEFT JOIN nota_inap ON kamar_inap.no_rawat=nota_inap.no_rawat
            INNER JOIN pasien ON reg_periksa.no_rkm_medis=pasien.no_rkm_medis
            LEFT JOIN dpjp_ranap ON kamar_inap.no_rawat=dpjp_ranap.no_rawat
            INNER JOIN penjab ON reg_periksa.kd_pj=penjab.kd_pj
            INNER JOIN kamar ON kamar_inap.kd_kamar=kamar.kd_kamar
            INNER JOIN bangsal ON kamar.kd_bangsal=bangsal.kd_bangsal
            INNER JOIN bridging_sep ON kamar_inap.no_rawat=bridging_sep.no_rawat
            WHERE reg_periksa.kd_pj='BPJ'
            AND reg_periksa.status_lanjut='Ranap'
            AND DATE(reg_periksa.tgl_registrasi)   
            between '".$start."' and '".$end."'";
            //reg_periksa.tgl_registrasi between '".$start."' and '".$end."' ";
            $sql = ($doctor!="all") ? $sql."and reg_periksa.kd_dokter= '".$doctor."' ": $sql." ";
            $sql = ($penjamin!="all") ? $sql."and reg_periksa.kd_pj= '".$penjamin."' ": $sql." ";
            $sql = $sql."ORDER BY tgl_registrasi";
            
            
            $grouperranap = DB::connection('mysql_khanza')->select($sql);
            
            return Datatables::of($grouperranap)
                    ->addIndexColumn()
                    ->make(true);

        }            

        $modul = $this->menu;
        return view('pendapatan.grouperranap', compact('modul'));
        
    }

    


}
?>
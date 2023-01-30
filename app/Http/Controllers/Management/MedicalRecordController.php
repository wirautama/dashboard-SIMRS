<?php

namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use DataTables;

class MedicalRecordController extends Controller
{
    private $menu;

    public function __construct()
    {
        $this->middleware('auth');
        $this->menu = 'medrec';    
    }

    public function index(Request $request)
    {
        $modul = $this->menu;
        return view('management.medicalrecord.index', compact('modul'));
    }

    public function getAlos($start, $end)
    {
        //Jumlah lama Inap
        $stay = DB::connection('mysql_khanza')->select("select kamar_inap.no_rawat,reg_periksa.no_rkm_medis,pasien.nm_pasien,concat(kamar_inap.kd_kamar,' ',bangsal.nm_bangsal) as kamar,
        kamar_inap.tgl_masuk,if(kamar_inap.tgl_keluar='0000-00-00',current_date(),kamar_inap.tgl_keluar) as tgl_keluar,kamar_inap.lama,kamar_inap.stts_pulang
        from kamar_inap inner join reg_periksa inner join pasien inner join kamar inner join bangsal
        on kamar_inap.no_rawat=reg_periksa.no_rawat and reg_periksa.no_rkm_medis=pasien.no_rkm_medis
        and kamar_inap.kd_kamar=kamar.kd_kamar and kamar.kd_bangsal=bangsal.kd_bangsal
        where kamar_inap.tgl_masuk between '".$start."' and '".$end."' order by kamar_inap.tgl_masuk");
        
        $jml_stay=0;
        foreach ($stay as $key) {
            $jml_stay += $key->lama;
        }

        //Jumlah Pasien
        $px = DB::connection('mysql_khanza')->select("select sum(X.px) as JML from ( select count(no_rawat) as px  
            from kamar_inap where tgl_masuk between '" . $start ."' and '".$end."' group by no_rawat) X");

        
        $jml_px=0;
        foreach ($px as $key) {
            $jml_px += $key->JML;
        }
        
        $alos = ceil( (int)$jml_stay/(int)$jml_px);

        return response()->json($alos);
    }

    public function getBor($start, $end)
    {
        //Jumlah lama Inap
        $stay = DB::connection('mysql_khanza')->select("select kamar_inap.no_rawat,reg_periksa.no_rkm_medis,pasien.nm_pasien,concat(kamar_inap.kd_kamar,' ',bangsal.nm_bangsal) as kamar,
        kamar_inap.tgl_masuk,if(kamar_inap.tgl_keluar='0000-00-00',current_date(),kamar_inap.tgl_keluar) as tgl_keluar,kamar_inap.lama,kamar_inap.stts_pulang
        from kamar_inap inner join reg_periksa inner join pasien inner join kamar inner join bangsal
        on kamar_inap.no_rawat=reg_periksa.no_rawat and reg_periksa.no_rkm_medis=pasien.no_rkm_medis
        and kamar_inap.kd_kamar=kamar.kd_kamar and kamar.kd_bangsal=bangsal.kd_bangsal
        where kamar_inap.tgl_masuk between '".$start."' and '".$end."' order by kamar_inap.tgl_masuk");
        
        $jml_stay=0;
        foreach ($stay as $key) {
            $jml_stay += $key->lama;
        }

        $jml_kamar = 0;
        $kamar = DB::connection('mysql_khanza')->select("select count(*) as JML from kamar where statusdata='1'");
        foreach ($kamar as $key) {
            $jml_kamar += $key->JML;
        }

        //"select (to_days('"+Valid.SetTgl(Tgl2.getSelectedItem()+"")+"')-to_days('"+Valid.SetTgl(Tgl1.getSelectedItem()+"")+"'))"
        $jml_hari = 1;
        $hari = DB::connection('mysql_khanza')->select("select DATEDIFF('" . $end ."', '". $start ."') as JML");
        foreach ($hari as $key) {
            $jml_hari += $key->JML;
        }

        //Jumlah Pasien
        $px = DB::connection('mysql_khanza')->select("select sum(X.px) as JML from ( select count(no_rawat) as px  
            from kamar_inap where tgl_masuk between '" . $start ."' and '".$end."' group by no_rawat) X");

        
        $jml_px=0;
        foreach ($px as $key) {
            $jml_px += $key->JML;
        }
        
        
        $bor = round( ((int)$jml_stay/ ( (int)$jml_kamar * (int)$jml_hari )*100), 2);
        $data = array("bor"=>$bor,  "lama"=>$jml_stay, "hari"=>$jml_hari, "kamar"=>$jml_kamar);
        return response()->json($data);
    }

    public function getDoctor()
    {

        $jml_dokter = 0;
        $kamar = DB::connection('mysql_khanza')->select("select count(*) as JML from dokter where status='1' ");
        foreach ($kamar as $key) {
            $jml_dokter += $key->JML;
        }
        
        $data = array("doctor"=>$jml_dokter);
        return response()->json($data);
    }

    public function getPatient($start, $end)
    {
        //Jumlah pasien
        $px = DB::connection('mysql_khanza')->select("select count(A.no_rkm_medis) jml, P.jk from reg_periksa A 
        left join pasien P ON  A.no_rkm_medis=P.no_rkm_medis 
        where A.tgl_registrasi between '".$start."' and '".$end."' group by P.jk");
        
        $jml_l=0; 
        $jml_p=0;
        $jml_admitted = 0;
        foreach ($px as $key) {
            $jml_l += ($key->jk=="L" ? $key->jml : 0);
            $jml_p += ($key->jk=="P" ? $key->jml : 0);
        }

        $jml_admitted =  $jml_l + $jml_p;
        
        $data = array("L"=>$jml_l,  "P"=>$jml_p, "admitted"=>$jml_admitted);
        return response()->json($data);
    }

    public function getReadmittedRate($start, $end)
    {
        //Jumlah pasien
        $px = DB::connection('mysql_khanza')->select("select count(A.stts_daftar) as JML, A.stts_daftar as STATUS 
        from reg_periksa A 
        left join pasien P ON  A.no_rkm_medis=P.no_rkm_medis  
        where  A.tgl_registrasi between '".$start."' and '".$end."' 
        group by A.stts_daftar ");
        
        $jml_lama=0; 
        $jml_baru=0;
        $jml_readmitted = 0;
        foreach ($px as $key) {
            $jml_lama += ($key->STATUS=="Lama" ? $key->JML : 0);
            $jml_baru += ($key->STATUS=="Baru" ? $key->JML : 0);
        }

        $jml_admitted =  $jml_lama + $jml_baru;
        
        $rate = round( ((int)$jml_lama/ $jml_admitted * 100), 2);

        $data = array("Lama"=>$jml_lama,  "Baru"=>$jml_baru, "admitted"=>$rate);
        return response()->json($data);
    }


    public function getAdmittedRateRalan($start, $end)
    {
        //Jumlah pasien
        $px = DB::connection('mysql_khanza')->select("select poliklinik.nm_poli,count(poliklinik.nm_poli) as jumlah 
        from reg_periksa inner join poliklinik on reg_periksa.kd_poli=poliklinik.kd_poli 
        where tgl_registrasi between '".$start."' and '".$end."'  group by poliklinik.nm_poli ");
        
        // $nama_poli = array(); 
        // $jumlah = array();

        // foreach ($px as $key) {
        //     $nama_poli [] = $key->nm_poli;
        //     $jumlah[] = $key->jumlah;
        // }

        // $data = array( "poli"=>$nama_poli, "jumlah"=> $jumlah);
        
        return response()->json($px);
    }

    public function getBestDiagnose($start, $end)
    {
        //Jumlah pasien
        $px = DB::connection('mysql_khanza')->select("select count(diagnosa_pasien.no_rawat) as jumlah,diagnosa_pasien.kd_penyakit 
        from penyakit inner join diagnosa_pasien inner join reg_periksa on diagnosa_pasien.kd_penyakit=penyakit.kd_penyakit 
        and reg_periksa.no_rawat=diagnosa_pasien.no_rawat where diagnosa_pasien.status='Ranap' and reg_periksa.tgl_registrasi between '".$start."' and '".$end."' 
        group by diagnosa_pasien.kd_penyakit 
        order by jumlah DESC
        LIMIT 10
        
        ");
        
        
        return response()->json($px);
    }
    

    public function getInOut($start, $end)
    {
        //Jumlah pasien
        $px_in = DB::connection('mysql_khanza')->select("select 
        YEAR(tgl_masuk) as Year, DATE_FORMAT(tgl_masuk, '%b %u') AS Week, COUNT(*) AS total 
        from kamar_inap 
        where kamar_inap.tgl_masuk between '".$start."' and '".$end."' 
        GROUP BY Year, Week ");

        $px_out = DB::connection('mysql_khanza')->select("select 
        YEAR(if(kamar_inap.tgl_keluar='0000-00-00',current_date(),kamar_inap.tgl_keluar)) as Year, DATE_FORMAT(if(kamar_inap.tgl_keluar='0000-00-00',current_date(),kamar_inap.tgl_keluar), '%b %u') AS Week, COUNT(*) AS total 
        from kamar_inap 
        where kamar_inap.tgl_keluar between '".$start."' and '".$end."'  
        GROUP BY Year, Week ");
        
        $data = array( "in"=>$px_in, "out"=> $px_out);

        return response()->json($data);
    }


    public function getAppointments()
    {
        
        $px =  DB::connection('mysql_khanza')->select("select reg_periksa.kd_dokter, dokter.nm_dokter,  reg_periksa.no_rkm_medis  , poliklinik.nm_poli, reg_periksa.tgl_registrasi  
        from reg_periksa inner join poliklinik on reg_periksa.kd_poli=poliklinik.kd_poli INNER join dokter on reg_periksa.kd_dokter=dokter.kd_dokter 
        where tgl_registrasi > NOW() ");

        return Datatables::of($px)
                ->addIndexColumn()
                ->make(true);

    }

}
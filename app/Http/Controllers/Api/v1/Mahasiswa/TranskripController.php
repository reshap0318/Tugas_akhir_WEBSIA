<?php

namespace App\Http\Controllers\Api\v1\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class TranskripController extends Controller
{
    public function getListTranskrip($nim)
    {
        try {
            $datas = DB::select("select 
            s_semester.semId as semesterid, 
            s_semester.semTahun as semestertahun, 
            s_nama_semester_ref.nmsemrNama as semesternama,
            s_matakuliah_kurikulum.mkkurKode as matkulkode,
            s_matakuliah_kurikulum.mkkurNamaResmi as matkulnama,
            s_krs_detil.krsdtSksMatakuliah as matkulsks,
            s_krs_detil.krsdtKodeNilai as nilaikode
            from s_krs
            join s_semester_prodi on s_krs.krsSempId = s_semester_prodi.sempId
            join s_semester on s_semester_prodi.sempSemId = s_semester.semId
            join s_nama_semester_ref on s_semester.semNmsemrId = s_nama_semester_ref.nmsemrId
            join s_krs_detil on s_krs.krsId = s_krs_detil.krsdtKrsId
            join s_kelas on s_krs_detil.krsdtKlsId = s_kelas.klsId
            join s_matakuliah_kurikulum on s_kelas.klsMkkurId = s_matakuliah_kurikulum.mkkurId
            where 
            s_krs.krsMhsNiu='$nim'
            ");

            $datas = $this->convertArrayTranskrip($datas);

            return $this->MessageSuccess($datas);
        } catch (\Throwable $th) {
            return $this->MessageError($th->getMessage());
        }
    }

    public function convertArrayTranskrip($datas)
    {
        $tempResult = [];

        foreach ($datas as $data) {
            if(count($tempResult)==0){
                $temp = [];
                $temp['semesterid'] = $data->semesterid;
                $temp['semestertahun'] = $data->semestertahun;
                $temp['semesternama'] = $data->semesternama;
                $tempResult[1]['semester'] = $temp;
            }

            $key = null;
            foreach ($tempResult as $k => $rs) {
                if($rs['semester']['semesterid']==$data->semesterid){
                    $key = $k;
                }
            }
            
            if($key){
                $temp = [];
                $temp['matkulkode'] = $data->matkulkode;
                $temp['matkulnama'] = $data->matkulnama;
                $temp['matkulsks'] = $data->matkulsks;
                $temp['nilaikode'] = $data->nilaikode;
                $tempResult[$key]['matkul'][] = $temp;
            }else{
                $temp = [];
                $temp_ = [];
                $temp_['semesterid'] = $data->semesterid;
                $temp_['semestertahun'] = $data->semestertahun;
                $temp_['semesternama'] = $data->semesternama;
                $temp['matkulkode'] = $data->matkulkode;
                $temp['matkulnama'] = $data->matkulnama;
                $temp['matkulsks'] = $data->matkulsks;
                $temp['nilaikode'] = $data->nilaikode;
                $tempResult[] = ['semester' => $temp_,'matkul'=>[$temp]];
            }

        }

        $result = [];
        foreach ($tempResult as $arr) {
            $result[] = $arr;
        }   

        return $result;
    }
}

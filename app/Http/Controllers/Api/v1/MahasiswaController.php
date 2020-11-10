<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\{
    Mahasiswa,
    Semester_Prodi,
    Krs_Detil,
    Matkul,
    SKS_Jatah
};
use App\Http\Resources\{
    Mahasiswa\listCollection as mahasiswaCollection,
    Krs\listCollection as listKrsCollection,
    SemesterProdi\listCollection as listSemesterProdiCollection
};

class MahasiswaController extends Controller
{
    public function getListData()
    {
        try {
            $data = Mahasiswa::where('mhsStakmhsrKode','A')->where('mhsNiu','<>',0)->get();
            $data = mahasiswaCollection::collection($data);
            return $this->MessageSuccess($data);
        } catch (\Exception $e) {
            return $this->MessageError($e->getMessage());
        }
    }

    public function getData($nim)
    {
        try {
            $data = Mahasiswa::find($nim);
            $data = new mahasiswaCollection($data);
            return $this->MessageSuccess($data);
        } catch (\Exception $e) {
            return $this->MessageError($e->getMessage());
        }
    }

    public function getKrs($nim)
    {
        try {
            $krs = Krs_Detil::whereRaw("krsdtKrsId in (select krsid from s_krs where krsMhsNiu = $nim)")->get();
            $data = listKrsCollection::collection($krs);
            return $this->MessageSuccess($data);
        } catch (\Exception $e) {
            return $this->MessageError($e->getMessage());
        }
    }

    public function getListSemester($nim)
    {
        try {
            $semesterProdi = Semester_Prodi::whereRaw("sempId in (select krsSempId from s_krs where krsMhsNiu = $nim)")->get();
            $data = listSemesterProdiCollection::collection($semesterProdi);
            return $this->MessageSuccess($data);
        } catch (\Exception $e) {
            return $this->MessageError($e->getMessage());
        }
    }

    public function getSumSks($nim)
    {
        try {
            $matkul = Matkul::whereRaw("mkkurId in (select klsMkkurId from s_kelas where klsId in (select krsdtKlsId from s_krs_detil where krsdtKrsId in (select krsId from s_krs where krsMhsNiu = $nim)))")->get();

            $jatahSks= SKS_Jatah::where('krssksMhsNiu', $nim)->latest('krssksSempId')->first();
            $totalJatah = $jatahSks ? $jatahSks->krssksJatahSksResmi : 0;

            $krsAktif = Matkul::whereRaw("mkkurId in (select klsMkkurId from s_kelas where klsId in (select krsdtKlsId from s_krs_detil where krsdtKrsId in (select krsId from s_krs where krsMhsNiu = $nim && krsSempId in (select sempId from s_semester_prodi where sempIsAktif = 1))))")->get();
            
            $data = [
                'total_sks' => $matkul->sum('mkkurJumlahSksKurikulum'),
                'jatah_sks' => $totalJatah,
                'sks_diambil' => $krsAktif->sum('mkkurJumlahSksKurikulum')
            ];
            return $this->MessageSuccess($data);
        } catch (\Exception $e) {
            return $this->MessageError($e->getMessage());
        }
    }

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

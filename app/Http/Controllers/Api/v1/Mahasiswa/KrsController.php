<?php

namespace App\Http\Controllers\Api\v1\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\{
    Kelas,
    Krs,
    Krs_Detil,
    Matkul,
    SKS_Jatah,
    Mahasiswa_Registrasi,
    Semester_Prodi,
};
use App\Http\Resources\{
    Krs\listCollection as listKrsCollection
};
use App\Http\Controllers\Api\v1\Mahasiswa\SksController;

class KrsController extends Controller
{
    public function getListData($nim)
    {
        try {
            $krs = Krs_Detil::whereRaw("krsdtKrsId in (select krsid from s_krs where krsMhsNiu = $nim) and krsdtIsBatal = 0")->get();
            $data = listKrsCollection::collection($krs);
            return $this->MessageSuccess($data);
        } catch (\Exception $e) {
            return $this->MessageError($e->getMessage());
        }
    }

    public function getListDataSemester($nim, $semester)
    {
        try {
            $krs = Krs_Detil::whereRaw("krsdtKrsId in (select krsid from s_krs where krsMhsNiu = $nim and krsSempId in (select sempId from s_semester_prodi where sempSemId = $semester)) and krsdtIsBatal = 0")->get();
            $data = listKrsCollection::collection($krs);

            $jatahSks= SKS_Jatah::where('krssksMhsNiu', $nim)->whereRaw("krssksSempId in (select sempId from s_semester_prodi where sempSemId = $semester)")->first();
            $totalJatah = $jatahSks ? $jatahSks->krssksJatahSksResmi : 0;

            $krsAktif = Matkul::whereRaw("mkkurId in (select klsMkkurId from s_kelas where klsId in (select krsdtKlsId from s_krs_detil where krsdtKrsId in (select krsId from s_krs where krsMhsNiu = $nim && krsSempId in (select sempId from s_semester_prodi where sempSemId = $semester))))")->get();

            return $this->MessageSuccess([
                'sks'=>[
                    'jatah_sks' => $totalJatah,
                    'sks_diambil' => $krsAktif->sum('mkkurJumlahSksKurikulum')
                ], 
                'krs' => $data
            ]);
        } catch (\Exception $e) {
            return $this->MessageError($e->getMessage());
        }
    }

    public function isCanEntry($nim)
    {
        try {
            $data = $this->canEntry($nim);
            if($data){
                return $this->MessageSuccess(["data" => true]);
            }
            return $this->MessageError(["data"=>false]);
        } catch (\Exception $e) {
            return $this->MessageError($e->getMessage());
        }
    }

    public function canEntry($nim, $newKrs = 0)
    {
        $date = date("Y-m-d");
        $date = "2018-08-04"; //untuk ujicoba
        $data = Mahasiswa_Registrasi::whereRaw("mhsregSemId in (SELECT sempSemId FROM  `s_semester_prodi` where '$date' BETWEEN sempTanggalKrsMulai and sempTanggalKrsSelesai or '$date' BETWEEN sempTanggalRevisiMulai and sempTanggalRevisiSelesai) and mhsregMhsNiu=$nim")->first();
        $sksController = new SksController();
        $sisaSks = $sksController->getSumeryData($nim);

        if($data){
            if($sisaSks["sks_diambil"]+$newKrs <= $sisaSks["jatah_sks"]){
                return true;
            }
            return false;
        }
        return false;
    }

    public function entry($nim, Request $request)
    {   
        $validator = Validator::make($request->all(), [
            'klsId'   => 'required',
        ]);

        if ($validator->fails()) {
            return $this->MessageError($validator->errors(), 422);
        }

        try {
            $klsId = $request->klsId;
            $iskls = Kelas::find($klsId);
            if(!$iskls){
                return $this->MessageError(["data"=>"No Class Found"]);
            }
            $klsId = $iskls->klsId;
            $mkId = $iskls->klsMkkurId;

            $isMk = Matkul::find($mkId);
            $mkSks = $isMk->mkkurJumlahSksKurikulum;
            $mkSifat = $isMk->mkkurSfmkrKode;

            if(!$this->canEntry($nim,$mkSks)){
                return $this->MessageError(["data"=>"Can't Entry"]);
            }

            $sempId = Semester_Prodi::where('sempIsAktif',1)->first() ? Semester_Prodi::where('sempIsAktif',1)->first()->sempId : "";

            if(!$sempId){
                return $this->MessageError(["data" => "No Semester Aktif"]);
            }
            
            $isKrsId = Krs::where("krsMhsNiu",$nim)->whereRaw("krsSempId in (select sempId from s_semester_prodi where sempIsAktif = 1)")->first();

            $krsId = Krs::latest('krsId')->first()->krsId + 1;
            if($isKrsId){
                $krsId = $isKrsId->krsId;
            }else{
                Krs::create([
                    'krsId' => $krsId,
                    'krsMhsNiu' => $nim,
                    'krsSempId' => $sempId,
                ]);
            }

            $isInKrsDt = Krs_Detil::where('krsdtkrsId',$krsId)->where('krsdtKlsId',$klsId)->first();
            if($isInKrsDt){
                return $this->MessageError(["data"=>"Kelas Alredy Axist"]);
            }
            
            $krsdtId = Krs_Detil::latest('krsdtId')->first()->krsdtId + 1;

            Krs_Detil::create([
                'krsdtId'=>$krsdtId,
                'krsdtKrsId' => $krsId,
                'krsdtKlsId' => $klsId,
                'krsdtMkkurId' => $mkId,
                'krsdtSksMatakuliah' => $mkSks,
                'krsdtSifatMatakuliah' => $mkSifat,
                'krsdtKrsjubahNama' => 'krs_tambah'
            ]);

            return $this->MessageSuccess("Success Add Class");
        } catch (\Exception $e) {
            return $this->MessageError($e->getMessage());
        }
        
    }

    public function canChange()
    {
        $date = date("Y-m-d");
        $date = "2018-08-04"; //untuk ujicoba
        $data = Semester_Prodi::whereRaw("'$date' BETWEEN sempTanggalKrsMulai and sempTanggalKrsSelesai or '$date' BETWEEN sempTanggalRevisiMulai and sempTanggalRevisiSelesai")->first();

        if($data){
            return true;
        }
        return false;
    }

    public function isCanChange()
    {
        try {
            $data = $this->canChange();
            if($data){
                return $this->MessageSuccess(["data" => true]);
            }
            return $this->MessageError(["data"=>false]);
        } catch (\Exception $e) {
            return $this->MessageError($e->getMessage());
        }
    }

    public function changeStatus($nim, $krsdtId, $status)
    {
        try {
            if(!$this->canChange()){
                return $this->MessageError("Not Time To Change Status KRS");
            }
            $data = Krs_Detil::find($krsdtId);
            $data->krsdtApproved = $status;
            $data->save();
            return $this->MessageSuccess("Success Change Status KRS be ".$status);
        } catch (\Exception $e) {
            return $this->MessageError($e->getMessage());
        }
    }

    public function deleteKrs($nim, $krsdtId)
    {
        try {
            if(!$this->canEntry($nim)){
                return $this->MessageError("Not Time To Change Status KRS");
            }
            $data = Krs_Detil::where("krsdtId",$krsdtId)->whereRaw("krsdtKrsId in (select krsId from s_krs where krsMhsNiu=$nim and krsSempId in ( select sempId from s_semester_prodi where sempIsAktif = 1))");
            if($data->first()){
                $data->delete();
                return $this->MessageSuccess("Success Deleted KRS");
            }else{
                return $this->MessageError("Data Not Found");
            }
        } catch (\Exception $e) {
            return $this->MessageError($e->getMessage());
        }
    }
}

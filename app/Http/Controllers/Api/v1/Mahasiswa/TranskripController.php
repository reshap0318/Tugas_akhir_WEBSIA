<?php

namespace App\Http\Controllers\Api\v1\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\{Krs, Krs_Detil};
use App\Http\Resources\Transkrip\listCollection;

class TranskripController extends Controller
{
    public function getListTranskrip($nim)
    {
        try {
            $datas = Krs::where('krsMhsNiu',$nim)->whereRaw("krsId in (select krsdtKrsId from s_krs_detil where krsdtKodeNilai is not null)")->orderby('krsSempId','asc')->get();
            $datas = listCollection::collection($datas);
            return $this->MessageSuccess($datas);
        } catch (\Throwable $th) {
            return $this->MessageError($th->getMessage());
        }
    }

    public function staticA($nim)
    {
        try{
            $datas = DB::Select("select nlmkrKode from s_nilai_matakuliah_ref");
            $hasil = [];
            foreach($datas as $data){
                $mNilai = Krs_Detil::selectRaw("count(krsdtKodeNilai) as  total")->join(DB::RAW("(select krsdtMkkurId, max(krsdtBobotNilai) as maxNilai from s_krs_detil where krsdtKrsId in (select krsId from s_krs where krsMhsNiu=$nim) GROUP by krsdtMkkurId) as tMaxNilai"), function($join){
                    $join->on("s_krs_detil.krsdtMkkurId","=","tMaxNilai.krsdtMkkurId");
                    $join->on("s_krs_detil.krsdtBobotNilai", "=", "tMaxNilai.maxNilai");
                })->whereNotNull('krsdtKodeNilai')->where(function ($query) {
                    $query->where('krsdtApproved',1)->orWhereRaw('krsdtKlsId in (select klsId from s_kelas where klsSemId in (select semId from s_semester where semNmSemrId = 4))');
                })->where('krsdtIsDipakaiTranskrip',1)->where("krsdtIsBatal",0)->whereRaw("krsdtkrsid in (select krsid from s_krs where krsMhsNiu=$nim)")->where("krsdtKodeNilai",$data->nlmkrKode)->first();
                $hasil []= [
                    'krsdtKodeNilai' => $data->nlmkrKode,
                    'total' => $mNilai ? $mNilai->total : 0
                ];
            }
            return $this->MessageSuccess($hasil);
        } catch (\Throwable $th) {
            return $this->MessageError($th->getMessage());
        }
    }

    public function staticB($nim)
    {
        try{
            $datas = Krs_Detil::selectRaw("
                concat(nmsemrNama,SPACE(1),semTahun ) as semester_nama,
                sum(krsdtBobotNilai * krsdtSksMatakuliah) as total_bobot, 
                sum(krsdtSksMatakuliah) as total_sks, 
                sum(krsdtBobotNilai * krsdtSksMatakuliah) / sum(krsdtSksMatakuliah) as ip_semester
            ")->whereRaw("krsdtKrsId in (select krsId from s_krs where krsMhsNiu = $nim) and krsdtIsDipakaiTranskrip=1")
            ->whereNotNull("krsdtKodeNilai")
            ->join('s_krs','s_krs.krsId','=','s_krs_detil.krsdtKrsId')
            ->join('s_semester_prodi','s_krs.krsSempId','=','s_semester_prodi.sempId')
            ->join('s_semester','s_semester_prodi.sempSemId','=','s_semester.semId')
            ->join('s_nama_semester_ref','s_semester.semNmsemrId','=','s_nama_semester_ref.nmsemrId')
            ->groupby('krsdtKrsId')->get();
            return $this->MessageSuccess($datas);
        } catch (\Throwable $th) {
            return $this->MessageError($th->getMessage());
        }
    }
}

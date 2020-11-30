<?php

namespace App\Http\Controllers\Api\v1\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{
    Krs_Detil,
    Matkul,
    SKS_Jatah
};
use App\Http\Resources\{
    Krs\listCollection as listKrsCollection
};

class KrsController extends Controller
{
    public function getListData($nim)
    {
        try {
            $krs = Krs_Detil::whereRaw("krsdtKrsId in (select krsid from s_krs where krsMhsNiu = $nim)")->get();
            $data = listKrsCollection::collection($krs);
            return $this->MessageSuccess($data);
        } catch (\Exception $e) {
            return $this->MessageError($e->getMessage());
        }
    }

    public function getListDataSemester($nim, $semester)
    {
        try {
            $krs = Krs_Detil::whereRaw("krsdtKrsId in (select krsid from s_krs where krsMhsNiu = $nim and krsSempId in (select sempId from s_semester_prodi where sempSemId = $semester))")->get();
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
}

<?php

namespace App\Http\Controllers\Api\v1\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{
    Matkul,
    SKS_Jatah,
    Mahasiswa
};

class SksController extends Controller
{
    public function getSumery($nim)
    {
        try {
            $data = $this->getSumeryData($nim);
            return $this->MessageSuccess($data);
        } catch (\Exception $e) {
            return $this->MessageError($e->getMessage());
        }
    }

    public function getSumeryData($nim)
    {
        $matkul = Matkul::whereRaw("mkkurId in (select klsMkkurId from s_kelas where klsId in (select krsdtKlsId from s_krs_detil where krsdtKrsId in (select krsId from s_krs where krsMhsNiu = $nim)))")->get();

        $jatahSks= SKS_Jatah::where('krssksMhsNiu', $nim)->latest('krssksSempId')->first();
        $totalJatah = $jatahSks ? $jatahSks->krssksJatahSksResmi : 0;

        $krsAktif = Matkul::whereRaw("mkkurId in (select klsMkkurId from s_kelas where klsId in (select krsdtKlsId from s_krs_detil where krsdtKrsId in (select krsId from s_krs where krsMhsNiu = $nim && krsSempId in (select sempId from s_semester_prodi where sempIsAktif = 1))))")->get();

        $mahasiswa = Mahasiswa::find($nim);
        
        $data = [
            'total_sks' => $matkul->sum('mkkurJumlahSksKurikulum'),
            'jatah_sks' => $totalJatah,
            'sks_diambil' => $krsAktif->sum('mkkurJumlahSksKurikulum'),
            'ipk' => $mahasiswa->mhsIpkTranskrip
        ];
        return $data;   
    }
}

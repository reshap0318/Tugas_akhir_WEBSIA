<?php

namespace App\Http\Controllers\Api\v1\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kelas;
use App\Http\Resources\Kelas\{
    listCollection as kelasCollection,
    detailCollection as detailKelasCollection
};

class KelasController extends Controller
{
    public function getListKelasNim($nim)
    {
        
        try {
            $data = Kelas::whereRAW("klsSemId in (select sempSemId from s_semester_prodi where sempIsAktif = 1)")->whereRaw("klsMkkurId in (select mkkurId from s_matakuliah_kurikulum where mkkurProdiKode in (select mhsProdiKode from mahasiswa where mhsNiu = $nim))")->get();
            $data = kelasCollection::collection($data);
            return $this->MessageSuccess($data);
        } catch (\Exception $e) {
            return $this->MessageError($e->getMessage());
        }

    }

    public function getDetailKelasNim($nim, $klsId)
    {
        
        try {
            $data = Kelas::where('klsId',$klsId)->first();
            $data->nim = $nim;
            $data = new detailKelasCollection($data);
            return $this->MessageSuccess($data);
        } catch (\Exception $e) {
            return $this->MessageError($e->getMessage());
        }

    }
}

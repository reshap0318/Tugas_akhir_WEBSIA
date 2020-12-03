<?php

namespace App\Http\Controllers\Api\v1\Dosen;

use App\Http\Controllers\Controller;
use App\Models\Mahasiswa;
use App\Http\Resources\Mahasiswa\listCollection as mahasiswaCollection;

class BimbinganController extends Controller
{
    public function getListMahasiswa($nip)
    {
        try {
            $data = Mahasiswa::whereRaW("mhsNiu in (select dsnpaMhsNiu from s_dosen_pembimbing_akademik where dsnpaPegNip = '$nip') and mhsStakmhsrKode in ('A','C')")->get();
            $data = mahasiswaCollection::collection($data);
            return $this->MessageSuccess($data);
        } catch (\Exception $e) {
            return $this->MessageError($e->getMessage());
        }
    }
}

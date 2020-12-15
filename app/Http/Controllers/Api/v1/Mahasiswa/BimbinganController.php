<?php

namespace App\Http\Controllers\Api\v1\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pegawai;
use App\Http\Resources\Pegawai\listCollection as DosenCollection;

class BimbinganController extends Controller
{
    public function getDosenPembimbing($nim)
    {
        try {
            $data = Pegawai::whereRaW("PegNip in (select dsnpaPegNip from s_dosen_pembimbing_akademik where dsnpaMhsNiu = '$nim') ")->first();
            $data = new DosenCollection($data);
            return $this->MessageSuccess($data);
        } catch (\Exception $e) {
            return $this->MessageError($e->getMessage());
        }
    }
}

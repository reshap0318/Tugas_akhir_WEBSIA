<?php

namespace App\Http\Resources\Dosen;

use Illuminate\Http\Resources\Json\JsonResource;

class listCollection extends JsonResource
{

    public function toArray($request)
    {
        return [
            'nip'   => $this->pegawai->PegNip,
            'nama'  => $this->pegawai->pegNama,
            'noHp'  => $this->pegawai->pegNoHP,
            'nama_jurusan' => $this->pegawai->jurusan ? $this->pegawai->jurusan->jurNamaResmi : ""
        ];
    }
}

<?php

namespace App\Http\Resources\Pegawai;

use Illuminate\Http\Resources\Json\JsonResource;

class listCollection extends JsonResource
{
    public function toArray($request)
    {
        return [
            'nip'   => $this->PegNip,
            'nama'  => $this->pegNama,
            'noHp'  => $this->pegnoHp,
            'nama_jurusan' => $this->jurusan->jurNamaResmi
        ];
    }
}

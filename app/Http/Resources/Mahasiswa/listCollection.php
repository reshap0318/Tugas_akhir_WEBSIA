<?php

namespace App\Http\Resources\Mahasiswa;

use Illuminate\Http\Resources\Json\JsonResource;

class listCollection extends JsonResource
{

    public function toArray($request)
    {
        return [
            'nim'   => $this->mhsNiu,
            'nama'  => $this->mhsNama,
            'nama_jurusan' => $this->prodi->jurusan->jurNamaResmi
        ];
    }
}

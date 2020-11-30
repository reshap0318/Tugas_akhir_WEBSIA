<?php

namespace App\Http\Resources\Mahasiswa;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Controllers\Api\v1\Mahasiswa\SksController;

class listCollection extends JsonResource
{

    public function toArray($request)
    {
        $sks = new SksController();
        return [
            'nim'   => $this->mhsNiu,
            'nama'  => $this->mhsNama,
            'nama_jurusan' => $this->prodi->jurusan->jurNamaResmi,
            // 'sks' => $sks->getSumeryData($this->mhsNiu)
        ];
    }
}

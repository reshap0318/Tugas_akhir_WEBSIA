<?php

namespace App\Http\Resources\JadwalKuliah;

use Illuminate\Http\Resources\Json\JsonResource;

class listCollection extends JsonResource
{

    public function toArray($request)
    {
        return [
            'hari' => $this->jdkulHari,
            'waktu_mulai' => $this->jdkulJamMulai,
            'waktu_selesai' => $this->jdkulJamSelesai,
            'ruangan' => $this->ruangan->ruNama
        ];
    }
}

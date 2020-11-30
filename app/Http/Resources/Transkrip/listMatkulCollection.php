<?php

namespace App\Http\Resources\Transkrip;

use Illuminate\Http\Resources\Json\JsonResource;

class listMatkulCollection extends JsonResource
{
    public function toArray($request)
    {
        return [
            'kode' => $this->kelas->matkul->mkkurKode,
            'nama' => $this->kelas->matkul->mkkurNamaResmi,
            'sks'  => $this->krsdtSksMatakuliah,
            'nilaiAngka' => $this->krsdtKodeNilai
        ];
    }
}

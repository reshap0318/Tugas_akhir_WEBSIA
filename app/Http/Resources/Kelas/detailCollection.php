<?php

namespace App\Http\Resources\Kelas;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\JadwalKuliah\listCollection as JadwalCollection;
use App\Http\Resources\Pegawai\listCollection as DosenCollection;

class detailCollection extends JsonResource
{
    public function toArray($request)
    {
        return [
            'kelas' => [
                'id' => $this->klsId,
                'nama' => $this->klsNama
            ],
            'matkul' => [
                'kode' => $this->matkul->mkkurKode,
                'nama' => $this->matkul->mkkurNamaResmi,
                'sks'  => $this->matkul->mkkurJumlahSksKurikulum,
            ],
            'jadwal' => JadwalCollection::collection($this->jadwals),
            'dosen' => DosenCollection::collection($this->dosens),
            'nilai' => $this->nilaiMahasiswa($this->nim),
        ];
    }
}

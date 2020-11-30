<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class ListCollection extends JsonResource
{
    public function toArray($request)
    {
        return [
            'nip'   => $this->susrNama,
            'nama'  => $this->susrProfil,
            'noHp'  => null,
            'nama_jurusan' => "TEKNOLOGI INFORMASI"
        ]; 
    }
}

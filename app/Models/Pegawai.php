<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pegawai extends Model
{
    protected $table = 'pegawai';
    protected $primaryKey = 'PegNip';

    public function jurusan()
    {
        return $this->hasOne(Jurusan::class, 'jurKode', 'pegJurKode');
    }
}

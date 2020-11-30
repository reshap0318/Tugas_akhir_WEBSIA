<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Prodi extends Model
{
    protected $table = 'program_studi';
    protected $primaryKey = 'prodiKode';
    // public $incrementing = false;
    public $timestamps = false;

    public function jurusan()
    {
        return $this->hasOne(Jurusan::class, 'jurKode', 'prodiJurKode');
    }
}

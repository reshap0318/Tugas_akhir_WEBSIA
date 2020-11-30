<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mahasiswa extends Model
{
    protected $table = 'mahasiswa';
    protected $primaryKey = 'mhsNiu';
    public $incrementing = false;
    public $timestamps = false;
    
    public function prodi()
    {
        return $this->hasOne(Prodi::class, 'prodiKode', 'mhsProdiKode');
    }

}

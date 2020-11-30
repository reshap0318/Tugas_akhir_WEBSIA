<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Jadwal_Kuliah extends Model
{
    protected $table = 's_jadwal_kuliah';
    protected $primaryKey = 'jdkulId';
    // public $incrementing = false;
    public $timestamps = false;

    public function ruangan()
    {
        return $this->hasOne(Ruangan::class, 'ruId', 'jdkulRuId');
    }
}

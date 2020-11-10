<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Krs extends Model
{
    protected $table = 's_krs';
    protected $primaryKey = 'krsId';

    public function kelas()
    {
      return $this->belongsToMany(Kelas::class, 's_krs_detil','krsdtKrsId','krsdtKlsId');
    }

}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Krs extends Model
{
    protected $table = 's_krs';
    protected $primaryKey = 'krsId';
    // public $incrementing = false;
    public $timestamps = false;

    protected $guarded = [];  

    public function kelas()
    {
      return $this->belongsToMany(Kelas::class, 's_krs_detil','krsdtKrsId','krsdtKlsId');
    }

    public function semProdi()
    {
      return $this->hasOne(Semester_Prodi::class, 'sempId','krsSempId');
    }

    public function detailKrs()
    {
        return $this->hasMany(Krs_Detil::class, 'krsdtKrsId', 'krsId');
    }

}

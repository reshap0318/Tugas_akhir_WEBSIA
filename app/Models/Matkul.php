<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Matkul extends Model
{
    protected $table = 's_matakuliah_kurikulum';
    protected $primaryKey = 'mkkurId';
    // public $incrementing = false;
    public $timestamps = false;

    public function krss()
    {
      return $this->belongsToMany(Krs::class, 's_krs_detil', 'krsdtMkkurId', 'krsdtKrsId');
    }
}

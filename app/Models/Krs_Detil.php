<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Krs_Detil extends Model
{
    protected $table = 's_krs_detil';
    protected $primaryKey = 'krsdtId';
    public $incrementing = false;
    public $timestamps = false;

    public function kelas()
    {
        return $this->hasOne(kelas::class, 'klsId', 'krsdtKlsId');
    }
}

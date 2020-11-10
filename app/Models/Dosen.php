<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dosen extends Model
{
    protected $table = 'dosen';
    protected $primaryKey = 'dsnPegNip';

    public function pegawai()
    {
        return $this->hasOne(Pegawai::class, 'pegNip', 'dsnPegNip');
    }

}

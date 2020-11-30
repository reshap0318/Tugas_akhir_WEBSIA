<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ruangan extends Model
{
    protected $table = 'e_ruang';
    protected $primaryKey = 'ruId';
    public $incrementing = false;
    public $timestamps = false;
}

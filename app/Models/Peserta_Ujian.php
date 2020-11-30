<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Peserta_Ujian extends Model
{
  protected $table = 's_peserta_ujian';
  protected $primaryKey = 'pesertaujiJdujiId';
  // public $incrementing = false;
  public $timestamps = false;
}

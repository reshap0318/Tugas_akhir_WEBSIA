<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dosen_PA extends Model
{
  protected $table = 's_dosen_pembimbing_akademik';
  protected $primaryKey = 'dsnpaId';
  // public $incrementing = false;
  public $timestamps = false;
}

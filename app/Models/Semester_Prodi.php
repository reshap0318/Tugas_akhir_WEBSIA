<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Semester_Prodi extends Model
{
  protected $table = 's_semester_prodi';
  protected $primaryKey = 'sempId';
  // public $incrementing = false;
  public $timestamps = false;

  public function semester()
  {
      return $this->hasOne(semester::class, 'semId', 'sempSemId');
  }
}

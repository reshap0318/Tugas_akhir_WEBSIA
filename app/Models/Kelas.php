<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
  protected $table = 's_kelas';
  protected $primaryKey = 'klsId';

  public function matkul()
  {
      return $this->belongsTo(Matkul::class, 'klsMkkurId', 'mkkurId');
  }

  public function dosens()
  {
      return $this->belongsToMany(Dosen::class, 's_dosen_kelas', 'dsnkKlsId', 'dsnkDsnPegNip');
  }
}

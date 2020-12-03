<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Kelas extends Model
{
  protected $table = 's_kelas';
  protected $primaryKey = 'klsId';
  public $incrementing = false;
  public $timestamps = false;

  public function matkul()
  {
      return $this->belongsTo(Matkul::class, 'klsMkkurId', 'mkkurId');
  }

  public function dosens()
  {
      return $this->belongsToMany(Pegawai::class, 's_dosen_kelas', 'dsnkKlsId', 'dsnkDsnPegNip');
  }

  public function jadwals()
  {
      return $this->hasMany(Jadwal_Kuliah::class, 'jdkulKlsId', 'klsId');
  }

  public function krsDetailFromMatul()
  {
      return $this->hasMany(Krs_Detil::class, 'krsdtMkkurId','klsMkkurId');
  }

  public function nilaiMahasiswa($nim=null)
  {
      //jikalau ada eloquent, maka returnnya harus eloquent, untuk mengatasinya, dibuatkan fungsi eloquent dan di panggil d fungsi ini
      $dtKrs = $this->krsDetailFromMatul()->join(DB::RAW("(select krsdtMkkurId, max(krsdtBobotNilai) as maxNilai from s_krs_detil where krsdtKrsId in (select krsId from s_krs where krsMhsNiu=$nim) GROUP by krsdtMkkurId) as tMaxNilai"), function($join){
        $join->on("s_krs_detil.krsdtMkkurId","=","tMaxNilai.krsdtMkkurId");
        $join->on("s_krs_detil.krsdtBobotNilai", "=", "tMaxNilai.maxNilai");
      })->whereRaw("krsdtkrsid in (select krsid from s_krs where krsMhsNiu=$nim)")->first(); 
      $hasil = $dtKrs ? $dtKrs->krsdtKodeNilai : "";
      return $hasil;
  }
}

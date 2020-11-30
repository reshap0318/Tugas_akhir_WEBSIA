<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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

  public function krsDetail()
  {
      return $this->hasOne(Krs_Detil::class, 'krsdtklsid','klsId');
  }

  public function nilaiMahasiswa($nim=null)
  {
      //jikalau ada eloquent, maka returnnya harus eloquent, untuk mengatasinya, dibuatkan fungsi eloquent dan di panggil d fungsi ini
      $dtKrs = $this->krsDetail;
      $hasil = !empty($dtKrs) ? $dtKrs->join('s_krs','s_krs_detil.krsdtKrsId','=','krsId')->where('krsMhsNiu',$nim)->first()->krsdtKodeNilai : "";
      return $hasil;
  }
}

<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class UnitController extends Controller
{
    public function getUnits()
    {
        try {
            $fakultas = DB::select("SELECT CAST(fakKode AS INT) as id, fakNamaResmi as name, null as unit_id FROM `fakultas` where fakKode <> 0");

            $jurusan = DB::select("SELECT null as id, jurNamaResmi as name, CAST(jurFakKode AS INT) as unit_id FROM `jurusan`");

            $units = DB::select("SELECT CAST(fakKode AS INT) as id, fakNamaResmi as name, null as unit_id FROM `fakultas` where fakKode <> 0 union SELECT null as id, jurNamaResmi as name, CAST(jurFakKode AS INT) as unit_id FROM `jurusan`");

            $datas = [
                'fakultas' => $fakultas,
                'jurusan'  => $jurusan,
                'units'    => $units
            ];
            return $this->MessageSuccess($datas);
        } catch (\Throwable $th) {
            return $this->MessageError($th->getMessage());
        }
    }
}

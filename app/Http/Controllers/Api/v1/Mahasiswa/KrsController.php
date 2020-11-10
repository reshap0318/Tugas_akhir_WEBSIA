<?php

namespace App\Http\Controllers\Api\v1\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{
    Krs_Detil
};
use App\Http\Resources\{
    Krs\listCollection as listKrsCollection
};

class KrsController extends Controller
{
    public function getListData($nim)
    {
        try {
            $krs = Krs_Detil::whereRaw("krsdtKrsId in (select krsid from s_krs where krsMhsNiu = $nim)")->get();
            $data = listKrsCollection::collection($krs);
            return $this->MessageSuccess($data);
        } catch (\Exception $e) {
            return $this->MessageError($e->getMessage());
        }
    }
}

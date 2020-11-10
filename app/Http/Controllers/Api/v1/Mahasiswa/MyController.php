<?php

namespace App\Http\Controllers\Api\v1\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{
    Mahasiswa
};
use App\Http\Resources\{
    Mahasiswa\listCollection as mahasiswaCollection
};

class MyController extends Controller
{
    public function getListData()
    {
        try {
            $data = Mahasiswa::where('mhsStakmhsrKode','A')->where('mhsNiu','<>',0)->get();
            $data = mahasiswaCollection::collection($data);
            return $this->MessageSuccess($data);
        } catch (\Exception $e) {
            return $this->MessageError($e->getMessage());
        }
    }

    public function getData($nim)
    {
        try {
            $data = Mahasiswa::find($nim);
            $data = new mahasiswaCollection($data);
            return $this->MessageSuccess($data);
        } catch (\Exception $e) {
            return $this->MessageError($e->getMessage());
        }
    }
}

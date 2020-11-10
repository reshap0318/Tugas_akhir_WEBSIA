<?php

namespace App\Http\Controllers\Api\v1\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{
    Semester_Prodi
};
use App\Http\Resources\{
    SemesterProdi\listCollection as listSemesterProdiCollection
};

class SemesterController extends Controller
{
    public function getListData($nim)
    {
        try {
            $semesterProdi = Semester_Prodi::whereRaw("sempId in (select krsSempId from s_krs where krsMhsNiu = $nim)")->get();
            $data = listSemesterProdiCollection::collection($semesterProdi);
            return $this->MessageSuccess($data);
        } catch (\Exception $e) {
            return $this->MessageError($e->getMessage());
        }
    }
}

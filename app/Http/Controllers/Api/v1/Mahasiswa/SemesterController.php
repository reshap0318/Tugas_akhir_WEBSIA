<?php

namespace App\Http\Controllers\Api\v1\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{
    Semester
};
use App\Http\Resources\{
    Semester\listCollection as listSemesterCollection
};

class SemesterController extends Controller
{
    public function getListData($nim)
    {
        try {
            $semesterProdi = Semester::whereRaw("semId in (select sempSemId from s_semester_prodi where sempId in (select krsSempId from s_krs where krsMhsNiu = $nim))")->get();
            $data = listSemesterCollection::collection($semesterProdi);
            return $this->MessageSuccess($data);
        } catch (\Exception $e) {
            return $this->MessageError($e->getMessage());
        }
    }
}

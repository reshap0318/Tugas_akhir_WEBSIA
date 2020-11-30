<?php

namespace App\Http\Controllers\Api\v1;
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
    public function getSemesterAktif()
    {
        try {
            $semester = Semester::whereRaw("semId in (select sempSemId from s_semester_prodi where sempIsAktif = 1)")->first();
            $data = new listSemesterCollection($semester);
            return $this->MessageSuccess($data);
        } catch (\Exception $e) {
            return $this->MessageError($e->getMessage());
        }
    }

    public function getListSemester()
    {
        try {
            $semester = Semester::select("s_semester.*")->where("semTahun", ">", "2015")->whereIn("semNmSemrId", [1,2])->distinct()->get();
            $semester = listSemesterCollection::collection($semester);
            return $this->MessageSuccess($semester);
        } catch (\Exception $e) {
            return $this->MessageError($e->getMessage());
        }
    }
}

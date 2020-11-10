<?php

namespace App\Http\Controllers\Api\v1;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\{
    Semester_Prodi
};
use App\Http\Resources\{
    SemesterProdi\listCollection as listSemesterProdiCollection
};

class SemesterProdiController extends Controller
{
    public function getSemesterAktif()
    {
        try {
            $semesterProdi = Semester_Prodi::where('sempIsAktif',1)->first();
            $data = new listSemesterProdiCollection($semesterProdi);
            return $this->MessageSuccess($data);
        } catch (\Exception $e) {
            return $this->MessageError($e->getMessage());
        }
    }
}

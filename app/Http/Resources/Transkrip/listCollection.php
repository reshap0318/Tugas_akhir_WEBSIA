<?php

namespace App\Http\Resources\Transkrip;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Semester\listCollection as listSemesterCollection;
use App\Http\Resources\Transkrip\listMatkulCollection;
use Illuminate\Support\Facades\DB;

class listCollection extends JsonResource
{

    public function toArray($request)
    {
        $nim = $this->krsMhsNiu;
        $matkul = $this->detailKrs()->join(DB::RAW("(select krsdtMkkurId, max(krsdtBobotNilai) as maxNilai from s_krs_detil where krsdtKrsId in (select krsId from s_krs where krsMhsNiu=$nim) GROUP by krsdtMkkurId) as tMaxNilai"), function($join){
            $join->on("s_krs_detil.krsdtMkkurId","=","tMaxNilai.krsdtMkkurId");
            $join->on("s_krs_detil.krsdtBobotNilai", "=", "tMaxNilai.maxNilai");
        })->whereNotNull('krsdtKodeNilai')->where(function ($query) {
            $query->where('krsdtApproved',1)->orWhereRaw('krsdtKlsId in (select klsId from s_kelas where klsSemId in (select semId from s_semester where semNmSemrId = 4))');
        })->where('krsdtIsDipakaiTranskrip',1)->where("krsdtIsBatal",0)->get();
        
        return [
            'semester' => new listSemesterCollection($this->semProdi->semester),
            'matkul' => listMatkulCollection::collection($matkul)
        ];

        /* 
        SELECT * FROM `s_krs_detil`
        LEFT OUTER join s_krs_detil as b
        on s_krs_detil.krsdtId = b.krsdtId and s_krs_detil.krsdtBobotNilai < b.krsdtBobotNilai 
        where b.krsdtId is null and s_krs_detil.krsdtKrsId in (select krsId from s_krs where krsMhsNiu='1611522004') and s_krs_detil.krsdtMkkurId = 830000061

        SELECT * FROM `s_krs_detil`
        inner join (
            select krsdtMkkurId, max(krsdtBobotNilai) as maxNilai from s_krs_detil where krsdtKrsId in (select krsId from s_krs where krsMhsNiu=1611522004) GROUP by krsdtMkkurId
        ) as tMaxNilai on s_krs_detil.krsdtMkkurId = tMaxNilai.krsdtMkkurId and s_krs_detil.krsdtBobotNilai = tMaxNilai.maxNilai where s_krs_detil.krsdtKrsId in (select krsId from s_krs where krsMhsNiu='1611522004')
        
        */
    }
}

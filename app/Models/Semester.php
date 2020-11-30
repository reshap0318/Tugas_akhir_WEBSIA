<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Semester extends Model
{
    protected $table = 's_semester';
    protected $primaryKey = 'semId';
    // public $incrementing = false;
    public $timestamps = false;

    public function ref()
    {
        return $this->hasOne(Semester_Ref::class, 'nmsemrId', 'semNmsemrId');
    }
}

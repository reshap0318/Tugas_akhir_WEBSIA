<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    protected $table = 's_user';
    protected $primaryKey = 'susrNama';
    public $incrementing = false;
    public $timestamps = false;
    
}

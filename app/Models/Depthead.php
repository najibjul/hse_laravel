<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Depthead extends Model
{
    protected $fillable = [
        'department_id',
        'user_id'
    ];
}

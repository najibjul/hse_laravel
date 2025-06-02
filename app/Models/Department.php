<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $fillable = [
        'department_name'
    ];

    public function depthead()
    {
        return $this->belongsTo(User::class, 'dept_head_id', 'id');
    }
}

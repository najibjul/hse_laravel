<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminDepartment extends Model
{
    protected $fillable = ['admin_id', 'department_id'];

    public function admin () 
    {
        return $this->belongsTo(User::class, 'admin_id', 'id');
    }

    public function department () 
    {
        return $this->belongsTo(Department::class);
    }
}

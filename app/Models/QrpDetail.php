<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QrpDetail extends Model
{
    protected $fillable = [
        'daily_check_id',
        'description',
        'before',
        'recomendation',
        'dept_head_id',
        'approved_dept_head_at',
        'admin_id',
        'approved_admin_at',
        'after',
        'after_uploaded_at',
        'qrp_status_id'
    ];

    public function deptHead(){
        return $this->belongsTo(User::class,'dept_head_id','id');
    }

    public function admin(){
        return $this->belongsTo(User::class,'admin_id','id');
    }
    
    public function qrpStatus(){
        return $this->belongsTo(QrpStatus::class);
        
    }
}

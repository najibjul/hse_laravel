<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QrpDetail extends Model
{
    protected $fillable = [
        'daily_check_id',
        'description',
        'category_id',
        'before',
        'rank_id',
        'due_date',
        'after',
        'after_uploaded_at',
        'closed_at',
        'qrp_status_id',
        'revision_note'
    ];

    public function deptHead()
    {
        return $this->belongsTo(User::class, 'dept_head_id', 'id');
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id', 'id');
    }

    public function qrpStatus()
    {
        return $this->belongsTo(QrpStatus::class);
    }

    public function dailyCheck()
    {
        return $this->belongsTo(DailyCheck::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function rank()
    {
        return $this->belongsTo(Rank::class);
    }

    public function adh()
    {
        return $this->belongsTo(user::class, 'adh_id', 'id');
    }

    public function dh()
    {
        return $this->belongsTo(user::class, 'dh_id', 'id');
    }

    public function ph()
    {
        return $this->belongsTo(user::class, 'ph_id', 'id');
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function qrpRecomendations()
    {
        return $this->hasMany(QrpRecomendation::class);
    }

    public function qrpApprovals()
    {
        return $this->hasMany(QrpApproval::class);
    }

}

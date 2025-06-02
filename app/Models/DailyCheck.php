<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DailyCheck extends Model
{
    protected $fillable = [
        'user_id',
        'activity',
        'area',
        'factor_id',
        'check_status'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function qrpDetail(){
        return $this->hasOne(QrpDetail::class);
    }

    public function factor()
    {
        return $this->belongsTo(Factor::class);
        
    }
}

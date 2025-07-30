<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QrpRecomendation extends Model
{
    protected $fillable = ['qrp_detail_id', 'user_id', 'recomendation', 'status'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

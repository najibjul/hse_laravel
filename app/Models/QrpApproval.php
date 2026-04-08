<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QrpApproval extends Model
{
    protected $fillable = ['qrp_detail_id', 'approval_id', 'approved_at', 'status'];

    public function approval()
    {
        return $this->belongsTo(User::class);
    }

}

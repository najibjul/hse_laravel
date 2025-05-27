<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = ['daily_check_id', 'from_user_id', 'to_user_id', 'message'];

    public function fromUser()
    {
        return $this->belongsTo(User::class);
    }
    
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = ['user_id', 'title', 'body', 'target', 'target_id', 'is_read'];

    public function fromUser()
    {
        return $this->belongsTo(User::class);
    }
    
}

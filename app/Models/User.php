<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */

    protected $guarded = [];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function department () {
        return $this->belongsTo(Department::class);
    }

    public function role () {
        return $this->belongsTo(Role::class);
    }
    
    public function position() 
    {
        return $this->belongsTo(Position::class);
    }

    public function plant() 
    {
        return $this->belongsTo(Plant::class);
    }
    
    public function leader()
    {
        return $this->belongsTo(User::class, 'leader_id', 'id');
    }
    
    public function costCenter() 
    {
        return $this->belongsTo(CostCenter::class, 'cost_center_id', 'id');
    }

    public function adminDepts() 
    {
        return $this->hasMany(AdminDepartment::class, 'admin_id', 'id');
    }
}

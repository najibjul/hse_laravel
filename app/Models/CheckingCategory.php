<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CheckingCategory extends Model
{
    use HasFactory;

    protected $fillable = ['checking_category_name'];
}

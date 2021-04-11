<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reader extends Model
{
    use HasFactory;
    // protected $primaryKey = "student_code";
    protected $fillable = [
        'student_code',
        'name',
        'email',
        'phone',
        'gender',
        'birthday'
    ];
}

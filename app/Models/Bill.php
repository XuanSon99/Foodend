<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bill extends Model
{
    use HasFactory;
    // protected $primaryKey = 'id';
    protected $fillable = [
        'student_id',
        'document_id',
        'lender',
        'borrow_time',
        'return_time',
        'status',
        'amount'
    ];
    public function getReader()
    {
        return $this->hasMany('App\Models\Reader', 'student_code', 'student_id')->select('student_code', 'name');
    }
    public function getDocument()
    {
        return $this->hasMany('App\Models\Document', 'id', 'document_id')->select('id', 'name');
    }
}

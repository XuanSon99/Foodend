<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Author extends Model
{
    use HasFactory;
    protected $fillable = [
        // 'author_id',
        'name',
        'note'
    ];
    public function getDoc(){
        return $this->hasMany('App\Models\Document', 'id');
    }
}

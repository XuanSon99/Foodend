<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'image',
        'cate_id',
        'time',
        'price'
    ];
    public function getCate()
    {
        return $this->hasMany('App\Models\Category', 'id', 'cate_id');
    }
}

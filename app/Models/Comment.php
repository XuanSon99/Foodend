<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;
    protected $fillable = [
        'customer_id',
        'product_id',
        'content',
    ];
    public function getCustomer()
    {
        return $this->hasMany('App\Models\User', 'id', 'user_id');
    }
    public function getProduct()
    {
        return $this->hasMany('App\Models\Product', 'id', 'product_id');
    }
}

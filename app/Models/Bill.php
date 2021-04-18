<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bill extends Model
{
    use HasFactory;
    // protected $primaryKey = 'id';
    public $timestamps = true;
    protected $fillable = [
        'user_id',
        'product_id',
        'address',
        'price',
        'quantity',
        'status',
        'bill_id'
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

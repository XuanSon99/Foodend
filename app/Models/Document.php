<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;
    protected $primaryKey = "id";

    protected $fillable = [
        'name',
        'author_id',
        'publisher_id',
        'language_id',
        'field_id',
        'publishing_year',
        'price',
        'page_number',
        'category',
        'amount'
    ];
    public function getAuthor()
    {
        return $this->hasMany('App\Models\Author', 'id', 'author_id')->select('id', 'name');
    }
    public function getLanguage()
    {
        return $this->hasMany('App\Models\Language', 'id', 'publisher_id')->select('id', 'name');
    }
    public function getPublisher()
    {
        return $this->hasMany('App\Models\Publisher', 'id', 'language_id')->select('id', 'name');
    }
    public function getField()
    {
        return $this->hasMany('App\Models\Field', 'id', 'field_id')->select('id', 'name');
    }
}

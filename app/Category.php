<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'products_categories';
    protected $primaryKey = "category_id";
    public $timestamps = true;
}

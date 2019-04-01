<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CategorisedProducts extends Model
{
    protected $table = 'products_categorised';
    protected $primaryKey = "products_categorised_id";
    public $timestamps = false;
}

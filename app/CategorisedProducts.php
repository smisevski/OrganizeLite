<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CategorisedProducts extends Model
{
    protected $table = 'vw_products_by_categories';
    protected $primaryKey = "products_id";
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Invoices extends Model
{
    protected $table = 'invoices';
    protected $primaryKey = "invoice_id";
    public $timestamps = true;
}

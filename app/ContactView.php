<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ContactView extends Model
{
    protected $table = 'vw_clients';
    protected $primaryKey = "contact_id";
    public $timestamps = true;
}

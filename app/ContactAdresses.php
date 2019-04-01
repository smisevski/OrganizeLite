<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ContactAdresses extends Model
{
    protected $table = 'contacts_address';
    protected $primaryKey = "contact_address_id";
    public $timestamps = true;
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ContactCompany extends Model
{
    protected $table = 'contacts_company';
    protected $primaryKey = "contact_company_id";
    public $timestamps = true;
}

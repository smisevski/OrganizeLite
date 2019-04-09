<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Invoices;

class InvoiceController extends Controller
{
    public function GetAllInvoices()
    {
        $invoices = Invoices::all();
        return $invoices;
    }
}

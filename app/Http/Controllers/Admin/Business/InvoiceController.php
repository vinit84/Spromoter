<?php

namespace App\Http\Controllers\Admin\Business;

use App\DataTables\Admin\Business\InvoiceDataTable;
use App\Http\Controllers\Controller;

class InvoiceController extends Controller
{
    public function index(InvoiceDataTable $dataTable)
    {
        return $dataTable->render('admin.business.invoices.index');
    }
}

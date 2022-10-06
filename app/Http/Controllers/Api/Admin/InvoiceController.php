<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\InvoiceResource;
use App\Models\Invoice;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function index()
    {
        $invoices = Invoice::with('orders.product', 'customer', 'city', 'province')
            ->when(Request()->q, function ($invoices) {
                $invoices = $invoices->where('invoice', 'like', '%' . Request()->q, '%');
            })->latest()->paginate(5);

        return new InvoiceResource(true, 'Data List Invoice', $invoices);
    }

    public function show($id)
    {
        $invoices = Invoice::with('orders.product', 'customer', 'city', 'province')->find($id);
        if ($invoices) {
            return new InvoiceResource(true, 'Detail data invoice ditemukan', $invoices);
        } else {
            return new InvoiceResource(false, 'Detail data invoice tidak ditemukan', null);
        }
    }
}
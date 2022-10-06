<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\CustomerResource;
use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = Customer::when(Request()->q, function ($customers) {
            $customers = $customers->where('name', 'like', '%' . Request()->q . '%');
        })->latest()->paginate(5);

        return new CustomerResource(true, 'Data List Customer', $customers);
    }
}
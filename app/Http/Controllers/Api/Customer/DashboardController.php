<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // count
        $success = Invoice::where('status', 'success')->where('customer_id', auth()->guard('api_customer')->user()->id)->count();
        $pending = Invoice::where('status', 'pending')->where('customer_id', auth()->guard('api_customer')->user()->id)->count();
        $failed = Invoice::where('status', 'failed')->where('customer_id', auth()->guard('api_customer')->user()->id)->count();
        $expired = Invoice::where('status', 'success')->where('customer_id', auth()->guard('api_customer')->user()->id)->count();

        return response()->json(
            [
                'success' => true,
                'message' => 'Statistik Dashboard Customer',
                'data' => [
                    'count' => [
                        'success' => $success,
                        'pending' => $pending,
                        'failed' => $failed,
                        'expired' => $expired
                    ]
                ]
            ]
        );
    }
}
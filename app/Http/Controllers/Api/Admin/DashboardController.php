<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // count
        $success = Invoice::where('status', 'success')->count();
        $pending = Invoice::where('status', 'pending')->count();
        $failed = Invoice::where('status', 'failed')->count();
        $expired = Invoice::where('status', 'expired')->count();

        //year and month
        $year = date('Y');
        $transactions = DB::table('invoices')
            ->where('status', 'success')
            ->addSelect(DB::raw('SUM(grand_total)  as grand_total'))
            ->addSelect(DB::raw('MONTH(created_at) as month'))
            ->addSelect(DB::raw('MONTHNAME(created_at) as month_name'))
            ->addSelect(DB::raw('YEAR(created_at) as year'))
            ->whereYear('created_at', '=', $year)
            ->groupBy('month')
            ->orderByRaw('month ASC')
            ->get();


        if (count($transactions)) {
            foreach ($transactions as $result) {
                $month_name[] = $result->month_name;
                $grand_total[] = (int)$result->grand_total;
            }
        } else {
            $month_name[] = "";
            $grand_total[] = "";
        }

        // return response 
        return response()->json([
            'success' => true,
            'message' => 'Stastik Data',
            'data' => [
                'count' => [
                    'pending' => $pending,
                    'success' => $success,
                    'expired' => $expired,
                    'failed' => $failed
                ],
                'chart' => [
                    'month_name' => $month_name,
                    'grand_total' => $grand_total
                ]
            ]
        ], 200);
    }
}
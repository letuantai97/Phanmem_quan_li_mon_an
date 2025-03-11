<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Carbon\Carbon;

class RevenueController extends Controller
{
    public function index(Request $request)
    {
        $timeframe = $request->input('timeframe', 'today');

        switch ($timeframe) {
            case 'today':
                $start = Carbon::today();
                $end = Carbon::now();
                break;
            case 'this_month':
                $start = Carbon::now()->startOfMonth();
                $end = Carbon::now();
                break;
            case 'this_year':
                $start = Carbon::now()->startOfYear();
                $end = Carbon::now();
                break;
            default:
                $start = Carbon::today();
                $end = Carbon::now();
        }

        // Lấy các đơn hàng hoàn thành trong khoảng thời gian
        $orders = Order::where('status', 'hoàn thành')
            ->whereBetween('created_at', [$start, $end])
            ->with(['products', 'user'])
            ->get();

        // Tính tổng doanh thu
        $totalRevenue = $orders->sum('total_amount');

        return view('revenue.index', compact('orders', 'totalRevenue', 'timeframe'));
    }

    public function report(Request $request)
    {
        return $this->index($request);
    }
}

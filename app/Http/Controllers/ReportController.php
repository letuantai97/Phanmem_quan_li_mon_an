<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Food;
use App\Models\Category;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $dateRange = $request->date_range ?? 'month';
        $query = Order::query();

        // Xử lý filter theo thời gian
        switch ($dateRange) {
            case 'today':
                $query->whereDate('created_at', Carbon::today());
                break;
            case 'week':
                $query->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
                break;
            case 'specific_month':
                $selectedMonth = $request->selected_month ?? Carbon::now()->month;
                $query->whereMonth('created_at', $selectedMonth)
                      ->whereYear('created_at', Carbon::now()->year);
                break;
            case 'custom':
                $start = $request->start_date ? Carbon::parse($request->start_date) : Carbon::now()->startOfMonth();
                $end = $request->end_date ? Carbon::parse($request->end_date)->endOfDay() : Carbon::now()->endOfDay();
                $query->whereBetween('created_at', [$start, $end]);
                break;
            default: // month
                $query->whereMonth('created_at', Carbon::now()->month)
                      ->whereYear('created_at', Carbon::now()->year);
        }

        // Cập nhật thống kê theo giờ dựa trên khoảng thời gian
        $hourlyQuery = DB::table('orders');
        if ($dateRange === 'today') {
            $hourlyQuery->whereDate('created_at', Carbon::today());
        } elseif ($dateRange === 'specific_month') {
            $selectedMonth = $request->selected_month ?? Carbon::now()->month;
            $hourlyQuery->whereMonth('created_at', $selectedMonth)
                        ->whereYear('created_at', Carbon::now()->year);
        }

        // Tổng quan
        $overview = [
            'total_orders' => $query->count(),
            'total_revenue' => $query->sum('total_amount'),
            'average_order_value' => $query->avg('total_amount') ?? 0,
            'hourly_stats' => [
                'orders' => $hourlyQuery->clone()
                    ->selectRaw('HOUR(created_at) as hour, COUNT(*) as count')
                    ->groupBy('hour')
                    ->get(),
                'items' => DB::table('orders')
                    ->join('order_items', 'orders.id', '=', 'order_items.order_id')
                    ->selectRaw('HOUR(orders.created_at) as hour, SUM(order_items.quantity) as total_items')
                    ->when($dateRange === 'today', function($query) {
                        return $query->whereDate('orders.created_at', Carbon::today());
                    })
                    ->when($dateRange === 'specific_month', function($query) use ($request) {
                        $selectedMonth = $request->selected_month ?? Carbon::now()->month;
                        return $query->whereMonth('orders.created_at', $selectedMonth)
                                     ->whereYear('orders.created_at', Carbon::now()->year);
                    })
                    ->groupBy('hour')
                    ->get(),
                'revenue' => $hourlyQuery->clone()
                    ->selectRaw('HOUR(created_at) as hour, SUM(total_amount) as total_revenue')
                    ->groupBy('hour')
                    ->get()
            ]
        ];

        // Thống kê doanh thu theo ngày
        $revenueStats = $query->select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('SUM(total_amount) as total_revenue')
        )
        ->groupBy('date')
        ->orderBy('date')
        ->get();

        // Top món ăn bán chạy
        $topFoods = Food::select('foods.*')
            ->join('order_items', 'foods.id', '=', 'order_items.food_id')
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->selectRaw('SUM(order_items.quantity) as total_quantity')
            ->selectRaw('SUM(order_items.quantity * order_items.price) as total_revenue')
            ->groupBy('foods.id')
            ->orderByDesc('total_quantity')
            ->take(5)
            ->get();

        // Thống kê theo danh mục
        $categoryStats = Category::select('categories.*')
            ->join('foods', 'categories.id', '=', 'foods.category_id')
            ->join('order_items', 'foods.id', '=', 'order_items.food_id')
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->selectRaw('COUNT(DISTINCT orders.id) as total_orders')
            ->selectRaw('SUM(order_items.quantity) as total_items')
            ->selectRaw('SUM(order_items.quantity * order_items.price) as total_revenue')
            ->groupBy('categories.id')
            ->get();

        return view('reports.index', compact(
            'overview',
            'revenueStats',
            'topFoods',
            'categoryStats'
        ));
    }
}

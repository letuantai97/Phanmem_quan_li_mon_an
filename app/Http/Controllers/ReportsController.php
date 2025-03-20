<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Food;
use App\Models\Category;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReportsController extends Controller
{
    public function index(Request $request)
    {
        $dateRange = $request->input('date_range', 'month');
        $selectedMonth = $request->input('selected_month', date('n'));
        $startDate = null;
        $endDate = null;

        switch ($dateRange) {
            case 'today':
                $startDate = Carbon::today();
                $endDate = Carbon::today()->endOfDay();
                break;
            case 'week':
                $startDate = Carbon::now()->startOfWeek();
                $endDate = Carbon::now()->endOfWeek();
                break;
            case 'month':
                $startDate = Carbon::now()->startOfMonth();
                $endDate = Carbon::now()->endOfMonth();
                break;
            case 'specific_month':
                $currentDate = Carbon::now();
                $year = $currentDate->year;
                $month = (int)$selectedMonth;
                
                // If selected month is in the future, use previous year
                if ($month > $currentDate->month) {
                    $year = $currentDate->subYear()->year;
                }
                
                $startDate = Carbon::create($year, $month, 1, 0, 0, 0);
                $endDate = $startDate->copy()->endOfMonth();
                break;
            case 'custom':
                $startDate = $request->input('start_date') ? Carbon::parse($request->input('start_date')) : Carbon::today();
                $endDate = $request->input('end_date') ? Carbon::parse($request->input('end_date'))->endOfDay() : Carbon::today()->endOfDay();
                break;
        }

        // Thống kê tổng quan
        $overview = [
            'total_orders' => Order::whereBetween('created_at', [$startDate, $endDate])->count(),
            'total_revenue' => Order::whereBetween('created_at', [$startDate, $endDate])->sum('total_amount'),
            'average_order_value' => Order::whereBetween('created_at', [$startDate, $endDate])->avg('total_amount') ?? 0,
            'orders_by_time' => array_fill(0, 6, 0),
            'items_by_time' => array_fill(0, 6, 0),
            'revenue_by_time' => array_fill(0, 6, 0),
            'hourly_stats' => [
                'orders' => DB::table('orders')
                    ->select(DB::raw('HOUR(created_at) as hour'), DB::raw('COUNT(*) as count'))
                    ->whereBetween('created_at', [$startDate, $endDate])
                    ->groupBy('hour')
                    ->get(),
                'items' => DB::table('order_items')
                    ->join('orders', 'orders.id', '=', 'order_items.order_id')
                    ->select(DB::raw('HOUR(orders.created_at) as hour'), DB::raw('SUM(order_items.quantity) as total_items'))
                    ->whereBetween('orders.created_at', [$startDate, $endDate])
                    ->groupBy('hour')
                    ->get(),
                'revenue' => DB::table('orders')
                    ->select(DB::raw('HOUR(created_at) as hour'), DB::raw('SUM(total_amount) as total_revenue'))
                    ->whereBetween('created_at', [$startDate, $endDate])
                    ->groupBy('hour')
                    ->get()
            ]
        ];

        // Thống kê doanh thu theo thời gian
        $revenueStats = Order::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('SUM(total_amount) as total_revenue')
        )
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Top món ăn bán chạy
        $topFoods = Food::select(
            'foods.id',
            'foods.name',
            DB::raw('SUM(order_items.quantity) as total_quantity'),
            DB::raw('SUM(order_items.quantity * order_items.price) as total_revenue')
        )
            ->join('order_items', 'foods.id', '=', 'order_items.food_id')
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->whereBetween('orders.created_at', [$startDate, $endDate])
            ->groupBy('foods.id', 'foods.name')
            ->orderByDesc('total_quantity')
            ->paginate(5);

        // Thống kê theo danh mục
        $categoryStats = Category::select(
            'categories.id',
            'categories.name',
            DB::raw('COUNT(DISTINCT orders.id) as total_orders'),
            DB::raw('SUM(order_items.quantity) as total_items'),
            DB::raw('SUM(order_items.quantity * order_items.price) as total_revenue')
        )
            ->join('foods', 'categories.id', '=', 'foods.category_id')
            ->join('order_items', 'foods.id', '=', 'order_items.food_id')
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->whereBetween('orders.created_at', [$startDate, $endDate])
            ->groupBy('categories.id', 'categories.name')
            ->get();

        return view('reports.index', compact('overview', 'revenueStats', 'topFoods', 'categoryStats'));
    }
}

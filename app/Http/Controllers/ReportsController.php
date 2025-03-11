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
        $dateRange = $request->input('date_range', 'today');
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
                    ->orderBy('hour')
                    ->get(),
                'items' => DB::table('orders')
                    ->join('order_items', 'orders.id', '=', 'order_items.order_id')
                    ->select(DB::raw('HOUR(orders.created_at) as hour'), DB::raw('SUM(order_items.quantity) as total_items'))
                    ->whereBetween('orders.created_at', [$startDate, $endDate])
                    ->groupBy('hour')
                    ->orderBy('hour')
                    ->get(),
                'revenue' => DB::table('orders')
                    ->select(DB::raw('HOUR(created_at) as hour'), DB::raw('SUM(total_amount) as total_revenue'))
                    ->whereBetween('created_at', [$startDate, $endDate])
                    ->groupBy('hour')
                    ->orderBy('hour')
                    ->get()
            ],
            'weekly_stats' => [
                'orders' => DB::table('orders')
                    ->select(DB::raw('DAYOFWEEK(created_at) as day'), DB::raw('COUNT(*) as count'))
                    ->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
                    ->groupBy('day')
                    ->orderBy('day')
                    ->get(),
                'items' => DB::table('orders')
                    ->join('order_items', 'orders.id', '=', 'order_items.order_id')
                    ->select(DB::raw('DAYOFWEEK(orders.created_at) as day'), DB::raw('SUM(order_items.quantity) as total_items'))
                    ->whereBetween('orders.created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
                    ->groupBy('day')
                    ->orderBy('day')
                    ->get()
            ],
            'monthly_stats' => [
                'orders' => DB::table('orders')
                    ->select(DB::raw('DAY(created_at) as day'), DB::raw('COUNT(*) as count'))
                    ->whereBetween('created_at', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()])
                    ->groupBy('day')
                    ->orderBy('day')
                    ->get(),
                'items' => DB::table('orders')
                    ->join('order_items', 'orders.id', '=', 'order_items.order_id')
                    ->select(DB::raw('DAY(orders.created_at) as day'), DB::raw('SUM(order_items.quantity) as total_items'))
                    ->whereBetween('orders.created_at', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()])
                    ->groupBy('day')
                    ->orderBy('day')
                    ->get()
            ]
        ];

        // Dữ liệu thống kê doanh thu
        $revenueStats = DB::table('orders')
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(total_amount) as total_revenue')
            )
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('date')
            ->orderBy('date')
            ->paginate(5);

        // Món ăn bán chạy nhất
        $topFoodsQuery = Food::select(
            'foods.id',
            'foods.name',
            DB::raw('SUM(order_items.quantity) as total_quantity'),
            DB::raw('SUM(order_items.quantity * order_items.price) as total_revenue')
        )
            ->join('order_items', 'foods.id', '=', 'order_items.food_id')
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->whereBetween('orders.created_at', [$startDate, $endDate])
            ->groupBy('foods.id', 'foods.name')
            ->orderByDesc('total_quantity');

        $topFoods = $topFoodsQuery->paginate(5);

        // Thống kê danh mục
        $categoryStatsQuery = Category::select(
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
            ->orderByDesc('total_revenue');

        $categoryStats = $categoryStatsQuery->paginate(5);

        return view('reports.index', compact('overview', 'revenueStats', 'topFoods', 'categoryStats', 'startDate', 'endDate'));
    }
}

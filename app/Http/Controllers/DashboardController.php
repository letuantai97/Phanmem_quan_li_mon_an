<?php

namespace App\Http\Controllers;

use App\Models\Food;
use App\Models\Category;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $data = [
            'totalFoods' => Food::count(),
            'totalCategories' => Category::count(),
            'totalOrders' => Order::count(),
            'totalRevenue' => Order::where('status', 'hoàn thành')->sum('total_amount'),
            'recentOrders' => Order::with(['items.food'])
                ->latest()
                ->take(5)
                ->get(),
            'topFoods' => Food::withCount('orderItems')
                ->orderBy('order_items_count', 'desc')
                ->take(5)
                ->get()
        ];

        return view('dashboard', $data);
    }
}

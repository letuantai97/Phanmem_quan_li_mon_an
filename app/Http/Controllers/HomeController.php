<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Food;
use App\Models\Category;
use App\Models\Order;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $totalFoods = Food::where('status', 'active')->count();
        $totalCategories = Category::count();
        $newOrders = Order::where('status', 'pending')->count();
        $totalRevenue = Order::where('status', 'completed')->sum('total_amount');

        $recentOrders = Order::latest()->take(5)->get();

        return view('home', compact('totalFoods', 'totalCategories', 'newOrders', 'totalRevenue', 'recentOrders'));
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Food;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['items.food']);

        // Search by customer name, phone, or email
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('customer_name', 'like', '%' . $search . '%')
                  ->orWhere('customer_phone', 'like', '%' . $search . '%')
                  ->orWhere('customer_email', 'like', '%' . $search . '%');
            });
        }

        // Filter by status
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        $orders = $query->latest()->paginate(10);
        return view('orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load('items.food');
        return view('orders.show', compact('order'));
    }

    public function create()
    {
        $foods = Food::where('status', true)->get();
        return view('orders.create', compact('foods'));
    }

    public function update(Request $request, Order $order)
    {
        $validatedData = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:20',
            'customer_email' => 'required|email|max:255',
            'address' => 'required|string|max:255',
            'status' => 'required|in:chờ xử lý,đang xử lý,hoàn thành,đã hủy'
        ]);

        $order->update([
            'customer_name' => trim($validatedData['customer_name']),
            'customer_phone' => trim($validatedData['customer_phone']),
            'customer_email' => trim($validatedData['customer_email']),
            'address' => trim($validatedData['address']),
            'status' => $validatedData['status']
        ]);

        return redirect()->route('orders.show', $order)
            ->with('success', 'Đơn hàng đã được cập nhật thành công.');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:20',
            'customer_email' => 'required|email|max:255',
            'address' => 'required|string|max:255',
            'items' => 'required|array|min:1',
            'items.*.food_id' => 'required|exists:foods,id',
            'items.*.quantity' => 'required|integer|min:1'
        ]);

        $order = Order::create([
            'customer_name' => trim($validatedData['customer_name']),
            'customer_phone' => trim($validatedData['customer_phone']),
            'customer_email' => trim($validatedData['customer_email']),
            'address' => trim($validatedData['address']),
            'status' => 'chờ xử lý',
            'total_amount' => 0
        ]);

        $totalAmount = 0;
        foreach ($request->items as $item) {
            $food = Food::find($item['food_id']);
            $amount = $food->price * $item['quantity'];
            $totalAmount += $amount;

            $order->items()->create([
                'food_id' => $item['food_id'],
                'quantity' => $item['quantity'],
                'price' => $food->price,
                'subtotal' => $amount
            ]);
        }

        $order->update(['total_amount' => $totalAmount]);

        return redirect()->route('orders.show', $order)
            ->with('success', 'Đơn hàng đã được tạo thành công.');
    }

    public function edit(Order $order)
    {
        return view('orders.edit', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => 'required|in:chờ xử lý,đang xử lý,hoàn thành,đã hủy'
        ]);

        $oldStatus = $order->status;
        $order->update(['status' => $validated['status']]);

        $statusMessages = [
            'chờ xử lý' => 'Đơn hàng đã được đặt lại trạng thái chờ xử lý',
            'đang xử lý' => 'Đơn hàng đang được xử lý',
            'hoàn thành' => 'Đơn hàng đã hoàn thành',
            'đã hủy' => 'Đơn hàng đã bị hủy'
        ];

        return redirect()->back()
            ->with('success', $statusMessages[$validated['status']] . '!');
    }
}

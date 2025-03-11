<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Food;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with(['items.food'])->latest()->paginate(10);
        return view('orders.index', compact('orders'));
    }

    public function create()
    {
        $foods = Food::where('status', true)->get();
        return view('orders.create', compact('foods'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'items' => 'required|array|min:1',
            'items.*.food_id' => 'required|exists:foods,id',
            'items.*.quantity' => 'required|integer|min:1'
        ]);

        $order = Order::create([
            'customer_name' => $request->customer_name,
            'status' => 'pending',
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

    public function show(Order $order)
    {
        $order->load('items.food');
        return view('orders.show', compact('order'));
    }

    public function edit(Order $order)
    {
        $foods = Food::where('status', true)->get();
        $order->load('items.food');
        return view('orders.edit', compact('order', 'foods'));
    }

    public function update(Request $request, Order $order)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'status' => 'required|in:pending,completed',
            'items' => 'required|array|min:1',
            'items.*.food_id' => 'required|exists:foods,id',
            'items.*.quantity' => 'required|integer|min:1'
        ]);

        // Update order details
        $order->update([
            'customer_name' => $request->customer_name,
            'status' => $request->status
        ]);

        // Delete old items
        $order->items()->delete();

        // Add new items
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
            ->with('success', 'Đơn hàng đã được cập nhật thành công.');
    }

    public function destroy(Order $order)
    {
        $order->items()->delete();
        $order->delete();

        return redirect()->route('orders.index')
            ->with('success', 'Đơn hàng đã được xóa thành công.');
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Food;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
    public function create()
    {
        $foods = Food::active()->get();
        return view('orders.create', compact('foods'));
    }
    public function update(Request $request, Order $order)
    {
        $validatedData = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:20',
            'customer_email' => 'nullable|email|max:255',
            'address' => 'required|string',
            'status' => 'required|in:chờ xử lý,đang xử lý,hoàn thành,đã hủy'
        ]);

        try {
            $order->update($validatedData);
            return redirect()->route('orders.index')->with('success', 'Đơn hàng đã được cập nhật thành công!');
        } catch (\Exception $e) {
            return back()->with('error', 'Có lỗi xảy ra khi cập nhật đơn hàng: ' . $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        // Xác thực dữ liệu đầu vào
        $rules = [
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'nullable|email|max:255',
            'note' => 'nullable|string',
            'order_type' => 'required|in:dine-in,online',
            'items' => 'required|array|min:1',
            'items.*.food_id' => 'required|exists:foods,id',
            'items.*.quantity' => 'required|integer|min:1',
            'customer_phone' => 'nullable|string|max:20',
            'table_number' => 'nullable|integer|min:1|max:100',
            'address' => 'nullable|string'
        ];

        // Add conditional validation rules based on order type
        if ($request->order_type === 'online') {
            $rules['customer_phone'] = 'required|string|max:20';
            $rules['address'] = 'required|string';
        } else if ($request->order_type === 'dine-in') {
            $rules['table_number'] = 'required|integer|min:1|max:100';
            $rules['customer_phone'] = 'nullable|string|max:20';
        }

        $validatedData = $request->validate($rules);

        try {
            DB::beginTransaction();

            // Calculate total amount
            $totalAmount = 0;
            foreach ($request->items as $item) {
                $food = Food::findOrFail($item['food_id']);
                $totalAmount += $food->price * $item['quantity'];
            }

            // Prepare order data
            $orderData = [
                'customer_name' => $validatedData['customer_name'],
                'customer_email' => $validatedData['customer_email'] ?? null,
                'customer_phone' => $validatedData['customer_phone'] ?? null,
                'note' => $validatedData['note'] ?? null,
                'order_type' => $validatedData['order_type'],
                'table_number' => $validatedData['table_number'] ?? null,
                'status' => 'chờ xử lý',
                'payment_status' => 'pending',
                'total_amount' => $totalAmount
            ];

            // Add online-specific fields
            if ($request->order_type === 'online') {
                $orderData['customer_phone'] = $validatedData['customer_phone'];
                $orderData['address'] = $validatedData['address'];
            } else if ($request->order_type === 'dine-in') {
                $orderData['table_number'] = $validatedData['table_number'];
            }

            // Create order
            $order = Order::create($orderData);

            // Thêm các món ăn vào đơn hàng
            foreach ($request->items as $item) {
                $food = Food::findOrFail($item['food_id']);
                OrderItem::create([
                    'order_id' => $order->id,
                    'food_id' => $food->id,
                    'quantity' => $item['quantity'],
                    'price' => $food->price
                ]);
            }

            // Cập nhật tổng tiền đơn hàng
            $order->updateTotal();

            DB::commit();

            return redirect()->route('orders.index')
                ->with('success', 'Đơn hàng đã được tạo thành công!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Có lỗi xảy ra khi tạo đơn hàng: ' . $e->getMessage());
        }
    }
    public function edit(Order $order)
{
    return view('orders.edit', compact('order'));
}

    public function show(Order $order)
    {
        $order->load('items.food');
        return view('orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:chờ xử lý,đang xử lý,hoàn thành,đã hủy'
        ]);

        $order->update(['status' => $request->status]);
        return back()->with('success', 'Trạng thái đơn hàng đã được cập nhật!');
    }

    public function updatePaymentStatus(Request $request, Order $order)
    {
        $request->validate([
            'payment_status' => 'required|in:pending,completed'
        ]);

        $order->update(['payment_status' => $request->payment_status]);
        return back()->with('success', 'Trạng thái thanh toán đã được cập nhật!');
    }
}



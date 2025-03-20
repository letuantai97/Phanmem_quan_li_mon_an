@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-6">
            <h1>Chi Tiết Đơn Hàng #{{ $order->id }}</h1>
        </div>
        <div class="col-md-6 text-end">
            <a href="{{ route('orders.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Quay lại
            </a>
        </div>
    </div>
    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Thông tin đơn hàng</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Khách hàng:</strong> {{ $order->customer_name }}
                        </div>
                        <div class="col-md-6">
                            <strong>Điện thoại:</strong> {{ $order->customer_phone }}
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Email:</strong> {{ $order->customer_email ?? 'Không có' }}
                        </div>
                        <div class="col-md-6">
                            <strong>Trạng thái:</strong> {!! $order->status_badge !!}
                        </div>
                        <div class="col-md-6">
                            <strong>Thanh toán:</strong>
                            <span class="status-badge {{ $order->payment_status === 'đã thanh toán' ? 'success' : ($order->payment_status === 'đã hoàn tiền' ? 'info' : 'warning') }} d-inline-flex align-items-center">
                                <i class="fas {{ $order->payment_status === 'đã thanh toán' ? 'fa-check-circle' : ($order->payment_status === 'đã hoàn tiền' ? 'fa-undo' : 'fa-clock') }} me-2"></i>
                                {{ ucfirst($order->payment_status) }}
                            </span>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Loại đơn hàng:</strong>
                            <span class="status-badge {{ $order->order_type === 'dine-in' ? 'success' : 'info' }} d-inline-flex align-items-center">
                                <i class="fas {{ $order->order_type === 'dine-in' ? 'fa-utensils' : 'fa-shopping-cart' }} me-2"></i>
                                {{ $order->order_type === 'dine-in' ? 'Tại chỗ' : 'Online' }}
                            </span>
                        </div>
                    </div>
                    <div class="mb-3">
                        <strong>Địa chỉ:</strong><br>
                        {{ $order->address }}
                    </div>
                    <div class="mb-3">
                        <strong>Ghi chú:</strong><br>
                        {{ $order->note ?? 'Không có' }}
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Chi tiết món ăn</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Món ăn</th>
                                    <th>Giá</th>
                                    <th>Số lượng</th>
                                    <th class="text-end">Thành tiền</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->items as $item)
                                    <tr>
                                        <td>{{ $item->food->name }}</td>
                                        <td>{{ number_format($item->price, 0, ',', '.') }}đ</td>
                                        <td>{{ $item->quantity }}</td>
                                        <td class="text-end">
                                            {{ number_format($item->price * $item->quantity, 0, ',', '.') }}đ
                                        </td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <td colspan="3" class="text-end"><strong>Tổng cộng:</strong></td>
                                    <td class="text-end">
                                        <strong>{{ number_format($order->total_amount, 0, ',', '.') }}đ</strong>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Cập nhật trạng thái</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('orders.update-status', $order) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <div class="mb-3">
                            <select name="status" class="form-control">
                                <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>
                                    Chờ xử lý
                                </option>
                                <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>
                                    Đang xử lý
                                </option>
                                <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>
                                    Hoàn thành
                                </option>
                                <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>
                                    Đã hủy
                                </option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-save"></i> Cập nhật trạng thái
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

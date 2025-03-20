@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="card">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h2 class="mb-0">Chỉnh sửa đơn hàng #{{ $order->id }}</h2>
            <a href="{{ route('orders.index') }}" class="btn btn-light">Quay lại</a>
        </div>
        <div class="card-body">
            <form action="{{ route('orders.update', $order->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">
                    <!-- Thông tin khách hàng -->
                    <div class="col-md-6">
                        <h4 class="mb-3">Thông tin khách hàng</h4>
                        <div class="mb-3">
                            <label for="customer_name" class="form-label">Tên khách hàng</label>
                            <input type="text" class="form-control @error('customer_name') is-invalid @enderror"
                                   id="customer_name" name="customer_name" value="{{ old('customer_name', $order->customer_name) }}">
                            @error('customer_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="customer_phone" class="form-label">Số điện thoại</label>
                            <input type="text" class="form-control @error('customer_phone') is-invalid @enderror"
                                   id="customer_phone" name="customer_phone" value="{{ old('customer_phone', $order->customer_phone) }}">
                            @error('customer_phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="customer_email" class="form-label">Email</label>
                            <input type="email" class="form-control @error('customer_email') is-invalid @enderror"
                                   id="customer_email" name="customer_email" value="{{ old('customer_email', $order->customer_email) }}">
                            @error('customer_email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="address" class="form-label">Địa chỉ</label>
                            <textarea class="form-control @error('address') is-invalid @enderror"
                                      id="address" name="address" rows="2">{{ old('address', $order->address) }}</textarea>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Trạng thái và tổng quan -->
                    <div class="col-md-6">
                        <h4 class="mb-3">Trạng thái đơn hàng</h4>
                        <div class="mb-3">
                            <label for="status" class="form-label">Trạng thái</label>
                            <select class="form-select @error('status') is-invalid @enderror" id="status" name="status">
                                <option value="chờ xử lý" {{ old('status', $order->status) === 'chờ xử lý' ? 'selected' : '' }}>Chờ xử lý</option>
                                <option value="đang xử lý" {{ old('status', $order->status) === 'đang xử lý' ? 'selected' : '' }}>Đang xử lý</option>
                                <option value="hoàn thành" {{ old('status', $order->status) === 'hoàn thành' ? 'selected' : '' }}>Hoàn thành</option>
                                <option value="đã hủy" {{ old('status', $order->status) === 'đã hủy' ? 'selected' : '' }}>Đã hủy</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="order_type" class="form-label">Loại đơn hàng</label>
                            <select class="form-select @error('order_type') is-invalid @enderror" id="order_type" name="order_type">
                                <option value="dine-in" {{ old('order_type', $order->order_type) === 'dine-in' ? 'selected' : '' }}>Tại chỗ</option>
                                <option value="online" {{ old('order_type', $order->order_type) === 'online' ? 'selected' : '' }}>Trực tuyến</option>
                            </select>
                            @error('order_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="payment_status" class="form-label">Trạng thái thanh toán</label>
                            <select class="form-select @error('payment_status') is-invalid @enderror" id="payment_status" name="payment_status">
                                <option value="pending" {{ old('payment_status', $order->payment_status) === 'pending' ? 'selected' : '' }}>Chưa thanh toán</option>
                                <option value="completed" {{ old('payment_status', $order->payment_status) === 'completed' ? 'selected' : '' }}>Đã thanh toán</option>
                            </select>
                            @error('payment_status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="card mt-4">
                            <div class="card-body bg-light">
                                <h5 class="card-title">Tổng quan đơn hàng</h5>
                                <p class="mb-1">Số lượng món: <strong>{{ $order->items->sum('quantity') }}</strong></p>
                                <p class="mb-1">Tổng tiền: <strong>{{ number_format($order->total_amount) }}đ</strong></p>
                                <p class="mb-0">Ngày tạo: <strong>{{ $order->created_at->format('d/m/Y H:i') }}</strong></p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Danh sách món ăn -->
                <div class="mt-4">
                    <h4>Danh sách món ăn</h4>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>Món ăn</th>
                                    <th>Đơn giá</th>
                                    <th>Số lượng</th>
                                    <th>Thành tiền</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->items as $item)
                                <tr>
                                    <td>{{ $item->food->name }}</td>
                                    <td>{{ number_format($item->price) }}đ</td>
                                    <td>{{ $item->quantity }}</td>
                                    <td>{{ number_format($item->subtotal) }}đ</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="table-light">
                                <tr>
                                    <td colspan="3" class="text-end"><strong>Tổng cộng:</strong></td>
                                    <td><strong>{{ number_format($order->total_amount) }}đ</strong></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

                <div class="mt-4 d-flex justify-content-between">
                    <a href="{{ route('orders.index') }}" class="btn btn-secondary">Hủy</a>
                    <button type="submit" class="btn btn-primary">Cập nhật đơn hàng</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-6">
            <h1>Quản Lý Đơn Hàng</h1>
        </div>
        <div class="col-md-6 text-end">
            <a href="{{ route('orders.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Tạo Đơn Hàng Mới
            </a>
        </div>
    </div>

    <!-- Search and Filter Form -->
    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('orders.index') }}" method="GET" class="row g-3">
                <div class="col-md-4">
                    <input type="text" name="search" class="form-control"
                           placeholder="Tìm theo tên/SĐT khách hàng..."
                           value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <select name="status" class="form-control">
                        <option value="">Tất cả trạng thái</option>
                        <option value="chờ xử lý" {{ request('status') == 'chờ xử lý' ? 'selected' : '' }}>
                            Chờ xử lý
                        </option>
                        <option value="đang xử lý" {{ request('status') == 'đang xử lý' ? 'selected' : '' }}>
                            Đang xử lý
                        </option>
                        <option value="hoàn thành" {{ request('status') == 'hoàn thành' ? 'selected' : '' }}>
                            Hoàn thành
                        </option>
                        <option value="đã hủy" {{ request('status') == 'đã hủy' ? 'selected' : '' }}>
                            Đã hủy
                        </option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-search"></i> Tìm kiếm
                    </button>
                </div>
            </form>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Mã ĐH</th>
                            <th>Khách Hàng</th>
                            <th>Tổng Tiền</th>
                            <th>Loại ĐH</th>
                            <th>Trạng Thái</th>
                            <th>Thanh Toán</th>
                            <th>Thời Gian</th>
                            <th>Thao Tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                        <tr>
                            <td>#{{ $order->id }}</td>
                            <td>{{ $order->customer_name }}</td>
                            <td>{{ number_format($order->total_amount) }}đ</td>
                            <td>
                                <span class="status-badge {{ $order->order_type === 'dine-in' ? 'success' : 'info' }} d-inline-flex align-items-center">
                                    <i class="fas {{ $order->order_type === 'dine-in' ? 'fa-utensils' : 'fa-shopping-cart' }} me-2"></i>
                                    {{ $order->order_type === 'dine-in' ? 'Tại chỗ' : 'Online' }}
                                </span>
                            </td>
                            <td>
                                <div class="dropdown">
                                    <button class="btn btn-sm dropdown-toggle {{ match($order->status) {
                                        'hoàn thành' => 'btn-success',
                                        'đang xử lý' => 'btn-info',
                                        'chờ xử lý' => 'btn-warning',
                                        'đã hủy' => 'btn-danger',
                                        default => 'btn-secondary'
                                    } }}" type="button" data-bs-toggle="dropdown">
                                        {{ $order->status }}
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <form action="{{ route('orders.update-status', ['order' => $order->id]) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="chờ xử lý">
                                                <button type="submit" class="dropdown-item">Chờ xử lý</button>
                                            </form>
                                        </li>
                                        <li>
                                            <form action="{{ route('orders.update-status', ['order' => $order->id]) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="đang xử lý">
                                                <button type="submit" class="dropdown-item">Đang xử lý</button>
                                            </form>
                                        </li>
                                        <li>
                                            <form action="{{ route('orders.update-status', ['order' => $order->id]) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="hoàn thành">
                                                <button type="submit" class="dropdown-item">Hoàn thành</button>
                                            </form>
                                        </li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li>
                                            <form action="{{ route('orders.update-status', ['order' => $order->id]) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="đã hủy">
                                                <button type="submit" class="dropdown-item text-danger">Đã hủy</button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                            <td>
                                @if($order->status === 'hoàn thành')
                                    <div class="dropdown">
                                        <button class="btn btn-sm dropdown-toggle {{ $order->payment_status === 'completed' ? 'btn-success' : 'btn-warning' }}" 
                                                type="button" data-bs-toggle="dropdown">
                                            {{ $order->payment_status === 'completed' ? 'Đã thanh toán' : 'Chưa thanh toán' }}
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li>
                                                <form action="{{ route('orders.update-payment-status', $order) }}" method="POST">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="hidden" name="payment_status" 
                                                           value="{{ $order->payment_status === 'completed' ? 'pending' : 'completed' }}">
                                                    <button type="submit" class="dropdown-item">
                                                        {{ $order->payment_status === 'completed' ? 'Đánh dấu chưa thanh toán' : 'Xác nhận đã thanh toán' }}
                                                    </button>
                                                </form>
                                            </li>
                                        </ul>
                                    </div>
                                @else
                                    {!! $order->payment_status_badge !!}
                                @endif
                            </td>
                            <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('orders.show', $order->id) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('orders.edit', $order->id) }}" class="btn btn-sm btn-outline-secondary">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center">Không có đơn hàng nào</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
                
                <div class="d-flex justify-content-end">
                    {{ $orders->links() }}
                </div>
            </div>

            <div class="d-flex justify-content-center mt-3">
                {{ $orders->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

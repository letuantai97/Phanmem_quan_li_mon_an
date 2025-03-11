@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h1 class="display-5 fw-bold text-primary">Báo Cáo Doanh Thu</h1>
                    <p class="text-muted">{{ now()->format('l, d/m/Y') }}</p>
                </div>
            </div>
        </div>
    </div>
    <!-- Filter Section -->
    <div class="card mb-4 shadow-sm">
        <div class="card-body">
            <form action="{{ route('revenue.report') }}" method="GET" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Từ Ngày</label>
                    <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Đến Ngày</label>
                    <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Trạng Thái Đơn Hàng</label>
                    <select name="status" class="form-select">
                        <option value="hoàn thành" {{ request('status') == 'hoàn thành' ? 'selected' : '' }}>Hoàn thành</option>
                        <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>Tất cả</option>
                    </select>
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-search me-2"></i>Xem Báo Cáo
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card shadow-sm h-100 border-primary border-start border-4">
                <div class="card-body">
                    <h6 class="text-muted mb-2">Tổng Doanh Thu</h6>
                    <h2 class="mb-0">{{ number_format($totalRevenue ?? 0) }}đ</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm h-100 border-success border-start border-4">
                <div class="card-body">
                    <h6 class="text-muted mb-2">Số Đơn Hàng</h6>
                    <h2 class="mb-0">{{ $totalOrders ?? 0 }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm h-100 border-info border-start border-4">
                <div class="card-body">
                    <h6 class="text-muted mb-2">Trung Bình/Đơn</h6>
                    <h2 class="mb-0">{{ $totalOrders > 0 ? number_format($totalRevenue / $totalOrders) : 0 }}đ</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm h-100 border-warning border-start border-4">
                <div class="card-body">
                    <h6 class="text-muted mb-2">Món Bán Chạy Nhất</h6>
                    <h2 class="mb-0">{{ $topSellingItem ?? 'N/A' }}</h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Detailed Report Table -->
    <div class="card shadow-sm">
        <div class="card-body">
            <h5 class="card-title mb-4">Chi Tiết Doanh Thu</h5>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Mã ĐH</th>
                            <th>Thời Gian</th>
                            <th>Khách Hàng</th>
                            <th>Trạng Thái</th>
                            <th>Tổng Tiền</th>
                            <th>Thao Tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders ?? [] as $order)
                        <tr>
                            <td>#{{ $order->id }}</td>
                            <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                            <td>{{ $order->customer_name }}</td>
                            <td>
                                <span class="badge {{ match($order->status) {
                                    'hoàn thành' => 'bg-success',
                                    'đang xử lý' => 'bg-info',
                                    'chờ xử lý' => 'bg-warning',
                                    'đã hủy' => 'bg-danger',
                                    default => 'bg-secondary'
                                } }}">
                                    {{ $order->status }}
                                </span>
                            </td>
                            <td>{{ number_format($order->total_amount) }}đ</td>
                            <td>
                                <a href="{{ route('orders.show', $order->id) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center">Không có dữ liệu</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>

                @if($orders ?? null)
                    <div class="d-flex justify-content-end">
                        {{ $orders->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">Báo Cáo Doanh Thu</h1>
        <div class="btn-group">
            <a href="{{ route('revenue.index', ['timeframe' => 'today']) }}" class="btn {{ $timeframe == 'today' ? 'btn-primary' : 'btn-outline-primary' }}">Hôm Nay</a>
            <a href="{{ route('revenue.index', ['timeframe' => 'this_month']) }}" class="btn {{ $timeframe == 'this_month' ? 'btn-primary' : 'btn-outline-primary' }}">Tháng Này</a>
            <a href="{{ route('revenue.index', ['timeframe' => 'this_year']) }}" class="btn {{ $timeframe == 'this_year' ? 'btn-primary' : 'btn-outline-primary' }}">Năm Nay</a>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-body">
                    <h6 class="card-subtitle mb-2 text-muted">Khoảng Thời Gian</h6>
                    <h4 class="card-title mb-0">
                        @switch($timeframe)
                            @case('today')
                                {{ \Carbon\Carbon::now()->format('d/m/Y') }}
                                @break
                            @case('this_month')
                                Tháng {{ \Carbon\Carbon::now()->format('m/Y') }}
                                @break
                            @case('this_year')
                                Năm {{ \Carbon\Carbon::now()->format('Y') }}
                                @break
                        @endswitch
                    </h4>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-body">
                    <h6 class="card-subtitle mb-2 text-muted">Tổng Doanh Thu</h6>
                    <h4 class="card-title mb-0 text-primary">{{ number_format($totalRevenue, 0, ',', '.') }} ₫</h4>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">Chi Tiết Đơn Hàng</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Mã Đơn</th>
                            <th>Thời Gian</th>
                            <th>Khách Hàng</th>
                            <th>Sản Phẩm</th>
                            <th class="text-center">Số Lượng</th>
                            <th class="text-end">Đơn Giá</th>
                            <th class="text-end">Thành Tiền</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                            @foreach($order->products as $product)
                                <tr>
                                    <td>{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</td>
                                    <td>{{ \Carbon\Carbon::parse($order->created_at)->format('H:i d/m/Y') }}</td>
                                    <td>{{ $order->customer_name }}</td>
                                    <td>{{ $product->name }}</td>
                                    <td class="text-center">{{ $product->pivot->quantity }}</td>
                                    <td class="text-end">{{ number_format($product->price, 0, ',', '.') }} ₫</td>
                                    <td class="text-end">{{ number_format($product->pivot->quantity * $product->price, 0, ',', '.') }} ₫</td>
                                </tr>
                            @endforeach
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4">Không có dữ liệu</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

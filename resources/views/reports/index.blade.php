@extends('layouts.app')
@section('content')
<div class="container-fluid py-4">
    <!-- Bộ lọc thời gian -->
    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('reports.index') }}" method="GET" class="row g-3 align-items-center">
                <div class="col-auto">
                    <select name="date_range" class="form-select" onchange="this.form.submit()">
                        <option value="today" {{ request('date_range') == 'today' ? 'selected' : '' }}>Hôm nay</option>
                        <option value="week" {{ request('date_range') == 'week' ? 'selected' : '' }}>Tuần này</option>
                        <option value="month" {{ request('date_range') == 'month' ? 'selected' : '' }}>Tháng này</option>
                        <option value="custom" {{ request('date_range') == 'custom' ? 'selected' : '' }}>Tùy chọn</option>
                    </select>
                </div>
                <div class="col-auto custom-date {{ request('date_range') == 'custom' ? '' : 'd-none' }}">
                    <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
                </div>
                <div class="col-auto custom-date {{ request('date_range') == 'custom' ? '' : 'd-none' }}">
                    <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
                </div>
            </form>
        </div>
    </div>

    <!-- Tổng quan -->
    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="card h-100">
                <div class="card-body">
                    <h6 class="text-muted mb-2">Tổng Đơn Hàng</h6>
                    <h2 class="mb-0">{{ $overview['total_orders'] }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100">
                <div class="card-body">
                    <h6 class="text-muted mb-2">Tổng Doanh Thu</h6>
                    <h2 class="mb-0">{{ number_format($overview['total_revenue']) }}đ</h2>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100">
                <div class="card-body">
                    <h6 class="text-muted mb-2">Giá Trị Đơn Trung Bình</h6>
                    <h2 class="mb-0">{{ number_format($overview['average_order_value']) }}đ</h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Biểu đồ doanh thu -->
    <div class="row g-4 mb-4">
        <!-- Biểu đồ cột theo thời gian -->
        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title">Biểu Đồ Doanh Thu Theo Thời Gian</h5>
                    <canvas id="timeRevenueChart"></canvas>
                </div>
            </div>
        </div>
        <!-- Biểu đồ doanh thu tổng quan -->
        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title">Tổng Quan Doanh Thu</h5>
                    <canvas id="overviewRevenueChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Top món ăn bán chạy -->
        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title">Top 5 Món Ăn Bán Chạy</h5>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Tên món</th>
                                    <th class="text-center">Số lượng</th>
                                    <th class="text-end">Doanh thu</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($topFoods as $food)
                                <tr>
                                    <td>{{ $food->name }}</td>
                                    <td class="text-center">{{ number_format($food->total_quantity) }}</td>
                                    <td class="text-end">{{ number_format($food->total_revenue) }}đ</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-center mt-3">
                        <nav class="pagination-container" aria-label="Page navigation">
                            <div class="pagination pagination-sm justify-content-center m-0">
                            @if ($topFoods instanceof \Illuminate\Pagination\LengthAwarePaginator)
    {{ $topFoods->links('pagination::bootstrap-4') }}
@endif
                            </div>
                        </nav>
                    </div>
                </div>
            </div>
        </div>

        <!-- Thống kê theo danh mục -->
        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title">Thống Kê Theo Danh Mục</h5>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Danh mục</th>
                                    <th class="text-center">Số đơn</th>
                                    <th class="text-center">Số món</th>
                                    <th class="text-end">Doanh thu</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($categoryStats as $category)
                                <tr>
                                    <td>{{ $category->name }}</td>
                                    <td class="text-center">{{ number_format($category->total_orders) }}</td>
                                    <td class="text-center">{{ number_format($category->total_items) }}</td>
                                    <td class="text-end">{{ number_format($category->total_revenue) }}đ</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Khởi tạo biểu đồ doanh thu theo thời gian
    const revenueData = @json($revenueStats);
    const timeCtx = document.getElementById('timeRevenueChart').getContext('2d');
    new Chart(timeCtx, {
        type: 'bar',
        data: {
            labels: revenueData.map(item => item.date),
            datasets: [{
                label: 'Doanh thu',
                data: revenueData.map(item => item.total_revenue),
                backgroundColor: '#3b82f6',
                borderRadius: 5
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'top' },
                title: { display: true, text: 'Doanh Thu Theo Thời Gian' },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            let label = context.dataset.label || '';
                            if (label) {
                                label += ': ';
                            }
                            label += new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' })
                                .format(context.parsed.y);
                            return label;
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' })
                                .format(value);
                        }
                    }
                }
            }
        }
    });

    // Khởi tạo biểu đồ tổng quan doanh thu
    const overviewCtx = document.getElementById('overviewRevenueChart').getContext('2d');
    // Xử lý dữ liệu theo khung giờ
    const timeRanges = ['6-9h', '9-12h', '12-15h', '15-18h', '18-21h', '21-24h'];
    @php
        $ordersByTime = array_fill(0, 6, 0);
        $itemsByTime = array_fill(0, 6, 0);
        $revenueByTime = array_fill(0, 6, 0);

        if(isset($overview['hourly_stats'])) {
            foreach($overview['hourly_stats']['orders'] ?? [] as $stat) {
                if($stat->hour >= 6 && $stat->hour < 24) {
                    $timeIndex = floor(($stat->hour - 6) / 3);
                    if ($timeIndex >= 0 && $timeIndex < 6) {
                        $ordersByTime[$timeIndex] += $stat->count;
                    }
                }
            }

            foreach($overview['hourly_stats']['items'] ?? [] as $stat) {
                if($stat->hour >= 6 && $stat->hour < 24) {
                    $timeIndex = floor(($stat->hour - 6) / 3);
                    if ($timeIndex >= 0 && $timeIndex < 6) {
                        $itemsByTime[$timeIndex] += $stat->total_items;
                    }
                }
            }

            foreach($overview['hourly_stats']['revenue'] ?? [] as $stat) {
                if($stat->hour >= 6 && $stat->hour < 24) {
                    $timeIndex = floor(($stat->hour - 6) / 3);
                    if ($timeIndex >= 0 && $timeIndex < 6) {
                        $revenueByTime[$timeIndex] += $stat->total_revenue;
                    }
                }
            }
        }
    @endphp
    const ordersByTime = @json($ordersByTime);
    const itemsByTime = @json($itemsByTime);
    const revenueByTime = @json($revenueByTime);
    const periodData = {
        labels: timeRanges,
        datasets: [
            {
                label: 'Số đơn hàng',
                data: ordersByTime,
                backgroundColor: '#ef4444',
                borderRadius: 5,
                yAxisID: 'orders',
                order: 2
            },
            {
                label: 'Số món ăn',
                data: itemsByTime,
                backgroundColor: '#f59e0b',
                borderRadius: 5,
                yAxisID: 'orders',
                order: 3
            },
            {
                label: 'Doanh thu',
                data: revenueByTime,
                backgroundColor: '#10b981',
                borderRadius: 5,
                yAxisID: 'revenue',
                order: 1
            }
        ]
    };

    new Chart(overviewCtx, {
        type: 'bar',
        data: periodData,
        options: {
            responsive: true,
            interaction: {
                mode: 'index',
                intersect: false,
            },
            plugins: {
                legend: { display: true, position: 'top' },
                title: { display: true, text: 'Thống kê theo khung giờ' }
            },
            scales: {
                orders: {
                    type: 'linear',
                    display: true,
                    position: 'left',
                    grid: {
                        drawOnChartArea: false
                    },
                    ticks: {
                        beginAtZero: true,
                        stepSize: 1,
                        precision: 0
                    },
                    title: {
                        display: true,
                        text: 'Số lượng'
                    }
                },
                revenue: {
                    type: 'linear',
                    display: true,
                    position: 'right',
                    grid: {
                        drawOnChartArea: false
                    },
                    ticks: {
                        callback: function(value) {
                            return new Intl.NumberFormat('vi-VN', {
                                style: 'currency',
                                currency: 'VND',
                                notation: 'compact',
                                compactDisplay: 'short'
                            }).format(value);
                        },
                        beginAtZero: true
                    },
                    title: {
                        display: true,
                        text: 'Doanh thu'
                    }
                }
            }
        }
    });

    // Xử lý hiển thị form tùy chọn ngày
    document.querySelector('select[name="date_range"]').addEventListener('change', function() {
        const customDateInputs = document.querySelectorAll('.custom-date');
        if (this.value === 'custom') {
            customDateInputs.forEach(input => input.classList.remove('d-none'));
        } else {
            customDateInputs.forEach(input => input.classList.add('d-none'));
        }
    });
</script>
@endpush
@endsection

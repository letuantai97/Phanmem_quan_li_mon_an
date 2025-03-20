@extends('layouts.app')
@section('content')
<div class="container-fluid py-4">
    <!-- Bộ lọc thời gian -->
    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('reports.index') }}" method="GET" class="row g-3 align-items-center">
                <div class="col-auto">
                    <select name="date_range" class="form-select" id="dateRangeSelect">
                        <option value="today" {{ request('date_range') == 'today' ? 'selected' : '' }}>Hôm nay</option>
                        <option value="week" {{ request('date_range') == 'week' ? 'selected' : '' }}>Tuần này</option>
                        <option value="month" {{ request('date_range') == 'month' ? 'selected' : '' }}>Tháng này</option>
                        <option value="specific_month" {{ request('date_range') == 'specific_month' ? 'selected' : '' }}>Chọn tháng</option>
                        <option value="custom" {{ request('date_range') == 'custom' ? 'selected' : '' }}>Tùy chọn</option>
                    </select>
                </div>
                <div class="col-auto specific-month {{ request('date_range') == 'specific_month' ? '' : 'd-none' }}">
                    <select name="selected_month" class="form-select">
                        @for ($i = 1; $i <= 12; $i++)
                            <option value="{{ $i }}" {{ request('selected_month') == $i ? 'selected' : '' }}>Tháng {{ $i }}</option>
                        @endfor
                    </select>
                </div>
                <div class="col-auto custom-date {{ request('date_range') == 'custom' ? '' : 'd-none' }}">
                    <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
                </div>
                <div class="col-auto custom-date {{ request('date_range') == 'custom' ? '' : 'd-none' }}">
                    <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-primary">Xem báo cáo</button>
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
        <div class="col-md-4">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title">Biểu Đồ Doanh Thu Theo Thời Gian</h5>
                    <canvas id="timeRevenueChart"></canvas>
                </div>
            </div>
        </div>
        <!-- Biểu đồ doanh thu tổng quan -->
        <div class="col-md-4">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title">Tổng Quan Doanh Thu</h5>
                    <canvas id="overviewRevenueChart"></canvas>
                </div>
            </div>
        </div>
        <!-- Biểu đồ món ăn bán chạy -->
        <div class="col-md-4">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title">Top Món Ăn Bán Chạy</h5>
                    <canvas id="topFoodsChart"></canvas>
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
    const dateRangeSelect = document.querySelector('#dateRangeSelect');
    const selectedMonth = document.querySelector('select[name="selected_month"]');

    const chartTitle = dateRangeSelect.value === 'specific_month' ?
        'Doanh Thu Tháng ' + selectedMonth.options[selectedMonth.selectedIndex].text :
        'Doanh Thu Theo Thời Gian';

    new Chart(timeCtx, {
        type: 'bar',
        data: {
            labels: revenueData.map(item => {
                const date = new Date(item.date);
                return date.getDate() + '/' + (date.getMonth() + 1);
            }),
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
                title: {
                    display: true,
                    text: chartTitle
                },
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
    const ordersByTime = @json($overview['hourly_stats']['orders']->groupBy('hour')->map(function($group) {
        return $group->sum('count');
    })->toArray());
    const itemsByTime = @json($overview['hourly_stats']['items']->groupBy('hour')->map(function($group) {
        return $group->sum('total_items');
    })->toArray());
    const revenueByTime = @json($overview['hourly_stats']['revenue']->groupBy('hour')->map(function($group) {
        return $group->sum('total_revenue');
    })->toArray());

    // Chuyển đổi dữ liệu theo khung giờ
    const processTimeData = (data) => {
        const result = Array(6).fill(0);
        Object.entries(data).forEach(([hour, value]) => {
            const timeIndex = Math.floor((parseInt(hour) - 6) / 3);
            if (timeIndex >= 0 && timeIndex < 6) {
                result[timeIndex] += value;
            }
        });
        return result;
    };

    const periodData = {
        labels: timeRanges,
        datasets: [
            {
                label: 'Số đơn hàng',
                data: processTimeData(ordersByTime),
                backgroundColor: '#ef4444',
                borderRadius: 5,
                yAxisID: 'orders',
                order: 2
            },
            {
                label: 'Số món ăn',
                data: processTimeData(itemsByTime),
                backgroundColor: '#f59e0b',
                borderRadius: 5,
                yAxisID: 'orders',
                order: 3
            },
            {
                label: 'Doanh thu',
                data: processTimeData(revenueByTime),
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
    document.querySelector('#dateRangeSelect').addEventListener('change', function() {
        const customDateInputs = document.querySelectorAll('.custom-date');
        const specificMonthInput = document.querySelector('.specific-month');

        customDateInputs.forEach(input => input.classList.add('d-none'));
        specificMonthInput.classList.add('d-none');

        if (this.value === 'custom') {
            customDateInputs.forEach(input => input.classList.remove('d-none'));
        } else if (this.value === 'specific_month') {
            specificMonthInput.classList.remove('d-none');
        }

        if (this.value !== 'custom') {
            this.closest('form').submit();
        }
    });

    // Add event listener for month selection
    document.querySelector('select[name="selected_month"]').addEventListener('change', function() {
        this.closest('form').submit();
    });

    // Khởi tạo biểu đồ món ăn bán chạy
    const topFoodsCtx = document.getElementById('topFoodsChart').getContext('2d');
    const topFoodsData = @json($topFoods);
    const dateRangeText = (() => {
        switch (dateRangeSelect.value) {
            case 'today':
                return 'Hôm nay';
            case 'week':
                return 'Tuần này';
            case 'month':
                return 'Tháng này';
            case 'specific_month':
                return selectedMonth.options[selectedMonth.selectedIndex].text;
            case 'custom':
                const startDate = document.querySelector('input[name="start_date"]').value;
                const endDate = document.querySelector('input[name="end_date"]').value;
                return `${startDate} - ${endDate}`;
            default:
                return 'Tất cả thời gian';
        }
    })();

    new Chart(topFoodsCtx, {
        type: 'bar',
        data: {
            labels: topFoodsData.slice(0, 5).map(item => item.name),
            datasets: [{
                label: 'Số lượng bán ra',
                data: topFoodsData.slice(0, 5).map(item => item.total_quantity),
                backgroundColor: '#60a5fa',
                borderRadius: 5
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'top' },
                title: {
                    display: true,
                    text: `Top 5 Món Ăn Bán Chạy (${dateRangeText})`
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1,
                        precision: 0
                    }
                }
            }
        }
    });
</script>
@endpush
@endsection

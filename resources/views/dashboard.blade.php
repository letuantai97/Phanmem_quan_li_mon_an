@extends('layouts.app')
@section('content')
<div class="dashboard container-fluid py-4">
    <!-- Header Section -->
    <div class="header-section mb-4 animate__animated animate__fadeIn">
        <div class="glass-card">
            <h1 class="display-4 fw-bold text-gradient mb-2">Phần Mềm Quản Lý Món Ăn</h1>
            <p class="text-muted fs-5 mb-0">{{ now()->format('l, d/m/Y') }}</p>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row g-4 mb-4">
        @foreach ([
            ['label' => 'Tổng Món Ăn', 'value' => $totalFoods ?? 0, 'icon' => 'fas fa-utensils', 'trend' => $foodsTrend ?? '+0%', 'link' => 'foods.index', 'gradient' => 'primary-gradient'],
            ['label' => 'Danh Mục', 'value' => $totalCategories ?? 0, 'icon' => 'fas fa-list', 'trend' => $categoriesTrend ?? '+0%', 'link' => 'categories.index', 'gradient' => 'success-gradient'],
            ['label' => 'Đơn Hàng', 'value' => $totalOrders ?? 0, 'icon' => 'fas fa-shopping-cart', 'trend' => $ordersTrend ?? '+0%', 'link' => 'orders.index', 'gradient' => 'warning-gradient'],
            ['label' => 'Doanh Thu', 'value' => number_format($totalRevenue ?? 0) . 'đ', 'icon' => 'fas fa-money-bill-wave', 'trend' => $revenueTrend ?? '+0%', 'link' => 'orders.index', 'params' => ['status' => 'hoàn thành'], 'gradient' => 'info-gradient'],
        ] as $index => $stat)
        <div class="col-md-3 animate__animated animate__fadeInUp" style="animation-delay: {{ 0.1 * $index }}s">
            <div class="stat-card {{ $stat['gradient'] }}">
                <div class="stat-icon">
                    <i class="{{ $stat['icon'] }} fa-2x"></i>
                </div>
                <div class="stat-info">
                    <h6 class="stat-label">{{ $stat['label'] }}</h6>
                    <h2 class="stat-value mb-0 text-gradient">{{ $stat['value'] }}</h2>
                </div>
                <div class="stat-trend text-gradient">{{ $stat['trend'] }} <i class="fas fa-arrow-up ms-1"></i></div>
                <a href="{{ route($stat['link'], $stat['params'] ?? []) }}" class="stretched-link"></a>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Quick Actions -->
    <div class="quick-actions-section mb-4">
        <div class="glass-card">
            <h5 class="section-title mb-4">Thao Tác Nhanh</h5>
            <div class="row g-4">
                @foreach ([
                    ['title' => 'Thêm Món Ăn', 'desc' => 'Tạo món ăn mới', 'icon' => 'fas fa-plus-circle', 'link' => 'foods.create', 'gradient' => 'primary-gradient'],
                    ['title' => 'Thêm Danh Mục', 'desc' => 'Quản lý phân loại', 'icon' => 'fas fa-folder-plus', 'link' => 'categories.create', 'gradient' => 'success-gradient'],
                    ['title' => 'Tạo Đơn Hàng', 'desc' => 'Đặt món mới', 'icon' => 'fas fa-file-invoice', 'link' => 'orders.create', 'gradient' => 'warning-gradient'],
                    ['title' => 'Xem Đơn Hàng', 'desc' => 'Quản lý đơn hàng', 'icon' => 'fas fa-list-alt', 'link' => 'orders.index', 'gradient' => 'info-gradient'],
                ] as $action)
                <div class="col-md-3 animate__animated animate__fadeInLeft" style="animation-delay: {{ 0.1 * $loop->iteration }}s">
                    <a href="{{ route($action['link']) }}" class="action-card">
                        <div class="action-icon {{ $action['gradient'] }}">
                            <i class="{{ $action['icon'] }} fa-2x"></i>
                        </div>
                        <h6 class="action-title">{{ $action['title'] }}</h6>
                        <p class="action-desc">{{ $action['desc'] }}</p>
                    </a>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Recent Orders -->
    <div class="recent-orders-section">
        <div class="glass-card">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="section-title mb-0">Đơn Hàng Gần Đây</h5>
                <a href="{{ route('orders.index') }}" class="btn-link">Xem tất cả <i class="fas fa-arrow-right ms-2"></i></a>
            </div>
            <div class="table-responsive">
                <table class="table custom-table">
                    <thead>
                        <tr>
                            <th>Mã ĐH</th>
                            <th>Khách hàng</th>
                            <th>Loại ĐH</th>
                            <th>Tổng tiền</th>
                            <th>Trạng thái đơn hàng</th>
                            <th>Thanh toán</th>
                            <th>Thời gian</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentOrders ?? [] as $order)
                        <tr class="animate__animated animate__fadeIn" style="animation-delay: {{ 0.1 * $loop->iteration }}s">
                            <td><span class="order-id">#{{ $order->id ?? '' }}</span></td>
                            <td>{{ $order->customer_name ?? 'N/A' }}</td>
                            <td>
                                <span class="status-badge {{ $order->order_type === 'dine-in' ? 'success' : 'info' }}">
                                    {{ $order->order_type === 'dine-in' ? 'Tại chỗ' : 'Online' }}
                                </span>
                            </td>
                            <td><span class="price-tag">{{ number_format($order->total_amount ?? 0) }}đ</span></td>
                            <td>
                                <span class="status-badge {{ match($order->status ?? '') {
                                    'chờ xử lý' => 'warning',
                                    'đang xử lý' => 'info',
                                    'hoàn thành' => 'success',
                                    'đã hủy' => 'danger',
                                    default => 'secondary'
                                } }} d-inline-flex align-items-center">
                                    <i class="fas {{ match($order->status ?? '') {
                                        'chờ xử lý' => 'fa-clock',
                                        'đang xử lý' => 'fa-spinner fa-spin',
                                        'hoàn thành' => 'fa-check-circle',
                                        'đã hủy' => 'fa-times-circle',
                                        default => 'fa-question-circle'
                                    } }} me-2"></i>
                                    {{ ucfirst($order->status ?? 'Không xác định') }}
                                </span>
                            </td>
                            <td>
                                <span class="status-badge {{ $order->payment_status === 'đã thanh toán' ? 'success' : ($order->payment_status === 'đã hoàn tiền' ? 'info' : 'warning') }} d-inline-flex align-items-center">
                                    <i class="fas {{ $order->payment_status === 'đã thanh toán' ? 'fa-check-circle' : ($order->payment_status === 'đã hoàn tiền' ? 'fa-undo' : 'fa-clock') }} me-2"></i>
                                    {{ ucfirst($order->payment_status) }}
                                </span>
                            </td>
                            <td>{{ optional($order->created_at)->format('d/m/Y H:i') ?? 'N/A' }}</td>
                            <td>
                                @if($order)
                                <div class="action-buttons">
                                    <a href="{{ route('orders.show', $order->id) }}" class="btn-icon" title="Xem chi tiết">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('orders.edit', $order->id) }}" class="btn-icon" title="Chỉnh sửa">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                </div>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-5">
                                <div class="empty-state">
                                    <i class="fas fa-inbox fa-3x mb-3"></i>
                                    <p class="text-muted">Không có đơn hàng nào</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
@section('styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
<style>
    :root {
        --primary: #E63946; /* Đỏ tươi */
        --primary-dark: #B31B1B; /* Đỏ đậm */
        --success: #2D936C; /* Xanh lá thực phẩm */
        --success-dark: #1B5E20; /* Xanh lá đậm */
        --warning: #FFB627; /* Vàng nghệ */
        --warning-dark: #F39C12; /* Cam quýt */
        --info: #457B9D; /* Xanh dương nhạt */
        --info-dark: #1D3557; /* Xanh dương đậm */
        --danger: #D62828; /* Đỏ cay */
        --danger-dark: #9B2226; /* Đỏ sẫm */
        --text-primary: #2F3E46; /* Xám đậm ấm */
        --text-secondary: #6B705C; /* Xám olive */
        --background: #FAF3E0; /* Kem nhạt */
        --card-bg: rgba(255, 253, 250, 0.95); /* Nền kem trong suốt */
        --border-color: rgba(222, 184, 135, 0.3); /* Màu viền nâu nhạt */
        --gradient-text-primary: linear-gradient(45deg, #E63946, #FF6B6B);
        --gradient-text-success: linear-gradient(45deg, #2D936C, #4CAF50);
        --gradient-text-warning: linear-gradient(45deg, #FFB627, #FFA000);
        --gradient-text-info: linear-gradient(45deg, #457B9D, #64B5F6);
        --gradient-start: #F4A261; /* Cam đào */
        --gradient-end: #E76F51; /* Cam đất */
        --card-shadow: 0 10px 20px rgba(97, 63, 41, 0.1); /* Bóng đổ nâu */
        --hover-transform: translateY(-5px); /* Hiệu ứng hover */
    }

    .dashboard {
        min-height: 100vh;
        background: linear-gradient(135deg, var(--gradient-start) 0%, var(--gradient-end) 100%);
        font-family: 'Roboto', sans-serif; /* Font Chữ */
        padding: 2rem;
        color: var(--text-primary);
        position: relative;
        overflow: hidden;
    }

    .glass-card {
        background: var(--card-bg);
        backdrop-filter: blur(10px);
        border-radius: 1.5rem;
        padding: 2.5rem;
        box-shadow: var(--card-shadow);
        border: 1px solid var(--border-color);
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
    }

    .table {
        border-collapse: separate;
        border-spacing: 0 0.5rem;
        font-family: 'Roboto', sans-serif;
    }

    .table th {
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
        color: white;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 1rem;
        font-size: 0.9rem;
        border: none;
    }

    .table th:first-child {
        border-top-left-radius: 0.5rem;
        border-bottom-left-radius: 0.5rem;
    }

    .table th:last-child {
        border-top-right-radius: 0.5rem;
        border-bottom-right-radius: 0.5rem;
    }

    .table tbody tr {
        background: var(--card-bg);
        transition: all 0.3s ease;
        border-radius: 0.5rem;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    }

    .table tbody tr:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        background: rgba(255, 255, 255, 0.98);
    }

    .table td {
        padding: 1rem;
        vertical-align: middle;
        border: none;
        color: var(--text-primary);
        font-size: 0.95rem;
    }

    .table tbody tr:nth-child(even) {
        background: rgba(255, 255, 255, 0.9);
    }

    .status-badge {
        padding: 0.5rem 1rem;
        border-radius: 1rem;
        color: white; /* Màu chữ trong badge */
    }

    .status-badge.success { background-color: var(--success); }
    .status-badge.warning { background-color: var(--warning); }
    .status-badge.info { background-color: var(--info); }
    .status-badge.danger { background-color: var(--danger); }
    .status-badge.secondary { background-color: var(--text-secondary); }

    .stat-value {
        font-weight: bold;
        color: var(--text-primary);
    }

    .text-gradient {
        color: var(--text-primary);
    }

    .primary-gradient .text-gradient {
        background: var(--gradient-text-primary);
    }

    .success-gradient .text-gradient {
        background: var(--gradient-text-success);
    }

    .warning-gradient .text-gradient {
        background: var(--gradient-text-warning);
    }

    .info-gradient .text-gradient {
        background: var(--gradient-text-info);
    }



    .stat-card {
        background: var(--card-bg);
        border-radius: 1rem;
        padding: 1.5rem;
        position: relative;
        transition: all 0.3s ease;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        height: 100%;
        border: 1px solid var(--border-color);
    }

    .stat-card:hover {
        transform: var(--hover-transform);
        box-shadow: var(--card-shadow);
    }

    .stat-card .stat-icon {
        margin-bottom: 1rem;
        font-size: 2rem;
        line-height: 1;
        color: var(--text-primary);
    }

    .stat-card .stat-info {
        flex-grow: 1;
    }

    .stat-card .stat-label {
        color: var(--text-secondary);
        font-size: 0.9rem;
        margin-bottom: 0.5rem;
    }

    .stat-card .stat-value {
        font-size: 1.8rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
        background: var(--gradient-text-primary);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .stat-card .stat-trend {
        font-size: 0.9rem;
        color: var(--success);
    }

    .primary-gradient .stat-value {
        background: var(--gradient-text-primary);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .success-gradient .stat-value {
        background: var(--gradient-text-success);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .warning-gradient .stat-value {
        background: var(--gradient-text-warning);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .info-gradient .stat-value {
        background: var(--gradient-text-info);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .btn-link {
        color: var(--primary);
        text-decoration: none;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .btn-link:hover {
        color: var(--primary-dark);
        transform: translateX(5px);
    }

    .btn-icon {
        color: var(--text-secondary);
        font-size: 1.1rem;
        margin: 0 0.3rem;
        transition: all 0.3s ease;
    }

    .btn-icon:hover {
        color: var(--primary);
        transform: scale(1.1);
    }

    .order-id {
        font-weight: 600;
        color: var(--primary);
    }

    .price-tag {
        font-weight: 600;
        color: var(--success);
    }

    .action-buttons {
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .empty-state {
        color: var(--text-secondary);
        padding: 2rem;
    }

    .empty-state i {
        color: var(--text-secondary);
        opacity: 0.5;
    }
    /* Các kiểu dáng khác không thay đổi */
</style>

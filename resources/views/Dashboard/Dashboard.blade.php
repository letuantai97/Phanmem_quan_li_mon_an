@extends('layouts.app')
@section('content')
<div class="container-fluid py-4">
    <!-- Header Section -->
    <div class="header-section mb-4 animate__animated animate__fadeIn">
        <div class="glass-card p-4 bg-white shadow-sm rounded">
            <h1 class="display-4 fw-bold text-primary mb-2">Tổng Quan Hệ Thống</h1>
            <p class="text-muted fs-5 mb-0">{{ now()->format('l, d/m/Y') }}</p>
        </div>
    </div>

    <!-- Stats Cards Row -->
    <div class="row g-4 mb-4">
        <!-- Tổng Số Món Ăn -->
        <div class="col-md-3 animate__animated animate__fadeInUp" style="animation-delay: 0.1s">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-primary bg-opacity-10 p-3 rounded-3 me-3">
                            <i class="fas fa-utensils text-primary fa-2x"></i>
                        </div>
                        <div>
                            <h6 class="text-muted mb-1">Tổng Số Món Ăn</h6>
                            <h3 class="mb-0">{{ $stats['total_foods'] }}</h3>
                        </div>
                    </div>
                    <a href="{{ route('foods.index') }}" class="btn btn-light btn-sm w-100">
                        Xem chi tiết <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Tổng Số Danh Mục -->
        <div class="col-md-3 animate__animated animate__fadeInUp" style="animation-delay: 0.2s">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-success bg-opacity-10 p-3 rounded-3 me-3">
                            <i class="fas fa-list text-success fa-2x"></i>
                        </div>
                        <div>
                            <h6 class="text-muted mb-1">Tổng Số Danh Mục</h6>
                            <h3 class="mb-0">{{ $stats['total_categories'] }}</h3>
                        </div>
                    </div>
                    <a href="{{ route('categories.index') }}" class="btn btn-light btn-sm w-100">
                        Xem chi tiết <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Giá Trung Bình -->
        <div class="col-md-3 animate__animated animate__fadeInUp" style="animation-delay: 0.3s">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-info bg-opacity-10 p-3 rounded-3 me-3">
                            <i class="fas fa-chart-line text-info fa-2x"></i>
                        </div>
                        <div>
                            <h6 class="text-muted mb-1">Giá Trung Bình</h6>
                            <h3 class="mb-0">{{ number_format($stats['price_stats']->avg_price, 0, ',', '.') }}đ</h3>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between text-muted small">
                        <span>Thấp nhất: {{ number_format($stats['price_stats']->min_price, 0, ',', '.') }}đ</span>
                        <span>Cao nhất: {{ number_format($stats['price_stats']->max_price, 0, ',', '.') }}đ</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Doanh Thu Hôm Nay -->
        <div class="col-md-3 animate__animated animate__fadeInUp" style="animation-delay: 0.4s">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-warning bg-opacity-10 p-3 rounded-3 me-3">
                            <i class="fas fa-money-bill-wave text-warning fa-2x"></i>
                        </div>
                        <div>
                            <h6 class="text-muted mb-1">Doanh Thu Hôm Nay</h6>
                            <h3 class="mb-0">{{ isset($stats['today_revenue']) ? number_format($stats['today_revenue'], 0, ',', '.') : '0' }}đ</h3>
                        </div>
                    </div>
                    <a href="{{ route('revenue.index', ['timeframe' => 'today']) }}" class="btn btn-light btn-sm w-100">
                        Xem chi tiết <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row g-4">
        <!-- Biểu đồ phân bố món ăn -->
        <div class="col-md-6 animate__animated animate__fadeInUp" style="animation-delay: 0.5s">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0">
                    <h5 class="card-title mb-0">Phân Bố Món Ăn Theo Danh Mục</h5>
                </div>
                <div class="card-body">
                    <canvas id="categoryChart" height="300"></canvas>
                </div>
            </div>
        </div>

        <!-- Món ăn mới nhất -->
        <div class="col-md-6 animate__animated animate__fadeInUp" style="animation-delay: 0.6s">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Món Ăn Mới Nhất</h5>
                    <a href="{{ route('foods.index') }}" class="btn btn-sm btn-primary">Xem tất cả</a>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        @forelse($stats['latest_foods'] as $food)
                            <div class="list-group-item border-0 py-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1">{{ $food->name }}</h6>
                                        <p class="mb-0 text-muted small">
                                            <span class="me-3"><i class="fas fa-tag me-1"></i>{{ $food->category->name }}</span>
                                            <span><i class="fas fa-clock me-1"></i>{{ $food->created_at->diffForHumans() }}</span>
                                        </p>
                                    </div>
                                    <h5 class="mb-0 text-primary">{{ number_format($food->price, 0, ',', '.') }}đ</h5>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-4 text-muted">
                                <i class="fas fa-inbox fa-2x mb-2"></i>
                                <p class="mb-0">Chưa có món ăn nào</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

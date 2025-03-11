@extends('layouts.app')

@section('content')
<!-- Wrapper chính cho toàn bộ trang -->
<div class="intro-wrapper">
    <!-- Hero Section: Phần đầu trang giới thiệu phần mềm -->
    <div class="hero-section">
        <div class="hero-content animate__animated animate__fadeIn">
            <!-- Tiêu đề chính -->
            <h1 class="hero-title">Phần Mềm Quản Lý Món Ăn</h1>
            <!-- Phụ đề mô tả ngắn gọn -->
            <p class="hero-subtitle">Giải pháp toàn diện giúp nhà hàng của bạn vận hành hiệu quả hơn</p>
            <!-- Nút hành động -->
            <div class="hero-actions">
                <a href="{{ route('foods.index') }}" class="btn btn-primary btn-lg">
                    <i class="fas fa-utensils me-2"></i>Xem Thực Đơn
                </a>
                <a href="{{ route('dashboard') }}" class="btn btn-outline-light btn-lg">
                    <i class="fas fa-chart-line me-2"></i>Bảng Điều Khiển
                </a>
            </div>
        </div>
    </div>
    <!-- Features Section: Các tính năng nổi bật -->
    <div class="features-section container py-5">
        <!-- Tiêu đề phần -->
        <h2 class="section-title text-center mb-5 animate__animated animate__fadeIn">Tính Năng Nổi Bật</h2>
        <div class="row g-4">
            <!-- Tính năng 1: Quản Lý Món Ăn -->
            <div class="col-md-3">
                <div class="feature-card animate__animated animate__fadeInUp" style="animation-delay: 0.1s">
                    <div class="feature-icon">
                        <i class="fas fa-utensils"></i>
                    </div>
                    <h3>Quản Lý Món Ăn</h3>
                    <p>Dễ dàng thêm, sửa, xóa và tổ chức các món ăn trong thực đơn của bạn.</p>
                </div>
            </div>
            <!-- Tính năng 2: Phân Loại -->
            <div class="col-md-3">
                <div class="feature-card animate__animated animate__fadeInUp" style="animation-delay: 0.2s">
                    <div class="feature-icon">
                        <i class="fas fa-tags"></i>
                    </div>
                    <h3>Phân Loại</h3>
                    <p>Sắp xếp món ăn theo danh mục giúp việc tìm kiếm trở nên dễ dàng.</p>
                </div>
            </div>
            <!-- Tính năng 3: Thống Kê -->
            <div class="col-md-3">
                <div class="feature-card animate__animated animate__fadeInUp" style="animation-delay: 0.3s">
                    <div class="feature-icon">
                        <i class="fas fa-chart-bar"></i>
                    </div>
                    <h3>Thống Kê</h3>
                    <p>Theo dõi doanh thu và phân tích xu hướng bán hàng.</p>
                </div>
            </div>
            <!-- Tính năng 4: Dễ Sử Dụng -->
            <div class="col-md-3">
                <div class="feature-card animate__animated animate__fadeInUp" style="animation-delay: 0.4s">
                    <div class="feature-icon">
                        <i class="fas fa-mobile-alt"></i>
                    </div>
                    <h3>Dễ Sử Dụng</h3>
                    <p>Giao diện thân thiện, tương thích với mọi thiết bị.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Section: Thống kê số liệu -->
    <div class="stats-section py-5">
        <div class="container">
            <!-- Tiêu đề phần -->
            <h2 class="section-title text-center mb-5 animate__animated animate__fadeIn">Số Liệu Ấn Tượng</h2>
            <div class="row g-4">
                <!-- Thống kê 1: Tổng Số Món Ăn -->
                <div class="col-md-4">
                    <div class="stat-card animate__animated animate__fadeInLeft">
                        <div class="stat-icon">
                            <i class="fas fa-utensils"></i>
                        </div>
                        <div class="stat-content">
                            <h3>{{ $totalFoods ?? '0' }}</h3>
                            <p>Tổng Số Món Ăn</p>
                        </div>
                    </div>
                </div>
                <!-- Thống kê 2: Danh Mục -->
                <div class="col-md-4">
                    <div class="stat-card animate__animated animate__fadeInUp">
                        <div class="stat-icon">
                            <i class="fas fa-list"></i>
                        </div>
                        <div class="stat-content">
                            <h3>{{ $totalCategories ?? '0' }}</h3>
                            <p>Danh Mục</p>
                        </div>
                    </div>
                </div>
                <!-- Thống kê 3: Món Ăn Mới -->
                <div class="col-md-4">
                    <div class="stat-card animate__animated animate__fadeInRight">
                        <div class="stat-icon">
                            <i class="fas fa-star"></i>
                        </div>
                        <div class="stat-content">
                            <h3>{{ $newFoods ?? '0' }}</h3>
                            <p>Món Ăn Mới</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions: Các hành động nhanh -->
    <div class="quick-actions-section container py-5">
        <!-- Tiêu đề phần -->
        <h2 class="section-title text-center mb-5 animate__animated animate__fadeIn">Bắt Đầu Dễ Dàng</h2>
        <div class="row g-4">
            <!-- Hành động 1: Thêm Món Ăn -->
            <div class="col-md-6">
                <div class="action-card animate__animated animate__fadeInLeft">
                    <div class="action-icon">
                        <i class="fas fa-plus-circle"></i>
                    </div>
                    <h3>Thêm Món Ăn Mới</h3>
                    <p>Bổ sung món ăn mới vào thực đơn của bạn chỉ với vài cú nhấp chuột.</p>
                    <a href="{{ route('foods.create') }}" class="btn btn-primary">Thêm Ngay</a>
                </div>
            </div>
            <!-- Hành động 2: Tạo Danh Mục -->
            <div class="col-md-6">
                <div class="action-card animate__animated animate__fadeInRight">
                    <div class="action-icon">
                        <i class="fas fa-folder-plus"></i>
                    </div>
                    <h3>Tạo Danh Mục</h3>
                    <p>Tổ chức món ăn theo danh mục để quản lý hiệu quả hơn.</p>
                    <a href="{{ route('categories.create') }}" class="btn btn-success">Tạo Mới</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    /* Biến CSS để tái sử dụng màu sắc */
    :root {
        --primary-color: #FF6B6B;    /* Màu chính (đỏ cam) */
        --secondary-color: #4ECDC4;  /* Màu phụ (xanh ngọc) */
        --accent-color: #FFE66D;     /* Màu nhấn (vàng) */
        --dark-color: #2C3E50;       /* Màu tối (xanh đậm) */
        --light-color: #F7F9FC;      /* Màu sáng (xám nhạt) */
    }

    /* Wrapper chính */
    .intro-wrapper {
        background-color: var(--light-color);
        font-family: 'Arial', sans-serif;
    }

    /* Hero Section */
    .hero-section {
        background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), url('/images/hero-bg.jpg');
        background-size: cover;
        background-position: center;
        color: white;
        padding: 150px 0;
        text-align: center;
    }

    .hero-title {
        font-size: 4rem; /* Kích thước chữ lớn cho tiêu đề */
        font-weight: bold;
        margin-bottom: 1.5rem;
    }

    .hero-subtitle {
        font-size: 1.75rem;
        margin-bottom: 2.5rem;
        opacity: 0.9; /* Độ mờ nhẹ để làm nổi bật tiêu đề */
    }

    .hero-actions .btn {
        margin: 0 15px;
        padding: 15px 35px;
        border-radius: 50px; /* Bo tròn nút */
        font-weight: 600;
        transition: transform 0.3s ease; /* Hiệu ứng khi hover */
    }

    .hero-actions .btn:hover {
        transform: scale(1.05); /* Phóng to nhẹ khi hover */
    }

    /* Features Section */
    .section-title {
        font-size: 2.5rem;
        color: var(--dark-color);
        font-weight: bold;
    }

    .feature-card {
        background: white;
        padding: 2.5rem;
        border-radius: 20px;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1); /* Đổ bóng nhẹ */
        text-align: center;
        transition: transform 0.3s ease;
    }

    .feature-card:hover {
        transform: translateY(-10px); /* Nâng thẻ khi hover */
    }

    .feature-icon {
        font-size: 3rem;
        color: var(--primary-color);
        margin-bottom: 1.5rem;
    }

    .feature-card h3 {
        font-size: 1.75rem;
        margin-bottom: 1rem;
        color: var(--dark-color);
    }

    .feature-card p {
        color: #666;
        line-height: 1.6;
    }

    /* Stats Section */
    .stats-section {
        background-color: var(--dark-color);
        color: white;
    }

    .stat-card {
        background: rgba(255, 255, 255, 0.1); /* Nền trong suốt nhẹ */
        padding: 2.5rem;
        border-radius: 20px;
        text-align: center;
        transition: all 0.3s ease;
    }

    .stat-card:hover {
        background: rgba(255, 255, 255, 0.2); /* Thay đổi nền khi hover */
    }

    .stat-icon {
        font-size: 3rem;
        color: var(--accent-color);
        margin-bottom: 1.5rem;
    }

    .stat-content h3 {
        font-size: 2.75rem;
        font-weight: bold;
        margin-bottom: 0.5rem;
    }

    .stat-content p {
        font-size: 1.2rem;
        opacity: 0.8;
    }

    /* Quick Actions Section */
    .action-card {
        background: white;
        padding: 2.5rem;
        border-radius: 20px;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
        text-align: center;
        transition: all 0.3s ease;
    }

    .action-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15); /* Đổ bóng lớn hơn khi hover */
    }

    .action-icon {
        font-size: 3.5rem;
        color: var(--primary-color);
        margin-bottom: 1.5rem;
    }

    .action-card h3 {
        font-size: 1.8rem;
        color: var(--dark-color);
        margin-bottom: 1rem;
    }

    .action-card p {
        color: #666;
        margin-bottom: 2rem;
    }

    .action-card .btn {
        padding: 12px 35px;
        border-radius: 50px;
        font-weight: 600;
    }

    /* Footer */
    .footer {
        background-color: var(--dark-color);
    }

    .social-links a {
        font-size: 1.5rem;
        transition: color 0.3s ease;
    }

    .social-links a:hover {
        color: var(--primary-color); /* Đổi màu khi hover */
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .hero-title {
            font-size: 2.5rem;
        }

        .hero-subtitle {
            font-size: 1.25rem;
        }

        .hero-actions .btn {
            display: block;
            margin: 15px auto;
            width: 90%;
        }

        .section-title {
            font-size: 2rem;
        }

        .feature-card, .stat-card, .action-card {
            margin-bottom: 20px;
        }
    }
</style>


<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nhà Hàng Việt Nam - Hương Vị Truyền Thống</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@700&family=Playfair+Display:wght@400;600;700&family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css">
    <style>
        :root {
            --primary-color: #ff6b4a;
            --secondary-color: #ffd000;
            --accent-color: #00d1a7;
            --text-color: #ffffff;
            --overlay-color: rgba(0, 0, 0, 0.5);
        }
        body {
            background: linear-gradient(var(--overlay-color), var(--overlay-color)),
                        url('https://images.unsplash.com/photo-1543007631-283050bb3e8c?q=80') no-repeat center center;
            background-size: cover;
            background-attachment: fixed;
            min-height: 100vh;
            font-family: 'Montserrat', sans-serif;
            overflow-x: hidden;
            animation: gradientShift 15s ease infinite;
        }

        @keyframes gradientShift {
            0% { background-color: rgba(0, 0, 0, 0.5); }
            50% { background-color: rgba(0, 0, 0, 0.4); }
            100% { background-color: rgba(0, 0, 0, 0.5); }
        }

        .hero-section {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 4rem 1rem;
        }

        .glass-container {
            background: rgba(0, 0, 0, 0.7);
            backdrop-filter: blur(10px);
            padding: 4rem 2rem;
            border-radius: 2rem;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.4);
            border: 1px solid rgba(255, 255, 255, 0.1);
            max-width: 1200px;
            width: 100%;
            margin: auto;
        }

        h1 {
            font-family: 'Dancing Script', cursive;
            font-size: clamp(3.5rem, 10vw, 6rem);
            color: var(--text-color);
            text-shadow: 2px 2px 8px rgba(0, 0, 0, 0.5);
            margin-bottom: 1.5rem;
            line-height: 1.2;
        }

        .subtitle {
            font-family: 'Playfair Display', serif;
            font-size: clamp(1.5rem, 4vw, 2rem);
            color: var(--secondary-color);
            margin-bottom: 2rem;
            font-weight: 600;
        }

        .lead {
            font-size: 1.25rem;
            color: var(--text-color);
            margin-bottom: 3rem;
            max-width: 800px;
            margin-left: auto;
            margin-right: auto;
        }

        .divider {
            height: 3px;
            background: linear-gradient(to right, transparent, var(--primary-color), transparent);
            margin: 3rem 0;
            opacity: 0.8;
        }

        .features {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 2.5rem;
            margin: 4rem 0;
        }

        .feature-item {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 1.5rem;
            padding: 2.5rem 2rem;
            transition: all 0.4s ease;
            border: 1px solid rgba(255, 255, 255, 0.05);
            position: relative;
            overflow: hidden;
        }

        .feature-item::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(45deg, var(--primary-color), var(--secondary-color));
            opacity: 0;
            transition: opacity 0.4s ease;
            z-index: 0;
        }

        .feature-item:hover::before {
            opacity: 0.1;
        }

        .feature-item:hover {
            transform: translateY(-10px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        }

        .feature-icon {
            font-size: 3rem;
            margin-bottom: 1.5rem;
            display: inline-block;
            background: linear-gradient(45deg, var(--primary-color), var(--secondary-color));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            position: relative;
            z-index: 1;
        }

        .feature-item h3 {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 1rem;
            color: var(--text-color);
            position: relative;
            z-index: 1;
        }

        .feature-item p {
            font-size: 1.1rem;
            color: rgba(255, 255, 255, 0.9);
            margin: 0;
            position: relative;
            z-index: 1;
            line-height: 1.6;
        }

        .specialties {
            text-align: center;
            margin: 4rem 0;
        }

        .specialties h2 {
            font-family: 'Playfair Display', serif;
            font-size: 2.5rem;
            color: var(--text-color);
            margin-bottom: 3rem;
        }

        .specialty-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
            margin-bottom: 3rem;
        }

        .specialty-item {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 1rem;
            padding: 2rem;
            text-align: center;
        }

        .specialty-item img {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 1.5rem;
            border: 3px solid var(--primary-color);
        }

        .btn-custom {
            background: linear-gradient(45deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 1.2rem 3rem;
            border-radius: 50px;
            font-size: 1.2rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s ease;
            border: none;
            box-shadow: 0 4px 15px rgba(233, 78, 27, 0.4);
            text-decoration: none;
            display: inline-block;
            margin-top: 2rem;
            position: relative;
            overflow: hidden;
        }

        .btn-custom::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(45deg, var(--secondary-color), var(--primary-color));
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .btn-custom:hover::before {
            opacity: 1;
        }

        .btn-custom:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(233, 78, 27, 0.6);
            color: white;
        }

        .btn-custom span {
            position: relative;
            z-index: 1;
        }

        @media (max-width: 768px) {
            .glass-container {
                padding: 3rem 1.5rem;
            }

            .features {
                grid-template-columns: 1fr;
                gap: 2rem;
            }

            .specialty-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="hero-section">
        <div class="glass-container">
            <div data-aos="fade-down" data-aos-duration="1000">
                <h1>Phần Mềm Quản Lý Món Ăn</h1>
                <h2 class="mt-3" style="font-family: 'Dancing Script', cursive; font-size: clamp(2.5rem, 8vw, 4rem); color: var(--text-color);">Nhà Hàng Việt Nam</h2>
                <p class="subtitle">Hương Vị Truyền Thống - Phong Cách Hiện Đại</p>
                <p class="lead">Khám phá tinh hoa ẩm thực Việt Nam trong không gian sang trọng và ấm cúng. Chúng tôi tự hào mang đến những món ăn đặc sắc được chế biến từ những nguyên liệu tươi ngon nhất.</p>
            </div>

            <div class="divider"></div>

            <div class="features">
                <div class="feature-item" data-aos="fade-up" data-aos-delay="100">
                    <i class="fas fa-utensils feature-icon"></i>
                    <h3>Món Ăn Đặc Sắc</h3>
                    <p>Thực đơn phong phú với các món ăn truyền thống Việt Nam được chế biến tinh tế, kết hợp hài hòa giữa hương vị xưa và cách trình bày hiện đại</p>
                </div>
                <div class="feature-item" data-aos="fade-up" data-aos-delay="200">
                    <i class="fas fa-award feature-icon"></i>
                    <h3>Đầu Bếp Chuyên Nghiệp</h3>
                    <p>Đội ngũ đầu bếp giàu kinh nghiệm và tâm huyết, luôn tận tâm trong từng món ăn để mang đến trải nghiệm ẩm thực tuyệt vời nhất</p>
                </div>
                <div class="feature-item" data-aos="fade-up" data-aos-delay="300">
                    <i class="fas fa-gem feature-icon"></i>
                    <h3>Không Gian Sang Trọng</h3>
                    <p>Thiết kế nội thất tinh tế, không gian ấm cúng và sang trọng, tạo nên bầu không khí hoàn hảo cho bữa ăn của bạn</p>
                </div>
            </div>
            <div class="system-features" data-aos="fade-up">
                <h2 class="text-center mb-5" style="color: var(--text-color);">Tính Năng Hệ Thống</h2>
                <div class="row g-4">
                    <div class="col-md-4" data-aos="zoom-in" data-aos-delay="100">
                        <div class="feature-card">
                            <i class="fas fa-tasks-alt feature-icon mb-4"></i>
                            <h3>Quản Lý Thực Đơn</h3>
                            <p>Dễ dàng thêm, sửa, xóa và cập nhật món ăn với đầy đủ thông tin chi tiết</p>
                        </div>
                    </div>
                    <div class="col-md-4" data-aos="zoom-in" data-aos-delay="200">
                        <div class="feature-card">
                            <i class="fas fa-chart-line feature-icon mb-4"></i>
                            <h3>Báo Cáo & Thống Kê</h3>
                            <p>Theo dõi doanh thu, phân tích xu hướng và quản lý hiệu quả kinh doanh</p>
                        </div>
                    </div>
                    <div class="col-md-4" data-aos="zoom-in" data-aos-delay="300">
                        <div class="feature-card">
                            <i class="fas fa-users-cog feature-icon mb-4"></i>
                            <h3>Quản Lý Nhân Viên</h3>
                            <p>Phân quyền và theo dõi hoạt động của nhân viên một cách hiệu quả</p>
                        </div>
                    </div>
                </div>
            </div>
            <style>
                .system-features {
                    margin: 4rem 0;
                }
                .feature-card {
                    background: rgba(255, 255, 255, 0.1);
                    border-radius: 1rem;
                    padding: 2rem;
                    text-align: center;
                    transition: all 0.3s ease;
                    height: 100%;
                    border: 1px solid rgba(255, 255, 255, 0.05);
                }
                .feature-card:hover {
                    transform: translateY(-10px);
                    background: rgba(255, 255, 255, 0.15);
                }
                .feature-card h3 {
                    color: var(--text-color);
                    margin-bottom: 1rem;
                    font-size: 1.5rem;
                }
                .feature-card p {
                    color: rgba(255, 255, 255, 0.9);
                    font-size: 1rem;
                    line-height: 1.6;
                }
            </style>
            <a href="{{ route('login') }}" class="btn btn-custom">
                <span><i class="fas fa-sign-in-alt me-2"></i>Đăng Nhập Quản Lý</span>
            </a>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
    <script>
        AOS.init({
            duration: 1000,
            once: true
        });
    </script>
</body>
</html>

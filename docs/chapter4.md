# Chương 4: Cài Đặt và Thực Nghiệm

## 4.1. Môi Trường Phát Triển
### 4.1.1. Yêu Cầu Hệ Thống
- PHP >= 8.1
- Laravel Framework 10.x
- MySQL 8.0
- Composer (PHP Dependency Manager)
- Node.js và NPM
- RAM: Tối thiểu 4GB
- Ổ cứng: Tối thiểu 10GB trống
- Hệ điều hành: Windows 10/11, macOS, hoặc Linux

### 4.1.2. Công Cụ Phát Triển
- Visual Studio Code với các extension:
  - Laravel Extension Pack
  - PHP Intelephense
  - Git Graph
  - MySQL Management Tool
- Laravel Artisan CLI
- Git (Quản lý mã nguồn)
- Laragon (Web server local)
- Postman (API Testing)

## 4.2. Cài Đặt và Cấu Hình

### 4.2.1. Cài Đặt Laravel Framework
```bash
# Tạo project mới
composer create-project laravel/laravel foodmanager
cd foodmanager

# Cài đặt dependencies
composer install
npm install

# Cài đặt các package bổ sung
composer require laravel/ui
php artisan ui bootstrap --auth
npm install && npm run dev
```

### 4.2.2. Cấu Hình Cơ Sở Dữ Liệu
1. Tạo file .env từ .env.example:
```bash
cp .env.example .env
php artisan key:generate
```

2. Cấu hình kết nối database trong file .env:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=foodmanager
DB_USERNAME=root
DB_PASSWORD=

APP_NAME="Food Manager"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000
```

### 4.2.3. Tạo Cấu Trúc Database
```bash
# Tạo và chạy migrations
php artisan migrate

# Tạo dữ liệu mẫu
php artisan db:seed

# Tạo symbolic link cho storage
php artisan storage:link
```

## 4.3. Triển Khai Chức Năng

### 4.3.1. Quản Lý Danh Mục
1. Tạo model và migration:
```bash
php artisan make:model Category -m
```

2. Cấu trúc bảng categories:
```php
Schema::create('categories', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->string('slug')->unique();
    $table->text('description')->nullable();
    $table->boolean('is_active')->default(true);
    $table->timestamps();
});
```

3. Tạo controller và routes:
```bash
php artisan make:controller CategoryController --resource
```

4. Định nghĩa routes trong routes/web.php:
```php
Route::resource('categories', CategoryController::class);
```

### 4.3.2. Quản Lý Món Ăn
1. Tạo model và migration:
```bash
php artisan make:model Food -m
```

2. Cấu trúc bảng foods:
```php
Schema::create('foods', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->string('slug')->unique();
    $table->text('description');
    $table->decimal('price', 10, 2);
    $table->string('image')->nullable();
    $table->foreignId('category_id')->constrained();
    $table->boolean('is_available')->default(true);
    $table->timestamps();
});
```

3. Xử lý upload hình ảnh:
```php
public function store(Request $request)
{
    $validatedData = $request->validate([
        'image' => 'required|image|mimes:jpg,png,jpeg|max:2048'
    ]);

    $imagePath = $request->file('image')->store('foods', 'public');
}
```

### 4.3.3. Quản Lý Đơn Hàng
1. Tạo các models và migrations:
```bash
php artisan make:model Order -m
php artisan make:model OrderItem -m
```

2. Cấu trúc bảng orders:
```php
Schema::create('orders', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained();
    $table->decimal('total_amount', 10, 2);
    $table->string('status');
    $table->text('delivery_address');
    $table->string('payment_method');
    $table->timestamps();
});
```

3. Quy trình xử lý đơn hàng:
- Kiểm tra tồn kho
- Tính toán giá trị đơn hàng
- Xử lý thanh toán
- Cập nhật trạng thái

## 4.4. Kiểm Thử Hệ Thống

### 4.4.1. Kiểm Thử Chức Năng
1. Unit Tests:
```bash
php artisan make:test CategoryTest --unit
php artisan make:test FoodTest --unit
php artisan make:test OrderTest --unit
```

2. Feature Tests:
```bash
php artisan make:test CategoryControllerTest
php artisan make:test FoodControllerTest
php artisan make:test OrderControllerTest
```

3. Kết quả kiểm thử:
```bash
php artisan test
```

### 4.4.2. Kiểm Thử Hiệu Năng
1. Công cụ sử dụng:
- Apache JMeter
- Laravel Debugbar
- Laravel Telescope

2. Các metrics đo lường:
- Response time
- Throughput
- Error rate
- CPU/Memory usage

3. Kết quả benchmark:
- Thời gian phản hồi trung bình: < 200ms
- Throughput: 100 requests/second
- Error rate: < 0.1%

## 4.5. Triển Khai và Vận Hành

### 4.5.1. Quy Trình Triển Khai
1. Chuẩn bị môi trường production:
```bash
# Optimize autoloader
composer install --optimize-autoloader --no-dev

# Optimize configuration loading
php artisan config:cache

# Optimize route loading
php artisan route:cache

# Optimize view loading
php artisan view:cache
```

2. Cấu hình web server:
- Nginx/Apache configuration
- SSL certificate
- Cache configuration
- Database optimization

### 4.5.2. Bảo Trì và Nâng Cấp
1. Backup dữ liệu định kỳ:
```bash
# Backup database
php artisan backup:run

# Backup files
rsync -avz /path/to/app /backup/location
```

2. Monitoring:
- Server monitoring (CPU, RAM, Disk)
- Application monitoring (Errors, Logs)
- Database monitoring (Queries, Performance)

### 4.5.3. Đánh Giá Hiệu Quả
1. Metrics:
- User engagement
- System performance
- Business metrics

2. Feedback và Cải Tiến:
- User feedback collection
- System optimization
- Feature enhancement

3. Documentation:
- API documentation
- User guides
- Technical documentation
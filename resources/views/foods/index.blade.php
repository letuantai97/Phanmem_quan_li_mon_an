@extends('layouts.app')
@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h3 class="mb-0">Danh Sách Món Ăn</h3>
                    <a href="{{ route('foods.create') }}" class="btn btn-light">
                        <i class="fas fa-plus"></i> Thêm Món Ăn
                    </a>
                </div>
                <div class="card-body">
                    <!-- Tìm kiếm và lọc -->
                    <form action="{{ route('foods.index') }}" method="GET" class="mb-4">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <input type="text" name="search" class="form-control"
                                       placeholder="Tìm kiếm món ăn..."
                                       value="{{ request('search') }}">
                            </div>
                            <div class="col-md-3">
                                <select name="category_id" class="form-select">
                                    <option value="">Tất cả danh mục</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}"
                                            {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-auto">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-search"></i> Tìm kiếm
                                </button>
                            </div>
                        </div>
                    </form>

                    <!-- Danh sách món ăn -->
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Hình ảnh</th>
                                    <th>Tên món</th>
                                    <th>Danh mục</th>
                                    <th>Giá</th>
                                    <th>Trạng thái</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($foods as $food)
                                <tr>
                                    <td>{{ $food->id }}</td>
                                    <td>
                                        <div class="food-image-container">
                                            <img src="{{ $food->image_url }}"
                                                 alt="{{ $food->name }}"
                                                 class="food-image img-thumbnail"
                                                 loading="lazy">
                                        </div>
                                    </td>
                                    <td>{{ $food->name }}</td>
                                    <td>{{ $food->category->name }}</td>
                                    <td>{{ $food->formatted_price }}</td>
                                    <td>
                                        <span class="badge bg-{{ $food->status ? 'success' : 'danger' }}">
                                            {{ $food->status ? 'Còn hàng' : 'Hết hàng' }}
                                        </span>
                                    </td>
                                    @if(auth()->user()->role === "admin")
                                    <td>
                                        <a href="{{ route('foods.edit', $food) }}"
                                           class="btn btn-sm btn-primary">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('foods.destroy', $food) }}"
                                              method="POST"
                                              class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="btn btn-sm btn-danger"
                                                    onclick="return confirm('Bạn có chắc muốn xóa món ăn này?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                    @endif
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4">
                                        <i class="fas fa-inbox fa-3x text-muted mb-3 d-block"></i>
                                        Chưa có món ăn nào
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Phân trang -->
                    <div class="d-flex justify-content-center mt-4">
                        <div class="pagination-container">
                            {{ $foods->links('pagination::bootstrap-4') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('styles')
<style>
    /* Định dạng Pagination */
    .pagination-container {
        width: 100%;
        max-width: 600px;
    }
    .pagination-container .pagination {
        margin: 0;
        justify-content: center;
    }
    .pagination-container .page-item .page-link {
        padding: 0.5rem 1rem;
        margin: 0 2px;
        color: #1a73e8;
        border-radius: 4px;
        border: 1px solid #dee2e6;
        transition: all 0.2s ease-in-out;
    }
    .pagination-container .page-item.active .page-link {
        background-color: #1a73e8;
        border-color: #1a73e8;
        color: white;
    }
    .pagination-container .page-item .page-link:hover {
        background-color: #e9ecef;
        border-color: #dee2e6;
        color: #0056b3;
    }
    .pagination-container .page-item.disabled .page-link {
        color: #6c757d;
        pointer-events: none;
        background-color: #fff;
        border-color: #dee2e6;
    }

    /* Định dạng hình ảnh món ăn */
    .food-image-container {
        width: 200px; /* Kích thước nhỏ gọn, đủ rõ */
        height: 200px;
        overflow: hidden;
        border-radius: 6px; /* Bo góc nhẹ */
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1px solid #dee2e6; /* Viền nhẹ nhàng */
        background: #fff;
        margin: 0 auto;
        flex-shrink: 0;
        position: relative; /* Hỗ trợ hiệu ứng hover */
    }

    .food-image {
        width: 100%;
        height: 100%;
        object-fit: cover; /* Giữ tỷ lệ ảnh */
        border-radius: 6px;
        transition: transform 0.3s ease; /* Hiệu ứng mượt mà */
    }

    /* Hiệu ứng hover */
    .food-image-container:hover .food-image {
        transform: scale(1.5); /* Phóng to vừa phải */
        position: absolute;
        z-index: 10; /* Đảm bảo nổi lên trên */
        box-shadow: 0 2px 4px rgba(0,0,0,0.15); /* Bóng nhẹ */
    }

    /* Căn giữa các ô trong bảng */
    td {
        vertical-align: middle;
    }
</style>


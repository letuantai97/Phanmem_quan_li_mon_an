@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h2 class="mb-0">Thêm Món Ăn Mới</h2>
                </div>
                <div class="card-body">
                    <form action="{{ route('foods.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-3">
                            <label for="order_type" class="form-label">Loại Đơn Hàng <span class="text-danger">*</span></label>
                            <select class="form-select" id="order_type" name="order_type" required onchange="toggleFields()">
                                <option value="dine_in">Tại Quán</option>
                                <option value="online">Đặt Online</option>
                            </select>
                        </div>

                        <div id="dine_in_form">
                            <div class="mb-3">
                                <label for="customer_name_dine" class="form-label">Tên Khách Hàng <span class="text-danger">*</span></label>
                                <input type="text"
                                       class="form-control @error('customer_name') is-invalid @enderror"
                                       id="customer_name_dine"
                                       name="customer_name"
                                       value="{{ old('customer_name') }}"
                                       required>
                                @error('customer_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="table_number" class="form-label">Số Bàn <span class="text-danger">*</span></label>
                                <input type="number"
                                       class="form-control @error('table_number') is-invalid @enderror"
                                       id="table_number"
                                       name="table_number"
                                       value="{{ old('table_number') }}"
                                       min="1"
                                       required>
                                @error('table_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div id="online_form" style="display: none;">
                            <div class="mb-3">
                                <label for="customer_name_online" class="form-label">Tên Người Đặt Hàng <span class="text-danger">*</span></label>
                                <input type="text"
                                       class="form-control @error('customer_name') is-invalid @enderror"
                                       id="customer_name_online"
                                       name="customer_name"
                                       value="{{ old('customer_name') }}">
                                @error('customer_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="customer_phone" class="form-label">Số Điện Thoại <span class="text-danger">*</span></label>
                                <input type="tel"
                                       class="form-control @error('customer_phone') is-invalid @enderror"
                                       id="customer_phone"
                                       name="customer_phone"
                                       value="{{ old('customer_phone') }}">
                                @error('customer_phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="delivery_address" class="form-label">Địa Chỉ Giao Hàng <span class="text-danger">*</span></label>
                                <textarea class="form-control @error('delivery_address') is-invalid @enderror"
                                          id="delivery_address"
                                          name="delivery_address"
                                          rows="2">{{ old('delivery_address') }}</textarea>
                                @error('delivery_address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="name" class="form-label">Tên Món Ăn <span class="text-danger">*</span></label>
                            <input type="text"
                                   class="form-control @error('name') is-invalid @enderror"
                                   id="name"
                                   name="name"
                                   value="{{ old('name') }}"
                                   required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="category_id" class="form-label">Danh Mục <span class="text-danger">*</span></label>
                            <select class="form-select @error('category_id') is-invalid @enderror"
                                    id="category_id"
                                    name="category_id"
                                    required>
                                <option value="">-- Chọn danh mục --</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}"
                                            {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="price" class="form-label">Giá <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="number"
                                       class="form-control @error('price') is-invalid @enderror"
                                       id="price"
                                       name="price"
                                       value="{{ old('price') }}"
                                       required>
                                <span class="input-group-text">VNĐ</span>
                                @error('price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Mô Tả</label>
                            <textarea class="form-control @error('description') is-invalid @enderror"
                                      id="description"
                                      name="description"
                                      rows="3">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="image" class="form-label">Hình Ảnh</label>
                            <input type="file"
                                   class="form-control @error('image') is-invalid @enderror"
                                   id="image"
                                   name="image"
                                   accept="image/*"
                                   onchange="previewImage(this)">
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div id="imagePreview" class="mt-2"></div>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Lưu Món Ăn
                            </button>
                            <a href="{{ route('foods.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Quay Lại
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function toggleFields() {
    const orderType = document.getElementById('order_type').value;
    const dineInForm = document.getElementById('dine_in_form');
    const onlineForm = document.getElementById('online_form');
    const dineInInputs = dineInForm.querySelectorAll('input, textarea');
    const onlineInputs = onlineForm.querySelectorAll('input, textarea');

    if (orderType === 'dine_in') {
        dineInForm.style.display = 'block';
        onlineForm.style.display = 'none';
        dineInInputs.forEach(input => input.setAttribute('required', 'required'));
        onlineInputs.forEach(input => {
            input.removeAttribute('required');
            input.value = '';
        });
    } else {
        dineInForm.style.display = 'none';
        onlineForm.style.display = 'block';
        onlineInputs.forEach(input => input.setAttribute('required', 'required'));
        dineInInputs.forEach(input => {
            input.removeAttribute('required');
            input.value = '';
        });
    }
}

function previewImage(input) {
    const preview = document.getElementById('imagePreview');
    preview.innerHTML = '';

    if (input.files && input.files[0]) {
        const reader = new FileReader();

        reader.onload = function(e) {
            const img = document.createElement('img');
            img.src = e.target.result;
            img.style.maxWidth = '200px';
            img.style.maxHeight = '200px';
            img.className = 'img-thumbnail';
            preview.appendChild(img);
        }

        reader.readAsDataURL(input.files[0]);
    }
}

// Initialize form fields on page load
document.addEventListener('DOMContentLoaded', function() {
    toggleFields();
});
</script>
@endpush
@endsection

@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-6">
            <h1>Tạo Đơn Hàng Mới</h1>
        </div>
        <div class="col-md-6 text-end">
            <a href="{{ route('orders.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Quay lại
            </a>
        </div>
    </div>

    <form action="{{ route('orders.store') }}" method="POST" id="orderForm">
        @csrf
        <div class="row">
            <div class="col-md-8">
                <!-- Thông tin khách hàng -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Thông tin khách hàng</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="customer_name" class="form-label">Tên khách hàng</label>
                                <input type="text"
                                       class="form-control @error('customer_name') is-invalid @enderror"
                                       name="customer_name"
                                       value="{{ old('customer_name') }}"
                                       required>
                                @error('customer_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="customer_phone" class="form-label">Số điện thoại</label>
                                <input type="text"
                                       class="form-control @error('customer_phone') is-invalid @enderror"
                                       name="customer_phone"
                                       value="{{ old('customer_phone') }}"
                                       required>
                                @error('customer_phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="customer_email" class="form-label">Email</label>
                            <input type="email"
                                   class="form-control @error('customer_email') is-invalid @enderror"
                                   name="customer_email"
                                   value="{{ old('customer_email') }}">
                            @error('customer_email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="address" class="form-label">Địa chỉ</label>
                            <textarea class="form-control @error('address') is-invalid @enderror"
                                      name="address"
                                      rows="3"
                                      required>{{ old('address') }}</textarea>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="note" class="form-label">Ghi chú</label>
                            <textarea class="form-control @error('note') is-invalid @enderror"
                                      name="note"
                                      rows="2">{{ old('note') }}</textarea>
                            @error('note')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Danh sách món ăn -->
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Danh sách món ăn</h5>
                        <button type="button" class="btn btn-primary btn-sm" id="addItem">
                            <i class="fas fa-plus"></i> Thêm món
                        </button>
                    </div>
                    <div class="card-body">
                        <div id="orderItems">
                            <!-- Items will be added here dynamically -->
                        </div>
                        <div class="text-end mt-3">
                            <h5>Tổng cộng: <span id="totalAmount">0</span>đ</h5>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Tạo đơn hàng</h5>
                    </div>
                    <div class="card-body">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-save"></i> Lưu đơn hàng
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    let foods = [];
    try {
        const foodsData = '{!! json_encode($foods ?? [], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) !!}';
        foods = JSON.parse(foodsData) || [];
    } catch (error) {
        console.error('Error parsing foods data:', error);
        foods = [];
    }
    let itemCount = 0;
    const orderItems = document.getElementById('orderItems');
    const addItemBtn = document.getElementById('addItem');

    function createItemRow() {
        const row = document.createElement('div');
        row.className = 'row mb-3 order-item';
        row.innerHTML = `
            <div class="col-md-5">
                <select name="items[${itemCount}][food_id]" class="form-control food-select" required>
                    <option value="">Chọn món ăn</option>
                    ${foods.map(food => `
                        <option value="${food.id}" data-price="${food.price}">
                            ${food.name} - ${new Intl.NumberFormat('vi-VN').format(food.price)}đ
                        </option>
                    `).join('')}
                </select>
            </div>
            <div class="col-md-3">
                <input type="number"
                       name="items[${itemCount}][quantity]"
                       class="form-control quantity-input"
                       value="1"
                       min="1"
                       required>
            </div>
            <div class="col-md-3">
                <span class="form-control item-total">0đ</span>
            </div>
            <div class="col-md-1">
                <button type="button" class="btn btn-danger btn-sm remove-item">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        `;

        orderItems.appendChild(row);
        itemCount++;

        // Add event listeners
        const select = row.querySelector('.food-select');
        const quantity = row.querySelector('.quantity-input');
        const removeBtn = row.querySelector('.remove-item');

        select.addEventListener('change', updateTotal);
        quantity.addEventListener('change', updateTotal);
        removeBtn.addEventListener('click', () => {
            row.remove();
            updateTotal();
        });
    }

    function updateTotal() {
        let total = 0;
        document.querySelectorAll('.order-item').forEach(item => {
            const select = item.querySelector('.food-select');
            const quantity = item.querySelector('.quantity-input');
            const itemTotal = item.querySelector('.item-total');

            if (select.value) {
                const price = parseFloat(select.options[select.selectedIndex].dataset.price);
                const qty = parseInt(quantity.value);
                const subtotal = price * qty;
                total += subtotal;
                itemTotal.textContent = new Intl.NumberFormat('vi-VN').format(subtotal) + 'đ';
            }
        });

        document.getElementById('totalAmount').textContent =
            new Intl.NumberFormat('vi-VN').format(total);
    }

    addItemBtn.addEventListener('click', createItemRow);

    // Add first row by default
    createItemRow();
});
</script>
@endpush

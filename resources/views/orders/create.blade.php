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
                <!-- Thông tin đơn hàng -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Thông tin đơn hàng</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="customer_name" class="form-label">Tên khách hàng <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('customer_name') is-invalid @enderror"
                                   id="customer_name" name="customer_name" value="{{ old('customer_name') }}" required>
                            @error('customer_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="customer_phone" class="form-label">Số điện thoại <span class="text-danger">*</span></label>
                            <input type="tel" class="form-control @error('customer_phone') is-invalid @enderror"
                                   id="customer_phone" name="customer_phone" value="{{ old('customer_phone') }}" required pattern="[0-9]{10}" title="Vui lòng nhập số điện thoại hợp lệ (10 số)">
                            @error('customer_phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="note" class="form-label">Ghi chú</label>
                            <textarea class="form-control @error('note') is-invalid @enderror"
                                      id="note" name="note" rows="2">{{ old('note') }}</textarea>
                            @error('note')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                <!-- Danh sách món ăn -->
                <div class="card" id="orderItemsSection">
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
                        <div class="mb-3">
                            <label for="order_type" class="form-label">Loại đơn hàng <span class="text-danger">*</span></label>
                            <select class="form-select @error('order_type') is-invalid @enderror" name="order_type" id="orderType" required>
                                <option value="">Chọn loại đơn hàng</option>
                                <option value="dine-in" {{ old('order_type') == 'dine-in' ? 'selected' : '' }}>Tại chỗ</option>
                                <option value="online" {{ old('order_type') == 'online' ? 'selected' : '' }}>Trực tuyến</option>
                            </select>
                            @error('order_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3" id="tableNumberField" style="display: none;">
                            <label for="table_number" class="form-label">Số bàn <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('table_number') is-invalid @enderror"
                                   id="table_number" name="table_number" value="{{ old('table_number') }}" min="1" max="100">
                            @error('table_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3" id="addressField" style="display: none;">
                            <label for="address" class="form-label">Địa chỉ giao hàng <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('address') is-invalid @enderror"
                                      id="address" name="address" rows="2" minlength="10">{{ old('address') }}</textarea>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="status" class="form-label">Trạng thái đơn hàng</label>
                            <select class="form-select @error('status') is-invalid @enderror" name="status" id="status" required>
                                <option value="chờ xử lý" {{ old('status') == 'chờ xử lý' ? 'selected' : '' }}>Chờ xử lý</option>
                                <option value="đang xử lý" {{ old('status') == 'đang xử lý' ? 'selected' : '' }}>Đang xử lý</option>
                                <option value="hoàn thành" {{ old('status') == 'hoàn thành' ? 'selected' : '' }}>Hoàn thành</option>
                                <option value="đã hủy" {{ old('status') == 'đã hủy' ? 'selected' : '' }}>Đã hủy</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-primary w-100" id="submitOrderBtn">
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
    const orderForm = document.getElementById('orderForm');
    const orderTypeSelect = document.getElementById('orderType');
    const addressField = document.getElementById('addressField');
    const tableNumberField = document.getElementById('tableNumberField');
    const orderItemsSection = document.getElementById('orderItemsSection');
    const orderItems = document.getElementById('orderItems');
    const addItemBtn = document.getElementById('addItem');
    const submitOrderBtn = document.getElementById('submitOrderBtn');

    // Tạo hàng đầu tiên cho danh sách món ăn khi tải trang
    createItemRow();

    // Hàm hiển thị thông báo lỗi
    function showError(message) {
        const existingErrors = document.querySelectorAll('.alert-danger');
        existingErrors.forEach(error => error.remove());

        const errorDiv = document.createElement('div');
        errorDiv.className = 'alert alert-danger alert-dismissible fade show mt-3';
        errorDiv.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        `;
        document.querySelector('.container').insertBefore(errorDiv, document.querySelector('.row'));
        window.scrollTo({ top: 0, behavior: 'smooth' });
        setTimeout(() => errorDiv.remove(), 5000);
    }

    // Hàm cập nhật hiển thị số bàn/địa chỉ dựa trên loại đơn hàng
    function updateFormDisplay(orderType) {
        addressField.style.display = 'none';
        tableNumberField.style.display = 'none';
        document.getElementById('address').required = false;
        document.getElementById('table_number').required = false;

        if (orderType === 'online') {
            addressField.style.display = 'block';
            document.getElementById('address').required = true;
        } else if (orderType === 'dine-in') {
            tableNumberField.style.display = 'block';
            document.getElementById('table_number').required = true;
            // Automatically set status to 'chờ xử lý' for dine-in orders
            document.getElementById('status').value = 'chờ xử lý';
        }
    }

    // Xử lý sự kiện khi thay đổi loại đơn hàng
    orderTypeSelect.addEventListener('change', function() {
        updateFormDisplay(this.value);
        // Clear table number and address when switching order types
        document.getElementById('table_number').value = '';
        document.getElementById('address').value = '';
    });

    // Kiểm tra giá trị mặc định từ old() khi tải trang và tự động chọn 'dine-in'
    const initialOrderType = 'dine-in';
    orderTypeSelect.value = initialOrderType;
    updateFormDisplay(initialOrderType);

    // Tạo hàng mới cho danh sách món ăn
    function createItemRow() {
        if (!foods.length) {
            showError('Không có món ăn nào trong danh sách. Vui lòng thêm món ăn trước.');
            return;
        }

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
                <input type="number" name="items[${itemCount}][quantity]" class="form-control quantity-input" value="1" min="1" required>
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

        const select = row.querySelector('.food-select');
        const quantity = row.querySelector('.quantity-input');
        const removeBtn = row.querySelector('.remove-item');

        select.addEventListener('change', updateTotal);
        quantity.addEventListener('change', updateTotal);
        removeBtn.addEventListener('click', () => {
            row.remove();
            updateTotal();
        });
        updateTotal();
    }

    // Cập nhật tổng tiền
    function updateTotal() {
        let total = 0;
        document.querySelectorAll('.order-item').forEach(item => {
            const select = item.querySelector('.food-select');
            const quantity = item.querySelector('.quantity-input');
            const itemTotal = item.querySelector('.item-total');

            if (select.value) {
                const price = parseFloat(select.options[select.selectedIndex].dataset.price);
                const qty = parseInt(quantity.value) || 0;
                const subtotal = price * qty;
                total += subtotal;
                itemTotal.textContent = new Intl.NumberFormat('vi-VN').format(subtotal) + 'đ';
            } else {
                itemTotal.textContent = '0đ';
            }
        });
        document.getElementById('totalAmount').textContent = new Intl.NumberFormat('vi-VN').format(total);
    }

    addItemBtn.addEventListener('click', createItemRow);

    // Validate form trước khi submit
    orderForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const orderType = orderTypeSelect.value;
        const items = document.querySelectorAll('.order-item');
        const customerName = document.getElementById('customer_name').value.trim();
        const customerPhone = document.getElementById('customer_phone').value.trim();
        const status = document.getElementById('status').value;
        let hasError = false;

        // Kiểm tra tên khách hàng
        if (!customerName) {
            showError('Vui lòng nhập tên khách hàng');
            hasError = true;
        }

        // Kiểm tra số điện thoại
        if (!customerPhone || !/^\d{10}$/.test(customerPhone)) {
            showError('Vui lòng nhập số điện thoại hợp lệ (10 chữ số)');
            hasError = true;
        }

        // Kiểm tra loại đơn hàng
        if (!orderType) {
            showError('Vui lòng chọn loại đơn hàng');
            hasError = true;
        }

        // Kiểm tra số bàn hoặc địa chỉ nếu có
        if (orderType === 'dine-in') {
            const tableNumber = document.getElementById('table_number').value;
            if (!tableNumber || isNaN(tableNumber) || parseInt(tableNumber) < 1 || parseInt(tableNumber) > 100) {
                showError('Vui lòng nhập số bàn hợp lệ (1-100)');
                hasError = true;
            }
        } else if (orderType === 'online') {
            const address = document.getElementById('address').value.trim();
            if (!address || address.length < 10) {
                showError('Vui lòng nhập địa chỉ giao hàng (ít nhất 10 ký tự)');
                hasError = true;
            }
        }

        // Kiểm tra danh sách món ăn
        if (items.length === 0) {
            showError('Vui lòng thêm ít nhất một món ăn vào đơn hàng');
            hasError = true;
        }

        items.forEach(item => {
            const foodSelect = item.querySelector('.food-select');
            const quantity = item.querySelector('.quantity-input');

            if (!foodSelect.value) {
                showError('Vui lòng chọn món ăn');
                hasError = true;
            }
            if (!quantity.value || quantity.value < 1) {
                showError('Số lượng món ăn phải lớn hơn 0');
                hasError = true;
            }
        });

        // Kiểm tra trạng thái đơn hàng
        if (!status) {
            showError('Vui lòng chọn trạng thái đơn hàng');
            hasError = true;
        }

        // Nếu không có lỗi, submit form
        if (!hasError) {
            this.submit();
        }
    });
});
</script>
@endpush
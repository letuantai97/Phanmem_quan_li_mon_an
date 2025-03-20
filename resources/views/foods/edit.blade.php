@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header">
                    <h2 class="mb-0">Sửa Món Ăn</h2>
                </div>
                <div class="card-body">
                    <form action="{{ route('foods.update', $food) }}"
                          method="POST"
                          enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="form-group mb-3">
                            <label for="name">Tên Món Ăn</label>
                            <input type="text"
                                   class="form-control @error('name') is-invalid @enderror"
                                   name="name"
                                   id="name"
                                   value="{{ old('name', $food->name) }}"
                                   required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="category_id">Danh Mục</label>
                            <select class="form-control @error('category_id') is-invalid @enderror"
                                    name="category_id"
                                    id="category_id"
                                    required>
                                <option value="">Chọn Danh Mục</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}"
                                            {{ old('category_id', $food->category_id) == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="description">Mô Tả</label>
                            <textarea class="form-control @error('description') is-invalid @enderror"
                                      name="description"
                                      id="description"
                                      rows="3">{{ old('description', $food->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="price">Giá</label>
                            <div class="input-group">
                                <input type="number"
                                       class="form-control @error('price') is-invalid @enderror"
                                       name="price"
                                       id="price"
                                       value="{{ old('price', $food->price) }}"
                                       required>
                                <span class="input-group-text">VND</span>
                            </div>
                            @error('price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="image">Hình Ảnh Mới</label>
                            <input type="file"
                                   class="form-control @error('image') is-invalid @enderror"
                                   name="image"
                                   id="image">
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        @if($food->image)
                            <div class="form-group mb-3">
                                <label>Hình Ảnh Hiện Tại</label>
                                <div>
                                    <img src="{{ asset('storage/'.$food->image) }}"
                                         alt="Current food image"
                                         class="img-thumbnail"
                                         style="max-height: 200px">
                                </div>
                            </div>
                        @endif

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Cập Nhật
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
@endsection

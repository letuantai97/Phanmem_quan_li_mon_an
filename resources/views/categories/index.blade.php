@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-6">
            <h1>Quản Lý Danh Mục</h1>
        </div>
        <div class="col-md-6 text-end">
            <a href="{{ route('categories.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Thêm Danh Mục Mới
            </a>
        </div>
    </div>
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Tên Danh Mục</th>
                            <th>Số Món Ăn</th>
                            <th>Mô Tả</th>
                            <th>Thao Tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($categories as $category)
                            <tr>
                                <td>{{ $category->id }}</td>
                                <td>{{ $category->name }}</td>
                                <td>{{ $category->foods_count }}</td>
                                <td>{{ $category->description ?? 'Không có mô tả' }}</td>
                                <td>
    @if(auth()->user() && auth()->user()->role === "admin")
        <div class="btn-group" role="group">
            <a href="{{ route('categories.edit', $category) }}"
               class="btn btn-warning btn-sm">
                <i class="fas fa-edit"></i> Sửa
            </a>
            <form action="{{ route('categories.destroy', $category) }}"
                  method="POST"
                  class="d-inline"
                  onsubmit="return confirm('Bạn có chắc muốn xóa danh mục này?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger btn-sm">
                    <i class="fas fa-trash"></i> Xóa
                </button>
            </form>
        </div>
    @endif
</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">Không có danh mục nào</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-center mt-3">
                {{ $categories->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

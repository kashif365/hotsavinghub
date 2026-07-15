@extends('admin.layouts.app')
<style>
    table th, table td {
        font-size: 12px;
    }
</style>
@section('content')
<div class="container mt-4">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4>Manage Blog Categories</h4>
            <div>
                <button id="bulk-delete-btn" class="btn btn-danger btn-sm mx-3" disabled>Delete Selected</button>
                <a href="{{ route('admin.blog-categories.create') }}" class="btn btn-primary btn-sm">+ Add New Category</a>
            </div>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <table id="categoriesTable" class="table table-bordered table-striped align-middle w-100">
                <thead>
                    <tr>
                        <th width="30"><input type="checkbox" id="selectAll"></th>
                        <th width="30">☰</th>
                        <th>SL</th>
                        <th>Category Name</th>
                        <th>Color</th>
                        <th>Status</th>
                        <th>Blogs Count</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($categories as $category)
                        <tr data-id="{{ $category->id }}">
                            <td><input type="checkbox" value="{{ $category->id }}" class="rowCheckbox"></td>
                            <td class="reorder-handle" style="cursor:move;">☰</td>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="color-indicator me-2" style="width: 20px; height: 20px; background-color: {{ $category->color }}; border-radius: 50%;"></div>
                                    {{ $category->name }}
                                </div>
                                @if($category->description)
                                    <small class="text-muted">{{ Str::limit($category->description, 50) }}</small>
                                @endif
                            </td>
                            <td>
                                <span class="badge" style="background-color: {{ $category->color }}; color: white;">
                                    {{ $category->color }}
                                </span>
                            </td>
                            <td>
                                <span class="badge badge-{{ $category->status ? 'success' : 'danger' }}">
                                    {{ $category->status ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td>{{ $category->blogs()->count() }}</td>
                            <td>
                                <a href="{{ route('admin.blog-categories.edit', $category->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                <form action="{{ route('admin.blog-categories.destroy', $category->id) }}" method="POST" class="d-inline single-delete-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm delete-btn">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            {{-- Hidden form for bulk delete --}}
            <form id="bulkDeleteForm" action="{{ route('admin.blog-categories.bulkDelete') }}" method="POST" style="display:none;">
                @csrf
                @method('DELETE')
            </form>
        </div>
    </div>
</div>

<script>
$(document).ready(function () {
    initDataTableWithFeatures({
        tableSelector: '#categoriesTable',
        bulkDeleteBtnSelector: '#bulk-delete-btn',
        selectAllSelector: '#selectAll',
        rowHandleSelector: 'td.reorder-handle',
        reorderUrl: '{{ route("admin.blog-categories.reorder") }}',
        bulkDeleteUrl: '{{ route("admin.blog-categories.bulkDelete") }}',
        csrfToken: '{{ csrf_token() }}'
    });
});
</script>
@endsection

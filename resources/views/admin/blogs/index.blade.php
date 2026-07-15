@extends('admin.layouts.app')
<style>
    table th, table td {
        font-size: 12px;
    }
    
    .empty-state {
        padding: 40px 20px;
        text-align: center;
    }
    
    .empty-state i {
        font-size: 48px;
        opacity: 0.3;
        color: #6c757d;
    }
    
    .empty-state h5 {
        color: #495057;
        margin: 15px 0 10px 0;
    }
    
    .empty-state p {
        color: #6c757d;
        margin: 0;
    }
    
    .empty-state-row {
        background-color: #f8f9fa;
    }
    
    .empty-state-row:hover {
        background-color: #f8f9fa !important;
    }
</style>
@section('content')
<div class="container mt-4">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4>Manage Blogs</h4>
            <div>
                <button id="bulk-delete-btn" class="btn btn-danger btn-sm mx-3" disabled>Delete Selected</button>
                <a href="{{ route('admin.blogs.create') }}" class="btn btn-primary btn-sm">+ Add New Blog</a>
            </div>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <table id="blogsTable" class="table table-bordered table-striped align-middle w-100">
                <thead>
                    <tr>
                        <th width="30"><input type="checkbox" id="selectAll"></th>
                        <th width="30">☰</th>
                        <th>SL</th>
                        <th>Blog Title</th>
                        <th>Category</th>
                        <th>Author</th>
                        <th>Status</th>
                        <th>Views</th>
                        <th>Featured Image</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($blogs as $blog)
                        <tr data-id="{{ $blog->id }}">
                            <td><input type="checkbox" value="{{ $blog->id }}" class="rowCheckbox"></td>
                            <td class="reorder-handle" style="cursor:move;">☰</td>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $blog->title }}<br><small class="text-muted">{{ Str::limit($blog->excerpt, 50) }}</small></td>
                            <td>
                                @if($blog->category)
                                    <span class="badge" style="background-color: {{ $blog->category->color }}; color: white;">
                                        {{ $blog->category->name }}
                                    </span>
                                @else
                                    <span class="badge badge-secondary">No Category</span>
                                @endif
                            </td>
                            <td>{{ $blog->author }}</td>
                            <td>
                                <span class="badge badge-{{ $blog->status === 'published' ? 'success' : 'warning' }}">
                                    {{ ucfirst($blog->status) }}
                                </span>
                            </td>
                            <td>{{ number_format($blog->views_count) }}</td>
                            <td>
                                @if($blog->featured_image)
                                    <img src="{{ asset($blog->featured_image) }}" width="50" alt="{{ $blog->title }}">
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.blogs.edit', $blog->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                <form action="{{ route('admin.blogs.destroy', $blog->id) }}" method="POST" class="d-inline single-delete-form">
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
            <form id="bulkDeleteForm" action="{{ route('admin.blogs.bulkDelete') }}" method="POST" style="display:none;">
                @csrf
                @method('DELETE')
            </form>
        </div>
    </div>
</div>

<script>
$(document).ready(function () {
    initDataTableWithFeatures({
        tableSelector: '#blogsTable',
        bulkDeleteBtnSelector: '#bulk-delete-btn',
        selectAllSelector: '#selectAll',
        rowHandleSelector: 'td.reorder-handle',
        reorderUrl: '{{ route("admin.blogs.reorder") }}',
        csrfToken: '{{ csrf_token() }}',
        bulkDeleteUrl: '{{ route("admin.blogs.bulkDelete") }}' // Important for JS bulk delete
    });
});
</script>
@endsection

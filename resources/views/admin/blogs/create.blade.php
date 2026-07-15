@extends('admin.layouts.app')

@section('title', 'Create New Blog')

@section('content')
<div class="container mt-4">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4>Create New Blog</h4>
            <a href="{{ route('admin.blogs.index') }}" class="btn btn-sm btn-secondary">⬅ Back</a>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.blogs.store') }}" method="POST" enctype="multipart/form-data">
                @include('admin.blogs.form')
            </form>
        </div>
    </div>
</div>
@endsection

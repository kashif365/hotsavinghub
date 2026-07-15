@extends('admin.layouts.app')

@section('title', 'Edit Blog')

@section('content')
<div class="container mt-4">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4>Edit Blog: {{ $blog->title }}</h4>
            <a href="{{ route('admin.blogs.index') }}" class="btn btn-sm btn-secondary">⬅ Back</a>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.blogs.update', $blog) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                @include('admin.blogs.form')
            </form>
        </div>
    </div>
</div>
@endsection

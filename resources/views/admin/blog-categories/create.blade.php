@extends('admin.layouts.app')

@section('content')
<div class="container mt-4">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4>Add New Blog Category</h4>
            <a href="{{ route('admin.blog-categories.index') }}" class="btn btn-secondary btn-sm">← Back to Categories</a>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.blog-categories.store') }}" method="POST">
                @include('admin.blog-categories.form')
            </form>
        </div>
    </div>
</div>
@endsection

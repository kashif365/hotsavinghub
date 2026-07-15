@extends('admin.layouts.app')

@section('title', 'Edit Store')

@section('content')
<div class="container mt-4">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4>Edit Store</h4>
            <a href="{{ route('admin.stores.index') }}" class="btn btn-sm btn-secondary">⬅ Back</a>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.stores.update', $store->id) }}" method="POST" enctype="multipart/form-data">
                @method('PUT')
                @include('admin.stores.form')
            </form>
        </div>
    </div>
</div>
@endsection

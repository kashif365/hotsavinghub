@extends('admin.layouts.app')

@section('content')
<div class="container mt-4">
  <div class="card">
    <div class="card-header"><h4>Edit Category</h4></div>
    <div class="card-body">
      <form action="{{ route('admin.categories.update', $category->id) }}" method="POST" enctype="multipart/form-data">
        @method('PUT')
        @include('admin.categories.form', [
          'category' => $category,
          'categories' => $categories   {{-- yahan forward --}}
        ])
      </form>
    </div>
  </div>
</div>
@endsection

@extends('admin.layouts.app')

@section('content')
<div class="container mt-4">
  <div class="card">
    <div class="card-header"><h4>Add New Category</h4></div>
    <div class="card-body">
      <form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data">
        @include('admin.categories.form', [
          'category' => new \App\Models\Category,
          'categories' => $categories   {{-- yahan forward --}}
        ])
      </form>
    </div>
  </div>
</div>
@endsection

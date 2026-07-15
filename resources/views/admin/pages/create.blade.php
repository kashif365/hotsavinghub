@extends('admin.layouts.app')

@section('content')

<div class="container mt-4">
  <div class="card">
    <div class="card-header"><h4>Add New Page</h4></div>
    <div class="card-body">
      <form action="{{ route('admin.pages.store') }}" method="POST" enctype="multipart/form-data">
        @include('admin.pages.form', [
          'page' => new \App\Models\Page
        ])
      </form>
    </div>
  </div>
</div>
@endsection

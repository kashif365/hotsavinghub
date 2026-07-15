@extends('admin.layouts.app')

@section('content')
<div class="container mt-4">
  <div class="card">
    <div class="card-header"><h4>Edit Page</h4></div>
    <div class="card-body">
      <form action="{{ route('admin.pages.update', $page->id) }}" method="POST" enctype="multipart/form-data">
        @method('PUT')
        @include('admin.pages.form', [
          'page' => $page
        ])
      </form>
    </div>
  </div>
</div>
@endsection

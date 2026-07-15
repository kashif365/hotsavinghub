@extends('admin.layouts.app')

@section('content')
<div class="container mt-4">
  <div class="card">
    <div class="card-header">
      <h4>Create New Coupon</h4>
    </div>
    <div class="card-body">
      <form action="{{ route('admin.coupons.store') }}" method="POST" enctype="multipart/form-data">
        @include('admin.coupons.form', ['coupon' => new App\Models\Coupon])
      </form>
    </div>
  </div>
</div>
@endsection

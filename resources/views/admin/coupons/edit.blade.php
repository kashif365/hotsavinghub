@extends('admin.layouts.app')

@section('content')
<div class="container mt-4">
  <div class="card">
    <div class="card-header">
      <h4>Edit Coupon</h4>
    </div>
    <div class="card-body">
      <form action="{{ route('admin.coupons.update', $coupon->id) }}" method="POST" enctype="multipart/form-data">
        @method('PUT')
        @include('admin.coupons.form', ['coupon' => $coupon])
      </form>
    </div>
  </div>
</div>
@endsection

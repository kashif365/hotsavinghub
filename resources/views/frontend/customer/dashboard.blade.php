@extends('frontend.layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h4 class="mb-0">Customer Dashboard</h4>
                </div>
                <div class="card-body">
                    <p>Welcome, {{ Auth::guard('customer')->user()->name }}!</p>
                    <p>This is your customer dashboard. More features coming soon.</p>
                    
                    <form action="{{ route('customer.logout') }}" method="POST" class="mt-4">
                        @csrf
                        <button type="submit" class="btn btn-danger">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

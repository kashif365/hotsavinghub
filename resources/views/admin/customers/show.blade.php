@extends('admin.layouts.app')

@section('title', 'Customer Details')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Customer Details</h5>
                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.customers.edit', $customer) }}" class="btn btn-primary">
                            <i class="ri-edit-line me-1"></i>Edit Customer
                        </a>
                        <a href="{{ route('admin.customers.index') }}" class="btn btn-secondary">
                            <i class="ri-arrow-left-line me-1"></i>Back to List
                        </a>
                    </div>
                </div>
                
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="row mb-4">
                                <div class="col-sm-3">
                                    <strong>Customer ID:</strong>
                                </div>
                                <div class="col-sm-9">
                                    {{ $customer->id }}
                                </div>
                            </div>

                            <div class="row mb-4">
                                <div class="col-sm-3">
                                    <strong>Full Name:</strong>
                                </div>
                                <div class="col-sm-9">
                                    {{ $customer->name }}
                                </div>
                            </div>

                            <div class="row mb-4">
                                <div class="col-sm-3">
                                    <strong>Email Address:</strong>
                                </div>
                                <div class="col-sm-9">
                                    <a href="mailto:{{ $customer->email }}" class="text-decoration-none">
                                        {{ $customer->email }}
                                    </a>
                                </div>
                            </div>

                            <div class="row mb-4">
                                <div class="col-sm-3">
                                    <strong>Newsletter Subscription:</strong>
                                </div>
                                <div class="col-sm-9">
                                    @if($customer->is_subscribed)
                                        <span class="badge bg-success">Subscribed</span>
                                    @else
                                        <span class="badge bg-secondary">Not Subscribed</span>
                                    @endif
                                </div>
                            </div>

                            <div class="row mb-4">
                                <div class="col-sm-3">
                                    <strong>Account Status:</strong>
                                </div>
                                <div class="col-sm-9">
                                    @if($customer->status)
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-danger">Inactive</span>
                                    @endif
                                </div>
                            </div>

                            <div class="row mb-4">
                                <div class="col-sm-3">
                                    <strong>Registration Date:</strong>
                                </div>
                                <div class="col-sm-9">
                                    {{ $customer->created_at->format('F d, Y') }} at {{ $customer->created_at->format('h:i A') }}
                                </div>
                            </div>

                            <div class="row mb-4">
                                <div class="col-sm-3">
                                    <strong>Last Updated:</strong>
                                </div>
                                <div class="col-sm-9">
                                    {{ $customer->updated_at->format('F d, Y') }} at {{ $customer->updated_at->format('h:i A') }}
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="card-title mb-0">Quick Actions</h6>
                                </div>
                                <div class="card-body">
                                    <div class="d-grid gap-2">
                                        <a href="{{ route('admin.customers.edit', $customer) }}" class="btn btn-primary">
                                            <i class="ri-edit-line me-1"></i>Edit Customer
                                        </a>
                                        
                                        <form action="{{ route('admin.customers.update-status', $customer) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="{{ $customer->status ? 0 : 1 }}">
                                            <button type="submit" class="btn btn-{{ $customer->status ? 'warning' : 'success' }} w-100">
                                                <i class="ri-{{ $customer->status ? 'pause' : 'check' }}-line me-1"></i>
                                                {{ $customer->status ? 'Deactivate' : 'Activate' }} Account
                                            </button>
                                        </form>

                                        <form action="{{ route('admin.customers.destroy', $customer) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this customer? This action cannot be undone.')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger w-100">
                                                <i class="ri-delete-bin-line me-1"></i>Delete Customer
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <div class="card mt-3">
                                <div class="card-header">
                                    <h6 class="card-title mb-0">Account Information</h6>
                                </div>
                                <div class="card-body">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="avatar avatar-lg me-3">
                                            <span class="avatar-initial rounded-circle bg-primary fs-4">
                                                {{ strtoupper(substr($customer->name, 0, 1)) }}
                                            </span>
                                        </div>
                                        <div>
                                            <h6 class="mb-0">{{ $customer->name }}</h6>
                                            <small class="text-muted">{{ $customer->email }}</small>
                                        </div>
                                    </div>
                                    
                                    <div class="row text-center">
                                        <div class="col-6">
                                            <div class="border-end">
                                                <h6 class="mb-0">{{ $customer->created_at->diffForHumans() }}</h6>
                                                <small class="text-muted">Member Since</small>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <h6 class="mb-0">{{ $customer->updated_at->diffForHumans() }}</h6>
                                            <small class="text-muted">Last Updated</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection



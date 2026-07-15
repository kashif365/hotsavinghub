@extends('admin.layouts.app')

@section('title', 'User Details')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">User Details: {{ $user->name }}</h5>
                    <div class="d-flex gap-2">
                        @if($user->id !== auth()->id())
                            <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-primary">
                                <i class="ri-edit-line me-1"></i>Edit User
                            </a>
                        @endif
                        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
                            <i class="ri-arrow-left-line me-1"></i>Back to Users
                        </a>
                    </div>
                </div>
                
                <div class="card-body">
                    <div class="row">
                        <!-- User Profile -->
                        <div class="col-md-4">
                            <div class="card border-0 bg-light">
                                <div class="card-body text-center">
                                    <div class="avatar avatar-xl mb-3">
                                        @if($user->avatar)
                                            <img src="{{ asset( $user->avatar) }}" alt="{{ $user->name }}" class="rounded-circle">
                                        @else
                                            <span class="avatar-initial rounded-circle bg-primary fs-1">
                                                {{ strtoupper(substr($user->name, 0, 1)) }}
                                            </span>
                                        @endif
                                    </div>
                                    <h4 class="mb-1">{{ $user->name }}</h4>
                                    <p class="text-muted mb-3">{{ $user->email }}</p>
                                    
                                    @php
                                        $roleColors = [
                                            'admin' => 'danger',
                                            'user' => 'primary'
                                        ];
                                        $statusColors = [
                                            'active' => 'success',
                                            'inactive' => 'warning',
                                            'suspended' => 'danger'
                                        ];
                                    @endphp
                                    
                                    <div class="d-flex justify-content-center gap-2 mb-3">
                                        <span class="badge bg-{{ $roleColors[$user->role] }} fs-6">
                                            {{ ucfirst($user->role) }}
                                        </span>
                                        <span class="badge bg-{{ $statusColors[$user->status] }} fs-6">
                                            {{ ucfirst($user->status) }}
                                        </span>
                                    </div>
                                    
                                    @if($user->phone)
                                        <p class="mb-0">
                                            <i class="ri-phone-line me-1"></i>
                                            <a href="tel:{{ $user->phone }}" class="text-decoration-none">
                                                {{ $user->phone }}
                                            </a>
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- User Information -->
                        <div class="col-md-8">
                            <div class="card border-0 bg-light">
                                <div class="card-body">
                                    <h6 class="card-title text-primary mb-3">
                                        <i class="ri-user-line me-2"></i>User Information
                                    </h6>
                                    
                                    <div class="row">
                                        <div class="col-sm-6 mb-3">
                                            <strong>User ID:</strong><br>
                                            <span class="text-muted">#{{ $user->id }}</span>
                                        </div>
                                        <div class="col-sm-6 mb-3">
                                            <strong>Email:</strong><br>
                                            <a href="mailto:{{ $user->email }}" class="text-decoration-none">
                                                {{ $user->email }}
                                            </a>
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-sm-6 mb-3">
                                            <strong>Role:</strong><br>
                                            <span class="badge bg-{{ $roleColors[$user->role] }}">
                                                {{ ucfirst($user->role) }}
                                            </span>
                                        </div>
                                        <div class="col-sm-6 mb-3">
                                            <strong>Status:</strong><br>
                                            <span class="badge bg-{{ $statusColors[$user->status] }}">
                                                {{ ucfirst($user->status) }}
                                            </span>
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-sm-6 mb-3">
                                            <strong>Phone:</strong><br>
                                            @if($user->phone)
                                                <a href="tel:{{ $user->phone }}" class="text-decoration-none">
                                                    {{ $user->phone }}
                                                </a>
                                            @else
                                                <span class="text-muted">Not provided</span>
                                            @endif
                                        </div>
                                        <div class="col-sm-6 mb-3">
                                            <strong>Email Verified:</strong><br>
                                            @if($user->email_verified_at)
                                                <span class="badge bg-success">
                                                    <i class="ri-check-line me-1"></i>Verified
                                                </span>
                                            @else
                                                <span class="badge bg-warning">
                                                    <i class="ri-close-line me-1"></i>Not Verified
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-sm-6 mb-3">
                                            <strong>Created:</strong><br>
                                            <span class="text-muted">
                                                {{ $user->created_at->format('F d, Y') }}<br>
                                                {{ $user->created_at->format('h:i A') }}
                                            </span>
                                        </div>
                                        <div class="col-sm-6 mb-3">
                                            <strong>Last Updated:</strong><br>
                                            <span class="text-muted">
                                                {{ $user->updated_at->format('F d, Y') }}<br>
                                                {{ $user->updated_at->format('h:i A') }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card border-0 bg-light">
                                <div class="card-body">
                                    <h6 class="card-title text-primary mb-3">
                                        <i class="ri-settings-3-line me-2"></i>Actions
                                    </h6>
                                    
                                    <div class="d-flex flex-wrap gap-2">
                                        @if($user->id !== auth()->id())
                                            <!-- Edit Button -->
                                            <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-primary">
                                                <i class="ri-edit-line me-1"></i>Edit User
                                            </a>
                                            
                                            <!-- Status Update Forms -->
                                            @if($user->status !== 'active')
                                            <form action="{{ route('admin.users.update-status', $user) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="active">
                                                <button type="submit" class="btn btn-success">
                                                    <i class="ri-check-line me-1"></i>Activate
                                                </button>
                                            </form>
                                            @endif
                                            
                                            @if($user->status !== 'inactive')
                                            <form action="{{ route('admin.users.update-status', $user) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="inactive">
                                                <button type="submit" class="btn btn-warning">
                                                    <i class="ri-pause-line me-1"></i>Deactivate
                                                </button>
                                            </form>
                                            @endif
                                            
                                            @if($user->status !== 'suspended')
                                            <form action="{{ route('admin.users.update-status', $user) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="suspended">
                                                <button type="submit" class="btn btn-danger">
                                                    <i class="ri-stop-line me-1"></i>Suspend
                                                </button>
                                            </form>
                                            @endif
                                            
                                            <!-- Delete Button -->
                                            <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this user?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger">
                                                    <i class="ri-delete-bin-line me-1"></i>Delete
                                                </button>
                                            </form>
                                        @else
                                            <div class="alert alert-info mb-0">
                                                <i class="ri-information-line me-1"></i>
                                                This is your own account. You cannot perform certain actions on it.
                                            </div>
                                        @endif
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

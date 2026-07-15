@extends('admin.layouts.app')

@section('title', 'Contact Submission Details')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Contact Submission Details</h5>
                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.contacts.index') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="ri-arrow-left-line me-1"></i>Back to List
                        </a>
                        @php
                            $statusColors = [
                                'new' => 'danger',
                                'read' => 'warning', 
                                'replied' => 'success',
                                'closed' => 'secondary'
                            ];
                        @endphp
                        <span class="badge bg-{{ $statusColors[$contact->status] }} fs-6">
                            {{ ucfirst($contact->status) }}
                        </span>
                    </div>
                </div>
                
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <div class="row">
                        <!-- Contact Information -->
                        <div class="col-md-6">
                            <div class="card border-0 bg-light">
                                <div class="card-body">
                                    <h6 class="card-title text-primary mb-3">
                                        <i class="ri-user-line me-2"></i>Contact Information
                                    </h6>
                                    
                                    <div class="row mb-3">
                                        <div class="col-sm-4">
                                            <strong>Name:</strong>
                                        </div>
                                        <div class="col-sm-8">
                                            {{ $contact->full_name }}
                                        </div>
                                    </div>
                                    
                                    <div class="row mb-3">
                                        <div class="col-sm-4">
                                            <strong>Email:</strong>
                                        </div>
                                        <div class="col-sm-8">
                                            <a href="mailto:{{ $contact->email }}" class="text-decoration-none">
                                                {{ $contact->email }}
                                                <i class="ri-mail-line ms-1"></i>
                                            </a>
                                        </div>
                                    </div>
                                    
                                    @if($contact->phone)
                                    <div class="row mb-3">
                                        <div class="col-sm-4">
                                            <strong>Phone:</strong>
                                        </div>
                                        <div class="col-sm-8">
                                            <a href="tel:{{ $contact->phone }}" class="text-decoration-none">
                                                {{ $contact->phone }}
                                                <i class="ri-phone-line ms-1"></i>
                                            </a>
                                        </div>
                                    </div>
                                    @endif
                                    
                                    <div class="row mb-3">
                                        <div class="col-sm-4">
                                            <strong>Subject:</strong>
                                        </div>
                                        <div class="col-sm-8">
                                            <span class="badge bg-info">{{ ucfirst($contact->subject) }}</span>
                                        </div>
                                    </div>
                                    
                                    <div class="row mb-3">
                                        <div class="col-sm-4">
                                            <strong>Submitted:</strong>
                                        </div>
                                        <div class="col-sm-8">
                                            {{ $contact->created_at->format('F d, Y \a\t h:i A') }}
                                        </div>
                                    </div>
                                    
                                    @if($contact->updated_at != $contact->created_at)
                                    <div class="row mb-3">
                                        <div class="col-sm-4">
                                            <strong>Last Updated:</strong>
                                        </div>
                                        <div class="col-sm-8">
                                            {{ $contact->updated_at->format('F d, Y \a\t h:i A') }}
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Message Content -->
                        <div class="col-md-6">
                            <div class="card border-0 bg-light">
                                <div class="card-body">
                                    <h6 class="card-title text-primary mb-3">
                                        <i class="ri-message-3-line me-2"></i>Message
                                    </h6>
                                    
                                    <div class="bg-white p-3 rounded border">
                                        <p class="mb-0" style="white-space: pre-wrap;">{{ $contact->message }}</p>
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
                                        <!-- Reply Button -->
                                        <a href="mailto:{{ $contact->email }}?subject=Re: {{ ucfirst($contact->subject) }}" class="btn btn-primary">
                                            <i class="ri-reply-line me-1"></i>Reply via Email
                                        </a>
                                        
                                        <!-- Status Update Forms -->
                                        @if($contact->status !== 'read')
                                        <form action="{{ route('admin.contacts.update-status', $contact) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="read">
                                            <button type="submit" class="btn btn-warning">
                                                <i class="ri-eye-line me-1"></i>Mark as Read
                                            </button>
                                        </form>
                                        @endif
                                        
                                        @if($contact->status !== 'replied')
                                        <form action="{{ route('admin.contacts.update-status', $contact) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="replied">
                                            <button type="submit" class="btn btn-success">
                                                <i class="ri-check-line me-1"></i>Mark as Replied
                                            </button>
                                        </form>
                                        @endif
                                        
                                        @if($contact->status !== 'closed')
                                        <form action="{{ route('admin.contacts.update-status', $contact) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="closed">
                                            <button type="submit" class="btn btn-secondary">
                                                <i class="ri-archive-line me-1"></i>Mark as Closed
                                            </button>
                                        </form>
                                        @endif
                                        
                                        <!-- Delete Button -->
                                        <form action="{{ route('admin.contacts.destroy', $contact) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this contact submission?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger">
                                                <i class="ri-delete-bin-line me-1"></i>Delete
                                            </button>
                                        </form>
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

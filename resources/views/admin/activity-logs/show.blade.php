@extends('admin.layouts.app')

@section('title', 'Activity Log Details')

@section('content')
<div class="container mt-4">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4>Activity Log Details</h4>
            <a href="{{ route('admin.activity-logs.index') }}" class="btn btn-secondary btn-sm">
                <i class="ri-arrow-left-line"></i> Back to Logs
            </a>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h5>Basic Information</h5>
                    <table class="table table-bordered">
                        <tr>
                            <th>ID</th>
                            <td>{{ $activityLog->id }}</td>
                        </tr>
                        <tr>
                            <th>User</th>
                            <td>
                                @if($activityLog->user)
                                    <div class="d-flex align-items-center">
                                        <div class="avatar avatar-sm me-2">
                                            <span class="avatar-initial rounded-circle bg-label-primary">
                                                {{ substr($activityLog->user->name, 0, 1) }}
                                            </span>
                                        </div>
                                        <div>
                                            <div class="fw-medium">{{ $activityLog->user->name }}</div>
                                            <small class="text-muted">{{ $activityLog->user->email }}</small>
                                        </div>
                                    </div>
                                @else
                                    <span class="text-muted">System</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Action</th>
                            <td>
                                <span class="badge bg-{{ $activityLog->action_color }}">
                                    <i class="{{ $activityLog->action_icon }} me-1"></i>
                                    {{ ucfirst($activityLog->action) }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th>Description</th>
                            <td>{{ $activityLog->description }}</td>
                        </tr>
                        <tr>
                            <th>Model</th>
                            <td>
                                @if($activityLog->model_type)
                                    <span class="badge bg-light text-dark">
                                        {{ class_basename($activityLog->model_type) }}
                                        @if($activityLog->model_id)
                                            #{{ $activityLog->model_id }}
                                        @endif
                                    </span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Created At</th>
                            <td>
                                <div>{{ $activityLog->created_at->format('M d, Y H:i:s') }}</div>
                                <small class="text-muted">{{ $activityLog->time_ago }}</small>
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <h5>Request Information</h5>
                    <table class="table table-bordered">
                        <tr>
                            <th>IP Address</th>
                            <td>{{ $activityLog->ip_address }}</td>
                        </tr>
                        <tr>
                            <th>Method</th>
                            <td>
                                <span class="badge bg-{{ $activityLog->method == 'GET' ? 'info' : ($activityLog->method == 'POST' ? 'success' : ($activityLog->method == 'PUT' ? 'warning' : ($activityLog->method == 'DELETE' ? 'danger' : 'secondary'))) }}">
                                    {{ $activityLog->method }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th>URL</th>
                            <td>
                                <small class="text-break">{{ $activityLog->url }}</small>
                            </td>
                        </tr>
                        <tr>
                            <th>User Agent</th>
                            <td>
                                <small class="text-break">{{ $activityLog->user_agent }}</small>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

            @if($activityLog->old_values || $activityLog->new_values)
                <div class="row mt-4">
                    <div class="col-md-12">
                        <h5>Data Changes</h5>
                        <div class="row">
                            @if($activityLog->old_values)
                                <div class="col-md-6">
                                    <h6 class="text-danger">Old Values</h6>
                                    <div class="card">
                                        <div class="card-body">
                                            <pre class="mb-0">{{ json_encode($activityLog->old_values, JSON_PRETTY_PRINT) }}</pre>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            @if($activityLog->new_values)
                                <div class="col-md-6">
                                    <h6 class="text-success">New Values</h6>
                                    <div class="card">
                                        <div class="card-body">
                                            <pre class="mb-0">{{ json_encode($activityLog->new_values, JSON_PRETTY_PRINT) }}</pre>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

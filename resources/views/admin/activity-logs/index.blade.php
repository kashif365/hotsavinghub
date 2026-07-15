@extends('admin.layouts.app')

@section('title', 'Activity Logs')

@section('content')
<div class="container mt-4">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4>Activity Logs</h4>
            <div>
                <a href="{{ route('admin.activity-logs.export', request()->query()) }}" class="btn btn-info btn-sm me-2">
                    <i class="ri-download-line"></i> Export
                </a>
                <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#cleanupModal">
                    <i class="ri-delete-bin-line"></i> Cleanup
                </button>
            </div>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <!-- Statistics Cards -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card bg-primary text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6 class="card-title">Total Activities</h6>
                                    <h3>{{ number_format($statistics['total_activities']) }}</h3>
                                </div>
                                <div class="align-self-center">
                                    <i class="ri-activity-line fs-1"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-success text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6 class="card-title">Active Users</h6>
                                    <h3>{{ number_format($statistics['unique_users']) }}</h3>
                                </div>
                                <div class="align-self-center">
                                    <i class="ri-user-line fs-1"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-info text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6 class="card-title">Most Active</h6>
                                    <h6>{{ $statistics['most_active_user']->user->name ?? 'N/A' }}</h6>
                                    <small>{{ $statistics['most_active_user']->count ?? 0 }} activities</small>
                                </div>
                                <div class="align-self-center">
                                    <i class="ri-star-line fs-1"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-warning text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6 class="card-title">Actions</h6>
                                    <h3>{{ $statistics['activities_by_action']->count() }}</h3>
                                </div>
                                <div class="align-self-center">
                                    <i class="ri-list-check fs-1"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filters -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5>Filters</h5>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('admin.activity-logs.index') }}">
                        <div class="row">
                            <div class="col-md-2">
                                <label>User</label>
                                <select name="user_id" class="form-select">
                                    <option value="">All Users</option>
                                    @foreach($allUsers as $user)
                                        <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                            {{ $user->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label>Action</label>
                                <select name="action" class="form-select">
                                    <option value="">All Actions</option>
                                    @foreach($actions as $action)
                                        <option value="{{ $action }}" {{ request('action') == $action ? 'selected' : '' }}>
                                            {{ ucfirst($action) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label>Model Type</label>
                                <select name="model_type" class="form-select">
                                    <option value="">All Models</option>
                                    @foreach($modelTypes as $modelType)
                                        <option value="{{ $modelType }}" {{ request('model_type') == $modelType ? 'selected' : '' }}>
                                            {{ class_basename($modelType) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label>Date From</label>
                                <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
                            </div>
                            <div class="col-md-2">
                                <label>Date To</label>
                                <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
                            </div>
                            <div class="col-md-2">
                                <label>Search</label>
                                <input type="text" name="search" class="form-control" placeholder="Search description..." value="{{ request('search') }}">
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary">Filter</button>
                                <a href="{{ route('admin.activity-logs.index') }}" class="btn btn-secondary">Clear</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Users Activity Table -->
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Total Activities</th>
                            <th>Page Visits</th>
                            <th>Link Clicks</th>
                            <th>Created</th>
                            <th>Updated</th>
                            <th>Deleted</th>
                            <th>First Activity</th>
                            <th>Last Activity</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $userActivity)
                            <tr>
                                <td>
                                    @if($userActivity->user)
                                        <div class="d-flex align-items-center">
                                            <div class="avatar avatar-sm me-2">
                                                <span class="avatar-initial rounded-circle bg-label-primary">
                                                    {{ substr($userActivity->user->name, 0, 1) }}
                                                </span>
                                            </div>
                                            <div>
                                                <div class="fw-medium">{{ $userActivity->user->name }}</div>
                                                <small class="text-muted">{{ $userActivity->user->email }}</small>
                                                <br>
                                                <span class="badge bg-light text-dark">{{ ucfirst($userActivity->user->role) }}</span>
                                            </div>
                                        </div>
                                    @else
                                        <span class="text-muted">Unknown User</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-primary fs-6">{{ number_format($userActivity->total_activities) }}</span>
                                </td>
                                <td>
                                    <span class="badge bg-info">{{ number_format($userActivity->page_visits) }}</span>
                                </td>
                                <td>
                                    <span class="badge bg-success">{{ number_format($userActivity->link_clicks) }}</span>
                                </td>
                                <td>
                                    <span class="badge bg-success">{{ number_format($userActivity->created_items) }}</span>
                                </td>
                                <td>
                                    <span class="badge bg-warning">{{ number_format($userActivity->updated_items) }}</span>
                                </td>
                                <td>
                                    <span class="badge bg-danger">{{ number_format($userActivity->deleted_items) }}</span>
                                </td>
                                <td>
                                    <div>{{ \Carbon\Carbon::parse($userActivity->first_activity)->format('M d, Y') }}</div>
                                    <small class="text-muted">{{ \Carbon\Carbon::parse($userActivity->first_activity)->format('H:i:s') }}</small>
                                </td>
                                <td>
                                    <div>{{ \Carbon\Carbon::parse($userActivity->last_activity)->format('M d, Y') }}</div>
                                    <small class="text-muted">{{ \Carbon\Carbon::parse($userActivity->last_activity)->format('H:i:s') }}</small>
                                </td>
                                <td>
                                    <a href="{{ route('admin.activity-logs.user', $userActivity->user_id) }}" class="btn btn-sm btn-primary" title="View all activities for this user">
                                        <i class="ri-eye-line"></i> View All Activities
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="ri-inbox-line fs-1"></i>
                                        <p>No users with activity logs found</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center">
                {{ $users->links() }}
            </div>
        </div>
    </div>
</div>

<!-- Cleanup Modal -->
<div class="modal fade" id="cleanupModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Cleanup Activity Logs</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('admin.activity-logs.cleanup') }}">
                @csrf
                <div class="modal-body">
                    <p>This will permanently delete activity logs older than the specified number of days.</p>
                    <div class="mb-3">
                        <label for="days" class="form-label">Delete logs older than (days):</label>
                        <input type="number" class="form-control" id="days" name="days" value="90" min="1" max="365">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning">Cleanup</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

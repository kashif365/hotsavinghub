@extends('admin.layouts.app')

@section('title', 'User Activity Logs - ' . $user->name)

@section('content')
<div class="container mt-4">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div>
                <h4>Activity Logs for {{ $user->name }}</h4>
                <small class="text-muted">{{ $user->email }}</small>
            </div>
            <div>
                <a href="{{ route('admin.activity-logs.index') }}" class="btn btn-secondary btn-sm">
                    <i class="ri-arrow-left-line"></i> Back to All Logs
                </a>
            </div>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <!-- User Statistics Cards -->
            <div class="row mb-4">
                <div class="col-md-2">
                    <div class="card bg-primary text-white">
                        <div class="card-body text-center">
                            <h6 class="card-title">Total Activities</h6>
                            <h3>{{ number_format($userStats['total_activities']) }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="card bg-info text-white">
                        <div class="card-body text-center">
                            <h6 class="card-title">Page Visits</h6>
                            <h3>{{ number_format($userStats['page_visits']) }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="card bg-success text-white">
                        <div class="card-body text-center">
                            <h6 class="card-title">Link Clicks</h6>
                            <h3>{{ number_format($userStats['link_clicks']) }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-warning text-white">
                        <div class="card-body text-center">
                            <h6 class="card-title">Last Activity</h6>
                            <small>{{ $userStats['last_activity'] ? $userStats['last_activity']->time_ago : 'Never' }}</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-secondary text-white">
                        <div class="card-body text-center">
                            <h6 class="card-title">User Role</h6>
                            <span class="badge bg-light text-dark">{{ ucfirst($user->role) }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Most Visited Pages & Clicked Links -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="card-title mb-0">Most Visited Pages</h6>
                        </div>
                        <div class="card-body">
                            @forelse($userStats['most_visited_pages'] as $page)
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span>{{ $page->page_title }}</span>
                                    <span class="badge bg-info">{{ $page->count }} times</span>
                                </div>
                            @empty
                                <p class="text-muted">No page visits recorded</p>
                            @endforelse
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="card-title mb-0">Most Clicked Links</h6>
                        </div>
                        <div class="card-body">
                            @forelse($userStats['most_clicked_links'] as $link)
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span>{{ $link->link_text }}</span>
                                    <span class="badge bg-success">{{ $link->count }} times</span>
                                </div>
                            @empty
                                <p class="text-muted">No link clicks recorded</p>
                            @endforelse
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
                    <form method="GET" action="{{ route('admin.activity-logs.user', $user->id) }}">
                        <div class="row">
                            <div class="col-md-3">
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
                            <div class="col-md-3">
                                <label>Date From</label>
                                <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
                            </div>
                            <div class="col-md-3">
                                <label>Date To</label>
                                <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
                            </div>
                            <div class="col-md-3">
                                <label>Search</label>
                                <input type="text" name="search" class="form-control" placeholder="Search description..." value="{{ request('search') }}">
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary">Filter</button>
                                <a href="{{ route('admin.activity-logs.user', $user->id) }}" class="btn btn-secondary">Clear</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Activity Logs Table -->
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Action</th>
                            <th>Description</th>
                            <th>Details</th>
                            <th>IP Address</th>
                            <th>Date & Time</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($logs as $log)
                            <tr>
                                <td>{{ $log->id }}</td>
                                <td>
                                    <span class="badge bg-{{ $log->action_color }}">
                                        <i class="{{ $log->action_icon }} me-1"></i>
                                        {{ ucfirst($log->action) }}
                                    </span>
                                </td>
                                <td>{{ $log->description }}</td>
                                <td>
                                    @if($log->action == 'view' && isset($log->new_values['page_title']))
                                        <div class="text-truncate" style="max-width: 200px;" title="{{ $log->new_values['url'] ?? '' }}">
                                            <strong>Page:</strong> {{ $log->new_values['page_title'] }}<br>
                                            <small class="text-muted">{{ $log->new_values['url'] ?? '' }}</small>
                                        </div>
                                    @elseif($log->action == 'click' && isset($log->new_values['text']))
                                        <div class="text-truncate" style="max-width: 200px;" title="{{ $log->new_values['url'] ?? '' }}">
                                            <strong>Link:</strong> {{ $log->new_values['text'] }}<br>
                                            <small class="text-muted">{{ $log->new_values['url'] ?? '' }}</small>
                                        </div>
                                    @elseif($log->model_type)
                                        <span class="badge bg-light text-dark">
                                            {{ class_basename($log->model_type) }}
                                            @if($log->model_id)
                                                #{{ $log->model_id }}
                                            @endif
                                        </span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    <small class="text-muted">{{ $log->ip_address }}</small>
                                </td>
                                <td>
                                    <div>{{ $log->created_at->format('M d, Y') }}</div>
                                    <small class="text-muted">{{ $log->created_at->format('H:i:s') }}</small>
                                </td>
                                <td>
                                    <a href="{{ route('admin.activity-logs.show', $log) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="ri-eye-line"></i> View Details
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="ri-inbox-line fs-1"></i>
                                        <p>No activity logs found for this user</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center">
                {{ $logs->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

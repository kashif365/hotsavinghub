@extends('admin.layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="row">
    <!-- Statistics Cards -->
    <div class="col-lg-3 col-md-6 col-12 mb-4">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div class="card-info">
                        <p class="card-text">Total Coupons</p>
                        <div class="d-flex align-items-center mb-1">
                            <h4 class="mb-0 me-1">{{ \App\Models\Coupon::count() }}</h4>
                        </div>
                    </div>
                    <div class="card-icon">
                        <span class="badge bg-label-primary rounded p-2">
                            <i class="ri-coupon-line ri-24px"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6 col-12 mb-4">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div class="card-info">
                        <p class="card-text">Total Stores</p>
                        <div class="d-flex align-items-center mb-1">
                            <h4 class="mb-0 me-1">{{ \App\Models\Store::count() }}</h4>
                        </div>
                    </div>
                    <div class="card-icon">
                        <span class="badge bg-label-success rounded p-2">
                            <i class="ri-store-line ri-24px"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6 col-12 mb-4">
        <a href="{{ route('admin.newsletters.index') }}" class="text-decoration-none">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div class="card-info">
                            <p class="card-text">Newsletter Subscribers</p>
                            <div class="d-flex align-items-center mb-1">
                                <h4 class="mb-0 me-1">{{ $newsletterCount ?? 0 }}</h4>
                            </div>
                        </div>
                        <div class="card-icon">
                            <span class="badge bg-label-info rounded p-2">
                                <i class="ri-mail-line ri-24px"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <div class="col-lg-3 col-md-6 col-12 mb-4">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div class="card-info">
                        <p class="card-text">Total Categories</p>
                        <div class="d-flex align-items-center mb-1">
                            <h4 class="mb-0 me-1">{{ \App\Models\Category::count() }}</h4>
                        </div>
                    </div>
                    <div class="card-icon">
                        <span class="badge bg-label-info rounded p-2">
                            <i class="ri-folder-line ri-24px"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6 col-12 mb-4">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div class="card-info">
                        <p class="card-text">Total Customers</p>
                        <div class="d-flex align-items-center mb-1">
                            <h4 class="mb-0 me-1">{{ \App\Models\Customer::count() }}</h4>
                        </div>
                    </div>
                    <div class="card-icon">
                        <span class="badge bg-label-warning rounded p-2">
                            <i class="ri-user-line ri-24px"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Recent Coupons -->
    <div class="col-lg-8 col-12 mb-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Recent Coupons</h5>
                <a href="{{ route('admin.coupons.index') }}" class="btn btn-sm btn-primary">View All</a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Store</th>
                                <th>Code</th>
                                <th>Status</th>
                                <th>Created</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse(\App\Models\Coupon::with('store')->latest()->take(5)->get() as $coupon)
                            <tr>
                                <td>{{ $coupon->coupon_title }}</td>
                                <td>{{ $coupon->brand_store }}</td>
                                <td>
                                    @if($coupon->coupon_code)
                                        <span class="badge bg-label-info">{{ $coupon->coupon_code }}</span>
                                    @else
                                        <span class="text-muted">No Code</span>
                                    @endif
                                </td>
                                <td>
                                    @if($coupon->status)
                                        <span class="badge bg-label-success">Active</span>
                                    @else
                                        <span class="badge bg-label-danger">Inactive</span>
                                    @endif
                                </td>
                                <td>{{ $coupon->created_at->format('M d, Y') }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center">No coupons found</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="col-lg-4 col-12 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Quick Actions</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('admin.coupons.create') }}" class="btn btn-primary">
                        <i class="ri-add-line me-1"></i> Add New Coupon
                    </a>
                    <a href="{{ route('admin.stores.create') }}" class="btn btn-success">
                        <i class="ri-store-line me-1"></i> Add New Store
                    </a>
                    <a href="{{ route('admin.categories.create') }}" class="btn btn-info">
                        <i class="ri-folder-add-line me-1"></i> Add New Category
                    </a>
                    <a href="{{ route('admin.events.create') }}" class="btn btn-warning">
                        <i class="ri-calendar-event-line me-1"></i> Add New Event
                    </a>
                    <a href="{{ route('admin.customers.index') }}" class="btn btn-secondary">
                        <i class="ri-user-line me-1"></i> Manage Customers
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Recent Customers -->
    <div class="col-lg-6 col-12 mb-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Recent Customers</h5>
                <a href="{{ route('admin.customers.index') }}" class="btn btn-sm btn-primary">View All</a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Subscribed</th>
                                <th>Status</th>
                                <th>Joined</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse(\App\Models\Customer::latest()->take(5)->get() as $customer)
                            <tr>
                                <td>{{ $customer->name }}</td>
                                <td>{{ $customer->email }}</td>
                                <td>
                                    @if($customer->is_subscribed)
                                        <span class="badge bg-label-success">Yes</span>
                                    @else
                                        <span class="badge bg-label-secondary">No</span>
                                    @endif
                                </td>
                                <td>
                                    @if($customer->status)
                                        <span class="badge bg-label-success">Active</span>
                                    @else
                                        <span class="badge bg-label-danger">Inactive</span>
                                    @endif
                                </td>
                                <td>{{ $customer->created_at->format('M d, Y') }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center">No customers found</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Active Events -->
    <div class="col-lg-6 col-12 mb-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Active Events</h5>
                <a href="{{ route('admin.events.index') }}" class="btn btn-sm btn-primary">View All</a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Event Name</th>
                                <th>Type</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse(\App\Models\Events::where('status', 1)->latest()->take(5)->get() as $event)
                            <tr>
                                <td>{{ $event->event_name }}</td>
                                <td>{{ $event->event_type }}</td>
                                <td>{{ $event->start_date }}</td>
                                <td>{{ $event->end_date }}</td>
                                <td>
                                    @if($event->status)
                                        <span class="badge bg-label-success">Active</span>
                                    @else
                                        <span class="badge bg-label-danger">Inactive</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center">No active events found</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Top Stores -->
    <div class="col-12 mb-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Top Stores</h5>
                <a href="{{ route('admin.stores.index') }}" class="btn btn-sm btn-primary">View All</a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Store Name</th>
                                <th>Coupons Count</th>
                                <th>Status</th>
                                <th>Featured</th>
                                <th>Created</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse(\App\Models\Store::withCount('coupons')->latest()->take(10)->get() as $store)
                            <tr>
                                <td>{{ $store->store_name }}</td>
                                <td>
                                    <span class="badge bg-label-primary">{{ $store->coupons_count }}</span>
                                </td>
                                <td>
                                    @if($store->status)
                                        <span class="badge bg-label-success">Active</span>
                                    @else
                                        <span class="badge bg-label-danger">Inactive</span>
                                    @endif
                                </td>
                                <td>
                                    @if($store->featured)
                                        <span class="badge bg-label-warning">Featured</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>{{ $store->created_at->format('M d, Y') }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center">No stores found</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity Logs -->
    <div class="col-lg-6 col-12 mb-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Recent Activity Logs</h5>
                <a href="{{ route('admin.activity-logs.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>User</th>
                                <th>Action</th>
                                <th>Description</th>
                                <th>Time</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse(\App\Models\ActivityLog::with('user')->latest()->take(10)->get() as $log)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar avatar-sm me-2">
                                            <span class="avatar-initial rounded-circle bg-label-primary">
                                                {{ substr($log->user_name, 0, 1) }}
                                            </span>
                                        </div>
                                        <div>
                                            <div class="fw-semibold">{{ $log->user_name }}</div>
                                            <small class="text-muted">{{ $log->user->email ?? 'System' }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-label-{{ $log->action_color }}">
                                        <i class="ri-{{ $log->action_icon }} me-1"></i>
                                        {{ ucfirst($log->action) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="text-truncate" style="max-width: 200px;" title="{{ $log->description }}">
                                        {{ $log->description }}
                                    </div>
                                </td>
                                <td>
                                    <small class="text-muted">{{ $log->time_ago }}</small>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center">No recent activity</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- User Navigation Tracking -->
    <div class="col-lg-6 col-12 mb-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">User Navigation Tracking</h5>
                <div class="btn-group btn-group-sm" role="group">
                    <button type="button" class="btn btn-outline-primary active" onclick="showPageVisits()">Page Visits</button>
                    <button type="button" class="btn btn-outline-primary" onclick="showLinkClicks()">Link Clicks</button>
                </div>
            </div>
            <div class="card-body">
                <!-- Page Visits Tab -->
                <div id="page-visits-tab">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>User</th>
                                    <th>Page</th>
                                    <th>URL</th>
                                    <th>Time</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse(\App\Models\ActivityLog::with('user')->where('action', 'view')->whereNotNull('new_values->page_title')->latest()->take(10)->get() as $log)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar avatar-sm me-2">
                                                <span class="avatar-initial rounded-circle bg-label-info">
                                                    {{ substr($log->user_name, 0, 1) }}
                                                </span>
                                            </div>
                                            <div>
                                                <div class="fw-semibold">{{ $log->user_name }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-label-info">
                                            <i class="ri-eye-line me-1"></i>
                                            {{ $log->new_values['page_title'] ?? 'Unknown' }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="text-truncate" style="max-width: 150px;" title="{{ $log->new_values['url'] ?? '' }}">
                                            {{ $log->new_values['url'] ?? '' }}
                                        </div>
                                    </td>
                                    <td>
                                        <small class="text-muted">{{ $log->time_ago }}</small>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center">No page visits recorded</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Link Clicks Tab -->
                <div id="link-clicks-tab" style="display: none;">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>User</th>
                                    <th>Link Text</th>
                                    <th>Destination</th>
                                    <th>Time</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse(\App\Models\ActivityLog::with('user')->where('action', 'click')->latest()->take(10)->get() as $log)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar avatar-sm me-2">
                                                <span class="avatar-initial rounded-circle bg-label-success">
                                                    {{ substr($log->user_name, 0, 1) }}
                                                </span>
                                            </div>
                                            <div>
                                                <div class="fw-semibold">{{ $log->user_name }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-label-success">
                                            <i class="ri-links-line me-1"></i>
                                            {{ $log->new_values['text'] ?? 'Unknown' }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="text-truncate" style="max-width: 150px;" title="{{ $log->new_values['url'] ?? '' }}">
                                            {{ $log->new_values['url'] ?? '' }}
                                        </div>
                                    </td>
                                    <td>
                                        <small class="text-muted">{{ $log->time_ago }}</small>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center">No link clicks recorded</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function showPageVisits() {
    document.getElementById('page-visits-tab').style.display = 'block';
    document.getElementById('link-clicks-tab').style.display = 'none';
    
    // Update button states
    document.querySelectorAll('.btn-group .btn').forEach(btn => btn.classList.remove('active'));
    event.target.classList.add('active');
}

function showLinkClicks() {
    document.getElementById('page-visits-tab').style.display = 'none';
    document.getElementById('link-clicks-tab').style.display = 'block';
    
    // Update button states
    document.querySelectorAll('.btn-group .btn').forEach(btn => btn.classList.remove('active'));
    event.target.classList.add('active');
}
</script>
@endsection

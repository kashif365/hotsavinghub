@extends('admin.layouts.app')

@section('content')
<div class="container mt-4">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4>Manage Events</h4>
            <div>
                <button id="bulk-delete-btn" class="btn btn-danger btn-sm mx-3" disabled>Delete Selected</button>
                <a href="{{ route('admin.events.create') }}" class="btn btn-primary btn-sm">+ Add New Event</a>
            </div>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <!-- Filter Controls -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <label class="form-label">Search Event</label>
                    <input type="text" id="searchInput" class="form-control" placeholder="Search by name, type...">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Status</label>
                    <select id="statusFilter" class="form-select">
                        <option value="">All Status</option>
                        <option value="1">Active</option>
                        <option value="0">Inactive</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Type</label>
                    <select id="typeFilter" class="form-select">
                        <option value="">All Types</option>
                        <option value="Sale">Sale</option>
                        <option value="Holiday">Holiday</option>
                        <option value="Seasonal">Seasonal</option>
                        <option value="Special">Special</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Show Footer</label>
                    <select id="footerFilter" class="form-select">
                        <option value="">All</option>
                        <option value="1">Yes</option>
                        <option value="0">No</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Date Range</label>
                    <select id="dateFilter" class="form-select">
                        <option value="">All Dates</option>
                        <option value="active">Currently Active</option>
                        <option value="upcoming">Upcoming</option>
                        <option value="expired">Expired</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Views</label>
                    <select id="viewsFilter" class="form-select">
                        <option value="">All Views</option>
                        <option value="popular">Popular (100+)</option>
                        <option value="trending">Trending (50+)</option>
                        <option value="new">New (0-10)</option>
                    </select>
                </div>
                <div class="col-md-1">
                    <label class="form-label">&nbsp;</label>
                    <button id="clearFilters" class="btn btn-outline-secondary w-100">Clear</button>
                </div>
            </div>

            <table id="eventsTable" class="table table-bordered table-striped align-middle w-100">
                <thead>
                    <tr>
                        <th width="30"><input type="checkbox" id="selectAll"></th>
                        <th width="30">☰</th>
                        <th>SL</th>
                        <th>Event Details</th>
                        <th>Type</th>
                        <th>Dates</th>
                        <th>Images</th>
                        <th>Stats</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($events as $event)
                        <tr data-id="{{ $event->id }}" 
                            data-status="{{ $event->status }}"
                            data-event-type="{{ strtolower($event->event_type ?? '') }}"
                            data-show-footer="{{ $event->show_footer }}"
                            data-event-name="{{ strtolower($event->event_name) }}"
                            data-date-available="{{ $event->date_available }}"
                            data-date-expiry="{{ $event->date_expiry }}"
                            data-views-count="{{ $event->views_count ?? 0 }}">
                            <td><input type="checkbox" value="{{ $event->id }}" class="rowCheckbox"></td>
                            <td class="reorder-handle" style="cursor:move;">☰</td>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                <div>
                                    <strong>{{ $event->event_name }}</strong>
                                    @if($event->seo_url)
                                        <br><small class="text-info">/{{ $event->seo_url }}</small>
                                    @endif
                                    @if($event->event_short_content)
                                        <br><small class="text-muted">{{ Str::limit($event->event_short_content, 50) }}</small>
                                    @endif
                                </div>
                            </td>
                            <td>
                                @if($event->event_type)
                                    <span class="badge bg-info">{{ $event->event_type }}</span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                <div>
                                    @if($event->date_available)
                                    <span class="badge bg-success">From: {{ \Carbon\Carbon::parse($event->date_available)->format('M d, Y') }}</span>
                                    @endif
                                    @if($event->date_expiry)
                                    <br><br><span class="badge bg-danger">Until: {{ \Carbon\Carbon::parse($event->date_expiry)->format('M d, Y') }}</span>
                                    @endif
                                    @if(!$event->date_available && !$event->date_expiry)
                                        <span class="text-muted">No dates set</span>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <div class="d-flex gap-1">
                                    @if($event->front_image)
                                        <img src="{{ asset($event->front_image) }}" width="30" height="30" alt="Front" class="img-thumbnail" title="Front Image">
                                    @endif
                                    @if($event->cover_image)
                                        <img src="{{ asset($event->cover_image) }}" width="30" height="30" alt="Cover" class="img-thumbnail" title="Cover Image">
                                    @endif
                                    @if($event->button_icon)
                                        <img src="{{ asset($event->button_icon) }}" width="30" height="30" alt="Icon" class="img-thumbnail" title="Button Icon">
                                    @endif
                                    @if(!$event->front_image && !$event->cover_image && !$event->button_icon)
                                        <div class="bg-light d-flex align-items-center justify-content-center" style="width: 30px; height: 30px; border-radius: 4px;">
                                            <i class="ri-image-line text-muted"></i>
                                        </div>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <div>
                                <span class="badge bg-primary">Coupons: {{ $event->coupons->count() }}</span>
                                    <br><br><span class="badge bg-info">Stores: {{ $event->stores->count() }}</span>
                                    @if($event->show_footer)
                                        <br><span class="badge bg-secondary">Footer</span>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <div>
                                    @if($event->status)
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-danger">Inactive</span>
                                    @endif
                                    <br><br><span class="badge bg-info">Views: {{ $event->views_count ?? 0 }}</span>
                                </div>
                            </td>
                            <td>
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                        Actions
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <a class="dropdown-item" href="{{ route('event.detail', $event->seo_url) }}" target="_blank">
                                                <i class="ri-eye-line me-2"></i>View Details
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="{{ route('admin.events.edit', $event->id) }}">
                                                <i class="ri-edit-line me-2"></i>Edit Event
                                            </a>
                                        </li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li>
                                            <form action="{{ route('admin.events.update-status', $event->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="{{ $event->status ? 0 : 1 }}">
                                                <button type="submit" class="dropdown-item">
                                                    @if($event->status)
                                                        <i class="ri-pause-line me-2"></i>Deactivate
                                                    @else
                                                        <i class="ri-check-line me-2"></i>Activate
                                                    @endif
                                                </button>
                                            </form>
                                        </li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li>
                                            <form action="{{ route('admin.events.destroy', $event->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this event?')">
                                    @csrf
                                    @method('DELETE')
                                                <button type="submit" class="dropdown-item text-danger">
                                                    <i class="ri-delete-bin-line me-2"></i>Delete
                                                </button>
                                </form>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            {{-- Hidden form for bulk delete --}}
            <form id="bulkDeleteForm" action="{{ route('admin.events.bulkDelete') }}" method="POST" style="display:none;">
                @csrf
                @method('DELETE')
            </form>
        </div>
    </div>
</div>

<script>
$(document).ready(function () {
    initDataTableWithFeatures({
        tableSelector: '#eventsTable',
        bulkDeleteBtnSelector: '#bulk-delete-btn',
        selectAllSelector: '#selectAll',
        rowHandleSelector: 'td.reorder-handle',
        reorderUrl: '{{ route("admin.events.reorder") }}',
        csrfToken: '{{ csrf_token() }}',
        bulkDeleteUrl: '{{ route("admin.events.bulkDelete") }}' // Important for JS bulk delete
    });

    // Filter functionality
    function filterTable() {
        const searchTerm = $('#searchInput').val().toLowerCase();
        const statusFilter = $('#statusFilter').val();
        const typeFilter = $('#typeFilter').val();
        const footerFilter = $('#footerFilter').val();
        const dateFilter = $('#dateFilter').val();
        const viewsFilter = $('#viewsFilter').val();

        $('#eventsTable tbody tr').each(function() {
            const row = $(this);
            const eventName = row.data('event-name') || '';
            const eventType = row.data('event-type') || '';
            const status = row.data('status').toString();
            const showFooter = row.data('show-footer').toString();
            const dateAvailable = row.data('date-available');
            const dateExpiry = row.data('date-expiry');
            const viewsCount = parseInt(row.data('views-count') || 0);

            let showRow = true;

            // Search filter
            if (searchTerm && !eventName.includes(searchTerm) && !eventType.includes(searchTerm)) {
                showRow = false;
            }

            // Status filter
            if (statusFilter && status !== statusFilter) {
                showRow = false;
            }

            // Type filter
            if (typeFilter && eventType !== typeFilter.toLowerCase()) {
                showRow = false;
            }

            // Footer filter
            if (footerFilter && showFooter !== footerFilter) {
                showRow = false;
            }

            // Date filter
            if (dateFilter) {
                const today = new Date();
                const availableDate = dateAvailable ? new Date(dateAvailable) : null;
                const expiryDate = dateExpiry ? new Date(dateExpiry) : null;

                if (dateFilter === 'active') {
                    if (!availableDate || availableDate > today || (expiryDate && expiryDate < today)) {
                        showRow = false;
                    }
                } else if (dateFilter === 'upcoming') {
                    if (!availableDate || availableDate <= today) {
                        showRow = false;
                    }
                } else if (dateFilter === 'expired') {
                    if (!expiryDate || expiryDate >= today) {
                        showRow = false;
                    }
                }
            }

            // Views filter
            if (viewsFilter) {
                if (viewsFilter === 'popular' && viewsCount < 100) {
                    showRow = false;
                } else if (viewsFilter === 'trending' && viewsCount < 50) {
                    showRow = false;
                } else if (viewsFilter === 'new' && viewsCount > 10) {
                    showRow = false;
                }
            }

            if (showRow) {
                row.show();
            } else {
                row.hide();
            }
        });
    }

    // Bind filter events
    $('#searchInput, #statusFilter, #typeFilter, #footerFilter, #dateFilter, #viewsFilter').on('input change', filterTable);

    // Clear filters
    $('#clearFilters').click(function() {
        $('#searchInput').val('');
        $('#statusFilter').val('');
        $('#typeFilter').val('');
        $('#footerFilter').val('');
        $('#dateFilter').val('');
        $('#viewsFilter').val('');
        filterTable();
    });
});
</script>
@endsection

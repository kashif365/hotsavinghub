@extends('admin.layouts.app')
<style>
    table th, table td {
        font-size: 12px;
    }
    
    .btn-group .btn {
        margin-right: 5px;
    }
    
    .btn-group .btn:last-child {
        margin-right: 0;
    }
    
    .img-thumbnail {
        border-radius: 4px;
        object-fit: cover;
    }
    
    .badge {
        font-size: 10px;
    }
    
    .reorder-handle {
        cursor: move !important;
    }
    
    .reorder-handle:hover {
        background-color: #f8f9fa;
    }
    
    .empty-state {
        padding: 40px 20px;
        text-align: center;
    }
    
    .empty-state i {
        font-size: 48px;
        opacity: 0.3;
        color: #6c757d;
    }
    
    .empty-state h5 {
        color: #495057;
        margin: 15px 0 10px 0;
    }
    
    .empty-state p {
        color: #6c757d;
        margin: 0;
    }
    
    .empty-state-row {
        background-color: #f8f9fa;
    }
    
    .empty-state-row:hover {
        background-color: #f8f9fa !important;
    }
</style>
@section('content')
<div class="container mt-4">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4>Manage Stores</h4>
            <div>
                @if($stores->count() > 0)
                    <button id="bulk-delete-btn" class="btn btn-danger btn-sm mx-3" disabled>
                        <i class="ri-delete-bin-line me-1"></i>Delete Selected
                    </button>
                @endif
                <a href="{{ route('admin.stores.create') }}" class="btn btn-primary btn-sm">
                    <i class="ri-add-line me-1"></i>Add New Store
                </a>
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
                    <label class="form-label">Search Store</label>
                    <input type="text" id="searchInput" class="form-control" placeholder="Search by name, URL...">
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
                    <label class="form-label">Featured</label>
                    <select id="featuredFilter" class="form-select">
                        <option value="">All</option>
                        <option value="1">Featured</option>
                        <option value="0">Not Featured</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Student Discount</label>
                    <select id="studentDiscountFilter" class="form-select">
                        <option value="">All</option>
                        <option value="1">Yes</option>
                        <option value="0">No</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Trending</label>
                    <select id="trendingFilter" class="form-select">
                        <option value="">All</option>
                        <option value="1">Trending</option>
                        <option value="0">Not Trending</option>
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

            <table id="storesTable" class="table table-bordered table-striped align-middle w-100">
                <thead>
                    <tr>
                        <th width="30"><input type="checkbox" id="selectAll"></th>
                        <th width="30">☰</th>
                        <th>SL</th>
                        <th>Store Name</th>
                        <th>Categories</th>
                        <th>Events</th>
                        <th>Networks</th>
                        <th>Views</th>
                        <th>Logo</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($stores as $store)
                        <tr data-id="{{ $store->id }}" 
                            data-status="{{ $store->status }}"
                            data-featured="{{ $store->featured }}"
                            data-student-discount="{{ $store->student_discount }}"
                            data-trending="{{ $store->show_trending }}"
                            data-store-name="{{ strtolower($store->store_name) }}"
                            data-affiliate-url="{{ strtolower($store->affiliate_url ?? '') }}"
                            data-views-count="{{ $store->views_count ?? 0 }}">
                            <td><input type="checkbox" value="{{ $store->id }}" class="rowCheckbox"></td>
                            <td class="reorder-handle" style="cursor:move;">☰</td>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                <div>
                                <strong>{{ $store->store_name }}</strong>
                                @if($store->affiliate_url)
                                        <br><small class="text-muted">{{ Str::limit($store->affiliate_url, 40) }}</small>
                                    @endif
                                    @if($store->seo_url)
                                        <br><small class="text-info">/{{ $store->seo_url }}</small>
                                @endif
                                </div>
                            </td>
                            <td>
                                @if($store->categories->count())
                                    <div class="d-flex flex-wrap gap-1">
                                        @foreach($store->categories->take(2) as $category)
                                            <span class="badge bg-primary">{{ $category->category_name }}</span>
                                        @endforeach
                                        @if($store->categories->count() > 2)
                                            <span class="badge bg-secondary">+{{ $store->categories->count() - 2 }}</span>
                                        @endif
                                    </div>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @if($store->events->count())
                                    <div class="d-flex flex-wrap gap-1">
                                        @foreach($store->events->take(2) as $event)
                                            <span class="badge bg-info">{{ $event->event_name }}</span>
                                        @endforeach
                                        @if($store->events->count() > 2)
                                            <span class="badge bg-secondary">+{{ $store->events->count() - 2 }}</span>
                                        @endif
                                    </div>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                <div>
                                    @if($store->currentNetwork)
                                        <small class="text-success">Current: {{ $store->currentNetwork->name }}</small>
                                    @endif
                                    @if($store->availableNetwork)
                                        <br><small class="text-info">Available: {{ $store->availableNetwork->name }}</small>
                                    @endif
                                    @if(!$store->currentNetwork && !$store->availableNetwork)
                                        <span class="text-muted">-</span>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-info">{{ number_format($store->views_count ?? 0) }}</span>
                            </td>
                            <td>
                                @if($store->store_logo)
                                    <img src="{{ asset($store->store_logo) }}" width="40" height="40" alt="{{ $store->store_name }}" class="img-thumbnail">
                                @else
                                    <div class="bg-light d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; border-radius: 4px;">
                                        <i class="ri-image-line text-muted"></i>
                                    </div>
                                @endif
                            </td>
                            <td>
                                <div>
                                    @if($store->status)
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-danger">Inactive</span>
                                    @endif
                                    <br><br><span class="badge bg-info">Views: {{ $store->views_count ?? 0 }}</span>
                                </div>
                            </td>
                            <td>
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                        Actions
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <a class="dropdown-item" href="{{ route('store', $store->seo_url) }}" target="_blank">
                                                <i class="ri-eye-line me-2"></i>View Details
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="{{ route('admin.stores.edit', $store->id) }}">
                                                <i class="ri-edit-line me-2"></i>Edit Store
                                            </a>
                                        </li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li>
                                            <form action="{{ route('admin.stores.update-status', $store->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="{{ $store->status ? 0 : 1 }}">
                                                <button type="submit" class="dropdown-item">
                                                    @if($store->status)
                                                        <i class="ri-pause-line me-2"></i>Deactivate
                                                    @else
                                                        <i class="ri-check-line me-2"></i>Activate
                                                    @endif
                                                </button>
                                            </form>
                                        </li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li>
                                            <form action="{{ route('admin.stores.destroy', $store->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this store?')">
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
                    @empty
                        <tr class="empty-state-row">
                            <td colspan="11" class="empty-state">
                                <i class="ri-store-line"></i>
                                <h5>No Stores Found</h5>
                                <p>No stores have been added yet. Click "Add New Store" to get started.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            {{-- Hidden form for bulk delete --}}
            <form id="bulkDeleteForm" action="{{ route('admin.stores.bulkDelete') }}" method="POST" style="display:none;">
                @csrf
                @method('DELETE')
            </form>
        </div>
    </div>
</div>

<script>
$(document).ready(function () {
    // Only initialize DataTable if there are stores
    @if($stores->count() > 0)
        initDataTableWithFeatures({
            tableSelector: '#storesTable',
            bulkDeleteBtnSelector: '#bulk-delete-btn',
            selectAllSelector: '#selectAll',
            rowHandleSelector: 'td.reorder-handle',
            reorderUrl: '{{ route("admin.stores.reorder") }}',
            bulkDeleteUrl: '{{ route("admin.stores.bulkDelete") }}',
            csrfToken: '{{ csrf_token() }}'
        });

        // Filter functionality
        function filterTable() {
            const searchTerm = $('#searchInput').val().toLowerCase();
            const statusFilter = $('#statusFilter').val();
            const featuredFilter = $('#featuredFilter').val();
            const studentDiscountFilter = $('#studentDiscountFilter').val();
            const trendingFilter = $('#trendingFilter').val();
            const viewsFilter = $('#viewsFilter').val();

            $('#storesTable tbody tr').each(function() {
                const row = $(this);
                const storeName = row.data('store-name') || '';
                const affiliateUrl = row.data('affiliate-url') || '';
                const status = row.data('status').toString();
                const featured = row.data('featured').toString();
                const studentDiscount = row.data('student-discount').toString();
                const trending = row.data('trending').toString();
                const viewsCount = parseInt(row.data('views-count') || 0);

                let showRow = true;

                // Search filter
                if (searchTerm && !storeName.includes(searchTerm) && !affiliateUrl.includes(searchTerm)) {
                    showRow = false;
                }

                // Status filter
                if (statusFilter && status !== statusFilter) {
                    showRow = false;
                }

                // Featured filter
                if (featuredFilter && featured !== featuredFilter) {
                    showRow = false;
                }

                // Student discount filter
                if (studentDiscountFilter && studentDiscount !== studentDiscountFilter) {
                    showRow = false;
                }

                // Trending filter
                if (trendingFilter && trending !== trendingFilter) {
                    showRow = false;
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
        $('#searchInput, #statusFilter, #featuredFilter, #studentDiscountFilter, #trendingFilter, #viewsFilter').on('input change', filterTable);

        // Clear filters
        $('#clearFilters').click(function() {
            $('#searchInput').val('');
            $('#statusFilter').val('');
            $('#featuredFilter').val('');
            $('#studentDiscountFilter').val('');
            $('#trendingFilter').val('');
            $('#viewsFilter').val('');
            filterTable();
        });

    @else
        // Don't initialize DataTable for empty state to avoid warnings
        console.log('No stores found - DataTable not initialized to avoid warnings');
    @endif
});
</script>
@endsection

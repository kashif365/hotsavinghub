@extends('admin.layouts.app')

@section('content')
<div class="container mt-4">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="mb-0">Manage Coupons</h4>
            <div class="d-flex">
                <button id="bulk-delete-btn" class="btn btn-danger btn-sm mx-3" disabled>Delete Selected</button>
                <a href="{{ route('admin.coupons.create') }}" class="btn btn-primary btn-sm">+ Add New Coupon</a>
            </div>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class='alert alert-success'>{{ session('success') }}</div>
            @elseif(session('error'))
                <div class='alert alert-danger'>{{ session('error') }}</div>
            @endif

            <!-- Filter Controls -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <label class="form-label">Search Coupon</label>
                    <input type="text" id="searchInput" class="form-control" placeholder="Search by title, brand, code...">
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
                    <label class="form-label">Verified</label>
                    <select id="verifiedFilter" class="form-select">
                        <option value="">All</option>
                        <option value="1">Verified</option>
                        <option value="0">Not Verified</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Exclusive</label>
                    <select id="exclusiveFilter" class="form-select">
                        <option value="">All</option>
                        <option value="1">Exclusive</option>
                        <option value="0">Not Exclusive</option>
                    </select>
                </div>
                <div class="col-md-1">
                    <label class="form-label">&nbsp;</label>
                    <button id="clearFilters" class="btn btn-outline-secondary w-100">Clear</button>
                </div>
            </div>

            <table id="couponsTable" class="table table-bordered table-striped align-middle w-100">
                <thead>
                    <tr>
                        <th width="30"><input type="checkbox" id="selectAll"></th>
                        <th width="30">☰</th>
                        <th>SL</th>
                        <th>Coupon Details</th>
                        <th>Brand/Store</th>
                        <th>Event</th>
                        <th>Expiry</th>
                        <th>Views</th>
                        <th>Logo</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($coupons as $coupon)
                    <tr data-id="{{ $coupon->id }}" 
                        data-status="{{ $coupon->status }}"
                        data-featured="{{ $coupon->featured }}"
                        data-verified="{{ $coupon->verified }}"
                        data-exclusive="{{ $coupon->exclusive }}"
                        data-coupon-title="{{ strtolower($coupon->coupon_title) }}"
                        data-brand-store="{{ strtolower($coupon->brand_store) }}"
                        data-coupon-code="{{ strtolower($coupon->coupon_code ?? '') }}">
                        <td>
                            <input type="checkbox" name="ids[]" value="{{ $coupon->id }}" class="rowCheckbox">
                        </td>
                        <td class="reorder-handle" style="cursor:move;">☰</td>
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            <div>
                                <strong>{{ $coupon->coupon_title }}</strong>
                                @if($coupon->coupon_code)
                                    <br><small class="text-info">Code: {{ $coupon->coupon_code }}</small>
                                @endif
                                @if($coupon->description)
                                    <br><small class="text-muted">{{ Str::limit($coupon->description, 50) }}</small>
                                @endif
                            </div>
                        </td>
                        <td>
                            <div>
                                <strong>{{ $coupon->brand_store }}</strong>
                                @if($coupon->affiliate_url)
                                    <br><small class="text-muted">{{ Str::limit($coupon->affiliate_url, 30) }}</small>
                                @endif
                            </div>
                        </td>
                        <td>
                            @if($coupon->event)
                                <span class="badge bg-info">{{ $coupon->event->event_name }}</span>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            <div>
                                @if($coupon->date_expiry)
                                    <small class="{{ $coupon->expiry_soon ? 'text-danger' : 'text-muted' }}">
                                        {{ \Carbon\Carbon::parse($coupon->date_expiry)->format('M d, Y') }}
                                    </small>
                                    @if($coupon->expiry_soon)
                                        <br><span class="badge bg-warning">Expires Soon</span>
                                    @endif
                                @else
                                    <span class="text-muted">No Expiry</span>
                                @endif
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-info">{{ number_format($coupon->views_count ?? 0) }}</span>
                        </td>
                        <td>
                            @if($coupon->cover_logo)
                                <img src="{{ asset($coupon->cover_logo) }}" width="40" height="40" alt="{{ $coupon->coupon_title }}" class="img-thumbnail">
                            @else
                                <div class="bg-light d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; border-radius: 4px;">
                                    <i class="ri-image-line text-muted"></i>
                                </div>
                            @endif
                        </td>
                        <td>
                            @if($coupon->status)
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-danger">Inactive</span>
                            @endif
                        </td>
                        <td>
                            <div class="dropdown">
                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                    Actions
                                </button>
                                <ul class="dropdown-menu">
                                    <li>
                                        <a class="dropdown-item" href="{{ route('admin.coupons.edit', $coupon->id) }}">
                                            <i class="ri-edit-line me-2"></i>Edit Coupon
                                        </a>
                                    </li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <form action="{{ route('admin.coupons.update-status', $coupon->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="{{ $coupon->status ? 0 : 1 }}">
                                            <button type="submit" class="dropdown-item">
                                                @if($coupon->status)
                                                    <i class="ri-pause-line me-2"></i>Deactivate
                                                @else
                                                    <i class="ri-check-line me-2"></i>Activate
                                                @endif
                                            </button>
                                        </form>
                                    </li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <form action="{{ route('admin.coupons.destroy', $coupon->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this coupon?')">
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

        </div>
    </div>
</div>

{{-- JS init --}}
<script>
    $(document).ready(function () {
        initDataTableWithFeatures({
            tableSelector: '#couponsTable',
            bulkDeleteBtnSelector: '#bulk-delete-btn',
            selectAllSelector: '#selectAll',
            rowHandleSelector: 'td.reorder-handle',
            reorderUrl: '{{ route("admin.coupons.reorder") }}',
            bulkDeleteUrl: '{{ route("admin.coupons.bulkDelete") }}', // Bulk delete route
            csrfToken: '{{ csrf_token() }}'
        });

        // Filter functionality
        function filterTable() {
            const searchTerm = $('#searchInput').val().toLowerCase();
            const statusFilter = $('#statusFilter').val();
            const featuredFilter = $('#featuredFilter').val();
            const verifiedFilter = $('#verifiedFilter').val();
            const exclusiveFilter = $('#exclusiveFilter').val();

            $('#couponsTable tbody tr').each(function() {
                const row = $(this);
                const couponTitle = row.data('coupon-title') || '';
                const brandStore = row.data('brand-store') || '';
                const couponCode = row.data('coupon-code') || '';
                const status = row.data('status').toString();
                const featured = row.data('featured').toString();
                const verified = row.data('verified').toString();
                const exclusive = row.data('exclusive').toString();

                let showRow = true;

                // Search filter
                if (searchTerm && !couponTitle.includes(searchTerm) && !brandStore.includes(searchTerm) && !couponCode.includes(searchTerm)) {
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

                // Verified filter
                if (verifiedFilter && verified !== verifiedFilter) {
                    showRow = false;
                }

                // Exclusive filter
                if (exclusiveFilter && exclusive !== exclusiveFilter) {
                    showRow = false;
                }

                if (showRow) {
                    row.show();
                } else {
                    row.hide();
                }
            });
        }

        // Bind filter events
        $('#searchInput, #statusFilter, #featuredFilter, #verifiedFilter, #exclusiveFilter').on('input change', filterTable);

        // Clear filters
        $('#clearFilters').click(function() {
            $('#searchInput').val('');
            $('#statusFilter').val('');
            $('#featuredFilter').val('');
            $('#verifiedFilter').val('');
            $('#exclusiveFilter').val('');
            filterTable();
        });
    });
</script>
@endsection

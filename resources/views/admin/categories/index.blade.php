@extends('admin.layouts.app')

@section('content')
<div class="container mt-4">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="mb-0">Manage Categories</h4>
            <div class="d-flex">
                <button id="bulk-delete-btn" class="btn btn-danger btn-sm mx-3" disabled>Delete Selected</button>
                <a href="{{ route('admin.categories.create') }}" class="btn btn-primary btn-sm">+ Add New Category</a>
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
                    <label class="form-label">Search Category</label>
                    <input type="text" id="searchInput" class="form-control" placeholder="Search by name...">
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
                    <label class="form-label">Show Home</label>
                    <select id="showHomeFilter" class="form-select">
                        <option value="">All</option>
                        <option value="1">Yes</option>
                        <option value="0">No</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Recommended</label>
                    <select id="recommendedFilter" class="form-select">
                        <option value="">All</option>
                        <option value="1">Yes</option>
                        <option value="0">No</option>
                    </select>
                </div>
                <div class="col-md-1">
                    <label class="form-label">&nbsp;</label>
                    <button id="clearFilters" class="btn btn-outline-secondary w-100">Clear</button>
                </div>
            </div>

            {{-- No outer bulk form anymore --}}
            <table id="categoriesTable" class="table table-bordered table-striped align-middle w-100">
                <thead>
                    <tr>
                        <th width="30"><input type="checkbox" id="selectAll"></th>
                        <th width="30">☰</th>
                        <th>SL</th>
                        <th>Category Name</th>
                        <th>Status</th>
                        <th>Featured</th>
                        <th>Show Home</th>
                        <th>Recommended</th>
                        <th>Views</th>
                        <th>Image</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($categories as $category)
                    <tr data-id="{{ $category->id }}" 
                        data-status="{{ $category->status }}"
                        data-featured="{{ $category->featured }}"
                        data-show-home="{{ $category->show_home }}"
                        data-recommended="{{ $category->recommended }}"
                        data-category-name="{{ strtolower($category->category_name) }}">
                        <td>
                            <input type="checkbox" name="ids[]" value="{{ $category->id }}" class="rowCheckbox">
                        </td>
                        <td class="reorder-handle" style="cursor:move;">☰</td>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $category->category_name }}</td>
                        <td>
                            <span class="badge {{ $category->status ? 'bg-success' : 'bg-secondary' }}">
                                {{ $category->status ? 'Enabled' : 'Disabled' }}
                            </span>
                        </td>
                        <td>
                            <span class="badge {{ $category->featured ? 'bg-warning' : 'bg-secondary' }}">
                                {{ $category->featured ? 'Featured' : 'No' }}
                            </span>
                        </td>
                <td>
                    <span class="badge {{ $category->show_home ? 'bg-info' : 'bg-secondary' }}">
                        {{ $category->show_home ? 'Yes' : 'No' }}
                    </span>
                </td>
                <td>
                    <span class="badge {{ $category->recommended ? 'bg-success' : 'bg-secondary' }}">
                        {{ $category->recommended ? 'Yes' : 'No' }}
                    </span>
                </td>
                <td>
                    <div>
                        <span class="badge bg-info">Views: {{ $category->views_count ?? 0 }}</span>
                    </div>
                </td>
                        <td>
                            @if($category->media)
                                <img src="{{ asset($category->media) }}" width="50" alt="{{ $category->category_name }}">
                            @endif
                        </td>
                        <td>
                            <div class="dropdown">
                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                    Actions
                                </button>
                                <ul class="dropdown-menu">
                                    <li>
                                        <a class="dropdown-item" href="{{ route('category', $category->seo_url) }}" target="_blank">
                                            <i class="ri-eye-line me-2"></i>View Details
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('admin.categories.edit', $category->id) }}">
                                            <i class="ri-edit-line me-2"></i>Edit Category
                                        </a>
                                    </li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <form action="{{ route('admin.categories.update-status', $category->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="{{ $category->status ? 0 : 1 }}">
                                            <button type="submit" class="dropdown-item">
                                                @if($category->status)
                                                    <i class="ri-pause-line me-2"></i>Deactivate
                                                @else
                                                    <i class="ri-check-line me-2"></i>Activate
                                                @endif
                                            </button>
                                        </form>
                                    </li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this category?')">
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
            tableSelector: '#categoriesTable',
            bulkDeleteBtnSelector: '#bulk-delete-btn',
            selectAllSelector: '#selectAll',
            rowHandleSelector: 'td.reorder-handle',
            reorderUrl: '{{ route("admin.categories.reorder") }}',
            bulkDeleteUrl: '{{ route("admin.categories.bulk-delete") }}', // pass route here
            csrfToken: '{{ csrf_token() }}'
        });

        // Filter functionality
        function filterTable() {
            const searchTerm = $('#searchInput').val().toLowerCase();
            const statusFilter = $('#statusFilter').val();
            const featuredFilter = $('#featuredFilter').val();
            const showHomeFilter = $('#showHomeFilter').val();
            const recommendedFilter = $('#recommendedFilter').val();

            $('#categoriesTable tbody tr').each(function() {
                const row = $(this);
                const categoryName = row.data('category-name') || '';
                const status = row.data('status').toString();
                const featured = row.data('featured').toString();
                const showHome = row.data('show-home').toString();
                const recommended = row.data('recommended').toString();

                let showRow = true;

                // Search filter
                if (searchTerm && !categoryName.includes(searchTerm)) {
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

                // Show Home filter
                if (showHomeFilter && showHome !== showHomeFilter) {
                    showRow = false;
                }

                // Recommended filter
                if (recommendedFilter && recommended !== recommendedFilter) {
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
        $('#searchInput, #statusFilter, #featuredFilter, #showHomeFilter, #recommendedFilter').on('input change', filterTable);

        // Clear filters
        $('#clearFilters').click(function() {
            $('#searchInput').val('');
            $('#statusFilter').val('');
            $('#featuredFilter').val('');
            $('#showHomeFilter').val('');
            $('#recommendedFilter').val('');
            filterTable();
        });
    });
</script>

@endsection

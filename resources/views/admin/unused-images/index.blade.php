@extends('admin.layouts.app')

@php
    use Illuminate\Support\Facades\Storage;
    use Illuminate\Support\Str;
    
    // Helper function for formatting bytes
    if (!function_exists('formatBytes')) {
        function formatBytes($bytes, $precision = 2) {
            $units = ['B', 'KB', 'MB', 'GB', 'TB'];
            for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
                $bytes /= 1024;
            }
            return round($bytes, $precision) . ' ' . $units[$i];
        }
    }
@endphp

@section('title', 'Unused Images Management')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="ri-image-line me-2"></i>Unused Images Management
                    </h5>
                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.unused-images.refresh') }}" class="btn btn-sm btn-outline-primary">
                            <i class="ri-refresh-line me-1"></i>Refresh Scan
                        </a>
                    </div>
                </div>
                
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('info'))
                        <div class="alert alert-info alert-dismissible fade show" role="alert">
                            {{ session('info') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <!-- Statistics -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="card bg-primary text-white">
                                <div class="card-body">
                                    <h6 class="card-title">Total Images</h6>
                                    <h3 class="mb-0">{{ count($allImages) }}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-danger text-white">
                                <div class="card-body">
                                    <h6 class="card-title">Unused Images</h6>
                                    <h3 class="mb-0">{{ $totalUnused }}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-success text-white">
                                <div class="card-body">
                                    <h6 class="card-title">Used Images</h6>
                                    <h3 class="mb-0">{{ count($allImages) - $totalUnused }}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-warning text-white">
                                <div class="card-body">
                                    <h6 class="card-title">Waste Space</h6>
                                    <h3 class="mb-0">{{ formatBytes($totalSize) }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Scan Statistics -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h6 class="mb-0">Scan Statistics</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3">
                                    <strong>Blade Files:</strong> {{ $stats['blade_files'] }}
                                </div>
                                <div class="col-md-3">
                                    <strong>PHP Files:</strong> {{ $stats['php_files'] }}
                                </div>
                                <div class="col-md-3">
                                    <strong>CSS Files:</strong> {{ $stats['css_files'] }}
                                </div>
                                <div class="col-md-3">
                                    <strong>JS Files:</strong> {{ $stats['js_files'] }}
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-md-12">
                                    <strong>Database Records Checked:</strong> {{ $stats['database_records'] }}
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($totalUnused > 0)
                        <!-- Bulk Actions -->
                        <div class="mb-3">
                            <form id="bulkDeleteForm" action="{{ route('admin.unused-images.destroy') }}" method="POST" onsubmit="return confirmBulkDelete();">
                                @csrf
                                @method('DELETE')
                                <input type="hidden" name="images" id="selectedImages">
                                <button type="submit" class="btn btn-danger" id="bulkDeleteBtn" disabled>
                                    <i class="ri-delete-bin-line me-1"></i>Delete Selected (0)
                                </button>
                                <button type="button" class="btn btn-outline-secondary" onclick="selectAllDataTable()">
                                    <i class="ri-checkbox-multiple-line me-1"></i>Select All
                                </button>
                                <button type="button" class="btn btn-outline-secondary" onclick="deselectAllDataTable()">
                                    <i class="ri-checkbox-blank-line me-1"></i>Deselect All
                                </button>
                            </form>
                        </div>

                        <!-- DataTable -->
                        <div class="table-responsive">
                            <table id="unusedImagesTable" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th width="30">
                                            <input type="checkbox" id="selectAllCheckbox" class="form-check-input">
                                        </th>
                                        <th width="100">Preview</th>
                                        <th>Filename</th>
                                        <th>Path</th>
                                        <th width="120">Size</th>
                                        <th width="100">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($unusedImages as $image)
                                        <tr>
                                            <td>
                                                <input type="checkbox" class="form-check-input image-checkbox" value="{{ $image['path'] }}">
                                            </td>
                                            <td>
                                                <img src="{{ str_starts_with($image['path'], 'storage/') ? Storage::url(str_replace('storage/', '', $image['path'])) : asset($image['path']) }}" 
                                                     alt="{{ $image['filename'] }}"
                                                     class="img-thumbnail"
                                                     style="width: 80px; height: 80px; object-fit: cover; cursor: pointer;"
                                                     onerror="this.src='data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' width=\'80\' height=\'80\'%3E%3Crect fill=\'%23ddd\' width=\'80\' height=\'80\'/%3E%3Ctext x=\'50%25\' y=\'50%25\' text-anchor=\'middle\' dy=\'.3em\' fill=\'%23999\'%3EImage%3C/text%3E%3C/svg%3E'"
                                                     onclick="window.open(this.src, '_blank')">
                                            </td>
                                            <td>
                                                <strong>{{ $image['filename'] }}</strong>
                                            </td>
                                            <td>
                                                <small class="text-muted" title="{{ $image['path'] }}">
                                                    {{ Str::limit($image['path'], 50) }}
                                                </small>
                                            </td>
                                            <td>
                                                <span class="badge bg-info">{{ formatBytes($image['size']) }}</span>
                                            </td>
                                            <td>
                                                <form action="{{ route('admin.unused-images.delete-single') }}" 
                                                      method="POST" 
                                                      class="d-inline"
                                                      onsubmit="return confirm('Are you sure you want to delete this image?');">
                                                    @csrf
                                                    <input type="hidden" name="image_path" value="{{ $image['path'] }}">
                                                    <button type="submit" class="btn btn-sm btn-danger">
                                                        <i class="ri-delete-bin-line"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-success">
                            <h5><i class="ri-checkbox-circle-line me-2"></i>Great News!</h5>
                            <p class="mb-0">No unused images found. All images in your project are being used. Your storage is clean! 🎉</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.img-thumbnail {
    transition: transform 0.2s;
}

.img-thumbnail:hover {
    transform: scale(1.1);
    z-index: 10;
    position: relative;
}
</style>

<script>
let selectedImages = [];
let dataTable;

$(document).ready(function() {
    @if($totalUnused > 0)
    // Initialize DataTable
    dataTable = $('#unusedImagesTable').DataTable({
        "pageLength": 25,
        "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
        "order": [[2, 'asc']], // Sort by filename
        "columnDefs": [
            {
                "targets": [0, 4, 5],
                "orderable": false
            },
            {
                "targets": [1],
                "orderable": false,
                "searchable": false
            }
        ],
        "language": {
            "search": "Search:",
            "lengthMenu": "Show _MENU_ entries",
            "info": "Showing _START_ to _END_ of _TOTAL_ unused images",
            "infoEmpty": "No unused images found",
            "infoFiltered": "(filtered from _TOTAL_ total images)",
            "paginate": {
                "first": "First",
                "last": "Last",
                "next": "Next",
                "previous": "Previous"
            }
        },
        "dom": '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>rt<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
        "drawCallback": function() {
            // Update checkboxes state after table redraw
            updateBulkDeleteBtn();
        }
    });

    // Select all checkbox handler
    $('#selectAllCheckbox').on('change', function() {
        const isChecked = $(this).prop('checked');
        $('.image-checkbox').prop('checked', isChecked);
        updateBulkDeleteBtn();
    });

    // Individual checkbox handler
    $(document).on('change', '.image-checkbox', function() {
        updateBulkDeleteBtn();
        // Uncheck select all if any checkbox is unchecked
        if (!$(this).prop('checked')) {
            $('#selectAllCheckbox').prop('checked', false);
        }
    });
    @endif
});

function updateBulkDeleteBtn() {
    const checkboxes = $('.image-checkbox:checked');
    const btn = $('#bulkDeleteBtn');
    const selectedInput = $('#selectedImages');
    
    selectedImages = checkboxes.map(function() {
        return $(this).val();
    }).get();
    
    selectedInput.val(JSON.stringify(selectedImages));
    
    if (selectedImages.length > 0) {
        btn.prop('disabled', false);
        btn.html(`<i class="ri-delete-bin-line me-1"></i>Delete Selected (${selectedImages.length})`);
    } else {
        btn.prop('disabled', true);
        btn.html(`<i class="ri-delete-bin-line me-1"></i>Delete Selected (0)`);
    }
}

function selectAllDataTable() {
    $('.image-checkbox').prop('checked', true);
    $('#selectAllCheckbox').prop('checked', true);
    updateBulkDeleteBtn();
}

function deselectAllDataTable() {
    $('.image-checkbox').prop('checked', false);
    $('#selectAllCheckbox').prop('checked', false);
    updateBulkDeleteBtn();
}

function confirmBulkDelete() {
    const count = selectedImages.length;
    if (count === 0) {
        alert('Please select at least one image to delete.');
        return false;
    }
    return confirm(`Are you sure you want to delete ${count} image(s)? This action cannot be undone!`);
}
</script>
@endsection

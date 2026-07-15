@extends('admin.layouts.app')

@php
    use Illuminate\Support\Str;
@endphp

@section('title', 'Media Library - Admin')

@section('content')
<div class="container mt-4">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4>Media Library</h4>
            <div class="d-flex gap-2">
                <div class="btn-group" role="group">
                    <button type="button" class="btn btn-sm btn-outline-primary" id="gridViewBtn" onclick="switchView('grid')">
                        <i class="ri-grid-line"></i> Grid
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-primary" id="tableViewBtn" onclick="switchView('table')">
                        <i class="ri-table-line"></i> Table
                    </button>
                </div>
                <button type="button" id="bulkDeleteBtn" class="btn btn-danger btn-sm" disabled onclick="bulkDeleteImages()">
                    <i class="ri-delete-bin-line"></i> Delete Selected (<span id="selectedCount">0</span>)
                </button>
                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#uploadModal">
                    <i class="ri-upload-line"></i> Upload Image
                </button>
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

            <!-- Search and Filter -->
            <div class="row mb-3">
                <div class="col-md-6">
                    <div class="d-flex gap-2">
                        <input type="text" id="mediaSearchInput" class="form-control" placeholder="Search images..." value="{{ $search ?? '' }}">
                        <button type="button" class="btn btn-secondary" onclick="performSearch()">
                            <i class="ri-search-line"></i>
                        </button>
                        @if($search ?? '')
                            <a href="{{ route('admin.media.index') }}" class="btn btn-outline-secondary">
                                <i class="ri-close-line"></i>
                            </a>
                        @endif
                    </div>
                </div>
                <div class="col-md-6 text-end d-flex align-items-center justify-content-end gap-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="selectAllCheckbox" onchange="toggleSelectAll()">
                        <label class="form-check-label" for="selectAllCheckbox">
                            Select All
                        </label>
                    </div>
                    <small class="text-muted">Total: {{ $total ?? 0 }} images</small>
                </div>
            </div>

            <!-- Grid View -->
            <div id="gridView" class="view-container">
                @if(isset($images) && count($images) > 0)
                    <div class="row g-3" id="imagesGrid">
                        @foreach($images as $image)
                            <div class="col-md-3 col-sm-4 col-6 media-item" data-filename="{{ basename($image['path']) }}" data-path="{{ $image['path'] }}">
                                <div class="card h-100 media-card">
                                    <div class="position-relative">
                                        <!-- Checkbox for selection -->
                                        <div class="position-absolute top-0 start-0 p-2" style="z-index: 10;">
                                            <input type="checkbox" class="form-check-input image-checkbox" 
                                                   value="{{ $image['path'] }}" 
                                                   id="image_{{ $loop->index }}"
                                                   onchange="updateBulkDeleteButton()"
                                                   style="width: 20px; height: 20px; background-color: white; border: 2px solid #007bff;">
                                        </div>
                                        <div class="image-wrapper" style="height: 200px; overflow: hidden; background: #f8f9fa; display: flex; align-items: center; justify-content: center;">
                                            <img src="{{ $image['url'] }}" 
                                                 alt="{{ basename($image['path']) }}" 
                                                 class="card-img-top media-image" 
                                                 style="max-height: 200px; width: 100%; object-fit: contain; cursor: pointer;"
                                                 onerror="this.onerror=null; this.src='data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' width=\'200\' height=\'200\'%3E%3Crect fill=\'%23ddd\' width=\'200\' height=\'200\'/%3E%3Ctext fill=\'%23999\' font-family=\'sans-serif\' font-size=\'14\' dy=\'10.5\' font-weight=\'bold\' x=\'50%25\' y=\'50%25\' text-anchor=\'middle\'%3EBroken Image%3C/text%3E%3C/svg%3E';"
                                                 onclick="openImageModal('{{ $image['url'] }}', '{{ basename($image['path']) }}', '{{ $image['path'] }}', {{ $image['width'] }}, {{ $image['height'] }}, {{ $image['size'] }})"
                                                 loading="lazy">
                                        </div>
                                        <div class="position-absolute top-0 end-0 p-2">
                                            <button class="btn btn-sm btn-danger delete-btn" 
                                                    data-path="{{ $image['path'] }}"
                                                    onclick="deleteImage('{{ $image['path'] }}')"
                                                    title="Delete Image">
                                                <i class="ri-delete-bin-line"></i>
                                            </button>
                                        </div>
                                        @if($image['format'] !== 'webp' && $image['format'] !== 'svg')
                                            <div class="position-absolute bottom-0 start-0 p-2">
                                                <button class="btn btn-sm btn-warning convert-btn" 
                                                        data-path="{{ $image['path'] }}"
                                                        onclick="convertToWebP('{{ $image['path'] }}')"
                                                        title="Convert to WebP">
                                                    <i class="ri-image-line"></i>
                                                </button>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="card-body p-2">
                                        <small class="text-muted d-block text-truncate" title="{{ basename($image['path']) }}">
                                            {{ basename($image['path']) }}
                                        </small>
                                        <small class="text-muted d-block">
                                            {{ $image['width'] }}x{{ $image['height'] }} | 
                                            {{ number_format($image['size'] / 1024, 2) }} KB
                                        </small>
                                        <small class="badge bg-info">{{ strtoupper($image['format']) }}</small>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="ri-image-line" style="font-size: 64px; color: #ccc;"></i>
                        <h5 class="mt-3">No images found</h5>
                        <p class="text-muted">Upload your first image to get started</p>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#uploadModal">
                            <i class="ri-upload-line"></i> Upload Image
                        </button>
                    </div>
                @endif
            </div>

            <!-- Table View -->
            <div id="tableView" class="view-container" style="display: none;">
                @if(isset($images) && count($images) > 0)
                    <div class="table-responsive">
                        <table id="mediaTable" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th width="30">
                                        <input type="checkbox" id="selectAllTableCheckbox" class="form-check-input">
                                    </th>
                                    <th width="100">Preview</th>
                                    <th>Filename</th>
                                    <th>Path</th>
                                    <th width="120">Dimensions</th>
                                    <th width="100">Size</th>
                                    <th width="80">Format</th>
                                    <th width="120">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($images as $image)
                                    <tr data-filename="{{ basename($image['path']) }}" data-path="{{ $image['path'] }}">
                                        <td>
                                            <input type="checkbox" class="form-check-input image-checkbox" value="{{ $image['path'] }}">
                                        </td>
                                        <td>
                                            <img src="{{ $image['url'] }}" 
                                                 alt="{{ basename($image['path']) }}"
                                                 class="img-thumbnail"
                                                 style="width: 80px; height: 80px; object-fit: cover; cursor: pointer;"
                                                 onerror="this.src='data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' width=\'80\' height=\'80\'%3E%3Crect fill=\'%23ddd\' width=\'80\' height=\'80\'/%3E%3Ctext fill=\'%23999\' font-family=\'sans-serif\' font-size=\'12\' dy=\'10.5\' font-weight=\'bold\' x=\'50%25\' y=\'50%25\' text-anchor=\'middle\'%3EBroken%3C/text%3E%3C/svg%3E'"
                                                 onclick="openImageModal('{{ $image['url'] }}', '{{ basename($image['path']) }}', '{{ $image['path'] }}', {{ $image['width'] }}, {{ $image['height'] }}, {{ $image['size'] }})">
                                        </td>
                                        <td>
                                            <strong>{{ basename($image['path']) }}</strong>
                                        </td>
                                        <td>
                                            <small class="text-muted" title="{{ $image['path'] }}">
                                                {{ Str::limit($image['path'], 50) }}
                                            </small>
                                        </td>
                                        <td>
                                            <span class="badge bg-secondary">{{ $image['width'] }}x{{ $image['height'] }}</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-info">{{ number_format($image['size'] / 1024, 2) }} KB</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-primary">{{ strtoupper($image['format']) }}</span>
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm" role="group">
                                                @if($image['format'] !== 'webp' && $image['format'] !== 'svg')
                                                    <button class="btn btn-warning" 
                                                            onclick="convertToWebP('{{ $image['path'] }}')"
                                                            title="Convert to WebP">
                                                        <i class="ri-image-line"></i>
                                                    </button>
                                                @endif
                                                <button class="btn btn-danger" 
                                                        onclick="deleteImage('{{ $image['path'] }}')"
                                                        title="Delete">
                                                    <i class="ri-delete-bin-line"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="ri-image-line" style="font-size: 64px; color: #ccc;"></i>
                        <h5 class="mt-3">No images found</h5>
                        <p class="text-muted">Upload your first image to get started</p>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#uploadModal">
                            <i class="ri-upload-line"></i> Upload Image
                        </button>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Upload Modal -->
<div class="modal fade" id="uploadModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Upload Image</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.media.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="image" class="form-label">Select Image</label>
                        <input type="file" class="form-control" id="image" name="image" accept="image/*" required>
                        <small class="text-muted">Max size: 5MB. Image will be automatically converted to WebP format.</small>
                    </div>
                    <div class="mb-3">
                        <label for="directory" class="form-label">Directory (optional)</label>
                        <input type="text" class="form-control" id="directory" name="directory" value="uploads" placeholder="uploads">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Upload</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Image Preview Modal -->
<div class="modal fade" id="imageModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="imageModalTitle">Image Preview</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <img id="imageModalImg" src="" alt="" class="img-fluid mb-3">
                <div class="text-start">
                    <p><strong>Path:</strong> <code id="imageModalPath"></code></p>
                    <p><strong>Dimensions:</strong> <span id="imageModalDimensions"></span></p>
                    <p><strong>Size:</strong> <span id="imageModalSize"></span></p>
                </div>
                <div class="d-flex gap-2 justify-content-center mt-3">
                    <button class="btn btn-danger" onclick="deleteImageFromModal()">
                        <i class="ri-delete-bin-line"></i> Delete
                    </button>
                    <button class="btn btn-warning" id="convertBtnFromModal" onclick="convertToWebPFromModal()" style="display: none;">
                        <i class="ri-image-line"></i> Convert to WebP
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.media-card {
    transition: transform 0.2s;
    border: 1px solid #e0e0e0;
}
.media-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}
.media-card .delete-btn, .media-card .convert-btn {
    opacity: 0.7;
    transition: opacity 0.2s;
}
.media-card:hover .delete-btn, .media-card:hover .convert-btn {
    opacity: 1;
}
.image-wrapper {
    position: relative;
}
.media-image {
    transition: transform 0.3s;
}
.media-card:hover .media-image {
    transform: scale(1.05);
}
.media-card:hover {
    border-color: #007bff;
}
.image-checkbox {
    cursor: pointer;
    box-shadow: 0 2px 4px rgba(0,0,0,0.2);
}
.image-checkbox:checked {
    background-color: #007bff !important;
    border-color: #007bff !important;
}
.view-container {
    min-height: 400px;
}
.img-thumbnail {
    transition: transform 0.2s;
}
.img-thumbnail:hover {
    transform: scale(1.1);
    z-index: 10;
    position: relative;
}
#gridViewBtn.active, #tableViewBtn.active {
    background-color: #007bff;
    color: white;
    border-color: #007bff;
}
</style>

<script>
let currentImagePath = '';
let dataTable;
let currentView = localStorage.getItem('mediaView') || 'grid';

// Initialize view on page load
document.addEventListener('DOMContentLoaded', function() {
    switchView(currentView);
});

function switchView(view) {
    currentView = view;
    localStorage.setItem('mediaView', view);
    
    const gridView = document.getElementById('gridView');
    const tableView = document.getElementById('tableView');
    const gridBtn = document.getElementById('gridViewBtn');
    const tableBtn = document.getElementById('tableViewBtn');
    
    if (view === 'grid') {
        gridView.style.display = 'block';
        tableView.style.display = 'none';
        gridBtn.classList.add('active');
        tableBtn.classList.remove('active');
        
        // Destroy DataTable if exists
        if (dataTable) {
            dataTable.destroy();
            dataTable = null;
        }
    } else {
        gridView.style.display = 'none';
        tableView.style.display = 'block';
        gridBtn.classList.remove('active');
        tableBtn.classList.add('active');
        
        // Initialize DataTable for table view
        if (!dataTable && $('#mediaTable').length) {
            initializeDataTable();
        }
    }
}

function initializeDataTable() {
    dataTable = $('#mediaTable').DataTable({
        "pageLength": 25,
        "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
        "order": [[2, 'asc']], // Sort by filename
        "columnDefs": [
            {
                "targets": [0, 7],
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
            "info": "Showing _START_ to _END_ of _TOTAL_ images",
            "infoEmpty": "No images found",
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
            updateBulkDeleteButton();
        }
    });
    
    // Select all checkbox handler for table
    $('#selectAllTableCheckbox').on('change', function() {
        const isChecked = $(this).prop('checked');
        $('#mediaTable .image-checkbox').prop('checked', isChecked);
        updateBulkDeleteButton();
    });
    
    // Individual checkbox handler for table
    $(document).on('change', '#mediaTable .image-checkbox', function() {
        updateBulkDeleteButton();
        const allChecked = $('#mediaTable .image-checkbox:checked').length === $('#mediaTable .image-checkbox').length;
        $('#selectAllTableCheckbox').prop('checked', allChecked);
    });
}

// Grid view search functionality
function performSearch() {
    const searchTerm = document.getElementById('mediaSearchInput').value.toLowerCase();
    const items = document.querySelectorAll('#imagesGrid .media-item');
    
    items.forEach(item => {
        const filename = item.getAttribute('data-filename').toLowerCase();
        const path = item.getAttribute('data-path').toLowerCase();
        
        if (filename.includes(searchTerm) || path.includes(searchTerm)) {
            item.style.display = '';
        } else {
            item.style.display = 'none';
        }
    });
}

// Update bulk delete button state
function updateBulkDeleteButton() {
    const checkboxes = document.querySelectorAll('.image-checkbox:checked');
    const count = checkboxes.length;
    const bulkDeleteBtn = document.getElementById('bulkDeleteBtn');
    const selectedCount = document.getElementById('selectedCount');
    
    selectedCount.textContent = count;
    
    if (count > 0) {
        bulkDeleteBtn.disabled = false;
    } else {
        bulkDeleteBtn.disabled = true;
    }
    
    // Update select all checkbox state
    const allCheckboxes = document.querySelectorAll('.image-checkbox');
    const selectAllCheckbox = document.getElementById('selectAllCheckbox');
    if (allCheckboxes.length > 0) {
        selectAllCheckbox.checked = checkboxes.length === allCheckboxes.length;
    }
}

// Toggle select all
function toggleSelectAll() {
    const selectAllCheckbox = document.getElementById('selectAllCheckbox');
    const checkboxes = document.querySelectorAll('.image-checkbox');
    
    checkboxes.forEach(checkbox => {
        checkbox.checked = selectAllCheckbox.checked;
    });
    
    // Also update table select all if in table view
    if (currentView === 'table' && $('#selectAllTableCheckbox').length) {
        $('#selectAllTableCheckbox').prop('checked', selectAllCheckbox.checked);
        $('#mediaTable .image-checkbox').prop('checked', selectAllCheckbox.checked);
    }
    
    updateBulkDeleteButton();
}

// Bulk delete images
function bulkDeleteImages() {
    const checkboxes = document.querySelectorAll('.image-checkbox:checked');
    const selectedPaths = Array.from(checkboxes).map(cb => cb.value);
    
    if (selectedPaths.length === 0) {
        alert('Please select at least one image to delete');
        return;
    }
    
    const count = selectedPaths.length;
    if (!confirm(`Are you sure you want to delete ${count} image(s)? This action cannot be undone.`)) {
        return;
    }
    
    // Disable button during deletion
    const bulkDeleteBtn = document.getElementById('bulkDeleteBtn');
    bulkDeleteBtn.disabled = true;
    bulkDeleteBtn.innerHTML = '<i class="ri-loader-4-line"></i> Deleting...';
    
    fetch('{{ route("admin.media.bulk-delete") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        },
        body: JSON.stringify({ paths: selectedPaths })
    })
    .then(response => {
        if (!response.ok) {
            return response.json().then(err => {
                throw new Error(err.message || 'Failed to delete images');
            });
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            alert(`Successfully deleted ${data.deleted_count || count} image(s)!`);
            location.reload();
        } else {
            alert(data.message || 'Failed to delete some images');
            bulkDeleteBtn.disabled = false;
            bulkDeleteBtn.innerHTML = '<i class="ri-delete-bin-line"></i> Delete Selected (<span id="selectedCount">0</span>)';
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while deleting images: ' + error.message);
        bulkDeleteBtn.disabled = false;
        bulkDeleteBtn.innerHTML = '<i class="ri-delete-bin-line"></i> Delete Selected (<span id="selectedCount">0</span>)';
    });
}

function openImageModal(url, name, path, width, height, size) {
    document.getElementById('imageModalImg').src = url;
    document.getElementById('imageModalTitle').textContent = name;
    document.getElementById('imageModalPath').textContent = path;
    document.getElementById('imageModalDimensions').textContent = width + ' x ' + height;
    document.getElementById('imageModalSize').textContent = (size / 1024).toFixed(2) + ' KB';
    currentImagePath = path;
    
    // Check if image is already WebP
    const convertBtn = document.getElementById('convertBtnFromModal');
    if (path.toLowerCase().endsWith('.webp')) {
        convertBtn.style.display = 'none';
    } else {
        convertBtn.style.display = 'inline-block';
    }
    
    new bootstrap.Modal(document.getElementById('imageModal')).show();
}

function deleteImage(path, force = false) {
    if (!force && !confirm('Are you sure you want to delete this image?')) {
        return;
    }
    
    fetch('{{ route("admin.media.destroy") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        },
        body: JSON.stringify({ path: path, force: force })
    })
    .then(response => {
        return response.json().then(data => {
            if (!response.ok) {
                // Check if force delete is available
                if (data.can_force_delete && !force) {
                    let message = data.message || 'This image is being used and cannot be deleted.';
                    if (data.usage_info && data.usage_info.length > 0) {
                        message += '\n\nUsed in:\n';
                        data.usage_info.forEach(usage => {
                            message += `- ${usage.model}: ${usage.field} (${usage.title})\n`;
                        });
                    }
                    message += '\n\nDo you want to force delete anyway? This may break references to this image.';
                    
                    if (confirm(message)) {
                        // Retry with force delete
                        deleteImage(path, true);
                    }
                    return;
                }
                throw new Error(data.message || 'Failed to delete image');
            }
            return data;
        });
    })
    .then(data => {
        if (data && data.success) {
            alert(data.message || 'Image deleted successfully!');
            location.reload();
        } else if (data && !data.success) {
            alert(data.message || 'Failed to delete image');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while deleting the image: ' + error.message);
    });
}

function deleteImageFromModal() {
    if (currentImagePath) {
        deleteImage(currentImagePath);
        bootstrap.Modal.getInstance(document.getElementById('imageModal')).hide();
    }
}

function convertToWebP(path) {
    if (!confirm('Convert this image to WebP format? The original will be deleted.')) {
        return;
    }
    
    fetch('{{ route("admin.media.convert-webp") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ path: path })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Image converted to WebP successfully!');
            location.reload();
        } else {
            alert(data.message || 'Failed to convert image');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while converting the image');
    });
}

function convertToWebPFromModal() {
    if (currentImagePath) {
        convertToWebP(currentImagePath);
        bootstrap.Modal.getInstance(document.getElementById('imageModal')).hide();
    }
}
</script>
@endsection

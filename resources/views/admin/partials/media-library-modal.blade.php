<style>
    .modal-content .modal-footer{
        padding: 15px 20px !important;
    }
    .modal-content .modal-header{
        padding: 15px 20px !important;
    }
    .modal-title h5{
        color: white !important;
    }
</style>
<!-- Media Library Modal -->
<div class="modal fade" id="mediaLibraryModal" tabindex="-1" aria-labelledby="mediaLibraryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" style="max-width: 1280px;">
        <div class="modal-content" style="border-radius: 12px; overflow: hidden;">
            <div class="modal-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none;">
                <h5 class="modal-title" id="mediaLibraryModalLabel" style="font-weight: 600;">
                    <i class="ri-image-2-line"></i> Media Library
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="max-height: 80vh; overflow-y: auto; padding: 20px;">
                <!-- Tabs for Upload Options -->
                <ul class="nav nav-tabs mb-4" id="uploadTabs" role="tablist" style="border-bottom: 2px solid #e9ecef;">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="file-tab" data-bs-toggle="tab" data-bs-target="#file-upload" type="button" role="tab">
                            <i class="ri-upload-cloud-line"></i> Upload File
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="url-tab" data-bs-toggle="tab" data-bs-target="#url-upload" type="button" role="tab">
                            <i class="ri-link"></i> From URL
                        </button>
                    </li>
                </ul>

                <!-- Tab Content -->
                <div class="tab-content mb-4" id="uploadTabContent">
                    <!-- File Upload Tab -->
                    <div class="tab-pane fade show active" id="file-upload" role="tabpanel">
                        <div class="upload-card">
                            <form id="mediaUploadForm" enctype="multipart/form-data">
                                @csrf
                                <div class="upload-area" id="dropZone" onclick="document.getElementById('mediaFileInput').click()">
                                    <div class="upload-icon">
                                        <i class="ri-upload-cloud-2-line"></i>
                                    </div>
                                    <p class="upload-text">Click to upload or drag and drop</p>
                                    <p class="upload-hint">PNG, JPG, GIF up to 5MB</p>
                                    <input type="file" id="mediaFileInput" name="image" accept="image/*" style="display:none;" required>
                                </div>
                                <div class="mt-3 text-center">
                                    <button type="submit" class="btn btn-primary btn-sm">
                                        <i class="ri-upload-line"></i> Upload & Convert to WebP
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- URL Upload Tab -->
                    <div class="tab-pane fade" id="url-upload" role="tabpanel">
                        <div class="upload-card">
                            <form id="urlUploadForm">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label"><i class="ri-link"></i> Image URL</label>
                                    <input type="url" class="form-control" id="imageUrlInput" 
                                           placeholder="https://example.com/image.jpg" required>
                                    <small class="text-muted">Enter direct image URL (must be accessible)</small>
                                </div>
                                <div class="text-center">
                                    <button type="submit" class="btn btn-primary btn-sm">
                                        <i class="ri-download-line"></i> Download & Add to Library
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Search Bar -->
                <div class="search-wrapper mb-3">
                    <div class="input-group">
                        <span class="input-group-text" style="background: #f8f9fa; border-right: none;">
                            <i class="ri-search-line"></i>
                        </span>
                        <input type="text" class="form-control" id="mediaSearchInput" 
                               placeholder="Search images by name..." 
                               style="border-left: none; border-right: none;">
                        <button class="btn btn-outline-secondary" type="button" onclick="loadMediaLibrary(1)" 
                                style="border-left: none;">
                            <i class="ri-refresh-line"></i>
                        </button>
                    </div>
                </div>

                <!-- Loading Indicator -->
                <div id="mediaLoading" class="text-center py-5" style="display: none;">
                    <div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem;">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-3 text-muted">Loading images...</p>
                </div>

                <!-- Error Message -->
                <div id="mediaError" class="alert alert-danger alert-dismissible fade" style="display: none;" role="alert">
                    <span id="mediaErrorText"></span>
                    <button type="button" class="btn-close" onclick="document.getElementById('mediaError').style.display='none'"></button>
                </div>

                <!-- Success Message -->
                <div id="mediaSuccess" class="alert alert-success alert-dismissible fade" style="display: none;" role="alert">
                    <span id="mediaSuccessText"></span>
                    <button type="button" class="btn-close" onclick="document.getElementById('mediaSuccess').style.display='none'"></button>
                </div>

                <!-- Images Grid -->
                <div id="mediaImagesGrid" class="row g-2">
                    <!-- Images will be loaded here via JavaScript -->
                </div>

                <!-- Pagination -->
                <nav id="mediaPagination" class="mt-4" style="display: none;">
                    <ul class="pagination justify-content-center pagination-sm">
                        <!-- Pagination will be generated by JavaScript -->
                    </ul>
                </nav>
            </div>
            <div class="modal-footer" style="background: #f8f9fa; border-top: 1px solid #dee2e6;">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">
                    <i class="ri-close-line"></i> Close
                </button>
                <button type="button" class="btn btn-primary btn-sm" id="mediaSelectBtn" onclick="selectMediaImage()" disabled>
                    <i class="ri-check-line"></i> Select Image
                </button>
            </div>
        </div>
    </div>
</div>

<style>
/* Media Library Modal Styles */
#mediaLibraryModal .modal-content {
    box-shadow: 0 10px 40px rgba(0,0,0,0.2);
}

#mediaLibraryModal .nav-tabs .nav-link {
    border: none;
    color: #6c757d;
    font-weight: 500;
    padding: 10px 20px;
    transition: all 0.3s;
}

#mediaLibraryModal .nav-tabs .nav-link:hover {
    color: #667eea;
    background: #f8f9fa;
}

#mediaLibraryModal .nav-tabs .nav-link.active {
    color: #667eea;
    background: transparent;
    border-bottom: 3px solid #667eea;
}

.upload-card {
    background: #f8f9fa;
    border-radius: 10px;
    padding: 20px;
    border: 2px dashed #dee2e6;
    transition: all 0.3s;
}

.upload-card:hover {
    border-color: #667eea;
    background: #f0f4ff;
}

.upload-area {
    text-align: center;
    padding: 30px;
    cursor: pointer;
    transition: all 0.3s;
}

.upload-area:hover {
    transform: scale(1.02);
}

.upload-icon {
    font-size: 48px;
    color: #667eea;
    margin-bottom: 15px;
}

.upload-text {
    font-size: 16px;
    font-weight: 500;
    color: #495057;
    margin-bottom: 5px;
}

.upload-hint {
    font-size: 12px;
    color: #6c757d;
    margin: 0;
}

.search-wrapper .form-control:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
}

/* Image Grid Styles */
.media-image-item {
    position: relative;
    cursor: pointer;
    border: 2px solid #e9ecef;
    border-radius: 10px;
    overflow: hidden;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    background: #fff;
    aspect-ratio: 1;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
}

.media-image-item:hover {
    border-color: #667eea;
    transform: translateY(-4px) scale(1.02);
    box-shadow: 0 8px 16px rgba(102, 126, 234, 0.2);
}

.media-image-item.selected {
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.3);
    transform: scale(1.05);
}

.media-image-item img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s;
}

.media-image-item:hover img {
    transform: scale(1.1);
}

.media-image-item .selected-badge {
    position: absolute;
    top: 8px;
    right: 8px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 50%;
    width: 28px;
    height: 28px;
    display: none;
    align-items: center;
    justify-content: center;
    font-size: 16px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.2);
    z-index: 10;
}

.media-image-item.selected .selected-badge {
    display: flex;
    animation: bounceIn 0.3s;
}

@keyframes bounceIn {
    0% { transform: scale(0); }
    50% { transform: scale(1.2); }
    100% { transform: scale(1); }
}

.media-image-item .image-overlay {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    background: linear-gradient(to top, rgba(0,0,0,0.8), transparent);
    color: white;
    padding: 10px;
    font-size: 11px;
    opacity: 0;
    transition: opacity 0.3s;
    transform: translateY(10px);
}

.media-image-item:hover .image-overlay {
    opacity: 1;
    transform: translateY(0);
}

.media-image-item .image-overlay strong {
    display: block;
    margin-bottom: 3px;
}

.media-image-item .image-format-badge {
    position: absolute;
    top: 8px;
    left: 8px;
    background: rgba(0,0,0,0.6);
    color: white;
    padding: 2px 8px;
    border-radius: 4px;
    font-size: 10px;
    font-weight: 600;
    text-transform: uppercase;
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 60px 20px;
    color: #6c757d;
}

.empty-state i {
    font-size: 64px;
    color: #dee2e6;
    margin-bottom: 20px;
}

.empty-state p {
    font-size: 16px;
    margin: 0;
}

/* Responsive */
@media (max-width: 768px) {
    #mediaLibraryModal .modal-dialog {
        margin: 10px;
        max-width: calc(100% - 20px);
    }
    
    .media-image-item {
        margin-bottom: 10px;
    }
}
</style>

<script>
let selectedMediaImage = null;
let currentMediaPage = 1;
let mediaTargetInput = null;
let mediaTargetPreview = null;
let mediaTargetPlaceholder = null;

// Initialize media library modal
document.addEventListener('DOMContentLoaded', function() {
    // File upload form handler
    document.getElementById('mediaUploadForm').addEventListener('submit', function(e) {
        e.preventDefault();
        uploadMediaImage();
    });

    // URL upload form handler
    document.getElementById('urlUploadForm').addEventListener('submit', function(e) {
        e.preventDefault();
        uploadImageFromUrl();
    });

    // Drag and drop
    const dropZone = document.getElementById('dropZone');
    const fileInput = document.getElementById('mediaFileInput');

    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, preventDefaults, false);
    });

    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }

    ['dragenter', 'dragover'].forEach(eventName => {
        dropZone.addEventListener(eventName, () => {
            dropZone.style.borderColor = '#667eea';
            dropZone.style.background = '#f0f4ff';
        }, false);
    });

    ['dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, () => {
            dropZone.style.borderColor = '#dee2e6';
            dropZone.style.background = '#f8f9fa';
        }, false);
    });

    dropZone.addEventListener('drop', (e) => {
        const dt = e.dataTransfer;
        const files = dt.files;
        if (files.length > 0) {
            fileInput.files = files;
            document.getElementById('mediaUploadForm').dispatchEvent(new Event('submit'));
        }
    });

    // Search handler
    let searchTimeout;
    document.getElementById('mediaSearchInput').addEventListener('keyup', function(e) {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            loadMediaLibrary(1);
        }, 500);
    });

    // Modal show event
    const modal = document.getElementById('mediaLibraryModal');
    modal.addEventListener('show.bs.modal', function() {
        loadMediaLibrary();
    });

    modal.addEventListener('hidden.bs.modal', function() {
        selectedMediaImage = null;
        mediaTargetInput = null;
        mediaTargetPreview = null;
        mediaTargetPlaceholder = null;
        document.getElementById('mediaSelectBtn').disabled = true;
        // Reset tabs
        document.getElementById('file-tab').click();
    });
});

// Open media library for specific input
function openMediaLibrary(inputId, previewId = null, placeholderId = null) {
    // Reset previous selection
    selectedMediaImage = null;
    document.getElementById('mediaSelectBtn').disabled = true;
    
    // Set target elements
    mediaTargetInput = document.getElementById(inputId);
    if (previewId) {
        mediaTargetPreview = document.getElementById(previewId);
    }
    if (placeholderId) {
        mediaTargetPlaceholder = document.getElementById(placeholderId);
    }
    
    // Clear search
    document.getElementById('mediaSearchInput').value = '';
    
    // Open modal
    const modal = new bootstrap.Modal(document.getElementById('mediaLibraryModal'));
    modal.show();
    
    // Load images when modal is shown
    setTimeout(() => {
        loadMediaLibrary(1);
    }, 300);
}

// Load media library images
function loadMediaLibrary(page = 1) {
    currentMediaPage = page;
    const search = document.getElementById('mediaSearchInput').value;
    const loading = document.getElementById('mediaLoading');
    const error = document.getElementById('mediaError');
    const grid = document.getElementById('mediaImagesGrid');
    const pagination = document.getElementById('mediaPagination');

    loading.style.display = 'block';
    error.style.display = 'none';
    grid.innerHTML = '';

    fetch(`{{ route('admin.media.images') }}?page=${page}&search=${encodeURIComponent(search)}&per_page=12`, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        loading.style.display = 'none';
        
        if (data.success && data.images.length > 0) {
            grid.innerHTML = '';
            data.images.forEach(image => {
                const col = document.createElement('div');
                col.className = 'col-md-2 col-sm-4 col-6';
                col.innerHTML = `
                    <div class="media-image-item" onclick="selectMediaItem(this, '${image.path}', '${image.url}')">
                        <div class="image-format-badge">${image.format}</div>
                        <img src="${image.url}" alt="${image.path}" loading="lazy" 
                             onerror="this.src='data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' width=\'200\' height=\'200\'%3E%3Crect fill=\'%23ddd\' width=\'200\' height=\'200\'/%3E%3C/svg%3E'">
                        <div class="selected-badge"><i class="ri-check-line"></i></div>
                        <div class="image-overlay">
                            <strong>${image.width} × ${image.height}</strong>
                            <span>${(image.size / 1024).toFixed(2)} KB</span>
                        </div>
                    </div>
                `;
                grid.appendChild(col);
            });

            // Pagination
            if (data.pagination.last_page > 1) {
                pagination.style.display = 'block';
                let paginationHTML = '<ul class="pagination justify-content-center">';
                
                // Previous button
                if (data.pagination.current_page > 1) {
                    paginationHTML += `<li class="page-item"><a class="page-link" href="#" onclick="loadMediaLibrary(${data.pagination.current_page - 1}); return false;"><i class="ri-arrow-left-s-line"></i></a></li>`;
                }
                
                // Page numbers (show max 5 pages)
                let startPage = Math.max(1, data.pagination.current_page - 2);
                let endPage = Math.min(data.pagination.last_page, data.pagination.current_page + 2);
                
                if (startPage > 1) {
                    paginationHTML += `<li class="page-item"><a class="page-link" href="#" onclick="loadMediaLibrary(1); return false;">1</a></li>`;
                    if (startPage > 2) {
                        paginationHTML += `<li class="page-item disabled"><span class="page-link">...</span></li>`;
                    }
                }
                
                for (let i = startPage; i <= endPage; i++) {
                    if (i === data.pagination.current_page) {
                        paginationHTML += `<li class="page-item active"><span class="page-link">${i}</span></li>`;
                    } else {
                        paginationHTML += `<li class="page-item"><a class="page-link" href="#" onclick="loadMediaLibrary(${i}); return false;">${i}</a></li>`;
                    }
                }
                
                if (endPage < data.pagination.last_page) {
                    if (endPage < data.pagination.last_page - 1) {
                        paginationHTML += `<li class="page-item disabled"><span class="page-link">...</span></li>`;
                    }
                    paginationHTML += `<li class="page-item"><a class="page-link" href="#" onclick="loadMediaLibrary(${data.pagination.last_page}); return false;">${data.pagination.last_page}</a></li>`;
                }
                
                // Next button
                if (data.pagination.current_page < data.pagination.last_page) {
                    paginationHTML += `<li class="page-item"><a class="page-link" href="#" onclick="loadMediaLibrary(${data.pagination.current_page + 1}); return false;"><i class="ri-arrow-right-s-line"></i></a></li>`;
                }
                
                paginationHTML += '</ul>';
                pagination.innerHTML = paginationHTML;
            } else {
                pagination.style.display = 'none';
            }
        } else {
            grid.innerHTML = `
                <div class="col-12">
                    <div class="empty-state">
                        <i class="ri-image-line"></i>
                        <p>No images found</p>
                        <small class="text-muted">Upload your first image to get started</small>
                    </div>
                </div>
            `;
            pagination.style.display = 'none';
        }
    })
    .catch(err => {
        loading.style.display = 'none';
        error.style.display = 'block';
        document.getElementById('mediaErrorText').textContent = 'Error loading images: ' + err.message;
    });
}

// Select media item
function selectMediaItem(element, path, url) {
    // Remove previous selection
    document.querySelectorAll('.media-image-item').forEach(item => {
        item.classList.remove('selected');
    });
    
    // Add selection to clicked item
    element.classList.add('selected');
    selectedMediaImage = { path: path, url: url };
    document.getElementById('mediaSelectBtn').disabled = false;
}

// Select and apply image
function selectMediaImage() {
    if (!selectedMediaImage || !mediaTargetInput) return;

    // Find or create hidden input for media library path
    const hiddenInputId = mediaTargetInput.id + '_path';
    let hiddenInput = document.getElementById(hiddenInputId);
    
    if (!hiddenInput) {
        // Create hidden input if it doesn't exist
        hiddenInput = document.createElement('input');
        hiddenInput.type = 'hidden';
        const fieldName = mediaTargetInput.name;
        hiddenInput.name = fieldName + '_path';
        hiddenInput.id = hiddenInputId;
        mediaTargetInput.parentNode.appendChild(hiddenInput);
    }
    
    // Set the path in hidden input
    hiddenInput.value = selectedMediaImage.path;
    
    // Clear file input (so it doesn't override the media library selection)
    if (mediaTargetInput.type === 'file') {
        mediaTargetInput.value = '';
    }

    // Update preview
    if (mediaTargetPreview) {
        mediaTargetPreview.src = selectedMediaImage.url;
        mediaTargetPreview.style.display = 'block';
    }

    // Hide placeholder
    if (mediaTargetPlaceholder) {
        mediaTargetPlaceholder.style.display = 'none';
    }

    // Close modal
    const modal = bootstrap.Modal.getInstance(document.getElementById('mediaLibraryModal'));
    modal.hide();
}

// Upload media image from file
function uploadMediaImage() {
    const form = document.getElementById('mediaUploadForm');
    const formData = new FormData(form);
    const loading = document.getElementById('mediaLoading');
    const error = document.getElementById('mediaError');
    const success = document.getElementById('mediaSuccess');

    if (!formData.get('image') || formData.get('image').size === 0) {
        error.style.display = 'block';
        document.getElementById('mediaErrorText').textContent = 'Please select an image file';
        return;
    }

    loading.style.display = 'block';
    error.style.display = 'none';
    success.style.display = 'none';

    fetch('{{ route("admin.media.store") }}', {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        loading.style.display = 'none';
        if (data.success) {
            form.reset();
            success.style.display = 'block';
            document.getElementById('mediaSuccessText').textContent = data.message || 'Image uploaded successfully!';
            loadMediaLibrary(currentMediaPage);
            // Auto-select the uploaded image
            setTimeout(() => {
                const uploadedItem = Array.from(document.querySelectorAll('.media-image-item')).find(item => {
                    return item.querySelector('img').src.includes(data.path);
                });
                if (uploadedItem) {
                    selectMediaItem(uploadedItem, data.path, data.url);
                }
            }, 500);
        } else {
            error.style.display = 'block';
            document.getElementById('mediaErrorText').textContent = data.message || 'Upload failed';
        }
    })
    .catch(err => {
        loading.style.display = 'none';
        error.style.display = 'block';
        document.getElementById('mediaErrorText').textContent = 'Upload error: ' + err.message;
    });
}

// Upload image from URL
function uploadImageFromUrl() {
    const url = document.getElementById('imageUrlInput').value.trim();
    const loading = document.getElementById('mediaLoading');
    const error = document.getElementById('mediaError');
    const success = document.getElementById('mediaSuccess');

    if (!url) {
        error.style.display = 'block';
        document.getElementById('mediaErrorText').textContent = 'Please enter an image URL';
        return;
    }

    // Validate URL
    try {
        new URL(url);
    } catch (e) {
        error.style.display = 'block';
        document.getElementById('mediaErrorText').textContent = 'Please enter a valid URL';
        return;
    }

    loading.style.display = 'block';
    error.style.display = 'none';
    success.style.display = 'none';

    // Download image from URL and upload
    fetch('{{ route("admin.media.store") }}', {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            image_url: url
        })
    })
    .then(response => response.json())
    .then(data => {
        loading.style.display = 'none';
        if (data.success) {
            document.getElementById('urlUploadForm').reset();
            success.style.display = 'block';
            document.getElementById('mediaSuccessText').textContent = data.message || 'Image downloaded and added successfully!';
            loadMediaLibrary(currentMediaPage);
            // Auto-select the uploaded image
            setTimeout(() => {
                const uploadedItem = Array.from(document.querySelectorAll('.media-image-item')).find(item => {
                    return item.querySelector('img').src.includes(data.path);
                });
                if (uploadedItem) {
                    selectMediaItem(uploadedItem, data.path, data.url);
                }
            }, 500);
        } else {
            error.style.display = 'block';
            document.getElementById('mediaErrorText').textContent = data.message || 'Failed to download image from URL';
        }
    })
    .catch(err => {
        loading.style.display = 'none';
        error.style.display = 'block';
        document.getElementById('mediaErrorText').textContent = 'Error: ' + err.message;
    });
}
</script>

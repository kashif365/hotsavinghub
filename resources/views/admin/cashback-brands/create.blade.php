@extends('admin.layouts.app')

@section('content')
<div class="container mt-4">
    <div class="card">
        <div class="card-header">
            <h4 class="mb-0">Add New Cashback Brand</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.cashback-brands.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Brand Logo <span class="text-danger">*</span></label>
                        <div class="image-upload-box" onclick="document.getElementById('logo').click()">
                            <img id="logo_preview" style="display:none;">
                            <svg id="logo_placeholder" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                <path d="M20,5A2,2 0 0,1 22,7V17A2,2 0 0,1 20,19H4C2.89,19 2,18.1 2,17V7C2,5.89 2.89,5 4,5H20M5,16H19L14.5,10L11,14.5L8.5,11.5L5,16Z" />
                            </svg>
                        </div>
                        <div class="d-flex gap-2 mt-2 flex-wrap">
                            <button type="button" class="btn btn-sm btn-outline-primary flex-fill" onclick="document.getElementById('logo').click()">
                                <i class="ri-upload-line"></i> Upload
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-info flex-fill" onclick="openMediaLibrary('logo', 'logo_preview', 'logo_placeholder')">
                                <i class="ri-image-line"></i> Media
                            </button>
                        </div>
                        <input type="file" class="d-none @error('logo') is-invalid @enderror" id="logo" name="logo" accept="image/*" onchange="previewCardImage(event, 'logo_preview', 'logo_placeholder')">
                        <input type="hidden" id="logo_path" name="logo_path">
                        @error('logo')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted d-block mt-2">Required. Square logo recommended — it is displayed inside a circle. Max 2MB.</small>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="store_name" class="form-label">Store Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('store_name') is-invalid @enderror" id="store_name" name="store_name" value="{{ old('store_name') }}" maxlength="255" placeholder="e.g. Best Buy" required>
                        @error('store_name')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror

                        <label for="cashback_rate" class="form-label mt-3">Cash Back Rate (%) <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('cashback_rate') is-invalid @enderror" id="cashback_rate" name="cashback_rate" value="{{ old('cashback_rate') }}" maxlength="20" placeholder="e.g. 7" required>
                        <small class="form-text text-muted">Just the number, e.g. "7" — it will be shown as "7% Cash Back".</small>
                        @error('cashback_rate')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="redirect_url" class="form-label">Redirect URL</label>
                        <input type="text" class="form-control @error('redirect_url') is-invalid @enderror" id="redirect_url" name="redirect_url" value="{{ old('redirect_url') }}" maxlength="2048" placeholder="https://... or /store/...">
                        <small class="form-text text-muted">Where the circle links to. Leave blank to disable the link.</small>
                        @error('redirect_url')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label for="status" class="form-label">Status</label>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="status" name="status" value="1" checked>
                        <label class="form-check-label" for="status">Active</label>
                    </div>
                </div>

                <div class="mb-3">
                    <button type="submit" class="btn btn-primary">Create Cashback Brand</button>
                    <a href="{{ route('admin.cashback-brands.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
function previewCardImage(event, previewId, placeholderId) {
    const file = event.target.files && event.target.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = function () {
        const preview = document.getElementById(previewId);
        const placeholder = document.getElementById(placeholderId);
        if (preview) {
            preview.src = reader.result;
            preview.style.display = 'block';
        }
        if (placeholder) {
            placeholder.style.display = 'none';
        }
    };
    reader.readAsDataURL(file);
}
</script>
@endsection

@extends('admin.layouts.app')

@section('content')
<div class="container mt-4">
    <div class="card">
        <div class="card-header">
            <h4 class="mb-0">Add New Slider</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.sliders.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="mb-3">
                    <label class="form-label">Slider Image <span class="text-danger">*</span></label>
                    <div class="image-upload-box" onclick="document.getElementById('background_image').click()">
                        <img id="slider_image_preview" style="display:none;">
                        <svg id="slider_image_placeholder" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                            <path d="M20,5A2,2 0 0,1 22,7V17A2,2 0 0,1 20,19H4C2.89,19 2,18.1 2,17V7C2,5.89 2.89,5 4,5H20M5,16H19L14.5,10L11,14.5L8.5,11.5L5,16Z" />
                        </svg>
                    </div>
                    <div class="d-flex gap-2 mt-2 flex-wrap">
                        <button type="button" class="btn btn-sm btn-outline-primary flex-fill" onclick="document.getElementById('background_image').click()">
                            <i class="ri-upload-line"></i> Upload
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-info flex-fill" onclick="openMediaLibrary('background_image', 'slider_image_preview', 'slider_image_placeholder')">
                            <i class="ri-image-line"></i> Media
                        </button>
                    </div>
                    <input type="file" class="d-none @error('background_image') is-invalid @enderror" id="background_image" name="background_image" accept="image/*" onchange="previewSliderImage(event, 'slider_image_preview', 'slider_image_placeholder')">
                    <input type="hidden" id="background_image_path" name="background_image_path">
                    @error('background_image')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                    <small class="form-text text-muted d-block mt-2">Upload an image for the slider (Max: 2MB)</small>
                </div>

                <div class="mb-3">
                    <label for="status" class="form-label">Status</label>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="status" name="status" value="1" checked>
                        <label class="form-check-label" for="status">Active</label>
                    </div>
                </div>

                <div class="mb-3">
                    <button type="submit" class="btn btn-primary">Create Slider</button>
                    <a href="{{ route('admin.sliders.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
function previewSliderImage(event, previewId, placeholderId) {
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


@extends('admin.layouts.app')

@section('content')
<div class="container mt-4">
    <div class="card">
        <div class="card-header">
            <h4 class="mb-0">Edit Slider</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.sliders.update', $slider) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Main Image (right, ~52% width)</label>
                        <div class="image-upload-box" onclick="document.getElementById('background_image').click()">
                            <img id="background_image_preview"
                                 src="{{ $slider->background_image ? asset($slider->background_image) : '' }}"
                                 style="{{ $slider->background_image ? '' : 'display:none;' }}">
                            <svg id="background_image_placeholder" style="{{ $slider->background_image ? 'display:none;' : '' }}" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                <path d="M20,5A2,2 0 0,1 22,7V17A2,2 0 0,1 20,19H4C2.89,19 2,18.1 2,17V7C2,5.89 2.89,5 4,5H20M5,16H19L14.5,10L11,14.5L8.5,11.5L5,16Z" />
                            </svg>
                        </div>
                        <div class="d-flex gap-2 mt-2 flex-wrap">
                            <button type="button" class="btn btn-sm btn-outline-primary flex-fill" onclick="document.getElementById('background_image').click()">
                                <i class="ri-upload-line"></i> Upload
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-info flex-fill" onclick="openMediaLibrary('background_image', 'background_image_preview', 'background_image_placeholder')">
                                <i class="ri-image-line"></i> Media
                            </button>
                        </div>
                        <input type="file" class="d-none @error('background_image') is-invalid @enderror" id="background_image" name="background_image" accept="image/*" onchange="previewSliderImage(event, 'background_image_preview', 'background_image_placeholder')">
                        <input type="hidden" id="background_image_path" name="background_image_path">
                        @error('background_image')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted d-block mt-2">Leave empty to keep current image. Max 2MB.</small>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Secondary Image (left, ~25% width)</label>
                        <div class="image-upload-box" onclick="document.getElementById('secondary_image').click()">
                            <img id="secondary_image_preview"
                                 src="{{ $slider->secondary_image ? asset($slider->secondary_image) : '' }}"
                                 style="{{ $slider->secondary_image ? '' : 'display:none;' }}">
                            <svg id="secondary_image_placeholder" style="{{ $slider->secondary_image ? 'display:none;' : '' }}" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                <path d="M20,5A2,2 0 0,1 22,7V17A2,2 0 0,1 20,19H4C2.89,19 2,18.1 2,17V7C2,5.89 2.89,5 4,5H20M5,16H19L14.5,10L11,14.5L8.5,11.5L5,16Z" />
                            </svg>
                        </div>
                        <div class="d-flex gap-2 mt-2 flex-wrap">
                            <button type="button" class="btn btn-sm btn-outline-primary flex-fill" onclick="document.getElementById('secondary_image').click()">
                                <i class="ri-upload-line"></i> Upload
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-info flex-fill" onclick="openMediaLibrary('secondary_image', 'secondary_image_preview', 'secondary_image_placeholder')">
                                <i class="ri-image-line"></i> Media
                            </button>
                        </div>
                        <input type="file" class="d-none @error('secondary_image') is-invalid @enderror" id="secondary_image" name="secondary_image" accept="image/*" onchange="previewSliderImage(event, 'secondary_image_preview', 'secondary_image_placeholder')">
                        <input type="hidden" id="secondary_image_path" name="secondary_image_path">
                        @error('secondary_image')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted d-block mt-2">Optional. Hidden on mobile and when not set. Max 2MB.</small>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Logo Badge</label>
                        <div class="image-upload-box" onclick="document.getElementById('logo').click()">
                            <img id="logo_preview"
                                 src="{{ $slider->logo ? asset($slider->logo) : '' }}"
                                 style="{{ $slider->logo ? '' : 'display:none;' }}">
                            <svg id="logo_placeholder" style="{{ $slider->logo ? 'display:none;' : '' }}" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
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
                        <input type="file" class="d-none @error('logo') is-invalid @enderror" id="logo" name="logo" accept="image/*" onchange="previewSliderImage(event, 'logo_preview', 'logo_placeholder')">
                        <input type="hidden" id="logo_path" name="logo_path">
                        @error('logo')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted d-block mt-2">Optional. Circular badge. Hidden cleanly if not set. Max 2MB.</small>
                    </div>
                </div>

                <hr>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="label" class="form-label">Label (eyebrow text)</label>
                        <input type="text" class="form-control @error('label') is-invalid @enderror" id="label" name="label" value="{{ old('label', $slider->label) }}" maxlength="255" placeholder="e.g. LIMITED TIME">
                        @error('label')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="heading" class="form-label">Heading</label>
                        <input type="text" class="form-control @error('heading') is-invalid @enderror" id="heading" name="heading" value="{{ old('heading', $slider->heading) }}" maxlength="255" placeholder="e.g. Up to 50% Off Everything">
                        <small class="form-text text-muted">Leave empty for a simple full-image slide (no content panel).</small>
                        @error('heading')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="subtitle" class="form-label">Subtitle</label>
                        <input type="text" class="form-control @error('subtitle') is-invalid @enderror" id="subtitle" name="subtitle" value="{{ old('subtitle', $slider->subtitle) }}" maxlength="255" placeholder="e.g. at Nike">
                        @error('subtitle')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="cta_text" class="form-label">CTA Text</label>
                        <input type="text" class="form-control @error('cta_text') is-invalid @enderror" id="cta_text" name="cta_text" value="{{ old('cta_text', $slider->cta_text) }}" maxlength="255" placeholder="Shop Now">
                        @error('cta_text')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="cta_url" class="form-label">CTA / Slide Link URL</label>
                        <input type="text" class="form-control @error('cta_url') is-invalid @enderror" id="cta_url" name="cta_url" value="{{ old('cta_url', $slider->cta_url) }}" maxlength="2048" placeholder="https://... or /category/...">
                        <small class="form-text text-muted">If set, the entire slide becomes clickable.</small>
                        @error('cta_url')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="badge_color" class="form-label">Badge / Theme Color</label>
                        <div class="color-input-group">
                            <div class="input-group">
                                <input type="color" class="form-control form-control-color" id="badge_color" name="badge_color" value="{{ old('badge_color', $slider->badge_color ?: '#2951c4') }}">
                                <input type="text" class="form-control color-text-input" value="{{ old('badge_color', $slider->badge_color ?: '#2951c4') }}" readonly>
                            </div>
                        </div>
                        <small class="form-text text-muted">Falls back to the site's primary color if left default.</small>
                        @error('badge_color')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label for="status" class="form-label">Status</label>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="status" name="status" value="1" {{ $slider->status ? 'checked' : '' }}>
                        <label class="form-check-label" for="status">Active</label>
                    </div>
                </div>

                <div class="mb-3">
                    <button type="submit" class="btn btn-primary">Update Slider</button>
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

document.addEventListener('DOMContentLoaded', function() {
    const colorInput = document.getElementById('badge_color');
    if (colorInput) {
        const textInput = colorInput.parentElement.querySelector('.color-text-input');
        colorInput.addEventListener('input', function() {
            if (textInput) textInput.value = colorInput.value;
        });
    }
});
</script>
@endsection

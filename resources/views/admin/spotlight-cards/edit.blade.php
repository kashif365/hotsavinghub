@extends('admin.layouts.app')

@section('content')
<div class="container mt-4">
    <div class="card">
        <div class="card-header">
            <h4 class="mb-0">Edit Spotlight Card</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.spotlight-cards.update', $spotlightCard) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Card Image / Photo</label>
                        <div class="image-upload-box" onclick="document.getElementById('image').click()">
                            <img id="image_preview"
                                 src="{{ $spotlightCard->image ? asset($spotlightCard->image) : '' }}"
                                 style="{{ $spotlightCard->image ? '' : 'display:none;' }}">
                            <svg id="image_placeholder" style="{{ $spotlightCard->image ? 'display:none;' : '' }}" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                <path d="M20,5A2,2 0 0,1 22,7V17A2,2 0 0,1 20,19H4C2.89,19 2,18.1 2,17V7C2,5.89 2.89,5 4,5H20M5,16H19L14.5,10L11,14.5L8.5,11.5L5,16Z" />
                            </svg>
                        </div>
                        <div class="d-flex gap-2 mt-2 flex-wrap">
                            <button type="button" class="btn btn-sm btn-outline-primary flex-fill" onclick="document.getElementById('image').click()">
                                <i class="ri-upload-line"></i> Upload
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-info flex-fill" onclick="openMediaLibrary('image', 'image_preview', 'image_placeholder')">
                                <i class="ri-image-line"></i> Media
                            </button>
                        </div>
                        <input type="file" class="d-none @error('image') is-invalid @enderror" id="image" name="image" accept="image/*" onchange="previewCardImage(event, 'image_preview', 'image_placeholder')">
                        <input type="hidden" id="image_path" name="image_path">
                        @error('image')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted d-block mt-2">Leave empty to keep current image. Max 2MB.</small>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Logo (optional)</label>
                        <div class="image-upload-box" onclick="document.getElementById('logo').click()">
                            <img id="logo_preview"
                                 src="{{ $spotlightCard->logo ? asset($spotlightCard->logo) : '' }}"
                                 style="{{ $spotlightCard->logo ? '' : 'display:none;' }}">
                            <svg id="logo_placeholder" style="{{ $spotlightCard->logo ? 'display:none;' : '' }}" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
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
                        <small class="form-text text-muted d-block mt-2">Leave empty to keep current logo.</small>
                    </div>
                </div>

                <hr>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="heading" class="form-label">Heading</label>
                        <input type="text" class="form-control @error('heading') is-invalid @enderror" id="heading" name="heading" value="{{ old('heading', $spotlightCard->heading) }}" maxlength="255" placeholder="e.g. Shop Summer Essentials for Up to 77% Off">
                        @error('heading')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="cta_label" class="form-label">CTA Label</label>
                        <input type="text" class="form-control @error('cta_label') is-invalid @enderror" id="cta_label" name="cta_label" value="{{ old('cta_label', $spotlightCard->cta_label) }}" maxlength="255" placeholder="e.g. SHOP NOW">
                        @error('cta_label')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="cta_url" class="form-label">CTA Link URL</label>
                        <input type="text" class="form-control @error('cta_url') is-invalid @enderror" id="cta_url" name="cta_url" value="{{ old('cta_url', $spotlightCard->cta_url) }}" maxlength="2048" placeholder="https://... or /category/...">
                        <small class="form-text text-muted">The whole card becomes clickable to this URL.</small>
                        @error('cta_url')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="bg_color" class="form-label">Fallback Background Color</label>
                        <div class="color-input-group">
                            <div class="input-group">
                                <input type="color" class="form-control form-control-color" id="bg_color" name="bg_color" value="{{ old('bg_color', $spotlightCard->bg_color ?: '#0f172a') }}">
                                <input type="text" class="form-control color-text-input" value="{{ old('bg_color', $spotlightCard->bg_color ?: '#0f172a') }}" readonly>
                            </div>
                        </div>
                        <small class="form-text text-muted">Used behind the card content if no image is set.</small>
                        @error('bg_color')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label for="status" class="form-label">Status</label>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="status" name="status" value="1" {{ $spotlightCard->status ? 'checked' : '' }}>
                        <label class="form-check-label" for="status">Active</label>
                    </div>
                </div>

                <div class="mb-3">
                    <button type="submit" class="btn btn-primary">Update Spotlight Card</button>
                    <a href="{{ route('admin.spotlight-cards.index') }}" class="btn btn-secondary">Cancel</a>
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

document.addEventListener('DOMContentLoaded', function() {
    const colorInput = document.getElementById('bg_color');
    if (colorInput) {
        const textInput = colorInput.parentElement.querySelector('.color-text-input');
        colorInput.addEventListener('input', function() {
            if (textInput) textInput.value = colorInput.value;
        });
    }
});
</script>
@endsection

@csrf

<!-- Event Name -->
<div class="row mb-3">
    <div class="col-md-6">
        <div class="form-floating form-floating-outline">
            <input type="text" name="event_name" class="form-control" placeholder="Event Name" 
                value="{{ old('event_name', $event->event_name ?? '') }}" required>
            <label>Event Name *</label>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-floating form-floating-outline">
            <input type="text" name="event_type" class="form-control" placeholder="Event Type"
                value="{{ old('event_type', $event->event_type ?? '') }}">
            <label>Event Type</label>
        </div>
    </div>
</div>

<!-- Date Fields -->
<div class="row mb-3">
    <div class="col-md-6">
        <label for="dateAvailable" class="form-label">Date Available</label>
        <input type="date" class="form-control" id="dateAvailable" name="date_available"
            value="{{ old('date_available', $event->date_available ?? '') }}">
    </div>
    <div class="col-md-6">
        <label for="dateExpiry" class="form-label">Date Expiry</label>
        <input type="date" class="form-control" id="dateExpiry" name="date_expiry"
            value="{{ old('date_expiry', $event->date_expiry ?? '') }}">
    </div>
</div>

<!-- SEO Fields -->
<div class="row mb-3">
    <div class="col-md-6">
        <div class="form-floating form-floating-outline">
            <input type="text" name="seo_url" class="form-control" placeholder="SEO URL" required
                value="{{ old('seo_url', $event->seo_url ?? '') }}">
            <label>SEO URL *</label>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-floating form-floating-outline">
            <input type="text" name="meta_title" class="form-control" placeholder="Meta Title"
                value="{{ old('meta_title', $event->meta_title ?? '') }}">
            <label>Meta Title</label>
        </div>
    </div>
</div>

<div class="row mb-3">
    <div class="col-md-6">
        <div class="form-floating form-floating-outline">
            <input type="text" name="meta_keywords" class="form-control" placeholder="Meta Keywords"
                value="{{ old('meta_keywords', $event->meta_keywords ?? '') }}">
            <label>Meta Keywords</label>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-floating form-floating-outline mb-3">
            <textarea class="form-control h-px-100" name="meta_description" rows="3" placeholder="Meta Description">{{ old('meta_description', $event->meta_description ?? '') }}</textarea>
            <label>Meta Description</label>
        </div>
    </div>
</div>

<!-- Image Uploads -->
<div class="row mb-3">

   {{-- Event Image --}}
<div class="col-md-3">
    <label>Event Image</label>
    <div class="image-upload-box" onclick="document.getElementById('image').click()">
        <img id="image_preview" 
             src="{{ isset($event) && $event->front_image ? asset($event->front_image) : '' }}" 
             style="{{ isset($event) && $event->front_image ? '' : 'display:none;' }}">
        <svg id="image_placeholder" style="{{ isset($event) && $event->front_image ? 'display:none;' : '' }}" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
            <path d="M20,5A2,2 0 0,1 22,7V17A2,2 0 0,1 20,19H4C2.89,19 2,18.1 
            2,17V7C2,5.89 2.89,5 4,5H20M5,16H19L14.5,10L11,14.5
            L8.5,11.5L5,16Z" />
        </svg>
    </div>
    <div class="d-flex gap-2 mt-2">
        <button type="button" class="btn btn-sm btn-outline-primary flex-fill" onclick="document.getElementById('image').click()">
            <i class="ri-upload-line"></i> Upload
        </button>
        <button type="button" class="btn btn-sm btn-outline-info flex-fill" onclick="openMediaLibrary('image', 'image_preview', 'image_placeholder')">
            <i class="ri-image-line"></i> Media
        </button>
    </div>
    <input type="file" id="image" name="front_image" style="display:none;" accept="image/*" 
           onchange="previewImage(event, 'image_preview', 'image_placeholder')">
    <input type="hidden" id="image_path" name="front_image_path">
</div>


    {{-- Button Icon --}}
    <div class="col-md-3">
        <label>Button Image Menu Icon</label>
        <div class="image-upload-box" onclick="document.getElementById('button_icon').click()">
            <img id="button_icon_preview" 
                 src="{{ isset($event) && $event->button_icon ? asset($event->button_icon) : '' }}" 
                 style="{{ isset($event) && $event->button_icon ? '' : 'display:none;' }}">
            <svg id="button_icon_placeholder" style="{{ isset($event) && $event->button_icon ? 'display:none;' : '' }}" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                <path d="M20,5A2,2 0 0,1 22,7V17A2,2 0 0,1 20,19H4C2.89,19 
                2,18.1 2,17V7C2,5.89 2.89,5 4,5H20M5,16H19L14.5,10
                L11,14.5L8.5,11.5L5,16Z" />
            </svg>
        </div>
        <div class="d-flex gap-2 mt-2">
            <button type="button" class="btn btn-sm btn-outline-primary flex-fill" onclick="document.getElementById('button_icon').click()">
                <i class="ri-upload-line"></i> Upload
            </button>
            <button type="button" class="btn btn-sm btn-outline-info flex-fill" onclick="openMediaLibrary('button_icon', 'button_icon_preview', 'button_icon_placeholder')">
                <i class="ri-image-line"></i> Media
            </button>
        </div>
        <input type="file" id="button_icon" name="button_icon" style="display:none;" accept="image/*" 
               onchange="previewImage(event, 'button_icon_preview', 'button_icon_placeholder')">
        <input type="hidden" id="button_icon_path" name="button_icon_path">
    </div>

    {{-- Cover Image --}}
    <div class="col-md-3">
        <label>Cover Image</label>
        <div class="image-upload-box" onclick="document.getElementById('cover_image').click()">
            <img id="cover_image_preview" 
                 src="{{ isset($event) && $event->cover_image ? asset($event->cover_image) : '' }}" 
                 style="{{ isset($event) && $event->cover_image ? '' : 'display:none;' }}">
            <svg id="cover_image_placeholder" style="{{ isset($event) && $event->cover_image ? 'display:none;' : '' }}" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                <path d="M20,5A2,2 0 0,1 22,7V17A2,2 0 0,1 20,19H4C2.89,19 
                2,18.1 2,17V7C2,5.89 2.89,5 4,5H20M5,16H19L14.5,10
                L11,14.5L8.5,11.5L5,16Z" />
            </svg>
        </div>
        <div class="d-flex gap-2 mt-2">
            <button type="button" class="btn btn-sm btn-outline-primary flex-fill" onclick="document.getElementById('cover_image').click()">
                <i class="ri-upload-line"></i> Upload
            </button>
            <button type="button" class="btn btn-sm btn-outline-info flex-fill" onclick="openMediaLibrary('cover_image', 'cover_image_preview', 'cover_image_placeholder')">
                <i class="ri-image-line"></i> Media
            </button>
        </div>
        <input type="file" id="cover_image" name="cover_image" style="display:none;" accept="image/*" 
               onchange="previewImage(event, 'cover_image_preview', 'cover_image_placeholder')">
        <input type="hidden" id="cover_image_path" name="cover_image_path">
    </div>

    {{-- No Coupon Cover --}}
    <div class="col-md-3">
        <label>No Coupon Page Cover Image</label>
        <div class="image-upload-box" onclick="document.getElementById('no_coupon_cover').click()">
            <img id="no_coupon_cover_preview" 
                 src="{{ isset($event) && $event->no_coupon_cover ? asset($event->no_coupon_cover) : '' }}" 
                 style="{{ isset($event) && $event->no_coupon_cover ? '' : 'display:none;' }}">
            <svg id="no_coupon_cover_placeholder" style="{{ isset($event) && $event->no_coupon_cover ? 'display:none;' : '' }}" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                <path d="M20,5A2,2 0 0,1 22,7V17A2,2 0 0,1 20,19H4C2.89,19 
                2,18.1 2,17V7C2,5.89 2.89,5 4,5H20M5,16H19L14.5,10
                L11,14.5L8.5,11.5L5,16Z" />
            </svg>
        </div>
        <div class="d-flex gap-2 mt-2">
            <button type="button" class="btn btn-sm btn-outline-primary flex-fill" onclick="document.getElementById('no_coupon_cover').click()">
                <i class="ri-upload-line"></i> Upload
            </button>
            <button type="button" class="btn btn-sm btn-outline-info flex-fill" onclick="openMediaLibrary('no_coupon_cover', 'no_coupon_cover_preview', 'no_coupon_cover_placeholder')">
                <i class="ri-image-line"></i> Media
            </button>
        </div>
        <input type="file" id="no_coupon_cover" name="no_coupon_cover" style="display:none;" accept="image/*" 
               onchange="previewImage(event, 'no_coupon_cover_preview', 'no_coupon_cover_placeholder')">
        <input type="hidden" id="no_coupon_cover_path" name="no_coupon_cover_path">
    </div>

</div>


<!-- Event Short Content -->
<div class="form-floating form-floating-outline mb-3">
    <textarea class="form-control h-px-100" name="event_short_content" placeholder="Event short content">{{ old('event_short_content', $event->event_short_content ?? '') }}</textarea>
    <label>Event short content</label>
</div>

<!-- Detail Description -->
<div class="form-floating form-floating-outline mb-3">
    <textarea class="form-control h-px-100" name="detail_description" rows="3" placeholder="Detail Description">{{ old('detail_description', $event->detail_description ?? '') }}</textarea>
    <label>Detail Description</label>
</div>

<!-- Enable/Disable Switch -->
<div class="d-flex align-items-center mb-3">
    <label>Disable</label>
    <div class="form-check form-switch mx-2">
        <input class="form-check-input" type="checkbox" role="switch" id="statusSwitch" name="status" value="1"
            {{ old('status', $event->status ?? 1) ? 'checked' : '' }}>
    </div>
    <label>Enable</label>
</div>

<!-- Show Footer Switch -->
<div class="d-flex align-items-center mb-3">
    <label>Hide from Footer</label>
    <div class="form-check form-switch mx-2">
        <input class="form-check-input" type="checkbox" role="switch" id="showFooterSwitch" name="show_footer" value="1"
            {{ old('show_footer', $event->show_footer ?? 0) ? 'checked' : '' }}>
    </div>
    <label>Show in Footer</label>
</div>

<!-- Save Button -->
<div class="text-end">
    <button type="submit" class="btn btn-primary">
        {{ isset($event) ? '💾 Update' : '💾 Save' }}
    </button>
</div>

<script>
function previewImage(event, previewId, placeholderId) {
    const file = event.target.files && event.target.files[0];
    if (!file) {
        return;
    }
    
    const reader = new FileReader();
    reader.onload = function() {
        const img = document.getElementById(previewId);
        const placeholder = document.getElementById(placeholderId);
        
        if (img) {
            img.src = reader.result;
            img.style.display = 'block';
        }
        if (placeholder) {
            placeholder.style.display = 'none';
        }
    };
    reader.readAsDataURL(file);
}
</script>



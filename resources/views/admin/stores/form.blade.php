@php
    // create/edit dono me safe:
    $store = $store ?? new \App\Models\Store;

    $selectedCategoryIds = (array) old(
        'category_ids',
        $store->relationLoaded('categories') ? $store->categories->pluck('id')->toArray() : []
    );

    $selectedEventIds = (array) old(
        'event_ids',
        $store->relationLoaded('events') ? $store->events->pluck('id')->toArray() : []
    );
@endphp

<style>
.ql-snow.ql-toolbar button svg{
        fill: #433c50 !important;
        stroke: #433c50 !important;
    }
.ql-snow.ql-toolbar button svg path{
        stroke: #433c50 !important;
    }
.ql-snow.ql-toolbar button svg line, polyline{
        stroke: #433c50 !important;
    }
</style>

@csrf
<!-- Top Toggles -->
<div class="d-flex flex-wrap mb-3 gap-3">
    <div class="form-check">
        <input type="checkbox" class="form-check-input" name="covid_disable" id="covid_disable" value="1"
            {{ old('covid_disable', $store->covid_disable ?? false) ? 'checked' : '' }}>
        <label class="form-check-label" for="covid_disable">COVID-19 Disable</label>
    </div>
    <div class="form-check">
        <input type="checkbox" class="form-check-input" name="featured" id="featured" value="1"
            {{ old('featured', $store->featured ?? false) ? 'checked' : '' }}>
        <label class="form-check-label" for="featured">Featured</label>
    </div>
    <div class="form-check">
        <input type="checkbox" class="form-check-input" name="recommended" id="recommended" value="1"
            {{ old('recommended', $store->recommended ?? false) ? 'checked' : '' }}>
        <label class="form-check-label" for="recommended">Recommended</label>
    </div>
    <div class="form-check">
        <input type="checkbox" class="form-check-input" name="auto_sort" id="auto_sort" value="1"
            {{ old('auto_sort', $store->auto_sort ?? false) ? 'checked' : '' }}>
        <label class="form-check-label" for="auto_sort">Auto Sort</label>
    </div>
    <div class="form-check">
        <input type="checkbox" class="form-check-input" name="show_trending" id="show_trending" value="1"
            {{ old('show_trending', $store->show_trending ?? false) ? 'checked' : '' }}>
        <label class="form-check-label" for="show_trending">Show Trending Stores</label>
    </div>
    <div class="form-check">
        <input type="checkbox" class="form-check-input" name="student_discount" id="student_discount" value="1"
            {{ old('student_discount', $store->student_discount ?? false) ? 'checked' : '' }}>
        <label class="form-check-label" for="student_discount">Student Discount</label>
    </div>

    <div class="d-flex align-items-center ms-auto">
        <label>Disable</label>
        <div class="form-check form-switch mx-2">
            <input class="form-check-input" type="checkbox" name="status" value="1"
                {{ old('status', $store->status ?? true) ? 'checked' : '' }}>
        </div>
        <label>Enable</label>
    </div>
</div>

<!-- Store Name + Affiliate URL -->
<div class="row mb-3">
    <div class="col-md-6">
        <div class="form-floating form-floating-outline">
            <input type="text" class="form-control" name="store_name" placeholder="Store Name"
                value="{{ old('store_name', $store->store_name ?? '') }}" required>
            <label>Store Name *</label>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-floating form-floating-outline">
            <input type="url" class="form-control" name="affiliate_url" placeholder="Affiliate URL"
                value="{{ old('affiliate_url', $store->affiliate_url ?? '') }}">
            <label>Affiliate URL</label>
        </div>
    </div>
</div>

<!-- Category + Event -->
<div class="row mb-3">
    <div class="col-md-6">
        <div class="form-floating form-floating-outline">
            <select name="categories[]" multiple class="form-select select2">
    @foreach($categories as $id => $name)
        <option value="{{ $id }}" {{ in_array($id, $store->categories->pluck('id')->toArray()) ? 'selected' : '' }}>
            {{ $name }}
        </option>
    @endforeach
</select>
            <label for="category_ids">Categories *</label>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-floating form-floating-outline">
            <select name="events[]" multiple class="form-select select2">
    @foreach($events as $id => $name)
        <option value="{{ $id }}" {{ in_array($id, $store->events->pluck('id')->toArray()) ? 'selected' : '' }}>
            {{ $name }}
        </option>
    @endforeach
</select>
            <label for="event_ids">Events</label>
        </div>
    </div>
</div>


<!-- Media Section -->
<div class="row mb-3">
    <div class="col-md-6">
        <label>Store Logo</label>
        <div class="image-upload-box" onclick="document.getElementById('store_logo').click()">
            <img id="store_logo_preview"
                 src="{{ ($store->store_logo ?? false) ? asset($store->store_logo) : '' }}"
                 style="{{ ($store->store_logo ?? false) ? '' : 'display:none;' }}">
            <!-- ✅ fixed unique placeholder id -->
            <svg id="store_logo_placeholder" style="{{ ($store->store_logo ?? false) ? 'display:none;' : '' }}" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                <path d="M20,5A2,2 0 0,1 22,7V17A2,2 0 0,1 20,19H4C2.89,19 2,18.1 2,17V7C2,5.89 2.89,5 4,5H20M5,16H19L14.5,10L11,14.5L8.5,11.5L5,16Z" />
            </svg>
        </div>
        <div class="d-flex gap-2 mt-2 flex-wrap">
            <button type="button" class="btn btn-sm btn-outline-primary flex-fill" onclick="document.getElementById('store_logo').click()">
                <i class="ri-upload-line"></i> Upload
            </button>
            <button type="button" class="btn btn-sm btn-outline-info flex-fill" onclick="openMediaLibrary('store_logo', 'store_logo_preview', 'store_logo_placeholder')">
                <i class="ri-image-line"></i> Media
            </button>
        </div>
        <input type="file" id="store_logo" name="store_logo" style="display:none;" accept="image/*"
               onchange="previewImage(event, 'store_logo_preview', 'store_logo_placeholder')">
        <input type="hidden" id="store_logo_path" name="store_logo_path">
    </div>

    <div class="col-md-6">
        <label>Cover Image</label>
        <div class="image-upload-box" onclick="document.getElementById('cover_image').click()">
            <img id="cover_image_preview"
                 src="{{ ($store->cover_image ?? false) ? asset($store->cover_image) : '' }}"
                 style="{{ ($store->cover_image ?? false) ? '' : 'display:none;' }}">
            <!-- ✅ fixed unique placeholder id -->
            <svg id="cover_image_placeholder" style="{{ ($store->cover_image ?? false) ? 'display:none;' : '' }}" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                <path d="M20,5A2,2 0 0,1 22,7V17A2,2 0 0,1 20,19H4C2.89,19 2,18.1 2,17V7C2,5.89 2.89,5 4,5H20M5,16H19L14.5,10L11,14.5L8.5,11.5L5,16Z" />
            </svg>
        </div>
        <div class="d-flex gap-2 mt-2 flex-wrap">
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
</div>

<!-- Current / Available Network -->
<div class="row mb-3">
    <div class="col-md-6">
        <div class="form-floating form-floating-outline">
            <select name="current_network" class="form-select">
                <option value="">Select Network</option>
                @foreach($networks as $id => $name)
                    <option value="{{ $id }}" {{ (string)old('current_network', $store->current_network ?? '') === (string)$id ? 'selected' : '' }}>
                        {{ $name }}
                    </option>
                @endforeach
            </select>
            <label>Current Network</label>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-floating form-floating-outline">
            <select name="available_network" class="form-select">
                <option value="">Select Network</option>
                @foreach($networks as $id => $name)
                    <option value="{{ $id }}" {{ (string)old('available_network', $store->available_network ?? '') === (string)$id ? 'selected' : '' }}>
                        {{ $name }}
                    </option>
                @endforeach
            </select>
            <label>Available Network</label>
        </div>
    </div>
</div>

<!-- Social Links -->
<div class="row mb-3">
    <div class="col-md-6">
        <div class="form-floating form-floating-outline">
            <input type="url" class="form-control" name="facebook_url" placeholder="Facebook URL"
                value="{{ old('facebook_url', $store->facebook_url ?? '') }}">
            <label>Facebook URL</label>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-floating form-floating-outline">
            <input type="url" class="form-control" name="twitter_url" placeholder="Twitter URL"
                value="{{ old('twitter_url', $store->twitter_url ?? '') }}">
            <label>Twitter URL</label>
        </div>
    </div>
</div>
<div class="row mb-3">
    <div class="col-md-6">
        <div class="form-floating form-floating-outline">
            <input type="url" class="form-control" name="instagram_url" placeholder="Instagram URL"
                value="{{ old('instagram_url', $store->instagram_url ?? '') }}">
            <label>Instagram URL</label>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-floating form-floating-outline">
            <input type="url" class="form-control" name="youtube_url" placeholder="YouTube URL"
                value="{{ old('youtube_url', $store->youtube_url ?? '') }}">
            <label>YouTube URL</label>
        </div>
    </div>
</div>

<!-- Content -->
<div class="form-floating form-floating-outline mb-3">
    <textarea class="form-control h-px-100" name="content" placeholder="Store Content">{{ old('content', $store->content ?? '') }}</textarea>
    <label>Content (Store Content)</label>
</div>

<!-- Description -->
<div class="form-floating form-floating-outline mb-3">
    <textarea class="form-control h-px-100" name="detail_description" placeholder="Detail Description">{{ old('detail_description', $store->detail_description ?? '') }}</textarea>
    <label>Description (Detail Description)</label>
</div>

<!-- FAQs (single HTML field: heading + answer together) -->
<div class="mb-3">
    <div class="card">
      <h5 class="card-header">FAQ (Heading + Answer - single field)</h5>
      <div class="card-body">
    <!-- Quill editor: visual editing area (use theme's editor id and allow theme CSS) -->
    <div id="full-editor" class="form-control">{!! old('faqs', $store->faqs ?? '') !!}</div>
    <!-- Hidden textarea that will be posted -->
    <textarea id="faqs" name="faqs" class="d-none">{{ old('faqs', $store->faqs ?? '') }}</textarea>
    <small class="form-text text-muted">Enter FAQ content as HTML (use &lt;h3&gt; for questions and &lt;p&gt; for answers). A rich editor is enabled below.</small>
</div>
</div>
</div>

<!-- SEO Section -->
<div class="card p-3 mb-3">
    <h5>SEO Store SEO Section</h5>
    <div class="row mb-3">
        <div class="col-md-4">
                <div class="form-floating form-floating-outline">
                <input type="text" id="seo_url" class="form-control" name="seo_url" placeholder="SEO URL" required
                    value="{{ old('seo_url', $store->seo_url ?? '') }}">
                <label>SEO URL *</label>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-floating form-floating-outline">
                <input type="text" class="form-control" name="meta_title" placeholder="Meta Title"
                    value="{{ old('meta_title', $store->meta_title ?? '') }}">
                <label>Meta Title</label>
            </div>
        </div>
    </div>
    <div class="form-floating form-floating-outline mb-3">
        <input type="text" name="meta_keywords" id="meta_keywords" class="form-control" placeholder="Meta Keywords (comma separated)" value="{{ old('meta_keywords', $store->meta_keywords ?? '') }}">
        <label>Meta Keywords</label>
        <small class="form-text text-muted">Enter keywords separated by commas. Example: sale, free delivery, student discount</small>
    </div>
    <div class="form-floating form-floating-outline mb-3">
        <textarea class="form-control h-px-100" name="meta_description" placeholder="Meta Description">{{ old('meta_description', $store->meta_description ?? '') }}</textarea>
        <label>Meta Description</label>
    </div>

    <!-- Canonical URL -->
    <div class="row mb-3">
        <div class="col-md-12">
            <div class="form-floating form-floating-outline">
                <input type="url" name="canonical_url" class="form-control" placeholder="Canonical URL"
                    value="{{ old('canonical_url', $store->canonical_url ?? '') }}">
                <label>Canonical URL</label>
            </div>
        </div>
    </div>

    <!-- Schema -->
    <div class="row mb-3">
        <div class="col-md-12">
            <div class="form-floating form-floating-outline mb-6">
                <textarea class="form-control h-px-150" placeholder="Schema Markup (JSON-LD)" name="schema">{{ old('schema', $store->schema ?? '') }}</textarea>
                <label>Schema Markup (JSON-LD)</label>
            </div>
        </div>
    </div>

</div>

<!-- Save Button -->
<div class="text-end">
    <button type="submit" class="btn btn-primary">
        {{ isset($store) && $store->exists ? '💾 Update' : '💾 Save' }}
    </button>
</div>

<script>
function previewImage(event, previewId, placeholderId) {
    const file = event.target.files && event.target.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = function(){
        const img = document.getElementById(previewId);
        const ph  = document.getElementById(placeholderId);
        if (img) { img.src = reader.result; img.style.display = 'block'; }
        if (ph)  { ph.style.display = 'none'; }
    };
    reader.readAsDataURL(file);
}
  $(document).ready(function() {
        $('.select2').select2({
            placeholder: "Click to select",
            allowClear: true,
            width: '100%'
        });
    });
</script>

<!-- TinyMCE for FAQs field -->
<!-- Sync existing Quill instance (initialized by theme's forms-editors.js) to hidden textarea on submit -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    const editorNode = document.getElementById('full-editor');
    const hidden = document.getElementById('faqs');
    // Bind to the form that contains the editor to avoid selecting an unrelated form
    const form = editorNode.closest ? editorNode.closest('form') : document.querySelector('form');
    if (!editorNode || !hidden || !form) return;

    // Always attach a submit handler. When Quill is available we use its API,
    // otherwise we fall back to reading the editor DOM (.ql-editor) content.
    form.addEventListener('submit', function (ev) {
        try {
            let html = '';
            // Prefer the Quill instance if we can find it
            const quill = (typeof Quill !== 'undefined' && Quill.find) ? Quill.find(editorNode) : null;
            if (quill && quill.root) {
                html = quill.root.innerHTML;
            } else {
                // Fall back to the editable container created by Quill
                const editorContent = editorNode.querySelector('.ql-editor');
                html = editorContent ? editorContent.innerHTML : editorNode.innerHTML;
            }

            // Debugging: log before and after setting hidden value so developer can inspect in DevTools Network
            console.debug('FAQ editor html (preview):', html);
            hidden.value = html;
            console.debug('Hidden textarea #faqs value set to:', hidden.value);
        } catch (err) {
            // Best-effort: ensure we don't block submission
            console.warn('FAQ editor sync failed:', err);
        }
        // allow form to continue submitting
    });
});
</script>

<script>
// Auto-generate slug for seo_url from store_name input
(function(){
    function slugify(text) {
        return text.toString().toLowerCase()
            .normalize('NFKD') // split accented characters
            .replace(/\p{Diacritic}/gu, '')
            .replace(/[^a-z0-9\s-]/g, '')
            .trim()
            .replace(/\s+/g, '-')
            .replace(/-+/g, '-');
    }

    const nameInput = document.querySelector('input[name="store_name"]');
    const seoInput = document.getElementById('seo_url');
    if (!nameInput || !seoInput) return;

    // Track if user manually edited SEO field
    let seoManuallyEdited = false;
    seoInput.addEventListener('input', function () { seoManuallyEdited = true; });

    // On blur or input of store name, populate seo if not manually edited
    function tryFill() {
        if (seoManuallyEdited) return;
        const val = nameInput.value || '';
        const slug = slugify(val);
        if (slug && !seoInput.value) {
            seoInput.value = slug;
        } else if (slug && seoInput.value && !seoManuallyEdited) {
            // If seo present but equals previous slug candidate, update it
            // (helps when editing existing store_name)
            const current = seoInput.value;
            const currentSlug = slugify(current);
            if (!current || currentSlug === currentSlug) {
                seoInput.value = slug;
            }
        }
    }

    nameInput.addEventListener('blur', tryFill);
    nameInput.addEventListener('input', function () {
        // live update only when seo is empty and not manually edited
        if (!seoManuallyEdited && !seoInput.value) {
            seoInput.value = slugify(nameInput.value || '');
        }
    });
})();
</script>

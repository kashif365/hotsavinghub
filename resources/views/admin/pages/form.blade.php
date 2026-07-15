@csrf

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
<!-- Status Toggle -->
<div class="form-section">
  <div class="row mb-3 ms-2">
    <div class="col-md-4 toggle-switch">
      <div class="d-flex">
        <label>Disable</label>
        <div class="form-check form-switch mx-2">
          <input class="form-check-input" type="checkbox" role="switch" id="statusSwitch" name="status" value="1"
            {{ old('status', $page->status ?? 1) ? 'checked' : '' }}>
        </div>
        <label>Enable</label>
      </div>
    </div>
  </div>
</div>

<!-- Page Title + SEO URL -->
<div class="form-section">
  <div class="row mb-3">
    <div class="col-md-6">
      <div class="form-floating form-floating-outline">
        <input type="text" name="page_title" class="form-control" placeholder="Page Title"
          value="{{ old('page_title', $page->page_title ?? '') }}" required>
        <label>Page Title *</label>
      </div>
    </div>
    <div class="col-md-6">
      <div class="form-floating form-floating-outline">
        <input type="text" name="seo_url" class="form-control" placeholder="SEO URL"
          value="{{ old('seo_url', $page->seo_url ?? '') }}" required>
        <label>SEO URL *</label>
      </div>
    </div>
  </div>
</div>

<!-- Meta Title + Meta Keywords -->
<div class="form-section">
  <div class="row mb-3">
    <div class="col-md-6">
      <div class="form-floating form-floating-outline">
        <input type="text" name="meta_title" class="form-control" placeholder="Meta Title"
          value="{{ old('meta_title', $page->meta_title ?? '') }}">
        <label>Meta Title</label>
      </div>
    </div>
    <div class="col-md-6">
      <div class="form-floating form-floating-outline">
        <input type="text" name="meta_keywords" class="form-control" placeholder="Meta Keywords"
          value="{{ old('meta_keywords', $page->meta_keywords ?? '') }}">
        <label>Meta Keywords</label>
      </div>
    </div>
  </div>
</div>

<!-- Meta Description -->
<div class="form-section">
  <div class="row mb-3">
    <div class="col-md-12">
      <div class="form-floating form-floating-outline mb-6">
        <textarea class="form-control h-px-100" placeholder="Meta Description" name="meta_description">{{ old('meta_description', $page->meta_description ?? '') }}</textarea>
        <label>Meta Description</label>
      </div>
    </div>
  </div>
</div>

<!-- Canonical URL -->
<div class="form-section">
  <div class="row mb-3">
    <div class="col-md-12">
      <div class="form-floating form-floating-outline">
        <input type="url" name="canonical_url" class="form-control" placeholder="Canonical URL"
          value="{{ old('canonical_url', $page->canonical_url ?? '') }}">
        <label>Canonical URL</label>
      </div>
    </div>
  </div>
</div>

<!-- Schema -->
<div class="form-section">
  <div class="row mb-3">
    <div class="col-md-12">
      <div class="form-floating form-floating-outline mb-6">
        <textarea class="form-control h-px-150" placeholder="Schema Markup (JSON-LD)" name="schema">{{ old('schema', $page->schema ?? '') }}</textarea>
        <label>Schema Markup (JSON-LD)</label>
      </div>
    </div>
  </div>
</div>

<!-- Page Content -->
<div class="mb-3">
  <div class="card">
    <h5 class="card-header">Page Content</h5>
    <div class="card-body">
      <!-- Quill editor: visual editing area (use theme's editor id and allow theme CSS) -->
      <div id="full-editor" class="form-control">{!! old('page_content', $page->page_content ?? '') !!}</div>
      <!-- Hidden textarea that will be posted -->
      <textarea id="page_content" name="page_content" class="d-none">{{ old('page_content', $page->page_content ?? '') }}</textarea>
      <small class="form-text text-muted">Enter page content as HTML. A rich editor is enabled below.</small>
    </div>
  </div>
</div>

<!-- Media Upload -->
<div class="form-section">
  <div class="row">
    <div class="col-md-3">
      <label>Page Image</label>
      <div class="image-upload-box" onclick="document.getElementById('media').click()">
        <img id="media_preview"
          src="{{ isset($page) && $page->media ? asset($page->media) : '' }}"
          style="{{ isset($page) && $page->media ? '' : 'display:none;' }}">
        <svg id="media_placeholder" style="{{ isset($page) && $page->media ? 'display:none;' : '' }}"
          xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
          <path d="M20,5A2,2 0 0,1 22,7V17A2,2 0 0,1 20,19H4C2.89,19 2,18.1 2,17V7C2,5.89 2.89,5 4,5H20M5,16H19L14.5,10 L11,14.5L8.5,11.5L5,16Z" />
        </svg>
      </div>
      <div class="d-flex gap-2 mt-2 flex-wrap">
        <button type="button" class="btn btn-sm btn-outline-primary flex-fill" onclick="document.getElementById('media').click()">
          <i class="ri-upload-line"></i> Upload
        </button>
        <button type="button" class="btn btn-sm btn-outline-info flex-fill" onclick="openMediaLibrary('media', 'media_preview', 'media_placeholder')">
          <i class="ri-image-line"></i> Media
        </button>
      </div>
      <input type="file" id="media" name="media" style="display:none;" accept="image/*"
        onchange="previewImage(event, 'media_preview', 'media_placeholder')">
      <input type="hidden" id="media_path" name="media_path">
    </div>
    <div class="col-md-3">
      <label>Banner Image</label>
      <div class="image-upload-box" onclick="document.getElementById('banner_image').click()">
        <img id="banner_preview"
          src="{{ isset($page) && $page->banner_image ? asset($page->banner_image) : '' }}"
          style="{{ isset($page) && $page->banner_image ? '' : 'display:none;' }}">
        <svg id="banner_placeholder" style="{{ isset($page) && $page->banner_image ? 'display:none;' : '' }}"
          xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
          <path d="M20,5A2,2 0 0,1 22,7V17A2,2 0 0,1 20,19H4C2.89,19 2,18.1 2,17V7C2,5.89 2.89,5 4,5H20M5,16H19L14.5,10 L11,14.5L8.5,11.5L5,16Z" />
        </svg>
      </div>
      <div class="d-flex gap-2 mt-2 flex-wrap">
        <button type="button" class="btn btn-sm btn-outline-primary flex-fill" onclick="document.getElementById('banner_image').click()">
          <i class="ri-upload-line"></i> Upload
        </button>
        <button type="button" class="btn btn-sm btn-outline-info flex-fill" onclick="openMediaLibrary('banner_image', 'banner_preview', 'banner_placeholder')">
          <i class="ri-image-line"></i> Media
        </button>
      </div>
      <input type="file" id="banner_image" name="banner_image" style="display:none;" accept="image/*"
        onchange="previewImage(event, 'banner_preview', 'banner_placeholder')">
      <input type="hidden" id="banner_image_path" name="banner_image_path">
    </div>
  </div>
</div>

<!-- Hidden Sort Order -->
<input type="hidden" name="sort_order" value="{{ old('sort_order', $page->sort_order ?? 0) }}">

<!-- Save Button -->
<div class="text-end mb-4">
  <button class="btn btn-primary">💾 Save</button>
</div>

<script>
function previewImage(event, previewId, placeholderId) {
    const reader = new FileReader();
    reader.onload = function() {
        document.getElementById(previewId).src = reader.result;
        document.getElementById(previewId).style.display = 'block';
        document.getElementById(placeholderId).style.display = 'none';
    };
    reader.readAsDataURL(event.target.files[0]);
}
</script>

<!-- Quill Editor Sync for Page Content -->
<!-- Sync existing Quill instance (initialized by theme's forms-editors.js) to hidden textarea on submit -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    const editorNode = document.getElementById('full-editor');
    const hidden = document.getElementById('page_content');
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
            console.debug('Page editor html (preview):', html);
            hidden.value = html;
            console.debug('Hidden textarea #page_content value set to:', hidden.value);
        } catch (err) {
            // Best-effort: ensure we don't block submission
            console.warn('Page editor sync failed:', err);
        }
        // allow form to continue submitting
    });
});
</script>

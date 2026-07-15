@csrf


<!-- Status + Enable/Disable -->
<div class="form-section">
    <div class="row mb-3 ms-2">
                <div class="col-md-2 toggle-switch">
                    <div class="d-flex">
                        <label>Disable</label>
                        <div class="form-check form-switch mx-2">
                            <input class="form-check-input" type="checkbox" role="switch" id="statusSwitch" name="status" value="1"
                                {{ old('status', $category->status ?? 1) ? 'checked' : '' }}>
                        </div>
                        <label>Enable</label>
                    </div>
                </div>
                <div class="col-md-2 toggle-switch">
                    <div class="d-flex">
                        <label>Feature Category</label>
                        <div class="form-check form-switch mx-2">
                            <input class="form-check-input" type="checkbox" role="switch" id="featuredSwitch" name="featured" value="1"
                                {{ old('featured', $category->featured ?? 0) ? 'checked' : '' }}>
                        </div>
                    </div>
                </div>
                <div class="col-md-2 toggle-switch">
                    <div class="d-flex">
                        <label>Show Home</label>
                        <div class="form-check form-switch mx-2">
                            <input class="form-check-input" type="checkbox" role="switch" id="showHomeSwitch" name="show_home" value="1"
                                {{ old('show_home', $category->show_home ?? 0) ? 'checked' : '' }}>
                        </div>
                    </div>
                </div>
                <div class="col-md-2 toggle-switch">
                    <div class="d-flex">
                        <label>Recommended</label>
                        <div class="form-check form-switch mx-2">
                            <input class="form-check-input" type="checkbox" role="switch" id="recommendedSwitch" name="recommended" value="1"
                                {{ old('recommended', $category->recommended ?? 0) ? 'checked' : '' }}>
                        </div>
                    </div>
                </div>
                <div class="col-md-2 toggle-switch">
                    <div class="d-flex">
                        <label>Student Discount</label>
                        <div class="form-check form-switch mx-2">
                            <input class="form-check-input" type="checkbox" role="switch" id="studentDiscountSwitch" name="student_discount" value="1"
                                {{ old('student_discount', $category->student_discount ?? 0) ? 'checked' : '' }}>
                        </div>
                    </div>
                </div>
                <div class="col-md-2 toggle-switch">
                    <div class="d-flex">
                        <label>Show Top</label>
                        <div class="form-check form-switch mx-2">
                            <input class="form-check-input" type="checkbox" role="switch" id="showTopSwitch" name="show_top" value="1"
                                {{ old('show_top', $category->show_top ?? 0) ? 'checked' : '' }}>
                        </div>
                    </div>
                </div>
    </div>
</div>

        <!-- Feature Category + Show Home + Recommended -->
        <!-- <div class="form-section">
            <div class="row mb-3 ms-2">
                
            </div>
        </div> -->

        <!-- Show Top Category -->
        <div class="form-section">
            <div class="row mb-3 ms-2">
                
            </div>
        </div>

<!-- Category Name + SEO URL -->
<div class="form-section">
    <div class="row mb-3">
        <div class="col-md-6">
            <div class="form-floating form-floating-outline">
                <input type="text" name="category_name" class="form-control" placeholder="Category Name"
                    value="{{ old('category_name', $category->category_name ?? '') }}" required>
                <label>Category Name *</label>
            </div>
        </div>
         <div class="col-md-6">
            <div class="form-floating form-floating-outline">
    <select name="parent_id" class="form-control select2">
        <option value="">-- None --</option>
        @foreach($categories as $cat)
            <option value="{{ $cat->id }}"
                {{ old('parent_id', $category->parent_id ?? '') == $cat->id ? 'selected' : '' }}>
                {{ $cat->category_name }}
            </option>
        @endforeach
    </select>
    <label>Parent Category</label>
</div>


        </div>
    </div>
</div>

<!-- Parent Category -->
<div class="form-section">
    <div class="row mb-3">
        <div class="col-md-6">
            <div class="form-floating form-floating-outline">
                <input type="text" name="seo_url" class="form-control" placeholder="SEO URL"
                    value="{{ old('seo_url', $category->seo_url ?? '') }}" required>
                <label>SEO URL *</label>
            </div>
        </div>
        <div class="col-md-6">
                <div class="form-floating form-floating-outline">
                    <input type="text" name="meta_title" class="form-control" placeholder="Meta Title"
                        value="{{ old('meta_title', $category->meta_title ?? '') }}">
                    <label>Meta Title</label>
                </div>
        </div>
    </div>
</div>

<!-- Meta Title & Meta Description -->
<div class="form-section">
    <div class="row mb-3">
        <div class="col-md-12">
            <div class="form-floating form-floating-outline">
                <input type="text" name="meta_description" class="form-control" placeholder="Meta Description"
                    value="{{ old('meta_description', $category->meta_description ?? '') }}">
                <label>Meta Description</label>
            </div>
        </div>
    </div>
</div>

<!-- Short Content -->
<div class="form-section">
    <div class="row mb-3">
        <div class="col-md-12">
            <div class="form-floating form-floating-outline mb-6">
                <textarea class="form-control h-px-100" placeholder="Short Content"
                    name="short_content">{{ old('short_content', $category->short_content ?? '') }}</textarea>
                <label>Short Content</label>
            </div>
        </div>
    </div>
</div>

<!-- Full Description -->
<div class="form-section">
    <div class="row mb-3">
        <div class="col-md-12">
            <div class="form-floating form-floating-outline mb-6">
                <textarea class="form-control h-px-100" placeholder="Description"
                    name="description">{{ old('description', $category->description ?? '') }}</textarea>
                <label>Description</label>
            </div>
        </div>
    </div>
</div>

<!-- Media Upload -->
<div class="form-section">
    <div class="row">
        <div class="col-md-3">
            <label>Category Image</label>
            <div class="image-upload-box" onclick="document.getElementById('media').click()">
                <img id="media_preview"
                    src="{{ isset($category) && $category->media ? asset($category->media) : '' }}"
                    style="{{ isset($category) && $category->media ? '' : 'display:none;' }}">
                <svg id="media_placeholder" style="{{ isset($category) && $category->media ? 'display:none;' : '' }}"
                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                    <path
                        d="M20,5A2,2 0 0,1 22,7V17A2,2 0 0,1 20,19H4C2.89,19 2,18.1 2,17V7C2,5.89 2.89,5 4,5H20M5,16H19L14.5,10 L11,14.5L8.5,11.5L5,16Z" />
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
    </div>
</div>

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





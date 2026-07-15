@php
    // create/edit dono me safe:
    $blogCategory = $blogCategory ?? new \App\Models\BlogCategory;
@endphp

@csrf

<!-- Top Toggles -->
<div class="d-flex flex-wrap mb-3 gap-3">
    <div class="d-flex align-items-center ms-auto">
        <label>Inactive</label>
        <div class="form-check form-switch mx-2">
            <input class="form-check-input" type="checkbox" name="status" value="1"
                {{ old('status', $blogCategory->status ?? true) ? 'checked' : '' }}>
        </div>
        <label>Active</label>
    </div>
</div>

<!-- Category Name + Color -->
<div class="row mb-3">
    <div class="col-md-8">
        <div class="form-floating form-floating-outline">
            <input type="text" class="form-control" name="name" placeholder="Category Name"
                value="{{ old('name', $blogCategory->name ?? '') }}" required>
            <label>Category Name *</label>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-floating form-floating-outline">
            <input type="color" class="form-control" name="color" placeholder="Category Color"
                value="{{ old('color', $blogCategory->color ?? '#007bff') }}">
            <label>Category Color</label>
        </div>
    </div>
</div>

<!-- Description -->
<div class="form-floating form-floating-outline mb-3">
    <textarea class="form-control h-px-100" name="description" placeholder="Category Description">{{ old('description', $blogCategory->description ?? '') }}</textarea>
    <label>Description (Optional)</label>
</div>

<!-- Sort Order -->
<div class="row mb-3">
    <div class="col-md-6">
        <div class="form-floating form-floating-outline">
            <input type="number" class="form-control" name="sort_order" placeholder="Sort Order"
                value="{{ old('sort_order', $blogCategory->sort_order ?? 0) }}">
            <label>Sort Order</label>
        </div>
    </div>
</div>

<!-- Save Button -->
<div class="text-end">
    <button type="submit" class="btn btn-primary">
        {{ isset($blogCategory) && $blogCategory->exists ? '💾 Update' : '💾 Save' }}
    </button>
</div>

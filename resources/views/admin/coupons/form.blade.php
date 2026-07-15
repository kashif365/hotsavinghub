@csrf

<!-- Status Checkboxes -->
<div class="form-section">
  <div class="row mb-3 ms-2">
    @php
      $checkboxes = [
        'exclusive' => 'Exclusive',
        'featured' => 'Featured',
        'recommended' => 'Recommended',
        'verified' => 'Verified',
        'hot_deals' => 'Hot Deals',
        'student_offer' => 'Student Discount',
      ];
    @endphp
    @foreach($checkboxes as $name => $label)
      <div class="col-md-2 form-check">
        <input class="form-check-input" type="checkbox" id="{{ $name }}" name="{{ $name }}" value="1"
          {{ old($name, $coupon->$name ?? 0) ? 'checked' : '' }}>
        <label class="form-check-label" for="{{ $name }}">{{ $label }}</label>
      </div>
    @endforeach
    <div class="col-md-4 toggle-switch">
      <div class="d-flex">
        <label>Disable</label>
        <div class="form-check form-switch mx-2">
          <input class="form-check-input" type="checkbox" role="switch" id="statusSwitch" name="status" value="1"
            {{ old('status', $coupon->status ?? 1) ? 'checked' : '' }}>
        </div>
        <label>Enable</label>
      </div>
    </div>
  </div>
</div>

<!-- Coupon Form -->
<div class="form-section">
  <div class="row mb-3">
    <div class="col-md-6">
      <div class="form-floating form-floating-outline">
        <input type="text" name="coupon_title" class="form-control" placeholder="Coupon Title"
               value="{{ old('coupon_title', $coupon->coupon_title ?? '') }}" required>
        <label>Coupon Title *</label>
      </div>
    </div>
    <div class="col-md-6">
      <div class="form-floating form-floating-outline">
        <select name="brand_store" class="form-control select2" required>
          <option value="">-- Select Store --</option>
          @foreach($stores as $store)
            <option value="{{ $store->store_name }}"
              {{ old('brand_store', $coupon->brand_store ?? '') == $store->store_name ? 'selected' : '' }}>
              {{ $store->store_name }}
            </option>
          @endforeach
        </select>
        <label>Brand/Store *</label>
      </div>
    </div>
  </div>

  <div class="row mb-3">
    <div class="col-md-6">
      <div class="form-floating form-floating-outline">
        <select name="category_id" class="form-control select2">
          <option value="">-- Select Category --</option>
          @foreach($categories as $category)
            <option value="{{ $category->id }}"
              {{ old('category_id', $coupon->category_id ?? '') == $category->id ? 'selected' : '' }}>
              {{ $category->category_name }}
            </option>
          @endforeach
        </select>
        <label>Category</label>
      </div>
    </div>
  </div>

  <div class="row mb-3">
    <div class="col-md-6">
      <div class="form-floating form-floating-outline">
        <input type="text" name="coupon_code" class="form-control" placeholder="Coupon Code"
               value="{{ old('coupon_code', $coupon->coupon_code ?? '') }}">
        <label>Coupon Code</label>
      </div>
    </div>
    <div class="col-md-6">
      <div class="form-floating form-floating-outline">
<select name="event_id" class="form-control select2">
    <option value="">-- Select Event --</option>
    @foreach($events as $event)
        <option value="{{ $event->id }}"
            {{ old('event_id', $coupon->event_id ?? '') == $event->id ? 'selected' : '' }}>
            {{ $event->event_name }}
        </option>
    @endforeach
</select>

        <label>Events</label>
      </div>
    </div>
  </div>

  <div class="row mb-3">
    <div class="col-md-6">
      <div class="form-floating form-floating-outline">
        <input type="text" name="submitted_by" class="form-control" placeholder="Submitted By"
               value="{{ old('submitted_by', $coupon->submitted_by ?? '') }}">
        <label>Submitted By</label>
      </div>
    </div>
    <div class="col-md-6">
      <div class="form-floating form-floating-outline">
        <input type="text" name="affiliate_url" class="form-control" placeholder="Affiliate URL"
               value="{{ old('affiliate_url', $coupon->affiliate_url ?? '') }}">
        <label>Affiliate URL</label>
      </div>
    </div>
  </div>

  <div class="row mb-3">
    <div class="col-md-5">
      <label for="dateAvailable" class="form-label">Date Available</label>
      <input type="date" class="form-control" id="dateAvailable" name="date_available"
             value="{{ old('date_available', $coupon->date_available ?? '') }}">
    </div>
    <div class="col-md-5">
      <label for="dateExpiry" class="form-label">Date Expiry</label>
      <input type="date" class="form-control me-2" id="dateExpiry" name="date_expiry"
             value="{{ old('date_expiry', $coupon->date_expiry ?? '') }}">
    </div>
    <div class="col-md-2">
      <div class="form-check mt-12">
        <input class="form-check-input" type="checkbox" id="expirySoon" name="expiry_soon" value="1"
          {{ old('expiry_soon', $coupon->expiry_soon ?? 0) ? 'checked' : '' }}>
        <label class="form-check-label" for="expirySoon">Expiry Soon</label>
      </div>
    </div>
  </div>

  <div class="row mb-3">
    <div class="col-md-12">
      <div class="form-floating form-floating-outline mb-6">
        <textarea class="form-control h-px-100" placeholder="Description" name="description">{{ old('description', $coupon->description ?? '') }}</textarea>
        <label>Description</label>
      </div>
    </div>
  </div>

  <div class="row mb-3">
    <div class="col-md-12">
      <div class="form-floating form-floating-outline mb-6">
        <textarea class="form-control h-px-100" placeholder="Terms" name="terms">{{ old('terms', $coupon->terms ?? '1. Some exclusions apply.
2. Cannot be used in conjunction with any other offer.
3. For full Terms & Conditions please see website.') }}</textarea>
        <label>Terms & Conditions</label>
      </div>
    </div>
  </div>

  <!-- File Upload -->
  <div class="row">
    <div class="col-md-3">
        <label>Media Section (Cover Logo)</label>
        <div class="image-upload-box" onclick="document.getElementById('cover_logo').click()">
            <img id="cover_logo_preview" 
                src="{{ isset($coupon) && $coupon->cover_logo ? asset($coupon->cover_logo) : '' }}" 
                style="{{ isset($coupon) && $coupon->cover_logo ? '' : 'display:none;' }}">
            <svg id="cover_logo_placeholder" style="{{ isset($coupon) && $coupon->cover_logo ? 'display:none;' : '' }}" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                <path d="M20,5A2,2 0 0,1 22,7V17A2,2 0 0,1 20,19H4C2.89,19 
                2,18.1 2,17V7C2,5.89 2.89,5 4,5H20M5,16H19L14.5,10
                L11,14.5L8.5,11.5L5,16Z" />
            </svg>
        </div>
        <div class="d-flex gap-2 mt-2 flex-wrap">
            <button type="button" class="btn btn-sm btn-outline-primary flex-fill" onclick="document.getElementById('cover_logo').click()">
                <i class="ri-upload-line"></i> Upload
            </button>
            <button type="button" class="btn btn-sm btn-outline-info flex-fill" onclick="openMediaLibrary('cover_logo', 'cover_logo_preview', 'cover_logo_placeholder')">
                <i class="ri-image-line"></i> Media
            </button>
        </div>
        <input type="file" id="cover_logo" name="cover_logo" style="display:none;" accept="image/*" 
            onchange="previewImage(event, 'cover_logo_preview', 'cover_logo_placeholder')">
        <input type="hidden" id="cover_logo_path" name="cover_logo_path">
    </div>

</div>

<!-- Save Button -->
<div class="text-end mb-4">
  <button class="btn btn-primary">💾 Save</button>
</div>

<!-- JS -->
<script>


document.getElementById('expirySoon').addEventListener('change', function() {
  const expiryInput = document.getElementById('dateExpiry');
  if (this.checked) {
    expiryInput.value = '';
    expiryInput.disabled = true;
    expiryInput.removeAttribute('required');
  } else {
    expiryInput.disabled = false;
    expiryInput.setAttribute('required', 'required');
  }
});
</script>

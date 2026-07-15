<form action="{{ $action }}" method="POST" id="verificationTagForm" enctype="multipart/form-data">
    @csrf
    @if(isset($method) && in_array(strtoupper($method), ['PUT', 'PATCH']))
        @method($method)
    @endif

    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">{{ $heading }}</h5>
            <a href="{{ route('admin.verification-tags.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="ri-arrow-go-back-line me-1"></i> Back
            </a>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Label / Notes</label>
                    <input type="text" class="form-control @error('label') is-invalid @enderror" name="label" value="{{ old('label', $tag->label) }}" placeholder="e.g. Google Search Console">
                    @error('label')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-3">
                <label class="form-label">Placement <span class="text-danger">*</span></label>
                <select name="placement" class="form-select @error('placement') is-invalid @enderror" required>
                    @foreach(\App\Models\VerificationTag::PLACEMENTS as $placement)
                        <option value="{{ $placement }}" {{ old('placement', $tag->placement ?? 'head_end') === $placement ? 'selected' : '' }}>
                            {{ ucwords(str_replace('_', ' ', $placement)) }}
                        </option>
                    @endforeach
                </select>
                @error('placement')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="row g-3 mt-1">
                <div class="col-md-3">
                    <label class="form-label">Sort Order</label>
                    <input type="number" min="0" class="form-control @error('sort_order') is-invalid @enderror" name="sort_order" value="{{ old('sort_order', $tag->sort_order) }}">
                    @error('sort_order')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-3 d-flex align-items-center">
                    <div class="form-check form-switch mt-4">
                        <input class="form-check-input" type="checkbox" name="is_active" value="1" id="is_active_switch" {{ old('is_active', $tag->is_active ?? true) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_active_switch">Active</label>
                    </div>
                </div>
            </div>

            <div class="mt-4">
                <div class="alert alert-info">
                    <strong>Paste Entire Tag:</strong> Copy the exact meta/script tag (or any HTML snippet) provided by networks and paste it below.
                    We will inject it on every page exactly where you choose.
                </div>
                <label class="form-label">Verification / Script Snippet <span class="text-danger">*</span></label>
                <textarea name="code" class="form-control @error('code') is-invalid @enderror" rows="10" required placeholder="Example: &lt;meta name=&quot;google-site-verification&quot; content=&quot;....&quot;&gt;
&lt;script async src=&quot;https://example.com/script.js&quot;&gt;&lt;/script&gt;
&lt;noscript&gt;Fallback content&lt;/noscript&gt;">{{ old('code', $tag->code ?? '') }}</textarea>
                @error('code')<div class="invalid-feedback">{{ $message }}</div>@enderror
                <small class="form-text text-muted">This field is required. Paste your meta tag, script tag, or any HTML snippet here.</small>
            </div>

            <div class="text-end mt-4">
                <button type="submit" class="btn btn-primary" id="saveTagBtn">
                    <i class="ri-save-line me-1"></i> Save Tag
                </button>
            </div>
        </div>
    </div>
</form>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var form = document.getElementById('verificationTagForm');
    var submitBtn = document.getElementById('saveTagBtn');
    
    if (form && submitBtn) {
        // Ensure form submits properly
        form.addEventListener('submit', function(e) {
            // Check if required fields are filled
            var codeField = form.querySelector('textarea[name="code"]');
            var placementField = form.querySelector('select[name="placement"]');
            
            if (codeField && !codeField.value.trim()) {
                e.preventDefault();
                alert('Please enter the verification/script snippet.');
                codeField.focus();
                return false;
            }
            
            if (placementField && !placementField.value) {
                e.preventDefault();
                alert('Please select a placement.');
                placementField.focus();
                return false;
            }
            
            // Disable submit button to prevent double submission
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="ri-loader-4-line me-1"></i> Saving...';
            
            // Allow form to submit normally
            return true;
        });
        
        // Also handle button click directly
        submitBtn.addEventListener('click', function(e) {
            // Let form validation handle it
            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }
        });
    }
});
</script>

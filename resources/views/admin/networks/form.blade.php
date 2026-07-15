@csrf
<div class="modal-header">
    <h5 class="modal-title">{{ $network ? 'Edit Network' : 'Add Network' }}</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
</div>

<div class="modal-body">
    <div class="mb-3">
        <label class="form-label">Network Name</label>
        <input type="text" name="name" class="form-control"
               value="{{ old('name', $network->name ?? '') }}" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Affiliate ID</label>
        <input type="text" name="affiliate_id" class="form-control"
               value="{{ old('affiliate_id', $network->affiliate_id ?? '') }}">
    </div>

    <div class="d-flex align-items-center mb-3">
        <label class="mb-0">Disable</label>
        <div class="form-check form-switch mx-2">
            <!-- Hidden input ensures unchecked = 0 -->
            <input type="hidden" name="status" value="0">
            <input class="form-check-input" type="checkbox" role="switch"
                   id="statusSwitch{{ $network->id ?? 'new' }}"
                   name="status" value="1"
                   {{ old('status', $network->status ?? false) ? 'checked' : '' }}>
        </div>
        <label class="mb-0">Enable</label>
    </div>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
    <button type="submit" class="btn btn-primary">Save</button>
</div>

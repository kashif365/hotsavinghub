@extends('admin.layouts.app')

@section('content')
<div class="card">
  <div class="card-header">
    <h5 class="mb-0">{{ $redirect->exists ? 'Edit Redirect' : 'Add Redirect' }}</h5>
  </div>
  <div class="card-body">
    <form method="post" action="{{ $redirect->exists ? route('admin.redirects.update', $redirect) : route('admin.redirects.store') }}">
      @csrf
      @if($redirect->exists)
        @method('put')
      @endif

      <div class="mb-3">
        <label class="form-label">Old URL</label>
        <input type="text" class="form-control" name="old_url" value="{{ old('old_url', $redirect->old_url) }}" placeholder="/old-page or full URL">
        @error('old_url')<div class="text-danger small">{{ $message }}</div>@enderror
      </div>

      <div class="mb-3">
        <label class="form-label">New URL</label>
        <input type="text" class="form-control" name="new_url" value="{{ old('new_url', $redirect->new_url) }}" placeholder="/new-page or full URL">
        @error('new_url')<div class="text-danger small">{{ $message }}</div>@enderror
      </div>

      <div class="row g-3 mb-3">
        <div class="col-md-4">
          <label class="form-label">Type</label>
          <select name="type" class="form-select">
            <option value="301" {{ old('type', $redirect->type ?? '301')=='301' ? 'selected' : '' }}>301 Permanent</option>
            <option value="302" {{ old('type', $redirect->type ?? '301')=='302' ? 'selected' : '' }}>302 Temporary</option>
          </select>
          @error('type')<div class="text-danger small">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-4 d-flex align-items-end">
          <div class="form-check">
            <input class="form-check-input" type="checkbox" name="status" value="1" id="status" {{ old('status', $redirect->status ?? true) ? 'checked' : '' }}>
            <label class="form-check-label" for="status">Active</label>
          </div>
        </div>
      </div>

      <div class="d-flex gap-2">
        <button class="btn btn-primary" type="submit">{{ $redirect->exists ? 'Update' : 'Create' }}</button>
        <a class="btn btn-outline-secondary" href="{{ route('admin.redirects.index') }}">Cancel</a>
      </div>
    </form>
  </div>
</div>
@endsection



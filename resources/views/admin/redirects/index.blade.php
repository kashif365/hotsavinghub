@extends('admin.layouts.app')

@section('content')
<div class="card">
  <div class="card-header d-flex justify-content-between align-items-center">
    <h5 class="mb-0">URL Redirects</h5>
    <a href="{{ route('admin.redirects.create') }}" class="btn btn-primary">Add Redirect</a>
  </div>
  <div class="card-body">
    <form method="get" class="row g-2 mb-3">
      <div class="col-md-3">
        <input type="text" class="form-control" name="q" value="{{ request('q') }}" placeholder="Search old/new URL">
      </div>
      <div class="col-md-3">
        <select name="type" class="form-select">
          <option value="">All Types</option>
          <option value="301" {{ request('type')=='301' ? 'selected' : '' }}>301 Permanent</option>
          <option value="302" {{ request('type')=='302' ? 'selected' : '' }}>302 Temporary</option>
        </select>
      </div>
      <div class="col-md-3">
        <select name="status" class="form-select">
          <option value="">All Status</option>
          <option value="active" {{ request('status')=='active' ? 'selected' : '' }}>Active</option>
          <option value="inactive" {{ request('status')=='inactive' ? 'selected' : '' }}>Inactive</option>
        </select>
      </div>
      <div class="col-md-3">
        <button class="btn btn-secondary" type="submit">Filter</button>
      </div>
    </form>

    @if(session('success'))
      <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="table-responsive">
      <table class="table table-striped align-middle">
        <thead>
          <tr>
            <th>ID</th>
            <th>Old URL</th>
            <th>New URL</th>
            <th>Type</th>
            <th>Status</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse($redirects as $redirect)
            <tr>
              <td>{{ $redirect->id }}</td>
              <td><code>{{ $redirect->old_url }}</code></td>
              <td><code>{{ $redirect->new_url }}</code></td>
              <td><span class="badge bg-label-info">{{ $redirect->type }}</span></td>
              <td>
                <form method="post" action="{{ route('admin.redirects.toggle', $redirect) }}">
                  @csrf
                  @method('patch')
                  <button class="btn btn-sm {{ $redirect->status ? 'btn-success' : 'btn-outline-secondary' }}" type="submit">
                    {{ $redirect->status ? 'Active' : 'Inactive' }}
                  </button>
                </form>
              </td>
              <td class="d-flex gap-2">
                <a class="btn btn-sm btn-warning" href="{{ route('admin.redirects.edit', $redirect) }}">Edit</a>
                <form method="post" action="{{ route('admin.redirects.destroy', $redirect) }}" onsubmit="return confirm('Delete this redirect?')">
                  @csrf
                  @method('delete')
                  <button class="btn btn-sm btn-danger" type="submit">Delete</button>
                </form>
              </td>
            </tr>
          @empty
            <tr><td colspan="6" class="text-center">No redirects found.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>

    {{ $redirects->links() }}
  </div>
</div>
@endsection



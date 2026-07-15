@php
    use Illuminate\Support\Str;
@endphp
@extends('admin.layouts.app')

@section('title', 'Meta & Script Manager')

@section('content')
<div class="d-flex justify-content-between flex-wrap align-items-center mb-4">
    <div>
        <h4 class="mb-1">Meta & Script Manager</h4>
        <p class="text-muted mb-0">Manage verification meta tags, tracking scripts, and partner snippets from a single place.</p>
    </div>
    <a href="{{ route('admin.verification-tags.create') }}" class="btn btn-primary">
        <i class="ri-add-line me-1"></i> Add Tag / Script
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-striped table-hover mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th style="width: 4rem;">#</th>
                        <th>Label</th>
                        <th>Type</th>
                        <th>Placement</th>
                        <th>Status</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tags as $tag)
                        <tr>
                            <td>{{ $tag->sort_order }}</td>
                            <td>
                                <strong>{{ $tag->label ?? '—' }}</strong>
                                <div class="text-muted small">
                                    @if($tag->type === \App\Models\VerificationTag::TYPE_META)
                                        &lt;meta {{ $tag->attribute_key }}="{{ $tag->attribute_value }}" content="{{ Str::limit($tag->content, 50) }}"&gt;
                                    @elseif($tag->type === \App\Models\VerificationTag::TYPE_SCRIPT)
                                        Inline script ({{ Str::limit($tag->code, 50) }})
                                    @else
                                        Custom snippet
                                    @endif
                                </div>
                            </td>
                            <td>{{ ucfirst($tag->type) }}</td>
                            <td>{{ ucwords(str_replace('_', ' ', $tag->placement)) }}</td>
                            <td>
                                <span class="badge {{ $tag->is_active ? 'bg-success' : 'bg-secondary' }}">
                                    {{ $tag->is_active ? 'Active' : 'Disabled' }}
                                </span>
                            </td>
                            <td class="text-end">
                                <a href="{{ route('admin.verification-tags.edit', $tag) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="ri-edit-line"></i>
                                </a>
                                <form action="{{ route('admin.verification-tags.destroy', $tag) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this tag?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i class="ri-delete-bin-line"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">
                                No verification tags yet. Click “Add Tag” to create your first meta or script snippet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection


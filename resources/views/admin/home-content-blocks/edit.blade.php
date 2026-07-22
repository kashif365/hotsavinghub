@extends('admin.layouts.app')

@section('content')
<div class="container mt-4">
    <div class="card">
        <div class="card-header">
            <h4 class="mb-0">Edit Home Content Block</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.home-content-blocks.update', $homeContentBlock) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="title" class="form-label">Title (admin reference only, not shown on the site)</label>
                    <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title', $homeContentBlock->title) }}" maxlength="255" placeholder="e.g. Buying Guide, FAQ, About this page...">
                    @error('title')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <div class="card">
                        <h5 class="card-header">Content</h5>
                        <div class="card-body">
                            <div id="full-editor" class="form-control">{!! old('content', $homeContentBlock->content ?? '') !!}</div>
                            <textarea id="content" name="content" class="d-none">{{ old('content', $homeContentBlock->content ?? '') }}</textarea>
                            <small class="form-text text-muted">Rich text shown inside the homepage's "additional content" block.</small>
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="status" class="form-label">Status</label>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="status" name="status" value="1" {{ $homeContentBlock->status ? 'checked' : '' }}>
                        <label class="form-check-label" for="status">Active</label>
                    </div>
                </div>

                <div class="mb-3">
                    <button type="submit" class="btn btn-primary">Update Content Block</button>
                    <a href="{{ route('admin.home-content-blocks.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Quill Editor Sync for Content -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    const editorNode = document.getElementById('full-editor');
    const hidden = document.getElementById('content');
    const form = editorNode.closest ? editorNode.closest('form') : document.querySelector('form');
    if (!editorNode || !hidden || !form) return;

    form.addEventListener('submit', function (ev) {
        try {
            let html = '';
            const quill = (typeof Quill !== 'undefined' && Quill.find) ? Quill.find(editorNode) : null;
            if (quill && quill.root) {
                html = quill.root.innerHTML;
            } else {
                const editorContent = editorNode.querySelector('.ql-editor');
                html = editorContent ? editorContent.innerHTML : editorNode.innerHTML;
            }
            hidden.value = html;
        } catch (err) {
            console.warn('Content editor sync failed:', err);
        }
    });
});
</script>
@endsection

@extends('admin.layouts.app')

@section('title', 'View Blog Post')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Blog Post Details</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.blogs.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to Blog List
                        </a>
                        <a href="{{ route('admin.blogs.edit', $blog) }}" class="btn btn-warning">
                            <i class="fas fa-edit"></i> Edit Blog
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <!-- Blog Content -->
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">{{ $blog->title }}</h4>
                                    <div class="card-tools">
                                        <span class="badge badge-{{ $blog->status === 'published' ? 'success' : 'warning' }}">
                                            {{ ucfirst($blog->status) }}
                                        </span>
                                    </div>
                                </div>
                                <div class="card-body">
                                    @if($blog->featured_image)
                                        <div class="mb-4">
                                            <img src="{{ asset( $blog->featured_image) }}" 
                                                 alt="{{ $blog->title }}" 
                                                 class="img-fluid rounded">
                                        </div>
                                    @endif

                                    @if($blog->excerpt)
                                        <div class="alert alert-info">
                                            <strong>Excerpt:</strong> {{ $blog->excerpt }}
                                        </div>
                                    @endif

                                    <div class="blog-content">
                                        {!! $blog->description !!}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <!-- Blog Information -->
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">Blog Information</h4>
                                </div>
                                <div class="card-body">
                                    <table class="table table-borderless">
                                        <tr>
                                            <td><strong>Author:</strong></td>
                                            <td>{{ $blog->author }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Status:</strong></td>
                                            <td>
                                                <span class="badge badge-{{ $blog->status === 'published' ? 'success' : 'warning' }}">
                                                    {{ ucfirst($blog->status) }}
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Views:</strong></td>
                                            <td>{{ number_format($blog->views_count) }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Sort Order:</strong></td>
                                            <td>{{ $blog->sort_order }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Created:</strong></td>
                                            <td>{{ $blog->formatted_date }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Updated:</strong></td>
                                            <td>{{ $blog->updated_at->format('M d, Y H:i') }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Slug:</strong></td>
                                            <td><code>{{ $blog->slug }}</code></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>

                            <!-- SEO Information -->
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">SEO Information</h4>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <strong>Meta Title:</strong>
                                        <p class="text-muted">{{ $blog->meta_title ?: 'Not set (will use blog title)' }}</p>
                                    </div>

                                    <div class="mb-3">
                                        <strong>Meta Description:</strong>
                                        <p class="text-muted">{{ $blog->meta_description ?: 'Not set' }}</p>
                                    </div>

                                    <div class="mb-3">
                                        <strong>Meta Keywords:</strong>
                                        <p class="text-muted">{{ $blog->meta_keywords ?: 'Not set' }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Actions -->
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">Actions</h4>
                                </div>
                                <div class="card-body">
                                    <div class="d-grid gap-2">
                                        <a href="{{ route('admin.blogs.edit', $blog) }}" class="btn btn-warning">
                                            <i class="fas fa-edit"></i> Edit Blog Post
                                        </a>
                                        
                                        @if($blog->status === 'published')
                                            <a href="{{ route('blog.show', $blog->slug) }}" 
                                               class="btn btn-info" 
                                               target="_blank">
                                                <i class="fas fa-external-link-alt"></i> View on Website
                                            </a>
                                        @endif

                                        <form action="{{ route('admin.blogs.destroy', $blog) }}" 
                                              method="POST" 
                                              class="d-inline"
                                              onsubmit="return confirm('Are you sure you want to delete this blog post?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger w-100">
                                                <i class="fas fa-trash"></i> Delete Blog Post
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.blog-content {
    line-height: 1.6;
    font-size: 16px;
}

.blog-content h1,
.blog-content h2,
.blog-content h3,
.blog-content h4,
.blog-content h5,
.blog-content h6 {
    margin-top: 2rem;
    margin-bottom: 1rem;
    font-weight: 600;
}

.blog-content p {
    margin-bottom: 1rem;
}

.blog-content ul,
.blog-content ol {
    margin-bottom: 1rem;
    padding-left: 2rem;
}

.blog-content img {
    max-width: 100%;
    height: auto;
    border-radius: 8px;
    margin: 1rem 0;
}

.blog-content blockquote {
    border-left: 4px solid #007bff;
    padding-left: 1rem;
    margin: 1rem 0;
    font-style: italic;
    background: #f8f9fa;
    padding: 1rem;
    border-radius: 4px;
}

.blog-content table {
    width: 100%;
    border-collapse: collapse;
    margin: 1rem 0;
}

.blog-content table th,
.blog-content table td {
    border: 1px solid #dee2e6;
    padding: 0.75rem;
    text-align: left;
}

.blog-content table th {
    background-color: #f8f9fa;
    font-weight: 600;
}
</style>
@endpush

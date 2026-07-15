@extends('frontend.layouts.app')

@section('title', ($blog->meta_title ?? $blog->title) . ' | Hotsavinghub')
@section('description', $blog->meta_description ?? Str::limit(strip_tags($blog->description), 160))

@push('meta')
    @if($blog->canonical_url)
        <link rel="canonical" href="{{ $blog->canonical_url }}">
    @else
        <link rel="canonical" href="{{ route('blog.single', $blog->slug) }}">
    @endif

    @if($blog->meta_keywords)
        <meta name="keywords" content="{{ $blog->meta_keywords }}">
    @else
        <meta name="keywords" content="{{ Str::slug($blog->title, ', ') }}">
    @endif
@endpush

@if($blog->schema && trim($blog->schema) !== '' && trim($blog->schema) !== 'test')
    @php
        $schemaContent = trim($blog->schema);
        // Check if it's already wrapped in script tag
        $isScriptTag = preg_match('/<script[^>]*type=["\']application\/ld\+json["\'][^>]*>/i', $schemaContent);
        // Check if it contains JSON structure
        $isJson = preg_match('/\{.*"@context".*\}/s', $schemaContent) || preg_match('/\{.*"@type".*\}/s', $schemaContent);
    @endphp
    @if($isScriptTag || $isJson)
        @push('head_scripts')
            @if(!$isScriptTag)
                <script type="application/ld+json">
            @endif
            {!! $schemaContent !!}
            @if(!$isScriptTag)
                </script>
            @endif
        @endpush
    @endif
@endif

@push('styles')
<link rel="preload" href="{{ asset('frontend_assets/css/fonts.css') }}" as="style" crossorigin>
<link rel="stylesheet" href="{{ asset('frontend_assets/css/fonts.css') }}" crossorigin>
<link rel="preload" href="{{ asset('frontend_assets/css/blog-single.css') }}" as="style" crossorigin>
<link rel="stylesheet" href="{{ asset('frontend_assets/css/blog-single.css') }}" crossorigin>

<style>
:root {
  --blog-primary-color: {{ $settings['primary_color'] ?? '#2951c4' }};
  --blog-secondary-color: {{ $settings['secondary_color'] ?? '#ff4444' }};
  --blog-text-color: {{ $settings['text_color'] ?? '#2d3748' }};
  --blog-heading-color: {{ $settings['text_color'] ?? '#1a202c' }};
  --blog-surface: {{ $settings['background_primary_color'] ?? '#ffffff' }};
  --blog-primary-light: {{ $settings['primary_color'] ?? '#2951c4' }}20;
  --blog-primary-lighter: {{ $settings['primary_color'] ?? '#2951c4' }}10;
  --blog-primary-hover: {{ $settings['primary_color'] ?? '#2951c4' }}CC;
}

/* Professional Single Blog Page Styles */
.blog-single-page {
    padding: 2rem 0 4rem;
    background: #f8f9fa;
    position: relative;
}

/* Reading Progress Bar */
.reading-progress {
    position: fixed;
    top: 0;
    left: 0;
    width: 0%;
    height: 3px;
    background: linear-gradient(90deg, var(--blog-primary-color, #2951c4) 0%, var(--blog-primary-hover, #2951c4) 100%);
    z-index: 9999;
    transition: width 0.1s ease;
    box-shadow: 0 2px 4px rgba(255, 0, 0, 0.3);
}

/* Breadcrumb Styles */
.breadcrumb-section {
    background: #ffffff;
    padding: 1rem 0;
    border-bottom: 1px solid #e2e8f0;
    max-width: 1200px;
    margin: 0 auto;
    padding-left: 1rem;
    padding-right: 1rem;
}

/* Blog Banner Section */
.blog-banner-section {
    width: 100%;
    max-width: 1200px;
    margin: 0 auto 2rem;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
    background: #f1f5f9;
}

.blog-banner-image {
    width: 100%;
    height: auto;
    max-height: 500px;
    object-fit: cover;
    display: block;
}

.blog-banner-placeholder {
    width: 100%;
    height: 400px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%);
}

.banner-placeholder-icon {
    font-size: 5rem;
    opacity: 0.3;
}

.breadcrumb-nav {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    flex-wrap: wrap;
    list-style: none;
    margin: 0;
    padding: 0;
}

.breadcrumb-nav li {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.breadcrumb-nav li:not(:last-child)::after {
    content: '→';
    color: #64748b;
    font-size: 0.875rem;
    margin-left: 0.5rem;
}

.breadcrumb-nav a {
    color: var(--blog-primary-color, #2951c4);
    text-decoration: none;
    font-size: 0.875rem;
    font-weight: 500;
    transition: color 0.3s ease;
}

.breadcrumb-nav a:hover {
    color: var(--blog-primary-hover, #cc0000);
    text-decoration: underline;
}

.breadcrumb-nav .active {
    color: #64748b;
    font-size: 0.875rem;
    max-width: 300px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

/* Main Content Layout */
.blog-content-layout {
    max-width: 1200px;
    margin: 0 auto;
    padding: 2rem 1rem;
    display: grid;
    grid-template-columns: 1fr 350px;
    gap: 2.5rem;
}

/* Main Article Card */
.blog-article-card {
    background: #ffffff;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
    border: 1px solid #e2e8f0;
}

.blog-article-header {
    padding: 2rem 2rem 1.5rem;
}

.blog-meta-info {
    display: flex;
    align-items: center;
    gap: 1.5rem;
    margin-bottom: 1.5rem;
    flex-wrap: wrap;
}

.blog-meta-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.875rem;
    color: #64748b;
    font-weight: 500;
}

.blog-meta-item svg {
    width: 16px;
    height: 16px;
    color: var(--blog-primary-color, #2951c4);
}

.blog-category-tag {
    display: inline-block;
    padding: 0.375rem 0.875rem;
    background: var(--blog-primary-color, #2951c4);
    color: #ffffff;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    text-decoration: none;
    transition: all 0.3s ease;
}

.blog-category-tag:hover {
    background: var(--blog-primary-hover, #cc0000);
    transform: translateY(-2px);
    box-shadow: 0 2px 8px rgba(255, 0, 0, 0.3);
}

.blog-title {
    font-size: 2.25rem;
    font-weight: 700;
    color: #1e293b;
    line-height: 1.2;
    margin-bottom: 1rem;
    letter-spacing: -0.02em;
}

/* Featured Image */
.blog-featured-image {
    width: 100%;
    height: auto;
    max-height: 500px;
    object-fit: cover;
    border-radius: 0;
    margin: 0;
    display: block;
}

.blog-image-wrapper {
    position: relative;
    width: 100%;
    overflow: hidden;
    background: #f1f5f9;
}

.blog-image-placeholder {
    width: 100%;
    height: 400px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%);
}

.blog-image-placeholder span {
    font-size: 4rem;
    opacity: 0.3;
}

/* Article Content */
.blog-article-content {
    padding: 2rem;
}

/* Reading Time */
.reading-time {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1rem;
    background: #f8f9fa;
    border-radius: 20px;
    font-size: 0.875rem;
    color: #64748b;
    font-weight: 500;
    margin-bottom: 1.5rem;
}

.reading-time svg {
    width: 16px;
    height: 16px;
    color: var(--blog-primary-color, #2951c4);
}

/* Blog Excerpt/Intro */
.blog-excerpt-section {
    background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
    border-left: 4px solid var(--blog-primary-color, #2951c4);
    padding: 1.5rem;
    margin: 2rem 0;
    border-radius: 8px;
    font-size: 1.125rem;
    line-height: 1.7;
    color: #475569;
    font-style: italic;
}

/* Enhanced Content Typography */
.blog-content {
    font-size: 1.125rem;
    line-height: 1.9;
    color: #334155;
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
}

.blog-content > *:first-child {
    margin-top: 0;
}

.blog-content > *:last-child {
    margin-bottom: 0;
}

.blog-content h2,
.blog-content h3,
.blog-content h4 {
    color: #1e293b;
    font-weight: 700;
    margin-top: 2.5rem;
    margin-bottom: 1rem;
    line-height: 1.3;
    letter-spacing: -0.01em;
}

.blog-content h2 {
    font-size: 2rem;
    padding-bottom: 0.5rem;
    border-bottom: 2px solid #e2e8f0;
}

.blog-content h3 {
    font-size: 1.5rem;
    margin-top: 2rem;
}

.blog-content h4 {
    font-size: 1.25rem;
    margin-top: 1.5rem;
}

.blog-content p {
    margin-bottom: 1.75rem;
    font-size: 1.125rem;
    line-height: 1.9;
    color: #334155;
}

.blog-content strong {
    color: #1e293b;
    font-weight: 700;
}

.blog-content em {
    font-style: italic;
    color: #475569;
}

.blog-content ul,
.blog-content ol {
    margin-bottom: 2rem;
    padding-left: 2rem;
}

.blog-content ul {
    list-style-type: disc;
}

.blog-content ol {
    list-style-type: decimal;
}

.blog-content li {
    margin-bottom: 0.75rem;
    line-height: 1.8;
    color: #334155;
}

.blog-content li::marker {
    color: var(--blog-primary-color, #2951c4);
}

.blog-content a {
    color: var(--blog-primary-color, #2951c4);
    text-decoration: underline;
    text-underline-offset: 2px;
    transition: all 0.3s ease;
    font-weight: 500;
}

.blog-content a:hover {
    color: var(--blog-primary-hover, #cc0000);
    text-decoration-thickness: 2px;
}

.blog-content img {
    max-width: 100%;
    height: auto;
    border-radius: 12px;
    margin: 2.5rem 0;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    display: block;
}

.blog-content blockquote {
    border-left: 4px solid var(--blog-primary-color, #2951c4);
    padding: 1.5rem 2rem;
    margin: 2.5rem 0;
    font-style: italic;
    color: #475569;
    background: #f8f9fa;
    border-radius: 0 8px 8px 0;
    font-size: 1.125rem;
    line-height: 1.7;
}

.blog-content blockquote p {
    margin-bottom: 0;
}

.blog-content code {
    background: #f1f5f9;
    padding: 0.25rem 0.5rem;
    border-radius: 4px;
    font-size: 0.9375rem;
    color: var(--blog-primary-color, #2951c4);
    font-family: 'Courier New', monospace;
}

.blog-content pre {
    background: #1e293b;
    color: #f1f5f9;
    padding: 1.5rem;
    border-radius: 8px;
    overflow-x: auto;
    margin: 2rem 0;
}

.blog-content pre code {
    background: transparent;
    color: inherit;
    padding: 0;
}

.blog-content table {
    width: 100%;
    border-collapse: collapse;
    margin: 2rem 0;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    border-radius: 8px;
    overflow: hidden;
}

.blog-content table th,
.blog-content table td {
    padding: 1rem;
    text-align: left;
    border-bottom: 1px solid #e2e8f0;
}

.blog-content table th {
    background: var(--blog-primary-color, #2951c4);
    color: #ffffff;
    font-weight: 700;
}

.blog-content table tr:hover {
    background: #f8f9fa;
}

/* Highlight Box */
.highlight-box {
    background: linear-gradient(135deg, var(--blog-primary-color, #2951c4) 0%, var(--blog-primary-hover, #2951c4) 100%);
    color: #ffffff;
    padding: 1.5rem;
    border-radius: 12px;
    margin: 2.5rem 0;
    box-shadow: 0 4px 12px rgba(255, 0, 0, 0.2);
}

.highlight-box p {
    margin-bottom: 0;
    color: #ffffff;
    font-weight: 500;
}

/* Social Share Section */
.social-share-section {
    padding: 2rem 0;
    border-top: 1px solid #e2e8f0;
    border-bottom: 1px solid #e2e8f0;
    margin: 2rem 0;
}

.social-share-title {
    font-size: 0.875rem;
    font-weight: 600;
    color: #64748b;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 1rem;
}

.social-share-buttons {
    display: flex;
    gap: 0.75rem;
    flex-wrap: wrap;
}

.social-share-btn {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.625rem 1.25rem;
    background: #f8f9fa;
    color: #334155;
    text-decoration: none;
    border-radius: 8px;
    font-size: 0.875rem;
    font-weight: 600;
    transition: all 0.3s ease;
    border: 1px solid #e2e8f0;
}

.social-share-btn:hover {
    background: var(--blog-primary-color, #2951c4);
    color: #ffffff;
    border-color: var(--blog-primary-color, #2951c4);
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(255, 0, 0, 0.2);
}

.social-share-btn svg {
    width: 16px;
    height: 16px;
}

/* Author Box */
.author-box {
    background: #f8f9fa;
    border-radius: 12px;
    padding: 2rem;
    margin: 2.5rem 0;
    border: 1px solid #e2e8f0;
}

.author-box-title {
    font-size: 1.125rem;
    font-weight: 700;
    color: #1e293b;
    margin-bottom: 1rem;
}

.author-box-content {
    display: flex;
    gap: 1.5rem;
    align-items: center;
}

.author-avatar {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    background: var(--blog-primary-color, #2951c4);
    display: flex;
    align-items: center;
    justify-content: center;
    color: #ffffff;
    font-size: 2rem;
    font-weight: 700;
    flex-shrink: 0;
}

.author-info {
    flex: 1;
}

.author-name {
    font-size: 1.125rem;
    font-weight: 700;
    color: #1e293b;
    margin-bottom: 0.5rem;
}

.author-bio {
    color: #64748b;
    line-height: 1.6;
    font-size: 0.9375rem;
}

/* Comments Section */
.comments-section {
    padding: 2rem;
    border-top: 1px solid #e2e8f0;
    margin-top: 2rem;
}

.comments-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 2rem;
    padding-bottom: 1rem;
    border-bottom: 2px solid var(--blog-primary-color, #2951c4);
}

.comments-title {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    font-size: 1.5rem;
    font-weight: 700;
    color: #1e293b;
    margin: 0;
}

.comments-title svg {
    color: var(--blog-primary-color, #2951c4);
}

.comments-count {
    font-size: 0.875rem;
    color: #64748b;
    font-weight: 500;
}

/* Comment Form */
.comment-form-wrapper {
    background: #f8f9fa;
    padding: 2rem;
    border-radius: 12px;
    margin-bottom: 2rem;
    border: 1px solid #e2e8f0;
}

.comment-form {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

.form-group {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.form-group label {
    font-size: 0.875rem;
    font-weight: 600;
    color: #1e293b;
}

.form-control {
    padding: 0.875rem 1rem;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    font-size: 1rem;
    color: #334155;
    background: #ffffff;
    transition: all 0.3s ease;
    font-family: inherit;
}

.form-control:focus {
    outline: none;
    border-color: var(--blog-primary-color, #2951c4);
    box-shadow: 0 0 0 3px rgba(255, 0, 0, 0.1);
}

.form-control::placeholder {
    color: #94a3b8;
}

.form-control textarea {
    resize: vertical;
    min-height: 120px;
}

.submit-comment-btn {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.875rem 1.75rem;
    background: var(--blog-primary-color, #2951c4);
    color: #ffffff;
    border: none;
    border-radius: 8px;
    font-size: 1rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 0 2px 8px rgba(255, 0, 0, 0.2);
    align-self: flex-start;
}

.submit-comment-btn:hover {
    background: var(--blog-primary-hover, #cc0000);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(255, 0, 0, 0.3);
}

.submit-comment-btn svg {
    width: 16px;
    height: 16px;
}

/* Comments List */
.comments-list {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

.no-comments {
    text-align: center;
    padding: 3rem 2rem;
    background: #f8f9fa;
    border-radius: 12px;
    border: 2px dashed #e2e8f0;
}

.no-comments-icon {
    font-size: 3rem;
    margin-bottom: 1rem;
    opacity: 0.5;
}

.no-comments p {
    color: #64748b;
    font-size: 1rem;
    margin: 0;
}

.comment-item {
    background: #ffffff;
    padding: 1.5rem;
    border-radius: 12px;
    border: 1px solid #e2e8f0;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
}

.comment-header {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-bottom: 1rem;
}

.comment-avatar {
    width: 48px;
    height: 48px;
    border-radius: 50%;
    background: var(--blog-primary-color, #2951c4);
    display: flex;
    align-items: center;
    justify-content: center;
    color: #ffffff;
    font-size: 1.25rem;
    font-weight: 700;
    flex-shrink: 0;
}

.comment-author-info {
    flex: 1;
}

.comment-author-name {
    font-size: 1rem;
    font-weight: 700;
    color: #1e293b;
    margin-bottom: 0.25rem;
}

.comment-date {
    font-size: 0.8125rem;
    color: #64748b;
}

.comment-message {
    color: #334155;
    line-height: 1.7;
    font-size: 0.9375rem;
    margin: 0;
}

/* Article Footer Navigation */
.blog-article-footer {
    padding: 2rem;
    border-top: 1px solid #e2e8f0;
    background: #f8f9fa;
}

.blog-navigation {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 1rem;
    flex-wrap: wrap;
}

.nav-button {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1.25rem;
    background: #ffffff;
    color: var(--blog-primary-color, #2951c4);
    text-decoration: none;
    border-radius: 8px;
    font-weight: 600;
    font-size: 0.875rem;
    border: 2px solid var(--blog-primary-color, #2951c4);
    transition: all 0.3s ease;
}

.nav-button:hover {
    background: var(--blog-primary-color, #2951c4);
    color: #ffffff;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(255, 0, 0, 0.3);
}

.nav-button.prev {
    flex: 1;
    min-width: 150px;
}

.nav-button.next {
    min-width: 150px;
    justify-content: flex-end;
}

.back-to-blog-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 0.75rem 1.5rem;
    background: var(--blog-primary-color, #2951c4);
    color: #ffffff;
    text-decoration: none;
    border-radius: 8px;
    font-weight: 600;
    font-size: 0.875rem;
    transition: all 0.3s ease;
    box-shadow: 0 2px 8px rgba(255, 0, 0, 0.2);
}

.back-to-blog-btn:hover {
    background: var(--blog-primary-hover, #cc0000);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(255, 0, 0, 0.3);
}

/* Sidebar Styles */
.blog-sidebar {
    display: flex;
    flex-direction: column;
    gap: 2rem;
    position: sticky;
    top: 2rem;
    align-self: start;
}

.sidebar-widget {
    background: #ffffff;
    border-radius: 12px;
    padding: 1.5rem;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
    border: 1px solid #e2e8f0;
}

.sidebar-widget-title {
    font-size: 1.25rem;
    font-weight: 700;
    color: #1e293b;
    margin-bottom: 1.5rem;
    padding-bottom: 0.75rem;
    border-bottom: 2px solid var(--blog-primary-color, #2951c4);
}

/* Related Posts */
.related-posts-list {
    display: flex;
    flex-direction: column;
    gap: 1.25rem;
}

.related-post-card {
    display: flex;
    gap: 1rem;
    text-decoration: none;
    color: inherit;
    transition: all 0.3s ease;
    padding: 0.75rem;
    border-radius: 8px;
}

.related-post-card:hover {
    background: #f8f9fa;
    transform: translateX(4px);
}

.related-post-image {
    width: 100px;
    height: 80px;
    object-fit: cover;
    border-radius: 8px;
    flex-shrink: 0;
}

.related-post-placeholder {
    width: 100px;
    height: 80px;
    background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%);
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.related-post-placeholder span {
    font-size: 1.5rem;
    opacity: 0.3;
}

.related-post-content {
    flex: 1;
    display: flex;
    flex-direction: column;
    gap: 0.375rem;
}

.related-post-title {
    font-size: 0.9375rem;
    font-weight: 600;
    color: #1e293b;
    line-height: 1.4;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    margin: 0;
}

.related-post-meta {
    font-size: 0.75rem;
    color: #64748b;
}

/* Categories Widget */
.categories-list {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.category-link {
    display: inline-flex;
    align-items: center;
    justify-content: space-between;
    padding: 0.75rem 1rem;
    background: #f8f9fa;
    color: #334155;
    text-decoration: none;
    border-radius: 8px;
    font-weight: 500;
    font-size: 0.875rem;
    transition: all 0.3s ease;
    border: 1px solid #e2e8f0;
}

.category-link:hover {
    background: var(--blog-primary-color, #2951c4);
    color: #ffffff;
    border-color: var(--blog-primary-color, #2951c4);
    transform: translateX(4px);
}

.category-link::after {
    content: '→';
    color: #64748b;
    transition: color 0.3s ease;
}

.category-link:hover::after {
    color: #ffffff;
}

/* Responsive Design */
@media (max-width: 992px) {
    .blog-content-layout {
        grid-template-columns: 1fr;
        gap: 2rem;
    }

    .blog-sidebar {
        order: -1;
        position: static;
    }

    .blog-navigation {
        flex-direction: column;
    }

    .nav-button.prev,
    .nav-button.next {
        width: 100%;
        justify-content: center;
    }
}

@media (max-width: 768px) {
    .blog-single-page {
        padding: 1rem 0 2rem;
    }

    .blog-content-layout {
        padding: 1rem 0.75rem;
    }

    .blog-article-header,
    .blog-article-content,
    .blog-article-footer {
        padding: 1.5rem;
    }

    .blog-title {
        font-size: 1.75rem;
    }

    .blog-meta-info {
        gap: 1rem;
    }

    .blog-content {
        font-size: 1rem;
    }

    .blog-content h2 {
        font-size: 1.5rem;
    }

    .blog-content h3 {
        font-size: 1.25rem;
    }
}

@media (max-width: 480px) {
    .blog-title {
        font-size: 1.5rem;
    }

    .blog-meta-info {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.75rem;
    }

    .related-post-card {
        flex-direction: column;
    }

    .related-post-image,
    .related-post-placeholder {
        width: 100%;
        height: 200px;
    }

    .social-share-buttons {
        flex-direction: column;
    }

    .social-share-btn {
        width: 100%;
        justify-content: center;
    }

    .author-box-content {
        flex-direction: column;
        text-align: center;
    }

    .author-avatar {
        margin: 0 auto;
    }

    .blog-banner-section {
        margin: 0 1rem 1.5rem;
        border-radius: 8px;
    }

    .blog-banner-image {
        max-height: 300px;
    }

    .blog-banner-placeholder {
        height: 250px;
    }

    .comment-form-wrapper {
        padding: 1.5rem;
    }

    .comments-section {
        padding: 1.5rem;
    }
}
</style>
@endpush

@section('content')

<!-- Reading Progress Bar -->
<div class="reading-progress" id="readingProgress"></div>

<!-- Blog Single Page -->
<div class="blog-single-page">
    <div class="container">
        <!-- Breadcrumb Section -->
        <!-- <div class="breadcrumb-section">
            <ul class="breadcrumb-nav">
                <li><a href="{{ route('home') }}">Home</a></li>
                <li><a href="{{ route('blog') }}">Blog</a></li>
                @if($blog->category)
                <li><a href="{{ route('blog.category', $blog->category->slug) }}">{{ $blog->category->name }}</a></li>
                @endif
                <li><span class="active">{{ Str::limit($blog->title, 50) }}</span></li>
            </ul>
        </div> -->

        <!-- Blog Banner Section -->
        <div class="blog-banner-section">
            @if($blog->featured_image)
                <img src="{{ asset($blog->featured_image) }}" alt="{{ $blog->title }}" class="blog-banner-image" loading="lazy" width="1200" height="400">
            @else
                <div class="blog-banner-placeholder">
                    <span class="banner-placeholder-icon">📝</span>
                </div>
            @endif
        </div>

        <!-- Main Content Layout -->
        <div class="blog-content-layout">
            <!-- Main Article -->
            <article class="blog-article-card">
                <!-- Article Header -->
                <div class="blog-article-header">
                    <div class="blog-meta-info">
                        @if($blog->category)
                        <a href="{{ route('blog.category', $blog->category->slug) }}" class="blog-category-tag">
                            {{ $blog->category->name }}
                        </a>
                        @endif
                        <div class="blog-meta-item">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                                <line x1="16" y1="2" x2="16" y2="6"/>
                                <line x1="8" y1="2" x2="8" y2="6"/>
                                <line x1="3" y1="10" x2="21" y2="10"/>
                            </svg>
                            <span>{{ $blog->created_at->format('M d, Y') }}</span>
                        </div>
                        @if($blog->author)
                        <div class="blog-meta-item">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                                <circle cx="12" cy="7" r="4"/>
                            </svg>
                            <span>{{ $blog->author }}</span>
                        </div>
                        @endif
                        @if($blog->views_count)
                        <div class="blog-meta-item">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                <circle cx="12" cy="12" r="3"/>
                            </svg>
                            <span>{{ number_format($blog->views_count) }} views</span>
                        </div>
                        @endif
                    </div>
                    <h1 class="blog-title">{{ $blog->title }}</h1>
                    @if($blog->excerpt)
                    <div class="blog-excerpt-section">
                        {{ $blog->excerpt }}
                    </div>
                    @endif
                    @php
                        $wordCount = str_word_count(strip_tags($blog->description));
                        $readingTime = max(1, round($wordCount / 200));
                    @endphp
                    <div class="reading-time">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10"/>
                            <polyline points="12 6 12 12 16 14"/>
                        </svg>
                        <span>{{ $readingTime }} min read</span>
                    </div>
                </div>

                <!-- Article Content -->
                <div class="blog-article-content">
                    <div class="blog-content">
                        {!! $blog->description !!}
                    </div>

                    <!-- Social Share Section -->
                    <div class="social-share-section">
                        <div class="social-share-title">Share this article</div>
                        <div class="social-share-buttons">
                            <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->url()) }}" target="_blank" rel="noopener noreferrer" class="social-share-btn">
                                <svg viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                </svg>
                                Facebook
                            </a>
                            <a href="https://twitter.com/intent/tweet?url={{ urlencode(request()->url()) }}&text={{ urlencode($blog->title) }}" target="_blank" rel="noopener noreferrer" class="social-share-btn">
                                <svg viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                                </svg>
                                Twitter
                            </a>
                            <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode(request()->url()) }}" target="_blank" rel="noopener noreferrer" class="social-share-btn">
                                <svg viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                                </svg>
                                LinkedIn
                            </a>
                            <a href="https://wa.me/?text={{ urlencode($blog->title . ' ' . request()->url()) }}" target="_blank" rel="noopener noreferrer" class="social-share-btn">
                                <svg viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                                </svg>
                                WhatsApp
                            </a>
                        </div>
                    </div>

                    <!-- Author Box -->
                    @if($blog->author)
                    <div class="author-box">
                        <div class="author-box-title">About the Author</div>
                        <div class="author-box-content">
                            <div class="author-avatar">
                                {{ strtoupper(substr($blog->author, 0, 1)) }}
                            </div>
                            <div class="author-info">
                                <div class="author-name">{{ $blog->author }}</div>
                                <div class="author-bio">
                                    Writer and content creator passionate about sharing valuable insights and tips.
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Comments Section -->
                <div class="comments-section">
                    <div class="comments-header">
                        <h3 class="comments-title">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
                            </svg>
                            Comments
                        </h3>
                        <span class="comments-count">0 comments</span>
                    </div>

                    <!-- Comment Form -->
                    <div class="comment-form-wrapper">
                        <form class="comment-form" id="commentForm">
                            @csrf
                            <input type="hidden" name="blog_id" value="{{ $blog->id }}">
                            <div class="form-group">
                                <label for="comment_name">Name *</label>
                                <input type="text" id="comment_name" name="name" class="form-control" required placeholder="Your name">
                            </div>
                            <div class="form-group">
                                <label for="comment_email">Email *</label>
                                <input type="email" id="comment_email" name="email" class="form-control" required placeholder="your.email@example.com">
                            </div>
                            <div class="form-group">
                                <label for="comment_message">Comment *</label>
                                <textarea id="comment_message" name="message" class="form-control" rows="5" required placeholder="Write your comment here..."></textarea>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="submit-comment-btn">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <line x1="22" y1="2" x2="11" y2="13"/>
                                        <polygon points="22 2 15 22 11 13 2 9 22 2"/>
                                    </svg>
                                    Post Comment
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Comments List -->
                    <div class="comments-list" id="commentsList">
                        <div class="no-comments">
                            <div class="no-comments-icon">💬</div>
                            <p>No comments yet. Be the first to comment!</p>
                        </div>
                    </div>
                </div>

                <!-- Article Footer Navigation -->
                <div class="blog-article-footer">
                    <div class="blog-navigation">
                        @if(!empty($previousBlog))
                            <a href="{{ route('blog.show', $previousBlog->slug) }}" class="nav-button prev">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polyline points="15 18 9 12 15 6"/>
                                </svg>
                                <span>Previous Post</span>
                            </a>
                        @else

                        @endif

                        <a href="{{ route('blog') }}" class="back-to-blog-btn">Back to Blog</a>

                        @if(!empty($nextBlog))
                            <a href="{{ route('blog.show', $nextBlog->slug) }}" class="nav-button next">
                                <span>Next Post</span>
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polyline points="9 18 15 12 9 6"/>
                                </svg>
                            </a>
                        @else
                            <span></span>
                        @endif
                    </div>
                </div>
            </article>

            <!-- Sidebar -->
            <aside class="blog-sidebar">
                <!-- Related Posts -->
                @if(!empty($relatedBlogs) && $relatedBlogs->count() > 0)
                <div class="sidebar-widget">
                    <h3 class="sidebar-widget-title">Related Posts</h3>
                    <div class="related-posts-list">
                        @foreach($relatedBlogs as $relatedBlog)
                        <a href="{{ route('blog.show', $relatedBlog->slug) }}" class="related-post-card">
                            @if($relatedBlog->featured_image)
                                <img src="{{ asset($relatedBlog->featured_image) }}" alt="{{ $relatedBlog->title }}" class="related-post-image" loading="lazy" width="400" height="250" decoding="async">
                            @else
                                <div class="related-post-placeholder">
                                    <span>📝</span>
                                </div>
                            @endif
                            <div class="related-post-content">
                                <h4 class="related-post-title">{{ $relatedBlog->title }}</h4>
                                <div class="related-post-meta">
                                    {{ $relatedBlog->created_at->format('M d, Y') }}
                                </div>
                            </div>
                        </a>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Categories Widget -->
                @if(!empty($blogCategories) && $blogCategories->count() > 0)
                <div class="sidebar-widget">
                    <h3 class="sidebar-widget-title">Categories</h3>
                    <div class="categories-list">
                        @foreach($blogCategories as $cat)
                            <a href="{{ route('blog.category', $cat->slug) }}" class="category-link">
                                {{ $cat->name }}
                            </a>
                        @endforeach
                    </div>
                </div>
                @endif
            </aside>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const slug = @json($blog->slug);
    if (slug) {
        fetch(`/blog/${slug}/view`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });
    }

    // Reading Progress Indicator
    const readingProgress = document.getElementById('readingProgress');
    const article = document.querySelector('.blog-article-card');

    if (readingProgress && article) {
        function updateProgress() {
            const windowHeight = window.innerHeight;
            const documentHeight = document.documentElement.scrollHeight;
            const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
            const scrollableHeight = documentHeight - windowHeight;
            const progress = (scrollTop / scrollableHeight) * 100;

            readingProgress.style.width = Math.min(100, Math.max(0, progress)) + '%';
        }

        window.addEventListener('scroll', updateProgress);
        window.addEventListener('resize', updateProgress);
        updateProgress();
    }

    // Comment Form Submission
    const commentForm = document.getElementById('commentForm');
    const commentsList = document.getElementById('commentsList');
    const commentsCount = document.querySelector('.comments-count');
    let commentCount = 0;

    if (commentForm) {
        commentForm.addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            const submitBtn = this.querySelector('.submit-comment-btn');
            const originalText = submitBtn.innerHTML;

            // Disable button and show loading
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="23 4 23 10 17 10"/><polyline points="1 20 1 14 7 14"/><path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"/></svg> Posting...';

            // Simulate comment submission (in real app, this would be an API call)
            setTimeout(() => {
                const name = formData.get('name');
                const email = formData.get('email');
                const message = formData.get('message');

                // Create comment element
                const commentDiv = document.createElement('div');
                commentDiv.className = 'comment-item';
                commentDiv.innerHTML = `
                    <div class="comment-header">
                        <div class="comment-avatar">${name.charAt(0).toUpperCase()}</div>
                        <div class="comment-author-info">
                            <div class="comment-author-name">${name}</div>
                            <div class="comment-date">Just now</div>
                        </div>
                    </div>
                    <p class="comment-message">${message}</p>
                `;

                // Remove no-comments message if exists
                const noComments = commentsList.querySelector('.no-comments');
                if (noComments) {
                    noComments.remove();
                }

                // Add comment to top of list
                commentsList.insertBefore(commentDiv, commentsList.firstChild);

                // Update comment count
                commentCount++;
                if (commentsCount) {
                    commentsCount.textContent = commentCount + (commentCount === 1 ? ' comment' : ' comments');
                }

                // Reset form
                this.reset();

                // Re-enable button
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;

                // Show success message (optional)
                const successMsg = document.createElement('div');
                successMsg.className = 'comment-success';
                successMsg.textContent = 'Thank you! Your comment has been posted.';
                successMsg.style.cssText = 'background: #10b981; color: white; padding: 0.75rem 1rem; border-radius: 8px; margin-bottom: 1rem; font-size: 0.875rem;';
                commentForm.parentElement.insertBefore(successMsg, commentForm);

                setTimeout(() => {
                    successMsg.remove();
                }, 3000);
            }, 500);
        });
    }
});
</script>
@endpush



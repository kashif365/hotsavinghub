@extends('frontend.layouts.app')

@section('title', 'Blog - Money Saving Tips & Shopping Guides | Hotsavinghub')
@section('description', 'Read money-saving tips, shopping guides, and exclusive discount code information. Learn how to save money on your favorite brands with our expert advice and verified deals.')
@section('keywords', 'money saving blog, shopping tips, discount code guides, saving money tips, shopping advice, coupon tips, deals blog')

@push('styles')
<link rel="preload" href="{{ asset('frontend_assets/css/fonts.css') }}" as="style" crossorigin>
<link rel="preload" href="{{ asset('frontend_assets/css/blog.css') }}" as="style" crossorigin>
<link rel="stylesheet" href="{{ asset('frontend_assets/css/fonts.css') }}" crossorigin>
<link rel="stylesheet" href="{{ asset('frontend_assets/css/blog.css') }}" crossorigin>

<style>
/* Dynamic Color Variables */
:root {
    --blog-primary-color: {{ $settings['primary_color'] ?? '#2951c4' }};
    --blog-primary-light: {{ $settings['primary_color'] ?? '#2951c4' }}20;
    --blog-primary-lighter: {{ $settings['primary_color'] ?? '#2951c4' }}10;
    --blog-primary-dark: {{ $settings['primary_color'] ?? '#2951c4' }}CC;
    --blog-secondary-color: {{ $settings['secondary_color'] ?? '#ff4444' }};
    --blog-accent-color: {{ $settings['primary_color'] ?? '#2951c4' }};
    --blog-text-color: {{ $settings['text_color'] ?? '#2d3748' }};
    --blog-heading-color: {{ $settings['text_color'] ?? '#1a202c' }};
    --blog-background-color: {{ $settings['background_primary_color'] ?? '#ffffff' }};
    --blog-card-background: {{ $settings['background_primary_color'] ?? '#ffffff' }};
}

/* Blog Hero Banner Section */
.blog-hero {
    background:
        linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)),
        url('{{ asset('frontend_assets/images/search-bg.webp') }}') center/cover no-repeat;
    color: white;
    padding: 80px 0;
    text-align: center;
    position: relative;
    overflow: hidden;
}

.blog-hero::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background:
        radial-gradient(circle at 20% 80%, var(--blog-primary-light) 0%, transparent 50%),
        radial-gradient(circle at 80% 20%, var(--blog-primary-light) 0%, transparent 50%),
        url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="white" opacity="0.1"/><circle cx="75" cy="75" r="1" fill="white" opacity="0.1"/><circle cx="50" cy="10" r="0.5" fill="white" opacity="0.1"/><circle cx="10" cy="60" r="0.5" fill="white" opacity="0.1"/><circle cx="90" cy="40" r="0.5" fill="white" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
    opacity: 0.3;
}

.blog-hero-content {
    position: relative;
    z-index: 2;
    max-width: 800px;
    margin: 0 auto;
    padding: 0 20px;
}

.blog-hero h1 {
    font-size: 3.5rem;
    font-weight: 700;
    margin-bottom: 20px;
    text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
}

.blog-hero p {
    font-size: 1.3rem;
    margin-bottom: 30px;
    opacity: 0.95;
    line-height: 1.6;
}

/* Responsive Design for Blog Hero */
@media (max-width: 768px) {
    .blog-hero h1 {
        font-size: 2.5rem;
    }

    .blog-hero p {
        font-size: 1.1rem;
    }
}

@media (max-width: 480px) {
    .blog-hero {
        padding: 60px 0;
    }

    .blog-hero h1 {
        font-size: 2rem;
    }
}

/* Blog Section - Clean AdSense-Friendly Design */
.blog-section {
    padding: 4rem 0;
    background: #ffffff;
}

.section-header {
    text-align: center;
    margin-bottom: 2rem;
}

.section-title {
    font-size: 2rem;
    font-weight: 700;
    color: #1e293b;
    margin-bottom: 0.75rem;
    letter-spacing: -0.02em;
    line-height: 1.2;
}

.title-normal {
    font-weight: 400;
    color: #64748b;
}

.title-highlight {
    font-weight: 700;
    color: var(--primary-color, #2951c4);
}

.section-subtitle {
    color: #64748b;
    font-size: 1rem;
    line-height: 1.6;
    max-width: 600px;
    margin: 0 auto;
}

.blog-category-filter {
    margin-top: 2rem;
    margin-bottom: 2.5rem;
    display: flex;
    justify-content: center;
}

.category-filter-tabs {
    display: flex;
    flex-wrap: wrap;
    gap: 0.75rem;
    justify-content: center;
}

.category-tab {
    padding: 0.625rem 1.25rem;
    background: #ffffff;
    color: var(--primary-color, #2951c4);
    text-decoration: none;
    font-weight: 600;
    font-size: 0.875rem;
    border-radius: 20px;
    border: 2px solid var(--primary-color, #2951c4);
    transition: all 0.3s ease;
    display: inline-block;
}

.category-tab:hover {
    background: var(--primary-color, #2951c4);
    color: #ffffff;
    transform: translateY(-2px);
    box-shadow: 0 2px 8px rgba(255, 0, 0, 0.2);
}

.category-tab.active {
    background: var(--primary-color, #2951c4);
    color: #ffffff;
    box-shadow: 0 2px 8px rgba(255, 0, 0, 0.2);
}

/* Blog Grid Layout */
.blog-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 2rem;
    margin-top: 2.5rem;
}

/* Blog Cards Styles */
.blog-card {
    background: #ffffff;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    border: 1px solid #f1f5f9;
    display: flex;
    flex-direction: column;
    height: 100%;
}

.blog-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
    border-color: #e2e8f0;
}

.blog-link {
    text-decoration: none;
    color: inherit;
    display: flex;
    flex-direction: column;
    height: 100%;
}

.blog-image-wrapper {
    position: relative;
    width: 100%;
    height: 220px;
    overflow: hidden;
    background: #f1f5f9;
}

.blog-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1);
}

.blog-card:hover .blog-image {
    transform: scale(1.05);
}

.blog-placeholder {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%);
}

.placeholder-icon {
    font-size: 3rem;
    opacity: 0.5;
}

.blog-category-badge {
    position: absolute;
    top: 12px;
    left: 12px;
    background: var(--primary-color, #2951c4);
    color: #ffffff;
    padding: 0.375rem 0.75rem;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    z-index: 2;
}

.blog-content {
    padding: 1.5rem;
    display: flex;
    flex-direction: column;
    flex-grow: 1;
}

.blog-meta {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-bottom: 1rem;
    flex-wrap: wrap;
}

.blog-date,
.blog-views {
    display: flex;
    align-items: center;
    gap: 0.375rem;
    font-size: 0.8125rem;
    color: #64748b;
    font-weight: 500;
}

.blog-date svg,
.blog-views svg {
    width: 16px;
    height: 16px;
    flex-shrink: 0;
}

.blog-title {
    font-size: 1.125rem;
    font-weight: 700;
    color: #1e293b;
    margin-bottom: 0.75rem;
    line-height: 1.4;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    flex-grow: 1;
}

.blog-excerpt {
    font-size: 0.875rem;
    /* WCAG AA compliant: darker gray for better contrast on white background */
    color: #334155 !important;
    line-height: 1.6;
    margin-bottom: 1rem;
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.blog-footer {
    margin-top: auto;
    padding-top: 0.75rem;
    border-top: 1px solid #f1f5f9;
}

.read-more {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    /* WCAG AA compliant: darker blue for high contrast on white background */
    color: #004085 !important;
    font-weight: 600;
    font-size: 0.875rem;
    transition: all 0.3s ease;
}

.blog-card:hover .read-more {
    gap: 0.75rem;
    /* Even darker blue on hover for maximum contrast */
    color: #003366 !important;
}

.read-more svg {
    width: 16px;
    height: 16px;
    transition: transform 0.3s ease;
}

.blog-card:hover .read-more svg {
    transform: translateX(4px);
}

.blog-view-all {
    text-align: center;
    margin-top: 3rem;
}

.view-all-blog-btn {
    display: inline-block;
    padding: 0.75rem 1.5rem;
    background: var(--primary-color, #2951c4);
    color: #ffffff;
    text-decoration: none;
    font-weight: 600;
    font-size: 0.9375rem;
    border-radius: 8px;
    transition: all 0.3s ease;
    box-shadow: 0 2px 8px rgba(255, 0, 0, 0.2);
    border: none;
    cursor: pointer;
}

.view-all-blog-btn:hover {
    background: var(--primary-hover, #cc0000);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(255, 0, 0, 0.3);
}

.view-all-blog-btn:disabled {
    opacity: 0.7;
    cursor: not-allowed;
    transform: none;
}

.view-all-blog-btn:disabled:hover {
    background: var(--primary-color, #2951c4);
    transform: none;
}

.no-blogs {
    grid-column: 1 / -1;
    text-align: center;
    padding: 4rem 2rem;
    background: #ffffff;
    border-radius: 16px;
    border: 2px dashed #e2e8f0;
}

.no-blogs-icon {
    font-size: 4rem;
    margin-bottom: 1rem;
    opacity: 0.5;
}

.no-blogs h3 {
    font-size: 1.5rem;
    font-weight: 600;
    color: #1e293b;
    margin-bottom: 0.5rem;
}

.no-blogs p {
    color: #64748b;
    font-size: 1rem;
}

/* Blog Section Responsive */
@media (max-width: 1200px) {
    .blog-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 1.5rem;
    }
}

@media (max-width: 768px) {
    .blog-section {
        padding: 3rem 0;
    }

    .blog-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 1.25rem;
    }

    .blog-image-wrapper {
        height: 200px;
    }

    .blog-content {
        padding: 1.25rem;
    }

    .blog-title {
        font-size: 1rem;
    }

    .blog-excerpt {
        font-size: 0.8125rem;
    }

    .category-filter-tabs {
        gap: 0.5rem;
    }

    .category-tab {
        padding: 0.5rem 1rem;
        font-size: 0.8125rem;
    }
}

@media (max-width: 480px) {
    .blog-grid {
        grid-template-columns: 1fr;
        gap: 1rem;
    }

    .blog-image-wrapper {
        height: 180px;
    }

    .blog-content {
        padding: 1rem;
    }

    .blog-meta {
        gap: 0.75rem;
    }

    .blog-date,
    .blog-views {
        font-size: 0.75rem;
    }
}
</style>
@endpush

@section('content')

<!-- Blog Hero Banner Section -->
<div class="blog-hero">
    <div class="blog-hero-content">
        <h1>Money Saving Blog</h1>
        <p>Stay updated with the latest money-saving tips, shopping guides, and exclusive discount code information. Learn how to save money on your favorite brands with our expert advice.</p>
    </div>
</div>

<!-- Blog Section -->
<div class="blog-section">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">
                <span class="title-normal">Latest</span>
                <span class="title-highlight" style="color: var(--primary-color, #2951c4) !important;">Blog Posts</span>
            </h2>
            <p class="section-subtitle">Stay updated with the latest deals, tips, and shopping guides</p>
        </div>

        <!-- Blog Category Filter -->
        @if(isset($blogCategories) && $blogCategories->count() > 0)
        <div class="blog-category-filter">
            <div class="category-filter-tabs">
                <a href="{{ route('blog') }}" class="category-tab {{ !isset($category) ? 'active' : '' }}" title="All Categories">
                    All
                </a>
                @foreach($blogCategories as $cat)
                    <a href="{{ route('blog.category', $cat->slug) }}" class="category-tab {{ isset($category) && $category->slug === $cat->slug ? 'active' : '' }}" title="{{ $cat->name }}">
                        {{ $cat->name }}
                    </a>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Blog Grid -->
        <div class="blog-grid">
            @include('frontend.partials.blog-content')
        </div>

        <!-- Show More Button -->
        @if($blogs->hasPages() && $blogs->hasMorePages())
        <div class="blog-view-all">
            <button id="loadMoreBtn" class="view-all-blog-btn" data-next-url="{{ $blogs->nextPageUrl() }}">
                Show More Posts →
            </button>
        </div>
        @endif
    </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Load More functionality
    const loadMoreBtn = document.getElementById('loadMoreBtn');
    const blogsContainer = document.querySelector('.blog-grid');

    if (loadMoreBtn) {
        loadMoreBtn.addEventListener('click', function() {
            const nextUrl = this.getAttribute('data-next-url');
            const originalText = this.innerHTML;

            // Show loading state
            this.innerHTML = 'Loading... <span><i class="bp_drprgt-r"></i></span>';
            this.disabled = true;

            // Make AJAX request
            fetch(nextUrl, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                }
            })
            .then(response => response.text())
            .then(html => {
                // Create a temporary container to parse the response
                const tempDiv = document.createElement('div');
                tempDiv.innerHTML = html;

                // Extract new blog posts
                const newBlogs = tempDiv.querySelectorAll('.blog-card');

                // Append new blogs to container
                newBlogs.forEach(blog => {
                    blogsContainer.appendChild(blog);
                });

                // Check if there are more pages by looking at the response
                // If we got fewer blogs than expected, we've reached the end
                if (newBlogs.length < 6) {
                    // No more pages, hide the button
                    this.parentElement.style.display = 'none';
                } else {
                    // Update button URL for next page
                    const currentUrl = new URL(nextUrl);
                    const currentPage = parseInt(currentUrl.searchParams.get('page') || '1');
                    const nextPage = currentPage + 1;
                    currentUrl.searchParams.set('page', nextPage);
                    this.setAttribute('data-next-url', currentUrl.toString());
                    this.innerHTML = originalText;
                    this.disabled = false;
                }

                // Re-attach event listeners to new blog links for view tracking
                const newBlogLinks = tempDiv.querySelectorAll('a[href*="/blog/"]');
                newBlogLinks.forEach(link => {
                    link.addEventListener('click', function() {
                        const slug = this.href.split('/blog/')[1];
                        if (slug) {
                            fetch(`/blog/${slug}/view`, {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                                }
                            });
                        }
                    });
                });
            })
            .catch(error => {
                console.error('Error loading more blogs:', error);
                this.innerHTML = originalText;
                this.disabled = false;
                alert('Error loading more blogs. Please try again.');
            });
        });
    }

    // Track blog views
    const blogLinks = document.querySelectorAll('a[href*="/blog/"]');
    blogLinks.forEach(link => {
        link.addEventListener('click', function() {
            const slug = this.href.split('/blog/')[1];
            if (slug) {
                fetch(`/blog/${slug}/view`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });
            }
        });
    });

    // Handle category active state
    const categoryLinks = document.querySelectorAll('.category-tab');
    categoryLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            // Remove active class from all category tabs
            document.querySelectorAll('.category-tab').forEach(tab => {
                tab.classList.remove('active');
            });

            // Add active class to clicked category tab
            this.classList.add('active');

            // Navigation will happen naturally via href
        });
    });
});
</script>
@endpush

@forelse($blogs as $blog)
    <!-- Single Blog -->
    <article class="blog-card">
        <a href="{{ route('blog.show', $blog->slug) }}" class="blog-link">
            <div class="blog-image-wrapper">
                @if($blog->featured_image)
                    <img src="{{ asset($blog->featured_image) }}" alt="{{ $blog->title }}" class="blog-image" loading="lazy" width="400" height="250">
                @else
                    <div class="blog-placeholder">
                        <span class="placeholder-icon">📝</span>
                    </div>
                @endif
                @if($blog->category)
                    <div class="blog-category-badge">
                        {{ $blog->category->name ?? 'Blog' }}
                    </div>
                @endif
            </div>
            
            <div class="blog-content">
                <div class="blog-meta">
                    <span class="blog-date">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                            <line x1="16" y1="2" x2="16" y2="6"/>
                            <line x1="8" y1="2" x2="8" y2="6"/>
                            <line x1="3" y1="10" x2="21" y2="10"/>
                        </svg>
                        {{ $blog->created_at->format('M d, Y') }}
                    </span>
                    @if($blog->views_count)
                    <span class="blog-views">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                            <circle cx="12" cy="12" r="3"/>
                        </svg>
                        {{ number_format($blog->views_count) }} views
                    </span>
                    @endif
                </div>
                
                <h3 class="blog-title">{{ $blog->title }}</h3>
                
                @if($blog->excerpt)
                <p class="blog-excerpt">{{ \Illuminate\Support\Str::limit(strip_tags($blog->excerpt), 100) }}</p>
                @endif
                
                <div class="blog-footer">
                    <span class="read-more">
                        Read More
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="5" y1="12" x2="19" y2="12"/>
                            <polyline points="12 5 19 12 12 19"/>
                        </svg>
                    </span>
                </div>
            </div>
        </a>
    </article>
    <!-- Single Blog End -->
@empty
    <div class="no-blogs">
        <div class="no-blogs-icon">📝</div>
        <h3>No blog posts found</h3>
        <p>Check back later for new content!</p>
    </div>
@endforelse


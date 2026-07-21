@extends('frontend.layouts.app')

@section('title', $homePage->meta_title ?? 'Discount Codes & Voucher Codes UK | Hotsavinghub')
@section('description', $homePage->meta_description ?? 'Discover the latest UK discount codes and voucher codes at Hotsavinghub. Explore exclusive online deals from verified retailers. Save money on fashion, electronics, food, travel, and more. Updated daily with fresh offers.')

@if($homePage && $homePage->canonical_url)
    <link rel="canonical" href="{{ $homePage->canonical_url }}">
@else
    <link rel="canonical" href="{{ url('/') }}">
@endif

@if($homePage && $homePage->meta_keywords)
    <meta name="keywords" content="{{ $homePage->meta_keywords }}">
@else
    <meta name="keywords" content="discount codes UK, voucher codes UK, promo codes, coupon codes, online deals, save money, verified coupons, exclusive offers, UK shopping deals">
@endif

@if($homePage && $homePage->schema && trim($homePage->schema) !== '' && trim($homePage->schema) !== 'test')
    @php
        $schemaContent = trim($homePage->schema);
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
@section('content')
    @include('frontend.partials.home.hero')
    @include('frontend.partials.home.categories')
    @include('frontend.partials.home.exclusive-offers')
    @include('frontend.partials.home.banner')
    @include('frontend.partials.home.featured-brands')
    @include('frontend.partials.home.category-deals')
    @include('frontend.partials.home.page-content')

<!-- Blog Section -->
<!-- <div class="blog-section">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">
                <span class="title-normal">Latest</span>
                <span class="title-highlight" style="color: var(--primary-color) !important;">Blog Posts</span>
            </h2>
            <p class="section-subtitle">Stay updated with the latest deals, tips, and shopping guides</p>
        </div>

        <div class="blog-grid">
            @forelse($featuredBlogs ?? [] as $blogIndex => $blog)
            <article class="blog-card @if($blogIndex >= 4) hide-on-mobile @endif">
                <a href="{{ route('blog.show', $blog->slug) }}" class="blog-link">
                    <div class="blog-image-wrapper">
                        @if($blog->featured_image)
                            <img src="{{ asset($blog->featured_image) }}" alt="{{ $blog->title }}" class="blog-image" width="400" height="250">
                        @else
                            <div class="blog-placeholder">
                                <span class="placeholder-icon">📝</span>
                            </div>
                        @endif
                        @if($blog->category)
                            <div class="blog-category-badge">
                                {{ strtoupper($blog->category->name ?? 'BLOG') }}
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
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <line x1="5" y1="12" x2="19" y2="12"/>
                                    <polyline points="12 5 19 12 12 19"/>
                                </svg>
                            </span>
                        </div>
                    </div>
                </a>
            </article>
            @empty
            <div class="no-blogs">
                <div class="no-blogs-icon">📝</div>
                <h3>No Blog Posts Available</h3>
                <p>Check back soon for latest updates and guides!</p>
            </div>
            @endforelse
        </div>

        @if(isset($featuredBlogs) && $featuredBlogs->count() > 0)
        <div class="blog-view-all">
            <a href="{{ route('blog') }}" class="view-all-blog-btn">View All Blog Posts →</a>
        </div>
        @endif
    </div>
</div> -->


<!-- Enhanced Coupon Modal -->
<div id="couponModal" aria-hidden="true" style="display:none;">
    <div class="cm-overlay"></div>

    <!-- Main Voucher Code Popup -->
    <div class="cm-main-popup" role="dialog" aria-modal="true" aria-label="Coupon Code Popup">
        <button class="cm-close" aria-label="Close popup">&times;</button>

        <!-- Main Popup Content -->
        <div class="cm-main-content text-center">
            <h3 class="cm-title" id="cmTitle">Here is your code</h3>

            <div class="cm-code-section">
                <div class="cm-code-display" id="cmCode">CODE123</div>
                <button class="cm-copy-btn" id="cmCopy">Copy Code</button>
                <button class="cm-redirect-btn" id="cmRedirect">Visit Store</button>
            </div>

            <div class="cm-note" id="cmNote">
                <p>Copy the code above and use it at checkout to get your discount!</p>
            </div>
        </div>
    </div>

    <!-- Email Subscription Popup -->
    <div class="cm-email-popup" role="dialog" aria-modal="true" aria-label="Email Subscription Popup">
        <div class="cm-email-content">
            <div class="cm-brand-logo">
                <div class="cm-brand-circle" id="cmBrandLogo">
                    <span id="cmBrandText">STORE</span>
                </div>
            </div>

            <h3 class="cm-email-title" id="cmEmailTitle">Get More Deals!</h3>
            <p class="cm-email-subtitle">Subscribe to get exclusive offers and discounts</p>

            <form class="cm-email-form" id="cmEmailForm">
                <input type="email" placeholder="Enter your email" required>
                <button type="submit">Subscribe</button>
            </form>

            <p class="cm-email-privacy">We respect your privacy. Unsubscribe at any time.</p>
        </div>
    </div>
</div>

<style>
.category-image{
    width:30%;
    height: inherit;
}

.category-title-icon{
    width:15%;
}
.category-card p{
    display: -webkit-box;
    -webkit-line-clamp: 2;  /* 👉 Sirf 2 lines show karega */
    -webkit-box-orient: vertical;
    overflow: hidden;
    text-overflow: ellipsis;
}
/* Coupon Modal Styles */
#couponModal {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 9999;
    padding: 20px;
    box-sizing: border-box;
}

.cm-overlay {
    position: absolute;
    inset: 0;
    background: rgba(0,0,0,0.6);
}

/* Main Voucher Code Popup */
.cm-main-popup {
  position: relative;
  top: 20px;
  margin: auto;
    width: 480px;
    max-width: calc(50% - 30px);
    background: #fff;
    border-radius: 16px;
    padding: 30px;
    box-shadow: 0 20px 40px rgba(0,0,0,0.3);
    overflow: hidden;
    z-index: 2;
}

/* Email Subscription Popup */
.cm-email-popup {
  position: relative;
  margin: auto;
  top: 40px;
    width: 480px;
    max-width: calc(50% - 30px);
    background: #fff;
    border-radius: 16px;
    padding: 30px;
    box-shadow: 0 20px 40px rgba(0,0,0,0.3);
    overflow: hidden;
    z-index: 2;
}

#couponModal .cm-close {
    position: absolute;
    top: 15px;
    right: 15px;
    background: transparent;
    border: none;
    font-size: 24px;
    cursor: pointer;
    color: #666;
    z-index: 10;
    width: 30px;
    height: 30px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    transition: background-color 0.2s;
}

#couponModal .cm-close:hover {
    background: rgba(0,0,0,0.1);
}

/* Main Popup Content */
.cm-main-content {
    background: #fff;
    text-align: center;
}

.cm-title {
    font-size: 28px;
    font-weight: 700;
    color: #111827;
    margin: 0 0 25px;
    line-height: 1.2;
}

.cm-code-section {
    margin: 20px 0;
}

.cm-code-display {
    background: #f8f9fa;
    border: 2px dashed var(--primary-color);
    border-radius: 8px;
    padding: 15px;
    font-size: 18px;
    font-weight: bold;
    color: var(--primary-color);
    margin-bottom: 15px;
    font-family: monospace;
}

.cm-copy-btn {
    background: var(--primary-color);
    color: white;
    border: none;
    padding: 12px 24px;
    border-radius: 6px;
    font-size: 16px;
    cursor: pointer;
    transition: background 0.3s;
    width: 100%;
}

.cm-copy-btn:hover {
    background: var(--primary-hover, #1e3a8a);
}

.cm-redirect-btn {
    background: var(--secondary-color, #333);
    color: white;
    border: none;
    padding: 12px 24px;
    border-radius: 6px;
    font-size: 16px;
    cursor: pointer;
    transition: background 0.3s;
    margin-top: 10px;
    width: 100%;
}

.cm-redirect-btn:hover {
    background: var(--text-color, #555);
}

.cm-note {
    color: #6b7280;
    font-size: 15px;
    margin: 20px 0;
    line-height: 1.6;
    max-width: 400px;
    margin-left: auto;
    margin-right: auto;
}

/* Feedback Section */
.cm-feedback {
    margin: 25px 0;
    padding: 20px 0;
    border-top: 1px solid #f0f0f0;
}

.cm-feedback p {
    margin: 0 0 15px;
    color: #6b7280;
    font-size: 15px;
    font-weight: 500;
}

.cm-feedback-buttons {
    display: flex;
    gap: 20px;
    justify-content: center;
}

.cm-feedback-btn {
    background: transparent;
    border: 2px solid #d1d5db;
    border-radius: 50%;
    width: 45px;
    height: 45px;
    cursor: pointer;
    font-size: 20px;
    transition: all 0.2s;
    display: flex;
    align-items: center;
    justify-content: center;
}

.cm-feedback-btn:hover {
    border-color: #10b981;
    background: #f0fdf4;
    transform: scale(1.1);
}

/* More Details */
.cm-more-details {
    margin: 20px 0;
}

.cm-more-btn {
    background: transparent;
    border: none;
    color: #6b7280;
    font-size: 14px;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 5px;
    margin: 0 auto;
    padding: 8px 12px;
    border-radius: 6px;
    transition: background-color 0.2s;
}

.cm-more-btn:hover {
    background: #f3f4f6;
}

.cm-chevron {
    font-size: 12px;
    transition: transform 0.2s;
}

/* Email Popup Content */
/* .cm-email-content {
    padding: 25px 20px 20px;
    background: #f8f9fa;
} */

.cm-brand-logo {
    margin-bottom: 15px;
}

.cm-brand-circle {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    background: var(--primary-color);
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto;
    color: white;
    font-weight: bold;
    font-size: 14px;
}

.cm-email-title {
    text-align: center;
    font-size: 22px;
    font-weight: bold;
    margin-bottom: 10px;
    color: #333;
}

.cm-email-form {
    display: flex;
    flex-direction: column;
    gap: 10px;
    margin-bottom: 15px;
}

.cm-email-form label {
    font-size: 13px;
    font-weight: 500;
    color: #374151;
    text-align: left;
    margin-bottom: 3px;
}

.cm-email-form input {
    padding: 12px 14px;
    border: 1px solid #d1d5db;
    border-radius: 6px;
    font-size: 14px;
    transition: border-color 0.2s;
}

.cm-email-form input:focus {
    outline: none;
    border-color: #10b981;
    box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
}

.cm-email-form button {
    width: 100%;
    background: var(--primary-color);
    color: white;
    border: none;
    padding: 12px;
    border-radius: 6px;
    font-size: 16px;
    cursor: pointer;
    transition: background 0.3s;
}

.cm-email-form button:hover {
    background: var(--primary-hover, #1e3a8a);
}

.cm-email-consent {
    font-size: 11px;
    color: #6b7280;
    line-height: 1.4;
    margin: 0;
}

.cm-email-consent a {
    color: #ef4444;
    text-decoration: underline;
    font-weight: 500;
}

/* Website Logo */
.cm-website-logo {
    padding: 15px;
    border-top: 1px solid #e5e7eb;
    background: #fff;
    text-align: center;
}

.cm-website-name {
    font-size: 14px;
    font-weight: 600;
    color: #111827;
    letter-spacing: 0.5px;
}

/* Fixed Card Layout - Exact Reference Match */
.Sec.fdvo .cpns {
    display: flex;
    gap: 20px;
    flex-wrap: wrap;
    justify-content: center;
}

.Sec.fdvo .cpn {
    width: 320px;
    background: #fff;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 0 5px 3px rgba(0,0,0,0.07);
    position: relative;
    transition: transform 0.2s, box-shadow 0.2s;
}

.Sec.fdvo .cpn:hover {
    transform: translateY(-2px);
    box-shadow: 0 0 5px 3px rgba(0,0,0,0.02);
}

.Sec.fdvo .cpn .imgs {
    position: relative;
    height: 140px;
    width: 100%;
    border-bottom: 1px solid #D5D5D5;
}

.Sec.fdvo .cpn .imgs img.cvr {
    width: 100%;
    height: 140px;
    object-fit: cover;
    display: block;
}

.Sec.fdvo .cpn .imgs a {
    position: absolute;
    left: 20px;
    bottom: -30px;
    display: block;
}

.Sec.fdvo .cpn .imgs .store-logo {
    width: 70px;
    height: 70px;
    border-radius: 50%;
    object-fit: cover;
    display: block;
    border: 3px solid #fff;
    box-shadow: 0 3px 6px rgba(0,0,0,0.16);
}

.Sec.fdvo .cpn .cnt {
    padding: 40px 20px 20px 20px;
    display: flex;
    flex-direction: column;
    gap: 5px 0;
}

.Sec.fdvo .cpn .str-vrf {
    display: flex;
    gap: 8px;
    align-items: center;
    font-size: 12px;
    color: #6d6e71;
    margin-bottom: 5px;
    justify-content: space-between;
    height: 16px;
}

.Sec.fdvo .cpn .str-vrf a {
    color: #6d6e71;
    font-weight: 400;
    text-decoration: none;
    max-width: 170px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.verified-badge {
    background: #fef08e;
    color: #0f0f0f;
    padding: 3px 6px 2px;
    border-radius: 5px;
    font-size: 12px;
    font-weight: 300;
    text-transform: uppercase;
    line-height: 1;
}

.Sec.fdvo .cpn h3 {
    margin: 10px 0;
    font-size: 14px;
    color: #0f0f0f;
    line-height: 1.3;
    font-weight: 500;
    height: 37px;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    text-align: left;
}

.Sec.fdvo .cpn .trm-cnt {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 12px;
    color: #6d6e71;
    font-size: 12px;
    height: 18px;
}

.Sec.fdvo .cpn .trm-cnt button {
    background: transparent;
    border: none;
    color: #6d6e71;
    cursor: pointer;
    font-size: 12px;
    font-weight: 300;
}

/* Enhanced Button Styling - Exact Reference Match */
.cpBtn {
    display: inline-flex;
    flex-wrap: wrap;
    align-items: center;
    align-self: center;
    width: 100%;
    justify-content: center;
    padding: 12px 12px;
    background-color: var(--primary-color);
    color: #fff;
    border-radius: 8px;
    font-weight: 300;
    line-height: 1;
    text-decoration: none;
    border: none;
    cursor: pointer;
    font-size: 14px;
    transition: background-color 0.2s;
    position: relative;
}

.cpBtn:hover {
    background-color: var(--primary-hover, #1e3a8a);
    opacity: 0.95;
}

.cpBtn.reveal-code {
    background-color: #f2f0e6;
    color: #0f0f0f;
    padding-left: 30px;
    position: relative;
    overflow: hidden;
    z-index: 1;
}

.cpBtn.reveal-code::before {
    content: attr(data-code);
    display: inline-flex;
    position: absolute;
    width: 50px;
    height: 100%;
    top: 0;
    right: 0;
    align-items: center;
    padding: 0 15px 0 0;
    overflow: hidden;
    border: 2px dashed var(--primary-color);
    border-left: 0;
    text-transform: uppercase;
    justify-content: end;
    box-sizing: border-box;
    border-radius: 0 9px 9px 0;
    color: #0f0f0f;
    z-index: -1;
    font-size: 14px;
}

.cpBtn.reveal-code::after {
    content: "";
    position: absolute;
    width: 100%;
    height: calc(100% + 2px);
    background-color: var(--primary-color);
    top: 0;
    right: 34px;
    transform: skewX(25deg);
    transition: .2s ease-in-out;
    z-index: -1;
}

.cpBtn.reveal-code:hover::after {
    right: 45px;
    box-shadow: 5px 0 5px 0 #00000040;
}

.cpBtn.get-deal {
    background-color: var(--primary-color);
    color: #fff;
}

.cpBtn.get-deal:hover {
    background-color: var(--primary-hover, #1e3a8a);
    opacity: 0.95;
}

/* Responsive Design */
@media (max-width: 1200px) {
    .Sec.fdvo .cpns {
        justify-content: center;
    }

    .Sec.fdvo .cpn {
        width: 300px;
    }
}

@media (max-width: 768px) {
    .Sec.fdvo .cpn {
        width: 100%;
        max-width: 350px;
    }

    #couponModal {
        gap: 15px;
    }

    .cm-main-popup {
        width: 95%;
        max-width: 450px;
    }

    .cm-email-popup {
        width: 95%;
        max-width: 450px;
    }

    .cm-main-content {
        padding: 25px 20px 20px;
    }

    .cm-email-content {
        padding: 20px 15px 15px;
    }
}

@media (max-width: 480px) {
    .Sec.fdvo .cpns {
        gap: 15px;
    }

    .Sec.fdvo .cpn .cnt {
        padding: 30px 15px 15px 15px;
    }

    .cm-code-wrap {
        flex-direction: column;
        gap: 10px;
    }

    .cm-verification {
        flex-direction: column;
        gap: 10px;
    }
}
.category-stats{
    color: #000;
}
</style>

<script>
// CRITICAL: Set up button event listeners IMMEDIATELY (before defer scripts)
// This must run first to ensure buttons work immediately
(function() {
    'use strict';

    // Event delegation for buttons - Works even if DOM is not fully loaded
    document.addEventListener('click', function(e) {
        var target = e.target;
        var button = null;

        // Traverse up the DOM tree to find the button (handles clicks on child elements like spans)
        while (target && target !== document.body && target !== document.documentElement) {
            if (target.classList) {
                var hasDealBtn = target.classList.contains('deal-btn') || target.classList.contains('cpBtn');
                var hasRevealCode = target.classList.contains('reveal-code');
                var hasGetDeal = target.classList.contains('get-deal');

                if (hasDealBtn && (hasRevealCode || hasGetDeal)) {
                    button = target;
                    break;
                }
            }
            target = target.parentElement;
        }

        if (!button) return;

        e.preventDefault();
        e.stopPropagation();

        var isRevealCode = button.classList.contains('reveal-code');
        var couponId = button.dataset.couponId || button.dataset.id;
        var code = button.dataset.code;
        var affiliate = isRevealCode ? button.dataset.affiliate : (button.getAttribute('href') || button.dataset.affiliate || '#');
        var store = button.dataset.store || button.dataset.title || 'Store';
        var title = button.dataset.title || (isRevealCode ? 'Here is your code' : 'Get Deal');

        // Track coupon view (defer to reduce blocking)
        if (couponId) {
            setTimeout(function() {
                var csrfToken = document.querySelector('meta[name="csrf-token"]');
                fetch('{{ route("coupon.track-view") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken ? csrfToken.getAttribute('content') : ''
                    },
                    body: JSON.stringify({ coupon_id: couponId })
                }).catch(function() {});
            }, 0);
        }

        // Handle Reveal Code button - Open popup and redirect to store
        if (isRevealCode && code && affiliate) {
            var currentUrl = window.location.href.split('#')[0].split('?')[0];
            var popupUrl = currentUrl + '?show_coupon=1&code=' + encodeURIComponent(code) + '&affiliate=' + encodeURIComponent(affiliate) + '&store=' + encodeURIComponent(store) + '&title=' + encodeURIComponent(title);
            // Defer window.open() and navigation to allow bfcache restoration
            setTimeout(function() {
                var popup = window.open(popupUrl, '_blank', 'noopener,noreferrer');
                // If popup blocked, use modal instead
                if (!popup || popup.closed || typeof popup.closed === 'undefined') {
                    if (window.openModal) {
                        window.openModal(code, affiliate, store, title);
                    }
                }
            }, 0);
            // Defer navigation to allow bfcache
            setTimeout(function() {
                window.location.href = affiliate;
            }, 0);
        }
        // Handle Get Deal button - Open popup and redirect to store
        else if (!isRevealCode && affiliate && affiliate !== '#') {
            var currentUrl = window.location.href.split('#')[0].split('?')[0];
            var popupUrl = currentUrl + '?show_coupon=1&code=&affiliate=' + encodeURIComponent(affiliate) + '&store=' + encodeURIComponent(store) + '&title=' + encodeURIComponent(title);
            // Defer window.open() and navigation to allow bfcache restoration
            setTimeout(function() {
                var popup = window.open(popupUrl, '_blank', 'noopener,noreferrer');
                // If popup blocked, use modal instead
                if (!popup || popup.closed || typeof popup.closed === 'undefined') {
                    if (window.openModal) {
                        window.openModal('', affiliate, store, title);
                    }
                }
            }, 0);
            // Defer navigation to allow bfcache
            setTimeout(function() {
                window.location.href = affiliate;
            }, 0);
        }
    }, true); // Use capture phase for immediate handling
})();

// Handle Get Deal button click - Same popup as coupon code
function handleGetDealClick(couponId, affiliate, store, title) {
    // Track coupon view (defer to reduce blocking)
    if (couponId) {
        setTimeout(function() {
            var csrfToken = document.querySelector('meta[name="csrf-token"]');
            fetch('{{ route("coupon.track-view") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken ? csrfToken.getAttribute('content') : ''
                },
                body: JSON.stringify({ coupon_id: couponId })
            }).catch(function() {});
        }, 0);
    }

    // Handle Get Deal button - Open popup and redirect to store
    if (affiliate && affiliate !== '#' && affiliate !== '{{ url("/") }}') {
        var currentUrl = window.location.href.split('#')[0].split('?')[0];
        var popupUrl = currentUrl + '?show_coupon=1&code=&affiliate=' + encodeURIComponent(affiliate) + '&store=' + encodeURIComponent(store) + '&title=' + encodeURIComponent(title);
        // Defer window.open() and navigation to allow bfcache restoration
        setTimeout(function() {
            var popup = window.open(popupUrl, '_blank', 'noopener,noreferrer');
            // If popup blocked, use modal instead
            if (!popup || popup.closed || typeof popup.closed === 'undefined') {
                if (window.openModal) {
                    window.openModal('', affiliate, store, title);
                }
            }
        }, 0);
        // Defer navigation to allow bfcache
        setTimeout(function() {
            window.location.href = affiliate;
        }, 0);
    }
}
</script>

<script defer>
// Combined DOMContentLoaded handler - Deferred for TBT reduction
// Modal initialization deferred but button handlers work immediately above
(function() {
    'use strict';
    let initComplete = false;

    function initPage() {
        if (initComplete) return;
        initComplete = true;

        // Coupon Modal - Initialize modal functions
        const modal = document.getElementById('couponModal');
        if (modal) {
            const overlay = modal.querySelector('.cm-overlay');
            const closeBtn = modal.querySelector('.cm-close');
            const cmCode = document.getElementById('cmCode');
            const cmCopy = document.getElementById('cmCopy');
            const cmContinue = document.getElementById('cmContinue');
            const cmTitle = document.getElementById('cmTitle');
            const cmNote = document.getElementById('cmNote');
            const cmEmailTitle = document.getElementById('cmEmailTitle');
            const cmBrandLogo = document.getElementById('cmBrandLogo');
            const cmBrandText = document.getElementById('cmBrandText');

            function openModal(code, affiliate, store, title) {
                if (cmCode) cmCode.textContent = code || '';
                if (cmTitle) cmTitle.textContent = title || 'Here is your code';
                if (cmEmailTitle) cmEmailTitle.textContent = `Get More ${store} Deals!`;

                // Store affiliate URL for redirect button
                window.currentAffiliateUrl = affiliate;

                if (cmBrandLogo && cmBrandText) {
                    if (store && store !== 'Store') {
                        cmBrandText.textContent = store.substring(0,5).toUpperCase();
                    } else {
                        cmBrandText.textContent = 'STORE';
                    }
                }

                // Hide Copy Code button if "No code required" is displayed
                if (cmCopy && cmCode) {
                    if (code === 'No code required' || code === '' || !code) {
                        cmCopy.style.display = 'none';
                    } else {
                        cmCopy.style.display = 'block';
                    }
                }

                modal.style.display = 'block';
                modal.setAttribute('aria-hidden','false');
                document.body.style.overflow = 'hidden';
            }

            function closeModal() {
                modal.style.display = 'none';
                modal.setAttribute('aria-hidden', 'true');
                document.body.style.overflow = '';
            }

            // Make openModal/closeModal globally accessible for button handlers
            window.openModal = openModal;
            window.closeModal = closeModal;

  // Copy button
  if (cmCopy) {
    cmCopy.addEventListener('click', function() {
      const code = cmCode ? cmCode.textContent : '';
      if (code) {
        navigator.clipboard.writeText(code).then(function() {
          const originalText = cmCopy.textContent;
          cmCopy.textContent = 'Copied!';
          cmCopy.style.backgroundColor = '#218838';

          setTimeout(function() {
            cmCopy.textContent = originalText;
            cmCopy.style.backgroundColor = '#28a745';
          }, 2000);
        }).catch(function(err) {
          console.error('Could not copy text: ', err);
          alert('Coupon Code: ' + code);
        });
      }
    });
  }

  // Redirect button
  const cmRedirect = document.getElementById('cmRedirect');
  if (cmRedirect) {
    cmRedirect.addEventListener('click', function() {
      // Get the affiliate URL from the current modal context
      const currentAffiliate = window.currentAffiliateUrl || '#';
      if (currentAffiliate && currentAffiliate !== '#') {
        window.open(currentAffiliate, '_blank');
      }
    });
  }

  // Email form
  const emailForm = document.getElementById('cmEmailForm');
  if (emailForm) {
    emailForm.addEventListener('submit', function(e) {
      e.preventDefault();
      const email = this.querySelector('input[type="email"]').value;
      if (email) {
        // Here you can add AJAX call to subscribe
        alert('Thank you for subscribing!');
        closeModal();
      }
    });
  }

    // Feedback buttons
    document.querySelectorAll('.cm-feedback-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            const feedback = this.dataset.feedback;

            // Remove active state from all buttons
            document.querySelectorAll('.cm-feedback-btn').forEach(b => {
                b.style.background = 'transparent';
                b.style.borderColor = '#d1d5db';
            });

            // Add active state to clicked button
            this.style.background = feedback === 'positive' ? '#f0fdf4' : '#fef2f2';
            this.style.borderColor = feedback === 'positive' ? '#10b981' : '#ef4444';

            // Here you can send feedback to your backend
        });
    });

    // More details toggle
    const moreBtn = document.querySelector('.cm-more-btn');
    if (moreBtn) {
        moreBtn.addEventListener('click', function () {
            const chevron = this.querySelector('.cm-chevron');
            chevron.style.transform = chevron.style.transform === 'rotate(180deg)'
                ? 'rotate(0deg)'
                : 'rotate(180deg)';

            // Here you can toggle additional details
        });
    }

    // Close modal events
    closeBtn.addEventListener('click', closeModal);
    overlay.addEventListener('click', closeModal);
    document.addEventListener('keydown', (ev) => {
        if (ev.key === 'Escape') closeModal();
    });

  // show modal if params present
  try {
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('show_coupon') === '1') {
      const code = urlParams.get('code') || '';
      const affiliate = urlParams.get('affiliate') || '#';
      const store = urlParams.get('store') || 'Store';
      const title = urlParams.get('title') || 'Here is your code';

      openModal(code, affiliate, store, title);

      // If no code, show "No code required" message
      if (!code) {
        if (cmCode) cmCode.textContent = 'No code required';
        if (cmCopy) {
          cmCopy.disabled = true;
          cmCopy.style.opacity = '0.6';
          cmCopy.style.cursor = 'not-allowed';
        }
      }

      history.replaceState({}, '', window.location.pathname);
    }
  } catch (e) {
    // URL params not supported
  }
        }

        // Defer non-critical initialization
        if ('requestIdleCallback' in window) {
            requestIdleCallback(function() {
                initNonCritical();
            }, { timeout: 2000 });
        } else {
            setTimeout(initNonCritical, 100);
        }
    }

    function initNonCritical() {
        // Non-critical features can be initialized here
    }

    // Use single DOMContentLoaded listener
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initPage);
    } else {
        initPage();
  }
})();
</script>

<!-- Modern Home Page Styles -->
<style>
@media (max-width: 1200px) {
    .category-item{
        width: calc((100% - (4 * 1.5rem)) / 4) !important
    }
}
/* Mobile Responsive */
@media (max-width: 992px) {
    .category-item{
        width: calc((100% - (3 * 1.5rem)) / 3) !important
    }
}

/* Modern Hero Section */
.modern-hero {
    position: relative;
    min-height: 600px;
    display: flex;
    align-items: center;
    overflow: hidden;

}

.hero-background {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 1;
}

.hero-banner-image {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 0;
}

.banner-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: center;
}

.hero-content {
    position: relative;
    z-index: 3;
    width: 100%;
}

.container {
    max-width: 1280px;
    margin: 0 auto;
}

.hero-text {
    text-align: center;
    color: white;
}

.hero-title {
    font-size: 3.5rem;
    font-weight: 800;
    margin-bottom: 1.5rem;
    line-height: 1.2;
}

.gradient-text {
    background: var(--primary-color);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.highlight {
    color: var(--primary-color);
}

.hero-subtitle {
    font-size: 1.25rem;
    margin-bottom: 2rem;
    opacity: 0.9;
    max-width: 600px;
    margin-left: auto;
    margin-right: auto;
}

.search-container {
    margin-bottom: 3rem;
}

.search-box {
    position: relative;
    max-width: 600px;
    margin: 0 auto;
}

.search-input {
    width: 100%;
    padding: 1rem 1.5rem;
    font-size: 1.1rem;
    border: none;
    border-radius: 50px;
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(10px);
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
    outline: none;
    transition: all 0.3s ease;
}

.search-input:focus {
    transform: translateY(-2px);
    box-shadow: 0 15px 40px rgba(0, 0, 0, 0.3);
}

.search-btn {
    position: absolute;
    right: 8px;
    top: 50%;
    transform: translateY(-50%);
    background: var(--primary-color);
    border: none;
    border-radius: 50%;
    width: 45px;
    height: 45px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    cursor: pointer;
    transition: all 0.3s ease;
}

.search-btn:hover {
    transform: translateY(-50%) scale(1.1);
}

/* Search Modal Styles */
.search-modal {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 9999;
    display: flex;
    align-items: center;
    justify-content: center;
}

.search-modal-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    backdrop-filter: blur(5px);
}

.search-modal-content {
    position: relative;
    background: white;
    border-radius: 15px;
    width: 90%;
    max-width: 800px;
    max-height: 80vh;
    overflow: hidden;
    box-shadow: 0 25px 50px rgba(0, 0, 0, 0.3);
    animation: modalSlideIn 0.3s ease-out;
}

@keyframes modalSlideIn {
    from {
        opacity: 0;
        transform: translateY(-50px) scale(0.9);
    }
    to {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
}


.offers-list,
.brands-list {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.offer-item {
    display: flex;
    align-items: center;
    padding: 15px;
    border-radius: 10px;
    background: #f8f9fa;
    transition: all 0.3s ease;
    cursor: pointer;
    text-decoration: none;
    color: inherit;
}

.offer-item:hover {
    background: #e9ecef;
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
}

.offer-logo {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    object-fit: cover;
    margin-right: 15px;
    flex-shrink: 0;
}

.offer-logo-placeholder {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    background: var(--primary-color);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    font-size: 12px;
    margin-right: 15px;
    flex-shrink: 0;
}

.offer-content {
    flex: 1;
}

.offer-brand {
    font-weight: 600;
    color: #333;
    margin: 0 0 5px 0;
    font-size: 14px;
}

.offer-description {
    color: #666;
    font-size: 13px;
    margin: 0 0 8px 0;
}

.offer-button {
    background: var(--primary-color);
    color: white;
    border: none;
    padding: 6px 12px;
    border-radius: 15px;
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
    cursor: pointer;
    transition: all 0.3s ease;
}

.offer-button:hover {
    background: #1e3a8a;
    transform: scale(1.05);
}

.brand-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 12px 0;
    border-bottom: 1px solid #f0f0f0;
    cursor: pointer;
    text-decoration: none;
    color: inherit;
    transition: color 0.3s ease;
}

.brand-item:hover {
    color: var(--primary-color);
}

.brand-item:last-child {
    border-bottom: none;
}

.brand-name {
    font-weight: 600;
    color: var(--primary-color);
    font-size: 14px;
}

.brand-offers {
    color: #666;
    font-size: 12px;
}

.loading-state {
    text-align: center;
    padding: 40px;
    color: #666;
}

.loading-spinner {
    display: inline-block;
    width: 20px;
    height: 20px;
    border: 2px solid #f3f3f3;
    border-top: 2px solid var(--primary-color);
    border-radius: 50%;
    animation: spin 1s linear infinite;
    margin-right: 10px;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}



.hero-stats {
    display: flex;
    justify-content: center;
    gap: 3rem;
    flex-wrap: wrap;
}

.stat-item {
    text-align: center;
}

.stat-number {
    font-size: 2.5rem;
    font-weight: 800;
    color: var(--primary-color);
    margin-bottom: 0.5rem;
}

.stat-label {
    font-size: 1rem;
    opacity: 0.8;
}

/* Featured Stores Section */
.featured-stores-section {
    padding: 4rem 0;
    background: #ffffff;
    position: relative;
}

.featured-stores-section .container {
    max-width: 1280px;
    margin: 0 auto;
    padding: 0 24px;
}

.featured-stores-section .section-header {
    text-align: center;
    margin-bottom: 2.5rem;
}

.featured-stores-section .section-title {
    font-size: 2rem;
    font-weight: 700;
    margin: 0 0 0.5rem 0;
    line-height: 1.2;
}

.featured-stores-section .section-subtitle {
    font-size: 1rem;
    color: #666666;
    margin: 0;
}

.featured-stores-grid {
    display: grid;
    grid-template-columns: repeat(6, 1fr);
    gap: 1.25rem;
    margin-top: 2.5rem;
}

.featured-store-card {
    background: #ffffff;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    overflow: hidden;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    display: flex;
    flex-direction: column;
    height: 100%;
}

.featured-store-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 4px 16px rgba(0, 0, 0, 0.15);
}

.featured-store-link {
    display: block;
    text-decoration: none;
    color: inherit;
}

.featured-store-logo-area {
    width: 100%;
    height: 140px;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 1.25rem;
    background: #ffffff;
    border-bottom: 1px solid #f0f0f0;
}

.featured-store-logo {
    max-width: 100%;
    max-height: 100%;
    object-fit: contain;
}

.featured-store-name {
    padding: 0.875rem 1rem;
    font-weight: 500;
    font-size: 0.875rem;
    text-align: center;
    color: #374151;
    line-height: 1.4;
    flex: 1;
    display: flex;
    align-items: center;
    justify-content: center;
}

.featured-store-offers-btn {
    padding: 0.75rem 1rem;
    background: var(--primary-color);
    background-color: var(--primary-color);
    color: #ffffff;
    text-align: center;
    font-size: 0.875rem;
    font-weight: 600;
    text-transform: none;
    border-radius: 0 0 8px 8px;
    transition: background-color 0.3s ease;
    line-height: 1.3;
}

.featured-store-card:hover .featured-store-offers-btn {
    background-color: var(--primary-color);
}

@media (max-width: 1200px) {
    .featured-stores-grid {
        grid-template-columns: repeat(5, 1fr);
    }
}

@media (max-width: 1024px) {
    .featured-stores-grid {
        grid-template-columns: repeat(4, 1fr);
        gap: 1rem;
    }
}

@media (max-width: 768px) {
    .featured-stores-grid {
        grid-template-columns: repeat(3, 1fr);
        gap: 1rem;
    }

    .featured-store-logo-area {
        height: 120px;
        padding: 1rem;
    }

    .featured-store-name {
        padding: 0.75rem;
        font-size: 0.8125rem;
    }

    .featured-store-offers-btn {
        padding: 0.625rem 0.75rem;
        font-size: 0.8125rem;
    }
}

@media (max-width: 480px) {
    .featured-stores-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 0.875rem;
    }

    .featured-store-logo-area {
        height: 100px;
        padding: 0.75rem;
    }

    .featured-store-logo-placeholder {
        width: 80px;
        height: 80px;
        font-size: 1.25rem;
    }

    .featured-store-name {
        padding: 0.625rem 0.5rem;
        font-size: 0.75rem;
    }

    .featured-store-offers-btn {
        padding: 0.5rem;
        font-size: 0.75rem;
    }
}

/* Hide utility classes */
.hide-on-desktop {
    display: block;
}

@media (min-width: 769px) {
    .hide-on-desktop {
        display: none !important;
    }
}

/* Featured Brands Section */
.featured-brands-section {
    padding: 4rem 0;
    background: var(--background-primary-color, #ffffff);
}

.featured-brands-section .section-header {
    text-align: center;
    margin-bottom: 2.5rem;
}

.featured-brands-section .section-title {
    font-size: 2rem;
    font-weight: 700;
    color: var(--text-color, #333333) !important;
    margin-bottom: 0.5rem;
}

/* Ensure title-highlight has sufficient contrast */
.title-highlight {
    /* Use darker shade of primary color for better contrast - WCAG AA compliant */
    color: var(--primary-hover, #2951c4) !important;
    /* Fallback: darken primary color if hover not available */
    color: var(--primary-hover, color-mix(in srgb, var(--primary-color, #2951c4) 80%, #000)) !important;
    font-weight: 700;
    /* Add subtle text shadow for better readability */
    text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
}

.featured-brands-section .section-subtitle {
    font-size: 1rem;
    color: var(--text-color, #666666) !important;
    max-width: 500px;
    margin: 0 auto;
}

.brands-carousel {
    margin-bottom: 3rem;
    position: relative;
}

.carousel-container {
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
    width: 100%;
    overflow: hidden;
}

.brands-slider {
    display: flex;
    gap: 2rem;
    overflow: visible;
    padding: 1rem 0;
    width: 100%;
    max-width: 1000px;
    transition: transform 0.5s ease;
    justify-content: center;
    align-items: center;
}

.carousel-btn {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    background: var(--primary-color);
    color: white;
    border: none;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    font-size: 1.5rem;
    font-weight: bold;
    cursor: pointer;
    transition: all 0.3s ease;
    z-index: 2;
    display: flex;
    align-items: center;
    justify-content: center;
}

.carousel-btn:hover {
    background: var(--secondary-color, #1e3a8a);
    transform: translateY(-50%) scale(1.1);
}

.prev-btn {
    left: -20px;
}

.next-btn {
    right: -20px;
}

.brand-item {
    flex: 0 0 120px;
    text-align: center;
    margin: 0 1rem;
}

.brand-link {
    display: block;
    text-decoration: none;
    color: inherit;
    transition: all 0.3s ease;
}

.brand-link:hover {
    transform: translateY(-5px);
}

.brand-logo-circle {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    background: var(--background-primary-color, #ffffff);
    border: 3px solid var(--background-secondary-color, #f0f0f0);
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto;
    transition: all 0.3s ease;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    overflow: hidden;
}

.brand-link:hover .brand-logo-circle {
    border-color: var(--primary-color);
    box-shadow: 0 8px 30px rgba(41, 81, 196, 0.2);
    transform: scale(1.05);
}

.brand-logo {
    max-width: 60px;
    max-height: 60px;
    object-fit: contain;
    transition: all 0.3s ease;
}

.brand-link:hover .brand-logo {
    transform: scale(1.1);
}

.brand-placeholder {
    width: 60px;
    height: 60px;
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color, #000000));
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
    overflow: hidden;
}

.brand-placeholder::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="brand-pattern" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="rgba(255,255,255,0.1)"/><circle cx="75" cy="75" r="1" fill="rgba(255,255,255,0.1)"/><circle cx="50" cy="10" r="0.5" fill="rgba(255,255,255,0.1)"/><circle cx="10" cy="60" r="0.5" fill="rgba(255,255,255,0.1)"/><circle cx="90" cy="40" r="0.5" fill="rgba(255,255,255,0.1)"/></pattern></defs><rect width="100" height="100" fill="url(%23brand-pattern)"/></svg>');
    opacity: 0.3;
}

.brand-initials {
    font-size: 1.5rem;
    font-weight: 800;
    color: white;
    text-transform: uppercase;
    letter-spacing: 1px;
    position: relative;
    z-index: 1;
}

.carousel-dots {
    display: flex;
    justify-content: center;
    gap: 0.5rem;
    margin-top: 2rem;
}

.dot {
    width: 12px;
    height: 12px;
    border-radius: 50%;
    background: #e0e0e0;
    cursor: pointer;
    transition: all 0.3s ease;
}

.dot.active {
    background: var(--primary-color);
    transform: scale(1.2);
}

.dot:hover {
    background: var(--primary-color);
    opacity: 0.7;
}

.no-brands {
    text-align: center;
    padding: 3rem;
    color: var(--text-color, #666);
}

.no-brands-icon {
    font-size: 3rem;
    margin-bottom: 1rem;
}

.no-brands h3 {
    font-size: 1.5rem;
    margin-bottom: 0.5rem;
    color: var(--text-color, #333);
}

.view-all-brands {
    text-align: center;
}

.btn-outline {
    display: inline-block;
    padding: 0.75rem 2rem;
    border: 2px solid var(--primary-color);
    color: var(--primary-color);
    text-decoration: none;
    border-radius: 25px;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-outline:hover {
    background: var(--primary-color);
    color: white;
    transform: translateY(-2px);
}

/* Professional Hot Deals Section - Google AdSense Ready */
.hot-deals-section {
    padding: 4rem 0;
    background: var(--bg-primary);
}

.section-header {
    text-align: center;
    margin-bottom: 3rem;
}

.section-title {
    color: var(--text-color) !important;
    font-size: 2.5rem;
    font-weight: 800;
    text-align: center;
    margin-bottom: 0.75rem;
    letter-spacing: -0.02em;
    line-height: 1.2;
}

.section-subtitle {
    color: var(--text-light) !important;
    text-align: center;
    margin-bottom: 2rem;
    font-size: 1.125rem;
    line-height: 1.6;
    margin-left: auto;
    margin-right: auto;
}

.view-all-link {
    color: #ffffff;
    text-decoration: none;
    font-weight: 600;
    transition: all 0.3s ease;
    text-align: center;
    display: inline-block;
    margin-bottom: 2rem;
    font-size: 1rem;
    padding: 0.625rem 1.5rem;
    border-radius: 8px;
    background: var(--primary-color);
    box-shadow: 0 2px 8px rgba(41, 81, 196, 0.2);
}

.view-all-link:hover {
    color: #ffffff;
    background: var(--primary-hover, #1e3a8a);
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(41, 81, 196, 0.3);
}

/* Hot Deals / Exclusive Offers Section */
.hot-deals-section {
    padding: 5rem 0;
    background: #f8fafc;
    position: relative;
}

.hot-deals-section .container {
    max-width: 1280px;
    margin: 0 auto;
    padding: 0 24px;
}

.hot-deals-section .section-header {
    text-align: center;
    margin-bottom: 3rem;
}

.hot-deals-section .section-title {
    font-size: 2.5rem;
    font-weight: 700;
    margin: 0 0 0.75rem 0;
    line-height: 1.2;
    color: #1a1a1a;
}

.hot-deals-section .title-highlight {
    color: var(--primary-color, #2951c4);
}

.hot-deals-section .section-subtitle {
    font-size: 1.125rem;
    color: #6b7280;
    margin: 0 0 1.5rem 0;
    font-weight: 400;
}

.hot-deals-section .view-all-link {
    display: inline-block;
    padding: 0.75rem 2rem;
    background: var(--primary-color, #2951c4);
    color: #ffffff;
    text-decoration: none;
    border-radius: 8px;
    font-weight: 600;
    font-size: 1rem;
    transition: all 0.3s ease;
    box-shadow: 0 2px 8px rgba(220, 38, 38, 0.2);
}

.hot-deals-section .view-all-link:hover {
    background: var(--primary-hover, #2951c4);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(220, 38, 38, 0.3);
}

/* Exclusive Offers Grid - Attractive Design */
.exclusive-offers-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 1.5rem;
    margin-top: 2.5rem;
}

.exclusive-offer-card {
    background: #ffffff;
    border-radius: 12px;
    box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
    overflow: hidden;
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    display: flex;
    flex-direction: column;
    height: 100%;
    border: 1px solid #f0f0f0;
}

.exclusive-offer-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
    border-color: #e0e0e0;
}

.exclusive-offer-link {
    display: flex;
    flex-direction: column;
    text-decoration: none;
    color: inherit;
    height: 100%;
}

/* Discount Badge Circle */
.discount-badge-circle {
    position: absolute;
    top: 10px;
    left: 10px;
    z-index: 10;
    width: 50px;
    height: 50px;
    border-radius: 50%;
    background: var(--primary-color);
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 2px 8px rgba(251, 191, 36, 0.4);
    border: 2px solid #ffffff;
}

.discount-percent {
    font-size: 0.75rem;
    font-weight: 800;
    color: #fff;
    line-height: 1;
    text-align: center;
}

/* Brand Logo Area */
.offer-logo-area {
    width: 100%;
    height: 160px;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 0;
    position: relative;
    transition: all 0.3s ease;
}

.exclusive-offer-card:hover .offer-logo-area {
    transform: scale(1.02);
}

.offer-brand-logo {
    max-width: 100%;
    max-height: 100%;
    object-fit: cover;
    width: 100%;
    height: 100%;
    transition: transform 0.3s ease;
}

.exclusive-offer-card:hover .offer-brand-logo {
    transform: scale(1.05);
}

.offer-logo-placeholder {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #ffffff;
    font-size: 2rem;
    font-weight: 700;
    letter-spacing: 2px;
}

/* Brand Name */
.offer-brand-name {
    padding: 1rem 1rem 0.5rem;
    font-size: 0.8125rem;
    font-weight: 700;
    text-transform: uppercase;
    color: #1f2937;
    letter-spacing: 0.8px;
    text-align: center;
    line-height: 1.3;
}

/* Offer Description */
.offer-description {
    padding: 0 1rem;
    font-size: 0.875rem;
    font-weight: 400;
    color: #4b5563;
    line-height: 1.6;
    text-align: center;
    flex: 1;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    margin-bottom: 1rem;
    min-height: 2.8em;
}

/* Coupon Code Button */
.offer-coupon-btn {
    margin: 0 1rem 1rem;
    padding: 0.625rem 1.25rem;
    background: var(--primary-color);
    color: #fff;
    border: 1px solid #e5e7eb;
    border-radius: 6px;
    font-size: 0.8125rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    text-align: center;
    width: calc(100% - 2rem);
}

.offer-coupon-btn:hover {
    background: var(--primary-hover);
    border-color: var(--primary-hover);
    transform: translateY(-1px);
    box-shadow: 0 2px 4px rgba(var(--primary-hover), 0.1);
}

/* Get Deal Button */
.offer-get-deal-btn {
    margin: 0 1rem 1rem;
    padding: 0.625rem 1.25rem;
    background: var(--primary-color, #2951c4);
    color: #ffffff;
    border: 1px solid var(--primary-color, #2951c4);
    border-radius: 6px;
    font-size: 0.8125rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    text-align: center;
    width: calc(100% - 2rem);
}

.offer-get-deal-btn:hover {
    background: var(--primary-hover, #2951c4);
    border-color: var(--primary-hover, #2951c4);
    transform: translateY(-1px);
    box-shadow: 0 2px 8px rgba(220, 38, 38, 0.3);
}

/* Sponsored Label */
/* .offer-sponsored {
    padding: 0.625rem 1rem;
    font-size: 0.6875rem;
    font-weight: 500;
    color: #9ca3af;
    text-align: center;
    text-transform: uppercase;
    letter-spacing: 0.8px;
    border-top: 1px solid #f3f4f6;
    background: #fafafa;
} */

/* Responsive Design */
@media (max-width: 1200px) {
    .exclusive-offers-grid {
        grid-template-columns: repeat(4, 1fr);
        gap: 1.25rem;
    }
}

@media (max-width: 992px) {
    .exclusive-offers-grid {
        grid-template-columns: repeat(3, 1fr);
        gap: 1.25rem;
    }
}

@media (max-width: 768px) {
    .exclusive-offers-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 1rem;
    }

    .offer-logo-area {
        height: 140px;
    }

    .offer-brand-name {
        font-size: 0.75rem;
        padding: 0.875rem 1rem 0.5rem;
    }

    .offer-description {
        font-size: 0.8125rem;
        padding: 0 1rem;
        margin-bottom: 0.875rem;
    }

    .offer-coupon-btn,
    .offer-get-deal-btn {
        margin: 0 1rem 0.875rem;
        padding: 0.5625rem 1rem;
        font-size: 0.75rem;
    }

    /* .offer-sponsored {
        padding: 0.5rem 1rem;
        font-size: 0.625rem;
    } */

    .discount-badge-circle {
        width: 44px;
        height: 44px;
        top: 8px;
        left: 8px;
    }

    .discount-percent {
        font-size: 0.6875rem;
    }
}

@media (max-width: 480px) {
    .exclusive-offers-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 0.875rem;
    }

    .offer-logo-area {
        height: 120px;
    }

    .offer-logo-placeholder {
        font-size: 1.5rem;
    }

    .discount-badge-circle {
        width: 40px;
        height: 40px;
        top: 6px;
        left: 6px;
    }

    .discount-percent {
        font-size: 0.625rem;
    }

    .offer-brand-name {
        font-size: 0.6875rem;
        padding: 0.75rem 0.75rem 0.5rem;
    }

    .offer-description {
        font-size: 0.75rem;
        padding: 0 0.75rem;
    }

    .offer-coupon-btn,
    .offer-get-deal-btn {
        margin: 0 0.75rem 0.75rem;
        padding: 0.5rem;
        font-size: 0.6875rem;
    }
}

/* Hide utility classes */
.hide-on-desktop {
    display: block;
}

@media (min-width: 769px) {
    .hide-on-desktop {
        display: none !important;
    }
}

.deals-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 1.5rem;
    margin-top: 2rem;
    contain: layout style;
}

.deal-card {
    background: var(--bg-primary);
    border-radius: 16px;
    overflow: hidden;
    box-shadow: var(--shadow-md);
    border: 1px solid var(--border-color);
    transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1), box-shadow 0.3s cubic-bezier(0.4, 0, 0.2, 1), border-color 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    height: 100%;
    display: flex;
    flex-direction: column;
    contain: layout style paint;
}

.deal-card:hover {
    transform: translateY(-4px);
    box-shadow: var(--shadow-xl);
    border-color: var(--primary-color);
}

/* Professional Deal Image Section */
.deal-image-section {
    position: relative;
    height: 180px;
    overflow: hidden;
    background: linear-gradient(135deg, var(--bg-secondary) 0%, #e5e7eb 100%);
}

.deal-cover-image {
    width: 100%;
    height: 100%;
    max-width: 100%;
    object-fit: cover;
    transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1);
}

.deal-card:hover .deal-cover-image {
    transform: scale(1.08);
}

.deal-placeholder-image {
    width: 100%;
    height: 100%;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, var(--bg-secondary) 0%, #e5e7eb 100%);
    color: var(--text-light);
}

.placeholder-icon {
    display: none !important;
}

.deal-placeholder-image span {
    font-size: 0.875rem;
    font-weight: 600;
    text-align: center;
    color: var(--text-color);
}

/* Professional Discount Badge */
.discount-badge {
    position: absolute;
    top: 12px;
    right: 12px;
    /* Use darker shade for better contrast with white text - WCAG AA compliant */
    background: var(--primary-hover, #2951c4);
    background-color: var(--primary-hover, #2951c4);
    /* Fallback: if primary-hover not available, darken primary color */
    background-color: var(--primary-hover, color-mix(in srgb, var(--primary-color, #2951c4) 85%, #000));
    color: #ffffff;
    padding: 0.375rem 0.75rem;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 700;
    box-shadow: var(--shadow-lg);
    text-transform: uppercase;
    letter-spacing: 0.5px;
    /* Ensure minimum 4.5:1 contrast ratio */
    text-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);
}

/* Professional Exclusive Badge */
.exclusive-badge {
    position: absolute;
    top: 12px;
    left: 12px;
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    color: white;
    padding: 0.375rem 0.625rem;
    border-radius: 20px;
    font-size: 0.65rem;
    font-weight: 700;
    display: flex;
    align-items: center;
    gap: 0.25rem;
    box-shadow: var(--shadow-lg);
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.exclusive-icon {
    display: none !important;
    font-size: 0.7rem;
}

/* Professional Deal Content Wrapper */
.deal-content-wrapper {
    padding: 1.25rem;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    flex-grow: 1;
}

.deal-header {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    margin-bottom: 1rem;
}

.deal-header {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    align-items: center;
    gap: 0.625rem;
    flex: 1;
}

.store-logo {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid var(--border-color);
    box-shadow: var(--shadow-sm);
    flex-shrink: 0;
}

.store-details {
    min-width: 0;
    flex: 1;
}

.store-details h3 {
    font-size: 0.95rem;
    font-weight: 700;
    color: var(--text-color);
    margin: 0 0 0.25rem 0;
    line-height: 1.3;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: wrap;
}

.verified-badge {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    color: white;
    padding: 0.125rem 0.5rem;
    border-radius: 12px;
    font-size: 0.65rem;
    font-weight: 700;
    display: inline-block;
    box-shadow: var(--shadow-sm);
    text-transform: uppercase;
    letter-spacing: 0.3px;
}

.type-badge {
    padding: 0.375rem 0.625rem;
    border-radius: 12px;
    font-size: 0.65rem;
    font-weight: 700;
    display: flex;
    align-items: center;
    gap: 0.25rem;
    box-shadow: var(--shadow-sm);
    text-transform: uppercase;
    letter-spacing: 0.5px;
    flex-shrink: 0;
}

.type-badge.code {
    /* Use darker shade for better contrast with white text - WCAG AA compliant */
    background: var(--primary-hover, #2951c4);
    background-color: var(--primary-hover, #2951c4);
    /* Fallback: if primary-hover not available, darken primary color */
    background-color: var(--primary-hover, color-mix(in srgb, var(--primary-color, #2951c4) 85%, #000));
    color: #ffffff;
    /* Ensure minimum 4.5:1 contrast ratio */
    text-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);
}

.type-badge.deal {
    /* Use darker shade for better contrast with white text - WCAG AA compliant */
    background: var(--primary-hover, #2951c4);
    background-color: var(--primary-hover, #2951c4);
    /* Fallback: if primary-hover not available, darken primary color */
    background-color: var(--primary-hover, color-mix(in srgb, var(--primary-color, #2951c4) 85%, #000));
    color: #ffffff;
    /* Ensure minimum 4.5:1 contrast ratio */
    text-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);
}

.badge-icon {
    display: none !important;
    font-size: 0.7rem;
}

.deal-content {
    margin-bottom: 1rem;
    flex-grow: 1;
}

.deal-title {
    font-size: 1rem;
    font-weight: 600;
    color: var(--text-color);
    margin-bottom: 0.5rem;
    line-height: 1.4;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.deal-description {
    font-size: 0.8125rem;
    color: var(--text-light);
    margin-bottom: 0.75rem;
    line-height: 1.5;
}

.deal-meta {
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap;
    margin-top: 0.5rem;
}

.meta-item {
    display: flex;
    align-items: center;
    gap: 0.25rem;
    font-size: 0.6875rem;
    color: var(--text-light);
    background: var(--bg-secondary);
    padding: 0.375rem 0.625rem;
    border-radius: 12px;
    border: 1px solid var(--border-color);
    font-weight: 500;
}

.meta-icon {
    font-size: 0.75rem;
}

.deal-footer {
    display: flex;
    gap: 0.5rem;
    align-items: center;
    border-top: 1px solid var(--border-color);
}

.terms-btn {
    background: transparent;
    border: 1px solid var(--border-color);
    color: var(--text-light);
    cursor: pointer;
    font-size: 0.6875rem;
    padding: 0.375rem 0.625rem;
    border-radius: 8px;
    display: flex;
    align-items: center;
    gap: 0.25rem;
    transition: all 0.3s ease;
    font-weight: 500;
}

.terms-btn:hover {
    background: var(--bg-secondary);
    color: var(--text-color);
    border-color: var(--text-light);
}

.terms-icon {
    font-size: 0.875rem;
}

.deal-btn {
    flex: 1;
    padding: 0.75rem 1rem;
    border: none;
    border-radius: 12px;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    font-size: 0.875rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    box-shadow: var(--shadow-sm);
    position: relative;
}

.deal-btn.reveal-code {
    background: linear-gradient(135deg, var(--primary-hover) 0%, #000 100%);
    color: white;
}

.deal-btn.reveal-code::before {
    content: '🔓';
    font-size: 0.875rem;
    margin-right: 0.25rem;
}

.deal-btn.get-deal {
    background: linear-gradient(135deg, var(--primary-hover) 0%, #000 100%);
    color: white;
}

.deal-btn.get-deal::before {
    content: '🎯';
    font-size: 0.875rem;
    margin-right: 0.25rem;
}

.deal-btn::after {
    content: '→';
    font-size: 1.125rem;
    transition: transform 0.3s ease;
    line-height: 1;
    margin-left: 0.25rem;
}

.deal-btn:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-md);
}

.deal-btn:hover::after {
    transform: translateX(4px);
}

.deal-btn.reveal-code:hover {
    background: linear-gradient(135deg, var(--primary-hover) 0%, #000 100%);
}

.deal-btn.get-deal:hover {
    background: linear-gradient(135deg, var(--primary-hover) 0%, #000 100%);
}

.no-deals {
    text-align: center;
    padding: 4rem 2rem;
    color: var(--text-color, #64748b);
    grid-column: 1 / -1;
    background: var(--background-primary-color, white);
    border-radius: 20px;
    border: 2px dashed var(--background-secondary-color, #e2e8f0);
}

.no-deals-icon {
    font-size: 4rem;
    margin-bottom: 1rem;
}

.no-deals h3 {
    font-size: 1.5rem;
    font-weight: 600;
    color: var(--text-color, #374151);
    margin-bottom: 0.5rem;
}

/* Blog Section - Clean AdSense-Friendly Design */
/* Blog Section - Professional Design */
.blog-section {
    padding: 5rem 0;
    background: #ffffff;
    position: relative;
}

.blog-section .container {
    max-width: 1280px;
    margin: 0 auto;
    padding: 0 24px;
}

.blog-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 2.5rem;
    margin-top: 3rem;
}

.blog-card {
    background: #ffffff;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    border: 1px solid #f0f0f0;
    display: flex;
    flex-direction: column;
    height: 100%;
    position: relative;
}

.blog-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
    border-color: #e0e0e0;
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
    height: 260px;
    overflow: hidden;
    background: #f5f5f5;
}

.blog-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.5s cubic-bezier(0.4, 0, 0.2, 1);
    display: block;
}

.blog-card:hover .blog-image {
    transform: scale(1.06);
}

.blog-placeholder {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #f5f5f5 0%, #e8e8e8 100%);
}

.placeholder-icon {
    font-size: 3rem;
    opacity: 0.4;
}

.blog-category-badge {
    position: absolute;
    top: 12px;
    left: 12px;
    background: var(--primary-color, #2951c4);
    color: #ffffff;
    padding: 0.375rem 0.875rem;
    border-radius: 4px;
    font-size: 0.6875rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    z-index: 2;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
    line-height: 1.2;
}

.blog-content {
    padding: 1.5rem;
    display: flex;
    flex-direction: column;
    flex-grow: 1;
    background: #ffffff;
}

.blog-meta {
    display: flex;
    align-items: center;
    gap: 1.25rem;
    margin-bottom: 1rem;
    flex-wrap: wrap;
}

.blog-date,
.blog-views {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.8125rem;
    color: #6b7280 !important;
    font-weight: 400;
}

.blog-date svg,
.blog-views svg {
    width: 16px;
    height: 16px;
    flex-shrink: 0;
    color: #9ca3af;
}

.blog-title {
    font-size: 1.125rem;
    font-weight: 700;
    color: #1f2937;
    margin-bottom: 0.75rem;
    line-height: 1.5;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    min-height: 3.375rem;
    transition: color 0.3s ease;
}

.blog-card:hover .blog-title {
    color: var(--primary-color, #2951c4);
}

.blog-excerpt {
    font-size: 0.875rem;
    color: #6b7280 !important;
    line-height: 1.6;
    margin-bottom: 1.25rem;
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
    flex-grow: 1;
}

/* Ensure blog excerpt text inside blog-link has proper contrast */
a.blog-link .blog-excerpt,
a.blog-link div.blog-content p.blog-excerpt,
.blog-content .blog-excerpt {
    color: #6b7280 !important;
}

.blog-footer {
    margin-top: auto;
    padding-top: 0;
    border-top: none;
}

.read-more {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    color: #1f2937 !important;
    font-weight: 600;
    font-size: 0.875rem;
    transition: all 0.3s ease;
    cursor: pointer;
}

.read-more svg {
    transition: transform 0.3s ease;
    flex-shrink: 0;
    width: 16px;
    height: 16px;
}

.blog-card:hover .read-more {
    gap: 0.625rem;
    color: #1f2937 !important;
}

.blog-card:hover .read-more svg {
    transform: translateX(3px);
}

/* Ensure read-more inside blog-link has maximum contrast - override any inherited colors */
a.blog-link .read-more,
a.blog-link div.blog-content div.blog-footer span.read-more,
a.blog-link > div.blog-content > div.blog-footer > span.read-more,
span.read-more {
    color: #000000 !important;
}

.blog-card:hover a.blog-link .read-more,
.blog-card:hover a.blog-link div.blog-content div.blog-footer span.read-more,
.blog-card:hover a.blog-link > div.blog-content > div.blog-footer > span.read-more,
.blog-card:hover span.read-more {
    color: #000000 !important;
}

.read-more svg {
    width: 16px;
    height: 16px;
    transition: transform 0.3s ease;
    /* SVG stroke color must be pure black for maximum WCAG AA compliance */
    color: #000000 !important;
    stroke: #000000 !important;
    fill: none !important;
}

.blog-card:hover .read-more svg {
    color: #000000 !important;
    stroke: #000000 !important;
    transform: translateX(4px);
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
    padding: 0.875rem 2rem;
    background: var(--primary-color, #2951c4) !important;
    color: #ffffff !important;
    text-decoration: none;
    font-weight: 600;
    font-size: 1rem;
    border-radius: 8px;
    transition: all 0.3s ease;
    box-shadow: 0 2px 8px rgba(220, 38, 38, 0.2);
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
}

/* Ensure View All Blog Posts button has maximum contrast */
div.blog-view-all a.view-all-blog-btn,
a.view-all-blog-btn {
    background: #000000 !important;
    color: #ffffff !important;
}

.view-all-blog-btn:hover {
    background: var(--primary-hover, #2951c4) !important;
    color: #ffffff !important;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(220, 38, 38, 0.3);
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

/* Blog Section Responsive - Moved to mobile section below */
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

/* Categories Section - Circular Carousel Design */
.categories-section {
    padding: 4rem 0;
    background: #ffffff;
}

.categories-carousel-wrapper {
    position: relative;
    max-width: 100%;
    margin-top: 2.5rem;
    padding: 0 60px;
    overflow: hidden;
}

.categories-carousel {
    display: flex;
    gap: 1.5rem;
    overflow-x: auto;
    scroll-behavior: smooth;
    scrollbar-width: none;
    -ms-overflow-style: none;
    padding: 1rem 0;
    /* Show exactly 5 items initially */
    width: 100%;
    /* Enable scrolling but hide scrollbar */
    -webkit-overflow-scrolling: touch;
}

.categories-carousel::-webkit-scrollbar {
    display: none;
}

.category-item {
    flex: 0 0 auto;
    /* Calculate width to show exactly 5 items: (100% - 4 gaps) / 5 */
    width: calc((100% - (4 * 1.5rem)) / 5);
    min-width: 0;
    text-align: center;
    /* Prevent shrinking */
    flex-shrink: 0;
}

.category-link-item {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.75rem;
    text-decoration: none;
    color: inherit;
    transition: transform 0.3s ease;
}

.category-link-item:hover {
    transform: translateY(-4px);
}

.category-circle {
    width: 200px;
    height: 200px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
    overflow: hidden;
    background: #f3f4f6;
    transition: all 0.3s ease;
}

.category-link-item:hover .category-circle {
    transform: scale(1.05);
}

/* Pastel color backgrounds for categories */
/* .category-item:nth-child(1) .category-circle {
    background: #FFE5D9;
}

.category-item:nth-child(2) .category-circle {
    background: #E0F2E7;
}

.category-item:nth-child(3) .category-circle {
    background: #E0F2E7;
}

.category-item:nth-child(4) .category-circle {
    background: #FFE5D9;
}

.category-item:nth-child(5) .category-circle {
    background: #E0F2E7;
}

.category-item:nth-child(6) .category-circle {
    background: #E0F2E7;
}

.category-item:nth-child(7) .category-circle {
    background: #E0F2E7;
}

.category-item:nth-child(8) .category-circle {
    background: #FFE5D9;
} */

/* Alternating pattern */
/* .category-item:nth-child(odd) .category-circle {
    background: #FFE5D9;
}

.category-item:nth-child(even) .category-circle {
    background: #E0F2E7;
} */

.category-circle-icon {
    width: 140px;
    height: 140px;
    object-fit: contain;
    border-radius: 50%;
}

.category-circle-placeholder {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    background: transparent;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
    font-weight: 700;
    color: #374151;
}

.category-label {
    font-size: 1rem;
    font-weight: 600;
    color: #374151;
    text-align: center;
    line-height: 1.5;
}

.category-carousel-nav {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    width: 44px;
    height: 44px;
    border-radius: 50%;
    background: #f3f4f6;
    border: none;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    z-index: 10;
    transition: all 0.3s ease;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.category-carousel-nav:hover {
    background: #e5e7eb;
    transform: translateY(-50%) scale(1.05);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
}

.category-carousel-nav svg {
    width: 20px;
    height: 20px;
    color: #3b82f6;
}

.category-nav-prev {
    left: 0;
}

.category-nav-next {
    right: 0;
}

.no-categories {
    grid-column: 1 / -1;
    text-align: center;
    padding: 3rem 2rem;
}

.no-categories-icon {
    font-size: 3rem;
    margin-bottom: 1rem;
}

.no-categories h3 {
    font-size: 1.5rem;
    font-weight: 700;
    color: #1a1a1a;
    margin: 0 0 0.5rem 0;
}

.no-categories p {
    font-size: 1rem;
    color: #6b7280;
    margin: 0;
}

/* Responsive Design */
@media (max-width: 768px) {
    .categories-section {
        padding: 3rem 0;
    }

    .categories-carousel-wrapper {
        padding: 0 50px;
    }

    .categories-carousel {
        gap: 1rem;
    }

    .category-item {
        /* Show 3 items on mobile */
        width: calc((100% - (2 * 1rem)) / 3);
        min-width: 90px;
        max-width: 110px;
    }

    .category-circle {
        width: 80px;
        height: 80px;
    }

    .category-circle-icon {
        width: 64px;
        height: 64px;
    }

    .category-circle-placeholder {
        width: 64px;
        height: 64px;
        font-size: 1.5rem;
    }

    .category-label {
        font-size: 0.8125rem;
    }

    .category-carousel-nav {
        width: 36px;
        height: 36px;
    }

    .category-carousel-nav svg {
        width: 16px;
        height: 16px;
    }
}

@media (max-width: 480px) {
    .categories-carousel-wrapper {
        padding: 0 40px;
    }

    .categories-carousel {
        gap: 0.75rem;
    }

    .category-item {
        /* Show 2.5 items on small mobile (partial visibility indicates scroll) */
        width: calc((100% - (1.5 * 0.75rem)) / 2.5);
        min-width: 80px;
        max-width: 100px;
    }

    .category-circle {
        width: 70px;
        height: 70px;
    }

    .category-circle-icon {
        width: 56px;
        height: 56px;
    }

    .category-circle-placeholder {
        width: 56px;
        height: 56px;
        font-size: 1.25rem;
    }

    .category-label {
        font-size: 0.75rem;
    }

    .category-carousel-nav {
        width: 32px;
        height: 32px;
    }

    .category-carousel-nav svg {
        width: 14px;
        height: 14px;
    }
}

/* Hide utility classes */
.hide-on-mobile {
    display: block;
}

@media (max-width: 768px) {
    .hide-on-mobile {
        display: none !important;
    }
}

.category-link {
    color: var(--primary-color);
    text-decoration: none;
    font-weight: 700;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.9375rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.category-link:hover {
    color: var(--primary-hover);
    gap: 0.75rem;
}

/* Home Page Content Section */
.home-content-section {
    padding: 5rem 0;
    background: #ffffff;
    position: relative;
}

.home-content-section .container {
    max-width: 1280px;
    margin: 0 auto;
    padding: 0 24px;
    background: #f8f9fa;
    padding: 25px 100px;
    border-radius: 25px;
}

.home-content-container {
    position: relative;
    display: flex;
    align-items: flex-start;
    gap: 0;
    max-width: 1200px;
    margin: 0 auto;
    max-height: 80vh;
    overflow-y: auto;
}

.home-content-wrapper {
    flex: 1;
    padding-right: 4rem;
    line-height: 1.8;
    color: #374151;
}

.home-content-wrapper h1,
.home-content-wrapper h2,
.home-content-wrapper h3,
.home-content-wrapper h4,
.home-content-wrapper h5,
.home-content-wrapper h6 {
    color: #1f2937;
    font-weight: 700;
    margin-top: 2rem;
    margin-bottom: 1rem;
    line-height: 1.3;
}

.home-content-wrapper h1 {
    font-size: 2.5rem;
}

.home-content-wrapper h2 {
    font-size: 2rem;
}

.home-content-wrapper h3 {
    font-size: 1.75rem;
}

.home-content-wrapper h4 {
    font-size: 1.5rem;
}

.home-content-wrapper p {
    margin-bottom: 1.5rem;
    font-size: 1.125rem;
    color: #4b5563;
}

.home-content-wrapper ul,
.home-content-wrapper ol {
    margin-bottom: 1.5rem;
    padding-left: 2rem;
}

.home-content-wrapper li {
    margin-bottom: 0.75rem;
    font-size: 1.125rem;
    color: #4b5563;
}

.home-content-wrapper a {
    color: var(--primary-color);
    text-decoration: underline;
    transition: color 0.3s ease;
}

.home-content-wrapper a:hover {
    color: var(--primary-hover);
}

.home-content-wrapper img {
    max-width: 100%;
    height: auto;
    border-radius: 8px;
    margin: 2rem 0;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.home-content-wrapper blockquote {
    border-left: 4px solid var(--primary-color);
    padding-left: 1.5rem;
    margin: 2rem 0;
    font-style: italic;
    color: #6b7280;
}

.home-content-wrapper code {
    background: #f3f4f6;
    padding: 0.25rem 0.5rem;
    border-radius: 4px;
    font-size: 0.875rem;
    color: #2951c4;
}

.home-content-wrapper pre {
    background: #1f2937;
    color: #f9fafb;
    padding: 1.5rem;
    border-radius: 8px;
    overflow-x: auto;
    margin: 2rem 0;
}

.home-content-wrapper pre code {
    background: transparent;
    color: inherit;
    padding: 0;
}

.home-content-wrapper table {
    width: 100%;
    border-collapse: collapse;
    margin: 2rem 0;
}

.home-content-wrapper table th,
.home-content-wrapper table td {
    padding: 0.75rem;
    border: 1px solid #e5e7eb;
    text-align: left;
}

.home-content-wrapper table th {
    background: #f9fafb;
    font-weight: 600;
    color: #1f2937;
}

@media (max-width: 768px) {
    .home-content-section {
        padding: 3rem 0;
    }

    .home-content-section .container {
        padding: 0 16px;
    }

    .home-content-container {
        flex-direction: column;
    }

    .home-content-wrapper {
        max-width: 100%;
        padding-right: 0;
        padding-bottom: 2rem;
    }

    .home-content-vertical-line {
        position: relative;
        width: 100%;
        height: 4px;
        right: auto;
        top: auto;
        bottom: 0;
    }

    .home-content-wrapper h1 {
        font-size: 2rem;
    }

    .home-content-wrapper h2 {
        font-size: 1.75rem;
    }

    .home-content-wrapper h3 {
        font-size: 1.5rem;
    }

    .home-content-wrapper p,
    .home-content-wrapper li {
        font-size: 1rem;
    }
}

@media (max-width: 480px) {
    .home-content-section {
        padding: 2.5rem 0;
    }

    .home-content-wrapper {
        padding-bottom: 1.5rem;
    }

    .home-content-wrapper h1 {
        font-size: 1.75rem;
    }

    .home-content-wrapper h2 {
        font-size: 1.5rem;
    }

    .home-content-wrapper h3 {
        font-size: 1.25rem;
    }

    .home-content-wrapper p,
    .home-content-wrapper li {
        font-size: 0.9375rem;
    }
}

/* Category Deals Section */
/* Home Page Banner Section */
.home-banner-section {
    padding: 0;
    margin: 0;
    width: 100%;
    position: relative;
}

.home-banner-wrapper {
    position: relative;
    width: 100%;
    overflow: hidden;
    display: flex;
    align-items: center;
    justify-content: center;
}

.home-banner-image {
    width: 100%;
    height: auto;
    display: block;
    object-fit: cover;
    max-height: 500px;
    object-position: center;
}

.home-banner-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(
        135deg,
        rgba(0, 0, 0, 0.3) 0%,
        rgba(0, 0, 0, 0.1) 50%,
        rgba(0, 0, 0, 0.3) 100%
    );
    pointer-events: none;
    z-index: 1;
}

@media (max-width: 768px) {
    .home-banner-section {
        margin: 0;
    }

    .home-banner-image {
        max-height: 250px;
    }

    .home-banner-overlay {
        background: linear-gradient(
            135deg,
            rgba(0, 0, 0, 0.2) 0%,
            rgba(0, 0, 0, 0.1) 50%,
            rgba(0, 0, 0, 0.2) 100%
        );
    }
}

@media (max-width: 480px) {
    .home-banner-image {
        max-height: 200px;
    }
}

.category-deals-section {
    padding: 5rem 0;
    background: var(--background-primary-color, white);
}

.category-section {
    margin-bottom: 3rem;
    contain: layout style;
}

.category-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.5rem;
}

.category-title {
    font-size: 1.5rem;
    font-weight: 600;
    color: var(--text-color, #1a202c);
}

.view-more {
    color: var(--primary-color, #667eea);
    text-decoration: none;
    font-weight: 600;
}

.category-deals {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 1rem;
}

.mini-deal-card {
    background: var(--background-secondary-color, #f8fafc);
    border-radius: 12px;
    padding: 1rem;
    border: 1px solid var(--background-secondary-color, #e2e8f0);
    transition: all 0.3s ease;
}

.mini-deal-card:hover {
    background: var(--background-primary-color, white);
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
}

.deal-store {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin-bottom: 0.75rem;
}

.mini-store-logo {
    width: 24px;
    height: 24px;
    border-radius: 50%;
    object-fit: cover;
}

.mini-store-name {
    font-size: 0.9rem;
    font-weight: 600;
    color: var(--text-color, #1a202c);
}

.mini-deal-title {
    font-size: 0.95rem;
    color: var(--text-color, #374151);
    margin-bottom: 0.75rem;
    line-height: 1.4;
}

.deal-actions {
    text-align: right;
}

.mini-btn {
    padding: 0.5rem 1rem;
    border: none;
    border-radius: 8px;
    font-size: 0.85rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
}

.mini-btn.reveal-code {
    background: linear-gradient(45deg, #ff6b6b, #ffd93d);
    color: white;
}

.mini-btn.get-deal {
    background: linear-gradient(45deg, #667eea, #764ba2);
    color: white;
}

.mini-btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
}

.no-category-deals {
    text-align: center;
    padding: 2rem;
    color: var(--text-color, #64748b);
    grid-column: 1 / -1;
}

/* Responsive Design */
@media (max-width: 1200px) {
    .deals-grid {
        grid-template-columns: repeat(3, 1fr);
        gap: 1.25rem;
    }
}

@media (max-width: 992px) {
    .deals-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 1rem;
    }

    /* .deal-card {
        height: 350px;
    } */

    /* .deal-image-section {
        height: 120px;
    } */

    .deal-content-wrapper {
        padding: 0.75rem;
    }
}

@media (max-width: 768px) {
    .hero-title {
        font-size: 2.5rem;
    }

    .hero-stats {
        gap: 2rem;
    }

    .stat-number {
        font-size: 2rem;
    }

    .deals-grid {
        grid-template-columns: 1fr;
        gap: 1rem;
    }

    .deal-card {
        max-width: 350px;
        margin: 0 auto;
        height: 280px;
    }

    /* .deal-image-section {
        height: 100px;
    } */

    .deal-content-wrapper {
        padding: 0.75rem;
    }

    .categories-grid {
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    }

    .category-deals {
        grid-template-columns: 1fr;
    }

    .hot-deals-section {
        padding: 3rem 0;
    }

    .section-title {
        font-size: 2rem !important;
    }

    .brands-slider {
        gap: 1.5rem;
    }

    .brand-logo-circle {
        width: 80px;
        height: 80px;
    }

    .brand-logo {
        max-width: 60px;
        max-height: 60px;
    }

    .brand-placeholder {
        width: 60px;
        height: 60px;
    }

    .brand-initials {
        font-size: 1.2rem;
    }

    .dot {
        width: 10px;
        height: 10px;
    }

    .carousel-btn {
        width: 35px;
        height: 35px;
        font-size: 1.2rem;
    }

    .prev-btn {
        left: -15px;
    }

    .next-btn {
        right: -15px;
    }
    .category-card p {
        margin-bottom: 0.5rem;
    }
}

@media (max-width: 480px) {
    .hero-title {
        font-size: 2rem;
    }

    .hero-subtitle {
        font-size: 1rem;
    }

    .search-input {
        padding: 0.875rem 1.25rem;
        font-size: 1rem;
    }

    .brands-track {
        gap: 1rem;
    }

    .brand-card {
        flex: 0 0 150px;
        padding: 1rem;
    }
}

/* Recommended Stores Tab Functionality CSS */
.store-category-content {
    display: none;
}

.store-category-content.active {
    display: block;
}

.tb a {
    cursor: pointer;
    transition: all 0.3s ease;
}

.tb a:hover {
    background-color: #f8f9fa;
    color: #333;
}

.tb a.active {
    background-color: #e9ecef;
    color: #333;
    font-weight: 600;
}

.cnt .store-category-content a {
    display: inline-block;
    color: #333;
    text-decoration: none;
    font-size: 0.9rem;
    margin-right: 1rem;
    margin-bottom: 0.5rem;
    transition: color 0.3s ease;
}

.cnt .store-category-content a:hover {
    color: #007bff;
    text-decoration: underline;
}

.cnt .no-stores, .cnt .no-categories {
    color: #666;
    font-size: 0.9rem;
    margin-bottom: 1rem;
}

.cnt .lst {
    display: block;
    margin-top: 1rem;
    color: #007bff;
    text-decoration: none;
    font-weight: 600;
}

.cnt .lst:hover {
    text-decoration: underline;
}

/* Responsive Design */
@media (max-width: 768px) {
    .cnt .store-category-content a {
        font-size: 0.85rem;
        margin-right: 0.75rem;
    }
}
</style>

<!-- Dynamic Background Styles -->
<style>
.hero-background {
    @if(!$homeBanner)
    background: {{ $secondaryColor }};
    @endif
}

.hero-banner-image {
    position: relative;
}

.hero-banner-image::after {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: {{ $homeOverlayColor}};
    z-index: 1;
    pointer-events: none;
}

.floating-shapes {
    position: absolute;
    width: 100%;
    height: 100%;
    z-index: 1;
}

.shape {
    position: absolute;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.1);
    animation: float 6s ease-in-out infinite;
}

.shape-1 {
    width: 80px;
    height: 80px;
    top: 20%;
    left: 10%;
    animation-delay: 0s;
}

.shape-2 {
    width: 120px;
    height: 120px;
    top: 60%;
    right: 15%;
    animation-delay: 2s;
}

.shape-3 {
    width: 60px;
    height: 60px;
    top: 30%;
    right: 30%;
    animation-delay: 4s;
}

.shape-4 {
    width: 100px;
    height: 100px;
    bottom: 20%;
    left: 20%;
    animation-delay: 1s;
}

@keyframes float {
    0%, 100% { transform: translateY(0px) rotate(0deg); }
    50% { transform: translateY(-20px) rotate(180deg); }
}

.hero-content {
    position: relative;
    z-index: 3;
}
</style>

<!-- Enhanced Coupon Modal -->
<div id="couponModal" aria-hidden="true" style="display:none;">
    <div class="cm-overlay"></div>

    <!-- Main Voucher Code Popup -->
    <div class="cm-main-popup" role="dialog" aria-modal="true" aria-label="Coupon Code Popup">
        <button class="cm-close" aria-label="Close popup">&times;</button>

        <!-- Main Popup Content -->
        <div class="cm-main-content">
            <h3 class="cm-title" id="cmTitle">Here is your code</h3>

            <div class="cm-code-section">
                <div class="cm-code-display" id="cmCode">CODE123</div>
                <button class="cm-copy-btn" id="cmCopy">Copy Code</button>
            </div>

            <div class="cm-note" id="cmNote">
                <p>Copy the code above and use it at checkout to get your discount!</p>
            </div>
        </div>
    </div>

    <!-- Email Subscription Popup -->
    <div class="cm-email-popup" role="dialog" aria-modal="true" aria-label="Email Subscription Popup">
        <div class="cm-email-content">
            <div class="cm-brand-logo">
                <div class="cm-brand-circle" id="cmBrandLogo">
                    <span id="cmBrandText">STORE</span>
                </div>
            </div>

            <h3 class="cm-email-title" id="cmEmailTitle">Get More Deals!</h3>
            <p class="cm-email-subtitle">Subscribe to get exclusive offers and discounts</p>

            <form class="cm-email-form" id="cmEmailForm">
                <input type="email" placeholder="Enter your email" required>
                <button type="submit">Subscribe</button>
            </form>

            <p class="cm-email-privacy">We respect your privacy. Unsubscribe at any time.</p>
        </div>
    </div>
</div>

<style>
/* Coupon Modal Styles */
#couponModal {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 9999;
    padding: 20px;
    box-sizing: border-box;
}

.cm-overlay {
    position: absolute;
    inset: 0;
    background: rgba(0,0,0,0.6);
}

/* Main Voucher Code Popup */
.cm-main-popup {
  position: relative;
  top: 20px;
  margin: auto;
    width: 480px;
    max-width: calc(50% - 30px);
    background: #fff;
    border-radius: 16px;
    padding: 30px;
    box-shadow: 0 20px 40px rgba(0,0,0,0.3);
    overflow: hidden;
    z-index: 2;
}

/* Email Subscription Popup */
.cm-email-popup {
  position: relative;
  margin: auto;
  top: 40px;
    width: 480px;
    max-width: calc(50% - 30px);
    background: #fff;
    border-radius: 16px;
    padding: 30px;
    box-shadow: 0 20px 40px rgba(0,0,0,0.3);
    overflow: hidden;
    z-index: 2;
}

.cm-close {
    position: absolute;
    top: 15px;
    right: 20px;
    background: none;
    border: none;
    font-size: 24px;
    cursor: pointer;
    color: #666;
    z-index: 3;
}

.cm-close:hover {
    color: #000;
}

.cm-main-content {
    text-align: center;
}

.cm-title {
    font-size: 24px;
    font-weight: bold;
    margin-bottom: 20px;
    color: #333;
}

.cm-code-section {
    margin: 20px 0;
}

.cm-code-display {
    background: var(--background-secondary-color, #f8f9fa);
    border: 2px dashed var(--primary-color);
    border-radius: 8px;
    padding: 15px;
    font-size: 18px;
    font-weight: bold;
    color: var(--primary-color);
    margin-bottom: 15px;
    font-family: monospace;
}

.cm-copy-btn {
    background: var(--primary-color);
    color: white;
    border: none;
    padding: 12px 24px;
    border-radius: 6px;
    font-size: 16px;
    cursor: pointer;
    transition: background 0.3s;
}

.cm-copy-btn:hover {
    background: var(--secondary-color, #cc0000);
}

.cm-note {
    margin-top: 20px;
    color: var(--text-color, #666);
    font-size: 14px;
}

.cm-email-content {
    text-align: center;
}

.cm-brand-logo {
    margin-bottom: 20px;
}

.cm-brand-circle {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    background: var(--primary-color);
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto;
    color: white;
    font-weight: bold;
    font-size: 14px;
}

.cm-brand-circle img {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    object-fit: cover;
}

.cm-email-title {
    font-size: 22px;
    font-weight: bold;
    margin-bottom: 10px;
    color: var(--text-color, #333);
}

.cm-email-subtitle {
    color: var(--text-color, #666);
    margin-bottom: 20px;
    font-size: 14px;
}

.cm-email-form {
    margin: 20px 0;
}

.cm-email-form input {
    width: 100%;
    padding: 12px;
    border: 1px solid #ddd;
    border-radius: 6px;
    margin-bottom: 10px;
    font-size: 14px;
}

.cm-email-form button {
    width: 100%;
    background: var(--primary-color);
    color: white;
    border: none;
    padding: 12px;
    border-radius: 6px;
    font-size: 16px;
    cursor: pointer;
    transition: background 0.3s;
}

.cm-email-form button:hover {
    background: var(--secondary-color, #cc0000);
}

.cm-email-privacy {
    font-size: 12px;
    color: var(--text-color, #999);
    margin-top: 15px;
}

/* Responsive */
@media (max-width: 768px) {
    .cm-main-popup {
        top: 20px;
        left: 10px;
        right: 10px;
        width: auto;
        max-width: none;
    }

    .cm-email-popup {
        top: 400px;
        left: 10px;
        right: 10px;
        width: auto;
        max-width: none;
    }

    .cm-main-content {
        padding: 20px;
    }

    .cm-title {
        font-size: 20px;
    }

    .cm-code-display {
        font-size: 16px;
        padding: 12px;
    }
}
</style>

<script defer>
// Recommended Stores Tab Functionality - Deferred
(function() {
    function initTabs() {
    const tabLinks = document.querySelectorAll('.tb a');
    const storeContents = document.querySelectorAll('.store-category-content');

    tabLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();

            // Remove active class from all tabs
            tabLinks.forEach(tab => tab.classList.remove('active'));
            storeContents.forEach(content => content.classList.remove('active'));

            // Add active class to clicked tab
            this.classList.add('active');

            // Show corresponding content
            const categoryId = this.getAttribute('data-type');
            const targetContent = document.querySelector(`.store-category-content[data-category="${categoryId}"]`);
            if (targetContent) {
                targetContent.classList.add('active');
            }
        });
    });
    }

    // Initialize tabs when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initTabs);
    } else {
        initTabs();
    }
})();
</script>

<script>
document.addEventListener('DOMContentLoaded', function () {
  // Prevent double-init
  if (window.__couponModalInit) return;
  window.__couponModalInit = true;

  const modal = document.getElementById('couponModal');
  if (!modal) return;

  const overlay = modal.querySelector('.cm-overlay');
  const closeBtn = modal.querySelector('.cm-close');
  const cmCode = document.getElementById('cmCode');
  const cmCopy = document.getElementById('cmCopy');
  const cmTitle = document.getElementById('cmTitle');
  const cmNote = document.getElementById('cmNote');
  const cmEmailTitle = document.getElementById('cmEmailTitle');
  const cmBrandLogo = document.getElementById('cmBrandLogo');
  const cmBrandText = document.getElementById('cmBrandText');

  // Make openModal globally accessible
  window.openModal = function(code, affiliate, store, title) {
    if (cmCode) cmCode.textContent = code;
    if (cmTitle) cmTitle.textContent = title || 'Here is your code';
    if (cmEmailTitle) cmEmailTitle.textContent = `Get More ${store} Deals!`;

    // Store affiliate URL for redirect button
    window.currentAffiliateUrl = affiliate;

    if (cmBrandLogo && cmBrandText) {
      if (store && store !== 'Store') {
        cmBrandText.textContent = store.substring(0,5).toUpperCase();
      } else {
        cmBrandText.textContent = 'STORE';
      }
    }

    // Hide Copy Code button if "No code required" is displayed
    if (cmCopy && cmCode) {
      if (code === 'No code required' || code === '' || !code) {
        cmCopy.style.display = 'none';
      } else {
        cmCopy.style.display = 'block';
      }
    }

    modal.style.display = 'block';
    modal.setAttribute('aria-hidden','false');
    document.body.style.overflow = 'hidden';
  };

  window.closeModal = function() {
    modal.style.display = 'none';
    modal.setAttribute('aria-hidden','true');
    document.body.style.overflow = '';
  }

  // Note: Button handlers are already set up in the immediate execution block above (lines 1450-1520)
  // This ensures buttons work immediately and allows bfcache restoration

  // Copy button
  if (cmCopy) {
    cmCopy.addEventListener('click', function() {
      const code = cmCode ? cmCode.textContent : '';
      if (code && code !== 'No code required') {
        navigator.clipboard.writeText(code).then(function() {
          const originalText = cmCopy.textContent;
          cmCopy.textContent = 'Copied!';
          cmCopy.style.backgroundColor = '#218838';

        setTimeout(function() {
          cmCopy.textContent = originalText;
          cmCopy.style.backgroundColor = getComputedStyle(document.documentElement).getPropertyValue('--primary-color') || '#2951c4';
        }, 2000);
        }).catch(function(err) {
          console.error('Could not copy text: ', err);
          alert('Coupon Code: ' + code);
        });
      } else if (code === 'No code required') {
        // For deals without codes, just show message
        const originalText = cmCopy.textContent;
        cmCopy.textContent = 'No Code Needed!';
        cmCopy.style.backgroundColor = '#218838';

        setTimeout(function() {
          cmCopy.textContent = originalText;
          cmCopy.style.backgroundColor = getComputedStyle(document.documentElement).getPropertyValue('--primary-color') || '#2951c4';
        }, 2000);
      }
    });
  }

  // Email form
  const emailForm = document.getElementById('cmEmailForm');
  if (emailForm) {
    emailForm.addEventListener('submit', function(e) {
      e.preventDefault();
      const email = this.querySelector('input[type="email"]').value;
      if (email) {
        // Here you can add AJAX call to subscribe
        alert('Thank you for subscribing!');
        if (window.closeModal) window.closeModal();
      }
    });
  }

  // Redirect button
  const cmRedirect = document.getElementById('cmRedirect');
  if (cmRedirect) {
    cmRedirect.addEventListener('click', function() {
      const currentAffiliate = window.currentAffiliateUrl || '#';
      if (currentAffiliate && currentAffiliate !== '#') {
        window.open(currentAffiliate, '_blank');
      }
    });
  }

  if (closeBtn) closeBtn.addEventListener('click', window.closeModal);
  if (overlay) overlay.addEventListener('click', window.closeModal);

  // show modal if params present
  try {
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('show_coupon') === '1') {
      const code = urlParams.get('code') || '';
      const affiliate = urlParams.get('affiliate') || '#';
      const store = urlParams.get('store') || 'Store';
      const title = urlParams.get('title') || 'Here is your code';

      if (window.openModal) {
        window.openModal(code, affiliate, store, title);
      }

      // If no code, show "No code required" message
      if (!code || code === 'No code required') {
        if (cmCode) cmCode.textContent = 'No code required';
        if (cmCopy) {
          cmCopy.style.display = 'none';
        }
      } else {
        if (cmCopy) {
          cmCopy.style.display = 'block';
        }
      }

      history.replaceState({}, '', window.location.pathname);
    }
  } catch (e) {
    // URL params not supported
  }
});
</script>

<!-- Brands Carousel Functionality - Deferred -->
<script>
(function() {
    function initBrandsCarousel() {
const brandsSlider = document.getElementById('brandsSlider');
const brandItems = document.querySelectorAll('.brand-item');

    if (!brandsSlider || brandItems.length === 0) {
        return;
    }

    // Woodmart-style carousel calculations
    const itemWidth = 224; // 200px width + 24px gap
    const containerWidth = brandsSlider.parentElement.offsetWidth - 64; // Subtract padding
    const visibleItems = Math.floor(containerWidth / itemWidth);
    const totalSlides = Math.ceil(brandItems.length / visibleItems);
    let currentSlide = 0;
    let isTransitioning = false;


function moveCarousel(direction) {
        if (isTransitioning || totalSlides <= 1) return;

        isTransitioning = true;
        currentSlide += direction;

        // Handle boundaries
        if (currentSlide >= totalSlides) {
            currentSlide = 0;
        } else if (currentSlide < 0) {
            currentSlide = totalSlides - 1;
        }

        // Calculate proper transform - move by visible items only
        const translateX = -(currentSlide * visibleItems * itemWidth);

        // Smooth transition with Woodmart-style easing
        brandsSlider.style.transition = 'transform 0.8s cubic-bezier(0.25, 0.46, 0.45, 0.94)';
        brandsSlider.style.transform = `translateX(${translateX}px)`;

        setTimeout(() => {
            isTransitioning = false;
        }, 800);
    }

    // Auto-play carousel
    let autoPlayInterval = setInterval(() => {
        if (totalSlides > 1) {
            moveCarousel(1);
        }
    }, 4000);

    // Pause auto-play on hover
    brandsSlider.addEventListener('mouseenter', () => {
        clearInterval(autoPlayInterval);
    });

    brandsSlider.addEventListener('mouseleave', () => {
        autoPlayInterval = setInterval(() => {
    if (totalSlides > 1) {
        moveCarousel(1);
    }
}, 4000);
    });

    // Touch/swipe support
    let startX = 0;
    let endX = 0;

    brandsSlider.addEventListener('touchstart', (e) => {
        startX = e.touches[0].clientX;
    });

    brandsSlider.addEventListener('touchend', (e) => {
        endX = e.changedTouches[0].clientX;
        const diff = startX - endX;

        if (Math.abs(diff) > 50) { // Minimum swipe distance
            if (diff > 0) {
                moveCarousel(1); // Swipe left - next
            } else {
                moveCarousel(-1); // Swipe right - previous
            }
        }
    });

    // Make functions global for onclick handlers
    window.moveCarousel = moveCarousel;

    // Generate pagination dots
function generateDots() {
    const dotsContainer = document.getElementById('carouselDots');
        if (!dotsContainer || totalSlides <= 1) return;

    dotsContainer.innerHTML = '';

    for (let i = 0; i < totalSlides; i++) {
        const dot = document.createElement('span');
        dot.className = 'dot';
        if (i === 0) dot.classList.add('active');
            dot.onclick = () => {
                currentSlide = i;
                const translateX = -(currentSlide * visibleItems * itemWidth);
                brandsSlider.style.transition = 'transform 0.8s cubic-bezier(0.25, 0.46, 0.45, 0.94)';
                brandsSlider.style.transform = `translateX(${translateX}px)`;
                updateDots();
            };
        dotsContainer.appendChild(dot);
    }
}

    function updateDots() {
        const dots = document.querySelectorAll('.dot');
        dots.forEach((dot, index) => {
            dot.classList.toggle('active', index === currentSlide);
        });
}

// Initialize carousel
    if (totalSlides > 1) {
    generateDots();
    updateDots();
    } else {
        // Hide navigation buttons if not enough items
        const prevBtn = document.querySelector('.prev-btn');
        const nextBtn = document.querySelector('.next-btn');
        if (prevBtn) prevBtn.style.display = 'none';
        if (nextBtn) nextBtn.style.display = 'none';
    }

    // Ensure initial position is correct
    brandsSlider.style.transform = 'translateX(0px)';

    // Add resize listener to recalculate on window resize
    window.addEventListener('resize', () => {
        const newContainerWidth = brandsSlider.parentElement.offsetWidth - 64;
        const newVisibleItems = Math.floor(newContainerWidth / itemWidth);
        const newTotalSlides = Math.ceil(brandItems.length / newVisibleItems);

        if (newTotalSlides !== totalSlides) {
            // Reset to first slide
            currentSlide = 0;
            brandsSlider.style.transform = 'translateX(0px)';
            generateDots();
            updateDots();
        }
    });

    }

    // Defer brands carousel initialization
    if ('requestIdleCallback' in window) {
        requestIdleCallback(initBrandsCarousel, { timeout: 2000 });
    } else {
        setTimeout(initBrandsCarousel, 200);
    }
})();
</script>

<!-- Search Modal Functionality -->

<script>
// Newsletter subscription (vanilla JS, no jQuery) to keep main-thread work low
(function() {
    function showMessage(message, type) {
        var messageDiv = document.getElementById('newsletterMessage');
        if (!messageDiv) return;

        messageDiv.innerHTML = '';
        messageDiv.style.display = 'none';

        var color = type === 'success' ? '#10b981' : '#ef4444';
        var backgroundColor = type === 'success' ? '#f0fdf4' : '#fef2f2';
        var borderColor = type === 'success' ? '#10b981' : '#ef4444';

        messageDiv.innerHTML =
            '<div style="color: ' + color + '; background-color: ' + backgroundColor + '; border: 1px solid ' + borderColor + '; font-size: 14px; margin-top: 10px; padding: 8px; border-radius: 4px; font-weight: 500; text-align: center;">' +
            message +
            '</div>';
        messageDiv.style.display = 'block';

        setTimeout(function() {
            messageDiv.style.display = 'none';
        }, 5000);
    }

    function handleNewsletterSubmit(e) {
            e.preventDefault();

        var form = document.getElementById('newsletterForm');
        if (!form) return;

        var emailInput = document.getElementById('newsletterEmail');
        var btn = document.getElementById('newsletterBtn');

        if (!emailInput || !btn) return;

        var email = (emailInput.value || '').trim();
            if (!email) {
                showMessage('Please enter your email address.', 'error');
                return;
            }

        if (!email.includes('@') || !email.includes('.') || email.indexOf('@') > email.lastIndexOf('.')) {
                showMessage('Please enter a valid email address.', 'error');
                return;
            }

        btn.disabled = true;
        btn.textContent = 'Subscribing...';

        var csrfToken = document.querySelector('meta[name=\"csrf-token\"]');
        var token = csrfToken ? csrfToken.getAttribute('content') : '';

        fetch('{{ route("newsletter.subscribe") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': token,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ email: email })
        })
            .then(function(response) {
                return response.json().catch(function() {
                    return {};
                });
            })
            .then(function(data) {
                if (data && data.success) {
                    showMessage(data.message || 'Subscribed successfully.', 'success');
                    emailInput.value = '';
                    } else {
                    showMessage((data && data.message) || 'Something went wrong. Please try again.', 'error');
                    }
            })
            .catch(function() {
                showMessage('Something went wrong. Please try again.', 'error');
            })
            .finally(function() {
                btn.disabled = false;
                btn.textContent = 'Subscribe';
            });
    }

    function bootstrapNewsletter() {
        var form = document.getElementById('newsletterForm');
        if (!form) return;
        form.addEventListener('submit', handleNewsletterSubmit);
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', bootstrapNewsletter);
                    } else {
        bootstrapNewsletter();
    }
})();
</script>

<!-- Simple jQuery Search Implementation -->
<style>
@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

/* Professional Mobile Responsiveness - Google AdSense Ready */

/* Featured Stores Section - Clean Grid Layout */
.featured-stores-section {
    padding: 4rem 0;
    background: #ffffff;
}

.featured-stores-grid {
    display: grid;
    grid-template-columns: repeat(5, 1fr);
    gap: 1.5rem;
    margin-top: 2.5rem;
}

.featured-store-card {
    background: #ffffff;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    overflow: hidden;
    display: flex;
    flex-direction: column;
}

.featured-store-card:hover {
    box-shadow: 0 4px 16px rgba(0, 0, 0, 0.12);
    transform: translateY(-2px);
}

.featured-store-link {
    display: flex;
    flex-direction: column;
    text-decoration: none;
    color: inherit;
    height: 100%;
}

.featured-store-logo-area {
    width: 100%;
    height: 120px;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 0;
    background: #ffffff;
    border-radius: 12px 12px 0 0;
    transition: background 0.3s ease;
    overflow: hidden;
}

.featured-store-card:hover .featured-store-logo-area {
    background: #f8f9fa;
}

.featured-store-logo {
    width: 100%;
    height: 100%;
    max-width: 100%;
    object-fit: contain;
    transition: transform 0.3s ease;
}

.featured-store-card:hover .featured-store-logo {
    transform: scale(1.05);
}

.featured-store-logo-placeholder {
    width: 100px;
    height: 100px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #374151;
    border-radius: 8px;
}

.featured-store-logo-placeholder span {
    font-weight: 700;
    color: #ffffff;
    font-size: 1.5rem;
    letter-spacing: 1px;
}

.featured-store-card:hover .featured-store-offers-btn {
    background: var(--primary-hover, #cc0000);
    transform: translateY(-1px);
    box-shadow: 0 2px 8px rgba(255, 0, 0, 0.2);
}

/* Responsive Grid - Already defined above */

@media (max-width: 992px) {
    .featured-stores-section {
        padding: 3rem 1rem;
    }

    .featured-stores-grid {
        grid-template-columns: repeat(3, 1fr);
        gap: 1rem;
    }

    .featured-store-logo-area {
        height: 110px;
        padding: 1rem;
    }
}

@media (max-width: 768px) {
    /* Hide stores 7-10 on mobile (show only first 6) to reduce DOM size */
    .featured-store-card.hide-on-mobile {
        display: none !important;
    }

    .featured-stores-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 1rem;
    }

    .featured-store-logo-area {
        height: 200px;
    }

    .featured-store-name {
        font-size: 0.875rem;
        padding: 0.875rem 0.875rem 0.625rem;
    }

    .featured-store-offers-btn {
        margin: 0 0.875rem 0.875rem;
        padding: 0.5rem 0.875rem;
        font-size: 0.8125rem;
    }
}

@media (max-width: 480px) {
    .featured-stores-section {
        padding: 2.5rem 1rem;
    }

    .featured-stores-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 0.875rem;
    }

    .featured-store-logo-area {
        height: 130px;
        padding: 0.875rem;
    }

    .featured-store-name {
        font-size: 0.8125rem;
        padding: 0.75rem 0.75rem 0.5rem;
    }

    .featured-store-offers-btn {
        margin: 0 0.75rem 0.75rem;
        padding: 0.5rem 0.75rem;
        font-size: 0.75rem;
    }
}

/* Hero section enhancements */
@media (max-width: 768px) {
  .modern-hero {
    min-height: 60vh;
  }
  .floating-shapes {
    display: none;
  }
  .hero-background::before {
    background: rgba(0, 0, 0, 0.5);
  }
}

/* Professional Deals grid for small screens */
@media (max-width: 480px) {
  .deal-card {
    height: auto;
    border-radius: 12px;
  }
  .deal-content-wrapper {
    height: auto;
    padding: 1rem;
  }
  .deal-image-section {
    height: 160px;
  }
}

/* Category images on small screens - Enhanced */
@media (max-width: 768px) {
  .category-image {
    width: 40%;
    height: auto;
    max-width: 120px;
  }
  .category-title-icon {
    width: 28px;
    height: auto;
  }
  .category-card {
    padding: 2rem 1.5rem;
  }
}

/* Additional professional enhancements */
@media (max-width: 992px) {
  .section-title {
    font-size: 2rem;
  }
  .categories-grid {
    grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
    gap: 1.5rem;
  }
}
</style>

<style>
/* Additional mobile fixes for Hot Deals and Category Deals cards */
@media (max-width: 768px) {
  /* Hide deals 7-8 on mobile (show only first 6) to reduce DOM size */
  .hot-deals-section .deal-card.hide-on-mobile {
    display: none !important;
  }

  .deals-grid { grid-template-columns: 1fr; gap: 12px; }
  .deal-card { width: 100%; max-width: 480px; margin: 0 auto; height: auto; border-radius: 16px; }
  .deal-header { margin-bottom: 0.75rem; padding-top: 15px; }
  .deal-title { font-size: 0.95rem; }
  .deal-meta .meta-item { font-size: 0.6rem; }
  .type-badge, .verified-badge { font-size: 0.55rem; }
}
@media (max-width: 480px) {
  .deal-cover-image { object-fit: cover; }
  .discount-badge { font-size: 0.6rem; padding: 0.15rem 0.4rem; top: 6px; right: 6px; }
  .exclusive-badge { font-size: 0.55rem; padding: 0.18rem 0.35rem; top: 6px; left: 6px; }
  .deal-content-wrapper { padding: 0.75rem; }
  .store-logo { width: 28px; height: 28px; }
  .store-details h3 { font-size: 0.85rem; }
  .deal-btn { font-size: 0.85rem; padding: 0.6rem 0.75rem; }
}


/* Mobile DOM optimization: Reduce initial render cost for below-the-fold content */
@media (max-width: 767px) {
  /* Hide categories 7-8 on mobile (show only first 6) to reduce DOM size */
  .categories-section .category-card.hide-on-mobile {
    display: none !important;
  }

  /* Hide category deals 7-8 on mobile (show only first 6 per category) to reduce DOM size */
  .category-deals-section .deal-card.hide-on-mobile {
    display: none !important;
  }

  /* Hide blog posts 5-6 on mobile (show only first 4) to reduce DOM size */
  .blog-section .blog-card.hide-on-mobile {
    display: none !important;
  }

  /* Blog Section Responsive */
  @media (max-width: 1024px) {
    .blog-grid {
      grid-template-columns: repeat(2, 1fr);
      gap: 2rem;
    }

    .blog-image-wrapper {
      height: 240px;
    }
  }

  @media (max-width: 768px) {
    .blog-section {
      padding: 4rem 0;
    }

    .blog-section .container {
      padding: 0 16px;
    }

    .blog-grid {
      grid-template-columns: 1fr;
      gap: 2rem;
      margin-top: 2.5rem;
    }

    .blog-image-wrapper {
      height: 220px;
    }

    .blog-content {
      padding: 1.5rem;
    }

    .blog-title {
      font-size: 1.125rem;
      min-height: auto;
    }

    .blog-meta {
      gap: 1rem;
    }
  }

  @media (max-width: 480px) {
    .blog-section {
      padding: 3rem 0;
    }

    .blog-grid {
      gap: 1.5rem;
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

    .blog-category-badge {
      top: 10px;
      left: 10px;
      padding: 0.3125rem 0.75rem;
      font-size: 0.625rem;
    }

    .blog-meta {
      gap: 0.875rem;
    }

    .blog-date,
    .blog-views {
      font-size: 0.75rem;
    }
  }


  /* Optimize grid layouts on mobile - reduce gap to save DOM space */
  .featured-stores-grid,
  .deals-grid,
  .categories-grid {
    gap: 0.75rem;
  }
}
</style>

@push('scripts')
<!-- Back to Top Button Functionality - Optimized -->
<script defer>
(function() {
    function initBackToTop() {
    const backToTopBtn = document.getElementById('tpBtn');
        if (!backToTopBtn) return;

        // Throttled scroll handler using requestAnimationFrame to reduce main-thread work
        let scrollTimeout;
    window.addEventListener('scroll', function() {
            if (scrollTimeout) return;
            scrollTimeout = requestAnimationFrame(function() {
                scrollTimeout = null;
        if (window.pageYOffset > 300) {
            backToTopBtn.style.display = 'block';
            backToTopBtn.style.opacity = '1';
        } else {
            backToTopBtn.style.opacity = '0';
            setTimeout(() => {
                if (window.pageYOffset <= 300) {
                    backToTopBtn.style.display = 'none';
                }
            }, 300);
        }
            });
    });

    // Scroll to top when button is clicked
    backToTopBtn.addEventListener('click', function() {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    });
    }

    // Defer back to top initialization
    if ('requestIdleCallback' in window) {
        requestIdleCallback(initBackToTop, { timeout: 1000 });
    } else {
        setTimeout(initBackToTop, 100);
    }
})();

// Handle Coupon Click for Exclusive Offers
function handleCouponClick(couponId, code, affiliate, store, title) {
    // Track coupon view
    if (couponId) {
        setTimeout(function() {
            var csrfToken = document.querySelector('meta[name="csrf-token"]');
            fetch('{{ route("coupon.track-view") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken ? csrfToken.getAttribute('content') : ''
                },
                body: JSON.stringify({ coupon_id: couponId })
            }).catch(function() {});
        }, 0);
    }

    // Handle Reveal Code - Open popup and redirect
    if (code && affiliate) {
        var currentUrl = window.location.href.split('#')[0].split('?')[0];
        var popupUrl = currentUrl + '?show_coupon=1&code=' + encodeURIComponent(code) + '&affiliate=' + encodeURIComponent(affiliate) + '&store=' + encodeURIComponent(store) + '&title=' + encodeURIComponent(title);
        setTimeout(function() {
            var popup = window.open(popupUrl, '_blank', 'noopener,noreferrer');
            if (!popup || popup.closed || typeof popup.closed === 'undefined') {
                if (window.openModal) {
                    window.openModal(code, affiliate, store, title);
                }
            }
        }, 0);
        setTimeout(function() {
            window.location.href = affiliate;
        }, 0);
    }
}

// Categories Carousel Functionality
(function() {
    function initCategoriesCarousel() {
        const carousel = document.getElementById('categoriesCarousel');
        const prevBtn = document.getElementById('categoryCarouselPrev');
        const nextBtn = document.getElementById('categoryCarouselNext');

        if (!carousel || !prevBtn || !nextBtn) return;

        // Calculate scroll amount based on visible items (5 items)
        function getScrollAmount() {
            const firstItem = carousel.querySelector('.category-item');
            if (!firstItem) return 200;
            const itemWidth = firstItem.offsetWidth;
            const gap = 24; // 1.5rem = 24px
            // Scroll by one item width + gap
            return itemWidth + gap;
        }

        // Scroll functions
        function scrollPrev() {
            const scrollAmount = getScrollAmount();
            carousel.scrollBy({
                left: -scrollAmount,
                behavior: 'smooth'
            });
        }

        function scrollNext() {
            const scrollAmount = getScrollAmount();
            carousel.scrollBy({
                left: scrollAmount,
                behavior: 'smooth'
            });
        }

        prevBtn.addEventListener('click', scrollPrev);
        nextBtn.addEventListener('click', scrollNext);

        // Touch/Swipe support for mobile
        let touchStartX = 0;
        let touchEndX = 0;
        let isDragging = false;

        carousel.addEventListener('touchstart', function(e) {
            touchStartX = e.touches[0].clientX;
            isDragging = true;
        }, { passive: true });

        carousel.addEventListener('touchmove', function(e) {
            if (isDragging) {
                touchEndX = e.touches[0].clientX;
            }
        }, { passive: true });

        carousel.addEventListener('touchend', function() {
            if (!isDragging) return;
            isDragging = false;

            const swipeThreshold = 50;
            const diff = touchStartX - touchEndX;

            if (Math.abs(diff) > swipeThreshold) {
                if (diff > 0) {
                    // Swipe left - next
                    scrollNext();
                } else {
                    // Swipe right - prev
                    scrollPrev();
                }
            }
        }, { passive: true });

        // Mouse drag support for desktop
        let mouseDown = false;
        let startX = 0;
        let scrollLeft = 0;

        carousel.addEventListener('mousedown', function(e) {
            mouseDown = true;
            startX = e.pageX - carousel.offsetLeft;
            scrollLeft = carousel.scrollLeft;
            carousel.style.cursor = 'grabbing';
            carousel.style.userSelect = 'none';
        });

        carousel.addEventListener('mouseleave', function() {
            mouseDown = false;
            carousel.style.cursor = 'grab';
        });

        carousel.addEventListener('mouseup', function() {
            mouseDown = false;
            carousel.style.cursor = 'grab';
            carousel.style.userSelect = 'auto';
        });

        carousel.addEventListener('mousemove', function(e) {
            if (!mouseDown) return;
            e.preventDefault();
            const x = e.pageX - carousel.offsetLeft;
            const walk = (x - startX) * 2;
            carousel.scrollLeft = scrollLeft - walk;
        });

        // Show/hide navigation buttons based on scroll position
        function updateNavButtons() {
            const isAtStart = carousel.scrollLeft <= 5; // Small threshold for rounding
            const isAtEnd = carousel.scrollLeft >= carousel.scrollWidth - carousel.clientWidth - 5;

            prevBtn.style.opacity = isAtStart ? '0.5' : '1';
            prevBtn.style.pointerEvents = isAtStart ? 'none' : 'auto';

            nextBtn.style.opacity = isAtEnd ? '0.5' : '1';
            nextBtn.style.pointerEvents = isAtEnd ? 'none' : 'auto';
        }

        carousel.addEventListener('scroll', updateNavButtons);

        // Update on resize
        let resizeTimer;
        window.addEventListener('resize', function() {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(updateNavButtons, 250);
        });

        // Set initial cursor
        carousel.style.cursor = 'grab';

        updateNavButtons();

        // Auto-play functionality
        let autoPlayInterval;
        const autoPlayDelay = 3000; // 3 seconds between slides

        function startAutoPlay() {
            // Clear any existing interval
            if (autoPlayInterval) {
                clearInterval(autoPlayInterval);
            }

            autoPlayInterval = setInterval(function() {
                // Check if carousel is at the end
                const isAtEnd = carousel.scrollLeft >= carousel.scrollWidth - carousel.clientWidth - 5;

                if (isAtEnd) {
                    // Loop back to start
                    carousel.scrollTo({
                        left: 0,
                        behavior: 'smooth'
                    });
                } else {
                    // Scroll to next
                    scrollNext();
                }
            }, autoPlayDelay);
        }

        function stopAutoPlay() {
            if (autoPlayInterval) {
                clearInterval(autoPlayInterval);
                autoPlayInterval = null;
            }
        }

        // Pause auto-play on hover/interaction
        const carouselWrapper = carousel.closest('.categories-carousel-wrapper');
        if (carouselWrapper) {
            carouselWrapper.addEventListener('mouseenter', stopAutoPlay);
            carouselWrapper.addEventListener('mouseleave', startAutoPlay);
        }

        // Pause on user interaction
        carousel.addEventListener('touchstart', stopAutoPlay, { passive: true });
        carousel.addEventListener('mousedown', stopAutoPlay);

        // Resume after interaction ends (with delay)
        let interactionTimeout;
        function resumeAutoPlay() {
            clearTimeout(interactionTimeout);
            interactionTimeout = setTimeout(startAutoPlay, autoPlayDelay);
        }

        carousel.addEventListener('touchend', resumeAutoPlay, { passive: true });
        carousel.addEventListener('mouseup', resumeAutoPlay);
        carousel.addEventListener('scroll', function() {
            stopAutoPlay();
            resumeAutoPlay();
        }, { passive: true });

        // Start auto-play initially
        startAutoPlay();
    }

    // Initialize on DOM ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initCategoriesCarousel);
    } else {
        initCategoriesCarousel();
    }
})();
</script>
@endpush

@endsection

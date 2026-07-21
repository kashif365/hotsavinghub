{{-- Swiper is used by both this hero carousel and the Featured Brands carousel further
     down the page — load it unconditionally so deactivating all hero sliders (which
     skips the block below) doesn't also break the Featured Brands carousel. --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11.1.14/swiper-bundle.min.css">
<script src="https://cdn.jsdelivr.net/npm/swiper@11.1.14/swiper-bundle.min.js"></script>
@if($sliders && $sliders->count() > 0)
<section class="hsc-hero">
    <h1 class="hsc-sr-only">{{ $brandingSettings['site_name'] ?? 'Hotsavinghub' }} - Discount Codes & Voucher Codes</h1>
    <div class="hsc-viewport" data-slide-count="{{ $sliders->count() }}">
        <div class="swiper hsc-swiper">
            <div class="swiper-wrapper">
                @foreach($sliders as $index => $slider)
                    @php
                        $isRich = filled($slider->heading);
                        $hasLeft = $isRich && filled($slider->secondary_image);
                        $hasLogo = $isRich && filled($slider->logo);
                        $ctaText = $slider->cta_text ?: ($slider->cta_url ? 'Shop Now' : null);
                        $mainImgUrl = $slider->background_image
                            ? route('image.resize', ['path' => ltrim($slider->background_image, '/'), 'w' => 1200, 'h' => 500, 'q' => 85])
                            : null;
                        $leftImgUrl = $hasLeft
                            ? route('image.resize', ['path' => ltrim($slider->secondary_image, '/'), 'w' => 500, 'h' => 500, 'q' => 85])
                            : null;
                        $logoUrl = $hasLogo
                            ? route('image.resize', ['path' => ltrim($slider->logo, '/'), 'w' => 260, 'q' => 90])
                            : null;
                        $isFirst = $index === 0;
                    @endphp
                    <div class="swiper-slide">
                        <div class="hsc-card {{ $isRich ? 'hsc-card--rich' : 'hsc-card--simple' }} {{ $hasLeft ? '' : 'hsc-card--no-left' }}">
                            <div class="hsc-card-inner">
                                @if($isRich)
                                    @if($hasLeft)
                                    <div class="hsc-panel hsc-panel--left">
                                        <img src="{{ $leftImgUrl }}" alt="" width="500" height="500" {!! $isFirst ? 'loading="eager" fetchpriority="high"' : 'loading="lazy"' !!}>
                                    </div>
                                    @endif
                                    <div class="hsc-panel hsc-panel--content">
                                        @if($slider->label)<span class="hsc-label">{{ $slider->label }}</span>@endif
                                        <h2 class="hsc-heading">{{ $slider->heading }}</h2>
                                        @if($slider->subtitle)<p class="hsc-subtitle">{{ $slider->subtitle }}</p>@endif
                                        @if($ctaText)<span class="hsc-cta">{{ $ctaText }}</span>@endif
                                    </div>
                                @endif
                                <div class="hsc-panel hsc-panel--main">
                                    @if($mainImgUrl)
                                        <img src="{{ $mainImgUrl }}" alt="{{ $slider->heading ?: 'Featured offer' }}" width="1200" height="500" {!! $isFirst ? 'loading="eager" fetchpriority="high"' : 'loading="lazy"' !!}>
                                    @endif
                                </div>
                            </div>
                            @if($hasLogo)
                                <span class="hsc-badge" @if($slider->badge_color) style="--hsc-badge-color: {{ $slider->badge_color }};" @endif>
                                    <img src="{{ $logoUrl }}" alt="{{ $slider->heading }} logo" width="130" height="130" loading="lazy" onerror="this.closest('.hsc-badge').style.display='none';">
                                </span>
                            @endif
                            @if($slider->cta_url)
                                <a href="{{ $slider->cta_url }}" class="hsc-stretched-link" aria-label="{{ $slider->heading ?: ($ctaText ?: 'View offer') }}"></a>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
            <button type="button" class="hsc-nav hsc-nav--prev" aria-label="Previous slide">
                <svg viewBox="0 0 24 24" width="20" height="20" stroke="currentColor" stroke-width="2.5" fill="none" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"></polyline></svg>
            </button>
            <button type="button" class="hsc-nav hsc-nav--next" aria-label="Next slide">
                <svg viewBox="0 0 24 24" width="20" height="20" stroke="currentColor" stroke-width="2.5" fill="none" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>
            </button>
        </div>
        <div class="hsc-pagination"></div>
    </div>
</section>

<style>
    .hsc-sr-only {
        position: absolute;
        width: 1px;
        height: 1px;
        padding: 0;
        margin: -1px;
        overflow: hidden;
        clip: rect(0, 0, 0, 0);
        white-space: nowrap;
        border: 0;
    }

    .hsc-hero {
        position: relative;
        padding: 20px 0 24px;
        background: #f8f9fc;
        overflow-x: clip;
    }

    .hsc-viewport {
        position: relative;
        overflow: visible;
        max-width: var(--container-max, 1280px);
        margin-inline: auto;
    }

    .hsc-swiper {
        position: relative;
        height: 360px;
        padding: 0 24px;
        overflow: visible !important;
    }

    .hsc-card {
        position: relative;
        height: 100%;
    }

    .hsc-card-inner {
        position: relative;
        height: 100%;
        border-radius: 30px;
        overflow: hidden;
        background: #fff;
        box-shadow: 0 20px 40px -12px rgba(15, 23, 42, 0.18);
    }

    .hsc-card--rich .hsc-card-inner {
        display: grid;
        grid-template-columns: 25% 23% 52%;
    }

    .hsc-card--rich.hsc-card--no-left .hsc-card-inner {
        grid-template-columns: 34% 66%;
    }

    .hsc-card--simple .hsc-panel--main {
        height: 100%;
    }

    .hsc-panel {
        position: relative;
        min-width: 0;
        height: 100%;
    }

    .hsc-panel--left img,
    .hsc-panel--main img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }

    .hsc-panel--content {
        background: #fff;
        display: flex;
        flex-direction: column;
        justify-content: center;
        gap: 10px;
        padding: 24px 20px 76px;
    }

    .hsc-label {
        font-size: 0.7rem;
        font-weight: 800;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        color: var(--primary-color, #2951c4);
    }

    .hsc-heading {
        margin: 0;
        font-size: 1.35rem;
        line-height: 1.25;
        font-weight: 800;
        color: #0f172a;
        overflow-wrap: anywhere;
    }

    .hsc-subtitle {
        margin: 0;
        font-size: 0.9rem;
        color: #64748b;
    }

    .hsc-cta {
        margin-top: 6px;
        font-size: 0.8rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        color: var(--primary-color, #2951c4);
        text-decoration: underline;
        text-underline-offset: 4px;
        width: fit-content;
    }

    .hsc-badge {
        --hsc-badge-color: var(--primary-color, #2951c4);
        position: absolute;
        left: 25%;
        bottom: 14px;
        transform: translate(-50%, 0);
        width: 124px;
        height: 124px;
        aspect-ratio: 1 / 1;
        box-sizing: border-box;
        border-radius: 50%;
        overflow: hidden;
        background: #fff;
        border: 3px solid var(--hsc-badge-color);
        box-shadow: 0 8px 20px rgba(15, 23, 42, 0.2);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 16px;
        z-index: 5;
    }

    .hsc-card--no-left .hsc-badge {
        left: 34%;
    }

    .hsc-badge img {
        width: 100%;
        height: 100%;
        object-fit: contain;
        display: block;
    }

    .hsc-stretched-link {
        position: absolute;
        inset: 0;
        z-index: 4;
    }

    .hsc-stretched-link:focus-visible {
        outline: 3px solid var(--primary-color, #2951c4);
        outline-offset: -3px;
        border-radius: 30px;
    }

    .hsc-nav {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        z-index: 10;
        width: 46px;
        height: 46px;
        border-radius: 50%;
        border: none;
        background: #fff;
        color: #0f172a;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        box-shadow: 0 6px 16px rgba(15, 23, 42, 0.18);
        transition: transform 0.2s ease, background 0.2s ease;
    }

    .hsc-nav:hover {
        background: var(--primary-color, #2951c4);
        color: #fff;
        transform: translateY(-50%) scale(1.06);
    }

    .hsc-nav:focus-visible {
        outline: 3px solid var(--primary-color, #2951c4);
        outline-offset: 2px;
    }

    .hsc-nav.swiper-button-disabled,
    .hsc-nav:disabled {
        opacity: 0.35;
        cursor: not-allowed;
        pointer-events: none;
    }

    .hsc-nav--prev { left: 6px; }
    .hsc-nav--next { right: 6px; }

    .hsc-viewport[data-slide-count="1"] .hsc-nav,
    .hsc-viewport[data-slide-count="1"] .hsc-pagination {
        display: none;
    }

    .hsc-pagination {
        margin-top: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }

    .hsc-pagination .swiper-pagination-bullet {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: #d1d5db;
        opacity: 1;
        margin: 0;
        cursor: pointer;
    }

    .hsc-pagination .swiper-pagination-bullet:focus-visible {
        outline: 2px solid var(--primary-color, #2951c4);
        outline-offset: 2px;
    }

    .hsc-pagination .swiper-pagination-bullet-active {
        background: var(--primary-color, #2951c4);
        width: 22px;
        border-radius: 5px;
    }

    @media (prefers-reduced-motion: reduce) {
        .hsc-swiper .swiper-wrapper {
            transition-duration: 1ms !important;
        }
    }

    @media (max-width: 1023px) {
        .hsc-swiper { height: 320px; }
        .hsc-card--rich .hsc-card-inner { grid-template-columns: 27% 27% 46%; }
        .hsc-card--rich.hsc-card--no-left .hsc-card-inner { grid-template-columns: 38% 62%; }
        .hsc-badge { width: 96px; height: 96px; padding: 12px; }
        .hsc-panel--content { padding: 16px 14px 66px; gap: 6px; }
        .hsc-label { font-size: 0.62rem; }
        .hsc-heading { font-size: 1.05rem; }
        .hsc-subtitle { font-size: 0.8rem; }
    }

    @media (max-width: 767px) {
        .hsc-hero { padding: 16px 0 20px; }
        .hsc-swiper { height: 400px; padding: 0 16px; }

        .hsc-card--rich .hsc-card-inner,
        .hsc-card--rich.hsc-card--no-left .hsc-card-inner {
            display: flex;
            flex-direction: column;
            grid-template-columns: none;
        }

        .hsc-panel--left { display: none; }
        .hsc-panel--main { flex: 1 1 58%; }
        .hsc-panel--content {
            flex: 1 1 42%;
            padding: 16px;
            gap: 6px;
        }

        .hsc-heading { font-size: 1.1rem; }
        .hsc-cta { display: none; }

        .hsc-badge {
            left: 20px;
            bottom: auto;
            top: calc(58% - 46px);
            transform: none;
            width: 92px;
            height: 92px;
            padding: 10px;
        }

        .hsc-card--no-left .hsc-badge { left: 20px; }

        .hsc-nav { width: 38px; height: 38px; }
    }
</style>

<script>
    (function () {
        function initHeroCarousel() {
            var viewport = document.querySelector('.hsc-viewport');
            var el = document.querySelector('.hsc-swiper');
            if (!el || typeof Swiper === 'undefined') return;

            var slideCount = parseInt((viewport || el).getAttribute('data-slide-count'), 10) || 0;
            var multi = slideCount > 1;
            var reduceMotion = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;

            var swiper = new Swiper(el, {
                loop: multi,
                centeredSlides: true,
                watchOverflow: true,
                grabCursor: multi,
                speed: reduceMotion ? 1 : 700,
                spaceBetween: 14,
                slidesPerView: 1.05,
                keyboard: { enabled: true, onlyInViewport: true },
                a11y: { enabled: true, prevSlideMessage: 'Previous slide', nextSlideMessage: 'Next slide' },
                navigation: { nextEl: '.hsc-nav--next', prevEl: '.hsc-nav--prev' },
                pagination: { el: '.hsc-pagination', clickable: true },
                autoplay: (multi && !reduceMotion) ? { delay: 4500, disableOnInteraction: false, pauseOnMouseEnter: true } : false,
                breakpoints: {
                    640: { slidesPerView: 1.08, spaceBetween: 18 },
                    1024: { slidesPerView: 1.12, spaceBetween: 22 },
                    1280: { slidesPerView: 1.15, spaceBetween: 26 }
                }
            });

            if (multi && swiper.autoplay) {
                document.addEventListener('visibilitychange', function () {
                    if (document.hidden) {
                        swiper.autoplay.stop();
                    } else {
                        swiper.autoplay.start();
                    }
                });
            }
        }

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initHeroCarousel);
        } else {
            initHeroCarousel();
        }
    })();
</script>
@else
<section class="modern-coupon-hero">
    <!-- Background Image with Overlay -->
    <div class="hero-background">
        <img src="https://images.unsplash.com/photo-1607082348824-0a96f2a4b9da?q=80&w=2070&auto=format&fit=crop" alt="Shopping and Savings Background">
        <div class="hero-overlay"></div>
    </div>

    <!-- Main Content -->
    <div class="hero-container">

        <!-- Animated Badge -->
        <div class="deal-badge">
            <span class="pulsing-dot"></span>
            <span>Live: 500+ New Codes Added</span>
        </div>

        <!-- SEO H1 Title -->
        <h1 class="hero-headline">
            Hotsaving
            <span class="text-outline">hub.</span>
            <br>
            Save More, <span class="text-red-highlight">Shop Smart.</span>
        </h1>

        <!-- Subtext -->
        <p class="hero-subtext">
            Access thousands of verified promo codes, exclusive deals, and cashback offers for your favorite brands instantly.
        </p>

        <!-- Search Component -->
        <div class="search-wrapper">
            <form class="search-box" action="{{ route('search') }}" method="GET">
                <div class="input-group">
                    <svg class="search-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                    <input type="text" name="q" placeholder="Search stores (e.g., Nike, Amazon)..." aria-label="Search for coupons" required>
                </div>
                <button type="submit" class="btn-search">
                    Find Coupons
                </button>
            </form>
        </div>

        <!-- Trust Metrics -->
        <div class="trust-metrics">
            <div class="metric-item">
                <span class="metric-value">100%</span>
                <span class="metric-label">Verified</span>
            </div>
            <div class="metric-divider"></div>
            <div class="metric-item">
                <span class="metric-value">2M+</span>
                <span class="metric-label">Users</span>
            </div>
            <div class="metric-divider"></div>
            <div class="metric-item">
                <span class="metric-value">Zero</span>
                <span class="metric-label">Spam</span>
            </div>
        </div>
    </div>
</section>

<style>
    /* CSS Variables for Easy Theming */
    :root {
        --hero-red: #2951c4;
        --hero-red-glow: rgba(41, 81, 196, 0.4);
        --hero-dark: #0f0f0f;
        --hero-light: #ffffff;
        --hero-gray: #ffffffff;
        --font-main: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    }

    /* Reset & Base Styles */
    .modern-coupon-hero {
        position: relative;
        width: 100%;
        height: 100vh;
        height: 100svh;
        display: flex;
        align-items: center;
        justify-content: center;
        font-family: var(--font-main);
        background-color: var(--hero-dark);
        overflow: hidden;
        color: var(--hero-light);
        padding: 20px;
        box-sizing: border-box;
    }

    .modern-coupon-hero * {
        box-sizing: border-box;
    }

    /* Background Handling */
    .hero-background {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: 1;
    }

    .hero-background img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        opacity: 0;
        animation: imageFadeIn 1.5s ease-out forwards;
        transform: scale(1.05);
    }

    .hero-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(
            to bottom,
            rgba(15, 15, 15, 0.7) 0%,
            rgba(15, 15, 15, 0.9) 100%
        );
        backdrop-filter: blur(3px);
    }

    /* Content Layout */
    .hero-container {
        position: relative;
        z-index: 2;
        max-width: 1000px;
        width: 100%;
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        gap: 1.5rem;
    }

    /* Badge Styles */
    .deal-badge {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        background: rgba(41, 81, 196, 0.1);
        border: 1px solid rgba(41, 81, 196, 0.3);
        padding: 8px 16px;
        border-radius: 50px;
        font-size: 0.85rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: var(--hero-red);
        animation: slideUp 0.8s ease-out forwards;
        opacity: 0;
    }

    .pulsing-dot {
        width: 8px;
        height: 8px;
        background-color: var(--hero-red);
        border-radius: 50%;
        box-shadow: 0 0 0 0 var(--hero-red-glow);
        animation: pulseRed 2s infinite;
    }

    /* Typography */
    .hero-headline {
        font-size: clamp(2.5rem, 6vw, 5rem);
        line-height: 1.1;
        font-weight: 800;
        margin: 0;
        animation: slideUp 0.8s ease-out 0.2s forwards;
        opacity: 0;
        transform: translateY(30px);
    }

    .text-outline {
        color: transparent;
        -webkit-text-stroke: 1px rgba(255, 255, 255, 0.5);
    }

    .text-red-highlight {
        color: var(--hero-red);
        position: relative;
        display: inline-block;
    }

    /* Red Underline Effect */
    .text-red-highlight::after {
        content: '';
        position: absolute;
        left: 0;
        bottom: 5px;
        width: 100%;
        height: 0.1em;
        background: var(--hero-red);
        box-shadow: 0 0 15px var(--hero-red);
        z-index: -1;
    }

    .hero-subtext {
        font-size: clamp(1rem, 2vw, 1.2rem);
        color: var(--hero-gray);
        max-width: 600px;
        line-height: 1.6;
        margin: 0;
        animation: slideUp 0.8s ease-out 0.4s forwards;
        opacity: 0;
        transform: translateY(30px);
    }

    /* Search Box Styles */
    .search-wrapper {
        width: 100%;
        max-width: 600px;
        margin-top: 1rem;
        animation: slideUp 0.8s ease-out 0.6s forwards;
        opacity: 0;
        transform: translateY(30px);
    }

    .search-box {
        display: flex;
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
        padding: 6px;
        border-radius: 12px;
        transition: all 0.3s ease;
    }

    .search-box:focus-within {
        background: rgba(0, 0, 0, 0.6);
        border-color: var(--hero-red);
        box-shadow: 0 0 30px rgba(41, 81, 196, 0.15);
    }

    .input-group {
        flex: 1;
        display: flex;
        align-items: center;
        padding-left: 15px;
    }

    .search-icon {
        width: 20px;
        height: 20px;
        color: var(--hero-gray);
        margin-right: 10px;
    }

    .search-box input {
        width: 100%;
        background: transparent;
        border: none;
        color: white;
        font-size: 1rem;
        padding: 15px 0;
        outline: none;
    }

    .btn-search {
        background: var(--hero-red);
        color: white;
        border: none;
        padding: 0 30px;
        border-radius: 8px;
        font-weight: 700;
        font-size: 1rem;
        cursor: pointer;
        transition: transform 0.2s, background 0.2s;
        white-space: nowrap;
    }

    .btn-search:hover {
        background: #2563eb;
        transform: scale(1.02);
    }

    /* Trust Metrics */
    .trust-metrics {
        display: flex;
        align-items: center;
        gap: 1.5rem;
        margin-top: 2rem;
        animation: fadeIn 1s ease-out 1s forwards;
        opacity: 0;
    }

    .metric-item {
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .metric-value {
        font-weight: 700;
        font-size: 1.25rem;
        color: white;
    }

    .metric-label {
        font-size: 0.75rem;
        color: var(--hero-gray);
        text-transform: uppercase;
    }

    .metric-divider {
        width: 1px;
        height: 30px;
        background: rgba(255, 255, 255, 0.1);
    }

    /* Animations */
    @keyframes imageFadeIn {
        to { opacity: 0.6; }
    }

    @keyframes slideUp {
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes fadeIn {
        to { opacity: 1; }
    }

    @keyframes pulseRed {
        0% { transform: scale(0.95); box-shadow: 0 0 0 0 var(--hero-red-glow); }
        70% { transform: scale(1); box-shadow: 0 0 0 6px rgba(41, 81, 196, 0); }
        100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(41, 81, 196, 0); }
    }

    /* Mobile Responsive Adjustments */
    @media (max-width: 768px) {
        .search-box {
            flex-direction: column;
            background: transparent;
            border: none;
            gap: 10px;
            padding: 0;
        }

        .input-group {
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 8px;
            width: 100%;
        }

        .btn-search {
            width: 100%;
            padding: 16px;
        }

        .trust-metrics {
            gap: 1rem;
        }
    }
</style>
@endif

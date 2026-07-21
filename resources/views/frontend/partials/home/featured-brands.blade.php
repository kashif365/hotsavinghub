<div class="featured-stores-section hsr-section">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">
                <span class="title-normal">Featured</span>
                <span class="title-highlight">Brands</span>
            </h2>
            <p class="section-subtitle">Explore amazing deals from your favorite top-rated brands</p>
        </div>
        @if(($featuredStores ?? collect())->count() > 0)
            <div class="swiper fs-swiper">
                <div class="swiper-wrapper">
                    @foreach($featuredStores as $store)
                        @php
                            $firstCoupon = $store->coupons()->where('status', 1)->orderBy('sort_order', 'asc')->first();
                            $offerText = $firstCoupon ? ($firstCoupon->coupon_title ?? 'Save ' . rand(20, 70) . '% Off') : 'Save ' . rand(20, 70) . '% Off';
                            if (strlen($offerText) > 22) {
                                $offerText = substr($offerText, 0, 20) . '...';
                            }
                        @endphp
                        <div class="swiper-slide">
                            <div class="featured-store-card">
                                <a href="{{ route('store', $store->seo_url) }}" class="featured-store-link">
                                    <div class="featured-store-logo-area">
                                        @if($store->store_logo && file_exists(public_path(ltrim($store->store_logo, '/'))))
                                            @php
                                                $logoPath = ltrim($store->store_logo, '/');
                                                if (!str_starts_with($logoPath, 'uploads/')) {
                                                    $logoPath = 'uploads/' . $logoPath;
                                                }
                                                $resizedUrl2x = route('image.resize', ['path' => $logoPath, 'w' => 120, 'q' => 80]);
                                            @endphp
                                            <img src="{{ $resizedUrl2x }}"
                                                 alt="{{ $store->short_name }}"
                                                 class="featured-store-logo"
                                                 width="120"
                                                 height="60"
                                                 loading="lazy"
                                                 onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                            <div class="featured-store-logo-placeholder" style="display: none !important;">
                                                <span>{{ strtoupper(substr($store->short_name, 0, 2)) }}</span>
                                            </div>
                                        @else
                                            <div class="featured-store-logo-placeholder">
                                                <span>{{ strtoupper(substr($store->short_name, 0, 2)) }}</span>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="featured-store-name">{{ $store->short_name }}</div>
                                    <div class="featured-store-offers-btn">{{ $offerText }}</div>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
                <button type="button" class="fs-nav fs-nav--prev" aria-label="Previous stores">
                    <svg viewBox="0 0 24 24" width="18" height="18" stroke="currentColor" stroke-width="2.5" fill="none" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"></polyline></svg>
                </button>
                <button type="button" class="fs-nav fs-nav--next" aria-label="Next stores">
                    <svg viewBox="0 0 24 24" width="18" height="18" stroke="currentColor" stroke-width="2.5" fill="none" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>
                </button>
            </div>
        @else
            <div class="no-stores-message">
                <div class="no-stores-icon">
                    <svg width="64" height="64" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M3 7V5C3 3.89543 3.89543 3 5 3H19C20.1046 3 21 3.89543 21 5V7" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                        <path d="M3 7L5 21H19L21 7" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                        <path d="M8 11H16" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                </div>
                <h3>No Featured Stores</h3>
                <p>We're working on adding amazing stores for you!</p>
            </div>
        @endif
    </div>
</div>

<style>
.featured-stores-section { background-color: #f8f9fc; font-family: 'Inter', system-ui, -apple-system, sans-serif; }
.featured-stores-section .container { max-width: var(--container-max, 1280px); margin: 0 auto; padding: 0 24px; width: 100%; box-sizing: border-box; }
.section-header { text-align: center; margin-bottom: 2.5rem; position: relative; }
.section-title { font-size: 2rem; font-weight: 800; letter-spacing: -0.03em; color: #1a1f2c; margin: 0 0 .75rem 0; line-height: 1.2; }
.title-highlight { color: var(--primary-color); position: relative; display: inline-block; }
.title-highlight::after { content: ''; position: absolute; bottom: 2px; left: 0; width: 100%; height: 8px; background: var(--primary-color); opacity: .15; z-index: -1; border-radius: 4px; }
.section-subtitle { font-size: 1.05rem; color: #64748b; max-width: 600px; margin: 0 auto; line-height: 1.6; }

.fs-swiper { position: relative; height: 270px; overflow: hidden; padding: 6px 4px 12px; }
.fs-swiper .swiper-wrapper { height: 100%; }
.fs-swiper .swiper-slide { height: auto; }

.featured-store-card { background: #fff; border-radius: var(--radius-card, 16px); overflow: visible; position: relative; transition: all .35s cubic-bezier(.175,.885,.32,1.275); box-shadow: var(--shadow-card, 0 4px 16px rgba(15,23,42,.06)); border: 1px solid rgba(0,0,0,.04); height: 100%; display: flex; flex-direction: column; }
.featured-store-card:hover { transform: translateY(-8px); box-shadow: var(--shadow-card-hover, 0 12px 28px rgba(15,23,42,.1)); border-color: rgba(var(--primary-color-rgb,41,81,196),.2); z-index: 2; }
.featured-store-link { display: flex; flex-direction: column; align-items: center; padding: 22px 18px; text-decoration: none; height: 100%; box-sizing: border-box; color: inherit; }
.featured-store-link:focus-visible { outline: 3px solid var(--primary-color, #2951c4); outline-offset: 2px; border-radius: var(--radius-card, 16px); }
.featured-store-logo-area { width: 84px; height: 84px; display: flex; align-items: center; justify-content: center; margin-bottom: 1rem; background: #fff; border-radius: 50%; padding: 14px; box-shadow: 0 4px 12px rgba(0,0,0,.05); border: 1px solid #f1f5f9; position: relative; }
.featured-store-logo { max-width: 100%; max-height: 100%; object-fit: contain; }
.featured-store-logo-placeholder { width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; background: #f8f9fa; border-radius: 50%; color: #94a3b8; font-weight: 700; font-size: 1.4rem; }
.featured-store-name { font-size: .98rem; font-weight: 700; color: #334155; margin-bottom: .85rem; text-align: center; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; width: 100%; }
.featured-store-offers-btn { margin-top: auto; background: rgba(var(--primary-color-rgb,41,81,196),.08); color: var(--primary-color); font-size: .78rem; font-weight: 700; padding: 8px 14px; border-radius: var(--radius-pill, 999px); width: 100%; text-align: center; transition: all .3s ease; border: 1px solid transparent; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.featured-store-card:hover .featured-store-offers-btn { background: var(--primary-color); color: #fff; box-shadow: 0 4px 12px rgba(var(--primary-color-rgb,41,81,196),.3); }

.no-stores-message { text-align: center; padding: 4rem 1.5rem; background: #fff; border-radius: var(--radius-lg, 20px); box-shadow: var(--shadow-card, 0 4px 16px rgba(15,23,42,.06)); }
.no-stores-icon { color: var(--primary-color); margin-bottom: 1.25rem; opacity: .8; }
.no-stores-message h3 { font-size: 1.4rem; font-weight: 700; color: #1e293b; margin-bottom: .5rem; }
.no-stores-message p { color: #64748b; font-size: 1rem; }

.fs-nav { position: absolute; top: 50%; transform: translateY(-50%); z-index: 10; width: 40px; height: 40px; border-radius: 50%; border: none; background: #fff; color: #0f172a; display: flex; align-items: center; justify-content: center; cursor: pointer; box-shadow: 0 6px 16px rgba(15,23,42,.15); transition: transform .2s ease, background .2s ease; }
.fs-nav:hover { background: var(--primary-color, #2951c4); color: #fff; transform: translateY(-50%) scale(1.06); }
.fs-nav:focus-visible { outline: 3px solid var(--primary-color, #2951c4); outline-offset: 2px; }
.fs-nav.swiper-button-disabled { opacity: .35; cursor: not-allowed; pointer-events: none; }
.fs-nav--prev { left: -8px; }
.fs-nav--next { right: -8px; }

@media (max-width: 1024px) {
    .fs-swiper { height: 250px; }
}

@media (max-width: 768px) {
    .section-title { font-size: 1.65rem; }
    .fs-swiper { height: 230px; }
    .fs-nav { width: 34px; height: 34px; }
    .fs-nav--prev { left: -4px; }
    .fs-nav--next { right: -4px; }
    .featured-store-link { padding: 18px 14px; }
    .featured-store-logo-area { width: 68px; height: 68px; padding: 10px; }
}

@media (max-width: 480px) {
    .fs-swiper { height: 220px; }
    .featured-store-name { font-size: .88rem; }
    .featured-store-offers-btn { font-size: .72rem; padding: 6px 12px; }
}
</style>

<script>
    (function () {
        function initFeaturedStoresCarousel() {
            var el = document.querySelector('.fs-swiper');
            if (!el || typeof Swiper === 'undefined') return;

            var slideCount = el.querySelectorAll('.swiper-slide').length;
            var multi = slideCount > 1;
            var reduceMotion = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;

            var swiper = new Swiper(el, {
                loop: multi,
                grabCursor: multi,
                speed: reduceMotion ? 1 : 600,
                spaceBetween: 16,
                slidesPerView: 2,
                keyboard: { enabled: true, onlyInViewport: true },
                a11y: { enabled: true, prevSlideMessage: 'Previous stores', nextSlideMessage: 'Next stores' },
                navigation: { nextEl: '.fs-nav--next', prevEl: '.fs-nav--prev' },
                autoplay: (multi && !reduceMotion) ? { delay: 2200, disableOnInteraction: false, pauseOnMouseEnter: true } : false,
                breakpoints: {
                    480: { slidesPerView: 3, spaceBetween: 16 },
                    768: { slidesPerView: 4, spaceBetween: 20 },
                    1024: { slidesPerView: 5, spaceBetween: 24 }
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
            document.addEventListener('DOMContentLoaded', initFeaturedStoresCarousel);
        } else {
            initFeaturedStoresCarousel();
        }
    })();
</script>

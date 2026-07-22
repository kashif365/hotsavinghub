@if(($spotlightCards ?? collect())->count() > 0)
<section class="spotlight-section hsr-section container" style="margin-top:32px;">
    <div class="hsr-container">
        <div class="spotlight-header">
            <span class="hsr-eyebrow">Top Deals</span>
            <h2 class="hsr-title">The Best Coupons, <span class="highlight-text">Promo Codes &amp; Cash Back Offers</span></h2>
        </div>

        <div class="swiper spotlight-swiper">
            <div class="swiper-wrapper">
                @foreach($spotlightCards as $card)
                    @php
                        $isLink = filled($card->cta_url);
                        $tag = $isLink ? 'a' : 'div';
                    @endphp
                    <div class="swiper-slide">
                        <{{ $tag }} @if($isLink) href="{{ $card->cta_url }}" @endif class="spotlight-card">
                            <div class="spotlight-card-media" style="background-color: {{ $card->bg_color ?: '#eef1f8' }};">
                                @if($card->image)
                                    <img class="spotlight-photo" src="{{ asset($card->image) }}" alt="{{ $card->heading ?: 'Spotlight offer' }}" loading="lazy">
                                    @if($card->logo)
                                        <span class="spotlight-card-logo spotlight-card-logo--badge">
                                            <img src="{{ asset($card->logo) }}" alt="" loading="lazy">
                                        </span>
                                    @endif
                                @elseif($card->logo)
                                    <span class="spotlight-card-logo spotlight-card-logo--center">
                                        <img src="{{ asset($card->logo) }}" alt="" loading="lazy">
                                    </span>
                                @endif
                            </div>

                            <div class="spotlight-card-body">
                                @if($card->heading)
                                    <p class="spotlight-card-heading">{{ $card->heading }}</p>
                                @endif
                                @if($card->cta_label)
                                    <span class="spotlight-card-cta">
                                        {{ $card->cta_label }}
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                                    </span>
                                @endif
                            </div>
                        </{{ $tag }}>
                    </div>
                @endforeach
            </div>
        </div>
        <div class="spotlight-pagination"></div>
    </div>
</section>

<style>
.spotlight-section {
    background: #fff;
}

.spotlight-header {
    margin-bottom: 1.75rem;
}

.spotlight-swiper {
    height: 300px;
    overflow: visible;
    padding-bottom: 4px;
}
.hsr-title{
    color: var(--dark-void);
}
.highlight-text,
.hsr-eyebrow{
    color: var(--primary-red);
}
.highlight-text,
.hsr-eyebrow,
.hsr-title{
font-weight: 800;
}
.spotlight-swiper .swiper-wrapper {
    height: 100%;
}

.spotlight-swiper .swiper-slide {
    height: auto;
}

.spotlight-card {
    position: relative;
    display: flex;
    flex-direction: column;
    height:auto;
    background: #fff;
    border-radius: var(--radius-card, 16px);
    overflow: hidden;
    text-decoration: none;
    border: 1px solid var(--hsr-border, #e7eaf0);
    box-shadow: var(--shadow-card, 0 4px 16px rgba(15,23,42,.06));
    transition: transform .3s cubic-bezier(.2,1,.3,1), box-shadow .3s ease, border-color .3s ease;
}

.spotlight-card:hover {
    transform: translateY(-6px);
    box-shadow: var(--shadow-card-hover, 0 12px 28px rgba(15,23,42,.1));
    border-color: rgba(var(--primary-color-rgb, 41, 81, 196), .25);
}

.spotlight-card-media {
    position: relative;
    height: 150px;
    flex-shrink: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
}

.spotlight-photo {
    position: absolute;
    inset: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
}

.spotlight-card-logo {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    background: #fff;
    border-radius: 10px;
}

.spotlight-card-logo--badge {
    position: absolute;
    top: 12px;
    left: 12px;
    z-index: 2;
    padding: 6px 10px;
    max-width: 92px;
    max-height: 38px;
    box-shadow: 0 4px 10px rgba(15,23,42,.15);
    border-radius:50%;
}

.spotlight-card-logo--badge img {
    max-width: 100%;
    max-height: 26px;
    object-fit: contain;
    display: block;
}

.spotlight-card-logo--center {
    padding: 10px 18px;
    max-width: 70%;
    box-shadow: 0 4px 10px rgba(15,23,42,.08);
}

.spotlight-card-logo--center img {
    max-width: 100%;
    max-height: 44px;
    object-fit: contain;
    display: block;
}

.spotlight-card-body {
    padding: 18px 20px 20px 20px;
    display: flex;
    flex-direction: column;
    flex-grow: 1;
}

.spotlight-card-heading {
    font-weight: 700;
    font-size: 1rem;
    line-height: 1.45;
    color: var(--hsr-text, #0f172a);
    margin: 0 0 14px;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    min-height: 1.9em;
}

.spotlight-card-cta {
    margin-top: auto;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    width: fit-content;
    color: var(--primary-color, #2951c4);
    font-weight: 800;
    font-size: .78rem;
    letter-spacing: .04em;
    text-transform: uppercase;
    text-decoration: underline;
    text-underline-offset: 3px;
}

.spotlight-card-cta svg {
    transition: transform .25s ease;
}

.spotlight-card:hover .spotlight-card-cta svg {
    transform: translateX(3px);
}

.spotlight-card:focus-visible {
    outline: 3px solid var(--primary-color, #2951c4);
    outline-offset: 3px;
}

.spotlight-pagination {
    margin-top: 22px;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
}

.spotlight-pagination .swiper-pagination-bullet {
    width: 24px;
    height: 4px;
    border-radius: 3px;
    background: #e2e8f0;
    opacity: 1;
    margin: 0;
    cursor: pointer;
    transition: all .25s ease;
}

.spotlight-pagination .swiper-pagination-bullet:hover {
    background: #cbd5e1;
}

.spotlight-pagination .swiper-pagination-bullet-active {
    background: var(--primary-color, #2951c4);
    width: 36px;
}

.spotlight-pagination.swiper-pagination-lock {
    display: none;
}

@media (max-width: 768px) {
    .spotlight-header { margin-bottom: 1.25rem; }
    .spotlight-swiper { height: 280px; }
}
</style>

<script>
    (function () {
        function initSpotlightCarousel() {
            var el = document.querySelector('.spotlight-swiper');
            if (!el || typeof Swiper === 'undefined') return;

            new Swiper(el, {
                grabCursor: true,
                watchOverflow: true,
                slidesPerView: 1.15,
                spaceBetween: 16,
                speed: 500,
                keyboard: { enabled: true, onlyInViewport: true },
                a11y: { enabled: true, prevSlideMessage: 'Previous offer', nextSlideMessage: 'Next offer' },
                pagination: { el: '.spotlight-pagination', clickable: true },
                breakpoints: {
                    640: { slidesPerView: 2, spaceBetween: 18 },
                    1024: { slidesPerView: 3, spaceBetween: 24 }
                }
            });
        }

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initSpotlightCarousel);
        } else {
            initSpotlightCarousel();
        }
    })();
</script>
@endif

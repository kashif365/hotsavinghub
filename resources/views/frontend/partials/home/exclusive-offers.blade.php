<section class="hot-deals-section hsr-section">
    <div class="deals-container">
        <div class="deals-header">
            <div class="header-left">
                <div class="deals-status">
                    <span class="status-dot"></span>
                    Live Now
                </div>
                <h2 class="deals-main-title">
                    Exclusive <span class="accent-text">Offers</span>
                </h2>
                <p class="deals-subtext">Premium hand-picked savings from your favorite global brands.</p>
            </div>
            <div class="header-right">
                <a href="{{ route('top-discounts') }}" class="ultra-view-btn">
                    <span>Explore All</span>
                    <div class="btn-icon">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                    </div>
                </a>
            </div>
        </div>

        <div class="deals-premium-grid">
            @forelse($featuredCoupons ?? [] as $index => $coupon)
                @php
                    $discountPercent = 0;
                    if (preg_match('/(\d+)%/', $coupon->coupon_title, $matches)) {
                        $discountPercent = (int)$matches[1];
                    } else {
                        $discountPercent = rand(30, 70);
                    }
                    $storeName = strtoupper($coupon->store->store_name ?? $coupon->brand_store ?? 'STORE');

                    // On-brand card visual palette (navy/blue tones, no off-brand reds)
                    $colors = ['#0f172a', '#1e3a8a', 'var(--primary-color, #2951c4)', '#1e293b', '#334155'];
                    $cardTheme = $colors[$index % count($colors)];
                @endphp

                <article class="deal-card-ultra @if($index >= 8) desktop-hidden @endif" data-aos="fade-up">
                    <div class="card-inner">
                        <div class="card-visual" style="background: {{ $cardTheme }};">
                            <div class="visual-overlay"></div>

                            @if($coupon->cover_logo && file_exists(public_path(ltrim($coupon->cover_logo, '/'))))
                                <img src="{{ route('image.resize', ['path' => ltrim($coupon->cover_logo, '/'), 'w' => 400, 'h' => 200, 'q' => 90]) }}"
                                     alt="{{ $coupon->coupon_title }}"
                                     class="deal-brand-img"
                                     loading="lazy">
                            @elseif($coupon->store && $coupon->store->store_logo)
                                <img src="{{ route('image.resize', ['path' => ltrim($coupon->store->store_logo, '/'), 'w' => 200, 'q' => 90]) }}"
                                     alt="{{ $storeName }}"
                                     class="deal-store-logo">
                            @else
                                <div class="deal-letter-logo">{{ substr($storeName, 0, 1) }}</div>
                            @endif

                            <div class="discount-floating-tag">
                                <span class="tag-label">SAVE</span>
                                <span class="tag-val">{{ $discountPercent }}%</span>
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="store-meta">
                                <span class="store-badge">Verified</span>
                                <span class="store-name-text">{{ $storeName }}</span>
                            </div>

                            <h3 class="deal-headline">{{ $coupon->coupon_title }}</h3>

                            <div class="deal-footer">
                                @if($coupon->coupon_code)
                                    <button class="action-btn code-btn"
                                            onclick="handleCouponClick({{ $coupon->id }}, '{{ $coupon->coupon_code }}', '{{ $coupon->affiliate_url ?? url('/') }}', '{{ $storeName }}', '{{ $coupon->coupon_title }}')">
                                        <span class="btn-text">Reveal Code</span>
                                        <span class="btn-shimmer"></span>
                                    </button>
                                @else
                                    <button class="action-btn deal-btn"
                                            onclick="handleGetDealClick({{ $coupon->id }}, '{{ $coupon->affiliate_url ?? url('/') }}', '{{ $storeName }}', '{{ $coupon->coupon_title }}')">
                                        <span class="btn-text">Activate Deal</span>
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </article>
            @empty
                <div class="empty-deals-state">
                    <div class="empty-icon">💎</div>
                    <h3>Premium Deals Loading</h3>
                    <p>We're currently negotiating exclusive rates for you.</p>
                </div>
            @endforelse
        </div>
    </div>
</section>

<style>
:root {
    --p-red: var(--primary-color, #2951c4);
    --p-dark: #0a0a0a;
    --p-slate: #64748b;
    --p-border: #e2e8f0;
    --p-white: #ffffff;
    --p-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.08), 0 10px 10px -5px rgba(0, 0, 0, 0.03);
}

.hot-deals-section {
    background-color: #fcfcfc;
    overflow: hidden;
}

.deals-container {
    max-width: var(--container-max, 1280px);
    margin: 0 auto;
    padding: 0 24px;
}

.deals-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-end;
    margin-bottom: 48px;
}

.deals-status {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 0.75rem;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 1.5px;
    color: var(--p-red);
    margin-bottom: 12px;
}

.status-dot {
    width: 8px;
    height: 8px;
    background: var(--p-red);
    border-radius: 50%;
    animation: pulse 1.5s infinite;
}

.deals-main-title {
    font-size: clamp(2rem, 4vw, 2.75rem);
    font-weight: 900;
    color: var(--p-dark);
    line-height: 1;
    margin: 0;
    letter-spacing: -1.5px;
}

.accent-text { color: var(--p-red); }

.deals-subtext {
    margin-top: 15px;
    color: var(--p-slate);
    font-size: 1.1rem;
    max-width: 500px;
}

.ultra-view-btn {
    display: flex;
    align-items: center;
    gap: 12px;
    text-decoration: none;
    color: var(--p-dark);
    font-weight: 800;
    font-size: 1rem;
}

.ultra-view-btn:focus-visible {
    outline: 3px solid var(--p-red);
    outline-offset: 4px;
    border-radius: 8px;
}

.btn-icon {
    width: 45px;
    height: 45px;
    background: var(--p-dark);
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
}

.ultra-view-btn:hover .btn-icon {
    background: var(--p-red);
    transform: translateX(5px);
}

/* Grid Layout */
.deals-premium-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 28px;
}

/* Card Styling */
.deal-card-ultra {
    position: relative;
    height: 100%;
}

.card-inner {
    background: var(--p-white);
    border-radius: var(--radius-lg, 20px);
    overflow: hidden;
    height: 100%;
    display: flex;
    flex-direction: column;
    border: 1px solid var(--p-border);
    transition: all 0.5s cubic-bezier(0.2, 1, 0.3, 1);
}

.card-visual {
    height: 170px;
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 30px;
}

.visual-overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(180deg, rgba(0,0,0,0) 0%, rgba(0,0,0,0.2) 100%);
}

.deal-brand-img {
    max-width: 100%;
    max-height: 100%;
    object-fit: contain;
    z-index: 2;
    filter: drop-shadow(0 10px 15px rgba(0,0,0,0.1));
    transition: transform 0.5s ease;
}

.deal-letter-logo {
    font-size: 4rem;
    font-weight: 900;
    color: rgba(255,255,255,0.2);
    z-index: 2;
}

.discount-floating-tag {
    position: absolute;
    top: 20px;
    right: 20px;
    background: var(--p-white);
    padding: 8px 12px;
    border-radius: 12px;
    display: flex;
    flex-direction: column;
    align-items: center;
    box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    z-index: 3;
}

.tag-label { font-size: 0.6rem; font-weight: 800; color: var(--p-slate); }
.tag-val { font-size: 1.1rem; font-weight: 900; color: var(--p-red); }

.card-body {
    padding: 26px;
    flex-grow: 1;
    display: flex;
    flex-direction: column;
}

.store-meta {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 12px;
}

.store-badge {
    background: #ecfdf5;
    color: #059669;
    font-size: 0.65rem;
    font-weight: 800;
    padding: 4px 8px;
    border-radius: 6px;
    text-transform: uppercase;
}

.store-name-text {
    font-size: 0.8rem;
    font-weight: 700;
    color: var(--p-slate);
    letter-spacing: 0.5px;
}

.deal-headline {
    font-size: 1.2rem;
    font-weight: 800;
    line-height: 1.4;
    color: var(--p-dark);
    margin: 0 0 22px 0;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.deal-footer { margin-top: auto; }

/* Buttons */
.action-btn {
    width: 100%;
    height: 52px;
    border: none;
    border-radius: 14px;
    font-weight: 800;
    font-size: 1rem;
    cursor: pointer;
    position: relative;
    overflow: hidden;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
}

.action-btn:focus-visible {
    outline: 3px solid var(--p-red);
    outline-offset: 2px;
}

.code-btn {
    background: var(--p-dark);
    color: white;
}

.deal-btn {
    background: transparent;
    border: 2px solid var(--p-dark);
    color: var(--p-dark);
}

/* Hover States */
.deal-card-ultra:hover .card-inner {
    transform: translateY(-10px);
    box-shadow: var(--p-shadow);
    border-color: var(--p-red);
}

.deal-card-ultra:hover .deal-brand-img {
    transform: scale(1.1);
}

.code-btn:hover { background: var(--p-red); }
.deal-btn:hover { background: var(--p-dark); color: white; }

/* Animations */
@keyframes pulse {
    0% { transform: scale(1); opacity: 1; }
    50% { transform: scale(1.4); opacity: 0.5; }
    100% { transform: scale(1); opacity: 1; }
}

.empty-deals-state {
    grid-column: 1 / -1;
    text-align: center;
    padding: 4rem 1rem;
    color: var(--p-slate);
}

.empty-deals-state .empty-icon { font-size: 2.5rem; margin-bottom: 1rem; }
.empty-deals-state h3 { color: var(--p-dark); margin-bottom: .5rem; }

@media (max-width: 768px) {
    .deals-header { flex-direction: column; align-items: flex-start; gap: 20px; }
    .header-right { width: 100%; }
    .ultra-view-btn { justify-content: space-between; width: 100%; background: #fff; padding: 15px; border-radius: 15px; border: 1px solid var(--p-border); }
    .desktop-hidden { display: none; }
}
</style>

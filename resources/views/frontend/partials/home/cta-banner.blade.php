<section class="cta-banner-section hsr-section">
    <div class="hsr-container">
        <div class="cta-banner">
            <div class="cta-banner-glow"></div>
            <div class="cta-banner-content">
                <h2 class="cta-banner-title">
                    Start <span class="cta-accent">Saving Today</span>
                </h2>
                <p class="cta-banner-text">
                    Now's the time to start saving money. Discover {{ $brandingSettings['site_name'] ?? 'Hot Saving Hub' }} and get access to thousands of
                    coupon codes, best deals and exclusive offers for savvy shoppers.
                </p>
                <p class="cta-banner-subtext">
                    With free coupon codes, exclusive voucher deals, and more, every offer is designed to save you
                    money. Whether you're looking for everyday essentials or high-end brands, {{ $brandingSettings['site_name'] ?? 'Hot Saving Hub' }} has
                    verified savings. Be part of the many savvy shoppers who turn to us for regular
                    <strong>offer codes</strong> and great shopping deals.
                </p>
                <a href="{{ route('top-discounts') }}" class="cta-banner-btn">
                    <span>Explore All Deals</span>
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                </a>
            </div>
        </div>
    </div>
</section>

<style>
.cta-banner-section {
    padding-top: 0;
}

.cta-banner {
    position: relative;
    overflow: hidden;
    border-radius: var(--radius-lg, 20px);
    background: linear-gradient(135deg, #0f172a 0%, #111c35 55%, #0f172a 100%);
    padding: 4rem 2rem;
    text-align: center;
    box-shadow: var(--shadow-card-hover, 0 12px 28px rgba(15,23,42,.1));
}

.cta-banner-glow {
    position: absolute;
    inset: -40% -10%;
    background: radial-gradient(circle at 50% 30%, rgba(var(--primary-color-rgb, 41, 81, 196), .35) 0%, rgba(var(--primary-color-rgb, 41, 81, 196), 0) 60%);
    pointer-events: none;
}

.cta-banner-content {
    position: relative;
    z-index: 1;
    max-width: 760px;
    margin: 0 auto;
}

.cta-banner-title {
    margin: 0 0 1.25rem;
    font-size: clamp(1.9rem, 4vw, 2.6rem);
    font-weight: 800;
    color: #fff;
    letter-spacing: -0.02em;
}

.cta-accent {
    color: var(--primary-color, #2951c4);
}

.cta-banner-text {
    margin: 0 auto 1rem;
    color: #e2e8f0;
    font-size: 1.05rem;
    line-height: 1.7;
    max-width: 640px;
}

.cta-banner-text strong {
    color: #fff;
}

.cta-banner-subtext {
    margin: 0 auto 2rem;
    color: #94a3b8;
    font-size: 0.92rem;
    font-style: italic;
    line-height: 1.7;
    max-width: 640px;
}

.cta-banner-subtext strong {
    color: #cbd5e1;
    font-style: normal;
}

.cta-banner-btn {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    padding: 14px 32px;
    border-radius: var(--radius-pill, 999px);
    background: var(--primary-color, #2951c4);
    color: #fff;
    font-weight: 700;
    font-size: 1rem;
    text-decoration: none;
    transition: all 0.25s ease;
    box-shadow: 0 12px 24px rgba(var(--primary-color-rgb, 41, 81, 196), .35);
}

.cta-banner-btn:hover {
    filter: brightness(1.1);
    transform: translateY(-2px);
}

.cta-banner-btn:focus-visible {
    outline: 3px solid #fff;
    outline-offset: 3px;
}

@media (max-width: 768px) {
    .cta-banner { padding: 3rem 1.5rem; border-radius: 16px; }
    .cta-banner-text, .cta-banner-subtext { font-size: 0.95rem; }
}
</style>

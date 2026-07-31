<div class="hsg-container">
    <header class="hsg-intro">
            <span class="hsg-eyebrow">Shop smarter with Hot Saving Hub</span>
            <h2 id="hsg-guide-title">Your Guide to Smarter Online Savings</h2>
            <p>From clothes and electronics to home essentials and travel, online shopping has become the go-to choice for many. Convenience matters, but savvy shoppers also want confidence that they are reducing their total spend. <span class="decent-color">Hot Saving Hub</span> brings trusted discounts, seasonal promotions and exclusive retailer offers together in one place.</p>
        </header>

        <div class="hsg-feature-grid">
            <article class="hsg-feature hsg-feature--primary">
                <span class="hsg-number">01</span>
                <div>
                    <h3>Find Verified Coupons and Deals in One Place</h3>
                    <p>Online shoppers want assurance before they buy. Nobody wants to waste time on outdated promotions or codes that do not work, so we organise offers to make dependable savings easier to find.</p>
                    <p>Browse discounts across fashion, electronics, beauty, software subscriptions, travel, home improvement and everyday essentials. Comparing offers before purchasing can reveal seasonal discounts, member-only promotions and free-delivery campaigns.</p>
                </div>
            </article>

            <article class="hsg-feature">
                <span class="hsg-number">02</span>
                <div>
                    <h3>Discover Promo Codes Faster</h3>
                    <p>Searching for discounts by hand can be tedious, especially when many websites still show expired promotions. Our coupon finder groups current retailer offers by brand, category and popularity.</p>
                    <p>Compare several stores without opening multiple browser windows. A convenient promo code finder helps save both time and money before checkout.</p>
                </div>
            </article>

            <article class="hsg-feature">
                <span class="hsg-number">03</span>
                <div>
                    <h3>Save More with Free Coupon Codes</h3>
                    <p>Welcome offers, seasonal promotions, newsletter deals and member discounts give shoppers many ways to save. A genuine free coupon code can unlock percentage, fixed-price, bundle or event discounts without a membership fee.</p>
                    <p>Remember to check for offers when ordering food too; even a small discount can improve the experience and help keep spending on budget.</p>
                </div>
            </article>
        </div>
</div>


    

<div class="modern-categories-section hsr-section">
    <span class="cat-orb cat-orb--1" aria-hidden="true"></span>
    <span class="cat-orb cat-orb--2" aria-hidden="true"></span>
    <div class="cat-container">
        <div class="cat-header">
            <div class="cat-title-group">
                <span class="cat-subtitle"><span class="cat-subtitle-dot"></span>DISCOVER</span>
                <h2 class="cat-title">
                    Top <span class="highlight-text">Categories</span>
                </h2>
                <p class="cat-subtext">Jump straight into the departments with the best live savings right now.</p>
            </div>
            <div class="cat-controls">
                <button class="cat-nav-btn prev-btn" id="catPrev" aria-label="Previous">
                    <svg viewBox="0 0 24 24" width="20" height="20" stroke="currentColor" stroke-width="2.5" fill="none" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"></polyline></svg>
                </button>
                <button class="cat-nav-btn next-btn" id="catNext" aria-label="Next">
                    <svg viewBox="0 0 24 24" width="20" height="20" stroke="currentColor" stroke-width="2.5" fill="none" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>
                </button>
            </div>
        </div>

        <div class="cat-track-wrapper">
            <div class="cat-track" id="categoryTrack">
                @php
                    $catAccents = ['#2951c4', '#7c3aed', '#0891b2', '#d97706', '#db2777', '#059669'];
                @endphp
                @forelse($categories ?? [] as $index => $category)
                    @php $accent = $catAccents[$index % count($catAccents)]; @endphp
                    <a href="{{ route('category', $category->seo_url) }}" class="cat-card" style="--cat-accent: {{ $accent }};">
                        <span class="cat-card-glow"></span>
                        <div class="cat-glass-bg"></div>
                        <span class="cat-index">{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</span>
                        <div class="cat-content">
                            <div class="cat-icon-wrapper">
                                <div class="cat-icon-blob"></div>
                                @if($category->media && file_exists(public_path(ltrim($category->media, '/'))))
                                    <img src="{{ asset(ltrim($category->media, '/')) }}" alt="{{ $category->category_name }}" class="cat-img" loading="lazy" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                    <div class="cat-placeholder" style="display: none;">
                                        <span>{{ substr($category->category_name, 0, 1) }}</span>
                                    </div>
                                @else
                                    <div class="cat-placeholder">
                                        <span>{{ substr($category->category_name, 0, 1) }}</span>
                                    </div>
                                @endif
                            </div>
                            <h3 class="cat-name">{{ $category->category_name }}</h3>
                            @if(isset($category->stores_count) && $category->stores_count > 0)
                                <span class="cat-count">{{ $category->stores_count }} {{ $category->stores_count == 1 ? 'Store' : 'Stores' }}</span>
                            @endif
                            <div class="cat-action">
                                <span>Browse</span>
                                <svg viewBox="0 0 24 24" width="14" height="14" stroke="currentColor" stroke-width="3" fill="none"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                            </div>
                        </div>
                    </a>
                @empty
                    <div class="cat-empty-state">
                        <div class="empty-ring">
                            <svg viewBox="0 0 24 24" width="32" height="32" stroke="currentColor" fill="none" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                        </div>
                        <h3>New Collections Soon</h3>
                        <p>We're curating the best deals for you.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<style>
:root {
    --primary-red: var(--primary-color, #2951c4);
    --deep-red: var(--primary-color, #2951c4);
    --soft-red: var(--primary-color, #2951c4);
    --dark-void: #0f172a;
    --slate-text: #475569;
    --card-radius: 28px;
}
.decent-color{
    color:var(--primary-red);
    font-weight: 700;
}

.modern-categories-section {
    position: relative;
    background: radial-gradient(circle at 0% 0%, #fdfbff 0%, #f7f9fd 45%, #ffffff 100%);
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
    overflow: hidden;
}

.cat-orb {
    position: absolute;
    border-radius: 50%;
    filter: blur(70px);
    opacity: 0.16;
    pointer-events: none;
    z-index: 0;
}

.cat-orb--1 {
    width: 380px;
    height: 380px;
    top: -160px;
    right: -80px;
    background: var(--primary-red);
}

.cat-orb--2 {
    width: 320px;
    height: 320px;
    bottom: -140px;
    left: -100px;
    background: #7c3aed;
}

.cat-container {
    position: relative;
    z-index: 1;
    max-width: var(--container-max, 1280px);
    margin: 0 auto;
    padding: 0 24px;
}

.cat-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-end;
    margin-bottom: 40px;
    gap: 20px;
}

.cat-subtitle {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    color: var(--primary-red);
    font-weight: 800;
    font-size: 0.78rem;
    letter-spacing: 2.5px;
    margin-bottom: 10px;
    padding: 6px 14px 6px 10px;
    border-radius: 999px;
    background: color-mix(in srgb, var(--primary-red) 10%, white);
}

.cat-subtitle-dot {
    width: 7px;
    height: 7px;
    border-radius: 50%;
    background: var(--primary-red);
    box-shadow: 0 0 0 3px color-mix(in srgb, var(--primary-red) 25%, transparent);
}

.cat-title {
    font-size: 2.5rem;
    font-weight: 900;
    color: var(--dark-void);
    margin: 0;
    letter-spacing: -1px;
}

.cat-subtext {
    margin: 10px 0 0;
    color: var(--slate-text);
    font-size: 1rem;
    max-width: 440px;
}

.highlight-text {
    background: linear-gradient(100deg, var(--primary-red), #7c3aed);
    -webkit-background-clip: text;
    background-clip: text;
    color: transparent;
    position: relative;
}

.cat-controls {
    display: flex;
    gap: 12px;
    padding-bottom: 10px;
    flex-shrink: 0;
}

.cat-nav-btn {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    border: 1px solid #eef0f6;
    background: rgba(255,255,255,0.8);
    backdrop-filter: blur(8px);
    color: var(--dark-void);
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
    box-shadow: 0 4px 14px rgba(15,23,42,0.06);
}

.cat-nav-btn:hover {
    background: linear-gradient(135deg, var(--primary-red), #7c3aed);
    border-color: transparent;
    color: white;
    transform: translateY(-4px) scale(1.05);
    box-shadow: 0 12px 24px -6px color-mix(in srgb, var(--primary-red) 45%, transparent);
}

.cat-nav-btn:focus-visible {
    outline: 3px solid var(--primary-red);
    outline-offset: 2px;
}

.cat-track-wrapper {
    position: relative;
    padding: 20px 0;
}

.cat-track {
    display: flex;
    gap: 22px;
    overflow-x: auto;
    scroll-snap-type: x mandatory;
    scrollbar-width: none;
    padding: 12px 6px;
}

.cat-track::-webkit-scrollbar { display: none; }

.cat-card {
    --cat-accent: var(--primary-red);
    flex: 0 0 216px;
    height: 268px;
    scroll-snap-align: start;
    position: relative;
    text-decoration: none;
    border-radius: var(--card-radius);
    overflow: visible;
    transition: transform 0.5s cubic-bezier(0.23, 1, 0.32, 1);
}

.cat-card-glow {
    position: absolute;
    inset: 10px;
    border-radius: var(--card-radius);
    background: var(--cat-accent);
    filter: blur(28px);
    opacity: 0;
    transition: opacity 0.5s ease;
    z-index: 0;
}

.cat-card:hover .cat-card-glow {
    opacity: 0.28;
}

.cat-glass-bg {
    position: absolute;
    inset: 0;
    background: linear-gradient(165deg, color-mix(in srgb, var(--cat-accent) 6%, white) 0%, #ffffff 55%);
    border: 1px solid color-mix(in srgb, var(--cat-accent) 14%, #eef0f6);
    border-radius: var(--card-radius);
    overflow: hidden;
    z-index: 1;
    transition: all 0.5s ease;
}

.cat-glass-bg::after {
    content: '';
    position: absolute;
    inset: 0;
    background: radial-gradient(120px 90px at 85% -10%, color-mix(in srgb, var(--cat-accent) 18%, transparent), transparent 70%);
    opacity: 0.9;
}

.cat-index {
    position: absolute;
    top: 18px;
    right: 22px;
    z-index: 3;
    font-size: 0.72rem;
    font-weight: 800;
    letter-spacing: 0.05em;
    color: color-mix(in srgb, var(--cat-accent) 55%, #94a3b8);
    opacity: 0.7;
}

.cat-content {
    position: relative;
    z-index: 2;
    height: 100%;
    padding: 36px 18px 24px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: flex-start;
    gap: 4px;
}

.cat-icon-wrapper {
    position: relative;
    width: 84px;
    height: 84px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 6px;
}

.cat-icon-blob {
    position: absolute;
    inset: 0;
    background: linear-gradient(145deg, var(--cat-accent), color-mix(in srgb, var(--cat-accent) 65%, #0f172a));
    border-radius: 32% 68% 65% 35% / 38% 32% 68% 62%;
    box-shadow: 0 12px 24px -8px color-mix(in srgb, var(--cat-accent) 55%, transparent);
    transition: all 0.6s cubic-bezier(0.23, 1, 0.32, 1);
}

.cat-card:hover .cat-icon-blob {
    border-radius: 50%;
    transform: scale(1.1) rotate(50deg);
    box-shadow: 0 16px 30px -8px color-mix(in srgb, var(--cat-accent) 65%, transparent);
}

.cat-img {
    width: 42px;
    height: 42px;
    position: relative;
    z-index: 3;
    filter: brightness(0) invert(1) drop-shadow(0 2px 4px rgba(15,23,42,0.15));
    transition: all 0.4s ease;
}

.cat-card:hover .cat-img {
    transform: scale(1.1) rotate(-8deg);
}

.cat-placeholder {
    font-size: 2rem;
    font-weight: 900;
    color: #fff;
    position: relative;
    z-index: 3;
}

.cat-name {
    font-size: 1.1rem;
    font-weight: 800;
    color: var(--dark-void);
    margin: 10px 0 0 0;
    transition: color 0.4s ease;
    text-align: center;
    line-height: 1.25;
}

.cat-count {
    font-size: 0.76rem;
    font-weight: 700;
    color: color-mix(in srgb, var(--cat-accent) 70%, #64748b);
    margin-top: 4px;
}

.cat-action {
    display: flex;
    align-items: center;
    gap: 6px;
    font-weight: 700;
    font-size: 0.82rem;
    color: var(--cat-accent);
    opacity: 0;
    margin-top: auto;
    transform: translateY(8px);
    transition: all 0.35s ease;
}

.cat-card:hover {
    transform: translateY(-8px);
}

.cat-card:hover .cat-glass-bg {
    box-shadow: 0 28px 48px -16px color-mix(in srgb, var(--cat-accent) 30%, rgba(15,23,42,0.12));
    border-color: color-mix(in srgb, var(--cat-accent) 35%, transparent);
}

.cat-card:hover .cat-name {
    color: var(--cat-accent);
}

.cat-card:hover .cat-action {
    opacity: 1;
    transform: translateY(0);
}

.cat-card:focus-visible .cat-glass-bg {
    outline: 3px solid var(--cat-accent);
    outline-offset: 2px;
}

.cat-empty-state {
    width: 100%;
    background: white;
    padding: 60px;
    border-radius: var(--card-radius);
    text-align: center;
    border: 2px dashed #e2e8f0;
}

.empty-ring {
    width: 80px;
    height: 80px;
    margin: 0 auto 20px;
    background: color-mix(in srgb, var(--soft-red) 12%, white);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--primary-red);
}

@media (max-width: 768px) {
    .cat-header { flex-direction: column; align-items: flex-start; gap: 20px; }
    .cat-title { font-size: 2rem; }
    .cat-card { flex: 0 0 178px; height: 240px; }
    .cat-content { padding: 28px 14px 18px; }
    .cat-controls { display: none; }
    .cat-orb { display: none; }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const track = document.getElementById('categoryTrack');
    const prevBtn = document.getElementById('catPrev');
    const nextBtn = document.getElementById('catNext');

    if(track && prevBtn && nextBtn) {
        const move = (dir) => {
            const amount = track.offsetWidth * 0.7;
            track.scrollBy({ left: dir * amount, behavior: 'smooth' });
        };

        nextBtn.addEventListener('click', () => move(1));
        prevBtn.addEventListener('click', () => move(-1));
    }
});
</script>

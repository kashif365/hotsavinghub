<style>
:root {
    --hh-primary: var(--primary-color, #2951c4);
    --hh-primary-glow: rgba(var(--primary-color-rgb, 41, 81, 196), .15);
    --hh-bg: #ffffff;
    --hh-card-bg: #ffffff;
    --hh-text-dark: #0f172a;
    --hh-text-muted: #64748b;
    --hh-border-subtle: rgba(0, 0, 0, .06);
    --hh-transition: all 0.4s cubic-bezier(0.23, 1, 0.32, 1);
}
.hh-deals-wrapper { background-color: var(--hh-bg); font-family: system-ui, -apple-system, sans-serif; }
.hh-container { max-width: var(--container-max, 1280px); margin: 0 auto; padding: 0 24px; }
.hh-cat-group { margin-bottom: 3.5rem; }
.hh-cat-group:last-child { margin-bottom: 0; }
.hh-cat-header { display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 12px; margin-bottom: 2rem; }
.hh-view-all { flex-shrink: 0; }
.hh-cat-title { font-size: 1.7rem; font-weight: 800; color: var(--hh-text-dark); display: flex; align-items: center; gap: 12px; margin: 0; letter-spacing: -0.02em; }
.hh-cat-icon { width: 30px; height: 30px; object-fit: contain; }
.hh-view-all { padding: 10px 22px; background: var(--hh-primary-glow); color: var(--hh-primary); border-radius: var(--radius-pill, 999px); text-decoration: none; font-weight: 700; font-size: 0.9rem; transition: var(--hh-transition); border: 1px solid transparent; }
.hh-view-all:hover { background: var(--hh-primary); color: #fff; transform: translateY(-2px); }
.hh-view-all:focus-visible { outline: 3px solid var(--hh-primary); outline-offset: 2px; }
.hh-bento-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(260px, 1fr)); gap: 1.5rem; }
.hh-deal-card { background: var(--hh-card-bg); border-radius: var(--radius-lg, 20px); border: 1px solid var(--hh-border-subtle); position: relative; transition: var(--hh-transition); display: flex; flex-direction: column; overflow: hidden; box-shadow: var(--shadow-card, 0 10px 30px -5px rgba(0,0,0,.05)); }
.hh-deal-card:hover { transform: translateY(-8px); box-shadow: var(--shadow-card-hover, 0 20px 40px -10px rgba(0,0,0,.12)); border-color: var(--hh-primary); }
.hh-img-container { position: relative; height: 170px; margin: 12px; border-radius: var(--radius-card, 16px); overflow: hidden; display: flex; align-items: center; justify-content: center; }
.hh-img-container img { width: 100%; height: 100%; object-fit: cover; transition: var(--hh-transition); }
.hh-deal-card:hover .hh-img-container img { transform: scale(1.08); }
.hh-placeholder { width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; color: #fff; font-size: 2.25rem; font-weight: 900; }
.hh-card-body { padding: 0 1.35rem 1.35rem; display: flex; flex-direction: column; flex-grow: 1; }
.hh-store-tag { font-size: 0.7rem; font-weight: 800; color: var(--hh-primary); text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 8px; }
.hh-deal-title { font-size: 1.1rem; font-weight: 700; color: var(--hh-text-dark); line-height: 1.4; margin: 0 0 1.35rem; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; min-height: 3.1em; }
.hh-btn-stack { margin-top: auto; position: relative; }
.hh-main-btn { width: 100%; padding: 13px; border-radius: 12px; border: none; font-weight: 700; font-size: 0.92rem; cursor: pointer; transition: var(--hh-transition); display: flex; align-items: center; justify-content: center; gap: 8px; }
.hh-main-btn:focus-visible { outline: 3px solid var(--hh-primary); outline-offset: 2px; }
.hh-btn-code { background: var(--hh-text-dark); color: #fff; box-shadow: 0 4px 12px rgba(0,0,0,.1); }
.hh-btn-code:hover { background: var(--hh-primary); transform: scale(1.02); }
.hh-btn-deal { background: var(--hh-primary); color: #fff; box-shadow: 0 4px 12px var(--hh-primary-glow); }
.hh-btn-deal:hover { filter: brightness(1.1); transform: scale(1.02); }
.hh-toast { position: fixed; bottom: 30px; right: 30px; background: var(--hh-text-dark); color: #fff; padding: 16px 28px; border-radius: 16px; display: flex; align-items: center; gap: 12px; box-shadow: 0 20px 40px rgba(0,0,0,.2); transform: translateY(150%); transition: var(--hh-transition); z-index: 9999; }
.hh-toast.active { transform: translateY(0); }
.hh-empty-cat { grid-column: 1/-1; text-align: center; padding: 3rem; color: var(--hh-text-muted); }
.hh-empty-all { text-align: center; padding: 5rem 0; }
.hh-empty-all p { font-size: 1.15rem; color: var(--hh-text-muted); }

@media (max-width: 1200px) { .hh-bento-grid { grid-template-columns: repeat(3, 1fr); } }
@media (max-width: 992px) { .hh-bento-grid { grid-template-columns: repeat(2, 1fr); } .hh-hide-tablet { display: none; } }
@media (max-width: 640px) {
    .hh-bento-grid { grid-template-columns: 1fr; }
    .hh-cat-title { font-size: 1.35rem; }
    .hh-hide-mobile { display: none; }
    .hh-img-container { height: 190px; }
    .hh-cat-group { margin-bottom: 2.5rem; }
}
</style>

<section class="hh-deals-wrapper hsr-section">
    <div class="hh-container">
        @forelse($homeCategories ?? [] as $category)
        <div class="hh-cat-group">
            <div class="hh-cat-header">
                <h3 class="hh-cat-title">
                    @if($category->media && file_exists(public_path(ltrim($category->media, '/'))))
                        <img src="{{ asset(ltrim($category->media, '/')) }}" alt="{{ $category->category_name }}" class="hh-cat-icon" loading="lazy">
                    @else
                        <span class="hh-cat-emoji">🏷️</span>
                    @endif
                    {{ $category->category_name }}
                </h3>
                <a href="{{ route('category', $category->seo_url) }}" class="hh-view-all">
                    Explore All Deals
                </a>
            </div>

            <div class="hh-bento-grid">
                @forelse($category->coupons ?? [] as $dealIndex => $coupon)
                    @php
                        $storeName = strtoupper($coupon->store->short_name ?? $coupon->brand_store ?? 'STORE');
                        $bgColors = ['#2951c4', '#0f172a', '#6366f1', '#4338ca', '#1e293b'];
                        $accentBg = $bgColors[$dealIndex % count($bgColors)];
                    @endphp

                    <article class="hh-deal-card @if($dealIndex >= 8) hh-hide-tablet @endif @if($dealIndex >= 4) hh-hide-mobile @endif">
                        <div class="hh-img-container" style="background: {{ $accentBg }};">
                            @if($coupon->cover_logo && file_exists(public_path(ltrim($coupon->cover_logo, '/'))))
                                @php
                                    $imagePath = ltrim($coupon->cover_logo, '/');
                                    if (!str_starts_with($imagePath, 'uploads/')) $imagePath = 'uploads/' . $imagePath;
                                    $resizedUrl = route('image.resize', ['path' => $imagePath, 'w' => 400, 'h' => 200, 'q' => 90]);
                                @endphp
                                <img src="{{ $resizedUrl }}" alt="{{ $coupon->coupon_title }}" loading="lazy">
                            @elseif($coupon->store && $coupon->store->store_logo && file_exists(public_path(ltrim($coupon->store->store_logo, '/'))))
                                @php
                                    $logoPath = ltrim($coupon->store->store_logo, '/');
                                    if (!str_starts_with($logoPath, 'uploads/')) $logoPath = 'uploads/' . $logoPath;
                                    $resizedUrl = route('image.resize', ['path' => $logoPath, 'w' => 200, 'q' => 90]);
                                @endphp
                                <img src="{{ $resizedUrl }}" alt="{{ $storeName }}" style="object-fit: contain; padding: 2rem; background: #fff;">
                            @else
                                <div class="hh-placeholder">
                                    {{ substr($storeName, 0, 1) }}
                                </div>
                            @endif
                        </div>

                        <div class="hh-card-body">
                            <span class="hh-store-tag">{{ $storeName }}</span>
                            <h4 class="hh-deal-title">{{ $coupon->coupon_title }}</h4>

                            <div class="hh-btn-stack">
                                @if($coupon->coupon_code)
                                    <button class="hh-main-btn hh-btn-code"
                                            onclick="copyCoupon('{{ $coupon->coupon_code }}', '{{ $coupon->affiliate_url ?? url('/') }}')">
                                        <span>Show Code</span>
                                        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2"></path></svg>
                                    </button>
                                @else
                                    <button class="hh-main-btn hh-btn-deal"
                                            onclick="window.open('{{ $coupon->affiliate_url ?? url('/') }}', '_blank')">
                                        <span>Get Deal</span>
                                        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                                    </button>
                                @endif
                            </div>
                        </div>
                    </article>
                @empty
                    <div class="hh-empty-cat">
                        No deals found in this category.
                    </div>
                @endforelse
            </div>
        </div>
        @empty
            <div class="hh-empty-all">
                <p>Stay tuned! Fresh deals are coming soon.</p>
            </div>
        @endforelse
    </div>
</section>

<div id="hhToast" class="hh-toast">
    <div style="background: #22c55e; border-radius: 50%; width: 24px; height: 24px; display: flex; align-items: center; justify-content: center;">✓</div>
    <span>Code Copied to Clipboard!</span>
</div>

<script>
function copyCoupon(code, url) {
    navigator.clipboard.writeText(code).then(() => {
        const toast = document.getElementById('hhToast');
        toast.classList.add('active');
        setTimeout(() => {
            toast.classList.remove('active');
            window.open(url, '_blank');
        }, 2000);
    });
}
</script>

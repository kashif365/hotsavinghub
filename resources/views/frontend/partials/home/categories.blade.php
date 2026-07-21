<div class="modern-categories-section hsr-section">
    <div class="cat-container">
        <div class="cat-header">
            <div class="cat-title-group">
                <span class="cat-subtitle">DISCOVER</span>
                <h2 class="cat-title">
                    Top <span class="highlight-text">Categories</span>
                </h2>
            </div>
            <div class="cat-controls">
                <button class="cat-nav-btn prev-btn" id="catPrev" aria-label="Previous">
                    <svg viewBox="0 0 24 24" width="22" height="22" stroke="currentColor" stroke-width="2.5" fill="none" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"></polyline></svg>
                </button>
                <button class="cat-nav-btn next-btn" id="catNext" aria-label="Next">
                    <svg viewBox="0 0 24 24" width="22" height="22" stroke="currentColor" stroke-width="2.5" fill="none" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>
                </button>
            </div>
        </div>

        <div class="cat-track-wrapper">
            <div class="cat-track" id="categoryTrack">
                @forelse($categories ?? [] as $index => $category)
                    <a href="{{ route('category', $category->seo_url) }}" class="cat-card">
                        <div class="cat-glass-bg"></div>
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

.modern-categories-section {
    background: radial-gradient(circle at 0% 0%, #fffcfc 0%, #ffffff 100%);
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
    overflow: hidden;
}

.cat-container {
    max-width: var(--container-max, 1280px);
    margin: 0 auto;
    padding: 0 24px;
}

.cat-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-end;
    margin-bottom: 40px;
}

.cat-subtitle {
    display: block;
    color: var(--primary-red);
    font-weight: 800;
    font-size: 0.85rem;
    letter-spacing: 3px;
    margin-bottom: 8px;
}

.cat-title {
    font-size: 2.5rem;
    font-weight: 900;
    color: var(--dark-void);
    margin: 0;
    letter-spacing: -1px;
}

.highlight-text {
    color: var(--primary-red);
    position: relative;
}

.cat-controls {
    display: flex;
    gap: 15px;
    padding-bottom: 10px;
}

.cat-nav-btn {
    width: 52px;
    height: 52px;
    border-radius: 16px;
    border: 2px solid #f1f5f9;
    background: white;
    color: var(--dark-void);
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    box-shadow: 0 4px 12px rgba(0,0,0,0.03);
}

.cat-nav-btn:hover {
    background: var(--primary-red);
    border-color: var(--primary-red);
    color: white;
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(0,0,0,0.15);
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
    gap: 24px;
    overflow-x: auto;
    scroll-snap-type: x mandatory;
    scrollbar-width: none;
    padding: 10px;
}

.cat-track::-webkit-scrollbar { display: none; }

.cat-card {
    flex: 0 0 210px;
    height: 260px;
    scroll-snap-align: start;
    position: relative;
    text-decoration: none;
    border-radius: var(--card-radius);
    overflow: hidden;
    transition: all 0.5s cubic-bezier(0.23, 1, 0.32, 1);
}

.cat-glass-bg {
    position: absolute;
    inset: 0;
    background: white;
    border: 1px solid #f1f5f9;
    border-radius: var(--card-radius);
    z-index: 1;
    transition: all 0.5s ease;
}

.cat-content {
    position: relative;
    z-index: 2;
    height: 100%;
    padding: 36px 20px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: space-between;
}

.cat-icon-wrapper {
    position: relative;
    width: 92px;
    height: 92px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.cat-icon-blob {
    position: absolute;
    inset: 0;
    background: color-mix(in srgb, var(--soft-red) 12%, white);
    border-radius: 35% 65% 70% 30% / 30% 30% 70% 70%;
    transition: all 0.6s ease;
}

.cat-card:hover .cat-icon-blob {
    background: var(--primary-red);
    border-radius: 50%;
    transform: scale(1.1) rotate(90deg);
}

.cat-img {
    width: 46px;
    height: 46px;
    position: relative;
    z-index: 3;
    filter: drop-shadow(0 4px 8px rgba(15,23,42,0.15));
    transition: all 0.4s ease;
}

/* Force image icons to white/primary color behavior */
.cat-card:hover .cat-img {
    filter: brightness(0) invert(1);
    transform: scale(1.1);
}

.cat-placeholder {
    font-size: 2.25rem;
    font-weight: 900;
    color: var(--primary-red);
    position: relative;
    z-index: 3;
    transition: color 0.4s ease;
}

.cat-card:hover .cat-placeholder {
    color: white;
}

.cat-name {
    font-size: 1.15rem;
    font-weight: 800;
    color: var(--dark-void);
    margin: 15px 0 0 0;
    transition: color 0.4s ease;
    text-align: center;
}

.cat-action {
    display: flex;
    align-items: center;
    gap: 8px;
    font-weight: 700;
    font-size: 0.85rem;
    color: var(--primary-red);
    opacity: 0;
    transform: translateY(10px);
    transition: all 0.4s ease;
}

.cat-card:hover {
    transform: translateY(-10px);
}

.cat-card:hover .cat-glass-bg {
    box-shadow: 0 25px 50px -12px rgba(15,23,42,0.15);
    border-color: color-mix(in srgb, var(--primary-red) 20%, transparent);
}

.cat-card:hover .cat-name {
    color: var(--primary-red);
}

.cat-card:hover .cat-action {
    opacity: 1;
    transform: translateY(0);
}

.cat-card:focus-visible .cat-glass-bg {
    outline: 3px solid var(--primary-red);
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
    .cat-card { flex: 0 0 180px; height: 240px; }
    .cat-controls { display: none; }
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

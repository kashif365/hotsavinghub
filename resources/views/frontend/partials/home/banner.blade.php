@if(isset($homeBanner) && $homeBanner)
<div class="home-banner-section">
    <div class="home-banner-container">
        <div class="home-banner-wrapper">
            <img src="{{ asset($homeBanner) }}"
                 alt="Home Page Banner"
                 class="home-banner-image"
                 width="1200"
                 height="300"
                 loading="lazy">
            <div class="home-banner-overlay"></div>
        </div>
    </div>
</div>

<style>
.home-banner-section {
    width: 100%;
    position: relative;
    padding-block: 1.5rem;
}

.home-banner-container {
    max-width: var(--container-max, 1280px);
    margin: 0 auto;
    padding: 0 24px;
}

.home-banner-wrapper {
    position: relative;
    width: 100%;
    overflow: hidden;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: var(--radius-lg, 20px);
    box-shadow: var(--shadow-card, 0 4px 16px rgba(15,23,42,.06));
}

.home-banner-image {
    width: 100%;
    height: auto;
    display: block;
    object-fit: cover;
    max-height: 340px;
    object-position: center;
}

.home-banner-overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(180deg, rgba(15,23,42,0) 55%, rgba(15,23,42,.18) 100%);
    pointer-events: none;
}

@media (max-width: 768px) {
    .home-banner-section { padding-block: 1rem; }
    .home-banner-container { padding: 0 16px; }
    .home-banner-wrapper { border-radius: 14px; }
    .home-banner-image { max-height: 220px; }
}

@media (max-width: 480px) {
    .home-banner-image { max-height: 180px; }
}
</style>
@endif

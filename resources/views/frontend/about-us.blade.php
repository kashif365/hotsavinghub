@extends('frontend.layouts.app')

@section('title', $aboutPage->meta_title ?? 'About Us - Hotsavinghub | Your Trusted Discount Code Platform')
@section('description', $aboutPage->meta_description ?? 'Learn about Hotsavinghub - the UK\'s leading discount code platform. Discover our mission to help you save money with verified voucher codes and exclusive deals.')

@push('meta')
    @if($aboutPage && $aboutPage->canonical_url)
        <link rel="canonical" href="{{ $aboutPage->canonical_url }}">
    @else
        <link rel="canonical" href="{{ url()->current() }}">
    @endif

    @if($aboutPage && $aboutPage->meta_keywords)
        <meta name="keywords" content="{{ $aboutPage->meta_keywords }}">
    @else
        <meta name="keywords" content="About Hotsavinghub, discount code platform, voucher codes UK, money saving, exclusive deals">
    @endif
@endpush

@if($aboutPage && $aboutPage->schema && trim($aboutPage->schema) !== '' && trim($aboutPage->schema) !== 'test')
    @php
        $schemaContent = trim($aboutPage->schema);
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

@push('styles')
<link rel="preload" href="{{ asset('frontend_assets/css/fonts.css') }}" as="style" crossorigin>
<link rel="preload" href="{{ asset('frontend_assets/css/store.css') }}" as="style" crossorigin>
<link rel="stylesheet" href="{{ asset('frontend_assets/css/fonts.css') }}" crossorigin>
<link rel="stylesheet" href="{{ asset('frontend_assets/css/store.css') }}" as="style" crossorigin>
<style>
/* Dynamic Color Variables */
:root {
    --about-primary-color: {{ $settings['primary_color'] ?? '#2951c4' }};
    --about-primary-light: {{ $settings['primary_color'] ?? '#2951c4' }}20;
    --about-primary-lighter: {{ $settings['primary_color'] ?? '#2951c4' }}10;
    --about-primary-dark: {{ $settings['primary_color'] ?? '#2951c4' }}CC;
    --about-secondary-color: {{ $settings['secondary_color'] ?? '#ff4444' }};
    --about-accent-color: {{ $settings['primary_color'] ?? '#2951c4' }};
    --about-text-color: {{ $settings['text_color'] ?? '#2d3748' }};
    --about-heading-color: {{ $settings['text_color'] ?? '#1a202c' }};
    --about-background-color: {{ $settings['background_primary_color'] ?? '#ffffff' }};
    --about-card-background: {{ $settings['background_primary_color'] ?? '#ffffff' }};
}

/* About Us Page Styles */
.about-hero {
    background:
        linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)),
        url('{{ asset('frontend_assets/images/search-bg.webp') }}') center/cover no-repeat;
    color: white;
    padding: 80px 0;
    text-align: center;
    position: relative;
    overflow: hidden;
}

.about-hero::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background:
        radial-gradient(circle at 20% 80%, var(--about-primary-light) 0%, transparent 50%),
        radial-gradient(circle at 80% 20%, var(--about-primary-light) 0%, transparent 50%),
        url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="white" opacity="0.1"/><circle cx="75" cy="75" r="1" fill="white" opacity="0.1"/><circle cx="50" cy="10" r="0.5" fill="white" opacity="0.1"/><circle cx="10" cy="60" r="0.5" fill="white" opacity="0.1"/><circle cx="90" cy="40" r="0.5" fill="white" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
    opacity: 0.3;
}

.about-hero-content {
    position: relative;
    z-index: 2;
    max-width: 800px;
    margin: 0 auto;
    padding: 0 20px;
}

.about-hero h1 {
    font-size: 3.5rem;
    font-weight: 700;
    margin-bottom: 20px;
    text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
}

.about-hero p {
    font-size: 1.3rem;
    margin-bottom: 30px;
    opacity: 0.95;
    line-height: 1.6;
}

.about-stats {
    display: flex;
    justify-content: center;
    gap: 60px;
    margin-top: 40px;
    flex-wrap: wrap;
}

.stat-item {
    text-align: center;
}

.stat-number {
    font-size: 2.5rem;
    font-weight: 700;
    display: block;
    margin-bottom: 5px;
}

.stat-label {
    font-size: 1rem;
    opacity: 0.9;
}

.about-section {
    padding: 80px 0;
    background: var(--about-background-color);
}

.about-section:nth-child(even) {
    background: var(--about-primary-lighter);
}

.section-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 20px;
}

.section-header {
    text-align: center;
    margin-bottom: 60px;
}

.section-title {
    font-size: 2.5rem;
    font-weight: 700;
    color: var(--about-heading-color);
    margin-bottom: 20px;
    position: relative;
}

.section-title::after {
    content: '';
    position: absolute;
    bottom: -10px;
    left: 50%;
    transform: translateX(-50%);
    width: 60px;
    height: 4px;
    background: linear-gradient(135deg, var(--about-primary-color) 0%, var(--about-primary-dark) 100%);
    border-radius: 2px;
}

.section-subtitle {
    font-size: 1.2rem;
    color: var(--about-text-color);
    max-width: 600px;
    margin: 0 auto;
    line-height: 1.6;
}

.mission-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 40px;
    margin-top: 60px;
}

.mission-card {
    background: var(--about-card-background);
    padding: 40px 30px;
    border-radius: 15px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    text-align: center;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    border: 1px solid var(--about-primary-lighter);
}

.mission-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 20px 40px rgba(0,0,0,0.15);
    border-color: var(--about-primary-light);
}

.mission-icon {
    width: 80px;
    height: 80px;
    background: linear-gradient(135deg, var(--about-primary-color) 0%, var(--about-primary-dark) 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 25px;
    font-size: 2rem;
    color: white;
}

.mission-card h3 {
    font-size: 1.5rem;
    font-weight: 600;
    color: var(--about-heading-color);
    margin-bottom: 15px;
}

.mission-card p {
    color: var(--about-text-color);
    line-height: 1.6;
    font-size: 1rem;
}

.values-section {
    background: linear-gradient(135deg, var(--about-primary-lighter) 0%, var(--about-primary-light) 100%);
}

.values-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 30px;
    margin-top: 60px;
}

.value-item {
    background: var(--about-card-background);
    padding: 30px 25px;
    border-radius: 12px;
    text-align: center;
    box-shadow: 0 5px 20px rgba(0,0,0,0.08);
    transition: transform 0.3s ease;
    border: 1px solid var(--about-primary-lighter);
}

.value-item:hover {
    transform: translateY(-5px);
    border-color: var(--about-primary-light);
}

.value-icon {
    font-size: 2.5rem;
    margin-bottom: 20px;
    color: var(--about-primary-color);
}

.value-item h4 {
    font-size: 1.3rem;
    font-weight: 600;
    color: var(--about-heading-color);
    margin-bottom: 15px;
}

.value-item p {
    color: var(--about-text-color);
    line-height: 1.5;
    font-size: 0.95rem;
}

.team-section {
    background: white;
}

.team-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 40px;
    margin-top: 60px;
}

.team-member {
    background: white;
    padding: 30px;
    border-radius: 15px;
    text-align: center;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    transition: transform 0.3s ease;
}

.team-member:hover {
    transform: translateY(-10px);
}

.member-avatar {
    width: 120px;
    height: 120px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 25px;
    font-size: 3rem;
    color: white;
    font-weight: 700;
}

.member-name {
    font-size: 1.4rem;
    font-weight: 600;
    color: #2c3e50;
    margin-bottom: 10px;
}

.member-role {
    color: #667eea;
    font-weight: 500;
    margin-bottom: 15px;
}

.member-bio {
    color: #6c757d;
    line-height: 1.5;
    font-size: 0.95rem;
}

.cta-section {
    background: var(--about-primary-color);
    color: white;
    padding: 80px 0;
    text-align: center;
}

.cta-content {
    max-width: 600px;
    margin: 0 auto;
    padding: 0 20px;
}

.cta-title {
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 20px;
}

.cta-text {
    font-size: 1.2rem;
    margin-bottom: 40px;
    opacity: 0.95;
    line-height: 1.6;
}

.cta-buttons {
    display: flex;
    gap: 20px;
    justify-content: center;
    flex-wrap: wrap;
}

.cta-btn {
    background: white;
    color: var(--about-primary-color);
    padding: 15px 30px;
    border-radius: 50px;
    text-decoration: none;
    font-weight: 600;
    font-size: 1.1rem;
    transition: all 0.3s ease;
    border: 2px solid white;
}

.cta-btn:hover {
    background: transparent;
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 10px 20px rgba(0,0,0,0.2);
}

.cta-btn.secondary {
    background: transparent;
    color: white;
    border: 2px solid white;
}

.cta-btn.secondary:hover {
    background: white;
    color: var(--about-primary-color);
}

/* Responsive Design */
@media (max-width: 768px) {
    .about-hero h1 {
        font-size: 2.5rem;
    }

    .about-hero p {
        font-size: 1.1rem;
    }

    .about-stats {
        gap: 30px;
    }

    .stat-number {
        font-size: 2rem;
    }

    .section-title {
        font-size: 2rem;
    }

    .mission-grid,
    .values-grid,
    .team-grid {
        grid-template-columns: 1fr;
        gap: 30px;
    }

    .cta-buttons {
        flex-direction: column;
        align-items: center;
    }

    .cta-btn {
        width: 100%;
        max-width: 300px;
    }
}

@media (max-width: 480px) {
    .about-hero {
        padding: 60px 0;
    }

    .about-hero h1 {
        font-size: 2rem;
    }

    .about-section {
        padding: 60px 0;
    }

    .mission-card,
    .team-member {
        padding: 25px 20px;
    }

    .cta-section {
        padding: 60px 0;
    }

    .cta-title {
        font-size: 2rem;
    }
}
</style>
@endpush

@section('content')

<!-- About Us Hero Section -->
<style>:root{--primary:#2951c4;--primary-light:#466ddb;--text-dark:#0f172a;--text-light:#64748b;--glass:rgba(255,255,255,0.8);--radius-xl:32px;--radius-lg:24px;--font-main:'Inter',system-ui,sans-serif}.hsh-about-hero{position:relative;padding:8rem 1.5rem;background:#fff;overflow:hidden;font-family:var(--font-main);display:flex;justify-content:center}.hsh-about-hero::before{content:'';position:absolute;top:-10%;right:-5%;width:400px;height:400px;background:radial-gradient(circle,rgba(41,81,196,0.08) 0%,rgba(255,255,255,0) 70%);border-radius:50%;z-index:1}.hsh-about-hero::after{content:'';position:absolute;bottom:-10%;left:-5%;width:350px;height:350px;background:radial-gradient(circle,rgba(41,81,196,0.05) 0%,rgba(255,255,255,0) 70%);border-radius:50%;z-index:1}.hsh-hero-content{position:relative;z-index:10;max-width:1100px;width:100%;text-align:center}.hsh-hero-content h1{font-size:clamp(2.5rem,8vw,4.5rem);font-weight:900;color:var(--text-dark);letter-spacing:-0.03em;line-height:1.1;margin-bottom:1.5rem}.hsh-hero-content h1 span{color:var(--primary);position:relative;display:inline-block}.hsh-hero-content p{font-size:clamp(1.1rem,2vw,1.35rem);color:var(--text-light);line-height:1.6;max-width:800px;margin:0 auto 4rem}.hsh-stats-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:1.5rem;margin-top:2rem}.hsh-stat-card{background:var(--glass);backdrop-filter:blur(12px);-webkit-backdrop-filter:blur(12px);border:1px solid rgba(41,81,196,0.1);padding:2.5rem 1.5rem;border-radius:var(--radius-lg);transition:all 0.4s cubic-bezier(0.175,0.885,0.32,1.275);position:relative;overflow:hidden}.hsh-stat-card:hover{transform:translateY(-10px);border-color:var(--primary);box-shadow:0 25px 50px -12px rgba(41,81,196,0.15)}.hsh-stat-number{display:block;font-size:2.5rem;font-weight:800;color:var(--primary);margin-bottom:0.5rem;letter-spacing:-1px}.hsh-stat-label{font-size:0.95rem;font-weight:600;color:var(--text-dark);text-transform:uppercase;letter-spacing:0.05em}.hsh-badge{display:inline-block;padding:0.6rem 1.2rem;background:rgba(41,81,196,0.06);color:var(--primary);border-radius:99px;font-weight:700;font-size:0.9rem;margin-bottom:1.5rem;border:1px solid rgba(41,81,196,0.1)}@media (max-width:1024px){.hsh-stats-grid{grid-template-columns:repeat(2,1fr)}}@media (max-width:640px){.hsh-about-hero{padding:5rem 1rem}.hsh-stats-grid{grid-template-columns:1fr;gap:1rem}.hsh-stat-card{padding:2rem 1rem}}</style>```


<section class="hsh-about-hero">
    <div class="hsh-hero-content">
        <div class="hsh-badge">UK's Leading Savings Hub</div>

        <h1>Saving Made <span>Simple</span> for Everyone</h1>

        <p>
            Welcome to <strong>Hotsavinghub</strong>, your premier destination for high-conversion discount codes,
            exclusive vouchers, and hand-picked deals. We bridge the gap between UK's top retailers
            and savvy shoppers, ensuring you never pay full price again.
        </p>

        <div class="hsh-stats-grid">
            <div class="hsh-stat-card">
                <span class="hsh-stat-number">10K+</span>
                <span class="hsh-stat-label">Verified Codes</span>
            </div>

            <div class="hsh-stat-card" style="transition-delay: 0.1s;">
                <span class="hsh-stat-number">2.5K+</span>
                <span class="hsh-stat-label">Trusted Brands</span>
            </div>

            <div class="hsh-stat-card" style="transition-delay: 0.2s;">
                <span class="hsh-stat-number">500K+</span>
                <span class="hsh-stat-label">Active Savers</span>
            </div>

            <div class="hsh-stat-card" style="transition-delay: 0.3s;">
                <span class="hsh-stat-number">£50M+</span>
                <span class="hsh-stat-label">Total Savings</span>
            </div>
        </div>
    </div>
</section>
<style>:root{--primary:#2951c4;--primary-soft:rgba(41,81,196,0.05);--primary-border:rgba(41,81,196,0.12);--text-h:#0f172a;--text-p:#475569;--white:#ffffff;--radius-2xl:32px;--radius-xl:24px;--shadow-bento:0 20px 25px -5px rgba(0,0,0,0.02),0 10px 10px -5px rgba(0,0,0,0.01);--transition:all 0.5s cubic-bezier(0.16,1,0.3,1)}.hsh-mission{padding:7rem 0;background-color:var(--white);font-family:system-ui,-apple-system,sans-serif}.hsh-container{max-width:1280px;margin:0 auto;padding:0 1.5rem}.hsh-header{text-align:center;max-width:800px;margin:0 auto 4.5rem}.hsh-badge{display:inline-block;padding:8px 20px;background:var(--primary-soft);color:var(--primary);border-radius:100px;font-size:0.85rem;font-weight:700;letter-spacing:1px;text-transform:uppercase;margin-bottom:1.5rem;border:1px solid var(--primary-border)}.hsh-header h2{font-size:clamp(2.2rem,5vw,3.2rem);color:var(--text-h);font-weight:900;letter-spacing:-0.04em;margin-bottom:1.5rem}.hsh-header p{font-size:1.2rem;color:var(--text-p);line-height:1.6}.hsh-mission-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:2rem}.hsh-m-card{background:var(--white);padding:3rem 2rem;border-radius:var(--radius-xl);border:1px solid var(--primary-border);transition:var(--transition);position:relative;overflow:hidden;box-shadow:var(--shadow-bento)}.hsh-m-card::before{content:'';position:absolute;top:0;left:0;width:100%;height:4px;background:var(--primary);transform:scaleX(0);transition:var(--transition);transform-origin:left}.hsh-m-card:hover{transform:translateY(-12px);border-color:var(--primary);box-shadow:0 30px 60px -12px rgba(41,81,196,0.12)}.hsh-m-card:hover::before{transform:scaleX(1)}.hsh-m-icon{width:64px;height:64px;background:var(--primary-soft);border-radius:18px;display:flex;align-items:center;justify-content:center;font-size:1.75rem;margin-bottom:2rem;transition:var(--transition)}.hsh-m-card:hover .hsh-m-icon{transform:rotate(-10deg) scale(1.1);background:var(--primary);color:white}.hsh-m-card h3{font-size:1.5rem;font-weight:800;color:var(--text-h);margin-bottom:1rem}.hsh-m-card p{color:var(--text-p);line-height:1.6;font-size:1.05rem;margin:0}@media (max-width:1024px){.hsh-mission-grid{grid-template-columns:repeat(2,1fr)}}@media (max-width:768px){.hsh-mission{padding:5rem 0}.hsh-mission-grid{grid-template-columns:1fr}.hsh-m-card{padding:2.5rem 1.5rem}.hsh-header h2{font-size:2rem}}</style>
<!-- Our Mission Section -->
<section class="hsh-mission">
    <div class="hsh-container">
        <header class="hsh-header">
            <span class="hsh-badge">Our Core Philosophy</span>
            <h2 class="section-title">The Hotsavinghub Mission</h2>
            <p class="section-subtitle">
                At Hotsavinghub, we empower the UK shopping community by removing the barriers to savings.
                We believe that premium brands should be accessible to everyone through transparent and verified deals.
            </p>
        </header>

        <div class="hsh-mission-grid">
            <article class="hsh-m-card">
                <div class="hsh-m-icon" aria-hidden="true">🎯</div>
                <h3>Verified Deals</h3>
                <p>Accuracy is our obsession. Every coupon is rigorously tested by our UK team to ensure it delivers the promised discount at checkout, every single time.</p>
            </article>

            <article class="hsh-m-card" style="transition-delay: 0.1s;">
                <div class="hsh-m-icon" aria-hidden="true">⚡</div>
                <h3>Real-Time Updates</h3>
                <p>Our infrastructure syncs with over 2,500 partner stores every hour, ensuring you see the freshest drops and that expired codes are vanished instantly.</p>
            </article>

            <article class="hsh-m-card" style="transition-delay: 0.2s;">
                <div class="hsh-m-icon" aria-hidden="true">🛡️</div>
                <h3>Secure & Safe</h3>
                <p>Your trust is our greatest asset. We provide a clutter-free, ad-safe environment where your data remains private and your shopping experience is seamless.</p>
            </article>

            <article class="hsh-m-card" style="transition-delay: 0.3s;">
                <div class="hsh-m-icon" aria-hidden="true">💡</div>
                <h3>Smart AI Savings</h3>
                <p>Utilizing 2026 neural mapping, we suggest deals tailored to your shopping habits, ensuring you never miss a discount on things you actually love.</p>
            </article>
        </div>
    </div>
</section>
<style>:root{--p:#2951c4;--p-h:rgba(41,81,196,0.1);--t-d:#0f172a;--t-l:#475569;--b-g:rgba(41,81,196,0.05);--w:#ffffff;--s:0 20px 40px -10px rgba(0,0,0,0.05);--tr:all 0.4s cubic-bezier(0.25,1,0.5,1)}.hsh-v{padding:8rem 0;background:var(--w);font-family:Inter,system-ui,sans-serif;overflow:hidden}.hsh-c{max-width:1300px;margin:0 auto;padding:0 2rem}.hsh-h{text-align:center;max-width:750px;margin:0 auto 5rem}.hsh-h h2{font-size:clamp(2.5rem,6vw,3.5rem);font-weight:950;color:var(--t-d);letter-spacing:-0.05em;margin-bottom:1.5rem}.hsh-h p{font-size:1.25rem;color:var(--t-l);line-height:1.6}.hsh-g{display:grid;grid-template-columns:repeat(3,1fr);gap:2.5rem}.hsh-i{background:var(--w);padding:3.5rem 2.5rem;border-radius:32px;border:1px solid #f1f5f9;transition:var(--tr);position:relative;z-index:1;display:flex;flex-direction:column;align-items:flex-start}.hsh-i:hover{transform:translateY(-15px);border-color:var(--p);box-shadow:0 35px 70px -15px rgba(41,81,196,0.15)}.hsh-ic{width:70px;height:70px;background:var(--b-g);border-radius:22px;display:flex;align-items:center;justify-content:center;font-size:2rem;margin-bottom:2rem;transition:var(--tr);color:var(--p)}.hsh-i:hover .hsh-ic{background:var(--p);color:var(--w);transform:scale(1.1) rotate(-5deg)}.hsh-i h4{font-size:1.6rem;font-weight:800;color:var(--t-d);margin:0 0 1rem;letter-spacing:-0.02em}.hsh-i p{font-size:1.05rem;color:var(--t-l);line-height:1.7;margin:0}.hsh-i::after{content:'';position:absolute;inset:0;border-radius:32px;background:linear-gradient(135deg,rgba(41,81,196,0.03) 0%,rgba(255,255,255,0) 100%);opacity:0;transition:var(--tr);z-index:-1}.hsh-i:hover::after{opacity:1}@media (max-width:1024px){.hsh-g{grid-template-columns:repeat(2,1fr)}}@media (max-width:768px){.hsh-v{padding:5rem 0}.hsh-g{grid-template-columns:1fr;gap:1.5rem}.hsh-h{margin-bottom:3.5rem}.hsh-i{padding:2.5rem 2rem}}</style>
<!-- Our Values Section -->
<section class="hsh-v">
    <div class="hsh-c">
        <header class="hsh-h">
            <h2 class="section-title">The Principles of Hotsavinghub</h2>
            <p class="section-subtitle">Our core values define how we serve millions of UK shoppers every day, ensuring reliability, speed, and trust in every click.</p>
        </header>

        <div class="hsh-g">
            <article class="hsh-i">
                <div class="hsh-ic" aria-hidden="true">🎯</div>
                <h4>Accuracy</h4>
                <p>We believe in quality over quantity. Every discount code undergoes a rigorous multi-step manual verification process before it reaches your screen.</p>
            </article>

            <article class="hsh-i" style="transition-delay: 0.05s;">
                <div class="hsh-ic" aria-hidden="true">⚡</div>
                <h4>Speed</h4>
                <p>In the world of flash sales, seconds matter. Our real-time engine pushes live updates the instant a partner brand drops a new voucher code.</p>
            </article>

            <article class="hsh-i" style="transition-delay: 0.1s;">
                <div class="hsh-ic" aria-hidden="true">🤝</div>
                <h4>Trust</h4>
                <p>Transparency is our foundation. We maintain honest, long-term relationships with UK retailers to provide exclusive deals you won't find anywhere else.</p>
            </article>

            <article class="hsh-i" style="transition-delay: 0.15s;">
                <div class="hsh-ic" aria-hidden="true">💎</div>
                <h4>Quality</h4>
                <p>We don't just list coupons; we curate savings. Our team selects the best deals that provide genuine value to your wallet and lifestyle.</p>
            </article>

            <article class="hsh-i" style="transition-delay: 0.2s;">
                <div class="hsh-ic" aria-hidden="true">🌟</div>
                <h4>Innovation</h4>
                <p>By leveraging 2026 AI discovery tools, we predict upcoming sales trends, helping you plan your purchases for maximum price reduction.</p>
            </article>

            <article class="hsh-i" style="transition-delay: 0.25s;">
                <div class="hsh-ic" aria-hidden="true">❤️</div>
                <h4>Customer First</h4>
                <p>Your journey is our priority. From mobile-first design to zero-clutter interfaces, everything we build is optimized for your saving experience.</p>
            </article>
        </div>
    </div>
</section>


<!-- Call to Action Section -->
<div class="cta-section">
    <div class="cta-content">
        <h2 class="cta-title">Ready to Start Saving?</h2>
        <p class="cta-text">Join thousands of smart shoppers who trust Hotsavinghub for their discount code needs. Start saving money today!</p>

        <div class="cta-buttons">
            <a href="{{ route('categories') }}" class="cta-btn">Browse Categories</a>
            <a href="{{ route('all-brands') }}" class="cta-btn secondary">View All Stores</a>
        </div>
    </div>
</div>

@endsection

@extends('frontend.layouts.app')

@section('title', 'All Categories | Hotsavinghub')
@section('description', 'Browse all shopping categories on Hotsavinghub to find the latest discount codes and voucher codes for your favorite UK brands.')

@push('styles')
<style>
:root{--p:#2951c4;--p-d:#1e3a8a;--w:#fff;--g:#64748b;--l:#f8fafc;--rad:20px}
.ph-sec{padding:20px 0 40px;background:var(--w);font-family:'Inter',system-ui,sans-serif}
.ph-wrp{max-width:1280px;margin:0 auto;padding:0 20px}
.ph-nav{margin-bottom:20px}
.ph-ol{list-style:none;padding:0;margin:0;display:flex;align-items:center;flex-wrap:wrap;gap:10px;font-size:14px;color:var(--g)}
.ph-li{display:flex;align-items:center}
.ph-li a{text-decoration:none;color:var(--g);transition:color .2s;display:flex;align-items:center;gap:6px;font-weight:500}
.ph-li a:hover{color:var(--p)}
.ph-sep{font-size:10px;color:#cbd5e1;margin:0 4px}
.ph-box{background:linear-gradient(135deg,var(--p) 0%,var(--p-d) 100%);padding:40px;border-radius:var(--rad);position:relative;overflow:hidden;box-shadow:0 10px 25px -5px rgba(41,81,196,0.25);display:flex;align-items:center;justify-content:space-between;gap:24px;min-height:140px;flex-wrap:wrap}
.ph-cnt{position:relative;z-index:2;color:var(--w)}
.ph-box h1{margin:0;font-size:2.5rem;font-weight:800;letter-spacing:-1px;line-height:1.1}
.ph-box p{margin:10px 0 0;color:rgba(255,255,255,.85);font-size:1rem;max-width:520px}
.ph-dec{position:absolute;top:0;right:0;width:50%;height:100%;pointer-events:none;background:radial-gradient(circle at top right,rgba(255,255,255,0.1) 0%,transparent 60%)}
.ph-stat{position:relative;z-index:2;flex-shrink:0;text-align:center;color:var(--w);background:rgba(255,255,255,.12);border:1px solid rgba(255,255,255,.25);border-radius:16px;padding:16px 26px}
.ph-stat strong{display:block;font-size:1.8rem;font-weight:800;line-height:1.1}
.ph-stat span{font-size:.78rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:rgba(255,255,255,.75)}

@media (max-width:768px){.ph-box{padding:30px 20px}.ph-box h1{font-size:1.75rem}}

/* This page has no ambient container rule for .Wrp anywhere in the site's shared
   CSS — every page defines its own — so without this, .Sec.bg .Wrp rendered full
   viewport width edge-to-edge. Matches the same 1280px/20px convention as .ph-wrp
   above and the rest of the site's --container-max. */
.Sec.bg{padding:40px 0}
.Sec.bg .Wrp{max-width:1280px;margin:0 auto;padding:0 20px}

/* Category cards */
.cat-list{display:flex;flex-direction:column;gap:22px}

.cat-card{
    --accent:var(--p);
    background:var(--w);
    border-radius:var(--rad);
    border:1px solid #eef0f6;
    box-shadow:0 4px 16px -4px rgba(15,23,42,.06);
    overflow:hidden;
    transition:box-shadow .3s ease,border-color .3s ease;
}

.cat-card:hover{box-shadow:0 16px 32px -10px rgba(15,23,42,.14);border-color:color-mix(in srgb,var(--accent) 25%,#eef0f6)}

.cat-card-head{
    display:flex;
    align-items:center;
    gap:16px;
    padding:22px 26px;
    border-bottom:1px solid #f1f3f8;
}

.cat-card-icon{
    flex-shrink:0;
    width:52px;
    height:52px;
    border-radius:14px;
    background:linear-gradient(145deg,var(--accent),color-mix(in srgb,var(--accent) 65%,#0f172a));
    display:flex;
    align-items:center;
    justify-content:center;
    box-shadow:0 8px 16px -6px color-mix(in srgb,var(--accent) 55%,transparent);
}

.cat-card-icon img{width:28px;height:28px;object-fit:contain;filter:brightness(0) invert(1)}
.cat-card-icon span{color:#fff;font-weight:800;font-size:1.1rem}

.cat-card-title{flex:1;min-width:0;text-align:left}

.cat-card-title h2{
    margin:0;
    font-size:1.3rem;
    font-weight:800;
    color:#0f172a;
    line-height:1.25;
    text-align:left;
}

.cat-card-title h2 a{display:inline;color:inherit;text-decoration:none}
.cat-card-title h2 a:hover{color:var(--accent)}

.cat-card-count{
    margin-top:3px;
    font-size:.82rem;
    font-weight:600;
    color:var(--g);
}

.cat-view-all{
    flex-shrink:0;
    display:inline-flex;
    align-items:center;
    gap:6px;
    padding:9px 18px;
    border-radius:999px;
    background:color-mix(in srgb,var(--accent) 10%,white);
    color:var(--accent);
    text-decoration:none;
    font-weight:700;
    font-size:.85rem;
    white-space:nowrap;
    transition:all .25s ease;
}

.cat-view-all:hover{background:var(--accent);color:#fff;transform:translateY(-1px)}
.cat-view-all svg{width:13px;height:13px;transition:transform .25s ease}
.cat-view-all:hover svg{transform:translateX(2px)}

.cat-card-body{padding:18px 26px 24px}

/* Store chips (shared visual language for desktop grid + mobile marquee) */
.store-chip{
    display:inline-flex;
    align-items:center;
    gap:8px;
    padding:6px 14px 6px 6px;
    border-radius:999px;
    background:#f8f9fc;
    border:1px solid #eef0f6;
    color:#334155;
    text-decoration:none;
    font-size:.84rem;
    font-weight:600;
    white-space:nowrap;
    transition:all .2s ease;
}

.store-chip:hover,
.store-chip:focus-visible{
    background:color-mix(in srgb,var(--accent) 12%,white);
    border-color:color-mix(in srgb,var(--accent) 30%,#eef0f6);
    color:var(--accent);
}

.store-chip-logo{
    flex-shrink:0;
    width:26px;
    height:26px;
    border-radius:50%;
    background:#fff;
    border:1px solid #eef0f6;
    display:flex;
    align-items:center;
    justify-content:center;
    overflow:hidden;
}

.store-chip-logo img{width:100%;height:100%;object-fit:contain;padding:3px}
.store-chip-logo span{color:var(--accent);font-weight:800;font-size:.7rem}

.cat-store-grid{display:flex;flex-wrap:wrap;gap:9px}
.cat-store-empty{color:var(--g);font-size:.88rem;margin:0}

/* Mobile: continuous auto-scrolling ticker instead of a static wrapped chip list */
.cat-store-marquee{display:none}

@media (max-width:768px){
    .cat-store-grid{display:none}
    .cat-store-marquee{
        display:block;
        overflow:hidden;
        margin:0 -26px;
        padding:0 26px;
        -webkit-mask-image:linear-gradient(90deg,transparent 0,#000 26px,#000 calc(100% - 26px),transparent 100%);
        mask-image:linear-gradient(90deg,transparent 0,#000 26px,#000 calc(100% - 26px),transparent 100%);
    }
    .cat-store-track{
        display:flex;
        width:max-content;
        animation:catMarquee var(--marquee-duration,20s) linear infinite;
    }
    .cat-store-marquee:hover .cat-store-track,
    .cat-store-marquee:active .cat-store-track{animation-play-state:paused}
    .cat-store-group{display:flex;gap:9px;flex-shrink:0;padding-right:9px}
    @keyframes catMarquee{from{transform:translateX(0)}to{transform:translateX(-50%)}}
}

@media (prefers-reduced-motion:reduce){
    .cat-store-track{animation:none}
}

@media (max-width:768px){
    .cat-card-head{padding:18px 20px;gap:12px}
    .cat-card-body{padding:14px 20px 20px}
    .cat-card-icon{width:44px;height:44px;border-radius:12px}
    .cat-card-icon img{width:22px;height:22px}
    .cat-card-title h2{font-size:1.1rem}
    .cat-view-all span{display:none}
}

.no-categories{
    text-align:center;
    padding:60px 20px;
    background:var(--w);
    border-radius:var(--rad);
    border:2px dashed #e2e8f0;
}

.no-categories h3{margin:0 0 8px;color:#0f172a;font-size:1.3rem}
.no-categories p{margin:0;color:var(--g)}
</style>
@endpush

@section('content')
<!-- Page title <start> -->
<section class="ph-sec">
  <div class="ph-wrp">

    <nav aria-label="Breadcrumb" class="ph-nav">
      <ol class="ph-ol" itemscope itemtype="http://schema.org/BreadcrumbList">
        <li class="ph-li" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
          <a href="{{ route('home') }}" itemprop="item">
            <i class="fa-solid fa-house" aria-hidden="true"></i>
            <span itemprop="name">Home</span>
          </a>
          <meta itemprop="position" content="1">
          <i class="fa-solid fa-chevron-right ph-sep"></i>
        </li>

        <li class="ph-li" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
          <a href="javascript:;" itemprop="item" style="cursor: default; color: var(--p);">
            <i class="fa-solid fa-layer-group" aria-hidden="true"></i>
            <span itemprop="name">Categories</span>
          </a>
          <meta itemprop="position" content="2">
        </li>
      </ol>
    </nav>

    <div class="ph-box">
      <div class="ph-dec"></div>

      <div class="ph-cnt">
        <h1>Browse Categories</h1>
        <p>Find verified discount codes and deals organised by department — from fashion to electronics.</p>
      </div>

      @if(isset($categories) && count($categories) > 0)
        <div class="ph-stat">
          <strong>{{ count($categories) }}</strong>
          <span>{{ count($categories) == 1 ? 'Category' : 'Categories' }}</span>
        </div>
      @endif
    </div>

  </div>
</section>
<!-- Page title <end> -->

<!-- Dynamic Categories Section -->
<div class="Sec bg">
  <div class="Wrp">
    <div>
      @if(isset($categories) && count($categories) > 0)
        @php
            $catAccents = ['#2951c4', '#7c3aed', '#0891b2', '#d97706', '#db2777', '#059669'];
        @endphp
        <div class="cat-list">
          @foreach($categories as $index => $category)
            @php
                $accent = $catAccents[$index % count($catAccents)];
                $storeCount = $category->brands ? count($category->brands) : 0;
                $marqueeDuration = max(8, min(60, $storeCount * 2.2));
            @endphp
            <article class="cat-card" style="--accent: {{ $accent }};">
                <div class="cat-card-head">
                    <div class="cat-card-icon">
                        @if($category->media && file_exists(public_path(ltrim($category->media, '/'))))
                            <img src="{{ asset(ltrim($category->media, '/')) }}" alt="" loading="lazy" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                            <span style="display:none;">{{ substr($category->category_name, 0, 1) }}</span>
                        @else
                            <span>{{ substr($category->category_name, 0, 1) }}</span>
                        @endif
                    </div>

                    <div class="cat-card-title">
                        <h2><a href="{{ route('category', $category->seo_url) }}">{{ $category->category_name }}</a></h2>
                        <div class="cat-card-count">{{ $storeCount }} {{ $storeCount == 1 ? 'store' : 'stores' }}</div>
                    </div>

                    <a href="{{ route('category', $category->seo_url) }}" class="cat-view-all" title="{{ $category->category_name }}">
                        <span>View All</span>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                    </a>
                </div>

                <div class="cat-card-body">
                    @if($storeCount > 0)
                        {{-- Desktop / tablet: static wrapped chip grid --}}
                        <div class="cat-store-grid">
                            @foreach($category->brands as $brand)
                                @include('frontend.partials.store-chip', ['brand' => $brand])
                            @endforeach
                        </div>

                        {{-- Mobile: continuous auto-scrolling ticker (pauses on touch/hover) --}}
                        <div class="cat-store-marquee">
                            <div class="cat-store-track" style="--marquee-duration: {{ $marqueeDuration }}s;">
                                <div class="cat-store-group">
                                    @foreach($category->brands as $brand)
                                        @include('frontend.partials.store-chip', ['brand' => $brand])
                                    @endforeach
                                </div>
                                <div class="cat-store-group" aria-hidden="true">
                                    @foreach($category->brands as $brand)
                                        @include('frontend.partials.store-chip', ['brand' => $brand, 'decorative' => true])
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @else
                        <p class="cat-store-empty">No stores available in this category yet.</p>
                    @endif
                </div>
            </article>
          @endforeach
        </div>
        @else
        <div class="no-categories">
          <h3>No categories available</h3>
          <p>Please check back later for available categories.</p>
        </div>
      @endif
    </div>
  </div>
</div>
@endsection

@extends('frontend.layouts.app')

@section('title', 'All Categories | Hotsavinghub')
@section('description', 'Browse all shopping categories on Hotsavinghub to find the latest discount codes and voucher codes for your favorite UK brands.')

@push('styles')
<style>
.text-links-columns {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 15px;
    margin-top: 20px;
}

.column {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.column a {
    color: var(--g);
    text-decoration: none;
    font-size: 14px;
    transition: color 0.2s;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.column a:hover {
    color: var(--p);
}

.bx {
    background: var(--w);
    border-radius: var(--rad);
    padding: 30px;
    margin-bottom: 30px;
    box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);
    border: 1px solid #f1f5f9;
}

.ttl {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 25px;
    padding-bottom: 15px;
    border-bottom: 1px solid #f1f5f9;
}

.ttl h3 {
    margin: 0;
    font-size: 28px;
    font-weight: 700;
    color: var(--p-d);
}

.ttl h3 a {
    color: inherit;
    text-decoration: none;
}

.ttl .bp_visit {
    font-style: normal;
}

.ttl a[title*="View All"] {
    color: var(--p);
    text-decoration: none;
    font-weight: 600;
    font-size: 14px;
    display: flex;
    align-items: center;
    gap: 5px;
}

@media (max-width: 1024px) {
    .text-links-columns {
        grid-template-columns: repeat(3, 1fr);
    }
}

@media (max-width: 768px) {
    .text-links-columns {
        grid-template-columns: repeat(2, 1fr);
    }

    .ph-box {
        min-height: 100px;
    }
}

@media (max-width: 576px) {
    .text-links-columns {
        grid-template-columns: 1fr;
    }
}
</style>
<style>:root{--p:#2951c4;--p-d:#1e3a8a;--w:#fff;--g:#64748b;--l:#f8fafc;--rad:20px}.ph-sec{padding:20px 0 40px;background:var(--w);font-family:'Inter',system-ui,sans-serif}.ph-wrp{max-width:1280px;margin:0 auto;padding:0 20px}.ph-nav{margin-bottom:20px}.ph-ol{list-style:none;padding:0;margin:0;display:flex;align-items:center;flex-wrap:wrap;gap:10px;font-size:14px;color:var(--g)}.ph-li{display:flex;align-items:center}.ph-li a{text-decoration:none;color:var(--g);transition:color .2s;display:flex;align-items:center;gap:6px;font-weight:500}.ph-li a:hover{color:var(--p)}.ph-sep{font-size:10px;color:#cbd5e1;margin:0 4px}.ph-box{background:linear-gradient(135deg,var(--p) 0%,var(--p-d) 100%);padding:40px;border-radius:var(--rad);position:relative;overflow:hidden;box-shadow:0 10px 25px -5px rgba(41,81,196,0.25);display:flex;align-items:center;min-height:140px}.ph-cnt{position:relative;z-index:2;color:var(--w)}.ph-box h1{margin:0;font-size:2.5rem;font-weight:800;letter-spacing:-1px;line-height:1.1}.ph-dec{position:absolute;top:0;right:0;width:50%;height:100%;pointer-events:none;background:radial-gradient(circle at top right,rgba(255,255,255,0.1) 0%,transparent 60%)}@media (max-width:768px){.ph-box{padding:30px 20px}.ph-box h1{font-size:1.75rem}}</style>
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
        <h1>Browse By Categories</h1>
      </div>
    </div>

  </div>
</section>
<!-- Page title <end> -->

<!-- Dynamic Categories Section -->
<div class="Sec bg">
  <div class="Wrp">
    <div>
      @if(isset($categories) && count($categories) > 0)
        @foreach($categories as $category)
          <div class="bx">
            <div class="ttl">
              <a href="{{ route('category', $category->seo_url) }}" title="{{ $category->category_name }}">View All <i class="bp_visit"></i></a>
            </div>

            <div class="lnks">
              @if($category->brands && count($category->brands) > 0)
                <div class="text-links-columns">
                  @php
                    $brandsPerColumn = ceil(count($category->brands) / 7);
                    $columns = array_chunk($category->brands->toArray(), $brandsPerColumn);
                  @endphp

                  @foreach($columns as $columnBrands)
                    <div class="column">
                      @foreach($columnBrands as $brand)
                        <a href="{{ route('store', $brand['seo_url']) }}" title="{{ $brand['store_name'] }}">{{ $brand['store_name'] }}</a>
                      @endforeach
                    </div>
                  @endforeach
                </div>
              @endif
            </div>
          </div>
        @endforeach
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

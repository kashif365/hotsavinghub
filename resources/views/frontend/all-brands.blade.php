@extends('frontend.layouts.app')

@section('title', 'All Brands & Stores A-Z | Hotsavinghub')
@section('description', 'Browse all brands and stores alphabetically. Find discount codes and voucher codes from your favorite brands. Discover new stores and save money with verified offers.')
@section('keywords', 'all brands, all stores, brand directory, store directory, discount codes A-Z, voucher codes by brand')
@push('styles')
<link rel="preload" href="{{ asset('frontend_assets/css/fonts.css') }}" as="style" crossorigin>
<link rel="preload" href="{{ asset('frontend_assets/css/store.css') }}" as="style" crossorigin>
<link rel="stylesheet" href="{{ asset('frontend_assets/css/fonts.css') }}" crossorigin>
<link rel="stylesheet" href="{{ asset('frontend_assets/css/store.css') }}" as="style" crossorigin>
<style>
/* Custom styles for All Brands page */
.rg {
    display: flex;
    flex-wrap: wrap;
    gap: 5px;
    margin-bottom: 20px;
    justify-content: center;
}

.rg a {
    display: inline-block;
    padding: 8px 12px;
    background-color: #f0f0f0;
    color: #333;
    text-decoration: none;
    border-radius: 4px;
    border: 1px solid #ddd;
    font-weight: 500;
    transition: all 0.3s ease;
}

.rg a:hover {
    background-color: #e0e0e0;
}

.rg a.active {
    background-color: #28a745;
    color: white;
    border-color: #28a745;
}

.bx {
    margin-bottom: 30px;
}

.bx .ttl h3 {
    font-size: 24px;
    font-weight: bold;
    margin-bottom: 15px;
    color: #333;
    border-bottom: 2px solid var(--primary-color, #2951c4);
    padding-bottom: 5px;
}

.lnks {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 8px;
    column-gap: 20px;
}

.lnks a {
    display: block;
    padding: 8px 12px;
    color: #333;
    text-decoration: none;
    border-radius: 4px;
    transition: all 0.3s ease;
    font-size: 14px;
}

.lnks a:hover {
    background-color: #f8f9fa;
    color: var(--primary-color, #2951c4);
    text-decoration: underline;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .lnks {
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        column-gap: 15px;
    }

    .rg {
        justify-content: flex-start;
    }

    .rg a {
        padding: 6px 10px;
        font-size: 14px;
    }
}

@media (max-width: 480px) {
    .lnks {
        grid-template-columns: 1fr;
    }

    .rg a {
        padding: 5px 8px;
        font-size: 12px;
    }
}
</style>
@endpush

@section('content')
<!-- Page Content <start> -->
<div class="pgHd">
  <div class="Wrp">
    <!-- Breadcrumb <start> -->
    <ul class="brdcrb" itemscope="" itemtype="http://schema.org/BreadcrumbList">
        <li itemprop="itemListElement" itemscope="" itemtype="http://schema.org/ListItem">
            <a href="{{ route('home') }}" class="link" itemprop="item">
                <span itemprop="name">Home</span>
                <meta itemprop="position" content="1">
            </a>
        </li>
        <li itemprop="itemListElement" itemscope="" itemtype="http://schema.org/ListItem">
            <a href="{{ route('all-brands') }}" class="link active" itemprop="item">
                <span itemprop="name">All Stores</span>
                <meta itemprop="position" content="2">
            </a>
        </li>
    </ul>
    <!-- Breadcrumb <end> -->
    <!-- Page title <start> -->
    <h1 class="ttl">All Stores</h1>
    <!-- Page title <end> -->
  </div>
</div>
<!-- sidebar wrp -->
<div class="Sec bg">
  <div class="Wrp">
    <div class="bg">
        <!-- alphabetical <start> -->
        <div class="rg">
            <a href="{{ route('all-brands') }}?q=0-9" title="0-9" class="{{ $query == '0-9' ? 'active' : '' }}">0-9</a>
            <a href="{{ route('all-brands') }}?q=a" title="A" class="{{ $query == 'a' ? 'active' : '' }}">A</a>
            <a href="{{ route('all-brands') }}?q=b" title="B" class="{{ $query == 'b' ? 'active' : '' }}">B</a>
            <a href="{{ route('all-brands') }}?q=c" title="C" class="{{ $query == 'c' ? 'active' : '' }}">C</a>
            <a href="{{ route('all-brands') }}?q=d" title="D" class="{{ $query == 'd' ? 'active' : '' }}">D</a>
            <a href="{{ route('all-brands') }}?q=e" title="E" class="{{ $query == 'e' ? 'active' : '' }}">E</a>
            <a href="{{ route('all-brands') }}?q=f" title="F" class="{{ $query == 'f' ? 'active' : '' }}">F</a>
            <a href="{{ route('all-brands') }}?q=g" title="G" class="{{ $query == 'g' ? 'active' : '' }}">G</a>
            <a href="{{ route('all-brands') }}?q=h" title="H" class="{{ $query == 'h' ? 'active' : '' }}">H</a>
            <a href="{{ route('all-brands') }}?q=i" title="I" class="{{ $query == 'i' ? 'active' : '' }}">I</a>
            <a href="{{ route('all-brands') }}?q=j" title="J" class="{{ $query == 'j' ? 'active' : '' }}">J</a>
            <a href="{{ route('all-brands') }}?q=k" title="K" class="{{ $query == 'k' ? 'active' : '' }}">K</a>
            <a href="{{ route('all-brands') }}?q=l" title="L" class="{{ $query == 'l' ? 'active' : '' }}">L</a>
            <a href="{{ route('all-brands') }}?q=m" title="M" class="{{ $query == 'm' ? 'active' : '' }}">M</a>
            <a href="{{ route('all-brands') }}?q=n" title="N" class="{{ $query == 'n' ? 'active' : '' }}">N</a>
            <a href="{{ route('all-brands') }}?q=o" title="O" class="{{ $query == 'o' ? 'active' : '' }}">O</a>
            <a href="{{ route('all-brands') }}?q=p" title="P" class="{{ $query == 'p' ? 'active' : '' }}">P</a>
            <a href="{{ route('all-brands') }}?q=q" title="Q" class="{{ $query == 'q' ? 'active' : '' }}">Q</a>
            <a href="{{ route('all-brands') }}?q=r" title="R" class="{{ $query == 'r' ? 'active' : '' }}">R</a>
            <a href="{{ route('all-brands') }}?q=s" title="S" class="{{ $query == 's' ? 'active' : '' }}">S</a>
            <a href="{{ route('all-brands') }}?q=t" title="T" class="{{ $query == 't' ? 'active' : '' }}">T</a>
            <a href="{{ route('all-brands') }}?q=u" title="U" class="{{ $query == 'u' ? 'active' : '' }}">U</a>
            <a href="{{ route('all-brands') }}?q=v" title="V" class="{{ $query == 'v' ? 'active' : '' }}">V</a>
            <a href="{{ route('all-brands') }}?q=w" title="W" class="{{ $query == 'w' ? 'active' : '' }}">W</a>
            <a href="{{ route('all-brands') }}?q=x" title="X" class="{{ $query == 'x' ? 'active' : '' }}">X</a>
            <a href="{{ route('all-brands') }}?q=y" title="Y" class="{{ $query == 'y' ? 'active' : '' }}">Y</a>
            <a href="{{ route('all-brands') }}?q=z" title="Z" class="{{ $query == 'z' ? 'active' : '' }}">Z</a>
        </div>
        <!-- alphabetical <end> -->

        @if(isset($storesByLetter) && count($storesByLetter) > 0)
            @foreach($storesByLetter as $letter => $stores)
                @if($stores->count() > 0)
                <!-- single box <start> -->
                <div class="bx">
                    <div class="ttl">
                        <h3>{{ strtoupper($letter) }}</h3>
                    </div>
                    <div class="lnks">
                        @foreach($stores as $store)
                            <a href="{{ route('store', $store->seo_url) }}" title="{{ $store->store_name }}">{{ $store->store_name }}</a>
                        @endforeach
                    </div>
                </div>
                <!-- single box <end> -->
                @endif
            @endforeach
        @else
            <!-- single box <start> -->
            <div class="bx">
                <div class="ttl">
                    <h3>{{ strtoupper($query ?? 'A') }}</h3>
                </div>
                <div class="lnks">
                    @if(isset($stores) && $stores->count() > 0)
                        @foreach($stores as $store)
                            <a href="{{ route('store', $store->seo_url) }}" title="{{ $store->store_name }}">{{ $store->store_name }}</a>
                        @endforeach
                    @else
                        <p>No stores found for this letter.</p>
                    @endif
                </div>
            </div>
            <!-- single box <end> -->
        @endif

    </div>
  </div>
</div>
<!-- sidebar wrp <end> -->

@endsection

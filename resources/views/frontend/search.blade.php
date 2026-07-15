@extends('frontend.layouts.app')

@section('title', 'Search Results for "' . $query . '" | Hotsavinghub')
@section('description', 'Search results for "' . $query . '" - Find the best discount codes and voucher codes from top UK brands. Browse stores, coupons, and categories.')
@section('keywords', 'search discount codes, search voucher codes, find deals, search coupons, ' . $query)

@section('content')
<div class="Sec">
    <div class="Wrp">
        <h1>Search Results for "{{ $query }}"</h1>

        @if($stores->count() > 0 || $coupons->count() > 0 || $categories->count() > 0)
            <!-- Stores Results -->
            @if($stores->count() > 0)
            <div class="search-section">
                <h2>Stores ({{ $stores->count() }})</h2>
                <div class="stores-grid">
                    @foreach($stores as $store)
                    <div class="store-item">
                        <a href="{{ route('store', $store->seo_url) }}" title="{{ $store->store_name }}">
                            @if($store->store_logo)
                                <img src="{{ asset( $store->store_logo) }}" alt="{{ $store->store_name }}" width="150" height="150">
                            @else
                                <div class="store-placeholder">{{ substr($store->store_name, 0, 2) }}</div>
                            @endif
                            <h3>{{ $store->store_name }}</h3>
                        </a>
                    </div>
                    @endforeach
                </div>
                @if($stores->hasPages())
                    <div class="pagination-wrapper">
                        {{ $stores->links() }}
                    </div>
                @endif
            </div>
            @endif

            <!-- Coupons Results -->
            @if($coupons->count() > 0)
            <div class="search-section">
                <h2>Coupons & Offers ({{ $coupons->count() }})</h2>
                <div class="cpns">
                    @foreach($coupons as $coupon)
                    <div class="cpn {{ $coupon->exclusive ? 'exclusive' : '' }} {{ $coupon->verified ? 'verified' : '' }}" data-id="{{ $coupon->id }}">
                        <div class="imgs cvr">
                            @if($coupon->cover_logo)
                                <img decoding="async" class="cvr" src="{{ asset( $coupon->cover_logo) }}" alt="{{ $coupon->coupon_title }}" title="{{ $coupon->coupon_title }}" width="328" height="160">
                            @endif
                            @if($coupon->store && $coupon->store->store_logo)
                                <a href="{{ route('store', $coupon->store->seo_url) }}" title="{{ $coupon->store->store_name }}">
                                    <img decoding="async" loading="lazy" src="{{ asset( $coupon->store->store_logo) }}" alt="{{ $coupon->store->store_name }} discount code" title="{{ $coupon->store->store_name }} discount code" width="80" height="80">
                                </a>
                            @endif
                        </div>
                        <div class="cnt">
                            <div class="str-vrf">
                                @if($coupon->store)
                                    <a href="{{ route('store', $coupon->store->seo_url) }}" title="{{ $coupon->store->store_name }}">{{ $coupon->store->store_name }}</a>
                                @endif
                                @if($coupon->verified)
                                    <span>Verified</span>
                                @endif
                            </div>
                            <h3>{{ $coupon->coupon_title }}</h3>
                            <div class="trm-cnt">
                                @if($coupon->terms)
                                    <button aria-label="View Terms" class="ctb">View Terms</button>
                                @endif
                                <span>{{ $coupon->sort_order ?? '0' }} Used</span>
                            </div>
                            @if($coupon->coupon_code)
                                <button class="cpBtn" aria-label="Reveal Code" data-code="{{ $coupon->coupon_code }}">Reveal Code</button>
                            @else
                                <a href="{{ $coupon->affiliate_url }}" class="cpBtn" target="_blank" rel="nofollow">Get Deal</a>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
                @if($coupons->hasPages())
                    <div class="pagination-wrapper">
                        {{ $coupons->links() }}
                    </div>
                @endif
            </div>
            @endif

            <!-- Categories Results -->
            @if($categories->count() > 0)
            <div class="search-section">
                <h2>Categories ({{ $categories->count() }})</h2>
                <div class="cat-grid">
                    @foreach($categories as $category)
                    <div class="cat-item">
                        <a href="{{ route('category', $category->seo_url) }}" title="{{ $category->category_name }}">
                            @if($category->media)
                                <img src="{{ asset( $category->media) }}" alt="{{ $category->category_name }}" width="100" height="100">
                            @else
                                <div class="cat-icon">{{ substr($category->category_name, 0, 1) }}</div>
                            @endif
                            <h3>{{ $category->category_name }}</h3>
                        </a>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        @else
            <div class="no-results">
                <h2>No results found for "{{ $query }}"</h2>
                <p>Try searching for different keywords or browse our categories:</p>
                <div class="suggestions">
                    <a href="{{ route('categories') }}" class="btn">Browse Categories</a>
                    <a href="{{ route('top-discounts') }}" class="btn">Top Discounts</a>
                    <a href="{{ route('all-brands') }}" class="btn">All Brands</a>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection

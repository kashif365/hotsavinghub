@extends('frontend.layouts.app')

@section('title', $store->meta_title ?? $store->store_name)
@section('description', $store->meta_description ?? 'Get the latest ' . $store->store_name . ' discount codes, voucher codes, and promo codes. Save money on your purchases with verified offers. Updated regularly with fresh deals.')

@push('meta')
    {{-- Canonical Link --}}
    @if(!empty($store->canonical_url))
        {{-- Use the manual canonical URL if provided --}}
        <link rel="canonical" href="{{ $store->canonical_url }}">
    @elseif(!empty($store->seo_url))
        {{-- Fallback: Generate the canonical link using the store's seo_url --}}
        <link rel="canonical" href="{{ route('store', ['slug' => $store->seo_url]) }}">
    @endif

    {{-- Meta Keywords --}}
    @if(!empty($store->meta_keywords))
        <meta name="keywords" content="{{ $store->meta_keywords }}">
    @else
        {{-- Default keywords based on store name if field is empty --}}
        <meta name="keywords" content="{{ $store->store_name }} discount codes, {{ $store->store_name }} voucher codes, {{ $store->store_name }} promo codes, {{ $store->store_name }} coupons">
    @endif
@endpush

@if($store->schema && trim($store->schema) !== '' && trim($store->schema) !== 'test')
    @php
        $schemaContent = trim($store->schema);
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
<link rel="preload" href="{{ asset('frontend_assets/js/store.js') }}" as="script" crossorigin>
@endpush

@push('scripts')
<script src="{{ asset('frontend_assets/js/store.js') }}" async crossorigin></script>
@endpush

@section('content')



<!-- Page Content <start> -->
<input type="radio" name="cpnflt" id="cpnall" checked>
<input type="radio" name="cpnflt" id="cpncd">
<input type="radio" name="cpnflt" id="cpnfs">
<input type="radio" name="cpnflt" id="cpndl">

<div class="pgHd">
  <div class="Wrp">
    <!-- Breadcrumb <start> -->
    <ul class="brdcrb" itemscope itemtype="http://schema.org/BreadcrumbList">
      <li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
        <a href="{{ url('/') }}" class="link" itemprop="item">
          <span itemprop="name">Home</span>
          <meta itemprop="position" content="1">
        </a>
      </li>
      <li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
        <a href="{{ route('all-brands') }}" class="link" itemprop="item">
          <span itemprop="name">All Brands</span>
          <meta itemprop="position" content="2">
        </a>
      </li>
      <li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
        <a href="{{ route('store', $store->seo_url) }}" class="link active" itemprop="item">
          <span itemprop="name">{{ $store->store_name }}</span>
          <meta itemprop="position" content="3">
        </a>
      </li>
    </ul>
    <!-- Breadcrumb <end> -->

   <div class="hsh-sh-wrapper">
    <div class="hsh-sh-container">
        <div class="hsh-sh-logo-box">
            @if($store->store_logo && file_exists(public_path(ltrim($store->store_logo, '/'))))
                <img src="{{ asset(ltrim($store->store_logo, '/')) }}"
                     class="hsh-sh-img"
                     alt="{{ $store->store_name }} discount code"
                     title="{{ $store->store_name }} discount code"
                     width="140" height="140" loading="lazy" decoding="async"
                     onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                <div class="hsh-sh-placeholder" style="display: none;">{{ substr($store->store_name, 0, 2) }}</div>
            @else
                <div class="hsh-sh-placeholder">{{ substr($store->store_name, 0, 2) }}</div>
            @endif

            <button class="hsh-sh-fav bp_hrt" data-id="{{ $store->id }}" aria-label="Add to favorites">
                <i class="fa-solid fa-heart"></i>
            </button>
        </div>

        <div class="hsh-sh-content">
            <div class="hsh-sh-badge">
                <i class="fa-solid fa-circle-check"></i>
                <span>Verified Offers Today</span>
            </div>

            <h1>{{ $store->store_name }}</h1>

            <p class="hsh-sh-desc">
                Maximize your savings with <strong>{{ $storeCoupons->count() }}</strong> active {{ $store->store_name }} voucher codes and exclusive deals curated for UK shoppers.
            </p>

            <div class="hsh-sh-rating">
                <div class="hsh-stars">
                    <i class="fa-solid fa-star active" onclick="storeRating(1, {{ $store->id }}, '{{ request()->ip() }}')"></i>
                    <i class="fa-solid fa-star active" onclick="storeRating(2, {{ $store->id }}, '{{ request()->ip() }}')"></i>
                    <i class="fa-solid fa-star active" onclick="storeRating(3, {{ $store->id }}, '{{ request()->ip() }}')"></i>
                    <i class="fa-solid fa-star active" onclick="storeRating(4, {{ $store->id }}, '{{ request()->ip() }}')"></i>
                    <i class="fa-solid fa-star" onclick="storeRating(5, {{ $store->id }}, '{{ request()->ip() }}')"></i>
                </div>
                <span class="hsh-rating-text">Excellent (4.8/5 based on 21 reviews)</span>
            </div>
        </div>

        <div class="hsh-sh-actions">
            <a href="{{ $store->affiliate_url ?? url('/') }}"
               class="hsh-visit-btn affiliate"
               data-aff-id="{{ $store->id }}"
               target="_blank"
               rel="nofollow noopener noreferrer">
                <span>Shop {{ $store->store_name }}</span>
                <i class="fa-solid fa-arrow-up-right-from-square"></i>
            </a>
            <p style="font-size: 0.8rem; color: var(--t-l); margin: 0;">
                <i class="fa-solid fa-shield-halved" style="color: #6366f1;"></i> Link verified safe
            </p>
        </div>
    </div>
</div>
<style>:root{--p:#2951c4;--p-h:#1e3fa3;--p-l:rgba(41,81,196,0.06);--t-d:#0f172a;--t-l:#475569;--w:#ffffff;--gold:#ffb800;--sh:0 20px 40px -10px rgba(0,0,0,0.05);--tr:all 0.3s ease}.hsh-sh-wrapper{background:var(--w);padding:3rem 0;border-bottom:1px solid #f1f5f9;font-family:Inter,system-ui,sans-serif}.hsh-sh-container{max-width:1200px;margin:0 auto;padding:0 1.5rem;display:flex;align-items:center;gap:3rem}.hsh-sh-logo-box{position:relative;width:140px;height:140px;flex-shrink:0}.hsh-sh-img{width:100%;height:100%;object-fit:contain;padding:15px;background:var(--w);border-radius:24px;border:1px solid #f1f5f9;box-shadow:var(--sh);transition:var(--tr)}.hsh-sh-placeholder{width:100%;height:100%;display:flex;align-items:center;justify-content:center;background:var(--p);color:var(--w);font-size:2.5rem;font-weight:800;border-radius:24px;text-transform:uppercase}.hsh-sh-fav{position:absolute;top:-10px;right:-10px;width:40px;height:40px;border-radius:50%;background:var(--w);border:1px solid #f1f5f9;color:var(--t-l);cursor:pointer;display:flex;align-items:center;justify-content:center;box-shadow:0 4px 10px rgba(0,0,0,0.1);transition:var(--tr);z-index:2}.hsh-sh-fav:hover{color:#ef4444;transform:scale(1.1)}.hsh-sh-content{flex-grow:1}.hsh-sh-badge{display:inline-flex;align-items:center;gap:6px;background:#f0fdf4;color:#16a34a;padding:6px 14px;border-radius:100px;font-size:0.85rem;font-weight:700;margin-bottom:1rem}.hsh-sh-content h1{font-size:2.25rem;font-weight:950;color:var(--t-d);margin:0 0 0.75rem;letter-spacing:-0.03em;line-height:1.2}.hsh-sh-desc{font-size:1.1rem;color:var(--t-l);margin-bottom:1.25rem}.hsh-sh-rating{display:flex;align-items:center;gap:12px}.hsh-stars{display:flex;gap:4px;color:#e2e8f0;font-size:1.1rem;cursor:pointer}.hsh-stars .active{color:var(--gold)}.hsh-rating-text{font-size:0.9rem;font-weight:600;color:var(--t-l)}.hsh-sh-actions{display:flex;flex-direction:column;align-items:flex-end;gap:1rem}.hsh-visit-btn{background:var(--p);color:var(--w);padding:14px 28px;border-radius:14px;font-weight:800;text-decoration:none;display:flex;align-items:center;gap:10px;transition:var(--tr);box-shadow:0 10px 20px -5px rgba(41,81,196,0.3)}.hsh-visit-btn:hover{background:var(--p-h);transform:translateY(-2px);box-shadow:0 15px 30px -5px rgba(41,81,196,0.4)}@media (max-width:992px){.hsh-sh-container{flex-direction:column;text-align:center;gap:1.5rem}.hsh-sh-actions{align-items:center}.hsh-sh-content h1{font-size:1.75rem}}</style>
<style>:root{--p:#2951c4;--p-s:#1e3da1;--p-l:rgba(41,81,196,0.08);--w:#ffffff;--b:#0f172a;--g:#64748b;--s:#e2e8f0;--success:#22c55e;--shadow:0 10px 15px -3px rgba(0,0,0,0.06),0 4px 6px -4px rgba(0,0,0,0.05);--rad:16px;--trans:all 0.3s cubic-bezier(0.4,0,0.2,1)}.hsh-wrap{font-family:'Inter',system-ui,-apple-system,sans-serif;color:var(--b);background:#fcfdfe;padding:40px 0}.hsh-grid{display:grid;grid-template-columns:1fr 350px;gap:30px;max-width:1320px;margin:0 auto;padding:0 20px}.hsh-coupon-card{background:var(--w);border:1px solid var(--s);border-radius:var(--rad);padding:24px;margin-bottom:20px;display:flex;align-items:center;gap:24px;position:relative;transition:var(--trans);box-shadow:var(--shadow)}.hsh-coupon-card:hover{transform:translateY(-4px);border-color:var(--p);box-shadow:0 20px 25px -5px rgba(0,0,0,0.1)}.hsh-logo-box{width:100px;height:100px;flex-shrink:0;border:1px solid var(--s);border-radius:12px;display:flex;align-items:center;justify-content:center;padding:10px;background:var(--w)}.hsh-content{flex:1}.hsh-tag{display:inline-flex;align-items:center;gap:6px;padding:4px 12px;border-radius:100px;font-size:12px;font-weight:700;margin-bottom:10px}.hsh-tag-verified{background:#f0fdf4;color:var(--success)}.hsh-title{font-size:1.25rem;font-weight:800;margin:0 0 10px;line-height:1.4;cursor:pointer}.hsh-meta{display:flex;gap:15px;font-size:13px;color:var(--g);font-weight:500}.hsh-btn{height:50px;padding:0 25px;border-radius:12px;font-weight:700;display:flex;align-items:center;justify-content:center;gap:10px;cursor:pointer;transition:var(--trans);border:none;min-width:160px;text-decoration:none}.hsh-btn-p{background:var(--p);color:var(--w)}.hsh-btn-p:hover{background:var(--p-s)}.hsh-btn-s{background:var(--b);color:var(--w)}.hsh-sidebar-wgt{background:var(--w);border-radius:var(--rad);padding:24px;border:1px solid var(--s);margin-bottom:24px;box-shadow:var(--shadow)}.hsh-wgt-hd{font-size:1.1rem;font-weight:800;margin-bottom:20px;display:flex;align-items:center;gap:10px;border-bottom:2px solid var(--p-l);padding-bottom:10px}.hsh-newsletter{background:var(--p);color:var(--w);padding:40px;border-radius:var(--rad);text-align:center;margin:40px 0}.hsh-nl-form{display:flex;gap:10px;background:rgba(255,255,255,0.1);padding:8px;border-radius:14px;margin-top:20px}.hsh-nl-form input{flex:1;background:transparent;border:none;color:var(--w);padding:10px 15px;outline:none}.hsh-nl-form input::placeholder{color:rgba(255,255,255,0.7)}.hsh-nl-form button{background:var(--w);color:var(--p);border:none;padding:0 25px;border-radius:10px;font-weight:800;cursor:pointer}.hsh-table{width:100%;border-collapse:collapse;margin-top:15px}.hsh-table th{text-align:left;background:var(--p-l);padding:12px;font-size:13px}.hsh-table td{padding:12px;border-bottom:1px solid var(--s);font-size:14px}@media (max-width:1024px){.hsh-grid{grid-template-columns:1fr}.hsh-sidebar{order:2}}@media (max-width:640px){.hsh-coupon-card{flex-direction:column;text-align:center}.hsh-btn{width:100%}}</style>
<!-- sidebar wrp -->
<div class="Sec bg">
  <div class="splt Wrp">
    <!-- coupon side -->
    <div class="wgtc">
      <div class="cpns wd">
        @if($storeCoupons->count() > 0)
            @foreach($storeCoupons as $coupon)
            <!-- coupon:code <start> -->
            <div class="cpn {{ $coupon->coupon_code ? 'cd' : 'dl' }} {{ $coupon->free_shipping ? 'fs' : '' }} {{ $coupon->student_offer ? 'std' : '' }} {{ request('highlight') == $coupon->id ? 'highlighted' : '' }}" data-id="{{ $coupon->id }}">
                <button title="Add to Favourite" class="cfb bp_save hideIconS" aria-label="Add to Favourite"></button>

                <a class="clgo" href="javascript:;" title="{{ $store->store_name }} Vouchers Code">
                    @if($store->store_logo)
                        <img src="{{ asset( $store->store_logo) }}" alt="{{ $store->store_name }} discount code" title="{{ $store->store_name }} discount code" decoding="async" loading="lazy" width="90" height="90">
                    @else
                        <div class="store-logo-placeholder">{{ substr($store->store_name, 0, 2) }}</div>
                    @endif
                </a>

                <div class="ccnt">
                    <div class="ctp">
                        @if($coupon->verified)
                            <span class="cvrf">Verified</span>
                        @endif
                    </div>
                    <h3 role="button" aria-label="{{ $coupon->coupon_code ? 'Reveal Code' : 'Get Deal' }}" title="{{ $coupon->coupon_title }}">
                        {{ $coupon->coupon_title }}
                    </h3>

                    <div class="cbt">
                        @if($coupon->terms)
                            <button class="ctb" title="Terms" aria-label="Details">Details</button>
                        @endif
                        <span class="cusd" data-coupon-id="{{ $coupon->id }}" data-used-count="{{ $coupon->used_count ?? $coupon->sort_order ?? rand(500, 5000) }}">
                            <span class="total-used">{{ number_format($coupon->used_count ?? $coupon->sort_order ?? rand(500, 5000)) }}</span> Used - <span class="today-used">{{ $coupon->today_usage_count ?? 0 }}</span> Today
                        </span>
                    </div>
                </div>

                @if($coupon->student_offer)
                    <button class="cpBtn get-code" title="Get Code" aria-label="Get Code"
                            data-code="{{ $coupon->coupon_code ?? '' }}"
                            data-affiliate="{{ $coupon->affiliate_url ?? $store->affiliate_url ?? url('/') }}"
                            data-store="{{ $store->store_name }}"
                            data-title="{{ $coupon->coupon_title }}">
                        Get Code
                    </button>
                @elseif($coupon->coupon_code)
                    <button class="cpBtn reveal-code" title="Reveal Code" aria-label="Reveal Code"
                            data-code="{{ $coupon->coupon_code }}"
                            data-affiliate="{{ $coupon->affiliate_url ?? $store->affiliate_url ?? url('/') }}"
                            data-store="{{ $store->store_name }}"
                            data-title="{{ $coupon->coupon_title }}">
                        Reveal Code
                    </button>
                @else
                    <button class="cpBtn get-deal" aria-label="Get Deal"
                            data-affiliate="{{ $coupon->affiliate_url ?? $store->affiliate_url }}"
                            data-store="{{ $store->store_name }}"
                            data-title="{{ $coupon->coupon_title }}">
                        Get Deal
                    </button>
                @endif

                @if($coupon->terms)
                <div class="ctc" style="display: none;">
                    <h3>Terms & Conditions</h3>
                    <div class="dyncnt">
                        {!! nl2br(e($coupon->terms)) !!}
                    </div>
                </div>
                @endif
            </div>
            <!-- coupon:code <end> -->
            @endforeach

            <!-- Newsletter Section -->
            <div class="snlsec wide">
                <h2>Stay Updated – Never Miss a {{ $store->store_name }} Voucher Code Again!</h2>
                <p>Be the first one to get notified as soon as we update a new offer or discount.</p>

                <form id="newsletterForm" class="snfld" novalidate>
                @csrf
                <input type="text" name="email" id="newsletterEmail" placeholder="Enter Your Email Address Here" required>
                <button type="submit" class="nfb" title="Subscribe" id="newsletterBtn">Subscribe</button>
            </form>
            <div id="newsletterMessage" style="margin-top: 10px; display: none; padding: 8px; border-radius: 4px; font-weight: 500; text-align: center;"></div>

                <p>By signing up I agree to Hotsavinghub's <a href="{{ route('privacy-policy') }}" target="_blank">Privacy Policy</a> and consent to receive emails about offers.</p>
            </div>
        @else
            <div class="no-coupons">
                <p>No active coupons available for {{ $store->store_name }} at the moment. Check back soon for new offers!</p>
            </div>
        @endif
      </div>



      <!-- store table <start> -->
      <div class="crd tbl">
        <h3 class="hd">Save Big with {{ $store->store_name }} Discount Codes – {{ date('d F Y') }}!</h3>
        <table>
          <tr>
            <th>Offers</th>
            <th>Last Checked</th>
            <th>Code</th>
          </tr>
          @if($storeCoupons->count() > 0)
            @foreach($storeCoupons->take(4) as $coupon)
            <tr>
              <td>{{ $coupon->coupon_title }}</td>
              <td>{{ date('jS M Y') }}</td>
              <td>{{ $coupon->coupon_code ? '*******' : 'Deal' }}</td>
            </tr>
            @endforeach
          @endif
          <tr>
            <td class="tcntr" colspan="3">Updated: {{ date('d/m/Y') }}</td>
          </tr>
        </table>
      </div>
      <!-- store table <end> -->

      <!-- store faq <start> -->
      @if(!empty($store->faqs))
        <div class="crd faqs" id="srtFaq">
          <h3 class="hd">FAQ</h3>
              <div class="faq">
              {!! $store->faqs !!}
          </div>
        </div>
      @endif
      <!-- store faq <end> -->

      <!-- store more content <start> -->
      @if(!empty($store->detail_description))
      <div class="crd" id="abtStr">
        <h3 class="hd">More About {{ $store->store_name }}</h3>
        <div class="cnt 3">
          {!! $store->detail_description !!}
        </div>
      </div>
      @endif
      <!-- store more content <end> -->
    </div>

    <!-- sidebar -->
    <div class="wgts">

      <!-- rating -->
      <div class="wgt rating-box">
        <h3>How Did We Do? Rate {{ $store->store_name }} Vouchers Today!</h3>
        <div class="rating mb-3">
          <input type="radio" id="star1" name="rating" value="1">
          <label class="bp_str rated" for="star1" onclick="storeRating(1, {{ $store->id }}, '{{ request()->ip() }}')"></label>
          <input type="radio" id="star2" name="rating" value="2">
          <label class="bp_str rated" for="star2" onclick="storeRating(2, {{ $store->id }}, '{{ request()->ip() }}')"></label>
          <input type="radio" id="star3" name="rating" value="3">
          <label class="bp_str rated" for="star3" onclick="storeRating(3, {{ $store->id }}, '{{ request()->ip() }}')"></label>
          <input type="radio" id="star4" name="rating" value="4">
          <label class="bp_str rated" for="star4" onclick="storeRating(4, {{ $store->id }}, '{{ request()->ip() }}')"></label>
          <input type="radio" id="star5" name="rating" value="5">
          <label class="bp_str" for="star5" onclick="storeRating(5, {{ $store->id }}, '{{ request()->ip() }}')"></label>
        </div>
        <p class="ratingCalculator">Rated 4 from 21 votes</p>
      </div>
      <!-- rating end -->

      <!-- Expert Review -->
      <div class="wgt">
        <div class="pst">
          <div class="hd">
            <!-- <img src="{{ asset('frontend_assets/images/Female-01.png') }}" alt="Anna Lawrence" decoding="async" loading="lazy" width="64" height="64"> -->
            <div>
              <h3>Why we love shopping at {{ $store->store_name }} <i class="bp_hrt"></i></h3>
              <!-- <span>by <a href="{{ url('/') }}">Anna Lawrence</a></span>
              <span>Content Executive - Interior and Pets</span> -->
            </div>
          </div>
          <div class="cnt">
            <p>{{ $store->content }}</p>
          </div>
        </div>
      </div>

      <!-- Today's Discount Code -->
      <div class="wgt today-discount-code">
        <div class="padding-div">
          <h3>Today's Hand Tested Discount Code</h3>
          <p class="last-update">Last updated: <span>{{ date('d-M-Y') }}</span></p>
          <ol>
            <li>Voucher Codes: <span>{{ $storeCoupons->where('coupon_code', '!=', null)->count() }}</span></li>
            <li>Deals: <span>{{ $storeCoupons->where('coupon_code', null)->count() }}</span></li>
          </ol>
        </div>
        <span class="total-offers">Total Offers: <span>{{ $storeCoupons->count() }}</span></span>
      </div>

      <!-- Filter by -->
      <div class="wgt">
        <h3>Filter by</h3>
        <div class="flts">
          <label class="cfltr" for="cpnall">All</label>
          <label class="cfltr" for="cpncd">Voucher Code</label>
          <label class="cfltr" for="cpnfs">Free Delivery</label>
          <label class="cfltr" for="cpndl">Online Sale</label>
        </div>

        <!-- quick links <start> -->
        <div class="qL" id="qucklinks">
          <div>Quick Links</div>
          <a href="#abtStr">About {{ $store->store_name }}</a>
          <a href="#tpHntsss" title="Hints and Tips">Hints and Tips</a>
          <a href="#srtFaq" title="{{ $store->store_name }}">FAQs</a>
        </div>
        <!-- quick links <end> -->
      </div>
      <!-- filters <end> -->

      <!-- What Makes -->
      <div class="wgt">
        <h3>What Makes <i class="bp_hrt"></i> {{ $store->store_name }} Special?</h3>
        <ol>
          <li><i class="bp_fr-dls"></i> Free Deals</li>
          <li><i class="bp_fr-dl"></i> Free Delivery</li>
          <li><i class="bp_st-ofr"></i> Student Offers</li>
        </ol>
      </div>
      <!-- What Makes end -->

      <!-- Social -->
      <div class="wgt">
        <h3>Become a Member of the {{ $store->store_name }} Social Club!</h3>
        <div class="scl">
          @if($store->facebook_url)
            <a href="{{ $store->facebook_url }}" target="_blank" class="bp_fb" title="Facebook" aria-label="Facebook"></a>
          @endif
          @if($store->twitter_url)
            <a href="{{ $store->twitter_url }}" target="_blank" title="Twitter" aria-label="Twitter">
              <svg style="width: 20px; fill: #000;" xmlns="http://www.w3.org/2000/svg" viewbox="0 0 512 512"><path d="M389.2 48h70.6L305.6 224.2 487 464H345L233.7 318.6 106.5 464H35.8L200.7 275.5 26.8 48H172.4L272.9 180.9 389.2 48zM364.4 421.8h39.1L151.1 88h-42L364.4 421.8z"></path></svg>
            </a>
          @endif
          @if($store->instagram_url)
            <a href="{{ $store->instagram_url }}" target="_blank" class="bp_insta" title="Instagram" aria-label="Instagram"></a>
          @endif
          @if($store->youtube_url)
            <a href="{{ $store->youtube_url }}" target="_blank" class="bp_yt" title="Youtube" aria-label="Youtube"></a>
          @endif
        </div>
      </div>
      <!-- Social end -->

      <!-- Hints & Tips -->
      <div class="wgt" id="tpHntsss">
        <div class="tpHnts">
          <h3>Hints & Tips</h3>
          <div>If you are looking for additional ways to save a significant amount of money on your shopping trip at {{ $store->store_name }}, the following are some of the ways you can do so:</div>
          <ul>
            <li>Always check out our website before making a purchase at {{ $store->store_name }} to see if you can get their products at a lower price than what they now offer.</li>
            <li>You may ensure that you are aware of the most recent changes made to the {{ $store->store_name }} website by subscribing to the newsletter programmed offered by the company.</li>
            <li>Don't forget to look through the sales and clearance part of the website for {{ $store->store_name }}! Deals like those can be found at the location.</li>
            <li>If you follow {{ $store->store_name }} on any of the major social media platforms (Facebook, Instagram, or Twitter), you will never miss an update.</li>
            <li>{{ $store->store_name }} is known to update their website with promotional codes for gift cards, free shipping and delivery, and next-day delivery frequently. Be sure to confirm that they are.</li>
          </ul>
        </div>
      </div>
      <!-- Hints & Tips end -->

      <!-- Related Stores -->
      @if($relatedStores->count() > 0)
      <div class="wgt">
        <h3>Related Stores</h3>
        <div class="btns">
          @foreach($relatedStores as $relatedStore)
            <a href="{{ route('store', $relatedStore->seo_url) }}" title="{{ $relatedStore->store_name }}">{{ $relatedStore->store_name }}</a>
          @endforeach
        </div>
      </div>
      @endif

      <!-- Related Categories -->
      @if($storeCategories->count() > 0)
      <div class="wgt">
        <h3>Related Categories</h3>
        <div class="btns">
          @foreach($storeCategories as $category)
            @if(!empty($category->category_slug))
              <a href="{{ route('category', $category->category_slug) }}" title="{{ $category->category_name }}">{{ $category->category_name }}</a>
            @else
              <span title="{{ $category->category_name }}">{{ $category->category_name }}</span>
            @endif
          @endforeach
        </div>
      </div>
      @endif

      <!-- Store shoppers also like -->
      @if($relatedStores->count() > 0)
      <div class="wgt">
        <h3>{{ $store->store_name }} shoppers also like</h3>
        <div class="lgos">
          @foreach($relatedStores->take(8) as $relatedStore)
            <a href="{{ route('store', $relatedStore->seo_url) }}" title="{{ $relatedStore->store_name }}">
              @if($relatedStore->store_logo)
                <img src="{{ asset( $relatedStore->store_logo) }}" alt="{{ $relatedStore->store_name }} discount code" title="{{ $relatedStore->store_name }} discount code" decoding="async" loading="lazy" width="64" height="64">
              @else
                <div class="store-placeholder-small">{{ substr($relatedStore->store_name, 0, 2) }}</div>
              @endif
              <div>
                {{ $relatedStore->store_name }}
                <span>{{ rand(5, 15) }} Discount Available</span>
              </div>
            </a>
          @endforeach
        </div>
      </div>
      @endif
    </div>
    <!-- sidebar <end> -->
  </div>
</div>
<!-- sidebar wrp <end> -->

<!-- Enhanced Coupon Modal -->
<div id="couponModal" aria-hidden="true" style="display:none;">
    <div class="cm-overlay"></div>

    <!-- Main Voucher Code Popup -->
    <div class="cm-main-popup" role="dialog" aria-modal="true" aria-label="Coupon Code Popup">
        <button class="cm-close" aria-label="Close popup">&times;</button>

        <!-- Main Popup Content -->
        <div class="cm-main-content">
            <h3 class="cm-title" id="cmTitle">Here is your code</h3>

            <div class="cm-code-section">
                <div class="cm-code-display" id="cmCode">CODE123</div>
                <button class="cm-copy-btn" id="cmCopy">Copy Code</button>
                <button class="cm-redirect-btn" id="cmRedirect">Visit Store</button>
            </div>

            <div class="cm-note" id="cmNote">
                <p>Copy the code above and use it at checkout to get your discount!</p>
            </div>
        </div>
    </div>

    <!-- Email Subscription Popup -->
    <div class="cm-email-popup" role="dialog" aria-modal="true" aria-label="Email Subscription Popup">
        <div class="cm-email-content">
            <div class="cm-brand-logo">
                <div class="cm-brand-circle" id="cmBrandLogo">
                    <span id="cmBrandText">STORE</span>
                </div>
            </div>

            <h3 class="cm-email-title" id="cmEmailTitle">Get More Deals!</h3>
            <p class="cm-email-subtitle text-center">Subscribe to get exclusive offers and discounts</p>

            <form class="cm-email-form" id="cmEmailForm">
                <input type="email" placeholder="Enter your email" required>
                <button type="submit">Subscribe</button>
            </form>

            <p class="cm-email-privacy">We respect your privacy. Unsubscribe at any time.</p>
        </div>
    </div>
</div>

<style>
/* Coupon Modal Styles */
#couponModal {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 9999;
    padding: 20px;
    box-sizing: border-box;
}

.cm-overlay {
    position: absolute;
    inset: 0;
    background: rgba(0,0,0,0.6);
}

/* Main Voucher Code Popup */
.cm-main-popup {
  position: relative;
  top: 20px;
  margin: auto;
    width: 480px;
    max-width: calc(50% - 30px);
    background: var(--background-primary-color, #fff);
    border-radius: 16px;
    padding: 30px;
    box-shadow: 0 20px 40px rgba(0,0,0,0.3);
    overflow: hidden;
    z-index: 2;
}

/* Email Subscription Popup */
.cm-email-popup {
  position: relative;
  margin: auto;
  top: 40px;
    width: 480px;
    max-width: calc(50% - 30px);
    background: var(--background-primary-color, #fff);
    border-radius: 16px;
    padding: 30px;
    box-shadow: 0 20px 40px rgba(0,0,0,0.3);
    overflow: hidden;
    z-index: 2;
}

.cm-close {
    position: absolute;
    top: 15px;
    right: 20px;
    background: none;
    border: none;
    font-size: 24px;
    cursor: pointer;
    color: var(--text-color, #666);
    z-index: 3;
}

.cm-close:hover {
    color: var(--text-color, #000);
}

.cm-main-content {
    text-align: center;
}

.cm-title {
    font-size: 24px;
    font-weight: bold;
    margin-bottom: 20px;
    color: var(--text-color, #333);
}

.cm-code-section {
    margin: 20px 0;
}

.cm-code-display {
    background: var(--background-secondary-color, #f8f9fa);
    border: 2px dashed var(--primary-color, #2951c4);
    border-radius: 8px;
    padding: 15px;
    font-size: 18px;
    font-weight: bold;
    color: var(--primary-color, #2951c4);
    margin-bottom: 15px;
    font-family: monospace;
}

.cm-copy-btn {
    background: var(--primary-color, #2951c4);
    color: white;
    border: none;
    padding: 12px 24px;
    border-radius: 6px;
    font-size: 16px;
    cursor: pointer;
    transition: background 0.3s;
    width: 100%;
}

.cm-copy-btn:hover {
    background: var(--primary-color, #2951c4);
}

.cm-redirect-btn {
    background: var(--secondary-color, #333);
    color: white;
    border: none;
    padding: 12px 24px;
    border-radius: 6px;
    font-size: 16px;
    cursor: pointer;
    transition: background 0.3s;
    margin-top: 10px;
    width: 100%;
}

.cm-redirect-btn:hover {
    background: var(--text-color, #555);
}

.cm-note {
    margin-top: 20px;
    color: var(--text-color, #666);
    font-size: 14px;
}

/* Feedback Section */
.cm-feedback {
    margin: 25px 0;
    padding: 20px 0;
    border-top: 1px solid var(--background-secondary-color, #f0f0f0);
}

.cm-feedback p {
    margin: 0 0 15px;
    color: var(--text-color, #6b7280);
    font-size: 15px;
    font-weight: 500;
}

.cm-feedback-buttons {
    display: flex;
    gap: 20px;
    justify-content: center;
}

.cm-feedback-btn {
    background: transparent;
    border: 2px solid var(--background-secondary-color, #d1d5db);
    border-radius: 50%;
    width: 45px;
    height: 45px;
    cursor: pointer;
    font-size: 20px;
    transition: all 0.2s;
    display: flex;
    align-items: center;
    justify-content: center;
}

.cm-feedback-btn:hover {
    border-color: var(--primary-color, #10b981);
    background: var(--background-secondary-color, #f0fdf4);
    transform: scale(1.1);
}

/* Student Discount Highlighting */
.cpn.highlighted {
    border: 3px solid #fbbf24 !important;
    box-shadow: 0 0 20px rgba(251, 191, 36, 0.5) !important;
    transform: scale(1.02);
    animation: highlightPulse 2s ease-in-out infinite;
    position: relative;
}

.cpn.highlighted::before {
    content: "STUDENT DISCOUNT";
    position: absolute;
    top: -4px;
    right: -2px;
    background: #fbbf24;
    color: #374151;
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 10px;
    font-weight: 700;
    z-index: 10;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

@keyframes highlightPulse {
    0%, 100% {
        box-shadow: 0 0 20px rgba(251, 191, 36, 0.5);
    }
    50% {
        box-shadow: 0 0 30px rgba(251, 191, 36, 0.8);
    }
}

/* More Details */
.cm-more-details {
    margin: 20px 0;
}

.cm-more-btn {
    background: transparent;
    border: none;
    color: var(--text-color, #6b7280);
    font-size: 14px;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 5px;
    margin: 0 auto;
    padding: 8px 12px;
    border-radius: 6px;
    transition: background-color 0.2s;
}

.cm-more-btn:hover {
    background: var(--background-secondary-color, #f3f4f6);
}

.cm-chevron {
    font-size: 12px;
    transition: transform 0.2s;
}

/* Email Popup Content */
/* .cm-email-content {
    padding: 25px 20px 20px;
    background: #f8f9fa;
} */

.cm-brand-logo {
    margin-bottom: 15px;
}

.cm-brand-circle {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    background: var(--primary-color, #2951c4);
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto;
    color: white;
    font-weight: bold;
    font-size: 14px;
}

.cm-email-title {
  text-align: center;
  font-size: 22px;
    font-weight: bold;
    margin-bottom: 10px;
    color: var(--text-color, #333);
}

.cm-email-form {
    margin: 20px 0;
}

.cm-email-form input {
    width: 100%;
    padding: 12px;
    border: 1px solid #ddd;
    border-radius: 6px;
    margin-bottom: 10px;
    font-size: 14px;
}

.cm-email-form button {
    width: 100%;
    background: var(--primary-color, #2951c4);
    color: white;
    border: none;
    padding: 12px;
    border-radius: 6px;
    font-size: 16px;
    cursor: pointer;
    transition: background 0.3s;
}

.cm-email-form button:hover {
    background: var(--secondary-color, #2951c4);
}

.cm-email-privacy {
    font-size: 12px;
    color: var(--text-color, #999);
    margin-top: 15px;
}

.cm-email-consent {
    font-size: 11px;
    color: var(--text-color, #6b7280);
    line-height: 1.4;
    margin: 0;
}

.cm-email-consent a {
    color: var(--primary-color, #ef4444);
    text-decoration: underline;
    font-weight: 500;
}

/* Website Logo */
.cm-website-logo {
    padding: 15px;
    border-top: 1px solid var(--background-secondary-color, #e5e7eb);
    background: var(--background-primary-color, #fff);
    text-align: center;
}

.cm-website-name {
    font-size: 14px;
    font-weight: 600;
    color: var(--text-color, #111827);
    letter-spacing: 0.5px;
}

/* Responsive */
@media (max-width: 768px) {
    .cm-main-popup {
        top: 20px;
        left: 10px;
        right: 10px;
        width: auto;
        max-width: none;
    }

    .cm-email-popup {
        top: 400px;
        left: 10px;
        right: 10px;
        width: auto;
        max-width: none;
    }

    .cm-main-content {
        padding: 20px;
    }

    .cm-title {
        font-size: 20px;
    }

    .cm-code-display {
        font-size: 16px;
        padding: 12px;
    }
}

/* Override store.css - Keep buttons as buttons on all screen sizes, remove arrows */
.cpns.wd .cpn .cpBtn {
    width: 170px !important;
    padding: 12px 12px !important;
    font-size: 14px !important;
    height: auto !important;
    color: #fff !important;
    background-color: var(--primary-color) !important;
    justify-content: center !important;
    margin: 0 !important;
    display: inline-flex !important;
    flex-wrap: wrap !important;
    align-items: center !important;
    border-radius: 8px !important;
}

.cpns.wd .cpn .cpBtn.reveal-code {
    background-color: #f2f0e6 !important;
    color: #fff !important;
    padding-right: 30px !important;
    position: relative !important;
    overflow: hidden !important;
}

.cpns.wd .cpn .cpBtn.reveal-code::before {
    content: attr(data-code) !important;
    font-family: inherit !important;
    display: inline-flex !important;
    position: absolute !important;
    width: 50px !important;
    height: 100% !important;
    top: 0 !important;
    right: 0 !important;
    align-items: center !important;
    padding: 0 15px 0 0 !important;
    overflow: hidden !important;
    border: 2px dashed var(--primary-color) !important;
    border-left: 0 !important;
    text-transform: uppercase !important;
    justify-content: end !important;
    box-sizing: border-box !important;
    border-radius: 0 9px 9px 0 !important;
    color: #0f0f0f !important;
    z-index: -1 !important;
    font-size: 14px !important;
}

.cpns.wd .cpn .cpBtn.reveal-code::after {
    content: "" !important;
    position: absolute !important;
    width: 100% !important;
    height: calc(100% + 2px) !important;
    background-color: var(--primary-color) !important;
    top: 0 !important;
    right: 34px !important;
    transform: skewX(25deg) !important;
    transition: .2s ease-in-out !important;
    z-index: -1 !important;
    display: block !important;
}

.cpns.wd .cpn .cpBtn.get-deal::before {
    display: none !important;
    content: none !important;
}

.cpns.wd .cpn .cpBtn.get-deal::after {
    display: none !important;
    content: none !important;
}

.cpns.wd .cpn .cpBtn.get-code::before {
    display: none !important;
    content: none !important;
}

.cpns.wd .cpn .cpBtn.get-code::after {
    display: none !important;
    content: none !important;
}

@media (max-width: 767px) {
    .cpns.wd .cpn .cpBtn {
        width: 150px !important;
    }
}

@media (max-width: 550px) {
    .cpns.wd .cpn .cpBtn {
        max-width: 200px !important;
        padding: 12px 12px !important;
        font-size: 14px !important;
        height: auto !important;
        color: #fff !important;
        background-color: var(--primary-color) !important;
        justify-content: center !important;
        margin: 0 !important;
    }

    .cpns.wd .cpn .cpBtn.reveal-code {
        background-color: #f2f0e6 !important;
        color: #fff !important;
        padding-right: 30px !important;
    }

    .cpns.wd .cpn .cpBtn.reveal-code::before {
        content: attr(data-code) !important;
        display: inline-flex !important;
    }

    .cpns.wd .cpn .cpBtn.get-deal::before {
        display: none !important;
        content: none !important;
    }

    .cpns.wd .cpn .cpBtn.get-code::before {
        display: none !important;
        content: none !important;
    }
}
</style>
<script>
document.addEventListener('DOMContentLoaded', function () {
  // Prevent double-init
  if (window.__couponModalInit) return;
  window.__couponModalInit = true;

  const modal = document.getElementById('couponModal');
  if (!modal) return;

  const overlay = modal.querySelector('.cm-overlay');
  const closeBtn = modal.querySelector('.cm-close');
  const cmCode = document.getElementById('cmCode');
  const cmCopy = document.getElementById('cmCopy');
  const cmTitle = document.getElementById('cmTitle');
  const cmNote = document.getElementById('cmNote');
  const cmEmailTitle = document.getElementById('cmEmailTitle');
  const cmEmailForm = document.getElementById('cmEmailForm');
  const cmEmailInput = document.getElementById('cmEmailInput');

  function openModal(code, affiliate, store, title) {
    if (cmCode) cmCode.textContent = code;
    if (cmTitle) cmTitle.textContent = title || 'Here is your code';
    if (cmEmailTitle) cmEmailTitle.textContent = `Get More ${store} Deals!`;

    // Store affiliate URL for redirect button
    window.currentAffiliateUrl = affiliate;

    if (cmBrandLogo && cmBrandText) {
      if (store && store !== 'Store') {
        cmBrandText.textContent = store.substring(0,5).toUpperCase();
      } else {
        cmBrandText.textContent = 'STORE';
      }
    }

    // Hide Copy Code button if "No code required" is displayed
    if (cmCopy && cmCode) {
      if (code === 'No code required' || code === '' || !code) {
        cmCopy.style.display = 'none';
      } else {
        cmCopy.style.display = 'block';
      }
    }

    modal.style.display = 'block';
    modal.setAttribute('aria-hidden','false');
    document.body.style.overflow = 'hidden';
  }

  function closeModal() {
    modal.style.display = 'none';
    modal.setAttribute('aria-hidden','true');
    document.body.style.overflow = '';
  }

  // Function to check if coupon was already used in this session (client-side check)
  function isCouponUsedInSession(couponId) {
    const sessionKey = 'coupon_used_' + couponId;
    return sessionStorage.getItem(sessionKey) === 'true';
  }

  // Function to mark coupon as used in session (client-side)
  function markCouponAsUsedInSession(couponId) {
    const sessionKey = 'coupon_used_' + couponId;
    sessionStorage.setItem(sessionKey, 'true');
  }

  // Function to track coupon usage in database
  function trackCouponUsage(couponId) {
    // Client-side check first - if already used, don't make API call
    if (isCouponUsedInSession(couponId)) {
      return; // Already used in this session, don't track again
    }

    fetch('{{ route("coupon.track-usage") }}', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      },
      body: JSON.stringify({
        coupon_id: couponId
      })
    })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        // Mark as used in session storage
        markCouponAsUsedInSession(couponId);

        // Only update counts if this is the first time user is using this coupon
        if (!data.already_used) {
          // Update the counts in the UI
          document.querySelectorAll('.cusd[data-coupon-id="' + couponId + '"]').forEach(el => {
            const totalUsedEl = el.querySelector('.total-used');
            const todayUsedEl = el.querySelector('.today-used');

            if (totalUsedEl && data.used_count !== undefined) {
              totalUsedEl.textContent = parseInt(data.used_count).toLocaleString();
            }
            if (todayUsedEl && data.today_count !== undefined) {
              todayUsedEl.textContent = data.today_count;
            }
          });
        }
        // If already_used is true, don't update the counts (they remain the same)
      }
    })
    .catch(error => {
      console.error('Error tracking coupon usage:', error);
    });
  }

  // Get Code buttons (Student Discount)
  document.querySelectorAll('.cpBtn.get-code').forEach(btn => {
    btn.addEventListener('click', function(e) {
      e.preventDefault();

      // Get coupon ID from parent coupon element
      const couponElement = this.closest('.cpn');
      const couponId = couponElement ? couponElement.dataset.id : null;

      const code = this.dataset.code;
      const affiliate = this.dataset.affiliate;
      const store = this.dataset.store;
      const title = this.dataset.title;

      // Track usage in database
      if (couponId) {
        trackCouponUsage(couponId);
      }

      // Remove persistent highlighting when user clicks Get Code
      if (sessionStorage.getItem('highlightUntilClick') === 'true') {
        sessionStorage.removeItem('highlightUntilClick');
        sessionStorage.removeItem('highlightCoupon');

        // Remove highlight from all coupons
        document.querySelectorAll('.cpn.highlighted').forEach(coupon => {
          coupon.classList.remove('highlighted');
        });

        // Clean URL
        const newUrl = window.location.pathname;
        window.history.replaceState({}, document.title, newUrl);
      }

      if (affiliate) {
        const currentUrl = window.location.href.split('#')[0].split('?')[0];
        const popupUrl = currentUrl + '?show_coupon=1&code=' + encodeURIComponent(code || '') + '&affiliate=' + encodeURIComponent(affiliate) + '&store=' + encodeURIComponent(store) + '&title=' + encodeURIComponent(title);
        window.open(popupUrl, '_blank');
        window.location.href = affiliate;
      } else {
        // Fallback to modal if no affiliate link
        openModal(code || 'No code required', affiliate, store, title);
      }
    });
  });

  // Reveal code buttons
  document.querySelectorAll('.cpBtn.reveal-code').forEach(btn => {
    btn.addEventListener('click', function(e) {
      e.preventDefault();

      // Get coupon ID from parent coupon element
      const couponElement = this.closest('.cpn');
      const couponId = couponElement ? couponElement.dataset.id : null;

      const code = this.dataset.code;
      const affiliate = this.dataset.affiliate;
      const store = this.dataset.store;
      const title = this.dataset.title;

      // Track usage in database
      if (couponId) {
        trackCouponUsage(couponId);
      }

      if (code && affiliate) {
        const currentUrl = window.location.href.split('#')[0].split('?')[0];
        const popupUrl = currentUrl + '?show_coupon=1&code=' + encodeURIComponent(code) + '&affiliate=' + encodeURIComponent(affiliate) + '&store=' + encodeURIComponent(store) + '&title=' + encodeURIComponent(title);
        window.open(popupUrl, '_blank');
        window.location.href = affiliate;
      }
    });
  });

  // Get Deal buttons - same logic as Reveal Code but without code
  document.querySelectorAll('.cpBtn.get-deal').forEach(btn => {
    btn.addEventListener('click', function(e) {
      e.preventDefault();

      // Get coupon ID from parent coupon element
      const couponElement = this.closest('.cpn');
      const couponId = couponElement ? couponElement.dataset.id : null;

      const affiliate = this.getAttribute('href') || this.dataset.affiliate || '#';
      const store = this.dataset.store || this.dataset.title || '';
      const title = this.dataset.title || '';

      // Track usage in database
      if (couponId) {
        trackCouponUsage(couponId);
      }

      if (affiliate && affiliate !== '#') {
        const currentUrl = window.location.href.split('#')[0].split('?')[0];
        const popupUrl = currentUrl + '?show_coupon=1&code=&affiliate=' + encodeURIComponent(affiliate) + '&store=' + encodeURIComponent(store) + '&title=' + encodeURIComponent(title);
        window.open(popupUrl, '_blank');
        window.location.href = affiliate;
      }
    });
  });

  // Copy button
  if (cmCopy) {
    cmCopy.addEventListener('click', function() {
      const code = cmCode ? cmCode.textContent : '';
      if (code && code !== 'No code required') {
        navigator.clipboard.writeText(code).then(function() {
          const originalText = cmCopy.textContent;
          cmCopy.textContent = 'Copied!';
          cmCopy.style.backgroundColor = '#218838';

          setTimeout(function() {
            cmCopy.textContent = originalText;
            cmCopy.style.backgroundColor = '#2951c4';
          }, 2000);
        }).catch(function(err) {
          console.error('Could not copy text: ', err);
          alert('Coupon Code: ' + code);
        });
      } else if (code === 'No code required') {
        // For deals without codes, just show message
        const originalText = cmCopy.textContent;
        cmCopy.textContent = 'No Code Needed!';
        cmCopy.style.backgroundColor = '#218838';

        setTimeout(function() {
          cmCopy.textContent = originalText;
          cmCopy.style.backgroundColor = '#2951c4';
        }, 2000);
      }
    });
  }

  // Visit Store button
  const cmRedirect = document.getElementById('cmRedirect');
  if (cmRedirect) {
    cmRedirect.addEventListener('click', function() {
      const currentAffiliate = window.currentAffiliateUrl || '#';
      if (currentAffiliate && currentAffiliate !== '#') {
        window.open(currentAffiliate, '_blank');
      }
    });
  }

  // Email form
  const emailForm = document.getElementById('cmEmailForm');
  if (emailForm) {
    emailForm.addEventListener('submit', function(e) {
      e.preventDefault();
      const email = this.querySelector('input[type="email"]').value;
      if (email) {
        // Here you can add AJAX call to subscribe
        alert('Thank you for subscribing!');
        closeModal();
      }
    });
  }

  // Newsletter subscription form
  const newsletterForm = document.getElementById('newsletterForm');
  if (newsletterForm) {
    newsletterForm.addEventListener('submit', function(e) {
      e.preventDefault();

      const email = document.getElementById('newsletterEmail').value.trim();
      const submitBtn = document.getElementById('newsletterBtn');
      const messageDiv = document.getElementById('newsletterMessage');

      if (!email) {
        showMessage('Please enter your email address.', 'error');
        return;
      }

      // Very simple email validation - just check for @ and .
      if (!email.includes('@') || !email.includes('.')) {
        showMessage('Please enter a valid email address.', 'error');
        return;
      }

      // Check that @ comes before the last .
      const atIndex = email.indexOf('@');
      const lastDotIndex = email.lastIndexOf('.');
      if (atIndex === -1 || lastDotIndex === -1 || atIndex >= lastDotIndex) {
        showMessage('Please enter a valid email address.', 'error');
        return;
      }

      // Disable submit button
      submitBtn.disabled = true;
      submitBtn.textContent = 'Subscribing...';

      // Make AJAX request
      fetch('{{ route("newsletter.subscribe") }}', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
          email: email
        })
      })
      .then(response => {
        if (!response.ok) {
          throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
      })
      .then(data => {
        if (data.success) {
          showMessage(data.message, 'success');
          document.getElementById('newsletterEmail').value = '';
        } else {
          showMessage(data.message || 'Something went wrong. Please try again.', 'error');
        }
      })
      .catch(error => {
        console.error('Error:', error);
        if (error.name === 'TypeError' && error.message.includes('fetch')) {
          showMessage('Network error. Please check your internet connection.', 'error');
        } else if (error.message.includes('HTTP error! status: 422')) {
          showMessage('This email is already subscribed to our newsletter.', 'error');
        } else if (error.message.includes('HTTP error! status: 500')) {
          showMessage('Server error. Please try again later.', 'error');
        } else {
          showMessage('Something went wrong. Please try again.', 'error');
        }
      })
      .finally(() => {
        // Re-enable submit button
        submitBtn.disabled = false;
        submitBtn.textContent = 'Subscribe';
      });
    });
  }

  function showMessage(message, type) {
    const messageDiv = document.getElementById('newsletterMessage');
    if (messageDiv) {
      // Clear any existing timeout
      if (messageDiv.timeoutId) {
        clearTimeout(messageDiv.timeoutId);
      }

      // Clear previous message immediately
      messageDiv.textContent = '';
      messageDiv.style.display = 'none';

      // Small delay to ensure previous message is cleared
      setTimeout(() => {
        messageDiv.textContent = message;
        messageDiv.style.display = 'block';
        messageDiv.style.color = type === 'success' ? '#10b981' : '#ef4444';
        messageDiv.style.backgroundColor = type === 'success' ? '#f0fdf4' : '#fef2f2';
        messageDiv.style.border = type === 'success' ? '1px solid #10b981' : '1px solid #ef4444';
        messageDiv.style.fontSize = '14px';
        messageDiv.style.marginTop = '10px';
        messageDiv.style.padding = '8px';
        messageDiv.style.borderRadius = '4px';
        messageDiv.style.fontWeight = '500';
        messageDiv.style.textAlign = 'center';

        // Hide message after 5 seconds
        messageDiv.timeoutId = setTimeout(() => {
          messageDiv.style.display = 'none';
        }, 5000);
      }, 100);
    }
  }

  // Student discount highlighting
  function highlightStudentCoupon() {
    let highlightId = null;

    // Check URL parameter first
    if (window.location.search.includes('highlight=')) {
      const urlParams = new URLSearchParams(window.location.search);
      highlightId = urlParams.get('highlight');
    }
    // Check sessionStorage for persistent highlighting
    else if (sessionStorage.getItem('highlightUntilClick') === 'true') {
      highlightId = sessionStorage.getItem('highlightCoupon');
    }

    if (highlightId) {
      setTimeout(() => {
        const highlightedCoupon = document.querySelector(`.cpn[data-id="${highlightId}"]`);
        if (highlightedCoupon) {
          highlightedCoupon.classList.add('highlighted');
          highlightedCoupon.scrollIntoView({
            behavior: 'smooth',
            block: 'center'
          });

          // Only auto-remove highlight if not persistent
          if (sessionStorage.getItem('highlightUntilClick') !== 'true') {
            setTimeout(() => {
              highlightedCoupon.classList.remove('highlighted');
              // Clean URL
              const newUrl = window.location.pathname;
              window.history.replaceState({}, document.title, newUrl);
            }, 5000);
          }
        }
      }, 500);
    }
  }

  // Initialize highlighting
  highlightStudentCoupon();

  // feedback & more toggle (guards added)
  document.querySelectorAll('.cm-feedback-btn').forEach(btn => {
    btn.addEventListener('click', function () {
      document.querySelectorAll('.cm-feedback-btn').forEach(b => { b.style.background = 'transparent'; b.style.borderColor = 'var(--background-secondary-color, #d1d5db)'; });
      const feedback = this.dataset.feedback;
      this.style.background = feedback === 'positive' ? 'var(--background-secondary-color, #f0fdf4)' : 'var(--background-secondary-color, #fef2f2)';
      this.style.borderColor = feedback === 'positive' ? 'var(--primary-color, #10b981)' : 'var(--primary-color, #ef4444)';
    });
  });

  const moreBtn = document.querySelector('.cm-more-btn');
  if (moreBtn) {
    moreBtn.addEventListener('click', function () {
      const chevron = this.querySelector('.cm-chevron');
      if (chevron) chevron.style.transform = chevron.style.transform === 'rotate(180deg)' ? 'rotate(0deg)' : 'rotate(180deg)';
    });
  }

  if (closeBtn) closeBtn.addEventListener('click', closeModal);
  if (overlay) overlay.addEventListener('click', closeModal);

  // show modal if params present
  try {
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('show_coupon') === '1') {
      const code = urlParams.get('code') || '';
      const affiliate = urlParams.get('affiliate') || '#';
      const store = urlParams.get('store') || 'Store';
      const title = urlParams.get('title') || 'Here is your code';

      openModal(code, affiliate, store, title);

      // If no code, show "No code required" message
      if (!code || code === 'No code required') {
        if (cmCode) cmCode.textContent = 'No code required';
        if (cmCopy) {
          cmCopy.style.display = 'none';
        }
      } else {
        if (cmCopy) {
          cmCopy.style.display = 'block';
        }
      }

      history.replaceState({}, '', window.location.pathname);
    }
  } catch (e) {
    console.log('URL params not supported');
  }
});
</script>

@endsection

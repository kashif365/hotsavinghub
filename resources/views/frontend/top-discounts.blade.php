@extends('frontend.layouts.app')

@section('title', 'Top 20 Discounts & Best Voucher Codes | Hotsavinghub')
@section('description', 'Get the best discount codes and voucher codes from top UK brands. Save money on your favorite products with our verified offers. Updated daily with fresh deals.')
@section('keywords', 'top discounts, best voucher codes, top discount codes, popular deals, trending offers, best coupons UK')
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
        <a href="javascript:;" class="link active" itemprop="item">
          <span itemprop="name">Top 20 Discount</span>
          <meta itemprop="position" content="2">
        </a>
      </li>
    </ul>
    <!-- Breadcrumb <end> -->
          <!-- Top 20 Discounts Head <start> -->
      <div class="dcHd">
        <h1>Top 20 Discounts</h1>
      </div>
      <!-- Top 20 Discounts Head <end> -->

  </div>
</div>
<!-- sidebar wrp -->
<div class="Sec bg">
  <div class="splt Wrp">
    <!-- coupon side -->
    <div class="wgtc">
     <div class="cpns wd" id="coupons">
         @if($topCoupons->count() > 0)
             @foreach($topCoupons as $coupon)
            <!-- coupon:code <start> -->
            <div class="cpn {{ $coupon->coupon_code ? 'cd' : 'dl' }} {{ $coupon->free_shipping ? 'fs' : '' }} {{ $coupon->student_offer ? 'std' : '' }} {{ $coupon->exclusive ? 'exclusive' : '' }}" data-id="{{ $coupon->id }}">
                <button data-id="{{ $coupon->id }}" title="Add to Favourite" class="cfb bp_save hideIconS" aria-label="Add to Favourite"></button>

                <a class="clgo" href="{{ route('store', $coupon->store->seo_url ?? 'store') }}" title="{{ $coupon->store->store_name ?? 'Store' }} Vouchers Code">
                    @if($coupon->store && $coupon->store->store_logo)
                        <img src="{{ asset($coupon->store->store_logo) }}" alt="{{ $coupon->store->store_name }} discount code" title="{{ $coupon->store->store_name }} discount code" decoding="async" loading="lazy" width="90" height="90">
                    @else
                        <div class="store-logo-placeholder">{{ substr($coupon->store->store_name ?? 'Store', 0, 2) }}</div>
                    @endif
                </a>

                <div class="ccnt">
                    <div class="ctp">
                        @if($coupon->verified)
                            <span class="cvrf {{ $coupon->exclusive ? 'exclusive' : '' }}">Verified</span>
                        @endif
                    </div>
                    <h3 role="button" aria-label="{{ $coupon->coupon_code ? 'Reveal Code' : 'Get Deal' }}" title="{{ $coupon->coupon_title }}">
                        @if($coupon->exclusive)
                            <strong class="cexclv">Exclusive</strong>
                        @endif
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

                @if($coupon->coupon_code)
                    <button class="cpBtn reveal-code" title="Reveal Code" aria-label="Reveal Code"
                            data-code="{{ $coupon->coupon_code }}"
                            data-affiliate="{{ $coupon->affiliate_url ?? ($coupon->store ? $coupon->store->affiliate_url : url('/')) }}"
                            data-store="{{ $coupon->store ? $coupon->store->store_name : 'Store' }}"
                            data-title="{{ $coupon->coupon_title }}">
                        Reveal Code
                    </button>
                @else
                    <button class="cpBtn get-deal" title="Get Deal" aria-label="Get Deal"
                            data-affiliate="{{ $coupon->affiliate_url ?? ($coupon->store ? $coupon->store->affiliate_url : url('/')) }}"
                            data-store="{{ $coupon->store ? $coupon->store->store_name : 'Store' }}"
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

        @else
            <div class="no-coupons">
                <div class="no-coupons-icon">😔</div>
                <h3>No Exclusive Discounts Available</h3>
                <p>Check back soon for amazing exclusive offers!</p>
            </div>
        @endif
      </div>
    </div>
    <!-- coupon side <end> -->





    <!-- coupon side <end> -->
    <!-- sidebar -->
    <div class="wgts">

<!-- wgtcnt: Widget Content <start> -->
<div class="wgt">

    <h3 class="brdr">About Top 20 Discounts</h3>
    <p>Why pay more when you can shop smarter? At Hotsavinghub, we bring you the latest and most popular discount codes from trusted brands across every category. From fashion and beauty to electronics, travel, food, health, and lifestyle essentials, our Top 20 Discount Codes page is designed to help you unlock unbeatable savings every day.
Finding genuine coupon codes online can often be frustrating, but we make it simple. Each voucher is carefully selected to ensure you get the best deals, verified offers, and real discounts without wasting time. Whether you're refreshing your wardrobe, upgrading your tech, planning a holiday, or ordering your favorite meals, our exclusive discount codes help you save more while enjoying more.
At Hotsavinghub, we believe that saving money should never mean compromising on quality. That's why we regularly update our Top 20 list with the hottest offers available—so you can always find something valuable no matter what you're shopping for.
Start exploring today and make every purchase rewarding with Hotsavinghub's Top 20 Discount Codes—because the best deals don't last forever!</p>
  </div>

<div class="wgt">
    <h3>Browse By Store</h3>
    <div class="btns alp">
        <a href="{{ route('all-brands') }}?q=A">A</a>
        <a href="{{ route('all-brands') }}?q=B">B</a>
        <a href="{{ route('all-brands') }}?q=C">C</a>
        <a href="{{ route('all-brands') }}?q=D">D</a>
        <a href="{{ route('all-brands') }}?q=E">E</a>
        <a href="{{ route('all-brands') }}?q=F">F</a>
        <a href="{{ route('all-brands') }}?q=G">G</a>
        <a href="{{ route('all-brands') }}?q=H">H</a>
        <a href="{{ route('all-brands') }}?q=I">I</a>
        <a href="{{ route('all-brands') }}?q=J">J</a>
        <a href="{{ route('all-brands') }}?q=K">K</a>
        <a href="{{ route('all-brands') }}?q=L">L</a>
        <a href="{{ route('all-brands') }}?q=M">M</a>
        <a href="{{ route('all-brands') }}?q=N">N</a>
        <a href="{{ route('all-brands') }}?q=O">O</a>
        <a href="{{ route('all-brands') }}?q=P">P</a>
        <a href="{{ route('all-brands') }}?q=Q">Q</a>
        <a href="{{ route('all-brands') }}?q=R">R</a>
        <a href="{{ route('all-brands') }}?q=S">S</a>
        <a href="{{ route('all-brands') }}?q=T">T</a>
        <a href="{{ route('all-brands') }}?q=U">U</a>
        <a href="{{ route('all-brands') }}?q=V">V</a>
        <a href="{{ route('all-brands') }}?q=W">W</a>
        <a href="{{ route('all-brands') }}?q=X">X</a>
        <a href="{{ route('all-brands') }}?q=Y">Y</a>
        <a href="{{ route('all-brands') }}?q=Z">Z</a>
        <a href="{{ route('all-brands') }}?q=0-9" class="active">0-9</a>
    </div>
  </div>



    <div class="wgt nbp">
    <h3>Trending Brands</h3>
    <p>Major Discounts, Vouchers and Codes for the month of September 2025</p>
    <div class="btns">

@if(isset($trendingStores) && $trendingStores->count() > 0)
    @foreach($trendingStores as $store)
        <a href="{{ route('store', $store->seo_url) }}" title="{{ $store->store_name }}">{{ $store->store_name }}</a>
    @endforeach
@else
  <a href="{{ route('store', 'debenhams') }}" title="Debenhams UK">Debenhams UK</a>
  <a href="{{ route('store', 'asos') }}" title="ASOS UK">ASOS UK</a>
  <a href="{{ route('store', 'boden') }}" title="Boden">Boden</a>
  <a href="{{ route('store', 'dominos-pizza') }}" title="Dominos Pizza">Dominos Pizza</a>
  <a href="{{ route('store', 'missguided') }}" title="Missguided UK">Missguided UK</a>
  <a href="{{ route('store', 'dunelm') }}" title="Dunelm">Dunelm</a>
  <a href="{{ route('store', 'asda-george') }}" title="Asda George">Asda George</a>
  <a href="{{ route('store', 'samsung') }}" title="Samsung">Samsung</a>
  <a href="{{ route('store', 'clarks') }}" title="Clarks UK">Clarks UK</a>
  <a href="{{ route('store', 'currys-pc-world') }}" title="Currys PC World">Currys PC World</a>
  <a href="{{ route('store', 'groupon') }}" title="Groupon">Groupon</a>
  <a href="{{ route('store', 'pizza-express') }}" title="Pizza Express">Pizza Express</a>
  <a href="{{ route('store', 'marks-and-spencer') }}" title="Marks and Spencer">Marks and Spencer</a>
  <a href="{{ route('store', 'the-white-company') }}" title="The White Company">The White Company</a>
  <a href="{{ route('store', 'boohoo') }}" title="Boohoo">Boohoo</a>
  <a href="{{ route('store', 'very') }}" title="Very">Very</a>
  <a href="{{ route('store', 'just-eat') }}" title="Just Eat">Just Eat</a>
  <a href="{{ route('store', 'ebay') }}" title="Ebay">Ebay</a>
  <a href="{{ route('store', 'all-beauty') }}" title="All Beauty">All Beauty</a>
  <a href="{{ route('store', 'dorothy-perkins') }}" title="Dorothy Perkins">Dorothy Perkins</a>
  <a href="{{ route('store', 'bershka') }}" title="Bershka">Bershka</a>
  <a href="{{ route('store', 'vonhaus') }}" title="VonHaus">VonHaus</a>
  <a href="{{ route('store', 'monsoon') }}" title="Monsoon">Monsoon</a>
  <a href="{{ route('store', 'appliances-direct') }}" title="Appliances Direct">Appliances Direct</a>
  <a href="{{ route('store', 'usc') }}" title="USC">USC</a>
  <a href="{{ route('store', 'notino') }}" title="Notino">Notino</a>
  <a href="{{ route('store', 'uber-eats') }}" title="Uber Eats">Uber Eats</a>
  <a href="{{ route('store', 'qwertee') }}" title="Qwertee">Qwertee</a>
  <a href="{{ route('store', 'natures-best') }}" title="Natures Best">Natures Best</a>
  <a href="{{ route('store', 'majestic-wine') }}" title="Majestic Wine">Majestic Wine</a>
  <a href="{{ route('store', 'udemy') }}" title="Udemy">Udemy</a>
  <a href="{{ route('store', 'moda-furnishings') }}" title="Moda Furnishings">Moda Furnishings</a>
  <a href="{{ route('store', 'smiggle') }}" title="smiggle.co.uk">smiggle.co.uk</a>
  <a href="{{ route('store', 'michael-kors') }}" title="Michael Kors">Michael Kors</a>
  <a href="{{ route('store', 'sports-direct') }}" title="Sports Direct">Sports Direct</a>
  <a href="{{ route('store', 'gymshark') }}" title="GymShark">GymShark</a>
  <a href="{{ route('store', 'ambrose-wilson') }}" title="Ambrose Wilson">Ambrose Wilson</a>
  <a href="{{ route('store', 'argos') }}" title="Argos">Argos</a>
  <a href="{{ route('store', 'screwfix') }}" title="Screwfix">Screwfix</a>
  <a href="{{ route('store', 'new-look') }}" title="New Look">New Look</a>
  <a href="{{ route('store', 'studio') }}" title="Studio">Studio</a>
  <a href="{{ route('store', 'h-and-m') }}" title="H&amp;M">H&amp;M</a>
  <a href="{{ route('store', 'john-lewis') }}" title="John Lewis">John Lewis</a>
  <a href="{{ route('store', 'pizza-hut') }}" title="Pizza Hut">Pizza Hut</a>
@endif
      </div>
    <a href="{{ route('all-brands') }}" class="bwsMre">Browse A-Z</a>
  </div>



  <div class="snlsec sdbr">

  <img src="{{ asset('uploads/mail.png') }}" alt="paper plan" width="100" height="100" decoding="async" loading="lazy">
            <h2>Sign-up To Get Latest Voucher Codes First</h2>
            <p>Be the first one to get notified as soon as we update a new offer or discount.</p>

            <form id="newsletterForm" class="snfld" novalidate>
                @csrf
                <input type="text" name="email" id="newsletterEmail" placeholder="Enter Your Email Address Here" required>
                <button type="submit" class="nfb" title="Subscribe" id="newsletterBtn">Subscribe</button>
            </form>
            <div id="newsletterMessage" style="margin-top: 10px; display: none; padding: 8px; border-radius: 4px; font-weight: 500; text-align: center;"></div>

    <p>By signing up I agree to Hotsavinghub's <a href="{{ url('/privacy-policy') }}" target="_blank">Privacy Policy</a> and consent to receive emails about offers.</p>
</div>

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
        <div class="cm-main-content text-center">
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
            <p class="cm-email-subtitle">Subscribe to get exclusive offers and discounts</p>

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
    background: #fff;
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
    background: #fff;
    border-radius: 16px;
    padding: 30px;
    box-shadow: 0 20px 40px rgba(0,0,0,0.3);
    overflow: hidden;
    z-index: 2;
}

#couponModal .cm-close {
    position: absolute;
    top: 15px;
    right: 15px;
    background: transparent;
    border: none;
    font-size: 24px;
    cursor: pointer;
    color: #666;
    z-index: 10;
    width: 30px;
    height: 30px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    transition: background-color 0.2s;
}

#couponModal .cm-close:hover {
    background: rgba(0,0,0,0.1);
}

/* Main Popup Content */
.cm-main-content {
    background: #fff;
    text-align: center;
}

.cm-title {
    font-size: 28px;
    font-weight: 700;
    color: #111827;
    margin: 0 0 25px;
    line-height: 1.2;
}

.cm-code-section {
    margin: 20px 0;
}

.cm-code-display {
    background: #f8f9fa;
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
    color: #6b7280;
    font-size: 15px;
    margin: 20px 0;
    line-height: 1.6;
    max-width: 400px;
    margin-left: auto;
    margin-right: auto;
}

/* Email Popup Content */
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
    color: #333;
}

.cm-email-form {
    display: flex;
    flex-direction: column;
    gap: 10px;
    margin-bottom: 15px;
}

.cm-email-form input {
    padding: 12px 14px;
    border: 1px solid #d1d5db;
    border-radius: 6px;
    font-size: 14px;
    transition: border-color 0.2s;
}

.cm-email-form input:focus {
    outline: none;
    border-color: #10b981;
    box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
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
    background: var(--primary-color, #2951c4);
}

.cm-email-privacy {
    font-size: 11px;
    color: #6b7280;
    line-height: 1.4;
    margin: 0;
}

/* Exclusive Coupon Styling */
.cpn.exclusive {
    border: 2px solid #FFD700;
    background: linear-gradient(135deg, #fff9e6 0%, #ffffff 100%);
    position: relative;
}

.cpn.exclusive::before {
    content: '⭐';
    position: absolute;
    top: -8px;
    right: -8px;
    background: #FFD700;
    color: #000;
    width: 24px;
    height: 24px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 12px;
    z-index: 10;
}

.cvrf.exclusive {
    background: linear-gradient(45deg, #FFD700, #FFA500);
    color: #000;
    font-weight: bold;
    border: 1px solid #FFD700;
}

.store-logo-placeholder {
    width: 90px;
    height: 90px;
    background: var(--primary-color, #2951c4);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    font-size: 24px;
    border-radius: 8px;
}

.no-coupons {
    text-align: center;
    padding: 60px 20px;
    color: #666;
}

.no-coupons-icon {
    font-size: 48px;
    margin-bottom: 20px;
}

.no-coupons h3 {
    font-size: 24px;
    margin-bottom: 10px;
    color: #333;
}

.no-coupons p {
    font-size: 16px;
    color: #666;
}

/* Responsive */
@media (max-width: 768px) {
    .cm-main-popup {
        width: 95%;
        max-width: 450px;
    }

    .cm-email-popup {
        width: 95%;
        max-width: 450px;
    }

    .cm-main-content {
        padding: 25px 20px 20px;
    }

    .cm-email-content {
        padding: 20px 15px 15px;
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
});
</script>

@endsection

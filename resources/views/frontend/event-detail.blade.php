@extends('frontend.layouts.app')

@section('title', ($event->meta_title ?? $event->event_name . ' Discount Codes & Voucher Codes') . ' | Hotsavinghub')
@section('description', $event->meta_description ?? 'Get the latest ' . $event->event_name . ' discount codes, voucher codes, and promo codes. Save money on your purchases with verified offers. Updated regularly with fresh deals.')
@section('keywords', trim($event->meta_keywords ?? '') ? $event->meta_keywords : ($event->event_name . ' discount codes, ' . $event->event_name . ' voucher codes, ' . $event->event_name . ' promo codes, ' . $event->event_name . ' coupons, event deals'))

@push('meta')
    @if($event->canonical_url)
        <link rel="canonical" href="{{ $event->canonical_url }}">
    @else
        <link rel="canonical" href="{{ route('event.detail', $event->seo_url) }}">
    @endif
@endpush

@if($event->schema && trim($event->schema) !== '' && trim($event->schema) !== 'test')
    @php
        $schemaContent = trim($event->schema);
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




<!-- Event Banner Section -->
<div class="event-banner-section">
  @if($event->cover_image)
    <div class="event-banner-image" style="background-image: url('{{ asset($event->cover_image) }}'); background-size: cover; background-position: center; background-repeat: no-repeat; height: 450px; width: 100%;"></div>
  @else
    <div class="event-banner-fallback" style="background: linear-gradient(135deg, var(--primary-color), #cc0000); height: 400px; width: 100%;"></div>
  @endif
</div>
<!-- Event Banner Section <end> -->

<!-- Event Coupons Section -->
<div class="hot-deals-section Sec bg">
    <div class="container" style="max-width: 1290px;">

        <!-- Carousel Container (shows only if 4+ cards) -->
        <div class="deals-carousel-container">
            <!-- Navigation Arrows -->
            <button class="carousel-nav carousel-prev" onclick="moveCarousel(-1)">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M15 18L9 12L15 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </button>

            <button class="carousel-nav carousel-next" onclick="moveCarousel(1)">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M9 18L15 12L9 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </button>

            <!-- Carousel Track -->
            <div class="carousel-track" id="dealsCarouselTrack">
                <div class="horizontal-deals-container">
                    @forelse($eventCoupons->where('coupon_code', null)->take(8) ?? [] as $coupon)
                    <div class="horizontal-deal-card" data-id="{{ $coupon->id }}"
                         onclick="openDealModal('{{ $coupon->affiliate_url ?? ($coupon->store ? $coupon->store->affiliate_url : url('/')) }}', '{{ $coupon->brand_store ?? ($coupon->store ? $coupon->store->store_name : 'Store') }}', '{{ $coupon->coupon_title }}')"
                         style="cursor: pointer;">
                        <!-- Image Section with Overlays -->
                        <div class="deal-image-container">
                    @if($coupon->cover_logo)
                                <img src="{{ asset($coupon->cover_logo) }}" alt="{{ $coupon->coupon_title }}" class="deal-main-image" loading="lazy" width="300" height="160">
                    @else
                        <div class="deal-placeholder-image">
                            <div class="placeholder-icon">🎁</div>
                            <span>{{ $coupon->store->store_name ?? $coupon->brand_store ?? 'Deal' }}</span>
                        </div>
                    @endif

                            <!-- Store Logo Overlay (Bottom Left) -->
                            <div class="store-logo-overlay">
                                @if($coupon->store && $coupon->store->store_logo)
                                    <img src="{{ asset($coupon->store->store_logo) }}" alt="{{ $coupon->store->store_name }}" class="overlay-store-logo">
                                @else
                                    <div class="overlay-store-logo-placeholder">
                                        {{ substr($coupon->store->store_name ?? $coupon->brand_store ?? 'Store', 0, 2) }}
                                    </div>
                                @endif
                    </div>

                            <!-- DEAL Badge (Top Right) -->
                            <div class="deal-badge-overlay">
                                DEAL
                        </div>
                </div>

                        <!-- Text Content Section -->
                        <div class="deal-text-content">
                            <h3 class="brand-name">{{ $coupon->store->store_name ?? $coupon->brand_store ?? 'Store' }}</h3>
                            <h4 class="deal-description">{{ $coupon->coupon_title }}</h4>
                            <a href="{{ route('store', $coupon->store->seo_url) }}" class="view-deals-link">
                                View all {{ $coupon->store->store_name ?? $coupon->brand_store ?? 'Store' }} voucher deals
                            </a>
                            </div>
                        </div>
                    @empty
                    <div class="no-deals">
                        <div class="no-deals-icon">😔</div>
                        <h3>No {{ $event->event_name }} Deals Available</h3>
                        <p>Check back soon for amazing offers!</p>
                    </div>
                    @endforelse
                        </div>
                    </div>

            <!-- Pagination Dots -->
            <div class="carousel-pagination" id="carouselPagination">
                <!-- Dots will be generated by JavaScript -->
                            </div>
                            </div>

        <!-- Fallback Grid (shows only if less than 4 cards) -->
        <div class="deals-fallback-grid" id="dealsFallbackGrid" style="display: none;">
            <div class="horizontal-deals-container">
                @forelse($eventCoupons->where('coupon_code', null)->take(8) ?? [] as $coupon)
                <div class="horizontal-deal-card" data-id="{{ $coupon->id }}"
                     onclick="openDealModal('{{ $coupon->affiliate_url ?? ($coupon->store ? $coupon->store->affiliate_url : url('/')) }}', '{{ $coupon->brand_store ?? ($coupon->store ? $coupon->store->store_name : 'Store') }}', '{{ $coupon->coupon_title }}')"
                     style="cursor: pointer;">
                    <!-- Image Section with Overlays -->
                    <div class="deal-image-container">
                        @if($coupon->cover_logo)
                            <img src="{{ asset($coupon->cover_logo) }}" alt="{{ $coupon->coupon_title }}" class="deal-main-image" loading="lazy" width="300" height="160" decoding="async">
                        @else
                            <div class="deal-placeholder-image">
                                <div class="placeholder-icon">🎁</div>
                                <span>{{ $coupon->store->store_name ?? $coupon->brand_store ?? 'Deal' }}</span>
                        </div>
                        @endif

                        <!-- Store Logo Overlay (Bottom Left) -->
                        <div class="store-logo-overlay">
                            @if($coupon->store && $coupon->store->store_logo)
                                <img src="{{ asset($coupon->store->store_logo) }}" alt="{{ $coupon->store->store_name }}" class="overlay-store-logo" loading="lazy" width="60" height="60" decoding="async">
                        @else
                                <div class="overlay-store-logo-placeholder">
                                    {{ substr($coupon->store->store_name ?? $coupon->brand_store ?? 'Store', 0, 2) }}
                                </div>
                        @endif
                    </div>

                        <!-- DEAL Badge (Top Right) -->
                        <div class="deal-badge-overlay">
                            DEAL
                        </div>
                    </div>

                    <!-- Text Content Section -->
                    <div class="deal-text-content">
                        <h3 class="brand-name">{{ $coupon->store->store_name ?? $coupon->brand_store ?? 'Store' }}</h3>
                        <h4 class="deal-description">{{ $coupon->coupon_title }}</h4>
                        <a href="{{ route('store', $coupon->store->seo_url) }}" class="view-deals-link">
                            View all {{ $coupon->store->store_name ?? $coupon->brand_store ?? 'Store' }} voucher deals
                        </a>
                </div>
            </div>
            @empty
            <div class="no-deals">
                <div class="no-deals-icon">😔</div>
                    <h3>No {{ $event->event_name }} Deals Available</h3>
                <p>Check back soon for amazing offers!</p>
            </div>
            @endforelse
            </div>
        </div>
    </div>
</div>
<!-- Event Coupons Section <end> -->

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

<!-- sidebar wrp -->
<div class="Sec bg">
  <div class="splt Wrp">
    <!-- coupon side -->
    <div class="wgtc">
      <div class="cpns wd">
        @if($eventCoupons->count() > 0)
            @foreach($eventCoupons as $coupon)
            <!-- coupon:code <start> -->
            <div class="cpn {{ $coupon->coupon_code ? 'cd' : 'dl' }} {{ $coupon->free_shipping ? 'fs' : '' }} {{ $coupon->student_offer ? 'std' : '' }}" data-id="{{ $coupon->id }}">
                <button title="Add to Favourite" class="cfb bp_save hideIconS" aria-label="Add to Favourite"></button>

                <a class="clgo" href="javascript:;" title="{{ $event->event_name }} Vouchers Code">
                    @if($coupon->store && $coupon->store->store_logo)
                        <img src="{{ asset( $coupon->store->store_logo) }}" alt="{{ $coupon->store->store_name }} discount code" title="{{ $coupon->store->store_name }} discount code" decoding="async" loading="lazy" width="90" height="90">
                    @else
                        <div class="store-logo-placeholder">{{ substr($coupon->brand_store ?? 'EV', 0, 2) }}</div>
                    @endif
                </a>

                <div class="ccnt">
                    <div class="ctp">
                        @if($coupon->verified)
                            <span class="cvrf">Verified</span>
                        @endif
                        @if($event->event_type)
                            <span class="event-type-badge">{{ $event->event_type }}</span>
                        @endif
                    </div>
                    <h3 role="button" aria-label="{{ $coupon->coupon_code ? 'Reveal Code' : 'Get Deal' }}" title="{{ $coupon->coupon_title }}">
                        {{ $coupon->coupon_title }}
                    </h3>

                    <div class="cbt">
                        @if($coupon->terms)
                            <button class="ctb" title="Terms" aria-label="Details">Details</button>
                        @endif
                        <span class="cusd">{{ number_format($coupon->sort_order ?? rand(500, 5000)) }} Used</span>
                    </div>
                </div>

                @if($coupon->coupon_code)
                    <button class="cpBtn reveal-code" title="Reveal Code" aria-label="Reveal Code"
                            data-code="{{ $coupon->coupon_code }}"
                            data-affiliate="{{ $coupon->affiliate_url ?? ($coupon->store ? $coupon->store->affiliate_url : url('/')) }}"
                            data-store="{{ $coupon->brand_store ?? ($coupon->store ? $coupon->store->store_name : 'Store') }}"
                            data-title="{{ $coupon->coupon_title }}">
                        Reveal Code
                    </button>
                @else
                    <button class="cpBtn get-deal" aria-label="Get Deal"
                            data-affiliate="{{ $coupon->affiliate_url ?? ($coupon->store ? $coupon->store->affiliate_url : url('/')) }}"
                            data-store="{{ $coupon->brand_store ?? ($coupon->store ? $coupon->store->store_name : 'Store') }}"
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
                <h2>Stay Updated – Never Miss a {{ $event->event_name }} Voucher Code Again!</h2>
                <p>Be the first one to get notified as soon as we update a new offer or discount.</p>

                <label class="snfld">
                    <input type="text" name="newsletter" value="" placeholder="Enter Your Email Address Here">
                    <button class="nfb" title="Subscribe">Subscribe</button>
                </label>

                <p>By signing up I agree to Hotsavinghub's <a href="{{ route('privacy-policy') }}" target="_blank">Privacy Policy</a> and consent to receive emails about offers.</p>
            </div>
        @else
            <div class="no-coupons">
                <p>No active coupons available for {{ $event->event_name }} at the moment. Check back soon for new offers!</p>
            </div>
        @endif
      </div>

      <!-- event table <start> -->
      <div class="crd tbl">
        <h3 class="hd">Save Big with {{ $event->event_name }} Discount Codes – {{ date('d F Y') }}!</h3>
        <table>
          <tr>
            <th>Offers</th>
            <th>Last Checked</th>
            <th>Code</th>
          </tr>
          @if($eventCoupons->count() > 0)
            @foreach($eventCoupons->take(4) as $coupon)
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
      <!-- event table <end> -->

      <!-- event faq <start> -->
      @if(!empty($event->detail_description))
        <div class="crd faqs" id="srtFaq">
          <h3 class="hd">About {{ $event->event_name }}</h3>
              <div class="faq">
              {!! nl2br(e($event->detail_description)) !!}
          </div>
        </div>
      @endif
      <!-- event faq <end> -->

      <!-- event more content <start> -->
      @if(!empty($event->event_short_content))
      <div class="crd" id="abtStr">
        <h3 class="hd">More About {{ $event->event_name }}</h3>
        <div class="cnt 3">
          {!! nl2br(e($event->event_short_content)) !!}
        </div>
      </div>
      @endif
      <!-- event more content <end> -->
    </div>

    <!-- sidebar -->
    <div class="wgts">

      <!-- rating -->
      <div class="wgt rating-box">
        <h3>How Did We Do? Rate {{ $event->event_name }} Vouchers Today!</h3>
        <div class="rating mb-3">
          <input type="radio" id="star1" name="rating" value="1">
          <label class="bp_str rated" for="star1" onclick="eventRating(1, {{ $event->id }}, '{{ request()->ip() }}')"></label>
          <input type="radio" id="star2" name="rating" value="2">
          <label class="bp_str rated" for="star2" onclick="eventRating(2, {{ $event->id }}, '{{ request()->ip() }}')"></label>
          <input type="radio" id="star3" name="rating" value="3">
          <label class="bp_str rated" for="star3" onclick="eventRating(3, {{ $event->id }}, '{{ request()->ip() }}')"></label>
          <input type="radio" id="star4" name="rating" value="4">
          <label class="bp_str rated" for="star4" onclick="eventRating(4, {{ $event->id }}, '{{ request()->ip() }}')"></label>
          <input type="radio" id="star5" name="rating" value="5">
          <label class="bp_str" for="star5" onclick="eventRating(5, {{ $event->id }}, '{{ request()->ip() }}')"></label>
        </div>
        <p class="ratingCalculator">Rated 4 from 21 votes</p>
      </div>
      <!-- rating end -->

      <!-- Expert Review -->
      <div class="wgt">
        <div class="pst">
          <div class="hd">
            <div>
              <h3>Why we love {{ $event->event_name }} <i class="bp_hrt"></i></h3>
            </div>
          </div>
          <div class="cnt">
            <p>{{ $event->event_short_content ?? 'Discover amazing deals and discounts with ' . $event->event_name . '. This special event offers exclusive savings and promotional codes for your favorite stores.' }}</p>
          </div>
        </div>
      </div>

      <!-- Today's Discount Code -->
      <div class="wgt today-discount-code">
        <div class="padding-div">
          <h3>Today's Hand Tested Discount Code</h3>
          <p class="last-update">Last updated: <span>{{ date('d-M-Y') }}</span></p>
          <ol>
            <li>Voucher Codes: <span>{{ $eventCoupons->where('coupon_code', '!=', null)->count() }}</span></li>
            <li>Deals: <span>{{ $eventCoupons->where('coupon_code', null)->count() }}</span></li>
          </ol>
        </div>
        <span class="total-offers">Total Offers: <span>{{ $eventCoupons->count() }}</span></span>
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
          <a href="#abtStr">About {{ $event->event_name }}</a>
          <a href="#tpHntsss" title="Hints and Tips">Hints and Tips</a>
          <a href="#srtFaq" title="{{ $event->event_name }}">Event Details</a>
        </div>
        <!-- quick links <end> -->
      </div>
      <!-- filters <end> -->

      <!-- What Makes -->
      <div class="wgt">
        <h3>What Makes <i class="bp_hrt"></i> {{ $event->event_name }} Special?</h3>
        <ol>
          <li><i class="bp_fr-dls"></i> Exclusive Deals</li>
          <li><i class="bp_fr-dl"></i> Limited Time Offers</li>
          <li><i class="bp_st-ofr"></i> Special Promotions</li>
        </ol>
      </div>
      <!-- What Makes end -->

      <!-- Event Info -->
      @if($event->date_available || $event->date_expiry)
      <div class="wgt">
        <h3>Event Details</h3>
        @if($event->date_available)
          <p><strong>Available From:</strong> {{ \Carbon\Carbon::parse($event->date_available)->format('F d, Y') }}</p>
        @endif
        @if($event->date_expiry)
          <p><strong>Expires On:</strong> {{ \Carbon\Carbon::parse($event->date_expiry)->format('F d, Y') }}</p>
        @endif
        @if($event->event_type)
          <p><strong>Event Type:</strong> {{ $event->event_type }}</p>
        @endif
      </div>
      @endif

      <!-- Related Events -->
      @if($relatedEvents->count() > 0)
      <div class="wgt">
        <h3>Related Events</h3>
        <div class="btns">
          @foreach($relatedEvents as $relatedEvent)
            <a href="{{ route('event.detail', $relatedEvent->seo_url) }}" title="{{ $relatedEvent->event_name }}">{{ $relatedEvent->event_name }}</a>
          @endforeach
        </div>
      </div>
      @endif

      <!-- Participating Stores -->
      @if($eventStores->count() > 0)
      <div class="wgt">
        <h3>Participating Stores</h3>
        <div class="btns">
          @foreach($eventStores as $store)
            <a href="{{ route('store', $store->seo_url) }}" title="{{ $store->store_name }}">{{ $store->store_name }}</a>
          @endforeach
        </div>
      </div>
      @endif

      <!-- Event shoppers also like -->
      @if($relatedEvents->count() > 0)
      <div class="wgt">
        <h3>{{ $event->event_name }} shoppers also like</h3>
        <div class="lgos">
          @foreach($relatedEvents->take(8) as $relatedEvent)
            <a href="{{ route('event.detail', $relatedEvent->seo_url) }}" title="{{ $relatedEvent->event_name }}">
              @if($relatedEvent->front_image)
                <img src="{{ asset( $relatedEvent->front_image) }}" alt="{{ $relatedEvent->event_name }} discount code" title="{{ $relatedEvent->event_name }} discount code" decoding="async" loading="lazy" width="64" height="64">
              @else
                <div class="store-placeholder-small">{{ substr($relatedEvent->event_name, 0, 2) }}</div>
              @endif
              <div>
                {{ $relatedEvent->event_name }}
                <span>{{ rand(5, 15) }} Discount Available</span>
              </div>
            </a>
          @endforeach
        </div>
      </div>
      @endif

      <!-- Hints & Tips -->
      <div class="wgt" id="tpHntsss">
        <div class="tpHnts">
          <h3>Hints & Tips</h3>
          <div>If you are looking for additional ways to save a significant amount of money during {{ $event->event_name }}, the following are some of the ways you can do so:</div>
          <ul>
            <li>Always check out our website before making a purchase during {{ $event->event_name }} to see if you can get products at a lower price than what they now offer.</li>
            <li>You may ensure that you are aware of the most recent changes made to the {{ $event->event_name }} by subscribing to the newsletter programmed offered by the company.</li>
            <li>Don't forget to look through the sales and clearance part of the website for {{ $event->event_name }}! Deals like those can be found at the location.</li>
            <li>If you follow {{ $event->event_name }} on any of the major social media platforms (Facebook, Instagram, or Twitter), you will never miss an update.</li>
            <li>{{ $event->event_name }} is known to update their website with promotional codes for gift cards, free shipping and delivery, and next-day delivery frequently. Be sure to confirm that they are.</li>
          </ul>
        </div>
      </div>
      <!-- Hints & Tips end -->
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
                    <span id="cmBrandText">EVENT</span>
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
/* Event-specific styles */
.event-type-badge {
    background: var(--primary-color);
    color: white;
    padding: 2px 8px;
    border-radius: 10px;
    font-size: 10px;
    font-weight: 500;
    margin-left: 5px;
}

.store-placeholder-small {
    width: 64px;
    height: 64px;
    background: #4a0c98;
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 8px;
    font-weight: bold;
    font-size: 18px;
}

.no-coupons {
    text-align: center;
    padding: 40px 20px;
    color: #666;
}

.no-coupons p {
    font-size: 16px;
    margin: 0;
}

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

.cm-close {
    position: absolute;
    top: 15px;
    right: 20px;
    background: none;
    border: none;
    font-size: 24px;
    cursor: pointer;
    color: #666;
    z-index: 3;
}

.cm-close:hover {
    color: #000;
}

.cm-main-content {
    text-align: center;
}

.cm-title {
    font-size: 24px;
    font-weight: bold;
    margin-bottom: 20px;
    color: #333;
}

.cm-code-section {
    margin: 20px 0;
}

.cm-code-display {
    background: #f8f9fa;
    border: 2px dashed var(--primary-color);
    border-radius: 8px;
    padding: 15px;
    font-size: 18px;
    font-weight: bold;
    color: var(--primary-color);
    margin-bottom: 15px;
    font-family: monospace;
}

.cm-copy-btn {
    background: var(--primary-color);
    color: white;
    border: none;
    padding: 12px 24px;
    border-radius: 6px;
    font-size: 16px;
    cursor: pointer;
    transition: background 0.3s;
}

.cm-copy-btn:hover {
    background: var(--primary-color);
}

.cm-note {
    margin-top: 20px;
    color: #666;
    font-size: 14px;
}

/* Email Subscription Styles */
.cm-email-content {
    text-align: center;
}

.cm-brand-logo {
    margin-bottom: 15px;
}

.cm-brand-circle {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    background: var(--primary-color);
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

.cm-email-subtitle {
    color: #6b7280;
    font-size: 16px;
    margin: 0 0 25px;
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
    background: var(--primary-color);
    color: white;
    border: none;
    padding: 12px;
    border-radius: 6px;
    font-size: 16px;
    cursor: pointer;
    transition: background 0.3s;
}

.cm-email-form button:hover {
    background: var(--primary-color);
}

.cm-email-privacy {
    font-size: 12px;
    color: #999;
    margin-top: 15px;
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
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
  const modal = document.getElementById('couponModal');
  const closeBtn = document.querySelector('.cm-close');
  const overlay = document.querySelector('.cm-overlay');
  const cmCode = document.getElementById('cmCode');
  const cmCopy = document.getElementById('cmCopy');
  const cmTitle = document.getElementById('cmTitle');
  const cmNote = document.getElementById('cmNote');
  const cmBrandText = document.getElementById('cmBrandText');
  const cmEmailTitle = document.getElementById('cmEmailTitle');

  function closeModal() {
    modal.style.display = 'none';
    document.body.style.overflow = '';
  }

  function openModal(code, affiliate, store, title) {
    if (cmCode) cmCode.textContent = code;
    if (cmTitle) cmTitle.textContent = title || 'Here is your code';
    if (cmEmailTitle) cmEmailTitle.textContent = `Get More ${store} Deals!`;

    if (cmBrandLogo && cmBrandText) {
      if (store && store !== 'Store') {
        cmBrandText.textContent = store.substring(0,5).toUpperCase();
      } else {
        cmBrandText.textContent = 'STORE';
      }
    }

    modal.style.display = 'block';
    modal.setAttribute('aria-hidden','false');
    document.body.style.overflow = 'hidden';
  }

  // Copy functionality
  if (cmCopy) {
    cmCopy.addEventListener('click', function() {
      if (cmCode) {
        navigator.clipboard.writeText(cmCode.textContent).then(() => {
          cmCopy.textContent = 'Copied!';
          setTimeout(() => {
            cmCopy.textContent = 'Copy Code';
          }, 2000);
        });
      }
    });
  }

  // Reveal code buttons
  document.querySelectorAll('.cpBtn.reveal-code').forEach(btn => {
    btn.addEventListener('click', function(e) {
      e.preventDefault();
      const code = this.dataset.code;
      const affiliate = this.dataset.affiliate;
      const store = this.dataset.store;
      const title = this.dataset.title;
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
      const affiliate = this.getAttribute('href') || this.dataset.affiliate || '#';
      const store = this.dataset.store || this.dataset.title || '';
      const title = this.dataset.title || '';

      if (affiliate && affiliate !== '#') {
        const currentUrl = window.location.href.split('#')[0].split('?')[0];
        const popupUrl = currentUrl + '?show_coupon=1&affiliate=' + encodeURIComponent(affiliate) + '&store=' + encodeURIComponent(store) + '&title=' + encodeURIComponent(title);
        window.open(popupUrl, '_blank');
        window.location.href = affiliate;
      }
    });
  });

  // Email form
  const emailForm = document.getElementById('cmEmailForm');
  if (emailForm) {
    emailForm.addEventListener('submit', function(e) {
      e.preventDefault();
      const email = this.querySelector('input[type="email"]').value;
      if (email) {
        // Here you would typically send the email to your backend
        alert('Thank you for subscribing!');
        closeModal();
      }
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
      if (!code) {
        if (cmCode) cmCode.textContent = 'No code required';
        if (cmCopy) cmCopy.style.display = 'none';
      }
    }
  } catch (e) {
    console.log('URL params not supported');
  }
});
</script>

<style>
/* Event Banner Responsive Styles */
@media (max-width: 768px) {
    .event-banner-image,
    .event-banner-fallback {
        height: 300px !important;
    }
}

@media (max-width: 480px) {
    .event-banner-image,
    .event-banner-fallback {
        height: 250px !important;
    }

    /* Carousel responsive styles */
    .carousel-nav {
        width: 40px;
        height: 40px;
    }

    .carousel-prev {
        left: 10px;
    }

    .carousel-next {
        right: 10px;
    }

}

/* Carousel Container */
.deals-carousel-container {
    position: relative;
    width: 100%;
    overflow: hidden;
}

/* Fallback Grid */
.deals-fallback-grid {
    width: 100%;
}

.deals-fallback-grid .horizontal-deals-container {
    display: flex;
    gap: 1.5rem;
    padding: 1rem 0;
    overflow-x: auto;
    scroll-behavior: smooth;
    scrollbar-width: thin;
    scrollbar-color: #ccc transparent;
}

.deals-fallback-grid .horizontal-deals-container::-webkit-scrollbar {
    height: 6px;
}

.deals-fallback-grid .horizontal-deals-container::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 3px;
}

.deals-fallback-grid .horizontal-deals-container::-webkit-scrollbar-thumb {
    background: #ccc;
    border-radius: 3px;
}

.deals-fallback-grid .horizontal-deals-container::-webkit-scrollbar-thumb:hover {
    background: #999;
}

/* Carousel Navigation Arrows */
.carousel-nav {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    background: rgba(255, 255, 255, 0.9);
    border: none;
    border-radius: 50%;
    width: 50px;
    height: 50px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    z-index: 10;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    transition: all 0.3s ease;
    color: #2c3e50;
}

.carousel-nav:hover {
    background: rgba(255, 255, 255, 1);
    transform: translateY(-50%) scale(1.1);
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.2);
}

.carousel-prev {
    left: 20px;
}

.carousel-next {
    right: 20px;
}

/* Carousel Track */
.carousel-track {
    overflow: hidden;
    width: 100%;
}

/* Horizontal Deals Layout Styles */
.horizontal-deals-container {
    display: flex;
    gap: 1.5rem;
    padding: 1rem 0;
    transition: transform 0.5s ease;
    width: max-content;
}

/* Carousel Pagination */
.carousel-pagination {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 8px;
    margin-top: 20px;
    padding: 5px 20px;
}

.carousel-dot {
    width: 12px;
    height: 12px;
    border-radius: 50%;
    background: #e0e0e0;
    border: none;
    cursor: pointer;
    transition: all 0.3s ease;
}

.carousel-dot.active {
    background: #3498db;
    transform: scale(1.2);
}

.carousel-dot:hover {
    background: #2980b9;
    transform: scale(1.1);
}

.horizontal-deal-card {
    flex: 0 0 300px;
    background: white;
    border-radius: 12px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    overflow: hidden;
    transition: all 0.3s ease;
    cursor: pointer;
}

.horizontal-deal-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.15);
}

.deal-image-container {
    position: relative;
    height: 200px;
    overflow: hidden;
}

.deal-main-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.horizontal-deal-card:hover .deal-main-image {
    transform: scale(1.05);
}

.deal-placeholder-image {
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, #f8f9fa, #e9ecef);
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    color: #6c757d;
}

.placeholder-icon {
    font-size: 3rem;
    margin-bottom: 0.5rem;
}

.store-logo-overlay {
    position: absolute;
    bottom: 15px;
    left: 15px;
    z-index: 2;
}

.overlay-store-logo {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    object-fit: cover;
    border: 3px solid white;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
}

.overlay-store-logo-placeholder {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    background: var(--primary-color);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    font-size: 1rem;
    border: 3px solid white;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
}

.deal-badge-overlay {
    position: absolute;
    top: 15px;
    right: 15px;
    background: #FFD700;
    color: #333;
    padding: 0.4rem 0.8rem;
    border-radius: 15px;
    font-size: 0.75rem;
    font-weight: 700;
    text-transform: uppercase;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
    z-index: 2;
}

.deal-text-content {
    padding: 1.5rem;
}

.brand-name {
    font-size: 1.1rem;
    font-weight: 600;
    color: #333;
    margin-bottom: 0.5rem;
    line-height: 1.3;
}

.deal-description {
    font-size: 0.9rem;
    color: #666;
    margin-bottom: 1rem;
    line-height: 1.4;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.view-deals-link {
    color: var(--primary-color);
    text-decoration: none;
    font-size: 0.85rem;
    font-weight: 500;
    transition: color 0.3s ease;
    display: inline-block;
}

.view-deals-link:hover {
    color: var(--secondary-color, #cc0000);
    text-decoration: underline;
}

.no-deals {
    text-align: center;
    padding: 4rem 2rem;
    color: #64748b;
    background: white;
    border-radius: 20px;
    border: 2px dashed #e2e8f0;
    margin: 2rem auto;
    max-width: 500px;
}

.no-deals-icon {
    font-size: 4rem;
    margin-bottom: 1rem;
}

.no-deals h3 {
    font-size: 1.5rem;
    font-weight: 600;
    color: #374151;
    margin-bottom: 0.5rem;
}

/* Mobile Responsive Design */
@media (max-width: 768px) {
    .hot-deals-section {
        padding: 20px 0;
    }

    .deals-carousel-container {
        padding: 0 20px;
    }

    .horizontal-deals-container {
        padding: 0;
        justify-content: center;
    }

    .deal-image-container {
        height: 180px;
    }

    .deal-text-content {
        padding: 15px;
    }

    .brand-name {
        font-size: 16px;
        font-weight: 700;
        margin-bottom: 8px;
        line-height: 1.2;
    }

    .deal-description {
        font-size: 14px;
        margin-bottom: 12px;
        line-height: 1.3;
        color: #6b7280;
    }

    .view-deals-link {
        font-size: 12px;
        padding: 8px 0;
    }

    .overlay-store-logo,
    .overlay-store-logo-placeholder {
        width: 45px;
        height: 45px;
        font-size: 12px;
        bottom: 10px;
        left: 10px;
    }

    .deal-badge-overlay {
        padding: 6px 12px;
        font-size: 11px;
        top: 10px;
        right: 10px;
    }

    .carousel-nav {
        width: 40px;
        height: 40px;
        top: 50%;
        transform: translateY(-50%);
    }

    .carousel-prev {
        left: 5px;
    }

    .carousel-next {
        right: 5px;
    }

    .carousel-pagination {
        margin-top: 20px;
        gap: 8px;
    }

    .carousel-pagination .dot {
        width: 8px;
        height: 8px;
    }
}

@media (max-width: 480px) {
    .hot-deals-section {
        padding: 15px 0;
    }

    .deals-carousel-container {
        padding: 0 15px;
    }

    .horizontal-deals-container {
        padding: 0;
        justify-content: center;
    }

    .deal-image-container {
        height: 160px;
    }

    .deal-text-content {
        padding: 12px;
    }

    .brand-name {
        font-size: 15px;
        margin-bottom: 6px;
    }

    .deal-description {
        font-size: 13px;
        margin-bottom: 10px;
    }

    .view-deals-link {
        font-size: 11px;
        padding: 6px 0;
    }

    .overlay-store-logo,
    .overlay-store-logo-placeholder {
        width: 40px;
        height: 40px;
        font-size: 11px;
        bottom: 8px;
        left: 8px;
    }

    .deal-badge-overlay {
        padding: 4px 8px;
        font-size: 10px;
        top: 8px;
        right: 8px;
    }

    .carousel-nav {
        width: 35px;
        height: 35px;
    }

    .carousel-prev {
        left: 2px;
    }

    .carousel-next {
        right: 2px;
    }

    .carousel-pagination {
        margin-top: 15px;
        gap: 6px;
    }

    .carousel-pagination .dot {
        width: 6px;
        height: 6px;
    }
}

@media (max-width: 360px) {
    .deals-carousel-container {
        padding: 0 10px;
    }

    .deal-image-container {
        height: 140px;
    }

    .deal-text-content {
        padding: 10px;
    }

    .brand-name {
        font-size: 14px;
    }

    .deal-description {
        font-size: 12px;
    }

    .view-deals-link {
        font-size: 10px;
    }

    .overlay-store-logo,
    .overlay-store-logo-placeholder {
        width: 35px;
        height: 35px;
        font-size: 10px;
    }

    .deal-badge-overlay {
        padding: 3px 6px;
        font-size: 9px;
    }
}

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
    border: 2px dashed var(--primary-color);
    border-radius: 8px;
    padding: 15px;
    font-size: 18px;
    font-weight: bold;
    color: var(--primary-color);
    margin-bottom: 15px;
    font-family: monospace;
}

.cm-copy-btn {
    background: var(--primary-color);
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
    background: var(--primary-color);
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
    background: var(--primary-color);
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
    background: var(--primary-color);
    color: white;
    border: none;
    padding: 12px;
    border-radius: 6px;
    font-size: 16px;
    cursor: pointer;
    transition: background 0.3s;
}

.cm-email-form button:hover {
    background: var(--primary-color);
}

.cm-email-privacy {
    font-size: 11px;
    color: #6b7280;
    line-height: 1.4;
    margin: 0;
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

/* Hot Deals Section Styles - Same as Home Page */
.hot-deals-section {
    padding: 3rem 0;
    background: var(--background-primary-color, #ffffff);
}

.section-title {
    color: var(--text-color, #333333) !important;
    font-size: 2rem;
    font-weight: 700;
    text-align: center;
    margin-bottom: 0.5rem;
}

.section-subtitle {
    color: var(--text-color, #666666) !important;
    text-align: center;
    margin-bottom: 2rem;
}

.deals-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 1rem;
    margin-top: 2rem;
}

.deal-card {
    background: var(--background-primary-color, #ffffff);
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    border: 1px solid var(--background-secondary-color, #e5e5e5);
    transition: all 0.3s ease;
    position: relative;
    height: 320px;
}

.deal-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 16px rgba(0, 0, 0, 0.15);
}

/* Deal Image Section */
.deal-image-section {
    position: relative;
    height: 140px;
    overflow: hidden;
    background: var(--background-secondary-color, #f8f9fa);
}

.deal-cover-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.deal-card:hover .deal-cover-image {
    transform: scale(1.05);
}

.deal-placeholder-image {
    width: 100%;
    height: 100%;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    background: var(--background-secondary-color, #f8f9fa);
    color: var(--text-color, #666);
}

.placeholder-icon {
    font-size: 2rem;
    margin-bottom: 0.25rem;
}

.deal-placeholder-image span {
    font-size: 0.9rem;
    font-weight: 600;
    text-align: center;
}

/* Discount Badge */
.discount-badge {
    position: absolute;
    top: 8px;
    right: 8px;
    background: var(--primary-color);
    color: white;
    padding: 0.25rem 0.5rem;
    border-radius: 12px;
    font-size: 0.7rem;
    font-weight: 700;
}

/* Exclusive Badge */
.exclusive-badge {
    position: absolute;
    top: 8px;
    left: 8px;
    background: var(--primary-color);
    color: white;
    padding: 0.2rem 0.4rem;
    border-radius: 8px;
    font-size: 0.6rem;
    font-weight: 700;
    display: flex;
    align-items: center;
    gap: 0.1rem;
}

.exclusive-icon {
    font-size: 0.6rem;
}

/* Deal Content Wrapper */
.deal-content-wrapper {
    padding: 1rem;
    height: 180px;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}

.deal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
}

.store-info {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.store-logo {
    width: 30px;
    height: 30px;
    border-radius: 50%;
    object-fit: cover;
    border: 1px solid #e5e5e5;
}

.store-details h3 {
    font-size: 0.9rem;
    font-weight: 600;
    color: var(--text-color, #333);
    margin: 0;
}

.verified-badge {
    background: #38a169;
    color: white;
    padding: 0.1rem 0.4rem;
    border-radius: 8px;
    font-size: 0.6rem;
    font-weight: 600;
    margin-top: 0.1rem;
    display: inline-block;
}

.type-badge {
    padding: 0.2rem 0.4rem;
    border-radius: 8px;
    font-size: 0.6rem;
    font-weight: 700;
    display: flex;
    align-items: center;
    gap: 0.1rem;
}

.type-badge.code {
    background: var(--primary-color);
    color: white;
}

.type-badge.deal {
    background: var(--secondary-color, #333);
    color: white;
}

.badge-icon {
    font-size: 0.6rem;
}

.deal-content {
    margin-bottom: 0.75rem;
}

.deal-title {
    font-size: 0.9rem;
    font-weight: 600;
    color: var(--text-color, #333);
    margin-bottom: 0.25rem;
    line-height: 1.2;
}

.deal-meta {
    display: flex;
    gap: 0.75rem;
    flex-wrap: wrap;
}

.meta-item {
    display: flex;
    align-items: center;
    gap: 0.1rem;
    font-size: 0.65rem;
    color: var(--text-color, #666);
    background: var(--background-secondary-color, #f8f9fa);
    padding: 0.2rem 0.4rem;
    border-radius: 8px;
    border: 1px solid var(--background-secondary-color, #e5e5e5);
}

.meta-icon {
    font-size: 0.7rem;
}

.deal-footer {
    display: flex;
    gap: 0.5rem;
    align-items: center;
}

.deal-btn {
    flex: 1;
    border: none;
    border-radius: 8px;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.3rem;
    font-size: 0.8rem;
    padding: 0.5rem 1rem;
}

.deal-btn.reveal-code {
    background: var(--primary-color);
    color: white;
}

.deal-btn.get-deal {
    background: var(--secondary-color, #333);
    color: white;
}

.deal-btn:hover {
    transform: translateY(-1px);
}

.deal-btn.reveal-code:hover {
    background: var(--secondary-color, #cc0000);
}

.deal-btn.get-deal:hover {
    background: var(--text-color, #555);
}

.btn-icon {
    font-size: 0.8rem;
}

.btn-arrow {
    font-size: 1.2rem;
    transition: transform 0.3s ease;
}

.deal-btn:hover .btn-arrow {
    transform: translateX(3px);
}

.no-deals {
    text-align: center;
    padding: 4rem 2rem;
    color: var(--text-color, #64748b);
    grid-column: 1 / -1;
    background: var(--background-primary-color, white);
    border-radius: 20px;
    border: 2px dashed var(--background-secondary-color, #e2e8f0);
}

.no-deals-icon {
    font-size: 4rem;
    margin-bottom: 1rem;
}

.no-deals h3 {
    font-size: 1.5rem;
    font-weight: 600;
    color: var(--text-color, #374151);
    margin-bottom: 0.5rem;
}

/* Responsive Design */
@media (max-width: 1200px) {
    .deals-grid {
        grid-template-columns: repeat(3, 1fr);
        gap: 1.25rem;
    }
}

@media (max-width: 992px) {
    .deals-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 1rem;
    }

    .deal-card {
        height: 300px;
    }

    .deal-image-section {
        height: 120px;
    }

    .deal-content-wrapper {
        padding: 0.75rem;
        height: 180px;
    }
}

@media (max-width: 768px) {
    .deals-grid {
        grid-template-columns: 1fr;
        gap: 1rem;
    }

    .deal-card {
        max-width: 350px;
        margin: 0 auto;
        height: 280px;
    }

    .deal-image-section {
        height: 100px;
    }

    .deal-content-wrapper {
        padding: 0.75rem;
        height: 180px;
    }

    .hot-deals-section {
        padding: 3rem 0;
    }

    .section-title {
        font-size: 2rem !important;
    }
}

@media (max-width: 480px) {
    .deal-card {
        height: 260px;
    }

    .deal-image-section {
        height: 90px;
    }

    .deal-content-wrapper {
        padding: 0.5rem;
        height: 170px;
    }

    .deal-title {
        font-size: 0.8rem;
    }

    .deal-btn {
        font-size: 0.7rem;
        padding: 0.4rem 0.8rem;
    }
}
</style>

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

.cm-close {
    position: absolute;
    top: 15px;
    right: 20px;
    background: none;
    border: none;
    font-size: 24px;
    cursor: pointer;
    color: #666;
    z-index: 3;
}

.cm-close:hover {
    color: #000;
}

.cm-main-content {
    text-align: center;
}

.cm-title {
    font-size: 24px;
    font-weight: bold;
    margin-bottom: 20px;
    color: #333;
}

.cm-code-section {
    margin: 20px 0;
}

.cm-code-display {
    background: var(--background-secondary-color, #f8f9fa);
    border: 2px dashed var(--primary-color);
    border-radius: 8px;
    padding: 15px;
    font-size: 18px;
    font-weight: bold;
    color: var(--primary-color);
    margin-bottom: 15px;
    font-family: monospace;
}

.cm-copy-btn {
    background: var(--primary-color);
    color: white;
    border: none;
    padding: 12px 24px;
    border-radius: 6px;
    font-size: 16px;
    cursor: pointer;
    transition: background 0.3s;
}

.cm-copy-btn:hover {
    background: var(--secondary-color, #cc0000);
}

.cm-note {
    margin-top: 20px;
    color: var(--text-color, #666);
    font-size: 14px;
}

.cm-email-content {
    text-align: center;
}

.cm-brand-logo {
    margin-bottom: 20px;
}

.cm-brand-circle {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    background: var(--primary-color);
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto;
    color: white;
    font-weight: bold;
    font-size: 14px;
}

.cm-brand-circle img {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    object-fit: cover;
}

.cm-email-title {
    font-size: 22px;
    font-weight: bold;
    margin-bottom: 10px;
    color: var(--text-color, #333);
}

.cm-email-subtitle {
    color: var(--text-color, #666);
    margin-bottom: 20px;
    font-size: 14px;
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
    background: var(--primary-color);
    color: white;
    border: none;
    padding: 12px;
    border-radius: 6px;
    font-size: 16px;
    cursor: pointer;
    transition: background 0.3s;
}

.cm-email-form button:hover {
    background: var(--secondary-color, #cc0000);
}

.cm-email-privacy {
    font-size: 12px;
    color: var(--text-color, #999);
    margin-top: 15px;
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
  const cmBrandLogo = document.getElementById('cmBrandLogo');
  const cmBrandText = document.getElementById('cmBrandText');

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
      if (code === 'No code required' || code === 'No%20code%20required' || code === '' || !code) {
        cmCopy.style.display = 'none';
        if (cmCode) cmCode.textContent = 'No code required';
      } else {
        cmCopy.style.display = 'block';
      }
    }

    modal.style.display = 'block';
    modal.setAttribute('aria-hidden','false');
    document.body.style.overflow = 'hidden';

    // Auto redirect to affiliate URL after 2 seconds (like home page)
    if (affiliate && affiliate !== '#') {
      setTimeout(function() {
        window.open(affiliate, '_blank');
      }, 2000);
    }
  }

  function closeModal() {
    modal.style.display = 'none';
    modal.setAttribute('aria-hidden','true');
    document.body.style.overflow = '';
  }

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
            cmCopy.style.backgroundColor = 'var(--primary-color)';
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
          cmCopy.style.backgroundColor = 'var(--primary-color)';
        }, 2000);
      }
    });
  }

  // Redirect button
  const cmRedirect = document.getElementById('cmRedirect');
  if (cmRedirect) {
    cmRedirect.addEventListener('click', function() {
      const currentAffiliate = window.currentAffiliateUrl || '#';
      if (currentAffiliate && currentAffiliate !== '#') {
        window.open(currentAffiliate, '_blank');
        closeModal(); // Close popup after redirect
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

  if (closeBtn) closeBtn.addEventListener('click', closeModal);
  if (overlay) overlay.addEventListener('click', closeModal);

  // ESC key
  document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape' && modal.style.display === 'block') {
      closeModal();
    }
  });

  // Make openModal function global for onclick handlers
  window.openModal = openModal;

  // Function for horizontal deal cards (same as Get Deal button)
  window.openDealModal = function(affiliate, store, title) {
    if (affiliate && affiliate !== '#') {
      const currentUrl = window.location.href.split('#')[0].split('?')[0];
      const popupUrl = currentUrl + '?show_coupon=1&code=No%20code%20required&affiliate=' + encodeURIComponent(affiliate) + '&store=' + encodeURIComponent(store) + '&title=' + encodeURIComponent(title);
      window.open(popupUrl, '_blank');
      window.location.href = affiliate;
    }
  };

  // Handle URL parameters for popup (including from horizontal deal cards)
  try {
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('show_coupon') === '1') {
      const code = urlParams.get('code') || '';
      const affiliate = urlParams.get('affiliate') || '#';
      const store = urlParams.get('store') || 'Store';
      const title = urlParams.get('title') || 'Here is your code';

      openModal(code, affiliate, store, title);

      // If no code or "No code required", hide Copy Code button
      if (!code || code === 'No code required' || code === 'No%20code%20required') {
        if (cmCode) cmCode.textContent = 'No code required';
        if (cmCopy) {
          cmCopy.style.display = 'none';
        }
      }

      history.replaceState({}, '', window.location.pathname);
    }
  } catch (e) {
    console.log('URL params not supported');
  }

  // Carousel functionality
  let currentSlide = 0;
  let totalSlides = 0;
  let cardsPerSlide = 1; // Always show 1 card at a time

  function initCarousel() {
    const container = document.querySelector('.horizontal-deals-container');
    const cards = container.querySelectorAll('.horizontal-deal-card');
    const pagination = document.getElementById('carouselPagination');
    const carouselContainer = document.querySelector('.deals-carousel-container');
    const fallbackGrid = document.getElementById('dealsFallbackGrid');

    if (!container || !cards.length) return;

    // Hide carousel if less than 4 cards, show fallback grid
    if (cards.length < 4) {
      if (carouselContainer) {
        carouselContainer.style.display = 'none';
      }
      if (fallbackGrid) {
        fallbackGrid.style.display = 'block';
      }
      return;
    }

    // Show carousel if 4 or more cards, hide fallback grid
    if (carouselContainer) {
      carouselContainer.style.display = 'block';
    }
    if (fallbackGrid) {
      fallbackGrid.style.display = 'none';
    }

    totalSlides = cards.length; // Each card is a slide
    const maxSlide = Math.max(0, totalSlides - 4); // Stop at last 4 cards

    // Generate pagination dots - only show dots for slides we can navigate to
    pagination.innerHTML = '';
    for (let i = 0; i <= maxSlide; i++) {
      const dot = document.createElement('button');
      dot.className = 'carousel-dot';
      if (i === 0) dot.classList.add('active');
      dot.onclick = () => goToSlide(i);
      pagination.appendChild(dot);
    }

    updateCarousel();
  }

  function updateCarousel() {
    const container = document.querySelector('.horizontal-deals-container');
    const pagination = document.getElementById('carouselPagination');

    if (!container) return;

    // Calculate transform value - move one card at a time
    const cardWidth = 320; // 300px + 20px gap
    const translateX = -(currentSlide * cardWidth);
    container.style.transform = `translateX(${translateX}px)`;

    // Update pagination dots
    const dots = pagination.querySelectorAll('.carousel-dot');
    dots.forEach((dot, index) => {
      dot.classList.toggle('active', index === currentSlide);
    });

    // Show/hide navigation arrows
    const prevBtn = document.querySelector('.carousel-prev');
    const nextBtn = document.querySelector('.carousel-next');

    // Stop at last 4 cards - don't go beyond (totalSlides - 4)
    const maxSlide = Math.max(0, totalSlides - 4);

    if (prevBtn) prevBtn.style.display = currentSlide === 0 ? 'none' : 'flex';
    if (nextBtn) nextBtn.style.display = currentSlide >= maxSlide ? 'none' : 'flex';
  }

  function moveCarousel(direction) {
    const newSlide = currentSlide + direction;
    const maxSlide = Math.max(0, totalSlides - 4); // Stop at last 4 cards

    if (newSlide >= 0 && newSlide <= maxSlide) {
      currentSlide = newSlide;
      updateCarousel();
    }
  }

  function goToSlide(slideIndex) {
    const maxSlide = Math.max(0, totalSlides - 4); // Stop at last 4 cards

    if (slideIndex >= 0 && slideIndex <= maxSlide) {
      currentSlide = slideIndex;
      updateCarousel();
    }
  }

  // Make carousel functions global
  window.moveCarousel = moveCarousel;
  window.goToSlide = goToSlide;

  // Initialize carousel when page loads
  initCarousel();
});
</script>

@endsection

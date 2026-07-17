<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<!-- sidenv: Side Navigation <component:start> -->
<nav class="sidenv">
    <!-- snhead: Side Navigation Head <start> -->
    <span class="snhead">Menu <button type="button" class="snx ico bp_close" aria-label="Close Notification">
    <i class="fa-solid fa-xmark"></i>
    </button></span>
    <!-- snhead: Side Navigation Head <end> -->

    <a href="{{ route('top-discounts') }}">Top 20 Discounts</a>

    <div class="sn-cat" id="snCat">
        <button type="button" class="sn-cat-trigger" id="snCatTrigger" aria-expanded="false" aria-controls="snCatList">
            Categories
            <svg class="sn-cat-chevron" width="12" height="12" viewBox="0 0 10 10" fill="none" stroke="currentColor" stroke-width="2"><path d="M2 3.5L5 6.5L8 3.5"/></svg>
        </button>
        <div class="sn-cat-list" id="snCatList">
            <button type="button" class="sn-cat-row" data-sn-cat="popular">
                <span>Popular</span>
                <svg width="10" height="10" viewBox="0 0 10 10" fill="none" stroke="currentColor" stroke-width="2" style="transform:rotate(-90deg)"><path d="M2 3.5L5 6.5L8 3.5"/></svg>
            </button>
            @foreach($navCategoryMenu['categories'] ?? [] as $category)
                <button type="button" class="sn-cat-row" data-sn-cat="cat-{{ $category->id }}">
                    <span>{{ $category->category_name }}</span>
                    <svg width="10" height="10" viewBox="0 0 10 10" fill="none" stroke="currentColor" stroke-width="2" style="transform:rotate(-90deg)"><path d="M2 3.5L5 6.5L8 3.5"/></svg>
                </button>
            @endforeach
        </div>

        <div class="sn-store-panel" data-sn-store-panel="popular">
            <button type="button" class="sn-store-back" data-sn-back>
                <svg width="12" height="12" viewBox="0 0 10 10" fill="none" stroke="currentColor" stroke-width="2" style="transform:rotate(90deg)"><path d="M2 3.5L5 6.5L8 3.5"/></svg>
                Popular
            </button>
            @forelse($navCategoryMenu['popular'] ?? [] as $store)
                <a href="{{ route('store', $store->seo_url) }}">{{ $store->short_name }}</a>
            @empty
                <p class="sn-store-empty">No stores available</p>
            @endforelse
            <a href="{{ route('categories') }}">All Stores</a>
        </div>
        @foreach($navCategoryMenu['categories'] ?? [] as $category)
            <div class="sn-store-panel" data-sn-store-panel="cat-{{ $category->id }}">
                <button type="button" class="sn-store-back" data-sn-back>
                    <svg width="12" height="12" viewBox="0 0 10 10" fill="none" stroke="currentColor" stroke-width="2" style="transform:rotate(90deg)"><path d="M2 3.5L5 6.5L8 3.5"/></svg>
                    {{ $category->category_name }}
                </button>
                <a href="{{ route('category', $category->seo_url) }}" class="sn-cat-view-all">View {{ $category->category_name }} deals</a>
                @forelse($category->stores as $store)
                    <a href="{{ route('store', $store->seo_url) }}">{{ $store->short_name }}</a>
                @empty
                    <p class="sn-store-empty">No stores available</p>
                @endforelse
                <a href="{{ route('categories') }}">All Stores</a>
            </div>
        @endforeach
    </div>

    <a href="{{ route('events') }}">Events</a>
    <a href="{{ route('blog') }}">Blog</a>
    <a href="{{ route('privacy-policy') }}">Privacy Policy</a>
    <a href="{{ route('about-us') }}">About Us</a>
    <a href="{{ route('contact') }}">Contact Us</a>

    @if(Auth::guard('customer')->check())
        <div class="user-menu">
            <span>Welcome, {{ Auth::guard('customer')->user()->name }}</span>
            <form method="POST" action="{{ route('customer.logout') }}" style="display: inline;">
                @csrf
                <button type="submit" class="logout-btn">Logout</button>
            </form>
        </div>
    @else
        <a href="{{ route('customer.login') }}" class="lgnbtn">Login</a>
        <a href="{{ route('customer.register') }}" class="lgnbtn">Sign Up</a>
    @endif

    <!-- scl: Social Links <start> -->
    <!-- <nav class="scl">
        @php
            $socialSettings = \App\Helpers\SettingsHelper::getSocial();
        @endphp
        @if($socialSettings['facebook_url'])
            <a href="{{ $socialSettings['facebook_url'] }}" class="ico fb" title="Facebook"></a>
        @endif
        @if($socialSettings['twitter_url'])
            <a href="{{ $socialSettings['twitter_url'] }}" class="ico twt" title="Twitter"></a>
        @endif
        @if($socialSettings['instagram_url'])
            <a href="{{ $socialSettings['instagram_url'] }}" class="ico ins" title="Instagram"></a>
        @endif
        @if($socialSettings['linkedin_url'])
            <a href="{{ $socialSettings['linkedin_url'] }}" class="ico linkedin" title="LinkedIn"></a>
        @endif
        @if($socialSettings['youtube_url'])
            <a href="{{ $socialSettings['youtube_url'] }}" class="ico youtube" title="YouTube"></a>
        @endif
    </nav> -->
    <!-- scl: Social Links <end> -->
</nav>
<!-- sidenv: Side Navigation <component:end> -->

<!-- header <component:start> -->
@php
    $brandingSettings = \App\Helpers\SettingsHelper::getBranding();
    $primaryColor = $brandingSettings['primary_color'] ?? '#2951c4';

    // Normalize hex (support 3 or 6 chars)
    $hex = ltrim($primaryColor, '#');
    if (strlen($hex) === 3) {
        $hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
    }

    $baseR = hexdec(substr($hex, 0, 2));
    $baseG = hexdec(substr($hex, 2, 2));
    $baseB = hexdec(substr($hex, 4, 2));

    // Helpers to compute WCAG contrast
    $calcLuminance = function (int $r, int $g, int $b): float {
        $toLinear = function (float $c): float {
            $c = $c / 255;
            return $c <= 0.03928 ? $c / 12.92 : pow(($c + 0.055) / 1.055, 2.4);
        };
        $R = $toLinear($r);
        $G = $toLinear($g);
        $B = $toLinear($b);
        return 0.2126 * $R + 0.7152 * $G + 0.0722 * $B;
    };

    $contrastWithWhite = function (int $r, int $g, int $b) use ($calcLuminance): float {
        $L = $calcLuminance($r, $g, $b);
        return (1.0 + 0.05) / ($L + 0.05);
    };

    // Ensure sufficient contrast
    $targetContrast = 4.5;
    $factor = 1.0;
    $adjR = $baseR;
    $adjG = $baseG;
    $adjB = $baseB;
    $steps = 0;
    while ($contrastWithWhite($adjR, $adjG, $adjB) < $targetContrast && $steps < 10) {
        $factor -= 0.08;
        if ($factor <= 0.2) $factor = 0.2;
        $adjR = max(0, min(255, (int) round($baseR * $factor)));
        $adjG = max(0, min(255, (int) round($baseG * $factor)));
        $adjB = max(0, min(255, (int) round($baseB * $factor)));
        $steps++;
    }
    $accessiblePrimary = sprintf('#%02X%02X%02X', $adjR, $adjG, $adjB);
@endphp
<header class="header-modern">
    <!-- Top Promotional Bar -->
    <div class="top-promo-bar" style="background: {{ $accessiblePrimary }} !important;">
        <div class="promo-container">
            <div class="promo-left">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                    <circle cx="12" cy="10" r="3"></circle>
                </svg>
                <span>Find a Store</span>
            </div>
            <div class="promo-center">
                <span>Get 35% Off with Code FG6556KD</span>
            </div>
            <div class="promo-right">
                <a href="#" class="promo-icon" title="Menu">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="3" width="7" height="7"></rect>
                        <rect x="14" y="3" width="7" height="7"></rect>
                        <rect x="14" y="14" width="7" height="7"></rect>
                        <rect x="3" y="14" width="7" height="7"></rect>
                    </svg>
                </a>
                <a href="#" class="promo-icon" title="Chat">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                    </svg>
                </a>
                <a href="#" class="promo-icon" title="Instagram">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect>
                        <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path>
                        <line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line>
                    </svg>
                </a>
                <a href="#" class="promo-icon" title="Shopping">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path>
                        <line x1="3" y1="6" x2="21" y2="6"></line>
                        <path d="M16 10a4 4 0 0 1-8 0"></path>
                    </svg>
                </a>
            </div>
        </div>
    </div>

    <!-- Top Bar -->
    <div class="top-bar">
        <div class="container">
            <!-- Mobile Top Row (Hamburger + Logo + Search) -->
            <div class="mobile-top-row">
                <!-- Hamburger Menu - Left -->
                <button class="mobile-menu-btn" id="mobileMenuBtn" aria-label="Toggle Menu" type="button" aria-expanded="false">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <line x1="3" y1="6" x2="21" y2="6"/>
                        <line x1="3" y1="12" x2="21" y2="12"/>
                        <line x1="3" y1="18" x2="21" y2="18"/>
                    </svg>
                </button>
                <!-- Logo - Center -->
                <div class="mobile-logo-section">
                    @php
                        $brandingSettings = \App\Helpers\SettingsHelper::getBranding();
                    @endphp
                    <a href="{{ route('home') }}" class="site-logo-modern" title="{{ $brandingSettings['site_name'] }}">
                        <!-- <span class="logo-text">{{ $brandingSettings['site_name'] ?? 'Hotsavinghub' }}</span> -->
                        @if($brandingSettings['site_logo_url'])
                            @php
                                // Get actual image dimensions to maintain proper aspect ratio (fixes Lighthouse "incorrect aspect ratio" warning)
                                $logoPath = public_path($brandingSettings['site_logo_url']);
                                $logoWidth = 200;
                                $logoHeight = 60;
                                if (file_exists($logoPath)) {
                                    $imageInfo = @getimagesize($logoPath);
                                    if ($imageInfo !== false) {
                                        $logoWidth = $imageInfo[0];
                                        $logoHeight = $imageInfo[1];
                                    }
                                }
                            @endphp
                            <img class="logo-img" loading="lazy" decoding="async" src="{{ $brandingSettings['site_logo_url'] }}" alt="{{ $brandingSettings['site_name'] }}" title="{{ $brandingSettings['site_name'] }}" width="{{ $logoWidth }}" height="{{ $logoHeight }}">
                        @else
                            <div class="logo-icon">
                                <svg width="40" height="40" viewBox="0 0 40 40" fill="none">
                                    <circle cx="20" cy="20" r="20" fill="#10b981"/>
                                    <path d="M15 20L18 23L25 16" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </div>
                        @endif
                    </a>
                </div>
                <!-- Search Icon - Right -->
                <button class="mobile-search-btn" id="mobileSearchBtn" aria-label="Search" type="button" aria-expanded="false">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#ffffff" stroke-width="2" style="display: block;">
                        <circle cx="11" cy="11" r="8"/>
                        <path d="m21 21-4.35-4.35"/>
                    </svg>
                </button>
            </div>

            <!-- Desktop/Mobile Middle Row -->
            <div class="top-bar-content">
                <!-- Left: Logo (Desktop) -->
                <div class="logo-section">
                    <a href="{{ route('home') }}" class="site-logo-modern" title="{{ $brandingSettings['site_name'] }}">
                        @if($brandingSettings['site_logo_url'])
                            @php
                                // Get actual image dimensions to maintain proper aspect ratio (fixes Lighthouse "incorrect aspect ratio" warning)
                                $logoPath = public_path($brandingSettings['site_logo_url']);
                                $logoWidth = 200;
                                $logoHeight = 60;
                                if (file_exists($logoPath)) {
                                    $imageInfo = @getimagesize($logoPath);
                                    if ($imageInfo !== false) {
                                        $logoWidth = $imageInfo[0];
                                        $logoHeight = $imageInfo[1];
                                    }
                                }
                            @endphp
                            <img class="logo-img" loading="lazy" decoding="async" src="{{ $brandingSettings['site_logo_url'] }}" alt="{{ $brandingSettings['site_name'] }}" title="{{ $brandingSettings['site_name'] }}" width="{{ $logoWidth }}" height="{{ $logoHeight }}">
                        @else
                            <div class="logo-icon">
                                <svg width="40" height="40" viewBox="0 0 40 40" fill="none">
                                    <circle cx="20" cy="20" r="20" fill="#10b981"/>
                                    <path d="M15 20L18 23L25 16" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </div>
                        @endif
                        <!-- <span class="logo-text">{{ $brandingSettings['site_name'] ?? 'Hotsavinghub' }}</span> -->
                    </a>
                </div>

                <!-- Middle: Search Component (Desktop) -->
                <!-- <div class="search-section">
                    <div class="search-bar-wrapper" id="searchBarTrigger">
                        <input type="text" class="search-bar-input" placeholder="Search for stores, brands, or products..." readonly>

                    </div>
                </div> -->
                <nav class="main-nav">
                    <!-- <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}">Home</a> -->
                    <a href="{{ route('top-discounts') }}" class="nav-link {{ request()->routeIs('top-discounts') ? 'active' : '' }}">Top 20 Discounts</a>

                        <!-- Trending Compact Menu -->
                        <div class="cat-menu" id="trendMenu">
                            <button type="button"
                                    class="nav-link cat-menu-trigger"
                                    id="trendMenuTrigger"
                                    aria-haspopup="true"
                                    aria-expanded="false"
                                    aria-controls="trendMenuPanel">
                                Trending
                                <svg class="dropdown-icon" width="10" height="10" viewBox="0 0 10 10" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M2 3.5L5 6.5L8 3.5"/>
                                </svg>
                            </button>
                            <div class="cat-menu-panel trend-menu-panel" id="trendMenuPanel" aria-label="Trending stores">
                                <div class="cat-menu-stores">
                                    <div class="cat-menu-store-panel is-active">
                                        @forelse($trendingStores ?? [] as $store)
                                            <a href="{{ route('store', $store->seo_url) }}">{{ $store->short_name }}</a>
                                        @empty
                                            <p class="cat-menu-empty">No trending stores available</p>
                                        @endforelse
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Categories Compact Menu -->
                        <div class="cat-menu" id="catMenu">
                            <button type="button"
                                    class="nav-link cat-menu-trigger {{ request()->routeIs('categories') || request()->routeIs('category') ? 'active' : '' }}"
                                    id="catMenuTrigger"
                                    aria-haspopup="true"
                                    aria-expanded="false"
                                    aria-controls="catMenuPanel">
                                Categories
                                <svg class="dropdown-icon" width="10" height="10" viewBox="0 0 10 10" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M2 3.5L5 6.5L8 3.5"/>
                                </svg>
                            </button>
                            <div class="cat-menu-panel" id="catMenuPanel" aria-label="Browse categories">
                                <div class="cat-menu-list">
                                    <a href="{{ route('categories') }}" class="cat-menu-item is-active" data-cat-target="popular">Popular</a>
                                    @foreach($navCategoryMenu['categories'] ?? [] as $category)
                                        <a href="{{ route('category', $category->seo_url) }}" class="cat-menu-item" data-cat-target="cat-{{ $category->id }}">{{ $category->category_name }}</a>
                                    @endforeach
                                </div>
                                <div class="cat-menu-divider" aria-hidden="true"></div>
                                <div class="cat-menu-stores">
                                    <div class="cat-menu-store-panel is-active" data-cat-panel="popular">
                                        @forelse($navCategoryMenu['popular'] ?? [] as $store)
                                            <a href="{{ route('store', $store->seo_url) }}">{{ $store->short_name }}</a>
                                        @empty
                                            <p class="cat-menu-empty">No stores available</p>
                                        @endforelse
                                        <a href="{{ route('categories') }}" class="cat-menu-all">All Stores</a>
                                    </div>
                                    @foreach($navCategoryMenu['categories'] ?? [] as $category)
                                        <div class="cat-menu-store-panel" data-cat-panel="cat-{{ $category->id }}">
                                            @forelse($category->stores as $store)
                                                <a href="{{ route('store', $store->seo_url) }}">{{ $store->short_name }}</a>
                                            @empty
                                                <p class="cat-menu-empty">No stores available</p>
                                            @endforelse
                                            <a href="{{ route('categories') }}" class="cat-menu-all">All Stores</a>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    <!-- <a href="{{ route('about-us') }}" class="nav-link {{ request()->routeIs('about-us') ? 'active' : '' }}">About Us</a> -->
                    <a href="{{ route('blog') }}" class="nav-link {{ request()->routeIs('blog') ? 'active' : '' }}">Blog</a>
                    <a href="{{ route('events') }}" class="nav-link {{ request()->routeIs('events') ? 'active' : '' }}">Events</a>
                </nav>

                <!-- Right: Contact & User Actions (Desktop) -->
                <div class="right-section">
                    <!-- <div class="hotline-info">
                        <svg class="phone-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>
                        </svg>
                        <div class="hotline-text">
                            <div class="hotline-number">Hotline: 196475</div>
                            <div class="hotline-subtext">Call us for free</div>
                        </div>
                    </div> -->
                    <button type="button" class="search-bar-button" aria-label="Search">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="11" cy="11" r="8"/>
                                <path d="m21 21-4.35-4.35"/>
                            </svg>
                        </button>
                    @if(Auth::guard('customer')->check())
                        <a href="{{ route('customer.dashboard') ?? '#' }}" class="user-icon login-icon" title="Account">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                                <circle cx="12" cy="7" r="4"/>
                            </svg>
                        </a>
                    @else
                        <a href="{{ route('customer.login') }}" title="Login" style="background: var(--primary-color); color: #fff; padding: 10px 20px; border-radius: 5px;">
                            Login
                        </a>
                    @endif
                    <a href="{{ route('customer.register') }}" title="Register" style="background: var(--primary-color); color: #fff; padding: 10px 20px; border-radius: 5px;">
                        Register
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Navigation Bar -->
    <!-- <div class="nav-bar">
        <div class="nav-wrapper">
            <div class="container nav-container">

        </div>
        </div>
    </div> -->

</header>
<!-- header <component:end> -->

  <!-- Search Modal -->
  <div id="searchModal" class="search-modal" style="display: none;">
      <div class="search-modal-overlay"></div>
      <div class="search-modal-content">
          <div class="search-modal-header">
              <label for="modalSearchInput" class="sr-only" style="position: absolute; width: 1px; height: 1px; padding: 0; margin: -1px; overflow: hidden; clip: rect(0, 0, 0, 0); white-space: nowrap; border-width: 0;">Search for stores, brands, or products</label>
              <input type="text" placeholder="Search" class="search-modal-input" id="modalSearchInput" autocomplete="off" aria-label="Search for stores, brands, or products">
              <button class="search-modal-close" id="closeSearchModal" type="button" aria-label="Close search modal">×</button>
          </div>
          <div class="search-modal-body">
              <div class="search-sections">
                  <div class="search-section-left">
                      <span>TRENDING OFFERS</span>
                      <div id="trendingOffers" class="offers-list">
                          <!-- Trending offers will be loaded here -->
                      </div>
                  </div>
                  <div class="search-section-right">
                      <span>BRANDS</span>
                      <div id="brandsList" class="brands-list">
                          <!-- Brands will be loaded here -->
                      </div>
                  </div>
              </div>
          </div>
      </div>
  </div>

  <style>.sidenv{position:fixed!important;top:0!important;left:-100%!important;width:0!important;height:0!important;overflow:hidden!important;opacity:0!important;visibility:hidden!important;z-index:-1!important;transition:none!important}
    /* ========================================
       MODERN HEADER STYLES - MATCHING IMAGE
       ======================================== */

    /* CSS Variables for Primary Color */
    :root {
        --primary-color: var(--primary-color);
    }

    .header-modern {
        background: #ffffff;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        position: sticky;
        top: 0;
        z-index: 1000;
    }

    .container {
        max-width: 1280px;
        margin: 0 auto;
        padding: 0 20px;
    }

    /* Top Promotional Bar */
    .top-promo-bar {
        background: var(--primary-color);
        padding: 10px 0;
        width: 100%;
    }

    .promo-container {
        max-width: 1280px;
        margin: 0 auto;
        padding: 0 20px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
    }

    .promo-left {
        display: flex;
        align-items: center;
        gap: 8px;
        color: #fff;
        font-size: 0.875rem;
        font-weight: 500;
    }

    .promo-left svg {
        width: 16px;
        height: 16px;
        color: #fff;
        flex-shrink: 0;
    }

    .promo-center {
        flex: 1;
        text-align: center;
        color: #fff;
        font-size: 0.875rem;
        font-weight: 600;
    }

    .promo-right {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .promo-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 32px;
        height: 32px;
        border-radius: 4px;
        color: #fff;
        text-decoration: none;
        transition: all 0.3s ease;
    }

    .promo-icon:hover {
        background: rgba(55, 65, 81, 0.1);
        transform: translateY(-1px);
    }

    .promo-icon svg {
        width: 16px;
        height: 16px;
    }

    /* Top Bar */
    .top-bar {
        background: #fff;
        border-bottom: 1px solid #e5e7eb;
        padding: 10px 0;
    }

    /* Hide Mobile Rows on Desktop */
    .mobile-top-row {
        display: none;
    }

    .top-bar-content {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 2rem;
    }

    /* Logo Section */
    .logo-section {
        flex-shrink: 0;
    }

    .site-logo-modern {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        text-decoration: none;
        color: #1e293b;
        font-weight: 700;
        font-size: 1.5rem;
        transition: all 0.3s ease;
    }

    /* .site-logo-modern:hover {
        opacity: 0.8;
    } */

    .logo-img {
        /* Maintain natural aspect ratio - fixes Lighthouse "incorrect aspect ratio" warning */
        /* Remove fixed dimensions, let natural aspect ratio determine display size */
        max-width: 200px;
        /* Don't set max-height to allow natural aspect ratio */
        height: auto !important;
        width: auto !important;
        /* Ensure aspect ratio is maintained from natural dimensions (960x133 = 7.22 ratio) */
        object-fit: contain;
        /* Prevent any distortion */
        image-rendering: -webkit-optimize-contrast;
        image-rendering: crisp-edges;
        /* Force browser to respect width/height attributes for aspect ratio */
        aspect-ratio: attr(width) / attr(height);
    }

    /* Fallback: Calculate aspect ratio from natural dimensions if aspect-ratio not supported */
    @supports not (aspect-ratio: attr(width) / attr(height)) {
        .logo-img {
            /* For 960x133 image: maintain 7.22 ratio */
            /* If max-width is 200px, height should be 200/7.22 = ~27.7px, but we let it scale naturally */
            max-height: none;
        }
    }

    .logo-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .logo-text {
        color: #fff;
        font-weight: 700;
        font-size: 1.5rem;
    }

    /* Search Section */
    .search-section {
        flex: 1;
        max-width: 600px;
        margin: 0 auto;
    }

    .search-bar-wrapper {
        display: flex;
        align-items: center;
        background: #f3f4f6;
        border: none;
        border-radius: 50px;
        overflow: hidden;
        transition: all 0.3s ease;
        cursor: pointer;
        padding: 0;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    }

    .search-bar-wrapper:hover {
        background: #e5e7eb;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .search-bar-input {
        flex: 1;
        border: none;
        padding: 14px 20px;
        font-size: 0.9375rem;
        /* Darker text color for better contrast against light background */
        color: #111827;
        font-weight: 500;
        outline: none;
        background: transparent;
        cursor: pointer;
        pointer-events: none;
    }

    .search-bar-input::placeholder {
        /* WCAG AA compliant: darker placeholder text for higher contrast */
        color: #374151;
        opacity: 1;
    }

    .search-bar-button {
        background:var(--primary-color);
        color: #ffffff;
        border: none;
        width: 40px;
        height: 40px;
        border-radius: 5px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
        flex-shrink: 0;
        margin: 4px;
    }

    .search-bar-button:hover {
        background: var(--primary-hover, #cc0000);
        transform: scale(1.05);
    }

    .search-bar-button svg {
        width: 20px;
        height: 20px;
        stroke: currentColor;
    }

    /* Right Section */
    .right-section {
        display: flex;
        align-items: center;
        gap: 1.5rem;
        flex-shrink: 0;
    }

    .hotline-info {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        display: none;
    }

    .phone-icon {
        color: #fff;
        flex-shrink: 0;
    }

    .hotline-text {
        display: flex;
        flex-direction: column;
        gap: 0.125rem;
    }

    .hotline-number {
        font-size: 0.875rem;
        font-weight: 700;
        color: #fff;
        line-height: 1.2;
    }

    .hotline-subtext {
        font-size: 0.75rem;
        color: #fff;
        line-height: 1.2;
    }

    .user-icon,
    .login-icon,
    .register-icon {
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        text-decoration: none;
        border-radius: 6px;
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .user-icon:hover,
    .login-icon:hover,
    .register-icon:hover {
        background: #f3f4f6;
        color: #1e293b;
    }

    /* Navigation Bar */
    .nav-bar {
        background: #fff;
        border-top: 1px solid #e5e7eb;
        position: relative;
        z-index: 1000;
    }

    .nav-wrapper {
        position: relative;
        width: 100%;
        overflow: visible;
        contain: layout style;
    }

    .nav-container {
        position: relative;
        width: 100%;
        max-width: 1280px;
        margin: 0 auto;
        padding: 0 20px;
        overflow: visible;
    }

    .main-nav {
        display: flex;
        align-items: center;
        gap: 2rem;
    }

    .nav-link {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 15px 0;
        color: #000;
        text-decoration: none;
        font-size: 0.9375rem;
        font-weight: 600;
        transition: all 0.3s ease;
        position: relative;
        border-bottom: 2px solid transparent;
    }

    .nav-link:hover {
        /* Keep text white for maximum contrast on black nav background */
        color: var(--primary-color);
        border-bottom-color: var(--primary-color);
    }

    .nav-link.active {
        /* Active link: white text (21:1 contrast on black) with primary-colored underline */
        color: var(--primary-color);
        border-bottom-color: var(--primary-color);
    }

    /* Ensure nav dropdown stays open when hovering */
    .nav-dropdown .nav-link:hover + .dropdown-menu,
    .nav-dropdown:hover .dropdown-menu {
        opacity: 1;
        visibility: visible;
        transform: translateY(0);
    }

    .dropdown-icon {
        width: 10px;
        height: 10px;
        transition: transform 0.3s ease;
    }

    .nav-dropdown {
        position: relative;
    }

    .nav-dropdown:hover .dropdown-menu,
    .nav-dropdown .dropdown-menu:hover {
        opacity: 1;
        visibility: visible;
        transform: translateY(0);
        display: block;
    }

    .nav-dropdown:hover .dropdown-icon {
        transform: rotate(180deg);
    }

    .dropdown-menu {
        position: absolute;
        top: 100%;
        left: 0;
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        min-width: 200px;
        padding: 0.5rem 0;
        opacity: 0;
        visibility: hidden;
        transform: translateY(-10px);
        transition: all 0.3s ease;
        z-index: 1000;
        margin-top: 0;
        display: block;
        pointer-events: auto;
    }

    .dropdown-menu a {
        display: block;
        padding: 0.75rem 1.25rem;
        color: #fff;
        text-decoration: none;
        font-size: 0.875rem;
        transition: all 0.2s ease;
        position: relative;
    }

    .dropdown-menu a:hover {
        background: rgba(255, 0, 0, 0.05);
        color: var(--primary-color);
    }

    /* Ensure dropdown stays open when hovering over it */
    .nav-dropdown:hover .dropdown-menu,
    .dropdown-menu:hover {
        opacity: 1 !important;
        visibility: visible !important;
        transform: translateY(0) !important;
    }

    /* Mega Menu - Full Width */
    .mega-dropdown {
        position: static;
    }

    .mega-dropdown .mega-menu {
        position: absolute;
        left: 50%;
        transform: translateX(-50%) translateY(-10px);
        width: 100vw;
        max-width: none;
        padding: 2rem calc((100vw - 1280px) / 2 + 20px);
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
        border-radius: 0;
        border-top: 1px solid #e5e7eb;
        margin: 0;
        z-index: 999;
        top: 100%;
        margin-top: 0;
    }

    @media (max-width: 1320px) {
        .mega-dropdown .mega-menu {
            padding: 2rem 20px;
        }
    }

    .mega-dropdown:hover .mega-menu,
    .mega-dropdown .mega-menu:hover {
        opacity: 1 !important;
        visibility: visible !important;
        transform: translateX(-50%) translateY(0) !important;
    }

    .mega-menu-content {
        display: grid;
        grid-template-columns: repeat(5, 1fr);
        gap: 2rem;
        max-width: 100%;
        contain: layout style;
    }

    @media (max-width: 1200px) {
        .mega-menu-content {
            grid-template-columns: repeat(4, 1fr);
        }
    }

    @media (max-width: 992px) {
        .mega-menu-content {
            grid-template-columns: repeat(3, 1fr);
        }
    }

    .mega-menu-title {
        display: block;
        margin: 0 0 0.75rem 0;
        font-size: 0.9375rem;
        font-weight: 700;
        color: #fff;
        text-decoration: none;
        padding: 0;
    }

    .mega-menu-title:hover {
        color: var(--primary-color);
        background: transparent;
    }

    .mega-menu-column {
        contain: layout style;
    }

    .mega-menu-column a {
        display: block;
        padding: 0.5rem 0;
        color: #000;
        text-decoration: none;
        font-size: 0.875rem;
        transition: color 0.2s ease;
    }

    .mega-menu-column a:hover {
        color: var(--primary-color);
        background: transparent;
    }

    .mega-menu-column a.more-link {
        color: var(--primary-color);
        font-weight: 600;
        margin-top: 0.5rem;
    }

    .mega-menu-column a.more-link:hover {
        color: var(--primary-color);
        background: rgba(255, 0, 0, 0.05);
        padding-left: 0.5rem;
    }

    /* Nav Utils */
    .nav-utils {
        display: flex;
        align-items: center;
        gap: 1.5rem;
    }

    .util-link {
        color: #fff;
        text-decoration: none;
        font-size: 0.9375rem;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .util-link:hover {
        color: #1e293b;
    }

    /* Mobile Menu Button */
    .mobile-menu-btn {
        display: none;
    }

    /* Mobile Search Button - Hidden on Desktop */
    .mobile-search-btn {
        display: none;
    }

    /* Mobile Responsive - Tablet */
    @media (max-width: 1024px) {
        .search-section {
            max-width: 400px;
        }

        .hotline-info {
            display: none;
        }

        .nav-utils {
            display: none;
        }

        .container {
            padding: 0 15px;
        }
    }

    /* Mobile Responsive - Mobile */
    @media (max-width: 768px) {
        .header-modern {
            position: sticky;
            top: 0;
            z-index: 1000;
            background: #ffffff;
        }

        .top-bar {
            padding: 0;
            background: #fff;
            border-bottom: 1px solid #e5e7eb;
        }

        /* Top Promotional Bar - Mobile */
        .top-promo-bar {
            padding: 8px 0;
            display: none;
        }

        .promo-container {
            padding: 0 15px;
            flex-wrap: wrap;
            gap: 8px;
        }

        .promo-left {
            font-size: 0.75rem;
            gap: 6px;
        }

        .promo-left svg {
            width: 14px;
            height: 14px;
        }

        .promo-center {
            /* order: 3; */
            width: 100%;
            text-align: center;
            font-size: 0.75rem;
            padding-top: 4px;
        }

        .promo-right {
            gap: 8px;
        }

        .promo-icon {
            width: 28px;
            height: 28px;
        }

        .promo-icon svg {
            width: 14px;
            height: 14px;
        }

        /* Mobile Top Row - Hamburger + Logo + Search */
        .mobile-top-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 12px 15px;
            border-bottom: 1px solid #e5e7eb;
            gap: 12px;
        }

        /* Hamburger Menu Button - Left */
        .mobile-menu-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 44px;
            height: 44px;
            padding: 10px;
            background: transparent;
            border: none;
            cursor: pointer;
            color: #000;
            flex-shrink: 0;
            order: 1;
            background: var(--primary-color);
            border-radius: 6px;
        }

        .mobile-menu-btn svg {
            width: 24px;
            height: 24px;
            stroke: #fff;
            stroke-width: 2.5;
        }

        .mobile-menu-btn:hover {
            background: #f3f4f6;
            border-radius: 6px;
        }

        .mobile-logo-section {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            order: 2;
            padding: 0 10px;
            min-width: 0;
            overflow: hidden;
        }

        .mobile-logo-section .logo-text {
            font-size: 1.125rem;
            font-weight: 700;
            color: #000;
            text-align: center;
        }

        /* Mobile Search Button - Right */
        .mobile-search-btn {
            display: flex !important;
            align-items: center;
            justify-content: center;
            width: 44px;
            height: 44px;
            background: var(--primary-color, #2951c4) !important;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            color: #ffffff !important;
            transition: all 0.3s ease;
            flex-shrink: 0;
            padding: 0;
            order: 3;
            visibility: visible !important;
            opacity: 1 !important;
            z-index: 10;
        }

        .mobile-search-btn svg {
            width: 20px;
            height: 20px;
            stroke: #ffffff !important;
            stroke-width: 2;
            fill: none;
            display: block;
            visibility: visible !important;
            opacity: 1 !important;
        }

        .mobile-search-btn:hover {
            background: var(--primary-hover, #cc0000);
            transform: scale(1.05);
        }

        .mobile-search-btn:active {
            transform: scale(0.95);
        }

        /* Show Mobile Top Row */
        .mobile-top-row {
            display: flex;
        }

        /* Hide Desktop Elements */
        .top-bar-content {
            display: none;
        }

        .search-section {
            display: none;
        }

        .right-section {
            display: none;
        }

        .hotline-info {
            display: none;
        }

        .user-icon,
        .login-icon,
        .register-icon {
            display: none;
        }

        .nav-bar {
            display: none;
        }
    }

    /* Mobile Responsive - Small Mobile */
    @media (max-width: 480px) {
        .mobile-top-row {
            padding: 10px 8px;
            gap: 8px;
            display: flex !important;
            align-items: center;
            justify-content: space-between;
            width: 100%;
            box-sizing: border-box;
        }

        .mobile-logo-section {
            flex: 1;
            padding: 0 5px;
            min-width: 0;
            overflow: hidden;
            display: flex !important;
            justify-content: center;
            align-items: center;
        }

        .mobile-logo-section .logo-text {
            font-size: 0.9rem;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .mobile-logo-section .logo-img {
            max-width: 120px;
            height: auto;
        }

        .mobile-menu-btn {
            width: 38px;
            height: 38px;
            padding: 8px;
            flex-shrink: 0;
            display: flex !important;
            align-items: center;
            justify-content: center;
        }

        .mobile-menu-btn svg {
            width: 20px;
            height: 20px;
        }

        .mobile-search-btn {
            width: 38px;
            height: 38px;
            display: flex !important;
            visibility: visible !important;
            opacity: 1 !important;
            flex-shrink: 0;
            align-items: center;
            justify-content: center;
            background: var(--primary-color, #2951c4) !important;
        }

        .mobile-search-btn svg {
            width: 18px;
            height: 18px;
            stroke: #ffffff !important;
            visibility: visible !important;
            opacity: 1 !important;
            display: block !important;
        }

        .sidenv {
            width: 70%;
            max-width: 260px;
            min-width: 220px;
        }
    }

    /* Side Navigation Mobile Styles - AdSense Compliant */
    @media (max-width: 768px) {
        .sidenv {
            position: fixed !important;
            top: 0 !important;
            left: -100% !important;
            width: 66.67% !important;
            max-width: 280px !important;
            min-width: 240px !important;
            height: 100vh !important;
            height: 100dvh !important; /* Dynamic viewport height for mobile */
            background: #ffffff !important;
            box-shadow: 2px 0 12px rgba(0, 0, 0, 0.15) !important;
            z-index: 10002 !important;
            transition: left 0.3s cubic-bezier(0.4, 0, 0.2, 1), opacity 0.3s ease, visibility 0.3s ease !important;
            overflow-y: auto !important;
            overflow-x: hidden !important;
            padding: 0 !important;
            -webkit-overflow-scrolling: touch !important;
            /* Ensure menu is clear and not blurred */
            backdrop-filter: none !important;
            -webkit-backdrop-filter: none !important;
            filter: none !important;
            opacity: 1 !important;
            visibility: visible !important;
        }

        .sidenv.active {
            left: 0 !important;
            opacity: 1 !important;
            visibility: visible !important;
            /* Red divider line on the right */
            border-right: 3px solid var(--primary-color) !important;
        }

        /* Main content visible on right when menu is open */
        body.menu-open main {
            margin-left: 0;
            transition: margin-left 0.3s ease;
        }

        .sidenv .snhead {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 20px 15px 20px;
            margin-bottom: 0;
            border-bottom: 2px solid #e5e7eb;
            font-size: 1rem;
            font-weight: 700;
            color: #000;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            background: #ffffff;
            position: sticky;
            top: 0;
            z-index: 10;
            /* Ensure text is clear */
            filter: none !important;
            opacity: 1 !important;
        }

        .sidenv .snx {
            cursor: pointer;
            padding: 8px;
            border-radius: 6px;
            transition: all 0.3s ease;
            min-width: 36px;
            min-height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: transparent;
            border: none;
            color: inherit;
            font-size: inherit;
            font-family: inherit;
            outline: none;
            -webkit-tap-highlight-color: transparent;
        }

        .sidenv .snx:hover {
            background: #f3f4f6;
        }

        .sidenv .snx:active,
        .sidenv .snx:focus {
            background: #e5e7eb;
            outline: none;
        }

        .sidenv > a {
            display: block;
            padding: 14px 20px;
            color: #000;
            text-decoration: none;
            font-size: 0.9375rem;
            font-weight: 500;
            border-bottom: 1px solid #f3f4f6;
            transition: all 0.3s ease;
            min-height: 44px;
            display: flex;
            align-items: center;
            background: #ffffff;
            /* Ensure text is clear */
            filter: none !important;
            opacity: 1 !important;
        }

        .sidenv > a:hover,
        .sidenv > a:focus {
            color: var(--primary-color);
            padding-left: 25px;
            background: #f8f9fa;
        }

        .sidenv .user-menu {
            padding: 15px 20px;
            border-bottom: 2px solid #e5e7eb;
            margin-bottom: 0;
            background: #ffffff;
        }

        .sidenv .user-menu span {
            display: block;
            margin-bottom: 10px;
            color: #64748b;
            font-size: 0.9rem;
            line-height: 1.5;
        }

        .sidenv .logout-btn {
            background: var(--primary-color);
            color: #ffffff;
            border: none;
            padding: 10px 18px;
            border-radius: 8px;
            font-size: 0.875rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            min-height: 44px;
            width: 100%;
        }

        .sidenv .logout-btn:hover {
            background: var(--primary-hover, #cc0000);
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(255, 0, 0, 0.2);
        }

        .sidenv .lgnbtn {
            display: block;
            text-align: center;
            padding: 14px 20px;
            margin: 0;
            background: #ffffff;
            color: #000;
            border-bottom: 1px solid #f3f4f6;
            font-weight: 500;
            transition: all 0.3s ease;
            min-height: 44px;
            display: flex;
            align-items: center;
            justify-content: flex-start;
            text-decoration: none;
            font-size: 0.9375rem;
        }

        .sidenv .lgnbtn:hover {
            background: #f8f9fa;
            color: #000;
            transform: none;
            box-shadow: none;
        }

        .sidenv .scl {
            margin-top: 0;
            padding: 20px;
            border-top: 2px solid #e5e7eb;
            display: flex;
            gap: 10px;
            justify-content: center;
            flex-wrap: wrap;
            background: #ffffff;
        }

        .sidenv .scl a {
            border: none;
            padding: 0;
            width: 44px;
            height: 44px;
            min-width: 44px;
            min-height: 44px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            background: #f3f4f6;
            transition: all 0.3s ease;
        }

        .sidenv .scl a:hover {
            background: var(--primary-color);
            transform: translateY(-2px);
            padding-left: 0;
            box-shadow: 0 4px 8px rgba(255, 0, 0, 0.2);
        }

        /* Main Content Fade When Menu Open */
        body.menu-open {
            overflow: hidden;
            position: fixed;
            width: 100%;
        }

        /* Overlay for blurred background - only covers right side (main content) */
        body.menu-open::after {
            content: '';
            position: fixed;
            top: 0;
            right: 0;
            width: 33.33%;
            min-width: calc(100% - 280px);
            height: 100%;
            height: 100dvh;
            background: rgba(0, 0, 0, 0.2);
            z-index: 10001;
            backdrop-filter: blur(6px);
            -webkit-backdrop-filter: blur(6px);
            pointer-events: none;
        }

        /* Fade and blur main content when menu is open - keep menu clear */
        /* body.menu-open .header-modern ~ *,
        body.menu-open main {
            filter: blur(6px);
            opacity: 0.4;
            transition: filter 0.3s ease, opacity 0.3s ease;
            pointer-events: none;
            position: relative;
            z-index: 1;
        } */

        /* Keep header visible but allow menu to be clear */
        body.menu-open .header-modern {
            z-index: 10001;
            filter: none;
            opacity: 1;
        }

        /* Keep menu completely clear and visible */
        body.menu-open .sidenv,
        body.menu-open .sidenv * {
            filter: none !important;
            opacity: 1 !important;
            pointer-events: auto !important;
            z-index: 10002 !important;
            background: #000 !important;
            color: #fff !important;
            border-bottom: solid 1px #fff !important;
        }
    }

    @media (max-width: 480px) {
        .sidenv {
            width: 75%;
            max-width: 280px;
            min-width: 220px;
        }

        .sidenv .snhead {
            font-size: 0.9375rem;
            padding: 18px 15px 12px 15px;
        }

        .sidenv > a {
            font-size: 0.875rem;
            padding: 12px 15px;
        }

        .sidenv .lgnbtn {
            padding: 12px 15px;
            font-size: 0.875rem;
        }

        .sidenv .user-menu {
            padding: 12px 15px;
        }

        .sidenv .scl {
            padding: 15px;
        }
    }

    /* Header Search Icon Styles */
    .header-search-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 40px;
        height: 40px;
        color: #333333;
        text-decoration: none;
        cursor: pointer;
        transition: all 0.3s ease;
        border-radius: 50%;
        border: 2px solid transparent;
        background: transparent;
        margin-right: 1rem;
    }

    .header-search-icon:hover {
        background: #f3f4f6;
        border-color: #e5e7eb;
        transform: scale(1.05);
    }

    .header-search-icon:active {
        transform: scale(0.95);
    }

    .header-search-icon svg {
        width: 22px;
        height: 22px;
        stroke: currentColor;
    }

    /* Mobile Responsive */
    @media (max-width: 768px) {
        .header-search-icon {
            width: 36px;
            height: 36px;
            margin-right: 0.5rem;
        }

        .header-search-icon svg {
            width: 20px;
            height: 20px;
        }
    }

    /* Search Modal Styles */
    .search-modal {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: 9999;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 10px;
        box-sizing: border-box;
    }

    .search-modal-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        backdrop-filter: blur(5px);
    }

    .search-modal-content {
        position: relative;
        background: white;
        border-radius: 15px;
        width: 90%;
        max-width: 800px;
        max-height: 80vh;
        overflow: hidden;
        box-shadow: 0 25px 50px rgba(0, 0, 0, 0.3);
        animation: modalSlideIn 0.3s ease-out;
        display: flex;
        flex-direction: column;
    }

    @keyframes modalSlideIn {
        from {
            opacity: 0;
            transform: translateY(-50px) scale(0.9);
        }
        to {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }

    .search-modal-header {
        display: flex;
        align-items: center;
        padding: 20px 25px;
        border-bottom: 1px solid #e0e0e0;
        background: #f8f9fa;
    }

    .search-modal-input {
        flex: 1;
        border: none;
        outline: none;
        font-size: 18px;
        padding: 10px 0;
        background: transparent;
        color: #333;
    }

    .search-modal-input::placeholder {
        color: #999;
    }

    .search-modal-close {
        background: none;
        border: none;
        font-size: 24px;
        color: #666;
        cursor: pointer;
        padding: 5px;
        margin-left: 15px;
        transition: color 0.3s ease;
    }

    .search-modal-close:hover {
        color: #333;
    }

    .search-modal-body {
        padding: 25px;
        max-height: 60vh;
        overflow-y: auto;
        flex: 1;
        -webkit-overflow-scrolling: touch;
    }

    .search-sections {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 40px;
        padding: 0 20px;
    }

    .search-section-left,
    .search-section-right {
        min-height: 200px;
    }

    .search-section-left h3,
    .search-section-right h3 {
        margin: 0 0 25px 0;
        font-size: 18px;
        font-weight: 700;
        color: #2c3e50;
        text-transform: uppercase;
        letter-spacing: 2px;
        border-bottom: 2px solid #e9ecef;
        padding-bottom: 10px;
    }

    .offers-list,
    .brands-list {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .offer-item {
        display: flex;
        align-items: center;
        padding: 15px;
        border-radius: 12px;
        background: #f8f9fa;
        border: 1px solid #e9ecef;
        transition: all 0.3s ease;
        cursor: pointer;
        text-decoration: none;
        color: inherit;
        margin-bottom: 0;
        box-sizing: border-box;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    }

    .offer-item:hover {
        background: #ffffff;
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        border-color: #dee2e6;
    }

    .offer-logo {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        object-fit: cover;
        margin-right: 15px;
        flex-shrink: 0;
        border: 2px solid #e9ecef;
    }

    .offer-logo-placeholder {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 12px;
        margin-right: 15px;
        flex-shrink: 0;
        border: 2px solid #e9ecef;
    }

    .offer-content {
        flex: 1;
    }

    .offer-brand {
        font-weight: 700;
        color: #2c3e50;
        margin: 0 0 6px 0;
        font-size: 16px;
        line-height: 1.2;
    }

    .offer-description {
        color: #6c757d;
        font-size: 13px;
        margin: 0 0 8px 0;
        line-height: 1.4;
        font-weight: 500;
    }

    .offer-button {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        cursor: pointer;
        transition: all 0.3s ease;
        letter-spacing: 0.5px;
    }

    .offer-button:hover {
        background: linear-gradient(135deg, #5a6fd8 0%, #6a4190 100%);
        transform: scale(1.05);
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
    }

    .brand-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 15px 0;
        border-bottom: 1px solid #e9ecef;
        cursor: pointer;
        text-decoration: none;
        color: inherit;
        transition: all 0.3s ease;
        box-sizing: border-box;
    }

    .brand-item:hover {
        background: #f8f9fa;
        padding-left: 15px;
        border-radius: 8px;
        margin: 0 -15px;
        padding-right: 15px;
    }

    .brand-item:last-child {
        border-bottom: none;
    }

    .brand-logo {
        width: 35px;
        height: 35px;
        border-radius: 50%;
        object-fit: cover;
        margin-right: 12px;
        flex-shrink: 0;
        border: 2px solid #e9ecef;
    }

    .brand-logo-placeholder {
        width: 35px;
        height: 35px;
        border-radius: 50%;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 10px;
        margin-right: 12px;
        flex-shrink: 0;
        border: 2px solid #e9ecef;
    }

    .brand-content {
        display: flex;
        align-items: center;
        flex: 1;
    }

    .brand-name {
        font-weight: 600;
        color: #2c3e50;
        font-size: 15px;
        margin: 0;
    }

    .brand-offers {
        color: #6c757d;
        font-size: 12px;
        font-weight: 500;
        margin-left: auto;
    }

    .loading-state {
        text-align: center;
        padding: 30px;
        color: #7f8c8d;
        font-size: 16px;
    }

    .loading-spinner {
        display: inline-block;
        width: 20px;
        height: 20px;
        border: 2px solid #3498db;
        border-radius: 50%;
        border-top-color: transparent;
        animation: spin 1s linear infinite;
        margin-right: 10px;
    }
    .container {
    max-width: 1280px;
    margin: 0 auto;
}

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    @media (max-width: 768px) {
        .search-modal-content {
            width: 95%;
            margin: 10px;
            max-height: 90vh;
            border-radius: 10px;
        }

        .search-sections {
            grid-template-columns: 1fr;
            gap: 20px;
            padding: 0 10px;
        }

        .search-modal-header {
            padding: 15px 20px;
        }

        .search-modal-input {
            font-size: 16px;
            padding: 8px 0;
        }

        .search-modal-body {
            padding: 15px;
            max-height: 70vh;
            overflow-y: auto;
        }

        .search-section-left,
        .search-section-right {
            min-height: auto;
        }

        .search-section-left h3,
        .search-section-right h3 {
            font-size: 14px;
            margin: 0 0 15px 0;
            letter-spacing: 1px;
        }

        .offer-item {
            padding: 10px;
            margin-bottom: 8px;
            border-radius: 8px;
            background: #f8f9fa;
            display: flex;
            align-items: center;
            width: 100%;
            box-sizing: border-box;
        }

        .offer-item:hover {
            background: #ffffff;
            transform: none;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .offer-logo,
        .offer-logo-placeholder {
            width: 30px;
            height: 30px;
            margin-right: 10px;
            margin-bottom: 0;
            flex-shrink: 0;
            font-size: 9px;
        }

        .offer-content {
            flex: 1;
            margin-left: 0;
        }

        .offer-brand {
            font-size: 12px;
            margin-bottom: 3px;
            font-weight: 700;
            color: #2c3e50;
        }

        .offer-description {
            font-size: 10px;
            margin-bottom: 4px;
            color: #6c757d;
            line-height: 1.3;
        }

        .offer-button {
            font-size: 8px;
            padding: 3px 8px;
            border-radius: 12px;
        }

        .brand-item {
            padding: 10px 0;
            border-bottom: 1px solid #e9ecef;
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 100%;
        }

        .brand-item:hover {
            background: #f8f9fa;
            padding-left: 10px;
            border-radius: 6px;
            margin: 0 -10px;
            padding-right: 10px;
        }

        .brand-logo,
        .brand-logo-placeholder {
            width: 25px;
            height: 25px;
            margin-right: 8px;
            font-size: 8px;
        }

        .brand-name {
            font-size: 11px;
            margin-bottom: 0;
            font-weight: 600;
        }

        .brand-offers {
            font-size: 9px;
        }

        .offers-list,
        .brands-list {
            gap: 8px;
        }

        .loading-state {
            padding: 15px;
            font-size: 12px;
        }
    .header .hsbtn {
        display: inline-flex !important;
        align-items: center;
        justify-content: center;
        width: 40px;
        height: 40px;
        background: var(--primary-color);
        color: white;
        border-radius: 5px;
        text-decoration: none;
    }
}

/* Compact Categories Menu */
.cat-menu {
    position: relative;
}

.cat-menu-trigger {
    background: none;
    border: none;
    border-bottom: 2px solid transparent;
    font: inherit;
    cursor: pointer;
    font-weight:600;
}

.cat-menu-trigger.is-open {
    color: var(--primary-color);
    border-bottom-color: var(--primary-color);
}

.cat-menu-trigger .dropdown-icon {
    transition: transform 0.25s ease;
}

.cat-menu-trigger[aria-expanded="true"] .dropdown-icon {
    transform: rotate(180deg);
}

.cat-menu-panel {
    position: absolute;
    top: 100%;
    left: 0;
    margin-top: 8px;
    width: 440px;
    max-width: calc(100vw - 40px);
    background: #fff;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    box-shadow: 0 20px 45px rgba(15, 23, 42, 0.15);
    z-index: 1000;
    display: flex;
    align-items: stretch;
    opacity: 0;
    visibility: hidden;
    transform: translateY(-8px);
    transition: opacity 0.2s ease, transform 0.2s ease, visibility 0.2s;
    padding: 14px;
}

.cat-menu-panel.is-open {
    opacity: 1;
    visibility: visible;
    transform: translateY(0);
}

.cat-menu-list {
    flex: 0 0 168px;
    max-height: 380px;
    overflow-y: auto;
    padding-right: 10px;
}

.cat-menu-item {
    display: block;
    position: relative;
    padding: 9px 16px 9px 10px;
    color: #1f2937;
    text-decoration: none;
    font-size: 0.875rem;
    font-weight: 500;
    line-height: 1.3;
    border-radius: 8px;
}

.cat-menu-item:hover,
.cat-menu-item:focus-visible {
    background: #f8f9fc;
    color: var(--primary-color);
}

.cat-menu-item.is-active {
    color: var(--primary-color);
    font-weight: 700;
}

.cat-menu-item.is-active::after {
    content: '';
    position: absolute;
    right: 2px;
    top: 50%;
    transform: translateY(-50%);
    width: 3px;
    height: 20px;
    background: var(--primary-color);
    border-radius: 2px;
}

.cat-menu-divider {
    flex: 0 0 1px;
    align-self: stretch;
    background: #e5e7eb;
    margin: 0 14px;
}

.cat-menu-stores {
    flex: 1 1 auto;
    min-width: 0;
    max-height: 380px;
    overflow-y: auto;
}

.cat-menu-store-panel {
    display: none;
    flex-direction: column;
}

.cat-menu-store-panel.is-active {
    display: flex;
}

.cat-menu-store-panel a {
    display: block;
    padding: 8px 10px;
    color: #374151;
    text-decoration: none;
    font-size: 0.875rem;
    border-radius: 8px;
}

.cat-menu-store-panel a:hover,
.cat-menu-store-panel a:focus-visible {
    background: #f8f9fc;
    color: var(--primary-color);
}

.cat-menu-empty {
    padding: 8px 10px;
    color: #9ca3af;
    font-size: 0.875rem;
    margin: 0;
}

.cat-menu-all {
    margin-top: 6px;
    padding-top: 10px !important;
    border-top: 1px solid #f1f5f9;
    color: var(--primary-color) !important;
    font-weight: 700 !important;
}

.cat-menu-trigger:focus-visible,
.cat-menu-item:focus-visible,
.cat-menu-store-panel a:focus-visible {
    outline: 2px solid var(--primary-color);
    outline-offset: 2px;
}

@media (max-width: 768px) {
    .cat-menu-panel {
        display: none !important;
    }
}

/* Trending: same compact popover component, single column (no category list/divider) */
.trend-menu-panel {
    display: block;
    width: 280px;
}

/* Mobile Categories Accordion (inside .sidenv) */
.sn-cat-trigger {
    display: flex !important;
    align-items: center;
    justify-content: space-between;
    width: 100%;
    padding: 14px 20px;
    color: #000;
    background: #ffffff;
    border: none;
    border-bottom: 1px solid #f3f4f6;
    font: inherit;
    font-size: 0.9375rem;
    font-weight: 500;
    text-align: left;
    cursor: pointer;
    min-height: 44px;
}

.sn-cat-trigger:hover,
.sn-cat-trigger:focus-visible {
    color: var(--primary-color);
    background: #f8f9fa;
}

.sn-cat-trigger .sn-cat-chevron {
    transition: transform 0.25s ease;
    flex-shrink: 0;
}

.sn-cat-trigger[aria-expanded="true"] .sn-cat-chevron {
    transform: rotate(180deg);
}

.sn-cat-list {
    display: none;
    flex-direction: column;
    max-height: 60vh;
    overflow-y: auto;
}

.sn-cat-list.is-open {
    display: flex;
}

.sn-cat-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    width: 100%;
    min-height: 44px;
    padding: 10px 20px 10px 32px;
    font-size: 0.9rem;
    font-family: inherit;
    color: #1f2937;
    background: #fbfbfc;
    border: none;
    border-bottom: 1px solid #f3f4f6;
    text-align: left;
    cursor: pointer;
}

.sn-cat-row:hover,
.sn-cat-row:focus-visible {
    color: var(--primary-color);
    background: #f8f9fa;
}

.sn-cat-view-all {
    display: block;
    min-height: 40px;
    display: flex;
    align-items: center;
    padding: 8px 20px 8px 32px !important;
    font-size: 0.85rem !important;
    font-weight: 700 !important;
    color: var(--primary-color) !important;
    background: #ffffff;
    border-bottom: 1px solid #f3f4f6;
}

.sn-store-panel {
    display: none;
    flex-direction: column;
    max-height: 60vh;
    overflow-y: auto;
}

.sn-store-panel.is-open {
    display: flex;
}

.sn-store-back {
    display: flex !important;
    align-items: center;
    gap: 8px;
    min-height: 44px;
    padding: 10px 20px !important;
    font-weight: 700 !important;
    background: #ffffff;
    border: none;
    border-bottom: 1px solid #f3f4f6;
    width: 100%;
    text-align: left;
    cursor: pointer;
    font-family: inherit;
    font-size: 0.9rem;
    color: var(--primary-color);
}

.sn-store-back:hover,
.sn-store-back:focus-visible {
    background: #f8f9fa;
}

.sn-store-panel > a {
    min-height: 44px;
    display: flex !important;
    align-items: center;
    padding: 10px 20px 10px 32px !important;
    font-size: 0.9rem !important;
    color: #1f2937;
    background: #ffffff;
    border-bottom: 1px solid #f3f4f6;
}

.sn-store-panel > a:hover,
.sn-store-panel > a:focus-visible {
    color: var(--primary-color);
    background: #f8f9fa;
}

.sn-store-empty {
    padding: 10px 20px 10px 32px;
    color: #9ca3af;
    font-size: 0.875rem;
    margin: 0;
    background: #ffffff;
}
  </style>

  <script>
  // Search Modal Functionality - Wait for jQuery to load
  function initSearchModal() {
      if (typeof jQuery === 'undefined') {
          setTimeout(initSearchModal, 100);
          return;
      }

      var $ = jQuery;

  $(document).ready(function() {
      // Simple search functionality
      $('#searchBox').click(function() {
          $('#searchModal').show();
          $('body').css('overflow', 'hidden');
          $('#modalSearchInput').focus();
          loadDefaultData();
      });

      $('#searchBtn').click(function() {
          $('#searchModal').show();
          $('body').css('overflow', 'hidden');
          $('#modalSearchInput').focus();
          loadDefaultData();
      });

      $('#closeSearchModal').click(function() {
          $('#searchModal').hide();
          $('body').css('overflow', 'auto');
          $('#modalSearchInput').val('');
      });

      // Close modal when clicking overlay
      $('#searchModal').click(function(e) {
          if (e.target === this) {
              $(this).hide();
              $('body').css('overflow', 'auto');
          }
      });

      // Load default data
      function loadDefaultData() {
          $('#trendingOffers').html('<div style="text-align: center; padding: 30px; color: #7f8c8d; font-size: 16px;"><div style="display: inline-block; width: 20px; height: 20px; border: 2px solid #3498db; border-radius: 50%; border-top-color: transparent; animation: spin 1s linear infinite; margin-right: 10px;"></div>Loading...</div>');
          $('#brandsList').html('<div style="text-align: center; padding: 30px; color: #7f8c8d; font-size: 16px;"><div style="display: inline-block; width: 20px; height: 20px; border: 2px solid #3498db; border-radius: 50%; border-top-color: transparent; animation: spin 1s linear infinite; margin-right: 10px;"></div>Loading...</div>');

          $.ajax({
              url: '{{ url("/getHeaderSearchDefault") }}',
              type: 'GET',
              dataType: 'json',
              success: function(data) {
                  renderDefaultData(data);
              },
              error: function(xhr, status, error) {
                  $('#trendingOffers').html('<div style="text-align: center; padding: 20px;">No trending offers available</div>');
                  $('#brandsList').html('<div style="text-align: center; padding: 20px;">No brands available</div>');
              }
          });
      }

      // Render default data
      function renderDefaultData(data) {
          var offersHtml = '';
          var brandsHtml = '';

          // Render offers
          if (data.coupons && data.coupons.length > 0) {
              $.each(data.coupons.slice(0, 5), function(index, coupon) {
                  var storeName = coupon.store ? coupon.store.store_name : coupon.brand_store || 'Store';
                  var storeUrl = coupon.store ? '{{ url("/store") }}/' + coupon.store.seo_url : '{{ url("/search") }}?q=' + encodeURIComponent(storeName);
                  var storeLogo = coupon.store ? coupon.store.store_logo : null;

                  offersHtml += '<div style="padding: 15px; border: 1px solid #e0e0e0; margin: 8px 0; border-radius: 8px; cursor: pointer; background: #fff; box-shadow: 0 2px 4px rgba(0,0,0,0.1); transition: all 0.3s ease; display: flex; align-items: center;" onmouseover="this.style.transform=\'translateY(-2px)\'; this.style.boxShadow=\'0 4px 8px rgba(0,0,0,0.15)\'" onmouseout="this.style.transform=\'translateY(0)\'; this.style.boxShadow=\'0 2px 4px rgba(0,0,0,0.1)\'" onclick="window.location.href=\'' + storeUrl + '\'">';

                  // Add store logo
                  if (storeLogo) {
                      var logoPath = '{{ asset("") }}' + storeLogo;
                      offersHtml += '<img src="' + logoPath + '" alt="' + storeName + '" style="width: 40px; height: 40px; margin-right: 12px; object-fit: contain; border-radius: 4px;">';
                  } else {
                      offersHtml += '<div style="width: 40px; height: 40px; margin-right: 12px; background: #f8f9fa; border-radius: 4px; display: flex; align-items: center; justify-content: center; font-weight: bold; color: #6c757d; font-size: 14px;">' + storeName.substring(0, 2).toUpperCase() + '</div>';
                  }

                  offersHtml += '<div style="flex: 1;">';
                  offersHtml += '<div style="font-weight: 600; color: #2c3e50; font-size: 16px; margin-bottom: 5px;">' + storeName + '</div>';
                  offersHtml += '<div style="color: #7f8c8d; font-size: 14px;">' + coupon.coupon_title + '</div>';
                  offersHtml += '</div>';
                  offersHtml += '</div>';
              });
          } else {
              offersHtml = '<div style="text-align: center; padding: 30px; color: #95a5a6; font-size: 16px;"><div style="font-size: 48px; margin-bottom: 15px;">📋</div>No trending offers available</div>';
          }

          // Render brands
          if (data.stores && data.stores.length > 0) {
              $.each(data.stores.slice(0, 5), function(index, store) {
                  brandsHtml += '<div style="padding: 15px; border: 1px solid #e0e0e0; margin: 8px 0; border-radius: 8px; cursor: pointer; background: #fff; box-shadow: 0 2px 4px rgba(0,0,0,0.1); transition: all 0.3s ease; display: flex; align-items: center;" onmouseover="this.style.transform=\'translateY(-2px)\'; this.style.boxShadow=\'0 4px 8px rgba(0,0,0,0.15)\'" onmouseout="this.style.transform=\'translateY(0)\'; this.style.boxShadow=\'0 2px 4px rgba(0,0,0,0.1)\'" onclick="window.location.href=\'{{ url("/store") }}/' + store.seo_url + '\'">';

                  // Add store logo
                  if (store.store_logo) {
                      var logoPath = '{{ asset("") }}' + store.store_logo;
                      brandsHtml += '<img src="' + logoPath + '" alt="' + store.store_name + '" style="width: 40px; height: 40px; margin-right: 12px; object-fit: contain; border-radius: 4px;">';
                  } else {
                      brandsHtml += '<div style="width: 40px; height: 40px; margin-right: 12px; background: #f8f9fa; border-radius: 4px; display: flex; align-items: center; justify-content: center; font-weight: bold; color: #6c757d; font-size: 14px;">' + store.store_name.substring(0, 2).toUpperCase() + '</div>';
                  }

                  brandsHtml += '<div style="flex: 1;">';
                  brandsHtml += '<div style="font-weight: 600; color: #2c3e50; font-size: 16px; margin-bottom: 5px;">' + store.store_name + '</div>';
                  brandsHtml += '<div style="color: #7f8c8d; font-size: 14px;">View offers</div>';
                  brandsHtml += '</div>';
                  brandsHtml += '</div>';
              });
          } else {
              brandsHtml = '<div style="text-align: center; padding: 30px; color: #95a5a6; font-size: 16px;"><div style="font-size: 48px; margin-bottom: 15px;">🏪</div>No brands available</div>';
          }

          $('#trendingOffers').html(offersHtml);
          $('#brandsList').html(brandsHtml);
      }

      // Search input handler
      var searchTimeout;
      $('#modalSearchInput').on('input', function() {
          var query = $(this).val().trim();

          clearTimeout(searchTimeout);

          if (query.length < 2) {
              loadDefaultData();
              return;
          }

          searchTimeout = setTimeout(function() {
              performSearch(query);
          }, 300);
      });

      // Perform search
      function performSearch(query) {
          $('#trendingOffers').html('<div style="text-align: center; padding: 30px; color: #7f8c8d; font-size: 16px;"><div style="display: inline-block; width: 20px; height: 20px; border: 2px solid #e74c3c; border-radius: 50%; border-top-color: transparent; animation: spin 1s linear infinite; margin-right: 10px;"></div>Searching...</div>');
          $('#brandsList').html('<div style="text-align: center; padding: 30px; color: #7f8c8d; font-size: 16px;"><div style="display: inline-block; width: 20px; height: 20px; border: 2px solid #e74c3c; border-radius: 50%; border-top-color: transparent; animation: spin 1s linear infinite; margin-right: 10px;"></div>Searching...</div>');

          $.ajax({
              url: '{{ url("/ajax-search") }}',
              type: 'GET',
              data: { q: query },
              dataType: 'json',
              success: function(data) {
                  renderSearchResults(data, query);
              },
              error: function(xhr, status, error) {
                  $('#trendingOffers').html('<div style="text-align: center; padding: 20px;">Search failed</div>');
                  $('#brandsList').html('<div style="text-align: center; padding: 20px;">Search failed</div>');
              }
          });
      }

      // Render search results
      function renderSearchResults(data, query) {
          var offersHtml = '';
          var brandsHtml = '';

          // Render offers from search results
          if (data.coupons && data.coupons.length > 0) {
              $.each(data.coupons.slice(0, 5), function(index, coupon) {
                  var storeName = coupon.store ? coupon.store.store_name : coupon.brand_store || 'Store';
                  var storeLogo = coupon.store ? coupon.store.store_logo : null;
                  var couponImage = coupon.cover_logo;
                  var storeUrl = coupon.store ? '{{ url("/store") }}/' + coupon.store.seo_url : '{{ url("/search") }}?q=' + encodeURIComponent(storeName);

                  offersHtml += '<div style="padding: 15px; border: 1px solid #e0e0e0; margin: 8px 0; border-radius: 8px; cursor: pointer; background: #fff; box-shadow: 0 2px 4px rgba(0,0,0,0.1); transition: all 0.3s ease; display: flex; align-items: center;" onmouseover="this.style.transform=\'translateY(-2px)\'; this.style.boxShadow=\'0 4px 8px rgba(0,0,0,0.15)\'" onmouseout="this.style.transform=\'translateY(0)\'; this.style.boxShadow=\'0 2px 4px rgba(0,0,0,0.1)\'" onclick="window.location.href=\'' + storeUrl + '\'">';

                  // Add images container
                  offersHtml += '<div style="display: flex; align-items: center; margin-right: 12px;">';

                  // Add store logo
                  if (storeLogo) {
                      var logoPath = '{{ asset("") }}' + storeLogo;
                      offersHtml += '<img src="' + logoPath + '" alt="' + storeName + '" style="width: 30px; height: 30px; object-fit: contain; border-radius: 4px; margin-right: 8px;">';
                  } else {
                      offersHtml += '<div style="width: 30px; height: 30px; background: #e9ecef; border-radius: 4px; display: flex; align-items: center; justify-content: center; font-weight: bold; color: #6c757d; font-size: 10px; margin-right: 8px;">' + storeName.substring(0, 2).toUpperCase() + '</div>';
                  }



                  offersHtml += '</div>';

                  offersHtml += '<div style="flex: 1;">';
                  offersHtml += '<div style="font-weight: 600; color: #2c3e50; font-size: 16px; margin-bottom: 5px;">' + storeName + '</div>';
                  offersHtml += '<div style="color: #7f8c8d; font-size: 14px;">' + coupon.coupon_title + '</div>';
                  offersHtml += '</div>';
                  offersHtml += '</div>';
              });
          } else {
              offersHtml = '<div style="text-align: center; padding: 30px; color: #95a5a6; font-size: 16px;"><div style="font-size: 48px; margin-bottom: 15px;">🔍</div>No offers found for "' + query + '"</div>';
          }

          // Render brands from search results
          if (data.stores && data.stores.length > 0) {
              $.each(data.stores.slice(0, 5), function(index, store) {
                  brandsHtml += '<div style="padding: 15px; border: 1px solid #e0e0e0; margin: 8px 0; border-radius: 8px; cursor: pointer; background: #fff; box-shadow: 0 2px 4px rgba(0,0,0,0.1); transition: all 0.3s ease; display: flex; align-items: center;" onmouseover="this.style.transform=\'translateY(-2px)\'; this.style.boxShadow=\'0 4px 8px rgba(0,0,0,0.15)\'" onmouseout="this.style.transform=\'translateY(0)\'; this.style.boxShadow=\'0 2px 4px rgba(0,0,0,0.1)\'" onclick="window.location.href=\'{{ url("/store") }}/' + store.seo_url + '\'">';

                  // Add store logo
                  if (store.store_logo) {
                      var logoPath = '{{ asset("") }}' + store.store_logo;
                      brandsHtml += '<img src="' + logoPath + '" alt="' + store.store_name + '" style="width: 40px; height: 40px; margin-right: 12px; object-fit: contain; border-radius: 4px;">';
                  } else {
                      brandsHtml += '<div style="width: 40px; height: 40px; margin-right: 12px; background: #f8f9fa; border-radius: 4px; display: flex; align-items: center; justify-content: center; font-weight: bold; color: #6c757d; font-size: 14px;">' + store.store_name.substring(0, 2).toUpperCase() + '</div>';
                  }

                  brandsHtml += '<div style="flex: 1;">';
                  brandsHtml += '<div style="font-weight: 600; color: #2c3e50; font-size: 16px; margin-bottom: 5px;">' + store.store_name + '</div>';
                  brandsHtml += '<div style="color: #7f8c8d; font-size: 14px;">View offers</div>';
                  brandsHtml += '</div>';
                  brandsHtml += '</div>';
              });
          } else {
              brandsHtml = '<div style="text-align: center; padding: 30px; color: #95a5a6; font-size: 16px;"><div style="font-size: 48px; margin-bottom: 15px;">🏪</div>No brands found for "' + query + '"</div>';
          }

          $('#trendingOffers').html(offersHtml);
          $('#brandsList').html(brandsHtml);
      }

      // Event listener for header search icon
      $('#headerSearchIcon').on('click', function(e) {
          e.preventDefault();
          e.stopPropagation();
          $('#searchModal').show();
          $('body').css('overflow', 'hidden');
          setTimeout(function() {
              $('#modalSearchInput').focus();
          }, 100);
          if (typeof loadDefaultData === 'function') {
              loadDefaultData();
          }
      });

      // Note: searchBarTrigger and searchBarButton are handled by vanilla JS above
      // These jQuery handlers are kept for compatibility but won't fire if vanilla JS already handled it
      // The vanilla JS handlers open the modal immediately, then jQuery loads in background

      // Event listeners for mobile search
      $('.hsbtn').on('click', function(e) {
          e.preventDefault();
          e.stopPropagation();
          e.stopImmediatePropagation();
          $('#searchModal').show();
          $('body').css('overflow', 'hidden');
          $('#modalSearchInput').focus();
          loadDefaultData();
      });

      // Mobile Search Button
      $('#mobileSearchBtn').on('click', function(e) {
          e.preventDefault();
          e.stopPropagation();
          $('#searchModal').show();
          $('body').css('overflow', 'hidden');
          setTimeout(function() {
              $('#modalSearchInput').focus();
          }, 100);
          if (typeof loadDefaultData === 'function') {
              loadDefaultData();
          }
      });

      // Handle escape key
      $(document).on('keydown', function(e) {
          if (e.key === 'Escape' && $('#searchModal').is(':visible')) {
              $('#searchModal').hide();
              $('body').css('overflow', 'auto');
          }
      });
  });
  }

  // Hide menu immediately on page load (before CSS loads)
  (function() {
      const sidenv = document.querySelector('.sidenv');
      if (sidenv) {
          sidenv.style.position = 'fixed';
          sidenv.style.top = '0';
          sidenv.style.left = '-100%';
          sidenv.style.width = '0';
          sidenv.style.height = '0';
          sidenv.style.overflow = 'hidden';
          sidenv.style.opacity = '0';
          sidenv.style.visibility = 'hidden';
          sidenv.style.zIndex = '-1';
          sidenv.style.transition = 'none';
      }
  })();

  // Initialize mobile menu immediately (no jQuery dependency)
  if (document.readyState === 'loading') {
      document.addEventListener('DOMContentLoaded', function() {
          initMobileMenu();
      });
  } else {
      initMobileMenu();
  }

  // Initialize search modal - Open modal immediately, load jQuery in background
  var searchModalInit = false;

  // Load default data using vanilla JS (works before jQuery loads)
  function loadDefaultDataVanilla() {
      var trendingOffers = document.getElementById('trendingOffers');
      var brandsList = document.getElementById('brandsList');

      if (trendingOffers) {
          trendingOffers.innerHTML = '<div style="text-align: center; padding: 30px; color: #7f8c8d; font-size: 16px;"><div style="display: inline-block; width: 20px; height: 20px; border: 2px solid #3498db; border-radius: 50%; border-top-color: transparent; animation: spin 1s linear infinite; margin-right: 10px;"></div>Loading...</div>';
      }
      if (brandsList) {
          brandsList.innerHTML = '<div style="text-align: center; padding: 30px; color: #7f8c8d; font-size: 16px;"><div style="display: inline-block; width: 20px; height: 20px; border: 2px solid #3498db; border-radius: 50%; border-top-color: transparent; animation: spin 1s linear infinite; margin-right: 10px;"></div>Loading...</div>';
      }

      // Fetch default data using vanilla JS
      fetch('{{ url("/getHeaderSearchDefault") }}')
          .then(function(response) {
              if (!response.ok) {
                  throw new Error('Network response was not ok');
              }
              return response.json();
          })
          .then(function(data) {
              renderDefaultDataVanilla(data);
          })
          .catch(function(error) {
              if (trendingOffers) {
                  trendingOffers.innerHTML = '<div style="text-align: center; padding: 20px;">No trending offers available</div>';
              }
              if (brandsList) {
                  brandsList.innerHTML = '<div style="text-align: center; padding: 20px;">No brands available</div>';
              }
          });
  }

  // Render default data using vanilla JS
  function renderDefaultDataVanilla(data) {
      var trendingOffers = document.getElementById('trendingOffers');
      var brandsList = document.getElementById('brandsList');
      var offersHtml = '';
      var brandsHtml = '';

      // Render offers
      if (data.coupons && data.coupons.length > 0) {
          data.coupons.slice(0, 5).forEach(function(coupon) {
              var storeName = coupon.store ? coupon.store.store_name : (coupon.brand_store || 'Store');
              var storeUrl = coupon.store ? '{{ url("/store") }}/' + coupon.store.seo_url : '{{ url("/search") }}?q=' + encodeURIComponent(storeName);
              var storeLogo = coupon.store ? coupon.store.store_logo : null;

              offersHtml += '<div style="padding: 15px; border: 1px solid #e0e0e0; margin: 8px 0; border-radius: 8px; cursor: pointer; background: #fff; box-shadow: 0 2px 4px rgba(0,0,0,0.1); transition: all 0.3s ease; display: flex; align-items: center;" onmouseover="this.style.transform=\'translateY(-2px)\'; this.style.boxShadow=\'0 4px 8px rgba(0,0,0,0.15)\'" onmouseout="this.style.transform=\'translateY(0)\'; this.style.boxShadow=\'0 2px 4px rgba(0,0,0,0.1)\'" onclick="window.location.href=\'' + storeUrl + '\'">';

              if (storeLogo) {
                  var logoPath = '{{ asset("") }}' + storeLogo;
                  offersHtml += '<img src="' + logoPath + '" alt="' + storeName + '" style="width: 40px; height: 40px; margin-right: 12px; object-fit: contain; border-radius: 4px;">';
              } else {
                  offersHtml += '<div style="width: 40px; height: 40px; margin-right: 12px; background: #f8f9fa; border-radius: 4px; display: flex; align-items: center; justify-content: center; font-weight: bold; color: #6c757d; font-size: 14px;">' + storeName.substring(0, 2).toUpperCase() + '</div>';
              }

              offersHtml += '<div style="flex: 1;"><div style="font-weight: 600; color: #2c3e50; font-size: 16px; margin-bottom: 5px;">' + storeName + '</div><div style="color: #7f8c8d; font-size: 14px;">' + (coupon.coupon_title || 'View offers') + '</div></div></div>';
          });
      } else {
          offersHtml = '<div style="text-align: center; padding: 30px; color: #95a5a6; font-size: 16px;"><div style="font-size: 48px; margin-bottom: 15px;">📋</div>No trending offers available</div>';
      }

      // Render brands
      if (data.stores && data.stores.length > 0) {
          data.stores.slice(0, 5).forEach(function(store) {
              brandsHtml += '<div style="padding: 15px; border: 1px solid #e0e0e0; margin: 8px 0; border-radius: 8px; cursor: pointer; background: #fff; box-shadow: 0 2px 4px rgba(0,0,0,0.1); transition: all 0.3s ease; display: flex; align-items: center;" onmouseover="this.style.transform=\'translateY(-2px)\'; this.style.boxShadow=\'0 4px 8px rgba(0,0,0,0.15)\'" onmouseout="this.style.transform=\'translateY(0)\'; this.style.boxShadow=\'0 2px 4px rgba(0,0,0,0.1)\'" onclick="window.location.href=\'{{ url("/store") }}/' + store.seo_url + '\'">';

              if (store.store_logo) {
                  var logoPath = '{{ asset("") }}' + store.store_logo;
                  brandsHtml += '<img src="' + logoPath + '" alt="' + store.store_name + '" style="width: 40px; height: 40px; margin-right: 12px; object-fit: contain; border-radius: 4px;">';
              } else {
                  brandsHtml += '<div style="width: 40px; height: 40px; margin-right: 12px; background: #f8f9fa; border-radius: 4px; display: flex; align-items: center; justify-content: center; font-weight: bold; color: #6c757d; font-size: 14px;">' + store.store_name.substring(0, 2).toUpperCase() + '</div>';
              }

              brandsHtml += '<div style="flex: 1;"><div style="font-weight: 600; color: #2c3e50; font-size: 16px; margin-bottom: 5px;">' + store.store_name + '</div><div style="color: #7f8c8d; font-size: 14px;">View offers</div></div></div>';
          });
      } else {
          brandsHtml = '<div style="text-align: center; padding: 30px; color: #95a5a6; font-size: 16px;"><div style="font-size: 48px; margin-bottom: 15px;">🏪</div>No brands available</div>';
      }

      if (trendingOffers) {
          trendingOffers.innerHTML = offersHtml;
      }
      if (brandsList) {
          brandsList.innerHTML = brandsHtml;
      }
  }

  function openSearchModal() {
      // Open modal immediately using vanilla JS (no jQuery needed)
      var searchModal = document.getElementById('searchModal');
      if (searchModal) {
          // Use 'flex' instead of 'block' to maintain centering (align-items: center, justify-content: center)
          searchModal.style.display = 'flex';
          document.body.style.overflow = 'hidden';

          // Load default data immediately (before jQuery loads)
          loadDefaultDataVanilla();

          // Focus input after a short delay
          var modalInput = document.getElementById('modalSearchInput');
          if (modalInput) {
              setTimeout(function() {
                  modalInput.focus();
              }, 100);
          }

          // Initialize jQuery and search functionality in background (only once)
          if (!searchModalInit) {
              searchModalInit = true;

              // Load jQuery only when user clicks search
              if (typeof window.ensurejQuery === 'function') {
                  window.ensurejQuery(function() {
                      if (typeof jQuery !== 'undefined' && typeof $ !== 'undefined') {
                          initSearchModal();
                      }
                  });
              } else if (typeof jQuery !== 'undefined') {
                  initSearchModal();
              }
          }
      }
  }

  // Close modal function (vanilla JS - works before jQuery loads)
  function closeSearchModal() {
      var searchModal = document.getElementById('searchModal');
      if (searchModal) {
          searchModal.style.display = 'none';
          document.body.style.overflow = '';
          var modalInput = document.getElementById('modalSearchInput');
          if (modalInput) {
              modalInput.value = '';
          }
      }
  }

  // Attach click handlers when DOM is ready (jQuery not needed for this)
  function attachSearchHandlers() {
      var searchBarTrigger = document.getElementById('searchBarTrigger');
      var searchBarButton = document.querySelector('.search-bar-button');
      var mobileSearchBtn = document.getElementById('mobileSearchBtn');
      var closeSearchModalBtn = document.getElementById('closeSearchModal');
      var searchModal = document.getElementById('searchModal');

      if (searchBarTrigger) {
          searchBarTrigger.addEventListener('click', openSearchModal, { passive: true });
      }
      if (searchBarButton) {
          searchBarButton.addEventListener('click', function(e) {
              e.stopPropagation();
              openSearchModal();
          }, { passive: true });
      }
      if (mobileSearchBtn) {
          mobileSearchBtn.addEventListener('click', openSearchModal, { passive: true });
      }
      if (closeSearchModalBtn) {
          closeSearchModalBtn.addEventListener('click', closeSearchModal, { passive: true });
      }
      // Close modal when clicking overlay
      if (searchModal) {
          searchModal.addEventListener('click', function(e) {
              if (e.target === searchModal || e.target.classList.contains('search-modal-overlay')) {
                  closeSearchModal();
              }
          }, { passive: true });
      }
      // Close modal on Escape key
      document.addEventListener('keydown', function(e) {
          if (e.key === 'Escape') {
              var modal = document.getElementById('searchModal');
              if (modal && modal.style.display === 'flex') {
                  closeSearchModal();
              }
          }
      }, { passive: true });
  }

  // Attach handlers when DOM is ready
  if (document.readyState === 'loading') {
      document.addEventListener('DOMContentLoaded', attachSearchHandlers);
  } else {
      // DOM already loaded
      attachSearchHandlers();
  }

  // Mobile Menu Button Functionality - AdSense Compliant (Vanilla JS - No jQuery dependency)
  function initMobileMenu() {
      const mobileMenuBtn = document.getElementById('mobileMenuBtn');
      const sidenv = document.querySelector('.sidenv');

      if (!mobileMenuBtn || !sidenv) {
          // Retry if elements not found yet
          setTimeout(initMobileMenu, 100);
          return;
      }

      // Ensure menu is properly initialized for mobile (CSS will handle styling)
      // No need to reset inline styles as CSS !important rules handle it

      // Toggle menu on button click
      mobileMenuBtn.addEventListener('click', function(e) {
          e.preventDefault();
          e.stopPropagation();

          const isActive = sidenv.classList.contains('active');

          if (isActive) {
              closeMobileMenu();
          } else {
              openMobileMenu();
          }
      });

      // Close menu when clicking close button
      const closeBtn = sidenv.querySelector('.snx');
      if (closeBtn) {
          // Use once option to prevent multiple handlers
          closeBtn.addEventListener('click', function(e) {
              e.preventDefault();
              e.stopPropagation();
              e.stopImmediatePropagation();
              closeMobileMenu();
              return false;
          }, { once: false, capture: true });

          // Also handle touch events for mobile
          closeBtn.addEventListener('touchend', function(e) {
              e.preventDefault();
              e.stopPropagation();
              e.stopImmediatePropagation();
              closeMobileMenu();
              return false;
          }, { once: false, capture: true });
      }

      // Close menu when clicking overlay
      document.addEventListener('click', function(e) {
          if (sidenv.classList.contains('active')) {
              if (!sidenv.contains(e.target) && e.target !== mobileMenuBtn && !mobileMenuBtn.contains(e.target)) {
                  closeMobileMenu();
              }
          }
      });

      // Close menu on escape key
      document.addEventListener('keydown', function(e) {
          if (e.key === 'Escape' && sidenv.classList.contains('active')) {
              closeMobileMenu();
          }
      });

      // Prevent body scroll when menu is open
      const observer = new MutationObserver(function(mutations) {
          mutations.forEach(function(mutation) {
              if (mutation.attributeName === 'class') {
                  const isMenuOpen = document.body.classList.contains('menu-open');
                  if (isMenuOpen) {
                      document.body.style.overflow = 'hidden';
                      document.body.style.position = 'fixed';
                      document.body.style.width = '100%';
                  } else {
                      document.body.style.overflow = '';
                      document.body.style.position = '';
                      document.body.style.width = '';
                  }
              }
          });
      });

      observer.observe(document.body, {
          attributes: true,
          attributeFilter: ['class']
      });

      function openMobileMenu() {
          // CSS handles all styling, just add active class
          sidenv.classList.add('active');
          document.body.classList.add('menu-open');

          // Change to X icon
          const svg = mobileMenuBtn.querySelector('svg');
          if (svg) {
              svg.innerHTML = `
                  <line x1="18" y1="6" x2="6" y2="18" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"/>
                  <line x1="6" y1="6" x2="18" y2="18" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"/>
              `;
          }

          // Focus trap for accessibility
          const firstLink = sidenv.querySelector('a');
          if (firstLink) {
              setTimeout(() => firstLink.focus(), 100);
          }
      }

      function closeMobileMenu() {
          sidenv.classList.remove('active');
          document.body.classList.remove('menu-open');

          // Change back to hamburger icon
          const svg = mobileMenuBtn.querySelector('svg');
          if (svg) {
              svg.innerHTML = `
                  <line x1="3" y1="6" x2="21" y2="6"/>
                  <line x1="3" y1="12" x2="21" y2="12"/>
                  <line x1="3" y1="18" x2="21" y2="18"/>
              `;
          }
      }
  }

  // ---- Compact Dropdown Menus (desktop) — shared by Trending and Categories ----
  (function () {
      function initCompactDropdown(wrapId, triggerId, panelId) {
          const wrap = document.getElementById(wrapId);
          const trigger = document.getElementById(triggerId);
          const panel = document.getElementById(panelId);
          if (!wrap || !trigger || !panel) return;

          // Only Categories has these (left category list / right store panels per category);
          // Trending's panel is a single flat list, so both arrays are simply empty for it
          // and the category-switching/arrow-key logic below becomes a no-op.
          const items = Array.from(panel.querySelectorAll('.cat-menu-item'));
          const storePanels = Array.from(panel.querySelectorAll('.cat-menu-store-panel'));
          let closeTimer = null;
          let openTimer = null;

          function selectCategory(target) {
              items.forEach((item) => item.classList.toggle('is-active', item.dataset.catTarget === target));
              storePanels.forEach((sp) => sp.classList.toggle('is-active', sp.dataset.catPanel === target));
          }

          function openMenu() {
              clearTimeout(closeTimer);
              clearTimeout(openTimer);
              panel.classList.add('is-open');
              trigger.classList.add('is-open');
              trigger.setAttribute('aria-expanded', 'true');
          }

          function closeMenu(focusTrigger) {
              clearTimeout(closeTimer);
              clearTimeout(openTimer);
              panel.classList.remove('is-open');
              trigger.classList.remove('is-open');
              trigger.setAttribute('aria-expanded', 'false');
              if (focusTrigger) trigger.focus();
          }

          function scheduleClose() {
              clearTimeout(closeTimer);
              closeTimer = setTimeout(() => closeMenu(false), 300);
          }

          function scheduleOpen() {
              clearTimeout(openTimer);
              openTimer = setTimeout(openMenu, 120);
          }

          trigger.addEventListener('click', function (e) {
              e.preventDefault();
              if (panel.classList.contains('is-open')) {
                  closeMenu(false);
              } else {
                  openMenu();
              }
          });

          wrap.addEventListener('mouseenter', scheduleOpen);
          wrap.addEventListener('mouseleave', scheduleClose);

          // Note: intentionally NOT opening on focusin. A real click (and Enter/Space on the
          // button) fires focus *before* the click event, so an immediate open-on-focus would
          // race with the click handler's open/close toggle above and immediately undo it.
          // Tab reaching the trigger is enough; Enter/Space (native button click) opens it.
          wrap.addEventListener('focusout', function (e) {
              if (!wrap.contains(e.relatedTarget)) {
                  scheduleClose();
              }
          });

          items.forEach((item) => {
              item.addEventListener('mouseenter', () => selectCategory(item.dataset.catTarget));
              item.addEventListener('focus', () => selectCategory(item.dataset.catTarget));
          });

          if (items.length) {
              panel.addEventListener('keydown', function (e) {
                  const currentIndex = items.indexOf(document.activeElement);
                  if (e.key === 'ArrowDown') {
                      e.preventDefault();
                      (items[currentIndex + 1] || items[0]).focus();
                  } else if (e.key === 'ArrowUp') {
                      e.preventDefault();
                      (items[currentIndex - 1] || items[items.length - 1]).focus();
                  } else if (e.key === 'Home') {
                      e.preventDefault();
                      items[0].focus();
                  } else if (e.key === 'End') {
                      e.preventDefault();
                      items[items.length - 1].focus();
                  }
              });
          }

          document.addEventListener('keydown', function (e) {
              if (e.key === 'Escape' && panel.classList.contains('is-open')) {
                  closeMenu(true);
              }
          });

          document.addEventListener('click', function (e) {
              if (panel.classList.contains('is-open') && !wrap.contains(e.target)) {
                  closeMenu(false);
              }
          });
      }

      initCompactDropdown('trendMenu', 'trendMenuTrigger', 'trendMenuPanel');
      initCompactDropdown('catMenu', 'catMenuTrigger', 'catMenuPanel');
  })();

  // ---- Categories Accordion (mobile sidenav) ----
  (function () {
      const catWrap = document.getElementById('snCat');
      const catTrigger = document.getElementById('snCatTrigger');
      const catList = document.getElementById('snCatList');
      if (!catWrap || !catTrigger || !catList) return;

      const rows = Array.from(catWrap.querySelectorAll('.sn-cat-row'));
      const storePanels = Array.from(catWrap.querySelectorAll('.sn-store-panel'));

      function resetToList() {
          storePanels.forEach((sp) => sp.classList.remove('is-open'));
          catList.classList.remove('is-open');
          catTrigger.setAttribute('aria-expanded', 'false');
      }

      catTrigger.addEventListener('click', function () {
          const isOpen = catList.classList.contains('is-open');
          if (isOpen) {
              resetToList();
          } else {
              storePanels.forEach((sp) => sp.classList.remove('is-open'));
              catList.classList.add('is-open');
              catTrigger.setAttribute('aria-expanded', 'true');
          }
      });

      rows.forEach((row) => {
          row.addEventListener('click', function () {
              const target = row.getAttribute('data-sn-cat');
              catList.classList.remove('is-open');
              storePanels.forEach((sp) => {
                  sp.classList.toggle('is-open', sp.getAttribute('data-sn-store-panel') === target);
              });
          });
      });

      catWrap.querySelectorAll('[data-sn-back]').forEach((backBtn) => {
          backBtn.addEventListener('click', function () {
              storePanels.forEach((sp) => sp.classList.remove('is-open'));
              catList.classList.add('is-open');
          });
      });

      // Start fresh (category list, not a leftover store panel) whenever the mobile drawer is opened.
      // This listener is registered after the drawer's own open/close handler, so by the time it
      // runs, body.menu-open already reflects the *new* state (present = we just opened).
      const mobileMenuBtnEl = document.getElementById('mobileMenuBtn');
      if (mobileMenuBtnEl) {
          mobileMenuBtnEl.addEventListener('click', function () {
              if (document.body.classList.contains('menu-open')) {
                  resetToList();
              }
          });
      }
  })();
  </script>

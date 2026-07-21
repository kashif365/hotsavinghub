<!DOCTYPE html>
<html lang="en" prefix="og: http://ogp.me/ns#">
<head prefix="og: https://ogp.me/ns# fb: https://ogp.me/ns/fb# topcodevoucher: https://ogp.me/ns/fb/topcodevoucher#">
    {{-- Force standards mode - ensure doctype is recognized --}}
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    @php
        $verificationTags = ($verificationTagPlacements ?? collect());
    @endphp
    @foreach($verificationTags->get('head_start', collect()) as $verificationTag)
        {!! $verificationTag->renderTag() !!}
    @endforeach

    {{-- User can add custom meta tags here using @push('meta') in any view --}}
    @stack('meta')


    {{-- Load CSS files normally - restored from PageSpeed optimizations --}}
    @php
        $colorsVersion = file_exists(public_path('css/colors.css')) ? filemtime(public_path('css/colors.css')) : time();
        $getFileVersion = function($path) {
            $fullPath = public_path($path);
            return file_exists($fullPath) ? filemtime($fullPath) : time();
        };
        $cssVersion = $getFileVersion('frontend_assets/css/home.css');
        $brandVersion = $getFileVersion('frontend_assets/css/brand.css');
        $responsiveVersion = $getFileVersion('frontend_assets/css/responsive-home.css');
        $mobileVersion = $getFileVersion('frontend_assets/css/mobile-optimizations.css');
        $redesignVersion = $getFileVersion('frontend_assets/css/home-redesign.css');
    @endphp

    {{-- Load all CSS files normally --}}
    <link rel="stylesheet" href="{{ url('/css/colors.css') }}?v={{ $colorsVersion }}">
    <link rel="stylesheet" href="{{ asset('frontend_assets/css/fonts.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend_assets/css/home.css') }}?v={{ $cssVersion }}">
    <link rel="stylesheet" href="{{ asset('frontend_assets/css/brand.css') }}?v={{ $brandVersion }}">
    <link rel="stylesheet" href="{{ asset('frontend_assets/css/responsive-home.css') }}?v={{ $responsiveVersion }}">
    <link rel="stylesheet" href="{{ asset('frontend_assets/css/mobile-optimizations.css') }}?v={{ $mobileVersion }}">
    {{-- Shared homepage design-system tokens/primitives (redesign) --}}
    <link rel="stylesheet" href="{{ asset('frontend_assets/css/home-redesign.css') }}?v={{ $redesignVersion }}">

    {{-- Font Awesome 6.5.1 --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    @stack('styles')

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

        // Helpers to compute WCAG contrast against white
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
            // White luminance is 1.0
            $L = $calcLuminance($r, $g, $b);
            return (1.0 + 0.05) / ($L + 0.05);
        };

        // Ensure primary color has at least ~4.5:1 contrast against white.
        // This keeps headings, buttons, and badges accessible even if a very light brand color is chosen.
        $targetContrast = 4.5;
        $factor = 1.0;
        $adjR = $baseR;
        $adjG = $baseG;
        $adjB = $baseB;

        // Gradually darken the color (up to 10 steps) until contrast is sufficient.
        $steps = 0;
        while ($contrastWithWhite($adjR, $adjG, $adjB) < $targetContrast && $steps < 10) {
            $factor -= 0.08; // darken by 8% each step
            if ($factor <= 0.2) {
                $factor = 0.2; // don't go fully black
            }
            $adjR = max(0, min(255, (int) round($baseR * $factor)));
            $adjG = max(0, min(255, (int) round($baseG * $factor)));
            $adjB = max(0, min(255, (int) round($baseB * $factor)));
            $steps++;
        }

        $accessiblePrimary = sprintf('#%02X%02X%02X', $adjR, $adjG, $adjB);

        // Now compute a slightly darker hover color from the accessible primary (darken by ~15%)
        $hoverR = max(0, min(255, (int) round($adjR - ($adjR * 15 / 100))));
        $hoverG = max(0, min(255, (int) round($adjG - ($adjG * 15 / 100))));
        $hoverB = max(0, min(255, (int) round($adjB - ($adjB * 15 / 100))));

        $primaryHover = sprintf('#%02X%02X%02X', $hoverR, $hoverG, $hoverB);

        // Use the contrast-safe color everywhere on the frontend
        $primaryColor = $accessiblePrimary;
    @endphp

    {{-- Critical CSS: Set primary color immediately in head to prevent FOUC --}}
    <style>
        :root {
            --primary-color: {{ $primaryColor }};
            --primary-hover: {{ $primaryHover }};
        }
        html {
            --primary-color: {{ $primaryColor }};
            --primary-hover: {{ $primaryHover }};
        }
        body {
            --primary-color: {{ $primaryColor }};
            --primary-hover: {{ $primaryHover }};
        }
        /* Prevent white flash on header top-promo-bar */
        .top-promo-bar {
            background: {{ $primaryColor }} !important;
        }
    </style>
    @if($brandingSettings['site_favicon_url'])
        <link rel="shortcut icon" href="{{ $brandingSettings['site_favicon_url'] }}" type="image/png" />
        <link rel="icon" href="{{ $brandingSettings['site_favicon_url'] }}" type="image/png">
        <link rel="mask-icon" href="{{ $brandingSettings['site_favicon_url'] }}">
        <link rel="apple-touch-icon" href="{{ $brandingSettings['site_favicon_url'] }}" />
    @else
        <link rel="shortcut icon" href="{{ asset('assets/img/icons/logo.png') }}" type="image/png" />
        <link rel="icon" href="{{ asset('assets/img/icons/logo.png') }}" type="image/png">
        <link rel="mask-icon" href="{{ asset('assets/img/icons/logo.png') }}">
        <link rel="apple-touch-icon" href="{{ asset('assets/img/icons/logo.png') }}" />
    @endif


    <title>@yield('title', $brandingSettings['site_name'] ?? 'Hotsavinghub')</title>

    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, user-scalable=yes">

    <meta name="description" content="@yield('description', 'Discover the latest UK discount and voucher codes at Hotsavinghub. Explore exclusive online deals, and save big on your favourite brands. Start saving today!')" />
    <meta name="keywords" content="@yield('keywords', 'Vouchers, Voucher Codes, Discount Vouchers, Promo Codes, Promotional Codes, Hotsavinghub')" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="data-attr" content="0,Home">
    @if(!isset($homePage) || !$homePage || !$homePage->canonical_url)
        <link rel="canonical" href="{{ url()->current() }}">
    @endif

    <!-- Chrome, Firefox OS and Opera -->
    <meta name="theme-color" content="#4a0c98">
    <!-- Windows Phone -->
    <meta name="msapplication-navbutton-color" content="#4a0c98">
    <!-- iOS Safari -->
    <meta name="apple-mobile-web-app-status-bar-style" content="#4a0c98">
    <meta name="author" content="Hotsavinghub">


    <!-- <meta property="fb:app_id" content="105222033160224" /> -->

    <meta property="og:title" content="@yield('og_title', $brandingSettings['site_name'] . ' - Discount Codes & Voucher Codes')" />
    <meta property="og:description" content="@yield('og_description', 'Discover the latest UK discount and voucher codes at Hotsavinghub. Explore exclusive online deals, and save big on your favourite brands.')" />
    <meta property="og:type" content="website" />
    <meta property="og:url" content="{{ url()->current() }}" />
    <meta property="og:image" content="{{ $brandingSettings['site_logo_url'] ?? asset('assets/img/icons/logo.png') }}" />
    <meta property="og:site_name" content="{{ $brandingSettings['site_name'] ?? 'Hotsavinghub' }}" />
    <meta name="apple-mobile-web-app-status-bar-style" content="#4a0c98">
    <meta name="apple-mobile-web-app-title" content="{{ $brandingSettings['site_name'] ?? 'Hotsavinghub' }}">
    <meta name="application-name" content="{{ $brandingSettings['site_name'] ?? 'Hotsavinghub' }}">
    <!-- <link rel="search" href="open-search.xml" title="Search bighsavinghub.com" type="application/opensearchdescription+xml"> -->

    {{-- Critical inline script - moved before closing head --}}
    <script>
        // Critical variables needed before page load
        window.app_config = {
            url: "{{ rtrim(url('/'), '/') }}/",
            media: "{{ asset('') }}",
            current_url: "{{ url()->current() }}",
            current_url_full: "{{ url()->full() }}",
            csrf_token: "{{ csrf_token() }}",
            Banner_Keyword: "voucher code",
            Logo_Keyword: "discount code",
            uid: "{{ auth()->id() ?? '0' }}"
        };

        // Legacy variables for backward compatibility
        var app_url = window.app_config.url;
        var app_media = window.app_config.media;
        var current_url = window.app_config.current_url;
        var current_url_full = window.app_config.current_url_full;
        var csrf_token = window.app_config.csrf_token;
        var Banner_Keyword = window.app_config.Banner_Keyword;
        var Logo_Keyword = window.app_config.Logo_Keyword;
        var uid = window.app_config.uid;
    </script>

    {{-- User can add custom head scripts here using @push('head_scripts') in any view --}}
    @stack('head_scripts')

    @foreach($verificationTags->get('head_end', collect()) as $verificationTag)
        {!! $verificationTag->renderTag() !!}
    @endforeach
</head>
<style>
    .dsclmr{
        display: none !important;
    }
</style>
<body>
    @foreach($verificationTags->get('body_start', collect()) as $verificationTag)
        {!! $verificationTag->renderTag() !!}
    @endforeach
    <!-- Skip to main content link for accessibility -->
    <a href="#main-content" class="skip-to-main" style="position: absolute; left: -9999px; z-index: 999999; padding: 1em; background: var(--primary-color, #2951c4); color: #fff; text-decoration: none; font-weight: 600;">Skip to main content</a>
    <style>
        .skip-to-main:focus {
            left: 0;
            top: 0;
        }
    </style>
    <!-- main wrapper <start> -->
    <main class="main" id="main-content">
        @include('frontend.partials.header')

        <div class="crtn"></div>

        <!-- Page content. -->
        @yield('content')

        @include('frontend.partials.footer')
    </main>

    <!-- Back to top button <start> -->
    <button id="tpBtn" type="button" tabindex="-1" class="bp_arw-ryt" aria-label="Back to top"></button>
    <!-- Back to top button <end> -->

    {{-- Load jQuery normally - restored from PageSpeed optimizations --}}
    <script src="{{ asset('frontend_assets/js/jquery-3.7.1.min.js') }}"></script>

    {{-- Load main JS normally --}}
    <script src="{{ asset('frontend_assets/js/home.js') }}"></script>


    {{-- User can add custom body scripts here using @push('scripts') in any view --}}
    @stack('scripts')

    @foreach($verificationTags->get('body_end', collect()) as $verificationTag)
        {!! $verificationTag->renderTag() !!}
    @endforeach
</body>
</html>

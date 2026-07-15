@extends('frontend.layouts.app')

@section('title', ($category->meta_title ?? $category->category_name . ' Discount Codes & Voucher Codes') . ' | Hotsavinghub')
@section('description', $category->meta_description ?? 'Get the latest ' . $category->category_name . ' discount codes and voucher codes. Save money on your purchases with verified offers from top UK brands.')
@section('keywords', $category->meta_keywords ?? $category->category_name . ' discount codes, ' . $category->category_name . ' voucher codes, ' . $category->category_name . ' promo codes, ' . $category->category_name . ' coupons')

@push('meta')
    @if($category->canonical_url)
        <link rel="canonical" href="{{ $category->canonical_url }}">
    @else
        <link rel="canonical" href="{{ route('category', $category->seo_url) }}">
    @endif
@endpush

@if($category->schema && trim($category->schema) !== '' && trim($category->schema) !== 'test')
    @php
        $schemaContent = trim($category->schema);
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
<style>
.store-logo-placeholder {
    width: 90px;
    height: 90px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: bold;
    font-size: 24px;
    text-transform: uppercase;
}
.no-coupons {
    text-align: center;
    padding: 40px 20px;
    background: #f8f9fa;
    border-radius: 8px;
    margin: 20px 0;
}
.no-coupons p {
    color: #6c757d;
    margin: 0;
}

/* .pgHd{
  max-width: 1230px;
}
.Sec.bg{
  max-width: 1230px;
} */


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
    background: #333;
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
    background: #555;
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

/* Responsive Design */
@media (max-width: 768px) {
    #couponModal {
        gap: 15px;
    }

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
<style/>
@endpush

@push('scripts')
<script src="{{ asset('frontend_assets/js/store.js') }}" async crossorigin></script>
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
  const cmBrandLogo = document.getElementById('cmBrandLogo');
  const cmBrandText = document.getElementById('cmBrandText');

  function openModal(code, affiliate, store, title) {
    if (cmCode) cmCode.textContent = code || '';
    if (cmTitle) cmTitle.textContent = title || 'Here is your code';
    if (cmEmailTitle) cmEmailTitle.textContent = `Get More ${store} Deals!`;

    window.currentAffiliateUrl = affiliate;

    if (cmBrandLogo && cmBrandText) {
      if (store && store !== 'Store') {
        cmBrandText.textContent = store.substring(0,5).toUpperCase();
      } else {
        cmBrandText.textContent = 'STORE';
      }
    }

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

  function isCouponUsedInSession(couponId) {
    const sessionKey = 'coupon_used_' + couponId;
    return sessionStorage.getItem(sessionKey) === 'true';
  }

  function markCouponAsUsedInSession(couponId) {
    const sessionKey = 'coupon_used_' + couponId;
    sessionStorage.setItem(sessionKey, 'true');
  }

  function trackCouponUsage(couponId) {
    if (isCouponUsedInSession(couponId)) { return; }
    fetch('{{ route("coupon.track-usage") }}', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      },
      body: JSON.stringify({ coupon_id: couponId })
    })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        markCouponAsUsedInSession(couponId);
        if (!data.already_used) {
          document.querySelectorAll('.cusd[data-coupon-id="' + couponId + '"]').forEach(el => {
            const totalUsedEl = el.querySelector('.total-used');
            const todayUsedEl = el.querySelector('.today-used');
            if (totalUsedEl && data.used_count !== undefined) { totalUsedEl.textContent = parseInt(data.used_count).toLocaleString(); }
            if (todayUsedEl && data.today_count !== undefined) { todayUsedEl.textContent = data.today_count; }
          });
        }
      }
    })
    .catch(error => { console.error('Error tracking coupon usage:', error); });
  }

  document.querySelectorAll('.cpBtn.get-code').forEach(btn => {
    btn.addEventListener('click', function(e) {
      e.preventDefault();
      const couponElement = this.closest('.cpn');
      const couponId = couponElement ? couponElement.dataset.id : null;
      const code = this.dataset.code;
      const affiliate = this.dataset.affiliate;
      const store = this.dataset.store;
      const title = this.dataset.title;
      if (couponId) { trackCouponUsage(couponId); }
      if (affiliate) {
        const currentUrl = window.location.href.split('#')[0].split('?')[0];
        const popupUrl = currentUrl + '?show_coupon=1&code=' + encodeURIComponent(code || '') + '&affiliate=' + encodeURIComponent(affiliate) + '&store=' + encodeURIComponent(store) + '&title=' + encodeURIComponent(title);
        window.open(popupUrl, '_blank');
        window.location.href = affiliate;
      } else {
        openModal(code || 'No code required', affiliate, store, title);
      }
    });
  });

  document.querySelectorAll('.cpBtn.reveal-code').forEach(btn => {
    btn.addEventListener('click', function(e) {
      e.preventDefault();
      const couponElement = this.closest('.cpn');
      const couponId = couponElement ? couponElement.dataset.id : null;
      const code = this.dataset.code;
      const affiliate = this.dataset.affiliate;
      const store = this.dataset.store;
      const title = this.dataset.title;
      if (couponId) { trackCouponUsage(couponId); }
      if (code && affiliate) {
        const currentUrl = window.location.href.split('#')[0].split('?')[0];
        const popupUrl = currentUrl + '?show_coupon=1&code=' + encodeURIComponent(code) + '&affiliate=' + encodeURIComponent(affiliate) + '&store=' + encodeURIComponent(store) + '&title=' + encodeURIComponent(title);
        window.open(popupUrl, '_blank');
        window.location.href = affiliate;
      }
    });
  });

  document.querySelectorAll('.cpBtn.get-deal').forEach(btn => {
    btn.addEventListener('click', function(e) {
      e.preventDefault();
      const couponElement = this.closest('.cpn');
      const couponId = couponElement ? couponElement.dataset.id : null;
      const affiliate = this.getAttribute('href') || this.dataset.affiliate || '#';
      const store = this.dataset.store || this.dataset.title || '';
      const title = this.dataset.title || '';
      if (couponId) { trackCouponUsage(couponId); }
      if (affiliate && affiliate !== '#') {
        const currentUrl = window.location.href.split('#')[0].split('?')[0];
        const popupUrl = currentUrl + '?show_coupon=1&code=&affiliate=' + encodeURIComponent(affiliate) + '&store=' + encodeURIComponent(store) + '&title=' + encodeURIComponent(title);
        window.open(popupUrl, '_blank');
        window.location.href = affiliate;
      }
    });
  });

  if (cmCopy) {
    cmCopy.addEventListener('click', function() {
      const code = cmCode ? cmCode.textContent : '';
      if (code && code !== 'No code required') {
        navigator.clipboard.writeText(code).then(function() {
          const originalText = cmCopy.textContent;
          cmCopy.textContent = 'Copied!';
          cmCopy.style.backgroundColor = '#218838';
          setTimeout(function() { cmCopy.textContent = originalText; cmCopy.style.backgroundColor = '#28a745'; }, 2000);
        }).catch(function(err) { console.error('Could not copy text: ', err); alert('Coupon Code: ' + code); });
      } else if (code === 'No code required') {
        const originalText = cmCopy.textContent;
        cmCopy.textContent = 'No Code Needed!';
        cmCopy.style.backgroundColor = '#218838';
        setTimeout(function() { cmCopy.textContent = originalText; cmCopy.style.backgroundColor = '#28a745'; }, 2000);
      }
    });
  }

  const cmRedirect = document.getElementById('cmRedirect');
  if (cmRedirect) {
    cmRedirect.addEventListener('click', function() {
      const currentAffiliate = window.currentAffiliateUrl || '#';
      if (currentAffiliate && currentAffiliate !== '#') { window.open(currentAffiliate, '_blank'); }
    });
  }

  if (cmEmailForm) {
    cmEmailForm.addEventListener('submit', function(e) {
      e.preventDefault();
      const email = this.querySelector('input[type="email"]').value;
      if (email) { alert('Thank you for subscribing!'); closeModal(); }
    });
  }

  if (closeBtn) closeBtn.addEventListener('click', closeModal);
  if (overlay) overlay.addEventListener('click', closeModal);

  try {
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('show_coupon') === '1') {
      const code = urlParams.get('code') || '';
      const affiliate = urlParams.get('affiliate') || '#';
      const store = urlParams.get('store') || 'Store';
      const title = urlParams.get('title') || 'Here is your code';
      openModal(code, affiliate, store, title);
      if (!code) {
        if (cmCode) cmCode.textContent = 'No code required';
        if (cmCopy) { cmCopy.disabled = true; cmCopy.style.opacity = '0.6'; cmCopy.style.cursor = 'not-allowed'; }
      }
      history.replaceState({}, '', window.location.pathname);
    }
  } catch (e) { console.log('URL params not supported'); }

  const newsletterForm = document.getElementById('newsletterForm');
  if (newsletterForm) {
    newsletterForm.addEventListener('submit', function(e) {
      e.preventDefault();
      const email = document.getElementById('newsletterEmail').value.trim();
      const submitBtn = document.getElementById('newsletterBtn');
      const messageDiv = document.getElementById('newsletterMessage');
      if (!email) { showMessage('Please enter your email address.', 'error'); return; }
      if (!email.includes('@') || !email.includes('.')) { showMessage('Please enter a valid email address.', 'error'); return; }
      const atIndex = email.indexOf('@');
      const lastDotIndex = email.lastIndexOf('.');
      if (atIndex === -1 || lastDotIndex === -1 || atIndex >= lastDotIndex) { showMessage('Please enter a valid email address.', 'error'); return; }
      submitBtn.disabled = true;
      submitBtn.textContent = 'Subscribing...';
      fetch('{{ route("newsletter.subscribe") }}', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') },
        body: JSON.stringify({ email: email })
      })
      .then(response => { if (!response.ok) { throw new Error(`HTTP error! status: ${response.status}`); } return response.json(); })
      .then(data => {
        if (data.success) { showMessage(data.message, 'success'); document.getElementById('newsletterEmail').value = ''; }
        else { showMessage(data.message || 'Something went wrong. Please try again.', 'error'); }
      })
      .catch(error => {
        console.error('Error:', error);
        if (error.name === 'TypeError' && error.message.includes('fetch')) { showMessage('Network error. Please check your internet connection.', 'error'); }
        else if (error.message.includes('HTTP error! status: 422')) { showMessage('This email is already subscribed to our newsletter.', 'error'); }
        else if (error.message.includes('HTTP error! status: 500')) { showMessage('Server error. Please try again later.', 'error'); }
        else { showMessage('Something went wrong. Please try again.', 'error'); }
      })
      .finally(() => { submitBtn.disabled = false; submitBtn.textContent = 'Subscribe'; });
    });
  }

  function showMessage(message, type) {
    const messageDiv = document.getElementById('newsletterMessage');
    if (messageDiv) {
      if (messageDiv.timeoutId) { clearTimeout(messageDiv.timeoutId); }
      messageDiv.textContent = '';
      messageDiv.style.display = 'none';
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
        messageDiv.timeoutId = setTimeout(() => { messageDiv.style.display = 'none'; }, 5000);
      }, 100);
    }
  }
});
</script>
@endpush

@section('content')

<!-- Page Content <start> -->
<input type="radio" name="cpnflt" id="cpnall" class="cpnall" checked="">
<input type="radio" name="cpnflt" id="cpncd" class="cpncd">
<input type="radio" name="cpnflt" id="cpnfs" class="cpnfs">
<input type="radio" name="cpnflt" id="cpndl" class="cpndl">
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
        <a href="{{ route('categories') }}" class="link" itemprop="item">
                    <span itemprop="name">Categories</span>
                    <meta itemprop="position" content="2">
                </a>
            </li>
      <li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
        <a href="{{ route('category', $category->seo_url) }}" class="link active" itemprop="item">
          <span itemprop="name">{{ $category->category_name }}</span>
                    <meta itemprop="position" content="3">
                </a>
            </li>
        </ul>
        <!-- Breadcrumb <end> -->

    <!-- Category Head <start> -->
    <div class="strHd">
      <div class="lgo">
        @if($category->media && file_exists(public_path(ltrim($category->media, '/'))))
            <img src="{{ asset(ltrim($category->media, '/')) }}" alt="{{ $category->category_name }} discount codes" title="{{ $category->category_name }} discount codes" width="120" height="120" loading="lazy" decoding="async" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
            <div class="store-logo-placeholder" style="display: none;">{{ substr($category->category_name, 0, 2) }}</div>
        @else
            <div class="store-logo-placeholder">{{ substr($category->category_name, 0, 2) }}</div>
        @endif
        <button class="sfvbtn bp_hrt" role="button" aria-label="Save category" data-id="{{ $category->id }}"></button>
                </div>
      <div class="cntr">
        <!-- category title -->
        <h1>{{ $category->category_name }} Discount Codes {{ date('F Y') }}</h1>
        <!-- category title <end> -->

        <!-- category description -->
        <p class="d-tab-none">Save money with these {{ $categoryCoupons->count() }} {{ $category->category_name }} voucher codes & deals</p>
        <!-- category description <end> -->

        <!-- rating -->
        <div class="rating">
            <input type="radio" id="star1" name="rating" value="1">
            <label class="bp_str rated" for="star1" onclick="categoryRating(1, {{ $category->id }}, '{{ request()->ip() }}')"></label>
            <input type="radio" id="star2" name="rating" value="2">
            <label class="bp_str rated" for="star2" onclick="categoryRating(2, {{ $category->id }}, '{{ request()->ip() }}')"></label>
            <input type="radio" id="star3" name="rating" value="3">
            <label class="bp_str rated" for="star3" onclick="categoryRating(3, {{ $category->id }}, '{{ request()->ip() }}')"></label>
            <input type="radio" id="star4" name="rating" value="4">
            <label class="bp_str rated" for="star4" onclick="categoryRating(4, {{ $category->id }}, '{{ request()->ip() }}')"></label>
            <input type="radio" id="star5" name="rating" value="5">
            <label class="bp_str" for="star5" onclick="categoryRating(5, {{ $category->id }}, '{{ request()->ip() }}')"></label>
            <p class="ratingCalculator">Rated 4 from 21 votes</p>
                </div>
        <!-- rating <end> -->
                    </div>
      <div class="btns">
        <a href="{{ route('categories') }}" class="affiliate btn" aria-label="Browse" style="padding:0px 20px;"><i class="bp_visit"></i> Browse</a>
                </div>
            </div>
    <!-- Category Head <end> -->
                </div>
            </div>


<!-- sidebar wrp -->
<div class="Sec bg">
  <div class="splt Wrp">
    <!-- coupon side -->
    <div class="wgtc">
      <div class="cpns wd">
        @if($categoryCoupons->count() > 0)
            @foreach($categoryCoupons as $coupon)
            <!-- coupon:code <start> -->
            <div class="cpn {{ $coupon->coupon_code ? 'cd' : 'dl' }} {{ $coupon->free_shipping ? 'fs' : '' }} {{ $coupon->student_offer ? 'std' : '' }}" data-id="{{ $coupon->id }}">
                <button title="Add to Favourite" class="cfb bp_save hideIconS" aria-label="Add to Favourite"></button>

                <a class="clgo" href="javascript:;" title="{{ $coupon->brand_store }} Vouchers Code">
                    @if($coupon->store && $coupon->store->store_logo)
                        <img src="{{ asset( $coupon->store->store_logo) }}" alt="{{ $coupon->brand_store }} discount code" title="{{ $coupon->brand_store }} discount code" decoding="async" loading="lazy" width="90" height="90">
                    @else
                        <div class="store-logo-placeholder">{{ substr($coupon->brand_store, 0, 2) }}</div>
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
                            data-affiliate="{{ $coupon->affiliate_url ?? ($coupon->store ? $coupon->store->affiliate_url : url('/')) }}"
                            data-store="{{ $coupon->brand_store }}"
                            data-title="{{ $coupon->coupon_title }}">
                        Get Code
                    </button>
                @elseif($coupon->coupon_code)
                    <button class="cpBtn reveal-code" title="Reveal Code" aria-label="Reveal Code"
                            data-code="{{ $coupon->coupon_code }}"
                            data-affiliate="{{ $coupon->affiliate_url ?? ($coupon->store ? $coupon->store->affiliate_url : url('/')) }}"
                            data-store="{{ $coupon->brand_store }}"
                            data-title="{{ $coupon->coupon_title }}">
                        Reveal Code
                    </button>
                @else
                    <button class="cpBtn get-deal" aria-label="Get Deal"
                            data-affiliate="{{ $coupon->affiliate_url ?? ($coupon->store ? $coupon->store->affiliate_url : url('/')) }}"
                            data-store="{{ $coupon->brand_store }}"
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
                <h2>Stay Updated â€“ Never Miss a {{ $category->category_name }} Voucher Code Again!</h2>
                <p>Be the first one to get notified as soon as we update a new offer or discount.</p>

                <form id="newsletterForm" class="snfld" novalidate>
                    @csrf
                    <input type="text" name="email" id="newsletterEmail" placeholder="Enter Your Email Address Here" required>
                    <button type="submit" class="nfb" title="Subscribe" id="newsletterBtn">Subscribe</button>
                </form>
                <div id="newsletterMessage" style="margin-top: 10px; display: none; padding: 8px; border-radius: 4px; font-weight: 500; text-align: center;"></div>

                <p>By signing up I agree to {{ config('app.name') }}'s <a href="{{ route('privacy-policy') }}" target="_blank">Privacy Policy</a> and consent to receive emails about offers.</p>
            </div>
        @else
            <div class="no-coupons">
                <p>No active coupons available for {{ $category->category_name }} at the moment. Check back soon for new offers!</p>
            </div>
        @endif
                    </div>

      <!-- store table <start> -->
      <div class="crd tbl">
        <h3 class="hd">Save Big with {{ $category->category_name }} Discount Codes â€“ {{ date('d F Y') }}!</h3>
        <table>
          <tr>
            <th>Offers</th>
            <th>Last Checked</th>
            <th>Code</th>
          </tr>
          @if($categoryCoupons->count() > 0)
            @foreach($categoryCoupons->take(4) as $coupon)
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

      <!-- category description <start> -->
      @if(!empty($category->description))
      <div class="crd" id="abtCat">
        <h3 class="hd">More About {{ $category->category_name }}</h3>
        <div class="cnt 3">
          {!! $category->description !!}
        </div>
            </div>
      @endif
      <!-- category description <end> -->
            </div>

    <!-- sidebar -->
    <div class="wgts">
      <!-- rating -->
      <div class="wgt rating-box">
        <h3>How Did We Do? Rate {{ $category->category_name }} Vouchers Today!</h3>
        <div class="rating mb-3">
          <input type="radio" id="star1" name="rating" value="1">
          <label class="bp_str rated" for="star1" onclick="categoryRating(1, {{ $category->id }}, '{{ request()->ip() }}')"></label>
          <input type="radio" id="star2" name="rating" value="2">
          <label class="bp_str rated" for="star2" onclick="categoryRating(2, {{ $category->id }}, '{{ request()->ip() }}')"></label>
          <input type="radio" id="star3" name="rating" value="3">
          <label class="bp_str rated" for="star3" onclick="categoryRating(3, {{ $category->id }}, '{{ request()->ip() }}')"></label>
          <input type="radio" id="star4" name="rating" value="4">
          <label class="bp_str rated" for="star4" onclick="categoryRating(4, {{ $category->id }}, '{{ request()->ip() }}')"></label>
          <input type="radio" id="star5" name="rating" value="5">
          <label class="bp_str" for="star5" onclick="categoryRating(5, {{ $category->id }}, '{{ request()->ip() }}')"></label>
          <p class="ratingCalculator">Rated 4 from 21 votes</p>
        </div>
      </div>
      <!-- rating <end> -->

            <!-- filters <start> -->
            <div class="wgt">
                <h3>Filter by</h3>
                <div class="flts">
                    <label class="cfltr" for="cpnall">All</label>
                    <label class="cfltr" for="cpncd">Voucher Code</label>
                    <label class="cfltr" for="cpndl">Online Sale</label>
                    <label class="cfltr" for="cpnfs">Student</label>
                </div>
            </div>
            <!-- filters <end> -->
      @if($stores->count() > 0)
                        <div class="wgt">
                <h3>Related Stores</h3>
                <div class="btns">
            @foreach($stores->take(25) as $store)
              <a href="{{ route('store', $store->seo_url) }}" title="{{ $store->store_name }}">{{ $store->store_name }}</a>
            @endforeach
                                    </div>
            </div>
      @endif

      @if($relatedCategories->count() > 0)
                        <div class="wgt">
                <h3>Related Categories</h3>
                <div class="btns">
            @foreach($relatedCategories as $relatedCategory)
              <a href="{{ route('category', $relatedCategory->seo_url) }}" title="{{ $relatedCategory->category_name }}">{{ $relatedCategory->category_name }}</a>
            @endforeach
                                    </div>
            </div>
      @endif

            <div class="wgt">
                <h3>Browse By Store</h3>
                <div class="btns alp">
          <a title="0-9" href="{{ route('all-brands', ['q' => '0-9']) }}">0-9</a>
          @foreach(range('A', 'Z') as $letter)
            <a title="{{ $letter }}" href="{{ route('all-brands', ['q' => $letter]) }}">{{ $letter }}</a>
          @endforeach
                                    </div>
            </div>

      @if($trendingStores->count() > 0)
                        <div class="wgt nbp">
                <h3>Trending Brands</h3>
          <p>Major Discounts, Vouchers and Codes for the month of {{ date('M Y') }}</p>
                <div class="btns">
            @foreach($trendingStores->take(30) as $store)
              <a href="{{ route('store', $store->seo_url) }}" title="{{ $store->store_name }}">{{ $store->store_name }}</a>
            @endforeach
                                    </div>
          <a href="{{ route('all-brands') }}" class="bwsMre">Browse A-Z</a>
            </div>
      @endif

        </div>
        <!-- sidebar <end> -->
    </div>
</div>
<!-- sidebar wrp <end> -->


<!-- Page Content <end> -->

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

@endsection

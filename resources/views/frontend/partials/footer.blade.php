@php
    $footerEvents = \App\Models\Events::where('status', 1)
        ->where('show_footer', 1)
        ->orderBy('sort_order', 'asc')
        ->take(4)
        ->get();
    $brandingSettings = \App\Helpers\SettingsHelper::getBranding();
    $socialSettings = \App\Helpers\SettingsHelper::getSocial();
@endphp
<style>
    /* Footer column grid: 5 columns when the Events column is present, 4 otherwise */
    .footer-main .row {
        display: grid;
        grid-template-columns: 1.3fr 1fr 1fr 1fr 1.15fr;
        gap: 0 32px;
        align-items: start;
    }
    .footer-main .row:not(:has(.events-col)) {
        grid-template-columns: 1.3fr 1fr 1fr 1.15fr;
    }
    .logo-col, .quick-links-col, .events-col, .company-info-col, .newsletter-col {
        width: auto;
        padding: 0;
    }
    .disclosure p strong{
        /* Use dark color for better readability on light footer background */
        color: #ffffff !important;
        font-weight: 700;
      }
    @media (max-width: 768px) {
        .footer-main .row {
            grid-template-columns: repeat(2, 1fr);
            row-gap: 28px;
        }
        .logo-col{
            grid-column: 1 / -1;
            display: flex;
            justify-content: center;
            align-items: center;
            margin-bottom: 8px;
        }
        .footer-brand{
            max-width: 100% !important;
        }
      .footer-description{
        text-align: center !important;
      }
      .newsletter-col{
        display: none !important;
      }
      .copyright-col{
        width: 100% !important;
        text-align: center;
      }
      .disclosure p{
        font-size: 12px !important;
      }
      .copyright p{
        font-size: 12px !important;
      }

    /* Hide company-info-col on mobile when events-col is present, to keep 2 columns balanced */
    .row:has(.events-col) .company-info-col {
        display: none !important;
    }
    .row:not(:has(.events-col)) .company-info-col {
        display: block !important;
    }
    }
</style>
<!-- Footer <start> -->
<footer class="modern-footer">
    <div class="footer-main">
        <div class="container">
            <div class="row">
                <!-- Brand Section -->
                <div class="col-md-4 logo-col">
                    <div class="footer-brand">
                        <div class="footer-logo">
                            <!-- <h3>{{ $brandingSettings['site_name'] }}</h3> -->
                            @if($brandingSettings['site_logo_url'])
                                <img src="{{ $brandingSettings['site_logo_url'] }}" alt="{{ $brandingSettings['site_name'] }}" width="200" height="60" loading="lazy" decoding="async">
                            @else
                                <img src="{{ asset('assets/img/icons/logo.png') }}" alt="{{ $brandingSettings['site_name'] }}" width="200" height="60" loading="lazy" decoding="async">
                            @endif
                            <!-- <h3>{{ $brandingSettings['site_name'] }}</h3> -->
                        </div>
                        <p class="footer-description">
                            {{ $brandingSettings['site_tagline'] }}
                        </p>
                        <div class="social-links">
                            @if($socialSettings['facebook_url'])
                                <a href="{{ $socialSettings['facebook_url'] }}" target="_blank" class="social-link facebook" title="Facebook" aria-label="Follow us on Facebook">
                                    <svg viewBox="0 0 24 24" aria-hidden="true">
                                        <path d="M14.5 9H17V6h-2.5C12.57 6 11 7.57 11 10v2H9v4h2v7h4v-7h3l.5-4H15v-2c0-.55.45-1 1-1Z" fill="currentColor"/>
                                    </svg>
                                </a>
                            @endif
                            @if($socialSettings['twitter_url'])
                                <a href="{{ $socialSettings['twitter_url'] }}" target="_blank" class="social-link twitter" title="X (Twitter)" aria-label="Follow us on X (Twitter)">
                                    <svg viewBox="0 0 24 24" aria-hidden="true">
                                        <path d="M4 4h3l5.5 6.5L18 4h3l-6.5 7.5L21 20h-3l-5-5.8L8 20H5l6.7-7.5L4 4Z" fill="currentColor"/>
                                    </svg>
                                </a>
                            @endif
                            @if($socialSettings['instagram_url'])
                                <a href="{{ $socialSettings['instagram_url'] }}" target="_blank" class="social-link instagram" title="Instagram" aria-label="Follow us on Instagram">
                                    <svg viewBox="0 0 24 24" aria-hidden="true">
                                        <rect x="3" y="3" width="18" height="18" rx="5" ry="5" stroke="currentColor" stroke-width="2" fill="none"/>
                                        <circle cx="12" cy="12" r="3.5" stroke="currentColor" stroke-width="2" fill="none"/>
                                        <circle cx="17.2" cy="6.8" r="1.2" fill="currentColor"/>
                                    </svg>
                                </a>
                            @endif
                            @if($socialSettings['youtube_url'])
                                <a href="{{ $socialSettings['youtube_url'] }}" target="_blank" class="social-link youtube" title="YouTube" aria-label="Subscribe on YouTube">
                                    <svg viewBox="0 0 24 24" aria-hidden="true">
                                        <path d="M21.5 7.5c-.2-1.2-1.2-2.2-2.4-2.3C17.2 5 12 5 12 5s-5.2 0-7.1.2c-1.2.1-2.2 1.1-2.4 2.3C2.2 9.4 2 12 2 12s.2 2.6.5 4.5c.2 1.2 1.2 2.2 2.4 2.3 1.9.2 7.1.2 7.1.2s5.2 0 7.1-.2c1.2-.1 2.2-1.1 2.4-2.3.3-1.9.5-4.5.5-4.5s-.2-2.6-.5-4.5Zm-11.5 8V8.5l6 3.5-6 3.5Z" fill="currentColor"/>
                                    </svg>
                                </a>
                            @endif
                            @if($socialSettings['linkedin_url'])
                                <a href="{{ $socialSettings['linkedin_url'] }}" target="_blank" class="social-link linkedin" title="LinkedIn" aria-label="Connect on LinkedIn">
                                    <svg viewBox="0 0 24 24" aria-hidden="true">
                                        <path d="M6 9H3v12h3V9Zm-.5-4a1.5 1.5 0 1 0 0 3 1.5 1.5 0 0 0 0-3ZM21 21h-3v-6.5c0-1.5-.03-3.5-2.25-3.5-2.25 0-2.6 1.66-2.6 3.38V21h-3V9h2.9v1.64h.04c.4-.75 1.38-1.54 2.85-1.54 3.05 0 3.61 2.01 3.61 4.62V21Z" fill="currentColor"/>
                                    </svg>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Quick Links -->
                <div class="col-md-4 quick-links-col">
                    <div class="footer-section">
                        <h4 class="footer-title">Quick Links</h4>
                        <ul class="footer-links">
                            <li><a href="{{ route('top-discounts') }}">Top Discounts</a></li>
                            <li><a href="{{ route('categories') }}">Categories</a></li>
                            <li><a href="{{ route('events') }}">Events</a></li>
                            <li><a href="{{ route('student-discount') }}">Student Discount</a></li>
                            <li><a href="{{ route('all-brands') }}">All Brands</a></li>
                            <!-- <li><a href="{{ route('smash-voucher-codes') }}">Get Inspired</a></li> -->
                        </ul>
                    </div>
                </div>

                <!-- Dynamic Events -->
                @if($footerEvents->count() > 0)
                <div class="col-md-4 events-col">
                    <div class="footer-section">
                        <h4 class="footer-title">Special Events</h4>
                        <ul class="footer-links">
                            @foreach($footerEvents as $event)
                                <li><a href="{{ route('event.detail', $event->seo_url) }}">{{ $event->event_name }}</a></li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                @endif

                <!-- Company Info -->
                <div class="col-md-4 company-info-col">
                    <div class="footer-section">
                        <h4 class="footer-title">Company</h4>
                        <ul class="footer-links">
                            <li><a href="{{ route('about-us') }}">About Us</a></li>
                            <li><a href="{{ route('contact') }}">Contact Us</a></li>
                            <li><a href="{{ route('blog') }}">Blog</a></li>
                            <li><a href="{{ route('privacy-policy') }}">Privacy Policy</a></li>
                        </ul>
                    </div>
                </div>

                <!-- Newsletter -->
                <div class="col-md-4 newsletter-col">
                    <div class="footer-section">
                        <h4 class="footer-title">Stay Updated</h4>
                        <p class="newsletter-text">Get the latest deals and offers delivered to your inbox!</p>
                        <form id="footerNewsletterForm" class="newsletter-form">
                            @csrf
                            <div class="newsletter-input-group">
                                <label for="footerNewsletterEmail" class="sr-only" style="position: absolute; width: 1px; height: 1px; padding: 0; margin: -1px; overflow: hidden; clip: rect(0, 0, 0, 0); white-space: nowrap; border-width: 0;">Email address for newsletter subscription</label>
                                <input type="email" name="email" id="footerNewsletterEmail" placeholder="Enter your email" required aria-label="Email address for newsletter subscription">
                                <button type="submit" class="newsletter-btn" id="footerNewsletterBtn" aria-label="Subscribe to newsletter" title="Subscribe">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"/></svg>
                                </button>
                            </div>
                        </form>
                        <div id="footerNewsletterMessage" style="margin-top: 10px; display: none;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer Bottom -->
    <div class="footer-bottom">
        <div class="container">
            <div class="row align-items-center justify-content-between">
                <div class="col-md-6 copyright-col">
                    <div class="copyright">
                        <p class="text-center">&copy; {{ date('Y') }} {{ $brandingSettings['site_name'] }}. All rights reserved.</p>
                    </div>
                </div>
                <div class="col-md-6 col-sm-12 mb-2">
                    <div class="disclosure">
                        <p><strong>Disclosure:</strong> We may earn a commission when you make a purchase through our links.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>

<style>
/* Modern Footer Styles */
.modern-footer {
    background: #f5f5f5 !important;
    color: #333333 !important;
}

/* Ensure all text inside footer main section is dark for readability on light background */
.modern-footer .footer-main,
.modern-footer .footer-main *,
.modern-footer .footer-main p,
.modern-footer .footer-main span,
.modern-footer .footer-main div,
.modern-footer .footer-main a,
.modern-footer .footer-main li,
.modern-footer .footer-main h3,
.modern-footer .footer-main h4 {
    color: #333333 !important;
}

.footer-main {
    padding: 3rem 0 2rem;
}

.footer-brand {
    max-width: 300px;
}

.footer-logo {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-bottom: 1.5rem;
}

.footer-logo .logo-img {
    width: 50px;
    height: 50px;
    border-radius: 8px;
}

.footer-logo h3 {
    font-size: 1.5rem;
    font-weight: 700;
    color: #000;
    margin: 0;
}

.footer-description {
    /* Dark text on light background for readability */
    color: #333333 !important;
    line-height: 1.6;
    margin-bottom: 1rem;
    font-size: 0.95rem;
}

.social-links {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
}
.social-links svg{
    width: 18px;
    height: 18px;
    display: block;
}
.social-link {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    /* Dark icons on light background for readability */
    color: #333333 !important;
    background: rgba(0, 0, 0, 0.05);
    text-decoration: none;
    transition: all 0.3s ease;
    backdrop-filter: blur(10px);
}

.social-link:hover {
    transform: translateY(-2px);
    /* Dark color on hover for consistency */
    color: var(--primary-color, #2951c4) !important;
    background: rgba(var(--primary-color-rgb, 41, 81, 196), 0.1);
}

.social-link:focus-visible {
    outline: 3px solid var(--primary-color, #2951c4);
    outline-offset: 2px;
}

/* .social-link.facebook:hover { background: var(--primary-color, #2951c4); }
.social-link.twitter:hover { background: var(--primary-color, #2951c4); }
.social-link.instagram:hover { background: var(--primary-color, #2951c4); }
.social-link.youtube:hover { background: var(--primary-color, #2951c4); }
.social-link.linkedin:hover { background: var(--primary-color, #2951c4); } */

.footer-section {
    margin-bottom: 1rem;
}

.footer-title {
    font-size: 1.1rem;
    font-weight: 600;
    color: #000;
    margin-bottom: 1.5rem;
    position: relative;
}

.footer-title::after {
    content: '';
    position: absolute;
    bottom: -8px;
    left: 0;
    width: 30px;
    height: 2px;
    background: var(--primary-color, #2951c4) !important;
    border-radius: 1px;
}

.footer-links {
    list-style: none;
    padding: 0;
    margin: 0;
}

.footer-links li {
    margin-bottom: 0.75rem;
}

.footer-links a {
    /* Dark text on light background for readability */
    color: #333333 !important;
    text-decoration: none;
    font-size: 0.9rem;
    transition: all 0.3s ease;
    position: relative;
}

.footer-links a:hover {
    color: var(--primary-color, #2951c4);
    padding-left: 8px;
}

.footer-links a::before {
    content: '→';
    position: absolute;
    left: -15px;
    opacity: 0;
    transition: all 0.3s ease;
}

.footer-links a:hover::before {
    opacity: 1;
    left: -12px;
}

.newsletter-text {
    /* Dark text on light background for readability */
    color: #333333 !important;
    font-size: 0.9rem;
    margin-bottom: 1rem;
    line-height: 1.5;
}

.newsletter-form {
    margin-top: 1rem;
}

.newsletter-input-group {
    display: flex;
    background: rgba(255, 255, 255, 0.9);
    border-radius: 25px;
    overflow: hidden;
    backdrop-filter: blur(10px);
    border: 1px solid rgba(0, 0, 0, 0.1);
}

.newsletter-input-group input {
    flex: 1;
    padding: 12px 16px;
    border: none;
    background: var(--background-primary-color, #fff);
    color: var(--text-color, #333333);
    font-size: 0.9rem;
    outline: none;
}

.newsletter-input-group input::placeholder {
    color: var(--text-color, #666666);
}

.newsletter-btn {
    padding: 12px 16px;
    background: var(--primary-color, #2951c4) !important;
    border: none;
    color: #ffffff;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
}

.newsletter-btn:hover {
    filter: brightness(1.1);
    transform: scale(1.05);
}

.newsletter-btn:focus-visible,
.newsletter-input-group input:focus-visible {
    outline: 3px solid var(--primary-color, #2951c4);
    outline-offset: 2px;
}

.footer-bottom {
    background: var(--secondary-color, #000);
    padding: 1.5rem 0;
    border-top: 1px solid rgba(255, 255, 255, 0.1);
}

.copyright p {
    /* Ensure high contrast white text on dark footer background - WCAG AA compliant */
    color: #ffffff !important;
    font-size: 0.9rem;
    margin: 0;
    font-weight: 400;
}

.disclosure p {
    /* Ensure high contrast white text on dark footer background - WCAG AA compliant */
    color: #ffffff !important;
    font-size: 0.85rem;
    margin: 0;
    font-weight: 400;
    line-height: 1.5;
}

.disclosure strong {
    /* Ensure high contrast white text on dark footer background - WCAG AA compliant */
    color: #ffffff !important;
    font-weight: 700;
}
footer .row{
        gap: 0 !important;
        margin-top: 0px !important;
    }
    footer .row a{
        /* WCAG AA compliant: Ensure white text on black background for high contrast */
        color: #000 !important;
    font-size: 14px !important;
    min-width: 30px;
    /* height: auto !important; */
    display: inline-flex;
    /* justify-content: start; */
    }
    .footer-bottom .container .row{
        display: flex;
    justify-content: space-between;
    align-items: center;
    }
/* Bootstrap Responsive Adjustments */
@media (max-width: 768px) {
    .footer-main {
        padding: 2rem 1rem 1rem;
    }
    
    .social-links {
        justify-content: center;
    }
    
    .disclosure p {
        text-align: center;
    }
    
    .newsletter-input-group {
        flex-direction: column;
        border-radius: 12px;
    }
    
    .newsletter-input-group input {
        border-radius: 12px 12px 0 0;
    }
    
    .newsletter-btn {
        border-radius: 0 0 12px 12px;
    }
    .footer-logo{
        margin: 0 !important;
    }
    footer .row{
        gap: 0 !important;
    }
}

@media (max-width: 576px) {
    .footer-main {
        padding: 1.5rem 1rem 1rem;
    }
    footer .row{
        gap: 0 !important;
    }
    .newsletter-input-group input {
        padding: 12px 14px;
    }
    
    .social-links {
        gap: 0.5rem;
    }

}
</style>


<!-- Footer Newsletter AJAX (vanilla JS, no jQuery dependency to keep main thread light) -->
<script>
(function() {
    function showFooterMessage(message, type) {
        var messageDiv = document.getElementById('footerNewsletterMessage');
        if (!messageDiv) return;

        var color = type === 'success' ? '#27ae60' : '#e74c3c';
        var bgColor = type === 'success' ? '#d4edda' : '#f8d7da';
        var borderColor = type === 'success' ? '#c3e6cb' : '#f5c6cb';

        messageDiv.innerHTML =
            '<div style="color: ' + color + '; background: ' + bgColor + '; border: 1px solid ' + borderColor + '; padding: 10px; border-radius: 5px; font-size: 14px; font-weight: 500;">' +
            message +
            '</div>';
        messageDiv.style.display = 'block';

        setTimeout(function() {
            messageDiv.style.display = 'none';
        }, 5000);
    }

    function handleFooterNewsletterSubmit(e) {
        e.preventDefault();
        
        var form = document.getElementById('footerNewsletterForm');
        if (!form) return;

        var emailInput = document.getElementById('footerNewsletterEmail');
        var btn = document.getElementById('footerNewsletterBtn');

        if (!emailInput || !btn) return;

        var email = (emailInput.value || '').trim();
        if (!email) {
            showFooterMessage('Please enter your email address.', 'error');
            return;
        }
        
        // Basic email sanity check
        if (!email.includes('@') || !email.includes('.') || email.indexOf('@') > email.lastIndexOf('.')) {
            showFooterMessage('Please enter a valid email address.', 'error');
            return;
        }

        btn.disabled = true;
        btn.innerHTML =
            '<div style="width: 16px; height: 16px; border: 2px solid #fff; border-radius: 50%; border-top-color: transparent; animation: spin 1s linear infinite;"></div>';

        var csrfToken = document.querySelector('meta[name="csrf-token"]');
        var token = csrfToken ? csrfToken.getAttribute('content') : '';

        fetch('{{ route("newsletter.subscribe") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': token,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ email: email })
        })
            .then(function(response) {
                return response.json().catch(function() {
                    // If response is not JSON, still resolve with empty object
                    return {};
                });
            })
            .then(function(data) {
                if (data && data.success) {
                    showFooterMessage(data.message || 'Subscribed successfully.', 'success');
                    emailInput.value = '';
                } else {
                    showFooterMessage((data && data.message) || 'Something went wrong. Please try again.', 'error');
                }
            })
            .catch(function() {
                showFooterMessage('Something went wrong. Please try again.', 'error');
            })
            .finally(function() {
                btn.disabled = false;
                btn.innerHTML =
                    '<svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"/></svg>';
            });
    }
    
    function bootstrapNewsletter() {
        var form = document.getElementById('footerNewsletterForm');
        if (!form) return;
        form.addEventListener('submit', handleFooterNewsletterSubmit);
    }
    
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', bootstrapNewsletter);
    } else {
        bootstrapNewsletter();
    }
})();

// Details button toggle functionality - Universal
document.querySelectorAll('.ctb').forEach(btn => {
    btn.addEventListener('click', function () {
        const termsSection = this.closest('.cpn').querySelector('.ctc');
        if (termsSection) {
            const isVisible = termsSection.style.display !== 'none';
            termsSection.style.display = isVisible ? 'none' : 'block';
            
            // Update button text and icon
            if (isVisible) {
                this.innerHTML = 'Details';
            } else {
                this.innerHTML = 'Details <span style="transform: rotate(180deg); display: inline-block;">^</span>';
            }
        }
    });
});
</script>

<style>
/* Terms & Conditions Toggle - Universal Styles */
.ctb {
    background: #f3f4f6;
    border: 1px solid #d1d5db;
    border-radius: 6px;
    padding: 8px 12px;
    font-size: 14px;
    color: #374151;
    cursor: pointer;
    transition: all 0.2s ease;
    display: flex;
    align-items: center;
    gap: 5px;
}

.ctb:hover {
    background: #e5e7eb;
    border-color: #9ca3af;
}

.ctc {
    margin-top: 15px;
    padding: 20px;
    background: #ffffff;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.ctc h3 {
    margin: 0 0 15px 0;
    font-size: 16px;
    font-weight: 700;
    color: #111827;
}

.ctc .dyncnt {
    color: #374151;
    font-size: 14px;
    line-height: 1.6;
}

.ctc .dyncnt ol,
.ctc .dyncnt ul {
    margin: 10px 0;
    padding-left: 20px;
}

.ctc .dyncnt li {
    margin: 5px 0;
}

.ctc .dyncnt a {
    color: #059669;
    text-decoration: underline;
}

.ctc .dyncnt a:hover {
    color: #047857;
}
</style>
<!-- Footer <end> -->

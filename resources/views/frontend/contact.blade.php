@extends('frontend.layouts.app')

@section('title', 'Contact Us - Hotsavinghub | Get in Touch')
@section('description', 'Contact Hotsavinghub for support, partnerships, or feedback. We\'re here to help you save more and provide the best discount code experience.')
@section('keywords', 'Contact Hotsavinghub, customer support, partnership inquiries, feedback, help center')

@push('styles')
<link rel="preload" href="{{ asset('frontend_assets/css/fonts.css') }}" as="style" crossorigin>
<link rel="preload" href="{{ asset('frontend_assets/css/store.css') }}" as="style" crossorigin>
<link rel="stylesheet" href="{{ asset('frontend_assets/css/fonts.css') }}" crossorigin>
<link rel="stylesheet" href="{{ asset('frontend_assets/css/store.css') }}" as="style" crossorigin>
<style>
/* Dynamic Color Variables */
:root {
    --contact-primary-color: {{ $settings['primary_color'] ?? '#2951c4' }};
    --contact-primary-light: {{ $settings['primary_color'] ?? '#2951c4' }}20;
    --contact-primary-lighter: {{ $settings['primary_color'] ?? '#2951c4' }}10;
    --contact-primary-dark: {{ $settings['primary_color'] ?? '#2951c4' }}CC;
    --contact-secondary-color: {{ $settings['secondary_color'] ?? '#ff4444' }};
    --contact-accent-color: {{ $settings['primary_color'] ?? '#2951c4' }};
    --contact-text-color: {{ $settings['text_color'] ?? '#2d3748' }};
    --contact-heading-color: {{ $settings['text_color'] ?? '#1a202c' }};
    --contact-background-color: {{ $settings['background_primary_color'] ?? '#ffffff' }};
    --contact-card-background: {{ $settings['background_primary_color'] ?? '#ffffff' }};
}

/* Contact Us Page Styles */
.contact-hero {
    background:
        linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)),
        url('{{ asset('frontend_assets/images/search-bg.webp') }}') center/cover no-repeat;
    color: white;
    padding: 80px 0;
    text-align: center;
    position: relative;
    overflow: hidden;
}

.contact-hero::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background:
        radial-gradient(circle at 20% 80%, var(--contact-primary-light) 0%, transparent 50%),
        radial-gradient(circle at 80% 20%, var(--contact-primary-light) 0%, transparent 50%),
        url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="white" opacity="0.1"/><circle cx="75" cy="75" r="1" fill="white" opacity="0.1"/><circle cx="50" cy="10" r="0.5" fill="white" opacity="0.1"/><circle cx="10" cy="60" r="0.5" fill="white" opacity="0.1"/><circle cx="90" cy="40" r="0.5" fill="white" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
    opacity: 0.3;
}

.contact-hero-content {
    position: relative;
    z-index: 2;
    max-width: 800px;
    margin: 0 auto;
    padding: 0 20px;
}

.contact-hero h1 {
    font-size: 3.5rem;
    font-weight: 700;
    margin-bottom: 20px;
    text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
}

.contact-hero p {
    font-size: 1.3rem;
    margin-bottom: 30px;
    opacity: 0.95;
    line-height: 1.6;
}

.contact-main {
    padding: 80px 0;
    background: var(--contact-background-color);
}

.contact-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 20px;
}

.contact-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 60px;
    align-items: start;
}

.contact-info {
    background: var(--contact-primary-lighter);
    padding: 40px;
    border-radius: 15px;
    height: fit-content;
    border: 1px solid var(--contact-primary-light);
}

.contact-info h2 {
    font-size: 2rem;
    font-weight: 700;
    color: var(--contact-heading-color);
    margin-bottom: 30px;
    position: relative;
}

.contact-info h2::after {
    content: '';
    position: absolute;
    bottom: -10px;
    left: 0;
    width: 60px;
    height: 4px;
    background: linear-gradient(135deg, var(--contact-primary-color) 0%, var(--contact-primary-dark) 100%);
    border-radius: 2px;
}

.info-item {
    display: flex;
    align-items: center;
    margin-bottom: 25px;
    padding: 20px;
    background: var(--contact-card-background);
    border-radius: 10px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.08);
    transition: transform 0.3s ease;
    border: 1px solid var(--contact-primary-lighter);
}

.info-item:hover {
    transform: translateY(-5px);
    border-color: var(--contact-primary-light);
}

.info-icon {
    width: 50px;
    height: 50px;
    background: var(--contact-primary-color);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 20px;
    font-size: 1.5rem;
    color: white;
}

.info-content h3 {
    font-size: 1.2rem;
    font-weight: 600;
    color: var(--contact-heading-color);
    margin-bottom: 5px;
}

.info-content p {
    color: var(--contact-text-color);
    margin: 0;
    font-size: 0.95rem;
}

.contact-form-section {
    background: var(--contact-card-background);
    padding: 40px;
    border-radius: 15px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    border: 1px solid var(--contact-primary-lighter);
}

.contact-form-section h2 {
    font-size: 2rem;
    font-weight: 700;
    color: var(--contact-heading-color);
    margin-bottom: 30px;
    position: relative;
}

.contact-form-section h2::after {
    content: '';
    position: absolute;
    bottom: -10px;
    left: 0;
    width: 60px;
    height: 4px;
    background: linear-gradient(135deg, var(--contact-primary-color) 0%, var(--contact-primary-dark) 100%);
    border-radius: 2px;
}

.contact-form {
    display: flex;
    flex-direction: column;
    gap: 25px;
}

.form-group {
    display: flex;
    flex-direction: column;
}

.form-group label {
    font-weight: 600;
    color: var(--contact-heading-color);
    margin-bottom: 8px;
    font-size: 0.95rem;
}

.form-group input,
.form-group textarea,
.form-group select {
    padding: 15px;
    border: 2px solid var(--contact-primary-lighter);
    border-radius: 8px;
    font-size: 1rem;
    transition: all 0.3s ease;
    background: var(--contact-card-background);
}

.form-group input:focus,
.form-group textarea:focus,
.form-group select:focus {
    outline: none;
    border-color: var(--contact-primary-color);
    box-shadow: 0 0 0 3px var(--contact-primary-light);
}

.form-group textarea {
    resize: vertical;
    min-height: 120px;
}

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
}

.submit-btn {
    background: linear-gradient(135deg, var(--contact-primary-color) 0%, var(--contact-primary-dark) 100%);
    color: white;
    padding: 15px 30px;
    border: none;
    border-radius: 8px;
    font-size: 1.1rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    align-self: flex-start;
}

.submit-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 20px var(--contact-primary-light);
}

.faq-section {
    background: var(--contact-primary-lighter);
    padding: 80px 0;
}

.faq-container {
    max-width: 1000px;
    margin: 0 auto;
    padding: 0 20px;
}

.faq-section h2 {
    text-align: center;
    font-size: 2.5rem;
    font-weight: 700;
    color: #2c3e50;
    margin-bottom: 50px;
    position: relative;
}

.faq-section h2::after {
    content: '';
    position: absolute;
    bottom: -10px;
    left: 50%;
    transform: translateX(-50%);
    width: 60px;
    height: 4px;
    background: linear-gradient(135deg, var(--primary-color) 0%, rgb(102, 102, 102) 100%);
    border-radius: 2px;
}

.faq-grid {
    display: grid;
    gap: 20px;
}

.faq-item {
    background: white;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 5px 15px rgba(0,0,0,0.08);
}

.faq-question {
    padding: 25px;
    background: white;
    border: none;
    width: 100%;
    text-align: left;
    font-size: 1.1rem;
    font-weight: 600;
    color: #2c3e50;
    cursor: pointer;
    display: flex;
    justify-content: space-between;
    align-items: center;
    transition: background 0.3s ease;
}

.faq-question:hover {
    background: #f8f9fa;
}

.faq-question::after {
    content: '+';
    font-size: 1.5rem;
    color: var(--primary-color);
    transition: transform 0.3s ease;
}

.faq-item.active .faq-question::after {
    transform: rotate(45deg);
}

.faq-answer {
    padding: 0 25px;
    max-height: 0;
    overflow: hidden;
    transition: all 0.3s ease;
}

.faq-item.active .faq-answer {
    padding: 0 25px 25px;
    max-height: 200px;
}

.faq-answer p {
    color: #6c757d;
    line-height: 1.6;
    margin: 0;
}

.office-hours {
    background: white;
    padding: 30px;
    border-radius: 10px;
    margin-top: 30px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.08);
}

.office-hours h3 {
    font-size: 1.3rem;
    font-weight: 600;
    color: #2c3e50;
    margin-bottom: 15px;
}

.office-hours p {
    color: #6c757d;
    margin: 5px 0;
}

/* Responsive Design */
@media (max-width: 992px) {
    .contact-grid {
        grid-template-columns: 1fr;
        gap: 40px;
    }

    .form-row {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 768px) {
    .contact-hero h1 {
        font-size: 2.5rem;
    }

    .contact-hero p {
        font-size: 1.1rem;
    }

    .contact-main {
        padding: 60px 0;
    }

    .contact-info,
    .contact-form-section {
        padding: 30px 20px;
    }

    .info-item {
        padding: 15px;
    }

    .info-icon {
        width: 40px;
        height: 40px;
        font-size: 1.2rem;
        margin-right: 15px;
    }

    .faq-section {
        padding: 60px 0;
    }

    .faq-section h2 {
        font-size: 2rem;
    }
}

@media (max-width: 480px) {
    .contact-hero {
        padding: 60px 0;
    }

    .contact-hero h1 {
        font-size: 2rem;
    }

    .contact-info h2,
    .contact-form-section h2 {
        font-size: 1.5rem;
    }

    .info-item {
        flex-direction: column;
        text-align: center;
    }

    .info-icon {
        margin-right: 0;
        margin-bottom: 15px;
    }
}
</style>
@endpush

@section('content')
<style>:root{--p:#2951c4;--p-h:#1e3fa3;--p-l:rgba(41,81,196,0.08);--t-d:#0f172a;--t-l:#475569;--w:#ffffff;--tr:all 0.5s cubic-bezier(0.16,1,0.3,1)}.hsh-ch{position:relative;padding:9rem 1.5rem;background:var(--w);overflow:hidden;display:flex;justify-content:center;font-family:Inter,system-ui,sans-serif}.hsh-ch::before{content:'';position:absolute;top:-20%;right:-10%;width:600px;height:600px;background:radial-gradient(circle,var(--p-l) 0%,rgba(255,255,255,0) 70%);border-radius:50%;z-index:1}.hsh-ch::after{content:'';position:absolute;bottom:-10%;left:-5%;width:400px;height:400px;background:radial-gradient(circle,rgba(41,81,196,0.04) 0%,rgba(255,255,255,0) 70%);border-radius:50%;z-index:1}.hsh-ch-c{position:relative;z-index:10;max-width:900px;width:100%;text-align:center}.hsh-ch-b{display:inline-flex;align-items:center;gap:8px;padding:8px 16px;background:var(--p-l);color:var(--p);border-radius:100px;font-size:0.85rem;font-weight:700;margin-bottom:2rem;border:1px solid rgba(41,81,196,0.1)}.hsh-ch h1{font-size:clamp(3rem,8vw,5rem);font-weight:950;color:var(--t-d);letter-spacing:-0.05em;line-height:1;margin-bottom:1.5rem}.hsh-ch h1 span{color:var(--p);position:relative}.hsh-ch p{font-size:clamp(1.1rem,2vw,1.4rem);color:var(--t-l);line-height:1.6;max-width:700px;margin:0 auto 3rem}.hsh-ch-g{display:grid;grid-template-columns:repeat(3,1fr);gap:1.5rem;margin-top:2rem}.hsh-ci{background:var(--w);padding:2rem;border-radius:24px;border:1px solid #f1f5f9;display:flex;flex-direction:column;align-items:center;transition:var(--tr);box-shadow:0 10px 30px rgba(0,0,0,0.02)}.hsh-ci:hover{transform:translateY(-10px);border-color:var(--p);box-shadow:0 20px 40px rgba(41,81,196,0.1)}.hsh-ci-ic{width:50px;height:50px;background:var(--p-l);border-radius:14px;display:flex;align-items:center;justify-content:center;font-size:1.5rem;margin-bottom:1rem;color:var(--p)}@media (max-width:768px){.hsh-ch{padding:6rem 1rem}.hsh-ch-g{grid-template-columns:1fr;gap:1rem}.hsh-ci{padding:1.5rem}}</style>
<!-- Contact Hero Section -->
<section class="hsh-ch">
    <div class="hsh-ch-c">
        <div class="hsh-ch-b">
            <span class="hsh-ch-dot" style="width:8px;height:8px;background:var(--p);border-radius:50%;display:inline-block;animation:pulse 2s infinite"></span>
            UK Support Live
        </div>

        <h1>Let’s Start a <span>Conversation</span></h1>

        <p>
            Have a question about a specific voucher? Interested in a partnership?
            Our dedicated team at Hotsavinghub is ready to assist you in maximizing your savings.
        </p>

        <div class="hsh-ch-g">
            <div class="hsh-ci">
                <div class="hsh-ci-ic">💬</div>
                <h3 style="font-size:1.1rem;font-weight:700;margin-bottom:0.5rem">Support</h3>
                <p style="font-size:0.9rem;margin:0">Help with coupons</p>
            </div>

            <div class="hsh-ci" style="transition-delay: 0.1s;">
                <div class="hsh-ci-ic">🤝</div>
                <h3 style="font-size:1.1rem;font-weight:700;margin-bottom:0.5rem">Partnerships</h3>
                <p style="font-size:0.9rem;margin:0">List your store</p>
            </div>

            <div class="hsh-ci" style="transition-delay: 0.2s;">
                <div class="hsh-ci-ic">📍</div>
                <h3 style="font-size:1.1rem;font-weight:700;margin-bottom:0.5rem">Location</h3>
                <p style="font-size:0.9rem;margin:0">London, United Kingdom</p>
            </div>
        </div>
    </div>
</section>

<style>
@keyframes pulse {
    0% { transform: scale(0.95); opacity: 0.8; }
    70% { transform: scale(1.1); opacity: 0.4; }
    100% { transform: scale(0.95); opacity: 0.8; }
}
</style>
<style>:root{--primary:#2951c4;--primary-glow:rgba(41,81,196,0.12);--text-main:#0f172a;--text-sub:#475569;--white:#ffffff;--border:#e2e8f0;--radius-lg:24px;--radius-md:16px;--shadow-soft:0 10px 30px -5px rgba(0,0,0,0.04),0 4px 6px -2px rgba(0,0,0,0.01);--transition:all 0.4s cubic-bezier(0.23,1,0.32,1)}.contact-main{padding:6rem 0;background-color:var(--white);font-family:system-ui,-apple-system,sans-serif}.contact-container{max-width:1300px;margin:0 auto;padding:0 1.5rem}.contact-grid{display:grid;grid-template-columns:380px 1fr;gap:4rem;align-items:start}.contact-info{display:grid;gap:1.5rem}.contact-info h2{font-size:2.25rem;font-weight:900;color:var(--text-main);letter-spacing:-0.03em;margin-bottom:1rem}.info-card{background:var(--white);padding:2rem;border-radius:var(--radius-md);border:1px solid var(--border);transition:var(--transition);display:flex;gap:1.5rem;box-shadow:var(--shadow-soft)}.info-card:hover{transform:translateY(-8px);border-color:var(--primary);box-shadow:0 20px 40px -10px rgba(41,81,196,0.15)}.info-icon-wrapper{width:56px;height:56px;background:var(--primary-glow);border-radius:14px;display:flex;align-items:center;justify-content:center;color:var(--primary);font-size:1.4rem;flex-shrink:0;transition:var(--transition)}.info-card:hover .info-icon-wrapper{background:var(--primary);color:var(--white);transform:rotate(-5deg)}.info-content h3{font-size:1.15rem;font-weight:800;color:var(--text-main);margin:0 0 0.5rem}.info-content p{font-size:0.95rem;color:var(--text-sub);line-height:1.6;margin:0}.contact-form-section{background:var(--white);padding:3.5rem;border-radius:var(--radius-lg);border:1px solid var(--border);box-shadow:var(--shadow-soft);position:relative;overflow:hidden}.contact-form-section h2{font-size:2.25rem;font-weight:900;color:var(--text-main);letter-spacing:-0.03em;margin-bottom:2.5rem}.form-row{display:grid;grid-template-columns:1fr 1fr;gap:1.5rem;margin-bottom:1.5rem}.form-group{display:flex;flex-direction:column;gap:0.6rem}.form-group label{font-size:0.9rem;font-weight:700;color:var(--text-main);padding-left:4px}.form-group input,.form-group select,.form-group textarea{padding:14px 18px;border-radius:12px;border:1px solid var(--border);font-size:1rem;background:#f8fafc;transition:var(--transition)}.form-group input:focus,.form-group select:focus,.form-group textarea:focus{outline:none;border-color:var(--primary);background:var(--white);box-shadow:0 0 0 4px var(--primary-glow)}.form-group textarea{min-height:160px;resize:none}.submit-btn{width:100%;margin-top:1rem;padding:18px;background:var(--primary);color:var(--white);border:none;border-radius:12px;font-weight:800;font-size:1.1rem;cursor:pointer;transition:var(--transition);display:flex;align-items:center;justify-content:center;gap:10px;box-shadow:0 10px 20px -5px rgba(41,81,196,0.4)}.submit-btn:hover{filter:brightness(1.1);transform:translateY(-2px);box-shadow:0 15px 30px -5px rgba(41,81,196,0.5)}.alert{padding:1.25rem;border-radius:12px;margin-bottom:2rem;font-weight:600;display:flex;align-items:center;gap:10px}.alert-success{background:#f0fdf4;color:#166534;border:1px solid #bbf7d0}.alert-danger{background:#fef2f2;color:#991b1b;border:1px solid #fecaca}@media (max-width:1100px){.contact-grid{grid-template-columns:1fr}.contact-info{grid-template-columns:repeat(2,1fr)}.contact-form-section{padding:2.5rem}}@media (max-width:768px){.contact-info{grid-template-columns:1fr}.form-row{grid-template-columns:1fr}.contact-main{padding:4rem 0}}</style>
<!-- Contact Main Section -->
<div class="contact-main">
    <div class="contact-container">
        <div class="contact-grid">

            @php
                $contactSettings = \App\Helpers\SettingsHelper::getContact();
            @endphp

            <aside class="contact-info">
                <h2>Get in Touch</h2>

                <div class="info-card">
                    <div class="info-icon-wrapper">
                        <i class="fa-solid fa-paper-plane"></i>
                    </div>
                    <div class="info-content">
                        <h3>Email Us</h3>
                        <p>{{ $contactSettings['contact_email'] }}</p>
                        <p>{{ $contactSettings['partnership_email'] }}</p>
                    </div>
                </div>

                <div class="info-card">
                    <div class="info-icon-wrapper">
                        <i class="fa-solid fa-phone-volume"></i>
                    </div>
                    <div class="info-content">
                        <h3>Call Us</h3>
                        <p>{{ $contactSettings['contact_phone'] }}</p>
                        <p>{{ $contactSettings['business_hours'] }}</p>
                    </div>
                </div>

                <div class="info-card">
                    <div class="info-icon-wrapper">
                        <i class="fa-solid fa-location-dot"></i>
                    </div>
                    <div class="info-content">
                        <h3>Visit Us</h3>
                        <p>{{ $contactSettings['contact_address'] }}</p>
                    </div>
                </div>

                <div class="info-card">
                    <div class="info-icon-wrapper">
                        <i class="fa-solid fa-comments"></i>
                    </div>
                    <div class="info-content">
                        <h3>Live Chat</h3>
                        <p>Available 24/7</p>
                        <p>UK based support team</p>
                    </div>
                </div>
            </aside>

            <section class="contact-form-section">
                <h2>Send us a Message</h2>

                @if(session('success'))
                    <div class="alert alert-success" role="alert">
                        <i class="fa-solid fa-circle-check"></i>
                        {{ session('success') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger" role="alert">
                        <i class="fa-solid fa-circle-exclamation"></i>
                        <ul style="margin:0; padding:0; list-style:none;">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form class="contact-form" action="{{ route('contact.submit') }}" method="POST">
                    @csrf

                    <div class="form-row">
                        <div class="form-group">
                            <label for="first_name">First Name *</label>
                            <input type="text" id="first_name" name="first_name" value="{{ old('first_name') }}" placeholder="Enter first name" required>
                        </div>
                        <div class="form-group">
                            <label for="last_name">Last Name *</label>
                            <input type="text" id="last_name" name="last_name" value="{{ old('last_name') }}" placeholder="Enter last name" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="email">Email Address *</label>
                            <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="email@example.com" required>
                        </div>
                        <div class="form-group">
                            <label for="phone">Phone Number</label>
                            <input type="tel" id="phone" name="phone" value="{{ old('phone') }}" placeholder="+44 000 0000 000">
                        </div>
                    </div>

                    <div class="form-group" style="margin-bottom: 1.5rem;">
                        <label for="subject">Inquiry Subject *</label>
                        <select id="subject" name="subject" required>
                            <option value="">Select a subject</option>
                            <option value="general" {{ old('subject') == 'general' ? 'selected' : '' }}>General Inquiry</option>
                            <option value="support" {{ old('subject') == 'support' ? 'selected' : '' }}>Technical Support</option>
                            <option value="partnership" {{ old('subject') == 'partnership' ? 'selected' : '' }}>Partnership Opportunity</option>
                            <option value="feedback" {{ old('subject') == 'feedback' ? 'selected' : '' }}>Feedback</option>
                            <option value="other" {{ old('subject') == 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                    </div>

                    <div class="form-group" style="margin-bottom: 2rem;">
                        <label for="message">Your Message *</label>
                        <textarea id="message" name="message" placeholder="How can we help you today?" required>{{ old('message') }}</textarea>
                    </div>

                    <button type="submit" class="submit-btn">
                        <span>Send Secure Message</span>
                        <i class="fa-solid fa-paper-plane"></i>
                    </button>
                </form>
            </section>
        </div>
    </div>
</div>

<!-- FAQ Section -->
<div class="faq-section">
    <div class="faq-container">
        <h2>Frequently Asked Questions</h2>

        <div class="faq-grid">
            <div class="faq-item">
                <button class="faq-question">How do I report a broken coupon code?</button>
                <div class="faq-answer">
                    <p>If you find a coupon code that doesn't work, please contact our support team with the store name and coupon code. We'll verify and update it within 24 hours.</p>
                </div>
            </div>

            <div class="faq-item">
                <button class="faq-question">How can I become a partner store?</button>
                <div class="faq-answer">
                    <p>We'd love to partner with you! Please email {{ $contactSettings['partnership_email'] }} with your store details, and our partnership team will get back to you within 2 business days.</p>
                </div>
            </div>

            <!-- <div class="faq-item">
                <button class="faq-question">Do you have a mobile app?</button>
                <div class="faq-answer">
                    <p>No! Our mobile app is available for both iOS and Android. You can download it from the App Store or Google Play Store to get exclusive mobile-only deals.</p>
                </div>
            </div> -->

            <div class="faq-item">
                <button class="faq-question">How often are coupon codes updated?</button>
                <div class="faq-answer">
                    <p>We update our coupon codes multiple times daily. Our system automatically checks for new codes and removes expired ones to ensure you always have access to working discounts.</p>
                </div>
            </div>

            <div class="faq-item">
                <button class="faq-question">Can I suggest new stores to add?</button>
                <div class="faq-answer">
                    <p>Absolutely! We welcome suggestions for new stores. Please email us with the store name, website, and any available discount codes, and we'll consider adding them to our platform.</p>
                </div>
            </div>

            <div class="faq-item">
                <button class="faq-question">How do I unsubscribe from emails?</button>
                <div class="faq-answer">
                    <p>You can unsubscribe from our emails by clicking the unsubscribe link at the bottom of any email, or by contacting our support team. We respect your privacy and will process your request immediately.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// FAQ Toggle Functionality
document.addEventListener('DOMContentLoaded', function() {
    const faqItems = document.querySelectorAll('.faq-item');

    faqItems.forEach(item => {
        const question = item.querySelector('.faq-question');

        question.addEventListener('click', () => {
            // Close other open items
            faqItems.forEach(otherItem => {
                if (otherItem !== item) {
                    otherItem.classList.remove('active');
                }
            });

            // Toggle current item
            item.classList.toggle('active');
        });
    });
});
</script>

@endsection

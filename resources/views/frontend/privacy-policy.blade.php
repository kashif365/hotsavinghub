@extends('frontend.layouts.app')

@section('title', 'Privacy Policy - Hotsavinghub | Your Privacy Matters')
@section('description', 'Learn how Hotsavinghub protects your privacy and handles your personal information. Our comprehensive privacy policy explains our data practices.')
@section('keywords', 'Privacy Policy, Hotsavinghub, data protection, privacy rights, GDPR compliance, personal information')

@push('styles')
<link rel="preload" href="{{ asset('frontend_assets/css/fonts.css') }}" as="style" crossorigin>
<link rel="preload" href="{{ asset('frontend_assets/css/store.css') }}" as="style" crossorigin>
<link rel="stylesheet" href="{{ asset('frontend_assets/css/fonts.css') }}" crossorigin>
<link rel="stylesheet" href="{{ asset('frontend_assets/css/store.css') }}" as="style" crossorigin>
<style>
/* Dynamic Color Variables */
:root {
    --privacy-primary-color: {{ $settings['primary_color'] ?? '#2951c4' }};
    --privacy-primary-light: {{ $settings['primary_color'] ?? '#2951c4' }}20;
    --privacy-primary-lighter: {{ $settings['primary_color'] ?? '#2951c4' }}10;
    --privacy-primary-dark: {{ $settings['primary_color'] ?? '#2951c4' }}CC;
    --privacy-secondary-color: {{ $settings['secondary_color'] ?? '#ff4444' }};
    --privacy-accent-color: {{ $settings['primary_color'] ?? '#2951c4' }};
    --privacy-text-color: {{ $settings['text_color'] ?? '#2d3748' }};
    --privacy-heading-color: {{ $settings['text_color'] ?? '#1a202c' }};
    --privacy-background-color: {{ $settings['background_primary_color'] ?? '#ffffff' }};
    --privacy-card-background: {{ $settings['background_primary_color'] ?? '#ffffff' }};
}

/* Privacy Policy Page Styles */
.privacy-hero {
    background:
        linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)),
        url('{{ asset('frontend_assets/images/search-bg.webp') }}') center/cover no-repeat;
    color: white;
    padding: 80px 0;
    text-align: center;
    position: relative;
    overflow: hidden;
}

.privacy-hero::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background:
        radial-gradient(circle at 20% 80%, var(--privacy-primary-light) 0%, transparent 50%),
        radial-gradient(circle at 80% 20%, var(--privacy-primary-light) 0%, transparent 50%),
        url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="white" opacity="0.1"/><circle cx="75" cy="75" r="1" fill="white" opacity="0.1"/><circle cx="50" cy="10" r="0.5" fill="white" opacity="0.1"/><circle cx="10" cy="60" r="0.5" fill="white" opacity="0.1"/><circle cx="90" cy="40" r="0.5" fill="white" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
    opacity: 0.3;
}

.privacy-hero-content {
    position: relative;
    z-index: 2;
    max-width: 800px;
    margin: 0 auto;
    padding: 0 20px;
}

.privacy-hero h1 {
    font-size: 3.5rem;
    font-weight: 700;
    margin-bottom: 20px;
    text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
}

.privacy-hero p {
    font-size: 1.3rem;
    margin-bottom: 30px;
    opacity: 0.95;
    line-height: 1.6;
}

.privacy-main {
    padding: 80px 0;
    background: var(--privacy-background-color);
}

.privacy-container {
    max-width: 1000px;
    margin: 0 auto;
    padding: 0 20px;
}

.privacy-intro {
    background: var(--privacy-primary-lighter);
    padding: 40px;
    border-radius: 15px;
    margin-bottom: 50px;
    border-left: 5px solid var(--privacy-primary-color);
}

.privacy-intro h2 {
    font-size: 2rem;
    font-weight: 700;
    color: var(--privacy-heading-color);
    margin-bottom: 20px;
}

.privacy-intro p {
    color: var(--privacy-text-color);
    line-height: 1.6;
    font-size: 1.1rem;
    margin-bottom: 15px;
}

.privacy-intro .last-updated {
    font-weight: 600;
    color: var(--privacy-primary-color);
    font-size: 1rem;
}

.privacy-section {
    margin-bottom: 50px;
    background: var(--privacy-card-background);
    padding: 40px;
    border-radius: 15px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.08);
    border: 1px solid var(--privacy-primary-lighter);
}

.privacy-section h2 {
    font-size: 1.8rem;
    font-weight: 700;
    color: var(--privacy-heading-color);
    margin-bottom: 25px;
    position: relative;
    padding-bottom: 15px;
}

.privacy-section h2::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    width: 60px;
    height: 3px;
    background: linear-gradient(135deg, var(--privacy-primary-color) 0%, var(--privacy-primary-dark) 100%);
    border-radius: 2px;
}

.privacy-section h3 {
    font-size: 1.4rem;
    font-weight: 600;
    color: var(--privacy-heading-color);
    margin: 30px 0 15px;
}

.privacy-section p {
    color: var(--privacy-text-color);
    line-height: 1.7;
    margin-bottom: 20px;
    font-size: 1rem;
}

.privacy-section ul {
    color: var(--privacy-text-color);
    line-height: 1.7;
    margin-bottom: 20px;
    padding-left: 25px;
}

.privacy-section li {
    margin-bottom: 10px;
}

.privacy-section strong {
    color: var(--privacy-heading-color);
    font-weight: 600;
}

.privacy-section .highlight {
    background: var(--privacy-primary-lighter);
    padding: 20px;
    border-radius: 8px;
    border-left: 4px solid var(--privacy-primary-color);
    margin: 20px 0;
}

.privacy-section .highlight p {
    margin: 0;
    color: var(--privacy-primary-dark);
    font-weight: 500;
}

.contact-info {
    background: linear-gradient(135deg, var(--privacy-primary-lighter) 0%, var(--privacy-primary-light) 100%);
    padding: 30px;
    border-radius: 10px;
    margin-top: 30px;
    text-align: center;
}

.contact-info h3 {
    color: var(--privacy-heading-color);
    margin-bottom: 15px;
}

.contact-info p {
    margin: 5px 0;
    color: var(--privacy-text-color);
}

.contact-info a {
    color: var(--privacy-primary-color);
    text-decoration: none;
    font-weight: 600;
}

.contact-info a:hover {
    text-decoration: underline;
}

.table-of-contents {
    background: var(--privacy-primary-lighter);
    padding: 30px;
    border-radius: 15px;
    margin-bottom: 50px;
}

.table-of-contents h2 {
    font-size: 1.8rem;
    font-weight: 700;
    color: #2c3e50;
    margin-bottom: 25px;
    text-align: center;
}

.toc-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.toc-list li {
    margin-bottom: 10px;
}

.toc-list a {
    color: #6c757d;
    text-decoration: none;
    font-weight: 500;
    padding: 8px 15px;
    display: block;
    border-radius: 8px;
    transition: all 0.3s ease;
}

.toc-list a:hover {
    background: var(--primary-color, #2951c4);
    color: white;
    transform: translateX(5px);
}

/* Responsive Design */
@media (max-width: 768px) {
    .privacy-hero h1 {
        font-size: 2.5rem;
    }

    .privacy-hero p {
        font-size: 1.1rem;
    }

    .privacy-main {
        padding: 60px 0;
    }

    .privacy-container {
        padding: 0 15px;
    }

    .privacy-section,
    .privacy-intro {
        padding: 25px 20px;
    }

    .privacy-section h2 {
        font-size: 1.5rem;
    }

    .privacy-section h3 {
        font-size: 1.2rem;
    }
}

@media (max-width: 480px) {
    .privacy-hero {
        padding: 60px 0;
    }

    .privacy-hero h1 {
        font-size: 2rem;
    }

    .privacy-section,
    .privacy-intro {
        padding: 20px 15px;
    }

    .privacy-section h2 {
        font-size: 1.3rem;
    }

    .table-of-contents {
        padding: 20px 15px;
    }
}
</style>
@endpush

@section('content')

<!-- Privacy Policy Hero Section -->
<div class="privacy-hero">
    <div class="privacy-hero-content">
        <h1>Privacy Policy</h1>
        <p>Your privacy is important to us. This policy explains how Hotsavinghub collects, uses, and protects your personal information.</p>
    </div>
</div>

<!-- Privacy Policy Main Content -->
<div class="privacy-main">
    <div class="privacy-container">

        <!-- Introduction -->
        <div class="privacy-intro">
            <h2>Introduction</h2>
            <p>At Hotsavinghub, we are committed to protecting your privacy and ensuring the security of your personal information. This Privacy Policy explains how we collect, use, disclose, and safeguard your information when you visit our website or use our services.</p>
            <p>By using our website, you consent to the data practices described in this policy. If you do not agree with the terms of this Privacy Policy, please do not use our services.</p>
            <p class="last-updated">Last Updated: {{ date('F d, Y') }}</p>
        </div>

        <!-- Table of Contents -->
        <div class="table-of-contents">
            <h2>Table of Contents</h2>
            <ul class="toc-list">
                <li><a href="#information-collection">1. Information We Collect</a></li>
                <li><a href="#information-use">2. How We Use Your Information</a></li>
                <li><a href="#information-sharing">3. Information Sharing and Disclosure</a></li>
                <li><a href="#data-security">4. Data Security</a></li>
                <li><a href="#cookies">5. Cookies and Tracking Technologies</a></li>
                <li><a href="#third-party">6. Third-Party Services</a></li>
                <li><a href="#user-rights">7. Your Rights and Choices</a></li>
                <li><a href="#data-retention">8. Data Retention</a></li>
                <li><a href="#children-privacy">9. Children's Privacy</a></li>
                <li><a href="#international-transfers">10. International Data Transfers</a></li>
                <li><a href="#policy-changes">11. Changes to This Policy</a></li>
                <li><a href="#contact-us">12. Contact Us</a></li>
            </ul>
        </div>

        <!-- Information We Collect -->
        <div class="privacy-section" id="information-collection">
            <h2>1. Information We Collect</h2>

            <h3>Personal Information</h3>
            <p>We may collect personal information that you voluntarily provide to us, including:</p>
            <ul>
                <li><strong>Contact Information:</strong> Name, email address, phone number, and mailing address</li>
                <li><strong>Account Information:</strong> Username, password, and profile information</li>
                <li><strong>Communication Data:</strong> Messages, feedback, and correspondence with us</li>
                <li><strong>Newsletter Subscriptions:</strong> Email preferences and subscription status</li>
            </ul>

            <h3>Automatically Collected Information</h3>
            <p>When you visit our website, we automatically collect certain information, including:</p>
            <ul>
                <li><strong>Usage Data:</strong> Pages visited, time spent on site, and click patterns</li>
                <li><strong>Device Information:</strong> IP address, browser type, operating system, and device identifiers</li>
                <li><strong>Location Data:</strong> General geographic location based on IP address</li>
                <li><strong>Cookies and Similar Technologies:</strong> Data stored on your device for functionality and analytics</li>
            </ul>
        </div>

        <!-- How We Use Your Information -->
        <div class="privacy-section" id="information-use">
            <h2>2. How We Use Your Information</h2>

            <p>We use the information we collect for various purposes, including:</p>

            <h3>Service Provision</h3>
            <ul>
                <li>Providing and maintaining our discount code services</li>
                <li>Processing your requests and transactions</li>
                <li>Delivering personalized content and recommendations</li>
                <li>Improving our website functionality and user experience</li>
            </ul>

            <h3>Communication</h3>
            <ul>
                <li>Sending newsletters and promotional materials (with your consent)</li>
                <li>Responding to your inquiries and providing customer support</li>
                <li>Sending important updates about our services</li>
                <li>Conducting surveys and gathering feedback</li>
            </ul>

            <h3>Analytics and Improvement</h3>
            <ul>
                <li>Analyzing website usage patterns and trends</li>
                <li>Conducting research and development</li>
                <li>Testing new features and functionality</li>
                <li>Measuring the effectiveness of our marketing campaigns</li>
            </ul>

            <div class="highlight">
                <p><strong>Legal Basis:</strong> We process your personal information based on your consent, legitimate business interests, and compliance with legal obligations.</p>
            </div>
        </div>

        <!-- Information Sharing -->
        <div class="privacy-section" id="information-sharing">
            <h2>3. Information Sharing and Disclosure</h2>

            <p>We do not sell, trade, or rent your personal information to third parties. However, we may share your information in the following circumstances:</p>

            <h3>Service Providers</h3>
            <p>We may share information with trusted third-party service providers who assist us in operating our website and providing services, including:</p>
            <ul>
                <li>Email service providers for newsletter delivery</li>
                <li>Analytics providers for website performance monitoring</li>
                <li>Payment processors for transaction handling</li>
                <li>Customer support platforms for assistance</li>
            </ul>

            <h3>Legal Requirements</h3>
            <p>We may disclose your information if required by law or in response to:</p>
            <ul>
                <li>Legal processes, such as court orders or subpoenas</li>
                <li>Government investigations or regulatory requests</li>
                <li>Protection of our rights, property, or safety</li>
                <li>Prevention of fraud or illegal activities</li>
            </ul>

            <h3>Business Transfers</h3>
            <p>In the event of a merger, acquisition, or sale of assets, your information may be transferred as part of the transaction, with appropriate safeguards in place.</p>
        </div>

        <!-- Data Security -->
        <div class="privacy-section" id="data-security">
            <h2>4. Data Security</h2>

            <p>We implement appropriate technical and organizational measures to protect your personal information against unauthorized access, alteration, disclosure, or destruction. These measures include:</p>

            <ul>
                <li><strong>Encryption:</strong> Data transmission using SSL/TLS encryption</li>
                <li><strong>Access Controls:</strong> Limited access to personal information on a need-to-know basis</li>
                <li><strong>Regular Updates:</strong> Keeping our systems and software up to date</li>
                <li><strong>Monitoring:</strong> Continuous monitoring for security threats and vulnerabilities</li>
                <li><strong>Training:</strong> Regular security training for our staff</li>
            </ul>

            <div class="highlight">
                <p><strong>Important:</strong> While we strive to protect your information, no method of transmission over the internet or electronic storage is 100% secure. We cannot guarantee absolute security.</p>
            </div>
        </div>

        <!-- Cookies -->
        <div class="privacy-section" id="cookies">
            <h2>5. Cookies and Tracking Technologies</h2>

            <p>We use cookies and similar tracking technologies to enhance your browsing experience and analyze website usage. Cookies are small text files stored on your device that help us:</p>

            <ul>
                <li>Remember your preferences and settings</li>
                <li>Provide personalized content and recommendations</li>
                <li>Analyze website traffic and user behavior</li>
                <li>Improve website performance and functionality</li>
            </ul>

            <h3>Types of Cookies We Use</h3>
            <ul>
                <li><strong>Essential Cookies:</strong> Necessary for website functionality</li>
                <li><strong>Analytics Cookies:</strong> Help us understand how visitors use our site</li>
                <li><strong>Marketing Cookies:</strong> Used to deliver relevant advertisements</li>
                <li><strong>Preference Cookies:</strong> Remember your choices and settings</li>
            </ul>

            <p>You can control cookie settings through your browser preferences. However, disabling cookies may affect website functionality.</p>
        </div>

        <!-- Third-Party Services -->
        <div class="privacy-section" id="third-party">
            <h2>6. Third-Party Services</h2>

            <p>Our website may contain links to third-party websites or integrate with third-party services. These services have their own privacy policies, and we are not responsible for their practices.</p>

            <h3>Common Third-Party Services</h3>
            <ul>
                <li><strong>Social Media Platforms:</strong> Facebook, Twitter, Instagram, LinkedIn</li>
                <li><strong>Analytics Services:</strong> Google Analytics, Facebook Pixel</li>
                <li><strong>Advertising Networks:</strong> Google Ads, Facebook Ads</li>
                <li><strong>Payment Processors:</strong> PayPal, Stripe, and other payment gateways</li>
            </ul>

            <p>We encourage you to review the privacy policies of these third-party services before providing any personal information.</p>
        </div>

        <!-- User Rights -->
        <div class="privacy-section" id="user-rights">
            <h2>7. Your Rights and Choices</h2>

            <p>Depending on your location, you may have certain rights regarding your personal information:</p>

            <h3>Access and Portability</h3>
            <ul>
                <li>Request access to your personal information</li>
                <li>Receive a copy of your data in a portable format</li>
                <li>Verify the accuracy of your information</li>
            </ul>

            <h3>Correction and Updates</h3>
            <ul>
                <li>Correct inaccurate or incomplete information</li>
                <li>Update your personal details</li>
                <li>Modify your communication preferences</li>
            </ul>

            <h3>Deletion and Restriction</h3>
            <ul>
                <li>Request deletion of your personal information</li>
                <li>Restrict processing of your data</li>
                <li>Object to certain types of processing</li>
            </ul>

            <h3>Communication Preferences</h3>
            <ul>
                <li>Unsubscribe from marketing emails</li>
                <li>Opt-out of promotional communications</li>
                <li>Manage newsletter subscriptions</li>
            </ul>

            <p>To exercise these rights, please contact us using the information provided in the "Contact Us" section.</p>
        </div>

        <!-- Data Retention -->
        <div class="privacy-section" id="data-retention">
            <h2>8. Data Retention</h2>

            <p>We retain your personal information only for as long as necessary to fulfill the purposes outlined in this Privacy Policy, unless a longer retention period is required or permitted by law.</p>

            <h3>Retention Periods</h3>
            <ul>
                <li><strong>Account Information:</strong> Until account deletion or 3 years of inactivity</li>
                <li><strong>Newsletter Subscriptions:</strong> Until you unsubscribe</li>
                <li><strong>Customer Support Records:</strong> 3 years from last interaction</li>
                <li><strong>Analytics Data:</strong> Aggregated data may be retained indefinitely</li>
                <li><strong>Legal Compliance:</strong> As required by applicable laws and regulations</li>
            </ul>

            <p>When we no longer need your personal information, we will securely delete or anonymize it.</p>
        </div>

        <!-- Children's Privacy -->
        <div class="privacy-section" id="children-privacy">
            <h2>9. Children's Privacy</h2>

            <p>Our services are not intended for children under 13 years of age. We do not knowingly collect personal information from children under 13. If you are a parent or guardian and believe your child has provided us with personal information, please contact us immediately.</p>

            <p>If we discover that we have collected personal information from a child under 13, we will take steps to delete such information promptly.</p>
        </div>

        <!-- International Transfers -->
        <div class="privacy-section" id="international-transfers">
            <h2>10. International Data Transfers</h2>

            <p>Your information may be transferred to and processed in countries other than your own. We ensure that such transfers comply with applicable data protection laws and implement appropriate safeguards.</p>

            <h3>Safeguards Include</h3>
            <ul>
                <li>Standard contractual clauses approved by relevant authorities</li>
                <li>Adequacy decisions by data protection authorities</li>
                <li>Certification schemes and codes of conduct</li>
                <li>Binding corporate rules for intra-group transfers</li>
            </ul>
        </div>

        <!-- Policy Changes -->
        <div class="privacy-section" id="policy-changes">
            <h2>11. Changes to This Privacy Policy</h2>

            <p>We may update this Privacy Policy from time to time to reflect changes in our practices, technology, legal requirements, or other factors. We will notify you of any material changes by:</p>

            <ul>
                <li>Posting the updated policy on our website</li>
                <li>Sending email notifications to registered users</li>
                <li>Displaying prominent notices on our website</li>
                <li>Updating the "Last Updated" date at the top of this policy</li>
            </ul>

            <p>We encourage you to review this Privacy Policy periodically to stay informed about how we protect your information.</p>
        </div>
        @php
                $contactSettings = \App\Helpers\SettingsHelper::getContact();
            @endphp
        <!-- Contact Us -->
        <div class="privacy-section" id="contact-us">
            <h2>12. Contact Us</h2>

            <p>If you have any questions, concerns, or requests regarding this Privacy Policy or our data practices, please contact us:</p>

            <div class="contact-info">
                <h3>Hotsavinghub Privacy Team</h3>
                <p><strong>Email:</strong> <a href="mailto:{{ $contactSettings['partnership_email'] }}">{{ $contactSettings['partnership_email'] }}</a></p>
                <p><strong>General Support:</strong> <a href="mailto:{{ $contactSettings['contact_email'] }}">{{ $contactSettings['contact_email'] }}</a></p>
                <p><strong>Address:</strong> {{ $contactSettings['contact_address'] }}</p>
                <p><strong>Phone:</strong> {{ $contactSettings['contact_phone'] }}</p>
            </div>

            <p>We will respond to your inquiry within 30 days of receipt. For data protection requests, we may require additional verification to ensure the security of your information.</p>
        </div>

    </div>
</div>

@endsection

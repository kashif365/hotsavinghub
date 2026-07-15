@extends('frontend.layouts.app')

@section('title', 'Student Discount Codes & Offers | Hotsavinghub')
@section('description', 'Get exclusive student discount codes and voucher codes from top UK brands. Save money on fashion, electronics, food, and more with verified student offers.')
@section('keywords', 'student discount, student discount codes, student voucher codes, student offers UK, student deals, university discounts')

@section('content')
<style>
    .sd-hero {
        background: #f7f7f2;
        border-bottom: 1px solid #eee;
    }
    .sd-hero .container {
        display: grid;
        grid-template-columns: 240px 1fr;
        gap: 24px;
        align-items: center;
        padding: 16px 0;
        max-width: 1290px;
    }
    .sd-hero-illustration {
        width: 100%;
        height: 120px;
        /* background: url('{{ asset('public/frontend_assets/images/student-hero.png') }}') center/contain no-repeat; */
    }
    .sd-hero h1 {
        margin: 0 0 6px 0;
        font-size: 28px;
        font-weight: 800;
        color: #2c3e50;
    }
    .sd-hero p { margin: 0; color: #6b7280; }

    .sd-section {
        background: #fbfbfb;
        padding: 32px 0;
    }
    .sd-section .container { max-width: 1290px; }
    .sd-cat-title {
        font-weight: 700;
        font-size: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 12px;
        color: #000;
        margin: 40px 0 30px 0;
        text-decoration: none;
        transition: color 0.3s ease;
    }
    .sd-cat-title:hover {
        color: var(--primary-color);
    }
    .sd-cat-title::after {
        content: ">";
        width: 24px;
        height: 24px;
        background: #000;
        color: #fff;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 15px;
        font-weight: bold;
    }
    .sd-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 14px;
    }
    .sd-card {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 10px;
        overflow: hidden;
        transition: box-shadow .2s ease, transform .2s ease;
        text-decoration: none;
        display: block;
    }
    .sd-card:hover {
        box-shadow: 0 8px 18px rgba(0,0,0,.08);
        transform: translateY(-2px);
    }

    /* Logo section (top) */
    .sd-logo {
        display: flex;
        align-items: center;
        justify-content: center;
        background: #f9f9f9;
    }
    .sd-logo img {
            width: 100%;
        height: 200px;
        object-fit: cover;
    }
    .sd-logo-placeholder {
        font-weight: 700;
        color: #6b7280;
        font-size: 32px;
    }

    /* Divider line */
    .sd-card-divider {
        border-top: 1px solid #eee;
        margin: 0 14px;
    }

    /* Content section (bottom) */
    .sd-text-content {
        padding: 14px;
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    /* Title row with store name and DEAL badge */
    .sd-title-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .sd-store {
        font-weight: 600;
        color: #111827;
        font-size: 16px;
        line-height: 1.2;
    }
    .sd-deal-badge {
        background: var(--primary-color);
        color: white;
        padding: 3px 8px;
        border-radius: 5px;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
    }

    /* Offer description */
    .sd-offer-description {
        font-size: 14px;
        color: #374151;
        line-height: 1.4;
        font-weight: 600;
        margin-bottom: 8px;
    }

    .sd-code-preview {
        font-size: 12px;
        color: #059669;
        font-weight: 700;
        background: #f0fdf4;
        padding: 4px 8px;
        border-radius: 4px;
        display: inline-block;
        border: 1px solid #bbf7d0;
    }

    /* Banner Image Responsive Fix */
    .event-banner-image {
        background-size: contain !important;
        background-position: center center !important;
        background-repeat: no-repeat !important;
        min-height: 500px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    /* Ensure image shows completely at all zoom levels */
    @media (min-width: 1300px) {
        .event-banner-image {
            min-height: 300px !important;
        }
    }
    @media (min-width: 1100px) {
        .event-banner-image {
            min-height: 200px !important;
        }
    }

    @media (max-width: 1199px) and (min-width: 1024px) {
        .event-banner-image {
            background-size: cover !important;
        }
    }

    @media (max-width: 1023px) and (min-width: 769px) {
        .event-banner-image {
            min-height: 400px !important;
            background-size: cover !important;
        }
    }

    @media (max-width: 768px) {
        .event-banner-image {
            height: 350px !important;
            background-size: cover !important;
        }
        .banner-content img{
            width: 50% !important;
            height: 100px;
        }
    }

    @media (max-width: 480px) {
        .event-banner-image {
            height: 300px !important;
        }
    }

    @media (max-width: 360px) {
        .event-banner-image {
            height: 250px !important;
        }
        .banner-content img{
            width: 60% !important;
            height: 100px;
        }
    }

    @media (max-width: 1024px) { .sd-grid { grid-template-columns: repeat(3, 1fr); } }
    @media (max-width: 768px)  {
        .sd-grid { grid-template-columns: repeat(2, 1fr); }
        .sd-hero .container{grid-template-columns:1fr;}
        .sd-hero-illustration{display:none;}
        .banner-content h1 { font-size: 32px !important; }
        .banner-content p { font-size: 16px !important; }
    }
</style>

<!-- Hero -->
<div class="event-banner-section">
    <div class="event-banner-image" style="background-image: url('{{ asset('uploads/1764706195_692f47935c9d4_student.webp') }}'); background-size: contain; background-position: center; background-repeat: no-repeat; min-height: 450px !important; width: 100%; position: relative; bottom: 10px;">
        <div class="banner-overlay" style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; display: flex; align-items: center; justify-content: center;">
            <div class="banner-content" style="text-align: center; color: white; padding: 20px;">
                <h1 style="font-size: 48px; font-weight: 800; margin: 0 0 16px 0; color: #000;"><span style="color: var(--primary-color);">Hotsavinghub</span> Student Discounts</h1>
                <p style="font-size: 20px; margin: 0;  text-transform: capitalize; color: var(--primary-color);">Get exclusive student discount codes & vouchers across top brands</p>
                <!-- <img src="{{ asset('uploads/student_beans.png') }}" alt="Student Discount" style="width: 40%; height: 100px;"> -->
            </div>
        </div>
    </div>
</div>

<!-- Category Sections -->
<div class="sd-section">
    <div class="container">
        @forelse($studentCategories as $category)
            @if(($category->studentCoupons ?? [])->count() > 0)
                <div class="category-section" style="margin-bottom: 40px;">
                    <div class="category-header" style="text-align: center;">
                    <a href="{{ route('category', $category->seo_url) }}" class="sd-cat-title">{{ $category->category_name }}</a>
                    </div>
                    <div class="sd-grid" style="margin-bottom: 22px;">
                        @foreach(($category->studentCoupons ?? []) as $coupon)
                            <a class="sd-card" href="{{ route('store', $coupon->store->seo_url) }}?highlight={{ $coupon->id }}" data-coupon-id="{{ $coupon->id }}">
                                <div class="sd-logo">
                                    @if($coupon->store->store_logo)
                                        <img src="{{ asset($coupon->store->store_logo) }}" alt="{{ $coupon->store->store_name }}" width="80" height="80" loading="lazy" decoding="async" />
                                    @else
                                        <span class="sd-logo-placeholder">{{ strtoupper(substr($coupon->store->store_name,0,2)) }}</span>
                                    @endif
                                </div>
                                <div class="sd-card-divider"></div>
                                <div class="sd-text-content">
                                    <div class="sd-title-row">
                                        <div class="sd-store">{{ $coupon->store->store_name }}</div>
                                        <div class="sd-deal-badge">{{ $coupon->coupon_code ? 'CODE' : 'DEAL' }}</div>
                                    </div>
                                    <div class="sd-offer-description">{{ $coupon->coupon_title }}</div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        @empty
            <div class="no-discounts" style="text-align: center; padding: 60px 20px; background: #f8f9fa; border-radius: 12px; margin: 40px 0;">
                <h3 style="color: #6c757d; margin-bottom: 16px;">No Student Discounts Available</h3>
                <p style="color: #6c757d; margin: 0;">Please check back later for new student discount offers.</p>
            </div>
        @endforelse
    </div>
@endsection

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle student discount card clicks
    document.querySelectorAll('.sd-card[data-coupon-id]').forEach(card => {
        card.addEventListener('click', function(e) {
            const couponId = this.getAttribute('data-coupon-id');
            const url = this.getAttribute('href');

            // Store coupon ID in sessionStorage for highlighting
            sessionStorage.setItem('highlightCoupon', couponId);
            sessionStorage.setItem('highlightUntilClick', 'true');

            // Navigate to store page
            window.location.href = url;
        });
    });
});
</script>



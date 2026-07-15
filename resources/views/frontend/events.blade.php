@extends('frontend.layouts.app')

@section('title', 'Special Events & Promotions | Hotsavinghub')
@section('description', 'Discover exclusive discount codes and voucher codes for special events and promotions. Save money during Black Friday, Christmas, New Year, and other major sales events.')
@section('keywords', 'event promotions, Black Friday deals, Christmas discounts, holiday sales, seasonal offers, event voucher codes')

@push('styles')
<link rel="stylesheet" href="{{ asset('frontend_assets/css/store.css') }}">
<style>
/* Events Layout - Matching Image Design */
.Sec.bg {
    background-color: #F2F0E6;
    padding: 20px 0;
}

.Wrp {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 20px;
}

.bx {
    background-color: #fff;
    border-radius: 15px;
    padding: 30px;
    margin: 20px 0;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.ttl {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 25px;
    border-bottom: 1px solid #e0e0e0;
    padding-bottom: 15px;
}

.ttl h3 {
    font-size: 28px;
    font-weight: bold;
    color: #000;
    margin: 0;
}

.ttl h3 a {
    color: #000;
    text-decoration: none;
}

.ttl a {
    color: #000;
    text-decoration: none;
    font-weight: 500;
}

.ttl a:hover {
    color: #4a0c98;
}

/* Event Grid */
.event-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 25px;
    margin-top: 20px;
}

.event-card-link {
    text-decoration: none;
    color: inherit;
    display: block;
}

.event-card-link:hover {
    text-decoration: none;
    color: inherit;
}

.event-card {
    background: #fff;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    border: 1px solid #e0e0e0;
}

.event-card-link:hover .event-card {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
}

.event-image {
    width: 100%;
    height: 200px;
    object-fit: cover;
}

.event-content {
    padding: 20px;
}

.event-title {
    font-size: 20px;
    font-weight: bold;
    color: #000;
    margin-bottom: 10px;
    line-height: 1.3;
}

.event-type {
    display: inline-block;
    background: var(--primary-color, #2951c4);
    color: white;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 500;
    margin-bottom: 10px;
}

.event-dates {
    display: flex;
    gap: 15px;
    margin-bottom: 15px;
    font-size: 14px;
    color: #666;
}

.event-date {
    display: flex;
    align-items: center;
    gap: 5px;
}

.event-description {
    color: #666;
    font-size: 14px;
    line-height: 1.5;
    margin-bottom: 15px;
}

.event-coupons {
    margin-top: 15px;
    padding-top: 15px;
    border-top: 1px solid #e0e0e0;
}

.event-coupons h4 {
    font-size: 16px;
    font-weight: 600;
    color: #000;
    margin-bottom: 10px;
}

.coupon-list {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
}

.coupon-item {
    background: #f8f9fa;
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    padding: 8px 12px;
    font-size: 12px;
    color: #666;
    display: inline-block;
    margin: 2px;
}

.no-events {
    text-align: center;
    padding: 60px 20px;
    color: #666;
}

.no-events h3 {
    font-size: 24px;
    margin-bottom: 10px;
    color: #000;
}

.no-events p {
    font-size: 16px;
    margin-bottom: 20px;
}

.btn-primary {
    background: #4a0c98;
    border: none;
    padding: 12px 24px;
    border-radius: 8px;
    color: white;
    text-decoration: none;
    font-weight: 500;
    transition: background 0.3s ease;
}

.btn-primary:hover {
    background: #3a0a7a;
    color: white;
    text-decoration: none;
}

/* Responsive Design */
@media (max-width: 768px) {
    .Wrp {
        padding: 0 15px;
    }

    .bx {
        padding: 20px;
        margin: 15px 0;
    }

    .ttl h3 {
        font-size: 24px;
    }

    .event-grid {
        grid-template-columns: 1fr;
        gap: 20px;
    }

    .event-image {
        height: 150px;
    }

    .event-content {
        padding: 15px;
    }

    .event-title {
        font-size: 18px;
    }

    .event-dates {
        flex-direction: column;
        gap: 8px;
    }
}

@media (max-width: 480px) {
    .ttl {
        flex-direction: column;
        align-items: flex-start;
        gap: 10px;
    }

    .event-grid {
        gap: 15px;
    }
}
</style>
@endpush

@section('content')
<style>:root{--p:#2951c4;--p-d:#1a368e;--w:#ffffff;--g:#f1f5f9;--t:#0f172a;--t-l:#64748b;--rad:16px;--sh:0 10px 15px -3px rgba(0,0,0,0.1);--tr:all 0.3s cubic-bezier(0.4,0,0.2,1)}.ev-sec{padding:60px 20px;background:var(--w);font-family:'Inter',system-ui,sans-serif}.ev-wrp{max-width:1280px;margin:0 auto}.ev-hd{display:flex;justify-content:space-between;align-items:center;margin-bottom:40px;flex-wrap:wrap;gap:20px}.ev-hd h3{font-size:2rem;font-weight:800;color:var(--t);margin:0;letter-spacing:-0.5px}.ev-back{color:var(--p);font-weight:600;text-decoration:none;display:inline-flex;align-items:center;gap:8px;padding:10px 20px;background:var(--g);border-radius:100px;transition:var(--tr)}.ev-back:hover{background:var(--p);color:var(--w)}.ev-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(340px,1fr));gap:32px}.ev-c{background:var(--w);border:1px solid #e2e8f0;border-radius:var(--rad);overflow:hidden;transition:var(--tr);display:flex;flex-direction:column;text-decoration:none;position:relative}.ev-c:hover{transform:translateY(-8px);box-shadow:0 20px 25px -5px rgba(41,81,196,0.15);border-color:var(--p)}.ev-img-w{position:relative;width:100%;aspect-ratio:16/9;overflow:hidden;background:var(--g)}.ev-img{width:100%;height:100%;object-fit:cover;transition:transform 0.5s ease}.ev-c:hover .ev-img{transform:scale(1.05)}.ev-tag{position:absolute;top:16px;left:16px;background:rgba(255,255,255,0.95);color:var(--p);padding:6px 14px;border-radius:8px;font-size:0.75rem;font-weight:800;text-transform:uppercase;backdrop-filter:blur(4px);box-shadow:0 4px 6px -1px rgba(0,0,0,0.1);z-index:2}.ev-body{padding:24px;flex:1;display:flex;flex-direction:column}.ev-tt{font-size:1.25rem;font-weight:700;color:var(--t);margin:0 0 12px;line-height:1.4}.ev-desc{color:var(--t-l);font-size:0.95rem;line-height:1.6;margin-bottom:20px;flex:1}.ev-act{margin-top:auto;color:var(--p);font-weight:700;font-size:0.9rem;display:flex;align-items:center;gap:8px}.ev-no{text-align:center;padding:80px 20px;background:var(--g);border-radius:var(--rad);grid-column:1/-1}.ev-no h3{font-size:1.5rem;margin-bottom:10px;color:var(--t)}.ev-btn{display:inline-block;background:var(--p);color:var(--w);padding:14px 28px;border-radius:12px;text-decoration:none;font-weight:600;margin-top:20px;transition:var(--tr)}.ev-btn:hover{background:var(--p-d)}@media(max-width:768px){.ev-grid{grid-template-columns:1fr}.ev-hd{flex-direction:column;align-items:flex-start}.ev-hd h3{font-size:1.75rem}}</style>

<section class="ev-sec">
    <div class="ev-wrp">
        <div class="ev-hd">
            <h3><i class="fa-solid fa-calendar-star" style="color:var(--p); margin-right:10px;"></i> Special Events</h3>
            <a href="{{ route('home') }}" class="ev-back">
                <i class="fa-solid fa-arrow-left-long"></i> Back to Deals
            </a>
        </div>

        <div class="ev-grid">
            @if($events->count() > 0)
                @foreach($events as $event)
                <a href="{{ route('event.detail', $event->seo_url) }}" class="ev-c" aria-label="View {{ $event->event_name }}">
                    <div class="ev-img-w">
                        @if($event->event_type)
                            <span class="ev-tag"><i class="fa-solid fa-bolt"></i> {{ $event->event_type }}</span>
                        @endif

                        @if($event->front_image)
                            <img src="{{ asset($event->front_image) }}" alt="{{ $event->event_name }}" class="ev-img" loading="lazy" width="400" height="225" decoding="async">
                        @else
                            <div class="ev-img" style="display:flex;align-items:center;justify-content:center;background:linear-gradient(45deg, #2951c4, #1a368e);color:white;font-weight:800;font-size:1.5rem;">
                                {{ substr($event->event_name, 0, 1) }}
                            </div>
                        @endif
                    </div>

                    <div class="ev-body">
                        <h3 class="ev-tt">{{ $event->event_name }}</h3>
                        @if($event->event_short_content)
                        <div class="ev-desc">
                            {{ Str::limit($event->event_short_content, 100) }}
                        </div>
                        @endif
                        <span class="ev-act">Explore Offers <i class="fa-solid fa-arrow-right"></i></span>
                    </div>
                </a>
                @endforeach
            @endif

            <a href="{{ route('student-discount') }}" class="ev-c">
                <div class="ev-img-w">
                    <span class="ev-tag" style="background:#fff; color:#10b981;"><i class="fa-solid fa-graduation-cap"></i> Exclusive</span>
                    <img src="{{ asset('uploads/1764704640_692f4180c2b75_gradient-student-discount-label-23-2150601755.webp') }}" alt="Student Discount" class="ev-img" loading="lazy" width="400" height="225">
                </div>
                <div class="ev-body">
                    <h3 class="ev-tt">Student Discounts</h3>
                    <div class="ev-desc">
                        Unlock exclusive savings for students. Verified codes for top brands available now.
                    </div>
                    <span class="ev-act">Get Verified <i class="fa-solid fa-check-circle"></i></span>
                </div>
            </a>

            @if($events->count() == 0)
            <div class="ev-no">
                <i class="fa-solid fa-box-open" style="font-size:48px; color:#cbd5e1; margin-bottom:20px;"></i>
                <h3>No Seasonal Events Active</h3>
                <p>Check back later for Black Friday, Cyber Monday, and Holiday specials.</p>
                <a href="{{ route('home') }}" class="ev-btn">Browse Today's Coupons</a>
            </div>
            @endif
        </div>
    </div>
</section>
@endsection

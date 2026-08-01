<section class="hsg-guide" aria-labelledby="hsg-guide-title">
    <div class="hsg-container">
        
        <section class="hsg-advice" aria-labelledby="hsg-advice-title">
            <div class="hsg-section-heading">
                <span class="hsg-eyebrow">Practical ways to spend less</span>
                <h2 id="hsg-advice-title">Make Every Order Work Harder</h2>
            </div>
            <div class="hsg-advice-grid">
                <article>
                    <div class="hsg-icon" aria-hidden="true">A</div>
                    <h3>Automotive Savings Made Easier</h3>
                    <p>Vehicle maintenance can be expensive throughout the year. Before buying parts, compare available discounts on accessories, detailing supplies, batteries, maintenance products, replacement parts and tyres. The right promotion may reduce repair or replacement costs.</p>
                </article>

                <article>
                    <div class="hsg-icon" aria-hidden="true">S</div>
                    <h3>Enjoy Better Shipping Offers</h3>
                    <p>Delivery charges can significantly increase an order total, especially for lower-cost products. Look for free-shipping codes, minimum-spend delivery offers and special-day coupons. Combining product and shipping discounts can create greater overall savings.</p>
                </article>

                <article>
                    <div class="hsg-icon" aria-hidden="true">C</div>
                    <h3>Online Shopping Made More Convenient</h3>
                    <p>Today's consumers want convenience and value. An organised online coupon finder lets visitors discover promotions from hundreds of brands on a computer, tablet or phone without manually searching retailer websites.</p>
                </article>

                <article>
                    <div class="hsg-icon" aria-hidden="true">V</div>
                    <h3>Voucher Codes for Everyday Shopping</h3>
                    <p>Retailers continue to provide voucher codes for percentage discounts, Buy One Get One offers, gifts, free delivery, exclusive member pricing and seasonal campaigns. Checking frequently updated vouchers can lead to better overall value.</p>
                </article>
            </div>
        </section>

        <section class="hsg-blog-section" aria-labelledby="hsg-blog-title">
            <div class="hsg-section-heading">
                <span class="hsg-eyebrow">Fresh from the blog</span>
                <h2 id="hsg-blog-title">Latest Blog Posts</h2>
                <p>Guides, deal roundups and money-saving tips from the Hot Saving Hub team.</p>
            </div>

            @php $blogPosts = ($featuredBlogs ?? collect())->take(5)->values(); @endphp

            @if($blogPosts->count() > 0)
                <div class="hsg-blog-bento">
                    @foreach($blogPosts as $i => $blog)
                        @php
                            $isFeature = $i === 2;
                            $tagColor = $blog->category->color ?? null;
                        @endphp
                        <a href="{{ route('blog.show', $blog->slug) }}" class="hsg-blog-card @if($isFeature) hsg-blog-card--feature @endif">
                            <div class="hsg-blog-media">
                                @if($blog->featured_image)
                                    <img src="{{ asset($blog->featured_image) }}" alt="{{ $blog->title }}" loading="lazy">
                                @else
                                    <div class="hsg-blog-placeholder" aria-hidden="true">📝</div>
                                @endif
                                @if($blog->category)
                                    <span class="hsg-blog-tag" @if($tagColor) style="--tag-color: {{ $tagColor }};" @endif>
                                        <span class="hsg-blog-tag-dot"></span>{{ $blog->category->name }}
                                    </span>
                                @endif
                            </div>
                            <div class="hsg-blog-info">
                                <span class="hsg-blog-date">{{ $blog->created_at->format('F jS, Y') }}</span>
                                <h3 class="hsg-blog-post-title">{{ $blog->title }}</h3>
                            </div>
                        </a>
                    @endforeach
                </div>
            @else
                <div class="hsg-blog-empty">
                    <p>New articles are on the way — check back soon.</p>
                </div>
            @endif
        </section>
    </div>
</section>



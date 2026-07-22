@if(($homeContentBlocks ?? collect())->count() > 0)
<div class="home-extra-content-section">
    <div class="container">
        @foreach($homeContentBlocks as $block)
            @if(filled($block->content) && trim(strip_tags($block->content)) !== '')
                <div class="home-extra-content-block">
                    {!! $block->content !!}
                </div>
            @endif
        @endforeach
    </div>
</div>

<style>
.home-extra-content-section {
    padding: 3.5rem 0;
    background: #ffffff;
}

.home-extra-content-section .container {
    max-width: var(--container-max, 1280px);
    margin: 0 auto;
    padding: 0 24px;
}

.home-extra-content-block {
    background: #f8f9fa;
    border-radius: var(--radius-lg, 20px);
    padding: 32px 40px;
    line-height: 1.8;
    color: #374151;
}

.home-extra-content-block + .home-extra-content-block {
    margin-top: 1.5rem;
}

.home-extra-content-block h1,
.home-extra-content-block h2,
.home-extra-content-block h3,
.home-extra-content-block h4,
.home-extra-content-block h5,
.home-extra-content-block h6 {
    color: #1f2937;
    font-weight: 700;
    margin-top: 1.5rem;
    margin-bottom: 1rem;
    line-height: 1.3;
}

.home-extra-content-block h1 { font-size: 2rem; }
.home-extra-content-block h2 { font-size: 1.6rem; }
.home-extra-content-block h3 { font-size: 1.35rem; }
.home-extra-content-block h4 { font-size: 1.15rem; }

.home-extra-content-block p {
    margin-bottom: 1.25rem;
    font-size: 1.0625rem;
    color: #4b5563;
}

.home-extra-content-block ul,
.home-extra-content-block ol {
    margin-bottom: 1.25rem;
    padding-left: 1.75rem;
}

.home-extra-content-block li {
    margin-bottom: 0.6rem;
    font-size: 1.0625rem;
    color: #4b5563;
}

.home-extra-content-block a {
    color: var(--primary-color);
    text-decoration: underline;
    transition: color 0.3s ease;
}

.home-extra-content-block a:hover {
    color: var(--primary-hover);
}

.home-extra-content-block img {
    max-width: 100%;
    height: auto;
    border-radius: 8px;
    margin: 1.5rem 0;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.home-extra-content-block table {
    width: 100%;
    border-collapse: collapse;
    margin: 1.5rem 0;
}

.home-extra-content-block table th,
.home-extra-content-block table td {
    padding: 0.75rem;
    border: 1px solid #e5e7eb;
    text-align: left;
}

.home-extra-content-block table th {
    background: #f9fafb;
    font-weight: 600;
    color: #1f2937;
}

@media (max-width: 768px) {
    .home-extra-content-section { padding: 2.5rem 0; }
    .home-extra-content-section .container { padding: 0 16px; }
    .home-extra-content-block { padding: 24px 20px; }
}
</style>
@endif

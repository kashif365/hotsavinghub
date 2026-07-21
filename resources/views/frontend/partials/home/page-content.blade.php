@if($homePage && !empty($homePage->page_content) && trim(strip_tags($homePage->page_content)) !== '')
<div class="home-content-section">
    <div class="container">
        <div class="home-content-container">
            <div class="home-content-wrapper">
                {!! $homePage->page_content !!}
            </div>
            <div class="home-content-vertical-line"></div>
        </div>
    </div>
</div>

<style>
.home-content-section {
    padding: 3.5rem 0;
    background: #ffffff;
    position: relative;
}

.home-content-section .container {
    max-width: var(--container-max, 1280px);
    margin: 0 auto;
    background: #f8f9fa;
    padding: 25px 60px;
    border-radius: var(--radius-lg, 20px);
}

.home-content-container {
    position: relative;
    display: flex;
    align-items: flex-start;
    gap: 0;
    max-width: 1200px;
    margin: 0 auto;
    max-height: 80vh;
    overflow-y: auto;
}

.home-content-wrapper {
    flex: 1;
    padding-right: 4rem;
    line-height: 1.8;
    color: #374151;
}

.home-content-wrapper h1,
.home-content-wrapper h2,
.home-content-wrapper h3,
.home-content-wrapper h4,
.home-content-wrapper h5,
.home-content-wrapper h6 {
    color: #1f2937;
    font-weight: 700;
    margin-top: 2rem;
    margin-bottom: 1rem;
    line-height: 1.3;
}

.home-content-wrapper h1 { font-size: 2.5rem; }
.home-content-wrapper h2 { font-size: 2rem; }
.home-content-wrapper h3 { font-size: 1.75rem; }
.home-content-wrapper h4 { font-size: 1.5rem; }

.home-content-wrapper p {
    margin-bottom: 1.5rem;
    font-size: 1.125rem;
    color: #4b5563;
}

.home-content-wrapper ul,
.home-content-wrapper ol {
    margin-bottom: 1.5rem;
    padding-left: 2rem;
}

.home-content-wrapper li {
    margin-bottom: 0.75rem;
    font-size: 1.125rem;
    color: #4b5563;
}

.home-content-wrapper a {
    color: var(--primary-color);
    text-decoration: underline;
    transition: color 0.3s ease;
}

.home-content-wrapper a:hover {
    color: var(--primary-hover);
}

.home-content-wrapper img {
    max-width: 100%;
    height: auto;
    border-radius: 8px;
    margin: 2rem 0;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.home-content-wrapper blockquote {
    border-left: 4px solid var(--primary-color);
    padding-left: 1.5rem;
    margin: 2rem 0;
    font-style: italic;
    color: #6b7280;
}

.home-content-wrapper code {
    background: #f3f4f6;
    padding: 0.25rem 0.5rem;
    border-radius: 4px;
    font-size: 0.875rem;
    color: var(--primary-color, #2951c4);
}

.home-content-wrapper pre {
    background: #1f2937;
    color: #f9fafb;
    padding: 1.5rem;
    border-radius: 8px;
    overflow-x: auto;
    margin: 2rem 0;
}

.home-content-wrapper pre code {
    background: transparent;
    color: inherit;
    padding: 0;
}

.home-content-wrapper table {
    width: 100%;
    border-collapse: collapse;
    margin: 2rem 0;
}

.home-content-wrapper table th,
.home-content-wrapper table td {
    padding: 0.75rem;
    border: 1px solid #e5e7eb;
    text-align: left;
}

.home-content-wrapper table th {
    background: #f9fafb;
    font-weight: 600;
    color: #1f2937;
}

@media (max-width: 768px) {
    .home-content-section { padding: 2.5rem 0; }
    .home-content-section .container { padding: 20px 16px; }
    .home-content-container { flex-direction: column; }
    .home-content-wrapper { max-width: 100%; padding-right: 0; padding-bottom: 2rem; }
    .home-content-vertical-line { position: relative; width: 100%; height: 4px; right: auto; top: auto; bottom: 0; }
    .home-content-wrapper h1 { font-size: 2rem; }
    .home-content-wrapper h2 { font-size: 1.75rem; }
    .home-content-wrapper h3 { font-size: 1.5rem; }
    .home-content-wrapper p, .home-content-wrapper li { font-size: 1rem; }
}

@media (max-width: 480px) {
    .home-content-section { padding: 2rem 0; }
    .home-content-wrapper { padding-bottom: 1.5rem; }
    .home-content-wrapper h1 { font-size: 1.75rem; }
    .home-content-wrapper h2 { font-size: 1.5rem; }
    .home-content-wrapper h3 { font-size: 1.25rem; }
    .home-content-wrapper p, .home-content-wrapper li { font-size: 0.9375rem; }
}
</style>
@endif

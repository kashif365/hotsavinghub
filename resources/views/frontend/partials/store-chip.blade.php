@php
    $chipTabindex = $decorative ?? false;
@endphp
<a href="{{ route('store', $brand->seo_url) }}" class="store-chip" title="{{ $brand->store_name }}" @if($chipTabindex) tabindex="-1" @endif>
    <span class="store-chip-logo">
        @if($brand->store_logo && file_exists(public_path(ltrim($brand->store_logo, '/'))))
            <img src="{{ asset(ltrim($brand->store_logo, '/')) }}" alt="" loading="lazy" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
            <span style="display:none;">{{ substr($brand->store_name, 0, 1) }}</span>
        @else
            <span>{{ substr($brand->store_name, 0, 1) }}</span>
        @endif
    </span>
    {{ $brand->store_name }}
</a>

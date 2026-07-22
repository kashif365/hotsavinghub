<!-- Layout wrapper -->
<div class="layout-wrapper layout-content-navbar">
  <div class="layout-container">

    <!-- Menu -->
    <aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">

      <div class="app-brand demo my-3">
        @php
            $brandingSettings = \App\Helpers\SettingsHelper::getBranding();
        @endphp
        <a href="{{ url('/') }}" class="app-brand-link d-block">
          <span class="app-brand-logo demo me-1">
            @if($brandingSettings['site_logo_url'])
                <img src="{{ $brandingSettings['site_logo_url'] }}" alt="{{ $brandingSettings['site_name'] }}" class="img-fluid" style="width:100%;">
            @else
                <img src="{{asset('assets/img/icons/logo.png')}}" alt="{{ $brandingSettings['site_name'] }}" class="img-fluid" style="width:100%;">
            @endif
          </span>
        </a>

        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
          <i class="menu-toggle-icon d-xl-block align-middle"></i>
        </a>
      </div>

      <div class="menu-inner-shadow"></div>

<ul class="menu-inner py-1">
        <li class="menu-item {{ request()->is('admin/dashboard') ? 'active' : '' }}">
            <a href="{{ route('admin.dashboard') }}" class="menu-link">
                <i class="menu-icon tf-icons ri-box-3-line"></i>
                <div>Dashboard</div>
            </a>
        </li>
    {{-- Coupons --}}
    <li class="menu-item {{ request()->is('admin/coupons', 'admin/coupons/*') ? 'active open' : '' }}">
        <a href="javascript:void(0);" class="menu-link menu-toggle">
            <i class="menu-icon tf-icons ri-box-3-line"></i>
            <div>Manage Coupons</div>
        </a>
        <ul class="menu-sub">
            <li class="menu-item {{ request()->is('admin/coupons/create') ? 'active' : '' }}">
                <a href="{{ route('admin.coupons.create') }}" class="menu-link">
                    <div>Add Coupon</div>
                </a>
            </li>
            <li class="menu-item {{ request()->is('admin/coupons') ? 'active' : '' }}">
                <a href="{{ route('admin.coupons.index') }}" class="menu-link">
                    <div>View Coupons</div>
                </a>
            </li>
        </ul>
    </li>

    {{-- Events --}}
    <li class="menu-item {{ request()->is('admin/events', 'admin/events/*') ? 'active open' : '' }}">
        <a href="javascript:void(0);" class="menu-link menu-toggle">
            <i class="menu-icon tf-icons ri-calendar-event-line"></i>
            <div>Manage Events</div>
        </a>
        <ul class="menu-sub">
            <li class="menu-item {{ request()->is('admin/events/create') ? 'active' : '' }}">
                <a href="{{ route('admin.events.create') }}" class="menu-link">
                    <div>Add Event</div>
                </a>
            </li>
            <li class="menu-item {{ request()->is('admin/events') ? 'active' : '' }}">
                <a href="{{ route('admin.events.index') }}" class="menu-link">
                    <div>View Events</div>
                </a>
            </li>
        </ul>
    </li>

    {{-- Networks --}}
    <li class="menu-item {{ request()->is('admin/networks', 'admin/networks/*') ? 'active open' : '' }}">
        <a href="{{ route('admin.networks.index') }}" class="menu-link">
            <i class="menu-icon tf-icons ri-share-line"></i>
            <div class="">Manage Networks</div>
        </a>
    </li>

    {{-- Categories --}}
    <li class="menu-item {{ request()->is('admin/categories', 'admin/categories/*') ? 'active open' : '' }}">
        <a href="javascript:void(0);" class="menu-link menu-toggle">
            <i class="menu-icon tf-icons ri-folder-3-line"></i>
            <div>Manage Categories</div>
        </a>
        <ul class="menu-sub">
            <li class="menu-item {{ request()->is('admin/categories/create') ? 'active' : '' }}">
                <a href="{{ route('admin.categories.create') }}" class="menu-link">
                    <div>Add Category</div>
                </a>
            </li>
            <li class="menu-item {{ request()->is('admin/categories') ? 'active' : '' }}">
                <a href="{{ route('admin.categories.index') }}" class="menu-link">
                    <div>View Categories</div>
                </a>
            </li>
        </ul>
    </li>

    {{-- Pages --}}
    <li class="menu-item {{ request()->is('admin/pages', 'admin/pages/*') ? 'active open' : '' }}">
        <a href="javascript:void(0);" class="menu-link menu-toggle">
            <i class="menu-icon tf-icons ri-file-list-3-line"></i>
            <div>Manage Pages</div>
        </a>
        <ul class="menu-sub">
            <li class="menu-item {{ request()->is('admin/pages/create') ? 'active' : '' }}">
                <a href="{{ route('admin.pages.create') }}" class="menu-link">
                    <div>Add Page</div>
                </a>
            </li>
            <li class="menu-item {{ request()->is('admin/pages') && !request()->is('admin/pages/create') ? 'active' : '' }}">
                <a href="{{ route('admin.pages.index') }}" class="menu-link">
                    <div>View Pages</div>
                </a>
            </li>
        </ul>
    </li>

    {{-- Stores --}}
    <li class="menu-item {{ request()->is('admin/stores', 'admin/stores/*') ? 'active open' : '' }}">
        <a href="javascript:void(0);" class="menu-link menu-toggle">
            <i class="menu-icon tf-icons ri-store-2-line"></i>
            <div>Manage Stores</div>
        </a>
        <ul class="menu-sub">
            <li class="menu-item {{ request()->is('admin/stores/create') ? 'active' : '' }}">
                <a href="{{ route('admin.stores.create') }}" class="menu-link">
                    <div>Add Store</div>
                </a>
            </li>
            <li class="menu-item {{ request()->is('admin/stores') ? 'active' : '' }}">
                <a href="{{ route('admin.stores.index') }}" class="menu-link">
                    <div>View Stores</div>
                </a>
            </li>
        </ul>
    </li>

    {{-- Sliders --}}
    <li class="menu-item {{ request()->is('admin/sliders', 'admin/sliders/*') ? 'active open' : '' }}">
        <a href="javascript:void(0);" class="menu-link menu-toggle">
            <i class="menu-icon tf-icons ri-slideshow-line"></i>
            <div>Manage Sliders</div>
        </a>
        <ul class="menu-sub">
            <li class="menu-item {{ request()->is('admin/sliders/create') ? 'active' : '' }}">
                <a href="{{ route('admin.sliders.create') }}" class="menu-link">
                    <div>Add Slider</div>
                </a>
            </li>
            <li class="menu-item {{ request()->is('admin/sliders') && !request()->is('admin/sliders/create') ? 'active' : '' }}">
                <a href="{{ route('admin.sliders.index') }}" class="menu-link">
                    <div>View Sliders</div>
                </a>
            </li>
        </ul>
    </li>

    {{-- Spotlight Cards --}}
    <li class="menu-item {{ request()->is('admin/spotlight-cards', 'admin/spotlight-cards/*') ? 'active open' : '' }}">
        <a href="javascript:void(0);" class="menu-link menu-toggle">
            <i class="menu-icon tf-icons ri-layout-grid-line"></i>
            <div>Spotlight Cards</div>
        </a>
        <ul class="menu-sub">
            <li class="menu-item {{ request()->is('admin/spotlight-cards/create') ? 'active' : '' }}">
                <a href="{{ route('admin.spotlight-cards.create') }}" class="menu-link">
                    <div>Add Spotlight Card</div>
                </a>
            </li>
            <li class="menu-item {{ request()->is('admin/spotlight-cards') && !request()->is('admin/spotlight-cards/create') ? 'active' : '' }}">
                <a href="{{ route('admin.spotlight-cards.index') }}" class="menu-link">
                    <div>View Spotlight Cards</div>
                </a>
            </li>
        </ul>
    </li>

    {{-- Home Content Blocks --}}
    <li class="menu-item {{ request()->is('admin/home-content-blocks', 'admin/home-content-blocks/*') ? 'active open' : '' }}">
        <a href="javascript:void(0);" class="menu-link menu-toggle">
            <i class="menu-icon tf-icons ri-article-line"></i>
            <div>Home Content Blocks</div>
        </a>
        <ul class="menu-sub">
            <li class="menu-item {{ request()->is('admin/home-content-blocks/create') ? 'active' : '' }}">
                <a href="{{ route('admin.home-content-blocks.create') }}" class="menu-link">
                    <div>Add Content Block</div>
                </a>
            </li>
            <li class="menu-item {{ request()->is('admin/home-content-blocks') && !request()->is('admin/home-content-blocks/create') ? 'active' : '' }}">
                <a href="{{ route('admin.home-content-blocks.index') }}" class="menu-link">
                    <div>View Content Blocks</div>
                </a>
            </li>
        </ul>
    </li>

    {{-- Blogs --}}
    <li class="menu-item {{ request()->is('admin/blogs', 'admin/blogs/*', 'admin/blog-categories', 'admin/blog-categories/*') ? 'active open' : '' }}">
        <a href="javascript:void(0);" class="menu-link menu-toggle">
            <i class="menu-icon tf-icons ri-article-line"></i>
            <div>Manage Blogs</div>
        </a>
        <ul class="menu-sub">
            <li class="menu-item {{ request()->is('admin/blogs/create') ? 'active' : '' }}">
                <a href="{{ route('admin.blogs.create') }}" class="menu-link">
                    <div>Add Blog Post</div>
                </a>
            </li>
            <li class="menu-item {{ request()->is('admin/blogs') && !request()->is('admin/blogs/create') ? 'active' : '' }}">
                <a href="{{ route('admin.blogs.index') }}" class="menu-link">
                    <div>View Blog Posts</div>
                </a>
            </li>
            <li class="menu-item {{ request()->is('admin/blog-categories/create') ? 'active' : '' }}">
                <a href="{{ route('admin.blog-categories.create') }}" class="menu-link">
                    <div>Add Blog Category</div>
                </a>
            </li>
            <li class="menu-item {{ request()->is('admin/blog-categories') && !request()->is('admin/blog-categories/create') ? 'active' : '' }}">
                <a href="{{ route('admin.blog-categories.index') }}" class="menu-link">
                    <div>View Blog Categories</div>
                </a>
            </li>
        </ul>
    </li>

    {{-- Contact Submissions (Admin Only) --}}
    @if(auth()->check() && auth()->user()->isAdmin())
    <li class="menu-item {{ request()->is('admin/contacts', 'admin/contacts/*') ? 'active open' : '' }}">
        <a href="{{ route('admin.contacts.index') }}" class="menu-link">
            <i class="menu-icon tf-icons ri-mail-line"></i>
            <div>Contact Submissions</div>
            @php
                $newContactsCount = $newContactsCount ?? 0;
            @endphp
            @if($newContactsCount > 0)
                <span class="badge bg-danger ms-auto">{{ $newContactsCount }}</span>
            @endif
        </a>
    </li>
    @endif

    {{-- Newsletter Subscribers (Admin Only) --}}
    @if(auth()->check() && auth()->user()->isAdmin())
    <li class="menu-item {{ request()->is('admin/newsletters', 'admin/newsletters/*') ? 'active open' : '' }}">
        <a href="{{ route('admin.newsletters.index') }}" class="menu-link">
            <i class="menu-icon tf-icons ri-newspaper-line"></i>
            <div>Newsletter Subscribers</div>
            @php
                $newsletterCount = $newsletterCount ?? 0;
            @endphp
            @if($newsletterCount > 0)
                <span class="badge bg-info ms-auto">{{ $newsletterCount }}</span>
            @endif
        </a>
    </li>
    @endif

    {{-- User Management (Admin Only) --}}
    @if(auth()->check() && auth()->user()->isAdmin())
    <li class="menu-item {{ request()->is('admin/users', 'admin/users/*') ? 'active open' : '' }}">
        <a href="javascript:void(0);" class="menu-link menu-toggle">
            <i class="menu-icon tf-icons ri-user-line"></i>
            <div>User Management</div>
        </a>
        <ul class="menu-sub">
            <li class="menu-item {{ request()->is('admin/users/create') ? 'active' : '' }}">
                <a href="{{ route('admin.users.create') }}" class="menu-link">
                    <div>Add New User</div>
                </a>
            </li>
            <li class="menu-item {{ request()->is('admin/users') && !request()->is('admin/users/create') ? 'active' : '' }}">
                <a href="{{ route('admin.users.index') }}" class="menu-link">
                    <div>View All Users</div>
                </a>
            </li>
        </ul>
    </li>
    @endif

    {{-- Customer Management (Admin Only) --}}
    @if(auth()->check() && auth()->user()->isAdmin())
    <li class="menu-item {{ request()->is('admin/customers', 'admin/customers/*') ? 'active open' : '' }}">
        <a href="javascript:void(0);" class="menu-link menu-toggle">
            <i class="menu-icon tf-icons ri-customer-service-line"></i>
            <div>Customer Management</div>
        </a>
        <ul class="menu-sub">
            <li class="menu-item {{ request()->is('admin/customers/create') ? 'active' : '' }}">
                <a href="{{ route('admin.customers.create') }}" class="menu-link">
                    <div>Add New Customer</div>
                </a>
            </li>
            <li class="menu-item {{ request()->is('admin/customers') && !request()->is('admin/customers/create') ? 'active' : '' }}">
                <a href="{{ route('admin.customers.index') }}" class="menu-link">
                    <div>View All Customers</div>
                </a>
            </li>
        </ul>
    </li>
    @endif

    {{-- URL Redirects --}}
    <li class="menu-item {{ request()->is('admin/redirects', 'admin/redirects/*') ? 'active open' : '' }}">
        <a href="{{ route('admin.redirects.index') }}" class="menu-link">
            <i class="menu-icon tf-icons ri-route-line"></i>
            <div>URL Redirects</div>
        </a>
    </li>

    {{-- Activity Logs (Admin Only) --}}
    @if(auth()->check() && auth()->user()->isAdmin())
    <li class="menu-item {{ request()->is('admin/activity-logs', 'admin/activity-logs/*') ? 'active open' : '' }}">
        <a href="{{ route('admin.activity-logs.index') }}" class="menu-link">
            <i class="menu-icon tf-icons ri-history-line"></i>
            <div>Activity Logs</div>
        </a>
    </li>
    @endif

    {{-- Media Library (Admin Only) --}}
    @if(auth()->check() && auth()->user()->isAdmin())
    <li class="menu-item {{ request()->is('admin/media', 'admin/media/*') ? 'active open' : '' }}">
        <a href="{{ route('admin.media.index') }}" class="menu-link">
            <i class="menu-icon tf-icons ri-image-line"></i>
            <div>Media Library</div>
        </a>
    </li>
    @endif

    {{-- Unused Images (Admin Only) --}}
    @if(auth()->check() && auth()->user()->isAdmin())
    <li class="menu-item {{ request()->is('admin/unused-images', 'admin/unused-images/*') ? 'active open' : '' }}">
        <a href="{{ route('admin.unused-images.index') }}" class="menu-link">
            <i class="menu-icon tf-icons ri-delete-bin-7-line"></i>
            <div>Unused Images</div>
        </a>
    </li>
    @endif

    {{-- Meta & Script Manager (Admin Only) --}}
    @if(auth()->check() && auth()->user()->isAdmin())
    <li class="menu-item {{ request()->is('admin/verification-tags', 'admin/verification-tags/*') ? 'active open' : '' }}">
        <a href="{{ route('admin.verification-tags.index') }}" class="menu-link">
            <i class="menu-icon tf-icons ri-shield-check-line"></i>
            <div>Meta & Script Manager</div>
        </a>
    </li>
    @endif

    {{-- Settings (Admin Only) --}}
    @if(auth()->check() && auth()->user()->isAdmin())
    <li class="menu-item {{ request()->is('admin/settings', 'admin/settings/*') ? 'active open' : '' }}">
        <a href="{{ route('admin.settings.index') }}" class="menu-link">
            <i class="menu-icon tf-icons ri-settings-3-line"></i>
            <div>Settings</div>
        </a>
    </li>
    @endif
</ul>




    </aside>
    <!-- / Menu -->

    <!-- Layout container -->
    <div class="layout-page">
      <!-- Navbar -->
      @include('admin.partials.top_navbar')

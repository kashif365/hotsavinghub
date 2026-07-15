<!-- Navbar -->

<nav class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme" id="layout-navbar">
  

  

      
      

      
      
      <div class="layout-menu-toggle navbar-nav align-items-xl-center me-4 me-xl-0   d-xl-none ">
        <a class="nav-item nav-link px-0 me-xl-6" href="javascript:void(0)">
          <i class="ri-menu-fill ri-24px"></i>
        </a>
      </div>
      

      <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">



        <!-- Search -->
        <div class="navbar-nav align-items-center">
          <div class="nav-item navbar-search-wrapper mb-0">
            <a class="nav-item nav-link search-toggler fw-normal px-0" href="javascript:void(0);">
              <i class="ri-search-line ri-22px scaleX-n1-rtl me-1_5"></i>
              <span class="d-none d-md-inline-block text-muted ms-1_5">Search (Ctrl+/)</span>
            </a>
          </div>
        </div>
        <!-- /Search -->
        @php
          // Stores with less than 4 coupons
          $storesWithFewCouponsCount = \App\Models\Store::withCount('coupons')->having('coupons_count', '<', 4)->count();
          $storesWithFewCoupons = \App\Models\Store::withCount('coupons')
            ->having('coupons_count', '<', 4)
            ->orderBy('coupons_count', 'asc')
            ->orderBy('store_name')
            ->take(10)
            ->get();
        @endphp
<div class="d-flex ms-auto">
        <!-- Notifications -->
        <ul class="navbar-nav flex-row align-items-center ms-2">
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle hide-arrow position-relative" href="javascript:void(0);" data-bs-toggle="dropdown" aria-expanded="false">
              <i class="ri-notification-3-line ri-22px"></i>
              @if($storesWithFewCouponsCount > 0)
                <span class="badge bg-danger rounded-pill position-absolute" style="top:-4px; right:-6px;">{{ $storesWithFewCouponsCount }}</span>
              @endif
            </a>
            <ul class="dropdown-menu dropdown-menu-end">
              <li class="dropdown-header">
                Stores with less than 4 coupons
              </li>
              @forelse($storesWithFewCoupons as $s)
                <li>
                  <span class="dropdown-item d-flex align-items-center justify-content-between">
                    <span>
                      <i class="ri-store-2-line me-2"></i>
                      {{ $s->store_name }}
                    </span>
                    <span class="badge bg-danger text-white">{{ $s->coupons_count }}</span>
                  </span>
                </li>
              @empty
                <li>
                  <span class="dropdown-item text-muted">No store notifications</span>
                </li>
              @endforelse
              @if($storesWithFewCouponsCount > $storesWithFewCoupons->count())
                <li><hr class="dropdown-divider"></li>
                <li>
                  <a class="dropdown-item" href="{{ route('admin.stores.index') }}">View all stores</a>
                </li>
              @endif
            </ul>
          </li>
        </ul>

        <a class="btn btn-danger mx-3" href="#"
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                      <small class="align-middle">Logout</small>
                      <i class="ri-logout-box-r-line ms-2 ri-16px"></i>
        </a>
</div>

      </div>

      
      <!-- Search Small Screens -->
      <div class="navbar-search-wrapper search-input-wrapper  d-none">
        <input type="text" class="form-control search-input container-xxl border-0" placeholder="Search..." aria-label="Search...">
        <i class="ri-close-fill search-toggler cursor-pointer"></i>
      </div>
      
      
  
</nav>

<!-- / Navbar -->
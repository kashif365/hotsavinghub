<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <title>Login</title>

    <!-- Icons -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/remixicon/remixicon.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/flag-icons.css') }}" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/rtl/core.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/rtl/theme-bordered.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/demo.css') }}" />

    <!-- Page CSS -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/pages/page-auth.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/node-waves/node-waves.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/typeahead-js/typeahead.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/formvalidation/dist/css/formValidation.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/sweetalert2/sweetalert2.css') }}" />
    <!-- Helpers -->
    <script src="{{ asset('assets/vendor/js/helpers.js') }}"></script>      
    <script src="{{ asset('assets/js/config.js') }}"></script>
</head>

<body>
<div class="position-relative">
  <div class="authentication-wrapper authentication-basic container-p-y">
    <div class="authentication-inner py-6 mx-4">

      <!-- Login Card -->
      <div class="card p-7">

        <!-- Logo -->
          <div class="app-brand justify-content-center my-5">
          @php
              $brandingSettings = \App\Helpers\SettingsHelper::getBranding();
          @endphp
          <a href="{{ url('/') }}" class="app-brand-link gap-3">
          @if($brandingSettings['site_logo_url'])
                <img src="{{ $brandingSettings['site_logo_url'] }}" alt="{{ $brandingSettings['site_name'] }}" title="{{ $brandingSettings['site_name'] }}" class="img-fluid"/>
              @else
                <img src="{{ asset('assets/img/icons/logo.png') }}" alt="{{ $brandingSettings['site_name'] }}" class="img-fluid" />
              @endif
          </a>
        </div>
        <!-- /Logo -->

        <div class="card-body mt-1">
          <h4 class="mb-1">Welcome Back 👋</h4>
          <p class="mb-5">Please sign in to your account</p>

          <!-- Laravel Login Form -->
          <form method="POST" action="{{ route('login.submit') }}">
            @csrf

            <!-- Email -->
            <div class="form-floating form-floating-outline mb-5">
              <input type="email" 
                     class="form-control @error('email') is-invalid @enderror" 
                     id="email" 
                     name="email" 
                     value="{{ old('email') }}" 
                     required 
                     autofocus 
                     placeholder="Enter your email">
              <label for="email">Email</label>
              @error('email')
                <span class="text-danger small">{{ $message }}</span>
              @enderror
            </div>

            <!-- Password -->
            <div class="mb-5">
              <div class="form-password-toggle">
                <div class="input-group input-group-merge">
                  <div class="form-floating form-floating-outline">
                    <input type="password" 
                           id="password" 
                           name="password" 
                           class="form-control @error('password') is-invalid @enderror" 
                           required 
                           placeholder="********">
                    <label for="password">Password</label>
                  </div>
                  <span class="input-group-text cursor-pointer"><i class="ri-eye-off-line ri-20px"></i></span>
                </div>
                @error('password')
                  <span class="text-danger small">{{ $message }}</span>
                @enderror
              </div>
            </div>

            <!-- Remember & Forgot -->
            <div class="mb-5 pb-2 d-flex justify-content-between align-items-center">
              <div class="form-check mb-0">
                <input class="form-check-input" type="checkbox" name="remember" id="remember">
                <label class="form-check-label" for="remember">Remember Me</label>
              </div>
            </div>

            <!-- Button -->
            <div class="mb-5">
              <button class="btn btn-primary d-grid w-100" type="submit">Login</button>
            </div>
          </form>

        </div>
      </div>
      <!-- /Login Card -->
    </div>
  </div>
</div>

<!-- Core JS -->
<script src="{{ asset('assets/vendor/libs/jquery/jquery.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/popper/popper.js') }}"></script>
<script src="{{ asset('assets/vendor/js/bootstrap.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/node-waves/node-waves.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/hammer/hammer.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/i18n/i18n.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/typeahead-js/typeahead.js') }}"></script>
<script src="{{ asset('assets/vendor/js/menu.js') }}"></script>

<!-- Main JS -->
<script src="{{ asset('assets/js/main.js') }}"></script>
<script src="{{ asset('assets/js/pages-auth.js') }}"></script>

<!-- Prevent back button navigation to cached pages -->
<script>
    // Clear browser history to prevent back button issues
    if (window.history && window.history.pushState) {
        window.history.pushState(null, null, window.location.href);
        window.onpopstate = function(event) {
            window.history.pushState(null, null, window.location.href);
        };
    }
</script>
</body>
</html>

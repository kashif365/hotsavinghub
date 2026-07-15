<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @php
        $brandingSettings = \App\Helpers\SettingsHelper::getBranding();
        $newContactsCount = \App\Models\Contact::where('status', 'new')->count();
        $newsletterCount = \App\Models\Newsletter::where('is_active', true)->count();
    @endphp
    <title>@yield('title', $brandingSettings['site_name'] . ' - Admin Dashboard')</title>
    <!-- Canonical SEO -->
    <link rel="canonical" href="{{ url('/') }}">
    
    
    <!-- ? PROD Only: Google Tag Manager (Default ThemeSelection: GTM-5DDHKGP, PixInvent: GTM-5J3LMKC) -->
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
      new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
      j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
      '../../../../www.googletagmanager.com/gtm5445.html?id='+i+dl;f.parentNode.insertBefore(j,f);
      })(window,document,'script','dataLayer','GTM-5DDHKGP');</script>
    <!-- End Google Tag Manager -->
        <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <!-- Favicon -->
    @if($brandingSettings['site_favicon_url'])
        <link rel="icon" type="image/x-icon" href="{{ $brandingSettings['site_favicon_url'] }}" />
    @else
        <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}" />
    @endif

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com/">
    <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&amp;ampdisplay=swap" rel="stylesheet">

    <!-- Icons -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/remixicon/remixicon.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/flag-icons.css') }}" />
    
    <!-- Dynamic Colors -->
    <link rel="stylesheet" href="{{ route('colors.css') }}?v={{ time() }}" />
    
    <!-- Menu waves for no-customizer fix -->
        <!-- Quill & icon related vendor CSS (load before core to avoid overrides) -->
        <link rel="stylesheet" href="{{ asset('assets/vendor/libs/quill/typography.css') }}" />
        <link rel="stylesheet" href="{{ asset('assets/vendor/libs/quill/editor.css') }}" />
        <link rel="stylesheet" href="{{ asset('assets/vendor/libs/quill/katex.css') }}" />

        <!-- Menu waves for no-customizer fix -->
        <link rel="stylesheet" href="{{ asset('assets/vendor/libs/node-waves/node-waves.css') }}" />

        <!-- Core css') }} -->
        <link rel="stylesheet" href="{{ asset('assets/vendor/css/rtl/core.css') }}" class="template-customizer-core-css') }}" />
        <link rel="stylesheet" href="{{ asset('assets/vendor/css/rtl/theme-bordered.css') }}" class="template-customizer-theme-css') }}" />
        <link rel="stylesheet" href="{{ asset('assets/css/demo.css') }}" />
    
        <!-- Vendors css') }} -->
        <link rel="stylesheet" href="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />
        <link rel="stylesheet" href="{{ asset('assets/vendor/libs/typeahead-js/typeahead.css') }}" /> 
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/flatpickr/flatpickr.css') }}" />
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css') }}">
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css') }}">
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-checkboxes-jquery/datatables.checkboxes.css') }}">
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css') }}">
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/flatpickr/flatpickr.css') }}" />
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/bootstrap-datepicker/bootstrap-datepicker.css') }}" />
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/bootstrap-daterangepicker/bootstrap-daterangepicker.css') }}" />
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/jquery-timepicker/jquery-timepicker.css') }}" />
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/pickr/pickr-themes.css') }}" />
<link rel="stylesheet" href="https://cdn.datatables.net/rowreorder/1.4.1/css/rowReorder.dataTables.min.css">
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/sweetalert2/sweetalert2.css') }}" />
    <!-- Page css') }} -->
    


    <!-- Helpers -->
    <script src="{{ asset('assets/vendor/js/helpers.js') }}"></script>
    <script src="{{ asset('assets/js/config.js') }}"></script>
    <!-- In header.php -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/rowreorder/1.4.1/css/rowReorder.bootstrap5.min.css">
</head>
<style>
  .menu-vertical .menu-item .menu-link{
        text-decoration: none;

  }
   .dataTables_wrapper .row{
        padding: 15px 25px;
  }
  .dataTables_wrapper .dt-row{
    padding: 0 !important;
  }
  .text-nowrap {
    white-space: wrap !important;
}
.image-upload-box {
    width: 100%;
    height: 150px;
    border: 1px dashed #ccc;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    overflow: hidden;
    margin: 10px 0px;
}
.image-upload-box:hover {
    cursor: pointer;
    border: 2px solid #ccc;
    background-color: #f8f9fa;

}
.image-upload-box img {
    max-width: 100%;
    max-height: 100%;
    display: block;
}

.image-upload-box svg {
    width: 50px;
    height: 50px;
    fill: #aaa;
}
.select2-container .select2-selection--single{
     height: 45px;
    padding-left: 10px;

}
.select2-container--default .select2-selection--single .select2-selection__rendered{
    line-height: 40px;
}
.select2-container--default .select2-selection--single .select2-selection__arrow{
    height: 40px;
    right: 10px;
}
.select2-selection--multiple{
    height: 40px;
}
.select2-search {
    line-height: 28px;
    padding-left: 10px;
}
.select2-selection__rendered .select2-selection__choice{
    background : linear-gradient(270deg, #8c57ff 0%, #c3a8ff 100%);
    color : #fff;
}

</style>
<body>

    @include('admin.partials.header')  <!-- Navbar -->
          <!-- Content wrapper -->
    <div class="content-wrapper">
        <div class="container-xxl flex-grow-1 container-p-y">
            @yield('content') <!-- Page content -->
        </div>
    </div>

    @include('admin.partials.footer')  <!-- Footer -->

    <!-- Logout Form -->
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>

    <!--/ Scrollable -->

    <!-- Core JS -->
    <!-- build:js assets/vendor/js/core.js -->
    <!-- Core JS -->
    <script src="{{ asset('assets/vendor/libs/popper/popper.js') }}"></script>
    <script src="{{ asset('assets/vendor/js/bootstrap.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/node-waves/node-waves.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/hammer/hammer.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/i18n/i18n.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/typeahead-js/typeahead.js') }}"></script>
    <script src="{{ asset('assets/vendor/js/menu.js') }}"></script>

    <!-- endbuild -->

    <!-- Vendors JS -->
    <script src="{{ asset('assets/vendor/libs/cleavejs/cleave.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/cleavejs/cleave-phone.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/moment/moment.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/flatpickr/flatpickr.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/bootstrap-datepicker/bootstrap-datepicker.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/bootstrap-daterangepicker/bootstrap-daterangepicker.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/jquery-timepicker/jquery-timepicker.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/pickr/pickr.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/sweetalert2/sweetalert2.js') }}"></script>
    <!-- Main JS -->
    <script src="{{ asset('assets/js/main.js') }}"></script>

    <!-- Page JS -->
    <script src="{{ asset('assets/js/form-layouts.js') }}"></script>
    <script src="{{ asset('assets/js/tables-datatables-basic.js') }}"></script>
    <script src="{{ asset('assets/js/forms-pickers.js') }}"></script>
    <script src="{{ asset('assets/js/extended-ui-sweetalert2.js') }}"></script>
    <!-- Quill editor and page editor init from Materio theme -->
    <script src="{{ asset('assets/vendor/libs/quill/katex.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/quill/quill.js') }}"></script>
    <script src="{{ asset('assets/js/forms-editors.js') }}"></script>
    <script src="https://cdn.datatables.net/rowreorder/1.4.1/js/dataTables.rowReorder.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/rowreorder/1.4.1/js/dataTables.rowReorder.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script src="{{ asset('assets/js/table-features.js') }}"></script>

<!-- Media Library Modal (Global) -->
@include('admin.partials.media-library-modal')

<!-- Link Click Tracking Script -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Track all link clicks
    document.addEventListener('click', function(e) {
        const link = e.target.closest('a');
        if (link && link.href) {
            trackLinkClick(link);
        }
    });
    
    function trackLinkClick(link) {
        // Skip external links and special links
        if (link.target === '_blank' || 
            link.href.includes('javascript:') || 
            link.href.includes('mailto:') || 
            link.href.includes('tel:') ||
            link.classList.contains('no-track')) {
            return;
        }
        
        // Get link information
        const linkData = {
            url: link.href,
            text: link.textContent.trim(),
            title: link.title || link.textContent.trim(),
            class: link.className,
            id: link.id,
            target: link.target || '_self'
        };
        
        // Send tracking data via AJAX
        fetch('{{ route("admin.track-link-click") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify(linkData)
        }).catch(function(error) {
            console.log('Link tracking failed:', error);
        });
    }
});
</script>

</body>
<script>
    $(document).ready(function() {
        $('#myTable').DataTable({
            "pageLength": 10,
            "lengthMenu": [
                [10, 25, 50, -1],
                [10, 25, 50, "All"]
            ]
        });
        $('.select2').select2({
            placeholder: "-- None --",
            allowClear: true
        });

    });
    function previewImage(event, imgId, placeholderId) {
    let reader = new FileReader();
    reader.onload = function(e) {
        document.getElementById(imgId).src = e.target.result;
        document.getElementById(imgId).style.display = 'block';
        document.getElementById(placeholderId).style.display = 'none';
    }
    if (event.target.files[0]) {
        reader.readAsDataURL(event.target.files[0]);
    }
}
</script>

</html>

<!-- beautify ignore:end -->

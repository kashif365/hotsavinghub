# Frontend HTML to Laravel Blade Conversion

This document describes the conversion of the HTML frontend to Laravel Blade templates.

## What Was Converted

### 1. Main Layout File
- **File**: `resources/views/frontend/layouts/app.blade.php`
- **Purpose**: Contains all CDN links, meta tags, and the basic HTML structure
- **Features**: 
  - All CSS and JavaScript CDN links
  - Meta tags and SEO elements
  - CSRF token integration
  - Laravel asset helpers using `frontend_assets` folder
  - Yield sections for title, description, and content
  - **Brand**: Social Offerz (bighsavinghub.com)
  - **Logo**: Uses `{{ asset('assets/img/icons/logo.png') }}`

### 2. Header Partial
- **File**: `resources/views/frontend/partials/header.blade.php`
- **Purpose**: Contains the navigation menu, logo, and search functionality
- **Features**:
  - Side navigation menu
  - Main header with logo
  - Dropdown navigation for categories
  - Mobile-responsive menu buttons
  - Laravel route helpers for all navigation links
  - **Brand**: Social Offerz logo and branding

### 3. Footer Partial
- **File**: `resources/views/frontend/partials/footer.blade.php`
- **Purpose**: Contains the footer content with links and social media
- **Features**:
  - Footer columns with organized links
  - Social media links (updated for Social Offerz)
  - Mobile app download links
  - Browser extension links
  - Laravel route helpers for all footer links
  - **Brand**: Social Offerz logo and copyright

### 4. Home Page Template
- **File**: `resources/views/frontend/home/index.blade.php`
- **Purpose**: The main home page content
- **Features**:
  - Extends the main layout
  - Search section
  - Featured discount offers
  - Featured stores
  - About section (Social Offerz)
  - Newsletter signup
  - Schema.org structured data (updated for bighsavinghub.com)

### 5. Frontend Controller
- **File**: `app/Http/Controllers/FrontendController.php`
- **Purpose**: Handles all frontend routes
- **Features**:
  - Methods for all frontend pages
  - Proper route naming
  - View rendering with data

### 6. Routes
- **File**: `routes/web.php`
- **Purpose**: Defines all frontend routes
- **Features**:
  - Home page route (`/`)
  - Category routes (`/category/{slug}`)
  - Store routes (`/store/{slug}`)
  - Static page routes (about, contact, etc.)
  - All routes use the FrontendController

## Key Changes Made

### Asset Paths
- Changed from `assets/` to `frontend_assets/` to match the public folder structure
- Used Laravel's `asset()` helper for proper URL generation
- **Logo**: Updated to use `{{ asset('assets/img/icons/logo.png') }}`

### Navigation Links
- Converted all hardcoded HTML links to Laravel route helpers
- Used `{{ route('route-name') }}` for dynamic routing
- Added proper route names for all pages

### Template Structure
- Implemented Laravel Blade template inheritance
- Used `@extends('frontend.layouts.app')` for consistent layout
- Added `@section` and `@yield` for dynamic content
- Used `@include('frontend.partials.header')` and `@include('frontend.partials.footer')` for reusable components

### CSRF Protection
- Added CSRF token meta tag
- Integrated with Laravel's built-in CSRF protection

### Dynamic Content
- Used Laravel's `{{ csrf_token() }}` helper
- Used `{{ url()->current() }}` for current URL
- Used `{{ auth()->id() ?? '0' }}` for user authentication

### Brand Updates
- **Brand Name**: Changed from "Top Vouchers Code" to "Social Offerz"
- **Website URL**: Changed from "topvoucherscode.co.uk" to "bighsavinghub.com"
- **Logo**: Updated to use your logo at `assets/img/icons/logo.png`
- **Social Media**: Updated all social media links to use "bighsavinghub" handles
- **Meta Tags**: Updated all meta tags and SEO elements with new brand information

## File Structure

```
resources/views/
└── frontend/
    ├── layouts/
    │   └── app.blade.php          # Main layout with CDN links and Social Offerz branding
    ├── partials/
    │   ├── header.blade.php       # Header navigation with Social Offerz logo
    │   └── footer.blade.php       # Footer content with Social Offerz branding
    ├── home/
    │   └── index.blade.php        # Home page content with Social Offerz branding
    └── top-discounts.blade.php    # Top discounts page with Social Offerz branding
```

## Usage

### Creating New Pages
1. Create a new Blade template in `resources/views/frontend/`
2. Extend the main layout: `@extends('frontend.layouts.app')`
3. Add content in the `@section('content')` block
4. Add a route in `routes/web.php`
5. Add a method in `FrontendController`

### Example New Page
```php
// routes/web.php
Route::get('/new-page', [FrontendController::class, 'newPage'])->name('new-page');

// FrontendController.php
public function newPage()
{
    return view('frontend.new-page');
}

// resources/views/frontend/new-page.blade.php
@extends('frontend.layouts.app')
@section('title', 'New Page Title')
@section('content')
    <h1>New Page Content</h1>
@endsection
```

## Benefits of the Conversion

1. **Maintainability**: Centralized layout and partials
2. **Reusability**: Header and footer can be used across all pages
3. **Consistency**: All pages use the same structure and styling
4. **SEO**: Dynamic meta tags and structured data
5. **Security**: CSRF protection and proper routing
6. **Scalability**: Easy to add new pages and features
7. **Brand Consistency**: All pages now use "Social Offerz" branding

## Notes

- All CDN links are preserved in the main layout
- Asset paths use the `frontend_assets` folder in the public directory
- **Logo**: Uses `assets/img/icons/logo.png` for all branding
- The conversion maintains the exact same visual appearance while updating branding
- All JavaScript functionality is preserved
- Mobile responsiveness is maintained
- SEO elements are enhanced with dynamic content and updated brand information
- **New Structure**: All frontend templates, layouts, and partials are now organized under the `frontend/` folder for better organization
- **Brand**: Successfully rebranded from "Top Vouchers Code" to "Social Offerz" throughout the application

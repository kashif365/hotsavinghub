@extends('frontend.layouts.app')

@section('title', '404 - Page Not Found')

@push('styles')
<style>
.error-page-container {
    min-height: 70vh;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 2rem 0;
    background-color: var(--quaternaryColor, #FAF9F5);
}

.error-content {
    background: white;
    border-radius: 20px;
    box-shadow: 0 20px 40px rgba(0,0,0,0.1);
    padding: 3rem;
    text-align: center;
    max-width: 700px;
    width: 90%;
    margin: 0 auto;
    border: 2px solid var(--primaryColor, #00bfff);
}

.error-icon {
    animation: bounce 2s infinite;
    margin-bottom: 2rem;
}

@keyframes bounce {
    0%, 20%, 50%, 80%, 100% {
        transform: translateY(0);
    }
    40% {
        transform: translateY(-10px);
    }
    60% {
        transform: translateY(-5px);
    }
}

.error-number {
    font-size: 6rem;
    font-weight: 900;
    color: var(--primaryColor, #00bfff);
    margin-bottom: 1rem;
    text-shadow: 2px 2px 4px rgba(0,0,0,0.1);
}

.error-title {
    font-size: 2rem;
    font-weight: 700;
    color: var(--primaryTextColor, #0F0F0F);
    margin-bottom: 1rem;
}

.error-description {
    font-size: 1.1rem;
    color: var(--greyTextColor, #6d6e71);
    margin-bottom: 2rem;
    line-height: 1.6;
}

.action-buttons {
    margin-bottom: 3rem;
}

.btn-custom {
    padding: 12px 30px;
    border-radius: 50px;
    font-weight: 600;
    text-decoration: none;
    display: inline-block;
    margin: 0 10px;
    transition: all 0.3s ease;
}

.btn-primary-custom {
    background-color: var(--primaryColor, #00bfff);
    color: white;
    border: none;
}

.btn-primary-custom:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 20px rgba(255,0,0,0.3);
    color: white;
    background-color: var(--secondaryColor, #2951c4);
}

.btn-secondary-custom {
    background: transparent;
    color: var(--primaryColor, #00bfff);
    border: 2px solid var(--primaryColor, #00bfff);
}

.btn-secondary-custom:hover {
    background-color: var(--primaryColor, #00bfff);
    color: white;
    transform: translateY(-2px);
}

.helpful-links {
    margin-bottom: 2rem;
}

.helpful-links h4 {
    color: var(--primaryTextColor, #0F0F0F);
    margin-bottom: 2rem;
    font-weight: 600;
}

.link-card {
    background: var(--quaternaryDarkColor, #F2F0E6);
    border-radius: 15px;
    padding: 1.5rem;
    margin-bottom: 1rem;
    transition: all 0.3s ease;
    text-decoration: none;
    color: inherit;
    display: block;
    border: 1px solid var(--borderColor, #D5D5D5);
}

.link-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    color: inherit;
    text-decoration: none;
    background-color: var(--tertiaryColor, #fef08e);
}

.link-icon {
    font-size: 2.5rem;
    margin-bottom: 1rem;
}

.link-title {
    font-size: 1.2rem;
    font-weight: 600;
    margin-bottom: 0.5rem;
}

.link-description {
    color: var(--greyTextColor, #6d6e71);
    font-size: 0.9rem;
}

.search-section h4 {
    color: var(--primaryTextColor, #0F0F0F);
    margin-bottom: 1.5rem;
    font-weight: 600;
}

.search-form {
    max-width: 400px;
    margin: 0 auto;
}

.search-input {
    border-radius: 25px 0 0 25px;
    border: 2px solid var(--borderColor, #D5D5D5);
    padding: 12px 20px;
    font-size: 1rem;
    background-color: white;
}

.search-input:focus {
    border-color: var(--primaryColor, #00bfff);
    box-shadow: none;
    outline: none;
}

.search-btn {
    border-radius: 0 25px 25px 0;
    background-color: var(--primaryColor, #00bfff);
    border: none;
    padding: 12px 20px;
    color: white;
    font-weight: 600;
}

.search-btn:hover {
    background-color: var(--secondaryColor, #2951c4);
    color: white;
}

@media (max-width: 768px) {
    .error-content {
        padding: 2rem 1.5rem;
    }
    
    .error-number {
        font-size: 4rem;
    }
    
    .error-title {
        font-size: 1.5rem;
    }
    
    .btn-custom {
        display: block;
        margin: 10px auto;
        width: 200px;
    }
}
</style>
@endpush

@section('content')
<div class="error-page-container">
    <div class="error-content">
        <!-- 404 Icon -->
        <div class="error-icon">
            <i class="fas fa-exclamation-triangle" style="font-size: 4rem; color: var(--primaryColor, #00bfff);"></i>
        </div>
        
        <!-- Error Message -->
        <h1 class="error-number">404</h1>
        <h2 class="error-title">Oops! Page Not Found</h2>
        <p class="error-description">
            The page you're looking for doesn't exist or has been moved.
        </p>
        
        <!-- Action Buttons -->
        <div class="action-buttons">
            <a href="{{ route('home') }}" class="btn-custom btn-primary-custom">
                <i class="fas fa-home me-2"></i>
                Go to Homepage
            </a>
            <a href="javascript:history.back()" class="btn-custom btn-secondary-custom">
                <i class="fas fa-arrow-left me-2"></i>
                Go Back
            </a>
        </div>
        
        <!-- Helpful Links -->
        <div class="helpful-links">
            <h4>You might be looking for:</h4>
            
            <a href="{{ route('all-stores') }}" class="link-card">
                <div class="link-icon" style="color: var(--primaryColor, #00bfff);">
                    <i class="fas fa-store"></i>
                </div>
                <div class="link-title" style="color: var(--primaryColor, #00bfff);">Browse Stores</div>
                <div class="link-description">Find your favorite stores</div>
            </a>
            
            <a href="{{ route('categories') }}" class="link-card">
                <div class="link-icon" style="color: var(--secondaryColor, #2951c4);">
                    <i class="fas fa-tags"></i>
                </div>
                <div class="link-title" style="color: var(--secondaryColor, #2951c4);">Categories</div>
                <div class="link-description">Explore by category</div>
            </a>
            
            <a href="{{ route('events') }}" class="link-card">
                <div class="link-icon" style="color: var(--greyColor, #4B5563);">
                    <i class="fas fa-calendar-alt"></i>
                </div>
                <div class="link-title" style="color: var(--greyColor, #4B5563);">Events</div>
                <div class="link-description">Check out special events</div>
            </a>
        </div>

    </div>
</div>
@endsection

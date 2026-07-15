// Categories Page JavaScript

document.addEventListener('DOMContentLoaded', function() {
    // Initialize categories page functionality
    initializeCategoriesPage();
});

function initializeCategoriesPage() {
    // Add smooth scrolling for anchor links
    const anchorLinks = document.querySelectorAll('a[href^="#"]');
    anchorLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });

    // Add hover effects for category boxes
    const categoryBoxes = document.querySelectorAll('.bx');
    categoryBoxes.forEach(box => {
        box.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-5px)';
        });
        
        box.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });

    // Add click tracking for store links
    const storeLinks = document.querySelectorAll('.lnks a');
    storeLinks.forEach(link => {
        link.addEventListener('click', function() {
            const storeName = this.textContent.trim();
            trackStoreClick(storeName);
        });
    });

    // Add click tracking for store images
    const storeImages = document.querySelectorAll('.imgs a');
    storeImages.forEach(image => {
        image.addEventListener('click', function() {
            const storeName = this.getAttribute('title');
            trackStoreClick(storeName);
        });
    });

    // Add click tracking for category titles
    const categoryTitles = document.querySelectorAll('.ttl h3 a');
    categoryTitles.forEach(title => {
        title.addEventListener('click', function() {
            const categoryName = this.textContent.trim();
            trackCategoryClick(categoryName);
        });
    });

    // Add click tracking for "View All" links
    const viewAllLinks = document.querySelectorAll('.ttl a[title]');
    viewAllLinks.forEach(link => {
        link.addEventListener('click', function() {
            const categoryName = this.getAttribute('title');
            trackCategoryClick(categoryName, 'view_all');
        });
    });
}

function trackStoreClick(storeName) {
    // Send analytics event for store click
    if (typeof gtag !== 'undefined') {
        gtag('event', 'store_click', {
            'event_category': 'categories_page',
            'event_label': storeName,
            'value': 1
        });
    }
    
    // Log to console for debugging
    console.log('Store clicked:', storeName);
}

function trackCategoryClick(categoryName, action = 'category_click') {
    // Send analytics event for category click
    if (typeof gtag !== 'undefined') {
        gtag('event', action, {
            'event_category': 'categories_page',
            'event_label': categoryName,
            'value': 1
        });
    }
    
    // Log to console for debugging
    console.log('Category clicked:', categoryName, action);
}

// Add loading animation for images
function addImageLoadingAnimation() {
    const images = document.querySelectorAll('.imgs img');
    images.forEach(img => {
        img.addEventListener('load', function() {
            this.style.opacity = '1';
        });
        
        img.addEventListener('error', function() {
            // Hide broken images
            this.style.display = 'none';
        });
        
        // Set initial opacity for loading effect
        img.style.opacity = '0';
        img.style.transition = 'opacity 0.3s ease';
    });
}

// Initialize image loading animation
addImageLoadingAnimation();

// Add search functionality if search input exists
function initializeSearch() {
    const searchInput = document.querySelector('input[type="search"]');
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            filterCategories(searchTerm);
        });
    }
}

function filterCategories(searchTerm) {
    const categoryBoxes = document.querySelectorAll('.bx');
    
    categoryBoxes.forEach(box => {
        const categoryName = box.querySelector('.ttl h3 a').textContent.toLowerCase();
        const storeLinks = box.querySelectorAll('.lnks a');
        
        let hasMatch = categoryName.includes(searchTerm);
        
        if (!hasMatch) {
            storeLinks.forEach(link => {
                if (link.textContent.toLowerCase().includes(searchTerm)) {
                    hasMatch = true;
                }
            });
        }
        
        box.style.display = hasMatch ? 'block' : 'none';
    });
}

// Initialize search if available
initializeSearch();

// Add keyboard navigation
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        // Clear any active filters
        const searchInput = document.querySelector('input[type="search"]');
        if (searchInput && searchInput.value) {
            searchInput.value = '';
            filterCategories('');
        }
    }
});

// Add intersection observer for lazy loading
if ('IntersectionObserver' in window) {
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
            }
        });
    }, {
        threshold: 0.1
    });

    const categoryBoxes = document.querySelectorAll('.bx');
    categoryBoxes.forEach(box => {
        observer.observe(box);
    });
}

// Add performance monitoring
function trackPagePerformance() {
    if ('performance' in window) {
        window.addEventListener('load', function() {
            setTimeout(function() {
                const perfData = performance.getEntriesByType('navigation')[0];
                if (perfData) {
                    const loadTime = perfData.loadEventEnd - perfData.loadEventStart;
                    console.log('Categories page load time:', loadTime + 'ms');
                    
                    // Send to analytics if available
                    if (typeof gtag !== 'undefined') {
                        gtag('event', 'page_load_time', {
                            'event_category': 'performance',
                            'event_label': 'categories_page',
                            'value': Math.round(loadTime)
                        });
                    }
                }
            }, 0);
        });
    }
}

// Initialize performance tracking
trackPagePerformance();

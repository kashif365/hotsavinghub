// All Modal JavaScript functionality
// This file contains the modal-related functionality

console.log("All Modal JS loaded successfully");

// Modal related functions will be implemented here
document.addEventListener('DOMContentLoaded', function() {
    console.log("All Modal DOM loaded");
    
    // Add any modal-specific functionality here
    // For now, this is a placeholder file to prevent 404 errors
});

// Export for module usage if needed
window.AllModals = {
    init: function() {
        console.log("All Modals initialized");
    },
    
    // Basic modal functions
    open: function(modalSelector) {
        const modal = document.querySelector(modalSelector);
        if (modal) {
            modal.style.display = 'block';
            document.body.style.overflow = 'hidden';
        }
    },
    
    close: function(modalSelector) {
        const modal = document.querySelector(modalSelector);
        if (modal) {
            modal.style.display = 'none';
            document.body.style.overflow = 'auto';
        }
    }
};

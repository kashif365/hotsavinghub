# Color Customization Guide

## Overview
The website now supports dynamic color customization through the admin dashboard. Users can change 5 different colors that will be applied across the entire website.

## Available Color Settings

1. **Primary Color** - Main brand color (default: #FF0000)
2. **Secondary Color** - Secondary brand color (default: #000000)  
3. **Background Primary Color** - Main background color (default: #FFFFFF)
4. **Background Secondary Color** - Secondary background color (default: #F8F9FA)
5. **Text Color** - Main text color (default: #333333)

## How to Change Colors

1. Go to Admin Dashboard → Settings
2. In the "Branding Settings" section, you'll see 5 color pickers
3. Click on any color picker to choose a new color
4. The color preview will update immediately
5. Click "Save Settings" to apply changes
6. The new colors will be applied across the entire website

## CSS Variables Available

The system generates CSS custom properties (variables) that can be used in your CSS:

```css
:root {
    --primary-color: #FF0000;
    --secondary-color: #000000;
    --background-primary-color: #FFFFFF;
    --background-secondary-color: #F8F9FA;
    --text-color: #333333;
}
```

## Utility Classes Available

The system also provides utility classes for quick styling:

```css
.primary-color { color: var(--primary-color) !important; }
.secondary-color { color: var(--secondary-color) !important; }
.bg-primary-color { background-color: var(--background-primary-color) !important; }
.bg-secondary-color { background-color: var(--background-secondary-color) !important; }
.text-color { color: var(--text-color) !important; }
```

## How to Use in Your CSS

You can use these variables in your custom CSS files:

```css
/* Example usage */
.my-button {
    background-color: var(--primary-color);
    color: var(--background-primary-color);
    border: 2px solid var(--secondary-color);
}

.my-text {
    color: var(--text-color);
}

.my-section {
    background-color: var(--background-secondary-color);
}
```

## Technical Implementation

- Colors are stored in the `settings` table
- Dynamic CSS is generated at `/css/colors.css`
- CSS is cached for 1 hour for performance
- Changes take effect immediately after saving
- Both frontend and admin panel use the same color system

## File Locations

- Settings Controller: `app/Http/Controllers/SettingsController.php`
- Settings Helper: `app/Helpers/SettingsHelper.php`
- Settings View: `resources/views/admin/settings/index.blade.php`
- Dynamic CSS Route: `routes/web.php` (line 165)
- Frontend Layout: `resources/views/frontend/layouts/app.blade.php`
- Admin Layout: `resources/views/admin/layouts/app.blade.php`

## Browser Support

- All modern browsers support CSS custom properties
- Fallback colors are provided for older browsers
- Color picker works in all modern browsers

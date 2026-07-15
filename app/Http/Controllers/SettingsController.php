<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;
use Illuminate\Support\Facades\Storage;
use App\Services\ImageService;

class SettingsController extends Controller
{
    protected $imageService;

    public function __construct(ImageService $imageService)
    {
        $this->imageService = $imageService;
    }
    /**
     * Display settings page
     */
    public function index()
    {
        $brandingSettings = Setting::group('branding')->get();
        $contactSettings = Setting::group('contact')->get();
        $socialSettings = Setting::group('social')->get();
        $generalSettings = Setting::group('general')->get();
        
        return view('admin.settings.index', compact('brandingSettings', 'contactSettings', 'socialSettings', 'generalSettings'));
    }

    /**
     * Update settings
     */
    public function update(Request $request)
    {
        $request->validate([
            'settings' => 'required|array',
            'settings.*' => 'nullable|string',
            'site_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'site_favicon' => 'nullable|file|mimes:jpeg,png,jpg,gif,svg,ico,webp|max:1024',
            'home_banner' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:5120',
            'site_logo_remove' => 'nullable|string',
            'site_favicon_remove' => 'nullable|string',
            'home_banner_remove' => 'nullable|string'
        ]);

        // Handle file uploads and removals
        $imageFields = ['site_logo', 'site_favicon', 'home_banner'];
        
        foreach ($imageFields as $field) {
            if ($request->hasFile($field)) {
                // Delete old image if exists
                $currentImage = Setting::get($field, '');
                if ($currentImage) {
                    // Remove 'storage/' prefix if present
                    $currentImagePath = str_replace('storage/', '', $currentImage);
                    $fullPath = public_path($currentImagePath);
                    if (file_exists($fullPath)) {
                        \Illuminate\Support\Facades\File::delete($fullPath);
                    }
                }
                
                $file = $request->file($field);
                $extension = strtolower($file->getClientOriginalExtension());
                
                // Handle .ico files separately (don't convert to WebP)
                if ($extension === 'ico' && $field === 'site_favicon') {
                    // Save .ico file as-is
                    $publicPath = public_path('uploads');
                    if (!\Illuminate\Support\Facades\File::exists($publicPath)) {
                        \Illuminate\Support\Facades\File::makeDirectory($publicPath, 0755, true);
                    }
                    
                    $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                    $fileName = time() . '_' . uniqid() . '_' . \Illuminate\Support\Str::slug($originalName) . '.ico';
                    $file->move($publicPath, $fileName);
                    $imagePath = 'uploads/' . $fileName;
                } else {
                    // Upload and convert image to WebP for other formats
                    $imagePath = $this->imageService->uploadAndConvert(
                        $file,
                        'uploads',
                        ['quality' => 100, 'preserve_original' => true]
                    );
                }
                
                Setting::set($field, $imagePath);
            } elseif ($request->has($field . '_remove') && $request->input($field . '_remove') == '1') {
                // Remove image
                $currentImage = Setting::get($field, '');
                if ($currentImage) {
                    // Remove 'storage/' prefix if present
                    $currentImagePath = str_replace('storage/', '', $currentImage);
                    $fullPath = public_path($currentImagePath);
                    if (file_exists($fullPath)) {
                        \Illuminate\Support\Facades\File::delete($fullPath);
                    }
                }
                Setting::set($field, '');
            } elseif ($request->filled($field . '_path')) {
                Setting::set($field, $request->input($field . '_path'));
            }
        }

        // Update other settings
        foreach ($request->settings as $key => $value) {
            // Skip logo, favicon, and banner as they're handled above
            if (!in_array($key, ['site_logo', 'site_favicon', 'home_banner'])) {
                Setting::set($key, $value);
            }
        }
        
        // Clear settings cache to ensure new colors are loaded
        \App\Models\Setting::clearCache();

        return back()->with('success', 'Settings updated successfully!');
    }

    /**
     * Generate dynamic CSS for color variables
     */
    public function generateColorCss()
    {
        $brandingSettings = \App\Helpers\SettingsHelper::getBranding();
        
        // Calculate primary-hover (darker shade of primary color)
        $primaryColor = $brandingSettings['primary_color'];
        $primaryHover = $this->darkenColor($primaryColor, 15); // Darken by 15%
        
        $css = ":root {\n";
        $css .= "    --primary-color: {$brandingSettings['primary_color']};\n";
        $css .= "    --primary-hover: {$primaryHover};\n";
        $css .= "    --secondary-color: {$brandingSettings['secondary_color']};\n";
        $css .= "    --background-primary-color: {$brandingSettings['background_primary_color']};\n";
        $css .= "    --background-secondary-color: {$brandingSettings['background_secondary_color']};\n";
        $css .= "    --text-color: {$brandingSettings['text_color']};\n";
        $css .= "}\n\n";
        
        // Add utility classes
        $css .= ".primary-color { color: var(--primary-color) !important; }\n";
        $css .= ".secondary-color { color: var(--secondary-color) !important; }\n";
        $css .= ".bg-primary-color { background-color: var(--background-primary-color) !important; }\n";
        $css .= ".bg-secondary-color { background-color: var(--background-secondary-color) !important; }\n";
        $css .= ".text-color { color: var(--text-color) !important; }\n";
        
        return response($css)
            ->header('Content-Type', 'text/css')
            ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
    }
    
    /**
     * Darken a hex color by a percentage
     */
    private function darkenColor($hex, $percent)
    {
        // Remove # if present
        $hex = str_replace('#', '', $hex);
        
        // Convert to RGB
        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));
        
        // Darken by percentage
        $r = max(0, min(255, $r - ($r * $percent / 100)));
        $g = max(0, min(255, $g - ($g * $percent / 100)));
        $b = max(0, min(255, $b - ($b * $percent / 100)));
        
        // Convert back to hex
        $r = str_pad(dechex(round($r)), 2, '0', STR_PAD_LEFT);
        $g = str_pad(dechex(round($g)), 2, '0', STR_PAD_LEFT);
        $b = str_pad(dechex(round($b)), 2, '0', STR_PAD_LEFT);
        
        return '#' . $r . $g . $b;
    }

    /**
     * Reset settings to default
     */
    public function reset()
    {
        // Delete all current settings
        Setting::truncate();
        
        // Run seeder to restore defaults
        $this->seedDefaultSettings();

        return back()->with('success', 'Settings reset to default values!');
    }

    /**
     * Seed default settings
     */
    private function seedDefaultSettings()
    {
        $defaultSettings = [
            // Branding Settings
            ['key' => 'site_name', 'value' => 'Social Offerz', 'type' => 'text', 'group' => 'branding', 'label' => 'Site Name', 'description' => 'The name of your website', 'sort_order' => 1],
            ['key' => 'site_tagline', 'value' => 'Save More, Shop Smart', 'type' => 'text', 'group' => 'branding', 'label' => 'Site Tagline', 'description' => 'A short tagline for your website', 'sort_order' => 2],
            ['key' => 'site_logo', 'value' => '', 'type' => 'image', 'group' => 'branding', 'label' => 'Site Logo', 'description' => 'Upload your site logo', 'sort_order' => 3],
            ['key' => 'site_favicon', 'value' => '', 'type' => 'image', 'group' => 'branding', 'label' => 'Site Favicon', 'description' => 'Upload your site favicon', 'sort_order' => 4],
            ['key' => 'home_banner', 'value' => '', 'type' => 'image', 'group' => 'branding', 'label' => 'Home Page Banner', 'description' => 'Upload banner image for home page hero section', 'sort_order' => 5],
            ['key' => 'home_heading', 'value' => 'Save Big with Exclusive Coupon Codes', 'type' => 'text', 'group' => 'branding', 'label' => 'Home Page Heading', 'description' => 'Main heading text for home page hero section', 'sort_order' => 6],
            ['key' => 'home_subheading', 'value' => 'Discover thousands of verified discount codes from your favorite brands. Start saving money on every purchase today!', 'type' => 'textarea', 'group' => 'branding', 'label' => 'Home Page Subheading', 'description' => 'Subheading text for home page hero section', 'sort_order' => 7],
            ['key' => 'home_overlay_color', 'value' => 'rgba(0, 0, 0, 0.5)', 'type' => 'text', 'group' => 'branding', 'label' => 'Home Page Overlay Color', 'description' => 'Background overlay color for home page banner', 'sort_order' => 8],
            ['key' => 'primary_color', 'value' => '#FF0000', 'type' => 'text', 'group' => 'branding', 'label' => 'Primary Color', 'description' => 'Main color for your brand', 'sort_order' => 9],
            ['key' => 'secondary_color', 'value' => '#000000', 'type' => 'text', 'group' => 'branding', 'label' => 'Secondary Color', 'description' => 'Secondary color for your brand', 'sort_order' => 10],
            ['key' => 'background_primary_color', 'value' => '#FFFFFF', 'type' => 'text', 'group' => 'branding', 'label' => 'Background Primary Color', 'description' => 'Main background color', 'sort_order' => 11],
            ['key' => 'background_secondary_color', 'value' => '#F8F9FA', 'type' => 'text', 'group' => 'branding', 'label' => 'Background Secondary Color', 'description' => 'Secondary background color', 'sort_order' => 12],
            ['key' => 'text_color', 'value' => '#333333', 'type' => 'text', 'group' => 'branding', 'label' => 'Text Color', 'description' => 'Main text color', 'sort_order' => 13],

            // Contact Settings
            ['key' => 'contact_email', 'value' => 'support@bighsavinghub.com', 'type' => 'email', 'group' => 'contact', 'label' => 'Contact Email', 'description' => 'Main contact email address', 'sort_order' => 1],
            ['key' => 'contact_phone', 'value' => '+44 20 7946 0958', 'type' => 'phone', 'group' => 'contact', 'label' => 'Contact Phone', 'description' => 'Main contact phone number', 'sort_order' => 2],
            ['key' => 'contact_address', 'value' => '123 Business Street, London, UK SW1A 1AA', 'type' => 'textarea', 'group' => 'contact', 'label' => 'Contact Address', 'description' => 'Physical address of your business', 'sort_order' => 3],
            ['key' => 'business_hours', 'value' => 'Mon-Fri: 9AM-6PM GMT', 'type' => 'text', 'group' => 'contact', 'label' => 'Business Hours', 'description' => 'Your business operating hours', 'sort_order' => 4],
            ['key' => 'support_email', 'value' => 'support@bighsavinghub.com', 'type' => 'email', 'group' => 'contact', 'label' => 'Support Email', 'description' => 'Customer support email', 'sort_order' => 5],
            ['key' => 'partnership_email', 'value' => 'partnerships@bighsavinghub.com', 'type' => 'email', 'group' => 'contact', 'label' => 'Partnership Email', 'description' => 'Partnership inquiries email', 'sort_order' => 6],

            // Social Media Settings
            ['key' => 'facebook_url', 'value' => '', 'type' => 'url', 'group' => 'social', 'label' => 'Facebook URL', 'description' => 'Your Facebook page URL', 'sort_order' => 1],
            ['key' => 'twitter_url', 'value' => '', 'type' => 'url', 'group' => 'social', 'label' => 'Twitter URL', 'description' => 'Your Twitter profile URL', 'sort_order' => 2],
            ['key' => 'instagram_url', 'value' => '', 'type' => 'url', 'group' => 'social', 'label' => 'Instagram URL', 'description' => 'Your Instagram profile URL', 'sort_order' => 3],
            ['key' => 'linkedin_url', 'value' => '', 'type' => 'url', 'group' => 'social', 'label' => 'LinkedIn URL', 'description' => 'Your LinkedIn profile URL', 'sort_order' => 4],
            ['key' => 'youtube_url', 'value' => '', 'type' => 'url', 'group' => 'social', 'label' => 'YouTube URL', 'description' => 'Your YouTube channel URL', 'sort_order' => 5],
            ['key' => 'tiktok_url', 'value' => '', 'type' => 'url', 'group' => 'social', 'label' => 'TikTok URL', 'description' => 'Your TikTok profile URL', 'sort_order' => 6],

            // General Settings
            ['key' => 'site_description', 'value' => 'Find the best discount codes and deals for your favorite stores. Save money on every purchase with Social Offerz.', 'type' => 'textarea', 'group' => 'general', 'label' => 'Site Description', 'description' => 'SEO description for your website', 'sort_order' => 1],
            ['key' => 'site_keywords', 'value' => 'discount codes, coupons, deals, savings, shopping', 'type' => 'text', 'group' => 'general', 'label' => 'Site Keywords', 'description' => 'SEO keywords for your website', 'sort_order' => 2],
            ['key' => 'timezone', 'value' => 'Europe/London', 'type' => 'text', 'group' => 'general', 'label' => 'Timezone', 'description' => 'Default timezone for your website', 'sort_order' => 3],
            ['key' => 'currency', 'value' => 'GBP', 'type' => 'text', 'group' => 'general', 'label' => 'Currency', 'description' => 'Default currency for your website', 'sort_order' => 4],
            ['key' => 'items_per_page', 'value' => '20', 'type' => 'text', 'group' => 'general', 'label' => 'Items Per Page', 'description' => 'Number of items to show per page', 'sort_order' => 5],
        ];

        foreach ($defaultSettings as $setting) {
            Setting::create($setting);
        }
    }
}

@extends('admin.layouts.app')

@section('title', 'Site Settings')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Site Settings</h5>
                    <div class="d-flex gap-2">
                        <form action="{{ route('admin.settings.reset') }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to reset all settings to default values?')">
                            @csrf
                            <button type="submit" class="btn btn-outline-warning">
                                <i class="ri-refresh-line me-1"></i>Reset to Default
                            </button>
                        </form>
                    </div>
                </div>
                
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <!-- Branding Settings -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h6 class="card-title mb-0">
                                    <i class="ri-palette-line me-2"></i>Branding Settings
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    @foreach($brandingSettings as $setting)
                                        <div class="col-md-6 mb-3">
                                            <label for="{{ $setting->key }}" class="form-label">{{ $setting->label }}</label>
                                            @if($setting->type === 'textarea')
                                                <textarea class="form-control" id="{{ $setting->key }}" name="settings[{{ $setting->key }}]" rows="3">{{ $setting->value }}</textarea>
                                            @elseif($setting->type === 'image')
                                                <div class="image-upload-container">
                                                    @if($setting->value)
                                                        <div class="current-image-preview mb-3">
                                                            <label class="form-label text-success">
                                                                <i class="ri-check-circle-fill me-1"></i>Current {{ $setting->label }} (Active on Website)
                                                            </label>
                                                            <div class="current-image-box">
                                                                <img src="{{ $setting->value ? asset($setting->value) : '' }}" 
                                                                     alt="Current {{ $setting->label }}" 
                                                                     class="current-image"
                                                                     style="max-width: {{ $setting->key === 'site_logo' ? '200px' : ($setting->key === 'home_banner' ? '300px' : '64px') }}; max-height: {{ $setting->key === 'site_logo' ? '100px' : ($setting->key === 'home_banner' ? '150px' : '64px') }}; border: 2px solid #28a745; border-radius: 8px; padding: 10px; background: #f8f9fa;">
                                                            </div>
                                                        </div>
                                                    @endif
                                                    
                                                    <div class="image-upload-box" onclick="document.getElementById('{{ $setting->key }}').click()" style="min-height: {{ $setting->key === 'home_banner' ? '150px' : '120px' }};">
                                                        <img id="{{ $setting->key }}_preview"
                                                             src="{{ $setting->value ? asset($setting->value) : '' }}"
                                                             style="{{ $setting->value ? '' : 'display:none;' }} max-width: {{ $setting->key === 'home_banner' ? '300px' : '100%' }}; max-height: {{ $setting->key === 'home_banner' ? '150px' : '100px' }};">
                                                        <svg id="{{ $setting->key }}_placeholder" style="{{ $setting->value ? 'display:none;' : '' }}" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                                            <path d="M20,5A2,2 0 0,1 22,7V17A2,2 0 0,1 20,19H4C2.89,19 2,18.1 2,17V7C2,5.89 2.89,5 4,5H20M5,16H19L14.5,10L11,14.5L8.5,11.5L5,16Z" />
                                                        </svg>
                                                    </div>
                                                    <div class="image-actions mt-2 d-flex gap-2 flex-wrap justify-content-center">
                                                        <button type="button" class="btn btn-outline-primary btn-sm flex-fill" onclick="document.getElementById('{{ $setting->key }}').click()">
                                                            <i class="ri-upload-line me-1"></i>Upload New
                                                        </button>
                                                        <button type="button" class="btn btn-outline-info btn-sm flex-fill" onclick="openMediaLibrary('{{ $setting->key }}', '{{ $setting->key }}_preview', '{{ $setting->key }}_placeholder')">
                                                            <i class="ri-image-line me-1"></i>Media
                                                        </button>
                                                        @if($setting->value)
                                                        <button type="button" class="btn btn-outline-danger btn-sm flex-fill" onclick="removeImage('{{ $setting->key }}')">
                                                            <i class="ri-delete-bin-line me-1"></i>Remove
                                                        </button>
                                                        @endif
                                                    </div>
                                                    <input type="file" id="{{ $setting->key }}" name="{{ $setting->key }}" style="display:none;" accept="{{ $setting->key === 'site_favicon' ? 'image/*,.ico' : 'image/*' }}"
                                                           onchange="previewImage(event, '{{ $setting->key }}_preview', '{{ $setting->key }}_placeholder')">
                                                    <input type="hidden" id="{{ $setting->key }}_path" name="{{ $setting->key }}_path">
                                                    <input type="hidden" id="{{ $setting->key }}_remove" name="{{ $setting->key }}_remove" value="0">
                                                    <div class="form-text">
                                                        @if($setting->value)
                                                            <span class="text-success">
                                                                <i class="ri-check-circle-fill me-1"></i>Current {{ $setting->label }} is active on website
                                                            </span><br>
                                                        @endif
                                                        Click to upload new {{ strtolower($setting->label) }} (Max: {{ $setting->key === 'site_logo' ? '2MB' : ($setting->key === 'home_banner' ? '5MB' : '1MB') }})
                                                    </div>
                                                </div>
                                             @elseif(in_array($setting->key, ['primary_color', 'secondary_color', 'background_primary_color', 'background_secondary_color', 'text_color']))
                                                 <div class="color-input-group">
                                                     <div class="input-group">
                                                         <input type="color" class="form-control form-control-color" id="{{ $setting->key }}" name="settings[{{ $setting->key }}]" value="{{ $setting->value }}">
                                                         <input type="text" class="form-control color-text-input" value="{{ $setting->value }}" readonly>
                                                     </div>
                                                     <div class="color-preview" style="background-color: {{ $setting->value }}; border: 1px solid #ddd; width: 100%; height: 40px; border-radius: 4px; margin-top: 8px;"></div>
                                                 </div>
                                            @elseif($setting->key === 'home_heading')
                                                <div class="heading-input-container">
                                                    <div class="input-group">
                                                        <input type="text" 
                                                               class="form-control" 
                                                               id="{{ $setting->key }}" 
                                                               name="settings[{{ $setting->key }}]" 
                                                               value="{{ $setting->value }}"
                                                               placeholder="Enter heading text">
                                                        <button type="button" class="btn btn-primary" onclick="applyPrimaryColor('{{ $setting->key }}')" title="Apply Primary Color">
                                                            <i class="ri-palette-line"></i> Primary Color
                                                        </button>
                                                    </div>
                                                    <div class="form-text">
                                                        <strong>How to use:</strong> Select text and click "Primary Color" button to apply theme's primary color.
                                                    </div>
                                                </div>
                                            @elseif($setting->key === 'home_overlay_color')
                                                <div class="overlay-color-container">
                                                    <div class="input-group">
                                                        <input type="color" 
                                                               class="form-control form-control-color" 
                                                               id="{{ $setting->key }}_color" 
                                                               value="#000000"
                                                               onchange="updateOverlayColor('{{ $setting->key }}')">
                                                        <input type="hidden" 
                                                               id="{{ $setting->key }}" 
                                                               name="settings[{{ $setting->key }}]" 
                                                               value="{{ $setting->value }}">
                                                    </div>
                                                    <div class="overlay-preview">
                                                        <label class="form-label text-muted">Preview:</label>
                                                        <div class="preview-overlay" id="{{ $setting->key }}_preview" style="background: {{ $setting->value }};">
                                                            <span class="preview-text">Sample Text</span>
                                                        </div>
                                                    </div>
                                                    <div class="form-text">
                                                        <strong>How to use:</strong> Pick any color using the color picker. The color will be applied with 50% opacity to the home page banner.
                                                    </div>
                                                </div>
                                            @else
                                                <input type="{{ $setting->type === 'email' ? 'email' : ($setting->type === 'url' ? 'url' : 'text') }}" 
                                                       class="form-control" 
                                                       id="{{ $setting->key }}" 
                                                       name="settings[{{ $setting->key }}]" 
                                                       value="{{ $setting->value }}">
                                            @endif
                                            @if($setting->description)
                                                <div class="form-text">{{ $setting->description }}</div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <!-- Contact Settings -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h6 class="card-title mb-0">
                                    <i class="ri-phone-line me-2"></i>Contact Information
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    @foreach($contactSettings as $setting)
                                        <div class="col-md-6 mb-3">
                                            <label for="{{ $setting->key }}" class="form-label">{{ $setting->label }}</label>
                                            @if($setting->type === 'textarea')
                                                <textarea class="form-control" id="{{ $setting->key }}" name="settings[{{ $setting->key }}]" rows="3">{{ $setting->value }}</textarea>
                                            @elseif($setting->type === 'email')
                                                <input type="email" class="form-control" id="{{ $setting->key }}" name="settings[{{ $setting->key }}]" value="{{ $setting->value }}">
                                            @elseif($setting->type === 'phone')
                                                <input type="tel" class="form-control" id="{{ $setting->key }}" name="settings[{{ $setting->key }}]" value="{{ $setting->value }}">
                                            @else
                                                <input type="text" class="form-control" id="{{ $setting->key }}" name="settings[{{ $setting->key }}]" value="{{ $setting->value }}">
                                            @endif
                                            @if($setting->description)
                                                <div class="form-text">{{ $setting->description }}</div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <!-- Social Media Settings -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h6 class="card-title mb-0">
                                    <i class="ri-share-line me-2"></i>Social Media Links
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    @foreach($socialSettings as $setting)
                                        <div class="col-md-6 mb-3">
                                            <label for="{{ $setting->key }}" class="form-label">
                                                @php
                                                    $icons = [
                                                        'facebook_url' => 'ri-facebook-fill',
                                                        'twitter_url' => 'ri-twitter-fill',
                                                        'instagram_url' => 'ri-instagram-fill',
                                                        'linkedin_url' => 'ri-linkedin-fill',
                                                        'youtube_url' => 'ri-youtube-fill',
                                                        'tiktok_url' => 'ri-tiktok-fill'
                                                    ];
                                                @endphp
                                                <i class="{{ $icons[$setting->key] ?? 'ri-link' }} me-1"></i>{{ $setting->label }}
                                            </label>
                                            <input type="url" class="form-control" id="{{ $setting->key }}" name="settings[{{ $setting->key }}]" value="{{ $setting->value }}" placeholder="https://...">
                                            @if($setting->description)
                                                <div class="form-text">{{ $setting->description }}</div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <!-- General Settings -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h6 class="card-title mb-0">
                                    <i class="ri-settings-3-line me-2"></i>General Settings
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    @foreach($generalSettings as $setting)
                                        <div class="col-md-6 mb-3">
                                            <label for="{{ $setting->key }}" class="form-label">{{ $setting->label }}</label>
                                            @if($setting->type === 'textarea')
                                                <textarea class="form-control" id="{{ $setting->key }}" name="settings[{{ $setting->key }}]" rows="3">{{ $setting->value }}</textarea>
                                            @elseif($setting->key === 'timezone')
                                                <select class="form-select" id="{{ $setting->key }}" name="settings[{{ $setting->key }}]">
                                                    <option value="Europe/London" {{ $setting->value === 'Europe/London' ? 'selected' : '' }}>Europe/London (GMT)</option>
                                                    <option value="America/New_York" {{ $setting->value === 'America/New_York' ? 'selected' : '' }}>America/New_York (EST)</option>
                                                    <option value="America/Los_Angeles" {{ $setting->value === 'America/Los_Angeles' ? 'selected' : '' }}>America/Los_Angeles (PST)</option>
                                                    <option value="Asia/Tokyo" {{ $setting->value === 'Asia/Tokyo' ? 'selected' : '' }}>Asia/Tokyo (JST)</option>
                                                    <option value="Asia/Dubai" {{ $setting->value === 'Asia/Dubai' ? 'selected' : '' }}>Asia/Dubai (GST)</option>
                                                </select>
                                            @elseif($setting->key === 'currency')
                                                <select class="form-select" id="{{ $setting->key }}" name="settings[{{ $setting->key }}]">
                                                    <option value="GBP" {{ $setting->value === 'GBP' ? 'selected' : '' }}>GBP (£)</option>
                                                    <option value="USD" {{ $setting->value === 'USD' ? 'selected' : '' }}>USD ($)</option>
                                                    <option value="EUR" {{ $setting->value === 'EUR' ? 'selected' : '' }}>EUR (€)</option>
                                                    <option value="CAD" {{ $setting->value === 'CAD' ? 'selected' : '' }}>CAD (C$)</option>
                                                    <option value="AUD" {{ $setting->value === 'AUD' ? 'selected' : '' }}>AUD (A$)</option>
                                                </select>
                                            @else
                                                <input type="text" class="form-control" id="{{ $setting->key }}" name="settings[{{ $setting->key }}]" value="{{ $setting->value }}">
                                            @endif
                                            @if($setting->description)
                                                <div class="form-text">{{ $setting->description }}</div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="ri-save-line me-1"></i>Save Settings
                            </button>
                            <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">
                                <i class="ri-arrow-left-line me-1"></i>Back to Dashboard
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.image-upload-container {
    border: 2px dashed #dee2e6;
    border-radius: 8px;
    padding: 20px;
    text-align: center;
    background: #f8f9fa;
    transition: all 0.3s ease;
}

.image-upload-container:hover {
    border-color: #007bff;
    background: #e3f2fd;
}

.current-image-preview {
    text-align: center;
}

.current-image-box {
    display: inline-block;
    margin-top: 10px;
}

.current-image {
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    transition: transform 0.3s ease;
}

.current-image:hover {
    transform: scale(1.05);
}

.image-upload-box {
    cursor: pointer;
    min-height: 120px;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 2px dashed #6c757d;
    border-radius: 8px;
    background: white;
    margin: 10px 0;
    transition: all 0.3s ease;
}

.image-upload-box:hover {
    border-color: #007bff;
    background: #f8f9fa;
}

.image-upload-box img {
    max-width: 100%;
    max-height: 100px;
    border-radius: 4px;
}

.image-upload-box svg {
    width: 48px;
    height: 48px;
    fill: #6c757d;
}

.image-actions {
    text-align: center;
}

.image-actions .btn {
    margin: 0 2px;
}

.color-input-group {
    margin-bottom: 15px;
}

.color-input-group .input-group {
    margin-bottom: 8px;
}

.color-preview {
    transition: all 0.3s ease;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.color-preview:hover {
    transform: scale(1.02);
    box-shadow: 0 4px 8px rgba(0,0,0,0.15);
}

.form-control-color {
    width: 60px !important;
    height: 38px;
    border: none;
    border-radius: 4px 0 0 4px;
    cursor: pointer;
}

.color-text-input {
    font-family: 'Courier New', monospace;
    font-weight: bold;
}

.heading-input-container {
    margin-bottom: 15px;
}

.overlay-color-container {
    margin-bottom: 15px;
}

.overlay-preview {
    margin-top: 10px;
}

.preview-overlay {
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" width="100" height="100" viewBox="0 0 100 100"><rect width="50" height="50" fill="%23ff6b6b"/><rect x="50" y="50" width="50" height="50" fill="%234ecdc4"/><rect x="0" y="50" width="50" height="50" fill="%2345b7d1"/><rect x="50" y="0" width="50" height="50" fill="%2396ceb4"/></svg>') center/cover;
    border: 1px solid #dee2e6;
    border-radius: 6px;
    padding: 20px;
    min-height: 60px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.preview-text {
    color: white;
    font-weight: bold;
    text-shadow: 1px 1px 2px rgba(0,0,0,0.5);
    font-size: 1.2rem;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Color picker synchronization
    const colorInputs = document.querySelectorAll('input[type="color"]');
    colorInputs.forEach(function(colorInput) {
        const textInput = colorInput.nextElementSibling;
        const colorPreview = colorInput.closest('.color-input-group').querySelector('.color-preview');
        
        colorInput.addEventListener('change', function() {
            textInput.value = this.value;
            if (colorPreview) {
                colorPreview.style.backgroundColor = this.value;
            }
        });
        
        textInput.addEventListener('input', function() {
            if (/^#[0-9A-F]{6}$/i.test(this.value)) {
                colorInput.value = this.value;
                if (colorPreview) {
                    colorPreview.style.backgroundColor = this.value;
                }
            }
        });
    });

    // URL validation
    const urlInputs = document.querySelectorAll('input[type="url"]');
    urlInputs.forEach(function(input) {
        input.addEventListener('blur', function() {
            if (this.value && !this.value.match(/^https?:\/\//)) {
                this.value = 'https://' + this.value;
            }
        });
    });

    // File validation for image uploads
    const fileInputs = document.querySelectorAll('input[type="file"]');
    fileInputs.forEach(function(input) {
        input.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                // Check file size
                const maxSize = this.name === 'site_logo' ? 2 * 1024 * 1024 : (this.name === 'home_banner' ? 5 * 1024 * 1024 : 1 * 1024 * 1024); // 2MB for logo, 5MB for banner, 1MB for favicon
                if (file.size > maxSize) {
                    alert('File size too large! Maximum size is ' + (maxSize / (1024 * 1024)) + 'MB');
                    this.value = '';
                    return;
                }

                // Check file type
                if (!file.type.startsWith('image/')) {
                    alert('Please select an image file!');
                    this.value = '';
                    return;
                }
            }
        });
    });
});

// Preview image function (same as store form)
function previewImage(event, previewId, placeholderId) {
    const file = event.target.files && event.target.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = function(){
        const img = document.getElementById(previewId);
        const ph  = document.getElementById(placeholderId);
        if (img) { img.src = reader.result; img.style.display = 'block'; }
        if (ph)  { ph.style.display = 'none'; }
    };
    reader.readAsDataURL(file);
}

// Remove image function
function removeImage(settingKey) {
    if (confirm('Are you sure you want to remove this image? This action cannot be undone.')) {
        // Hide the preview image
        const previewImg = document.getElementById(settingKey + '_preview');
        const placeholder = document.getElementById(settingKey + '_placeholder');
        const fileInput = document.getElementById(settingKey);
        const removeInput = document.getElementById(settingKey + '_remove');
        
        if (previewImg) {
            previewImg.style.display = 'none';
            previewImg.src = '';
        }
        if (placeholder) {
            placeholder.style.display = 'block';
        }
        if (fileInput) {
            fileInput.value = '';
        }
        if (removeInput) {
            removeInput.value = '1';
        }
        const pathInput = document.getElementById(settingKey + '_path');
        if (pathInput) {
            pathInput.value = '';
        }
        
        // Hide the remove button and show upload button
        const removeBtn = event.target.closest('.image-actions').querySelector('button[onclick*="removeImage"]');
        if (removeBtn) {
            removeBtn.style.display = 'none';
        }
        
        // Hide current image preview section
        const currentImagePreview = document.querySelector('.current-image-preview');
        if (currentImagePreview) {
            currentImagePreview.style.display = 'none';
        }
        
        console.log('Image removal marked for:', settingKey);
    }
}

// Apply primary color function for heading
function applyPrimaryColor(inputId) {
    const input = document.getElementById(inputId);
    
    if (!input) return;
    
    // Get current cursor position
    const start = input.selectionStart;
    const end = input.selectionEnd;
    const text = input.value;
    
    // Get selected text or prompt for text
    let selectedText = text.substring(start, end);
    if (!selectedText) {
        selectedText = prompt('Enter text to apply primary color:');
        if (!selectedText) return;
    }
    
    // Get primary color from settings
    const primaryColorInput = document.querySelector('input[name="settings[primary_color]"]');
    const primaryColor = primaryColorInput ? primaryColorInput.value : (getComputedStyle(document.documentElement).getPropertyValue('--primary-color') || 'var(--primary-color)');
    
    // Create span tag with inline style using primary color
    const spanTag = '<span style="color: ' + primaryColor + '; font-weight: bold;">' + selectedText + '</span>';
    
    // Insert span tag
    const newText = text.substring(0, start) + spanTag + text.substring(end);
    input.value = newText;
    
    // Set cursor position after the inserted tag
    const newPosition = start + spanTag.length;
    input.setSelectionRange(newPosition, newPosition);
    input.focus();
}

// Update overlay color from color picker
function updateOverlayColor(inputId) {
    const colorPicker = document.getElementById(inputId + '_color');
    const hiddenInput = document.getElementById(inputId);
    const previewId = inputId + '_preview';
    
    if (!colorPicker || !hiddenInput) return;
    
    // Use the selected color directly
    const selectedColor = colorPicker.value;
    
    // Update hidden input for form submission
    hiddenInput.value = selectedColor;
    
    // Update preview
    const preview = document.getElementById(previewId);
    if (preview) {
        preview.style.background = selectedColor;
    }
    
    console.log('Overlay color updated to:', selectedColor);
}

// Initialize overlay color picker
document.addEventListener('DOMContentLoaded', function() {
    const overlayColorInput = document.getElementById('home_overlay_color');
    const overlayColorPicker = document.getElementById('home_overlay_color_color');
    
    if (overlayColorInput && overlayColorPicker) {
        // Set initial color picker value from current value
        const currentValue = overlayColorInput.value;
        
        if (currentValue.startsWith('rgba(')) {
            // Extract RGB values from RGBA
            const rgbaMatch = currentValue.match(/rgba\((\d+),\s*(\d+),\s*(\d+),\s*[\d.]+\)/);
            if (rgbaMatch) {
                const r = parseInt(rgbaMatch[1]);
                const g = parseInt(rgbaMatch[2]);
                const b = parseInt(rgbaMatch[3]);
                const hex = '#' + ((1 << 24) + (r << 16) + (g << 8) + b).toString(16).slice(1);
                overlayColorPicker.value = hex;
            }
        } else if (currentValue.startsWith('#')) {
            // Already a hex color
            overlayColorPicker.value = currentValue;
        } else {
            // Default to black if no valid color
            overlayColorPicker.value = '#000000';
        }
        
        // Initial preview update
        const preview = document.getElementById('home_overlay_color_preview');
        if (preview) {
            preview.style.background = currentValue;
        }
    }
});
</script>
@endsection

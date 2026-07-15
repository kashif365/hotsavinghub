# Unused Image Cleanup Tool

## Overview

This tool automatically detects and removes unused images from your Laravel project. It scans all image files in `/public` and `/storage/app/public` directories and checks if they are referenced anywhere in your codebase or database.

## Features

✅ **Comprehensive Scanning:**
- Scans all Blade templates (`resources/views/**/*.blade.php`)
- Scans all Controllers (`app/Http/Controllers`)
- Scans all Models (`app/Models`)
- Scans all Services (`app/Services`)
- Scans all Helper files (`app/Helpers`)
- Scans all CSS files (`public/assets/css`, `public/frontend_assets/css`)
- Scans all JS files (`public/assets/js`, `public/frontend_assets/js`)
- Scans database tables for image paths

✅ **Safe Detection:**
- Multiple pattern matching (asset(), url(), src=, background-image, etc.)
- Filename and path matching
- Database record checking
- Excludes system files (favicon, logo, etc.)

✅ **Safety Features:**
- Dry-run mode (preview without deleting)
- Confirmation prompt before deletion
- Detailed logging
- JSON report generation

## Usage

### 1. Dry Run (Recommended First Step)

Preview what would be deleted without actually deleting anything:

```bash
php artisan images:cleanup --dry-run
```

This will:
- Scan all images
- Show statistics
- Generate a report at `storage/logs/unused_images.json`
- **NOT delete anything**

### 2. Actual Cleanup

After reviewing the dry-run results, run the actual cleanup:

```bash
php artisan images:cleanup
```

You will be prompted to confirm before deletion.

### 3. Force Mode (Skip Confirmation)

If you're sure and want to skip the confirmation prompt:

```bash
php artisan images:cleanup --force
```

⚠️ **Warning:** Use `--force` only if you're absolutely certain!

### 4. Custom Directories

Scan specific directories only:

```bash
php artisan images:cleanup --directories=public/uploads,storage/app/public/uploads
```

## Output

### Console Output

The command displays:
- Total images found
- Used vs unused images count
- Files scanned (Blade, PHP, CSS, JS)
- Database records checked
- List of unused images (first 50)
- Deletion progress
- Final summary

### JSON Report

A detailed report is saved to `storage/logs/unused_images.json` containing:
- Scan date and time
- Total statistics
- Complete list of unused images with paths and sizes
- Scan statistics

## Database Tables Scanned

The tool checks these database tables for image references:

- **events**: `front_image`, `button_icon`, `cover_image`, `no_coupon_cover`
- **blogs**: `featured_image`
- **stores**: `store_logo`, `cover_image`
- **categories**: `media`
- **pages**: `media`, `banner_image`
- **sliders**: `background_image`
- **coupons**: `cover_logo`
- **settings**: `value` (for site_logo, site_favicon, home_banner)

## Image Reference Patterns Detected

The tool detects images referenced in:

1. **Laravel Helpers:**
   - `asset('path/to/image.jpg')`
   - `url('path/to/image.jpg')`

2. **HTML/Blade:**
   - `src="path/to/image.jpg"`
   - `src='path/to/image.jpg'`

3. **CSS:**
   - `background-image: url('path/to/image.jpg')`

4. **Direct Paths:**
   - `uploads/image.jpg`
   - `storage/image.jpg`
   - `assets/image.jpg`

5. **Filename References:**
   - Just the filename: `image.jpg`

## Safety Exclusions

The following files are **never deleted** (always considered "used"):

- `favicon.ico`
- `logo.png` / `logo.jpg`
- `default-store.png`
- Any file matching these patterns

## Example Output

```
🗑️  Unused Image Cleanup Tool
================================

Step 1: Scanning for unused images...

🔍 Starting image scan...
📁 Collecting all images...
Found 1046 images total
📝 Scanning codebase files...
💾 Scanning database...
🔎 Identifying unused images...

+--------------------------+-------+
| Metric                   | Count |
+--------------------------+-------+
| Total Images Found       | 1046  |
| Used Images              | 2811  |
| Unused Images            | 15    |
| Blade Files Scanned      | 91    |
| PHP Files Scanned        | 48    |
| CSS Files Scanned        | 87    |
| JS Files Scanned         | 278   |
| Database Records Checked | 304   |
+--------------------------+-------+

Step 2: Generating report...
✅ Report saved to: storage/logs/unused_images.json

📋 Unused Images List:
+------------------+--------+-------------------+
| Filename         | Size   | Path              |
+------------------+--------+-------------------+
| old_image_1.jpg  | 245 KB | uploads/old_...  |
| unused_logo.png | 1.2 MB | uploads/unused... |
+------------------+--------+-------------------+

⚠️  WARNING: You are about to delete 15 unused images (2.5 MB)
This action cannot be undone!

Do you want to proceed with deletion? (yes/no) [no]:
```

## Best Practices

1. **Always run dry-run first:**
   ```bash
   php artisan images:cleanup --dry-run
   ```

2. **Review the JSON report:**
   Check `storage/logs/unused_images.json` before deleting

3. **Backup before deletion:**
   Consider backing up your `public` and `storage/app/public` directories

4. **Test in development first:**
   Run on a development/staging environment before production

5. **Check the report regularly:**
   Run this monthly to keep your storage clean

## Troubleshooting

### No unused images found

If the tool reports 0 unused images, it means:
- All images are properly referenced in your codebase
- All images are stored in database records
- Your project is clean! ✅

### False Positives

If an image is marked as unused but you know it's used:
1. Check the JSON report to see why it wasn't detected
2. The image might be referenced in a way the tool doesn't detect
3. Manually exclude it by adding it to the exclusion list in the code

### Performance

For large projects with thousands of images:
- The scan may take a few minutes
- Progress is shown in real-time
- The tool is optimized to handle large codebases

## Files Created

- `app/Services/UnusedImageDetector.php` - Core detection service
- `app/Console/Commands/CleanupUnusedImages.php` - Artisan command
- `storage/logs/unused_images.json` - Generated report (created on each run)

## Support

If you encounter any issues:
1. Check the JSON report for details
2. Review the console output for errors
3. Ensure all directories exist and are readable
4. Check file permissions

---

**⚠️ Important:** This tool permanently deletes files. Always run with `--dry-run` first and review the results carefully!


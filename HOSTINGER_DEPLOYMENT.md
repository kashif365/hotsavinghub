# Hostinger Deployment Guide

## File Structure for Hostinger

```
public_html/
├── index.php (copy from public/index.production.php)
├── .htaccess (copy from public/.htaccess.production)
├── assets/
├── frontend_assets/
├── uploads/ (create this directory)
└── ... (all other public files)

coupons_project/ (outside public_html)
├── app/
├── config/
├── database/
├── resources/
├── storage/
├── vendor/
├── .env (update with production values)
└── ... (all other Laravel files)
```

## Step-by-Step Deployment

### 1. Upload Files
- Upload your entire project to Hostinger
- Move contents of `public/` folder to `public_html/`
- Move all other files to `coupons_project/` folder outside `public_html/`

### 2. Update index.php
- Copy `public/index.production.php` to `public_html/index.php`
- Make sure it points to `../coupons_project/`

### 3. Update .htaccess
- Copy `public/.htaccess.production` to `public_html/.htaccess`

### 4. Create uploads directory
```bash
mkdir public_html/uploads
chmod 755 public_html/uploads
```

### 5. Update .env file
Update your `.env` file in `coupons_project/` with these values:

```env
APP_NAME="Social Offerz"
APP_ENV=production
APP_KEY=base64:0txQV138CIjMtkhlrfYop+L/5wqy6yR+0ygMZKtQrv4=
APP_DEBUG=false
APP_URL=https://bigsavinghub.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=u110144442_u298747500_hdc
DB_USERNAME=u110144442_u298747500_hdc
DB_PASSWORD=>R?7T7Xh

FILESYSTEM_DISK=public
```

### 6. Set Permissions
```bash
chmod -R 755 coupons_project/storage
chmod -R 755 coupons_project/bootstrap/cache
chmod -R 755 public_html/uploads
```

### 7. Clear Cache
```bash
cd coupons_project
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
php artisan optimize
```

### 8. Test
- Visit: https://bigsavinghub.com
- Test image uploads
- Check: https://bigsavinghub.com/uploads/test.txt

## Troubleshooting

### Images not showing:
1. Check if `public_html/uploads/` directory exists
2. Check permissions: `chmod 755 public_html/uploads`
3. Verify APP_URL in .env is correct
4. Clear cache: `php artisan config:clear`

### File uploads not working:
1. Check storage permissions
2. Verify filesystem configuration
3. Check PHP upload limits in cPanel

### Database connection issues:
1. Verify database credentials in .env
2. Check if database exists
3. Run migrations if needed: `php artisan migrate`

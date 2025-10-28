# Deployment Guide for New Device/Server

This guide will help you set up the SureScholarship system on a new device.

## Prerequisites

- PHP 8.1 or higher
- MySQL/MariaDB
- Composer
- Node.js & NPM (for assets)

## Step-by-Step Setup

### 1. Clone/Copy the Project
```bash
# If using Git
git clone <repository-url>
cd SureScholarShip

# Or simply copy the entire project folder
```

### 2. Install Dependencies
```bash
# Install PHP dependencies
composer install

# Install Node dependencies (if using)
npm install
```

### 3. Environment Configuration
```bash
# Copy the example environment file
copy .env.example .env

# Edit .env file and configure:
# - Database credentials (DB_DATABASE, DB_USERNAME, DB_PASSWORD)
# - E-Kalinga database connection
# - E-Tala database connection
# - Application URL
```

### 4. Generate Application Key
```bash
php artisan key:generate
```

### 5. Run Migrations
```bash
# This will create all necessary tables including the grade_photo column
php artisan migrate

# If you have existing data and want to refresh
# WARNING: This will delete all data!
# php artisan migrate:fresh

# With seeding (if available)
# php artisan migrate:fresh --seed
```

### 6. Create Storage Link
```bash
php artisan storage:link
```

### 7. Set Permissions (Linux/Mac)
```bash
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

### 8. Clear Caches
```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
```

### 9. Compile Assets (if needed)
```bash
npm run build
# or for development
npm run dev
```

## Common Issues

### Issue: "Column 'grade_photo' not found"

**Solution:** Run migrations
```bash
php artisan migrate
```

This will run the migration that adds the `grade_photo` column:
`2025_01_20_000000_add_grade_photo_to_applications_table.php`

### Issue: Database connection error

**Solution:** Check your `.env` file:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_username
DB_PASSWORD=your_password

# E-Kalinga Connection
E_KALINGA_DB_HOST=127.0.0.1
E_KALINGA_DB_PORT=3306
E_KALINGA_DB_DATABASE=e_kalinga
E_KALINGA_DB_USERNAME=your_username
E_KALINGA_DB_PASSWORD=your_password

# E-Tala Connection
E_TALA_DB_HOST=127.0.0.1
E_TALA_DB_PORT=3306
E_TALA_DB_DATABASE=e_tala
E_TALA_DB_USERNAME=your_username
E_TALA_DB_PASSWORD=your_password
```

### Issue: Permission denied errors

**Solution (Linux/Mac):**
```bash
sudo chmod -R 775 storage bootstrap/cache
sudo chown -R $USER:www-data storage bootstrap/cache
```

**Solution (Windows):**
- Right-click on `storage` and `bootstrap/cache` folders
- Properties → Security → Edit → Add write permissions

## Database Schema Check

To verify if all columns exist, you can run:

```bash
php artisan tinker
```

Then in tinker:
```php
Schema::hasColumn('applications', 'grade_photo')
// Should return: true
```

## Quick Migration Fix Script

If you only need to add the missing column without affecting other data:

```bash
php artisan migrate --path=/database/migrations/2025_01_20_000000_add_grade_photo_to_applications_table.php
```

## Testing the Setup

1. Access the application: `http://localhost:8000` or your configured URL
2. Try to login with super admin credentials
3. Test creating an application to verify `grade_photo` column works

## Production Deployment

For production environments:

1. Set `APP_ENV=production` in `.env`
2. Set `APP_DEBUG=false` in `.env`
3. Run: `php artisan config:cache`
4. Run: `php artisan route:cache`
5. Run: `php artisan view:cache`
6. Set up proper web server (Nginx/Apache) configuration
7. Set up SSL certificate
8. Configure proper file permissions
9. Set up automated backups

## Need Help?

If you encounter other issues:
1. Check the Laravel log: `storage/logs/laravel.log`
2. Run: `php artisan about` to see system information
3. Verify PHP extensions: `php -m`
4. Check database connection: `php artisan db:show`


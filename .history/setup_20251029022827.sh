#!/bin/bash

echo "========================================"
echo "SureScholarship System Setup"
echo "========================================"
echo ""

echo "Step 1: Installing Composer dependencies..."
composer install
if [ $? -ne 0 ]; then
    echo "ERROR: Composer install failed!"
    exit 1
fi
echo "✓ Composer dependencies installed"
echo ""

echo "Step 2: Copying environment file..."
if [ ! -f .env ]; then
    cp .env.example .env
    echo "✓ .env file created"
else
    echo ".env file already exists, skipping..."
fi
echo ""

echo "Step 3: Generating application key..."
php artisan key:generate
echo "✓ Application key generated"
echo ""

echo "Step 4: Running database migrations..."
php artisan migrate
if [ $? -ne 0 ]; then
    echo "ERROR: Migrations failed!"
    echo "Please check your database configuration in .env file"
    exit 1
fi
echo "✓ Database migrations completed"
echo ""

echo "Step 5: Creating storage link..."
php artisan storage:link
echo "✓ Storage link created"
echo ""

echo "Step 6: Setting permissions..."
chmod -R 775 storage bootstrap/cache
echo "✓ Permissions set"
echo ""

echo "Step 7: Clearing caches..."
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
echo "✓ Caches cleared"
echo ""

echo "========================================"
echo "Setup Complete!"
echo "========================================"
echo ""
echo "Next steps:"
echo "1. Edit .env file with your database credentials"
echo "2. Run: php artisan serve"
echo "3. Access: http://localhost:8000"
echo ""


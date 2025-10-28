@echo off
echo ========================================
echo SureScholarship System Setup
echo ========================================
echo.

echo Step 1: Installing Composer dependencies...
call composer install
if %errorlevel% neq 0 (
    echo ERROR: Composer install failed!
    pause
    exit /b %errorlevel%
)
echo ✓ Composer dependencies installed
echo.

echo Step 2: Copying environment file...
if not exist .env (
    copy .env.example .env
    echo ✓ .env file created
) else (
    echo .env file already exists, skipping...
)
echo.

echo Step 3: Generating application key...
call php artisan key:generate
echo ✓ Application key generated
echo.

echo Step 4: Running database migrations...
call php artisan migrate
if %errorlevel% neq 0 (
    echo ERROR: Migrations failed!
    echo Please check your database configuration in .env file
    pause
    exit /b %errorlevel%
)
echo ✓ Database migrations completed
echo.

echo Step 5: Creating storage link...
call php artisan storage:link
echo ✓ Storage link created
echo.

echo Step 6: Clearing caches...
call php artisan config:clear
call php artisan cache:clear
call php artisan route:clear
call php artisan view:clear
echo ✓ Caches cleared
echo.

echo ========================================
echo Setup Complete!
echo ========================================
echo.
echo Next steps:
echo 1. Edit .env file with your database credentials
echo 2. Run: php artisan serve
echo 3. Access: http://localhost:8000
echo.
pause


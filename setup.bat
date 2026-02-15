@echo off
echo ========================================
echo Laravel Project Setup Script
echo ========================================
echo.

echo Step 1: Installing Composer dependencies...
call composer install
if %errorlevel% neq 0 (
    echo ERROR: Composer install failed
    pause
    exit /b 1
)
echo.

echo Step 2: Installing NPM dependencies...
call npm install
if %errorlevel% neq 0 (
    echo ERROR: NPM install failed
    pause
    exit /b 1
)
echo.

echo Step 3: Checking .env file...
if not exist .env (
    echo Creating .env file from .env.example...
    copy .env.example .env
    echo Generating application key...
    php artisan key:generate
) else (
    echo .env file already exists
)
echo.

echo Step 4: Running database migrations...
php artisan migrate
if %errorlevel% neq 0 (
    echo WARNING: Migration failed - check database configuration
)
echo.

echo Step 5: Creating storage link...
php artisan storage:link
echo.

echo Step 6: Clearing caches...
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear
echo.

echo Step 7: Optimizing application...
php artisan config:cache
php artisan route:cache
echo.

echo ========================================
echo Setup Complete!
echo ========================================
echo.
echo To start the development server, run:
echo   php artisan serve
echo.
echo Then visit: http://localhost:8000
echo.
pause

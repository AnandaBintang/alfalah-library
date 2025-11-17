#!/bin/bash

# Default environment
ENV="staging"

# Check for argument
if [ "$1" == "--prod" ]; then
    ENV="production"
fi

echo "=============================="
echo "Laravel Deployment Setup"
echo "Environment: $ENV"
echo "=============================="

# --- Clean previous builds ---
echo "Cleaning previous storage symlink and build files..."
# Hapus symlink storage jika ada
if [ -L "public/storage" ]; then
    rm public/storage
fi
# Hapus hasil build frontend
if [ -d "public/build" ]; then
    rm -rf public/build
fi

rm -rf composer.lock package-lock.json

# 1. Install Composer dependencies
echo "Installing Composer dependencies..."
composer install

# 2. Install NPM dependencies
echo "Installing NPM dependencies..."
npm install

# 3. Set permissions for storage and bootstrap/cache
echo "Setting permissions for storage and bootstrap/cache..."
chmod -R 775 storage bootstrap/cache

# 4. Build frontend assets
if [ "$ENV" == "production" ]; then
    echo "Building frontend for production..."
    npm run build
else
    echo "Building frontend for staging..."
    npm run build
fi

# 5. Clear and optimize Laravel caches
echo "Clearing and optimizing caches..."
php artisan optimize:clear

# setup job batches
#echo "Run Job batches setup"
#php artisan make:queue-batches-table
#php artisan make:notifications-table


# 7. Environment setup
echo "Setting up environment..."
# Uncomment if you want to copy .env.example
# cp .env.example .env
php artisan key:generate

php artisan vendor:publish --tag=filament-actions-migrations

php artisan generate:import-template

# 8. Run database migrations and seeders
echo "Running migrations and seeders..."
if [ "$ENV" == "production" ]; then
    php artisan migrate --force
    php artisan db:seed --class=UserSeeder --force
else
    php artisan migrate:fresh
    php artisan db:seed
fi

# 6. Create storage symlink
echo "Creating storage symlink..."
php artisan storage:link


echo "=============================="
echo "Setup completed for $ENV environment!"
echo "=============================="

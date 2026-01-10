# Railway.app Deployment Fix

## Issue: Missing PHP GD Extension

The error shows:
```
mpdf/mpdf v8.2.5 requires ext-gd * -> it is missing from your system
```

## Solution 1: Add GD Extension to Build Script

In your deployment configuration, update the PHP extensions installation to include `gd`:

```bash
install-php-extensions calendar pdo openssl mbstring intl curl pdo_mysql \
  tokenizer ctype dom fileinfo filter hash pcre session xml redis gd zip
```

## Solution 2: Create Dockerfile

Create a `Dockerfile` in your project root:

```dockerfile
FROM php:8.2-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libfreetype6-dev \
    libjpeg62-turbo-dev

# Install PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd intl

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /app

# Copy application files
COPY . .

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# Install Node dependencies and build assets
RUN npm install && npm run build

# Set permissions
RUN chown -R www-data:www-data /app/storage /app/bootstrap/cache

EXPOSE 8000

CMD php artisan serve --host=0.0.0.0 --port=8000
```

## Solution 3: Create nixpacks.toml (for Railway)

Create `nixpacks.toml` in project root:

```toml
[phases.setup]
nixPkgs = ['php82', 'php82Extensions.gd', 'php82Extensions.pdo', 'php82Extensions.pdo_mysql', 'php82Extensions.mbstring', 'php82Extensions.intl', 'php82Extensions.xml', 'php82Extensions.curl', 'php82Extensions.zip', 'php82Extensions.bcmath', 'php82Extensions.redis', 'composer', 'nodejs']

[phases.build]
cmds = [
    'composer install --no-dev --optimize-autoloader',
    'npm install',
    'npm run build',
    'php artisan config:cache',
    'php artisan route:cache',
    'php artisan view:cache'
]

[start]
cmd = 'php artisan serve --host=0.0.0.0 --port=$PORT'
```

## Solution 4: Render.com Configuration

If using Render.com, create `render.yaml`:

```yaml
services:
  - type: web
    name: perfume-palace
    env: docker
    plan: free
    buildCommand: |
      composer install --no-dev --optimize-autoloader
      npm install
      npm run build
      php artisan key:generate
      php artisan config:cache
      php artisan route:cache
      php artisan view:cache
    startCommand: php artisan serve --host=0.0.0.0 --port=$PORT
    envVars:
      - key: APP_ENV
        value: production
      - key: APP_DEBUG
        value: false
      - key: APP_KEY
        generateValue: true
      - key: DB_CONNECTION
        value: mysql
      - key: DB_HOST
        fromDatabase:
          name: perfume-palace-db
          property: host
      - key: DB_PORT
        fromDatabase:
          name: perfume-palace-db
          property: port
      - key: DB_DATABASE
        fromDatabase:
          name: perfume-palace-db
          property: database
      - key: DB_USERNAME
        fromDatabase:
          name: perfume-palace-db
          property: user
      - key: DB_PASSWORD
        fromDatabase:
          name: perfume-palace-db
          property: password

databases:
  - name: perfume-palace-db
    plan: free
```

## Quick Fix Commands:

If you have shell access to your deployment:

```bash
# Install GD extension
apt-get update
apt-get install -y libpng-dev libjpeg-dev libfreetype6-dev
docker-php-ext-configure gd --with-freetype --with-jpeg
docker-php-ext-install gd

# Then retry composer install
composer install --optimize-autoloader --no-scripts --no-interaction
```

## Verify GD Installation:

```bash
php -m | grep gd
```

Should output: `gd`

## Alternative: Ignore Platform Requirements (Temporary)

```bash
composer install --optimize-autoloader --no-scripts --no-interaction --ignore-platform-req=ext-gd
```

⚠️ **Warning**: This will install dependencies but image processing won't work!

---

## Recommended Approach for Railway:

1. Create `nixpacks.toml` with GD extension
2. Push to GitHub
3. Railway will auto-redeploy with correct PHP extensions

## Which Platform Are You Using?

Tell me which platform you're deploying to and I'll provide specific instructions!

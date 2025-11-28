# Multi-stage Dockerfile for Laravel with FrankenPHP

# Stage 1: Base image with FrankenPHP
FROM dunglas/frankenphp:latest-php8.3 AS base

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libzip-dev \
    libpq-dev \
    libicu-dev \
    && docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd zip intl opcache \
    && pecl install redis \
    && docker-php-ext-enable redis \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Install Node.js and npm (for asset building in production)
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /app

# Configure PHP for production
RUN echo "opcache.enable=1" >> /usr/local/etc/php/conf.d/opcache.ini \
    && echo "opcache.memory_consumption=128" >> /usr/local/etc/php/conf.d/opcache.ini \
    && echo "opcache.interned_strings_buffer=8" >> /usr/local/etc/php/conf.d/opcache.ini \
    && echo "opcache.max_accelerated_files=10000" >> /usr/local/etc/php/conf.d/opcache.ini \
    && echo "opcache.revalidate_freq=2" >> /usr/local/etc/php/conf.d/opcache.ini \
    && echo "opcache.fast_shutdown=1" >> /usr/local/etc/php/conf.d/opcache.ini

# Configure PHP memory and limits for resource-constrained environments
RUN echo "memory_limit=256M" >> /usr/local/etc/php/conf.d/custom.ini \
    && echo "upload_max_filesize=20M" >> /usr/local/etc/php/conf.d/custom.ini \
    && echo "post_max_size=20M" >> /usr/local/etc/php/conf.d/custom.ini \
    && echo "max_execution_time=300" >> /usr/local/etc/php/conf.d/custom.ini

# Stage 2: Development environment
FROM base AS development

# Set environment
ENV APP_ENV=local \
    APP_DEBUG=true

# Copy FrankenPHP Caddyfile for development (without worker mode)
COPY docker/frankenphp/Caddyfile.dev /etc/caddy/Caddyfile

# Expose port
EXPOSE 8080

# Start FrankenPHP
CMD ["frankenphp", "run", "--config", "/etc/caddy/Caddyfile"]

# Stage 3: Production build (optimized)
FROM base AS production

# Copy application files
COPY src /app

# Install production dependencies only
RUN composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist

# Build frontend assets if needed
RUN if [ -f "package.json" ]; then \
        npm install && npm run build; \
    fi

# Set permissions before optimization
RUN chown -R www-data:www-data /app/storage /app/bootstrap/cache \
    && chmod -R 775 /app/storage /app/bootstrap/cache

# Note: Laravel optimization commands will run via deploy script
# to ensure they use the correct environment variables

# Set environment
ENV APP_ENV=production \
    APP_DEBUG=false

# Copy FrankenPHP Caddyfile for production
COPY docker/frankenphp/Caddyfile.prod /etc/caddy/Caddyfile

# Expose port
EXPOSE 8080

# Health check
HEALTHCHECK --interval=30s --timeout=10s --start-period=40s \
    CMD curl -f http://localhost:8080/ || exit 1

# Start FrankenPHP
CMD ["frankenphp", "run", "--config", "/etc/caddy/Caddyfile"]


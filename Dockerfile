

FROM php:8.0-fpm
ARG APP_ENV=prod

WORKDIR /var/www

# Install system dependencies
RUN apt-get update && apt-get install -y \
    nginx \
    build-essential \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libmariadb-dev \
    locales \
    zip \
    jpegoptim optipng pngquant gifsicle \
    vim \
    unzip \
    git \
    curl \
    libzip-dev \
    libpq-dev \
    libonig-dev \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install gd
RUN docker-php-ext-install pdo pdo_mysql pdo_pgsql mbstring zip exif pcntl

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set up project directory and permissions
COPY . /var/www
COPY .env.${APP_ENV} /var/www/.env

RUN mkdir -p /var/www/storage/framework/cache /var/www/storage/framework/sessions /var/www/storage/framework/views /var/www/storage/logs \
    && chmod -R 775 /var/www/storage /var/www/bootstrap/cache /var/www/storage/logs \
    && chown -R www-data:www-data /var/www

# Ensure composer is executable
RUN chmod +x /usr/bin/composer

# Clear Composer cache and install dependencies
RUN composer clear-cache
RUN composer install --prefer-dist --no-interaction --no-dev --optimize-autoloader

# Switch to www-data user
USER www-data

# Copy Nginx configuration
# COPY nginx.conf /etc/nginx/sites-available/default

# Expose port 80
EXPOSE 80

# Install additional packages and supervisor
USER root
RUN apt-get update && \
    apt-get install -y --no-install-recommends software-properties-common supervisor && \
    apt-get clean && rm -rf /var/lib/apt/lists/*

# Copy supervisor configuration
COPY supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Start supervisor to manage services
CMD ["/usr/bin/supervisord"]
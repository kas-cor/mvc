FROM php:8.2-fpm

# Set working directory
WORKDIR /var/www/html

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    zip \
    unzip \
    nodejs \
    npm

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip intl

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Create system user to run Composer and Artisan Commands
RUN useradd -G www-data,root -u 1000 -d /home/dev dev && \
    mkdir -p /home/dev/.composer && \
    chown -R dev:dev /home/dev

# Copy existing application directory
COPY . /var/www/html

# Set permissions
RUN chown -R dev:dev /var/www/html && \
    chmod -R 755 /var/www/html

# Install dependencies
USER dev
RUN composer install --no-interaction --optimize-autoloader

# Generate application key (if needed)
# RUN php bin/console app:key:generate

# Expose port 9000
EXPOSE 9000

CMD ["php-fpm"]

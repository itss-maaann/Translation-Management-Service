FROM php:8.1-fpm

# Arguments for user setup (optional)
ARG user
ARG uid

# Install system dependencies and build tools
RUN apt-get update && apt-get install -y \
    git \
    curl \
    autoconf \
    gcc \
    make \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libzip-dev \
    libpq-dev \
  && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install \
    pdo_mysql \
    mbstring \
    exif \
    pcntl \
    bcmath \
    xml \
    zip

# Install and enable the phpredis extension
RUN pecl install redis \
    && docker-php-ext-enable redis

# Install and enable Xdebug for coverage
RUN pecl install xdebug \
    && docker-php-ext-enable xdebug

# Install Composer
COPY --from=composer:2.3 /usr/bin/composer /usr/bin/composer

# Create system user
RUN useradd -G www-data,root -u ${uid:-1000} -m tms

# Set working directory
WORKDIR /var/www/html

USER tms

# Expose port 9000 for php-fpm
EXPOSE 9000

CMD ["php-fpm"]
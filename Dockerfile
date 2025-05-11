# ─────────── FRONTEND BUILDER ───────────
FROM node:18-alpine AS frontend-builder

WORKDIR /app

# Copy only the manifest to leverage layer caching
COPY src/tms-frontend/package.json ./
RUN npm install

# Copy the rest of your frontend code and build
COPY src/tms-frontend/ .
RUN npm run build

# ─────────── PHP APP ───────────
FROM php:8.1-fpm AS php-app

ARG uid=1000

# 1) System deps + PHP extensions
RUN apt-get update && apt-get install -y \
      git curl default-mysql-client autoconf gcc g++ make \
      libpng-dev libxml2-dev libonig-dev zip unzip libzip-dev libpq-dev \
    && rm -rf /var/lib/apt/lists/* \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath xml zip

# 2) Redis & Xdebug
RUN pecl install redis xdebug \
    && docker-php-ext-enable redis xdebug

# 3) Tell php-fpm to listen on TCP 9000 instead of socket
RUN sed -i \
      -e "s#^listen = .*#listen = 0.0.0.0:9000#" \
      -e "/^listen.owner/d" \
      -e "/^listen.group/d" \
      -e "/^listen.mode/d" \
    /usr/local/etc/php-fpm.d/www.conf

# 4) Composer
COPY --from=composer:2.3 /usr/bin/composer /usr/bin/composer

# 5) Create a non-root user
RUN useradd -m -u ${uid} tms

# 6) Copy Laravel app & entrypoint
WORKDIR /var/www/html
COPY src/ .
COPY setup.sh /usr/local/bin/setup.sh
RUN chmod +x /usr/local/bin/setup.sh

# 7) Copy built frontend into public/
COPY --from=frontend-builder /app/dist /var/www/html/public

# 8) Drop privileges and expose
USER tms
EXPOSE 9000
ENTRYPOINT ["setup.sh"]
CMD ["php-fpm"]
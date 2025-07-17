FROM php:8.1-fpm

# Tạo user riêng cho Laravel
RUN useradd -G www-data,root -u 1000 -d /home/laravel laravel && \
    mkdir -p /home/laravel && chown -R laravel:laravel /home/laravel

# Cài các gói hệ thống cần thiết
RUN apt-get update && apt-get install -y \
    git curl zip unzip libzip-dev libpng-dev libjpeg-dev libfreetype6-dev \
    libonig-dev libxml2-dev libssl-dev libcurl4-openssl-dev libicu-dev g++ \
    libmagickwand-dev libmagickcore-dev libxslt-dev nginx \
    default-mysql-client

# Cài extension PHP (có pdo_mysql)
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_mysql gd zip exif pcntl bcmath

# Redis
RUN pecl install redis \
    && docker-php-ext-enable redis

# Cài Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# NodeJS (cho InertiaJS / Vite)
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get install -y nodejs

# Set thư mục làm việc
WORKDIR /var/www/html

# Copy project vào container
COPY . .

# Cài Laravel & frontend
RUN composer install && npm install && npm run build

# Chỉnh quyền
RUN chown -R laravel:www-data /var/www/html

USER laravel

EXPOSE 9000
CMD ["php-fpm"]

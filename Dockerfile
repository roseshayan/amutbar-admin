# Multi-stage production Dockerfile for Amutbar PHP Backend
FROM php:8.2-fpm-alpine

# نصب پکیج‌های سیستمی و اکستنشن‌های PHP مورد نیاز
RUN apk add --no-cache \
    nginx \
    curl \
    git \
    unzip \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    ffmpeg \
    bash \
    icu-dev \
    libzip-dev

# کامپایل و نصب ماژول‌های PHP
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
        pdo \
        pdo_mysql \
        gd \
        intl \
        zip \
        opcache \
        fileinfo

# نصب Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# تنظیم دایرکتوری کاری
WORKDIR /var/www/html

# کپی فایل‌های composer و نصب وابستگی‌ها
COPY composer.json composer.lock* ./
RUN composer install --no-dev --prefer-dist --optimize-autoloader --no-scripts --no-interaction

# کپی کامل پروژه
COPY . .

# ساخت پوشه‌های ذخیره‌سازی و تنظیم دسترسی‌ها
RUN mkdir -p storage/logs storage/uploads/avatars storage/uploads/videos storage/uploads/tickets \
    && chown -R www-data:www-data storage \
    && chmod -R 775 storage

EXPOSE 9000

CMD ["php-fpm"]

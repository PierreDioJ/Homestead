FROM php:8.3-fpm

# Обновление системы и установка зависимостей
RUN apt-get update && apt-get upgrade -y
RUN apt-get install -y libpng-dev libjpeg-dev libfreetype6-dev libzip-dev unzip git

# Установка расширений PHP
RUN docker-php-ext-configure gd --with-freetype --with-jpeg
RUN docker-php-ext-install gd pdo pdo_mysql zip opcache && docker-php-ext-enable gd pdo_mysql opcache

# Установка Xdebug
RUN pecl install xdebug-3.3.2 && docker-php-ext-enable xdebug

# Установка Composer (если его нет в контейнере)
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Копируем конфиги для PHP
COPY docker-config/php/conf.d/* $PHP_INI_DIR/conf.d/

CMD ["php-fpm"]

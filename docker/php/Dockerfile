FROM php:8.1-fpm

# Dependencias para PHP y Node
RUN apt-get update && apt-get install -y \
    git curl zip unzip \
    libpng-dev libjpeg-dev libfreetype6-dev libonig-dev libxml2-dev \
    gnupg2 ca-certificates lsb-release libzip-dev libcurl4-openssl-dev \
    && docker-php-ext-install pdo mbstring exif pcntl bcmath gd

# Instalar Node.js (LTS) y npm
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get install -y nodejs

# Instalar MongoDb
RUN pecl install mongodb \
    && docker-php-ext-enable mongodb

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

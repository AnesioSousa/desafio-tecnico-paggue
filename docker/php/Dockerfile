FROM php:8.1-apache

# Instalar dependências
RUN apt-get update && apt-get install -y \
    libpq-dev unzip curl git zip \
    && docker-php-ext-install pdo_pgsql

# Instalar Xdebug
RUN pecl install xdebug && docker-php-ext-enable xdebug

# Ativar mod_rewrite
RUN a2enmod rewrite

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

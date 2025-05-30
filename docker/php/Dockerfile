FROM php:8.2-apache

# Instalar dependências
RUN apt-get update && apt-get install -y \
    libpq-dev unzip curl git zip \
    && docker-php-ext-install pdo_pgsql

# Instalar Xdebug
RUN pecl install xdebug && docker-php-ext-enable xdebug

# Install the PHP redis extension
RUN pecl install redis \
 && docker-php-ext-enable redis

# Ativar mod_rewrite
RUN a2enmod rewrite

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

ARG UID=1000
ARG GID=1000

RUN addgroup --gid $GID appgroup && \
    adduser --disabled-password --gecos '' --uid $UID --gid $GID appuser

USER appuser

WORKDIR /var/www/html

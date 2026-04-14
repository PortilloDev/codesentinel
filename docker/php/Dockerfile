ARG PHP_VERSION=8.4
FROM php:${PHP_VERSION}-fpm-alpine

# Dependencias del sistema
RUN apk add --no-cache \
    git \
    curl \
    unzip \
    libpq-dev \
    icu-dev \
    oniguruma-dev \
    linux-headers \
    $PHPIZE_DEPS

# Extensiones PHP necesarias
RUN docker-php-ext-install \
    pdo \
    pdo_pgsql \
    pgsql \
    intl \
    mbstring \
    opcache \
    sockets

# Redis extension (para Symfony Messenger y caché)
RUN pecl install redis && docker-php-ext-enable redis

# Composer
COPY --from=composer:2.7 /usr/bin/composer /usr/bin/composer

# Usuario no-root para dev
RUN addgroup -g 1000 symfony && adduser -u 1000 -G symfony -s /bin/sh -D symfony

WORKDIR /var/www/html

USER symfony

EXPOSE 9000

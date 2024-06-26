FROM php:8.2-fpm
# Install packages
#RUN apk add --no-cache curl git build-base zlib-dev oniguruma-dev autoconf bash
#RUN apk add --update linux-headers && pecl install xdebug && docker-php-ext-enable xdebug

RUN apt-get update && apt-get install -y \
    git \
    unzip \
    curl \
    libicu-dev \
    libpq-dev \
    && pecl install xdebug && docker-php-ext-enable xdebug \
    && docker-php-ext-install intl pdo_pgsql

COPY ./docker/php-fpm/xdebug.ini /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
#COPY ./docker/php-fpm/xdebug.ini /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

## Configure non-root user.
#ARG PUID=1000
#ARG PGID=1000
#RUN apk --no-cache add shadow && \
#    groupmod -o -g ${PGID} www-data && \
#    usermod -o -u ${PUID} -g www-data www-data


# Source code
COPY ./ /var/www
WORKDIR /var/www

# Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
#RUN composer install --no-interaction

CMD php-fpm

EXPOSE 9000
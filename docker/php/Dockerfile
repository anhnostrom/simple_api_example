FROM php:7.2-fpm

RUN docker-php-ext-install pdo_mysql
RUN pecl install redis && docker-php-ext-enable redis

RUN apt-get update && apt-get install -y \
        zlib1g-dev \
        libmemcached-dev \
    && pecl install memcached \
    && docker-php-ext-enable memcached
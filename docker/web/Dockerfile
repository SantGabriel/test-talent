FROM php:8.4-apache

RUN apt-get update && apt-get install -y \
    default-mysql-client \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    curl \
    git \
    vim \
    && docker-php-ext-install pdo mbstring exif pcntl bcmath gd pdo_mysql \
        && pecl install xdebug \
        && docker-php-ext-enable xdebug

RUN pecl install redis && docker-php-ext-enable redis

RUN a2enmod rewrite

COPY ./apache.conf /etc/apache2/sites-available/000-default.conf
COPY ./xdebug.ini /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

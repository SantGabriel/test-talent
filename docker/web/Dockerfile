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

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

COPY ./apache.conf /etc/apache2/sites-available/000-default.conf
COPY ./xdebug.ini /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

ARG UID=1000
ARG GID=1000
RUN groupadd -g ${GID} docker && \
    useradd -m -u ${UID} -g ${GID} docker

USER docker
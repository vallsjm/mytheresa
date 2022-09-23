FROM php:8.1-fpm AS php-devel

RUN apt-get update \
    && apt-get install -y git libzip-dev zip unzip openssl

RUN docker-php-ext-install mysqli pdo_mysql

RUN pecl install zip \
    && docker-php-ext-enable zip

RUN php -r "readfile('http://getcomposer.org/installer');" | php -- --install-dir=/usr/bin/ --filename=composer

FROM php-devel AS php-debug

RUN pecl install xdebug \
    && docker-php-ext-enable xdebug

RUN touch /var/log/xdebug.log \
    && chmod 777 /var/log/xdebug.log

WORKDIR /var/www/html
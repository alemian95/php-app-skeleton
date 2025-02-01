FROM php:8.3-apache

RUN apt-get update && apt-get install -y \
    libzip-dev \
    libicu-dev \
    libpng-dev \
    zip \
    unzip

RUN docker-php-ext-install gd zip intl bcmath pdo pdo_mysql

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN a2enmod rewrite

ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

COPY apache2.conf /etc/apache2/conf-enabled/
COPY php_ini_override.ini /usr/local/etc/php/conf.d/

RUN usermod -u 1000 www-data
RUN groupmod -g 1000 www-data
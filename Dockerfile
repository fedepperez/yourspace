FROM php:8.4-apache
WORKDIR /var/www/html

RUN apt-get update && apt-get install -y git unzip \
 && docker-php-ext-install opcache

RUN a2enmod rewrite
RUN docker-php-ext-install pdo pdo_mysql

COPY . /var/www/html
#COPY ./vhost.conf /etc/apache2/sites-available/000-default.conf

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

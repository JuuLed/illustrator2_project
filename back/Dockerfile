FROM php:5.6-apache

# Installation de l'extension pdo_mysql
RUN docker-php-ext-install pdo_mysql
RUN a2enmod rewrite

COPY . /var/www/html/

EXPOSE 80
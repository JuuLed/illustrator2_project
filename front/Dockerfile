FROM php:5.6-apache

COPY . /var/www/html/front
COPY ./000-default.conf /etc/apache2/sites-available/000-default.conf
RUN a2enmod rewrite
EXPOSE 80
CMD ["apachectl", "-D", "FOREGROUND"]
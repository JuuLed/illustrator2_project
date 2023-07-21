FROM php:5.6-apache

RUN apt-get update && apt-get install -y \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    curl \
    zip \
    unzip \
 && docker-php-ext-install -j$(nproc) iconv \
 && docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ \
 && docker-php-ext-install -j$(nproc) gd \
 && apt-get clean; rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*

# Installation de l'extension pdo_mysql
RUN docker-php-ext-install pdo_mysql

# Installer Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Configurer le répertoire de travail
WORKDIR /var/www/html/

# Copier le fichier composer.lock et composer.json si vous les avez
COPY composer.lock composer.json /var/www/html/

# Installer les dépendances
RUN composer install

RUN a2enmod rewrite
RUN a2enmod ssl

# Copier le code de l'application dans le conteneur
COPY . /var/www/html/

EXPOSE 80 443

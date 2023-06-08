ma structure :
illustrator2_project/
├── docker-compose.yml
├── back/
│   ├── config/
│   │   ├── init.sql
│   │   └── database.php
│   ├── controllers/
│   │   ├── CategoryController.php
│   │   ├── KeywordController.php
│   │   ├── LanguageController.php
│   │   ├── SymbolCategoryController.php
│   │   ├── SymbolController.php
│   │   ├── SymbolKeywordController.php
│   │   └── TranslateController.php
│   ├── models/
│   │   ├── Category.php
│   │   ├── Keyword.php
│   │   ├── Language.php
│   │   ├── Symbol.php
│   │   ├── SymbolCategory.php
│   │   ├── SymbolKeyword.php
│   │   └── Translate.php
│   ├── Dockerfile
│   └── index.php
└── front/
    ├── css/
    │   └── style.css
    ├── js/
    │   └── script.js
    ├── views/
    │   ├── home.php
    │   ├── symbols.php
    │   ├── category.php
    │   ├── keyword.php
    │   ├── language.php
    │   ├── upload.php
    │   ├── edit.php
    │   ├── stats.php
    │   └── login.php
    ├── Dockerfile
    ├── 000-default.conf
    └── index.php

mon dockerfile back :
FROM php:5.6-apache
COPY . /var/www/html/back
EXPOSE 80

mon dockerfile front :
FROM php:5.6-apache
COPY . /var/www/html/front
COPY ./000-default.conf /etc/apache2/sites-available/000-default.conf
RUN a2enmod rewrite
EXPOSE 80
CMD ["apachectl", "-D", "FOREGROUND"]

mon 000-default-conf :
<VirtualHost *:80>
    DocumentRoot /var/www/html/front

    <Directory /var/www/html/front>
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>

    <Directory /var/www/html/back>
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
        RewriteEngine On
        RewriteBase /back/
        RewriteRule ^index\.php$ - [L]
        RewriteCond %{REQUEST_FILENAME} !-f
        RewriteCond %{REQUEST_FILENAME} !-d
        RewriteRule . /back/index.php [L]
    </Directory>

    ErrorLog ${APACHE_LOG_DIR}/error.log
    CustomLog ${APACHE_LOG_DIR}/access.log combined
</VirtualHost>

mon docker-compose.yml :
services:
  db:
    image: mariadb:10.6.5
    environment:
      MYSQL_DATABASE: illustrator2
      MYSQL_USER: root
      MYSQL_PASSWORD: ''
      MYSQL_ALLOW_EMPTY_PASSWORD: 'yes'
    volumes:
      - ./back/config/database.php:/docker-entrypoint-initdb.d/database.php
      - ./back/config/init.sql:/docker-entrypoint-initdb.d/init.sql

  backend:
    build:
      context: ./back
      dockerfile: Dockerfile
    ports:
      - 80:80
    depends_on:
      - db

  frontend:
    build:
      context: ./front
      dockerfile: Dockerfile
    ports:
      - 8080:80
    depends_on:
      - backend

  phpmyadmin:
    image: phpmyadmin/phpmyadmin
    environment:
      PMA_HOST: db
      PMA_USER: root
      PMA_PASSWORD: ''
    ports:
      - 8081:80
    depends_on:
      - db

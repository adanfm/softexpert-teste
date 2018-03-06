FROM php:5.6-apache

MAINTAINER Adan Felipe Medeiros <adan.grg@gmail.com>

RUN apt-get update && apt-get install -y libmcrypt-dev libicu-dev libssl-dev git libpq-dev

#Install VIM
RUN apt-get install -y vim

#Install PHP Extensions
RUN docker-php-ext-install mcrypt
RUN docker-php-ext-install pdo pdo_pgsql
RUN docker-php-ext-install mbstring
RUN docker-php-ext-install zip
RUN docker-php-ext-configure intl && docker-php-ext-install intl

#Install xdebug
RUN pecl install xdebug-2.5.5
RUN docker-php-ext-enable xdebug

#Install composer

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"

RUN php composer-setup.php

RUN php -r "unlink('composer-setup.php');"

RUN mv composer.phar /usr/local/bin/composer

# Install Node & NPM
RUN curl -sL https://deb.nodesource.com/setup_9.x | /bin/bash
RUN apt-get install -y nodejs

# Install gulp
RUN npm install -g gulp-cli

#Enable Module Rewrite
RUN a2enmod rewrite

WORKDIR /var/www/html
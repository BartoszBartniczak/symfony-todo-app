FROM php:7.3-fpm

RUN apt-get update && buildDeps="libpq-dev libzip-dev" && apt-get install -y $buildDeps git nano wget vim --no-install-recommends
RUN docker-php-ext-install pdo pdo_pgsql pgsql zip bcmath mbstring calendar
#RUN docker-php-ext-install gettext
RUN pecl install xdebug && docker-php-ext-enable xdebug
#RUN pecl install memcached && docker-php-ext-enable memcached


RUN apt-get update && apt-get install -y zlib1g-dev libicu-dev g++ libjpeg-dev libpng-dev
RUN docker-php-ext-configure intl
RUN docker-php-ext-install intl

#RUN rm /etc/apt/preferences.d/no-debian-php
#RUN apt-get update -y && apt-get install -y libxml2-dev php-soap  && apt-get clean -y
#RUN apt-get install -y libxml2-dev
#RUN docker-php-ext-install soap

#RUN docker-php-ext-enable mcrypt
#RUN pecl install curl && docker-php-ext-enable curl
#RUN pecl install recode && docker-php-ext-enable recode
#RUN docker-php-ext-configure gd --with-jpeg-dir=/usr/lib/x86_64-linux-gnu/
#RUN docker-php-ext-install gd && docker-php-ext-enable gd
#RUN pecl install xsl && docker-php-ext-enable xsl
RUN docker-php-ext-install opcache
RUN pecl install apcu && docker-php-ext-enable apcu

RUN wget https://getcomposer.org/composer.phar && mv composer.phar /usr/bin/composer && chmod +x /usr/bin/composer

WORKDIR /app

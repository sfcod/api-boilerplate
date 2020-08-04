FROM php:7.3-apache

ARG HOST_UID
ARG OAUTH_KEY

VOLUME ["/var/www/html"]

ENV DEBIAN_FRONTEND noninteractive

RUN rm /etc/apt/preferences.d/no-debian-php

RUN rm -rf /var/lib/apt/lists/* && apt update

RUN apt-get update && apt-get install -y apt-utils \
        openssh-server \
        sudo \
        zlib1g-dev \
        libicu-dev \
        libpq-dev \
        libzip-dev \
        git \
        vim \
        zip \
        wget \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libmcrypt-dev \
        libpng-dev \
        libgmp-dev \
        htop \
        mc \
        gnupg \
        libxml2-dev \
        php-soap \
    && docker-php-ext-install -j$(nproc) iconv \
    && docker-php-ext-configure mbstring \
    && docker-php-ext-install -j$(nproc) mbstring \
    && docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ \
    && docker-php-ext-install -j$(nproc) gd \
    && docker-php-ext-install intl zip opcache pcntl \
    && docker-php-ext-configure calendar --enable-calendar \
    && docker-php-ext-install -j$(nproc) calendar

# Install mysql pdo
# RUN apt-get install -y default-mysql-client
# RUN docker-php-ext-install pdo_mysql mysqli

# Install pgsql pdo
RUN echo "deb http://apt.postgresql.org/pub/repos/apt/ stretch-pgdg main" > /etc/apt/sources.list.d/pgdg.list \
    && wget --quiet -O - https://www.postgresql.org/media/keys/ACCC4CF8.asc | sudo apt-key add - \
    && apt-get update && apt-get install -y postgresql-client
RUN docker-php-ext-install pdo pdo_pgsql soap

RUN a2enmod rewrite && a2enmod headers

RUN echo Europe/London > /etc/timezone && \
    dpkg-reconfigure -f noninteractive tzdata

COPY project.conf /etc/apache2/sites-available/000-default.conf
COPY apache2.conf /etc/apache2/apache2.conf
COPY php.ini /usr/local/etc/php/

RUN echo "LogFormat \"%a %l %u %t \\\"%r\\\" %>s %O \\\"%{User-Agent}i\\\"\" mainlog" >> /etc/apache2/apache2.conf
RUN a2ensite 000-default.conf

RUN set -x && curl -sS https://getcomposer.org/installer | php \
    && mv composer.phar /usr/local/bin/composer

RUN usermod -u ${HOST_UID} -d /var/www -s /bin/bash -p secret www-data \
    && groupmod -g ${HOST_UID} www-data \
    && chown -R www-data:www-data /var/www

RUN sudo -u www-data composer config --global github-oauth.github.com "${OAUTH_KEY}"

RUN curl -sL https://deb.nodesource.com/setup_12.x | bash \
    && apt-get install -y nodejs
RUN npm i -g yarn

RUN yes | pecl install xdebug \
    && echo "zend_extension=$(find /usr/local/lib/php/extensions/ -name xdebug.so)" > /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.remote_enable=on" >> /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.remote_autostart=off" >> /usr/local/etc/php/conf.d/xdebug.ini

WORKDIR /var/www/html/sources

EXPOSE 80 22

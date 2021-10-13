FROM php:7.4-apache

ARG HOST_UID
ARG OAUTH_KEY

VOLUME ["/var/www/html"]

ENV DEBIAN_FRONTEND noninteractive

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
        libonig-dev \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libmcrypt-dev \
        libpng-dev \
        libgmp-dev \
        htop \
        mc \
        gnupg \
        libxml2-dev \
    && docker-php-ext-install -j$(nproc) iconv \
    && docker-php-ext-configure gd --with-freetype=/usr/include/ --with-jpeg=/usr/include/ \
    && docker-php-ext-install -j$(nproc) gd \
    && docker-php-ext-install intl zip opcache pcntl \
    && docker-php-ext-configure calendar --enable-calendar \
    && docker-php-ext-install -j$(nproc) calendar

#php-redis
RUN pecl install -o -f redis \
    &&  rm -rf /tmp/pear \
    &&  docker-php-ext-enable redis

# inky-extra
RUN apt install -y libxslt-dev \
    && docker-php-ext-install xsl

# Install pgsql pdo
RUN apt update && apt-get install -y postgresql-client
RUN docker-php-ext-install pdo pdo_pgsql

RUN a2enmod rewrite && a2enmod headers

RUN echo Europe/London > /etc/timezone && \
    dpkg-reconfigure -f noninteractive tzdata

# Configs
COPY conf/project.conf /etc/apache2/sites-available/000-default.conf
COPY conf/apache2.conf /etc/apache2/apache2.conf
COPY conf/php.ini /usr/local/etc/php/

RUN echo "LogFormat \"%a %l %u %t \\\"%r\\\" %>s %O \\\"%{User-Agent}i\\\"\" mainlog" >> /etc/apache2/apache2.conf
RUN a2ensite 000-default.conf

RUN set -x && curl -sS https://getcomposer.org/installer | php \
    && mv composer.phar /usr/local/bin/composer

RUN usermod -u ${HOST_UID} -d /var/www -s /bin/bash -p secret www-data \
    && groupmod -g ${HOST_UID} www-data \
    && chown -R www-data:www-data /var/www

RUN sudo -u www-data composer config --global github-oauth.github.com "${OAUTH_KEY}"

RUN curl -sL https://deb.nodesource.com/setup_14.x | bash \
    && apt-get install -y nodejs
RUN npm i -g yarn

WORKDIR /var/www/html/sources

EXPOSE 80

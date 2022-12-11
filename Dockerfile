FROM node:lts AS nodebuild
WORKDIR /app
COPY . /app
RUN npm install && npm run build

FROM php:8.1-apache
WORKDIR /var/www/html/

COPY . /var/www/html

COPY --from=nodebuild /app/public/build /var/www/html/public/build

RUN apt-get update && apt-get install -y \
    git \
    libsodium-dev \
    && docker-php-ext-install sodium \
    && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install mysqli pdo pdo_mysql

RUN apt-get update && apt-get install -y \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libpng-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd \
    && rm -rf /var/lib/apt/lists/*

RUN apt-get update && apt-get install -y \
        libzip-dev \
        zip \
  && docker-php-ext-install zip \
  && rm -rf /var/lib/apt/lists/*

RUN apt-get update && apt-get install -y \
        libicu-dev \
  && docker-php-ext-configure intl \
  && docker-php-ext-install intl \
  && rm -rf /var/lib/apt/lists/*

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
ENV COMPOSER_ALLOW_SUPERUSER=1
ENV PATH="${PATH}:/root/.composer/vendor/bin"

RUN set -eux; \
    mkdir -p var/cache var/log; \
    composer install --prefer-dist --no-dev --no-progress --no-scripts --no-interaction; \
    composer dump-autoload --classmap-authoritative --no-dev; \
    composer symfony:dump-env prod; \
    composer run-script --no-dev post-install-cmd; \
    chmod +x bin/console; sync

RUN chmod 777 -R var/
RUN chmod 777 -R public/images

ENV APACHE_DOCUMENT_ROOT /var/www/html/public
COPY .docker/apache.conf /etc/apache2/sites-available/000-default.conf
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf
RUN a2enmod rewrite
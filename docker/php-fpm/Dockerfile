FROM php:7.4.16-fpm-alpine

ENV APP_DEPS \
    git \
    autoconf \
    gcc \
    g++ \
    make \
    zlib-dev \
    libzip-dev \
    icu-dev \
    bash \
    libxml2-dev

ENV PHP_EXTS \
    pdo_mysql \
    mysqli \
    zip \
    ctype \
    iconv \
    intl \
    json \
    shmop

# Install system and php dependencies
RUN set -eux \
    && apk add --no-cache --update $APP_DEPS \
    && docker-php-ext-install $PHP_EXTS \
    && rm -rf /tmp/pear

# Copy php default configuration
COPY ./docker/php-fpm/default.ini /usr/local/etc/php/conf.d/default.ini

WORKDIR /var/www/html

# Install composer
RUN wget https://getcomposer.org/installer && \
   php installer --install-dir=/usr/local/bin/ --filename=composer && \
   rm installer

# Change files owner to php-fpm default user
RUN chown -R www-data:www-data .

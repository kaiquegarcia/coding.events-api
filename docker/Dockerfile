FROM php:8.0-rc-fpm

ARG APP_STAGE

ENV APP_STAGE=$APP_STAGE

WORKDIR /app

# Required bins
RUN apt-get update \
    && apt-get -y install \
    libzip-dev \
    libonig-dev \
    zip \
    curl \
    wget \
    build-essential \
    software-properties-common

# MySQL
RUN docker-php-ext-install mysqli pdo pdo_mysql \
    && docker-php-ext-enable pdo_mysql

# Download composer
RUN curl -sS https://getcomposer.org/installer | \
    php -- --instal-dir=/usr/bin --filename=composer

# Composer install
COPY ./application/composer.* ./
RUN if [ "$APP_STAGE" = 'dev']; then \
        composer install --prefer-dist --no-autoloader; \
    else \
        composer install --prefer-dist --no-autoloader --no-dev; \
    fi

# Sincronizar código-fonte
COPY ./application/ /app
RUN if [ "$APP_STAGE" = 'dev']; then \
        composer dump-autoload; \
    else \
        composer dump-autoload --no-dev; \
    fi

# Atualizar permissões de log em arquivo
RUN mkdir -p /app/storage/logs \
    && rm -f /app/storage/logs/app.log \
    && chmod -R 755 /app/storage \
    && chown -R www-data:www-data /app/storage
RUN touch /app/storage/logs/app.log \
    && chown -R www-data: /app/storage

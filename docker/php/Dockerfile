FROM php:8.0.7-fpm-alpine3.13

WORKDIR /app
ENV APP_ENV=prod


RUN set -ex \
    && apk --no-cache add postgresql-dev

RUN docker-php-ext-install pdo pdo_pgsql

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
ENV COMPOSER_ALLOW_SUPERUSER=1
ENV PATH="${PATH}:/root/.composer/vendor/bin"

COPY sshelter.ini $PHP_INI_DIR/conf.d/
COPY www.conf /usr/local/etc/php-fpm.d/www.conf

RUN set -eux; \
    mkdir -p var/cache var/log /app/config/jwt; \
    chown -R www-data /app/config/jwt

VOLUME /app/config/jwt

EXPOSE 9000

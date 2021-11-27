# This is the docker file for the prod image
# If you are searching for the dev one, those are in /docker/{php,nginx}

# I know this is not good practice to put multiple software in the same container but
# this is for easy setup

FROM php:8.0.7-fpm-alpine3.13

WORKDIR /app
ENV APP_ENV=prod


RUN set -ex \
    && apk --no-cache add postgresql-dev nginx

RUN docker-php-ext-install pdo pdo_pgsql

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
ENV COMPOSER_ALLOW_SUPERUSER=1
ENV PATH="${PATH}:/root/.composer/vendor/bin"

COPY docker/php/sshelter.ini $PHP_INI_DIR/conf.d/
COPY docker/php/www.conf /usr/local/etc/php-fpm.d/www.conf

COPY . /app
RUN ls /app

RUN set -eux; \
    mkdir -p var/cache var/log /app/config/jwt; \
    chown -R www-data /app/config/jwt

RUN composer install

VOLUME /app/config/jwt

RUN rm /etc/nginx/conf.d/default.conf && rm /etc/nginx/nginx.conf && mkdir -p /run/nginx;

COPY docker/nginx/sshelter.prod.conf /etc/nginx/conf.d/
COPY docker/nginx/nginx.prod.conf /etc/nginx/nginx.conf

VOLUME /app/var/storage

EXPOSE 80

ENTRYPOINT ["sh", "/app/entrypoint.sh"]

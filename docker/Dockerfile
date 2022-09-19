ARG PHP_VERSION=8.1.10
FROM php:${PHP_VERSION}-fpm-alpine3.16 as composer

COPY docker/install_composer.sh /install_composer.sh
RUN chmod +x /install_composer.sh && /install_composer.sh
RUN mv composer.phar /usr/bin/composer

COPY bin /app/bin
COPY config /app/config
COPY src /app/src
COPY .env.prod /app/.env
COPY composer.json /app/composer.json
COPY composer.lock /app/composer.lock
COPY symfony.lock /app/symfony.lock

WORKDIR /app

RUN composer install --no-dev --optimize-autoloader

FROM php:${PHP_VERSION}-fpm-alpine3.16 as runner
COPY --from=composer /usr/bin/composer /usr/bin
COPY --from=composer /app/bin /app/bin
COPY --from=composer /app/config /app/config
COPY --from=composer /app/src /app/src
COPY --from=composer /app/.env /app/.env
COPY --from=composer /app/vendor /app/vendor
COPY --from=composer /app/composer.json /app/composer.json

RUN touch /file.php

WORKDIR /app

ENTRYPOINT ["php", "bin/console"]
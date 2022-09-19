ARG PHP_VERSION=8.1
FROM ghcr.io/phpstan/phpstan:1.8.5-php${PHP_VERSION}
RUN composer global require spaze/phpstan-disallowed-calls
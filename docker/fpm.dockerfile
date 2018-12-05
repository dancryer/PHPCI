FROM php:7-fpm
RUN apt update && apt install -qy libcurl3-dev curl gnupg
RUN curl -sL https://deb.nodesource.com/setup_11.x | bash -
RUN apt update && apt install -qy nodejs
RUN docker-php-ext-install -j$(nproc) pdo_mysql
RUN docker-php-ext-install -j$(nproc) curl

COPY docker/fpm-entrypoint.sh /fpm-entrypoint.sh

ENTRYPOINT ["/bin/bash", "/fpm-entrypoint.sh"]

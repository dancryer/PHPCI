FROM php:7
RUN apt update && apt install -qy git-core libcurl3-dev curl
RUN docker-php-ext-install -j$(nproc) pdo_mysql
RUN docker-php-ext-install -j$(nproc) curl

RUN git config --global user.name "PHPCI" \
&&  git config --global user.email "hello@phptesting.org"

COPY docker/worker-entrypoint.sh /worker-entrypoint.sh

ENTRYPOINT ["/bin/bash", "/worker-entrypoint.sh"]

#!/bin/sh

cd /phpci

# Install composer:
php -r "readfile('https://getcomposer.org/installer');" | php \
    && mv composer.phar /usr/local/bin/composer

# Install composer dependencies:
composer install -o --no-dev

php /phpci/console phpci:worker

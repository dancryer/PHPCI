#!/bin/sh

cd /phpci

# Install composer:
php -r "readfile('https://getcomposer.org/installer');" | php \
    && mv composer.phar /usr/local/bin/composer

# Install composer dependencies:
composer install -o --no-dev

# Install npm dependencies:
npm install --production

mkdir -p ./public/assets/js/AdminLTE
mkdir -p ./public/assets/plugins

cp -Rf ./node_modules/admin-lte/dist/adminlte.min.js ./public/assets/js/AdminLTE/app.min.js
cp -Rf ./node_modules/admin-lte/dist/pages/dashboard.min.js ./public/assets/js/AdminLTE/dashboard.js
cp -Rf ./node_modules/admin-lte/dist/demo.js ./public/assets/js/AdminLTE/demo.js
cp -Rf ./node_modules/admin-lte/dist/css ./public/assets/css
cp -Rf ./node_modules/admin-lte/dist/img ./public/assets/img
cp -Rf ./node_modules/admin-lte/plugins ./public/assets/plugins

php-fpm

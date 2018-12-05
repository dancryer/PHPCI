#!/bin/sh

cd /phpci

# Install composer:
php -r "readfile('https://getcomposer.org/installer');" | php \
    && mv composer.phar /usr/local/bin/composer

# Install composer dependencies:
composer install -o --no-dev

# Install npm dependencies:
npm install --production

mkdir -p /phpci/public/assets/js/AdminLTE
mkdir -p /phpci/public/assets/css
mkdir -p /phpci/public/assets/plugins

cp -f /phpci/node_modules/admin-lte/dist/js/adminlte.min.js /phpci/public/assets/js/AdminLTE/app.min.js
cp -f /phpci/node_modules/admin-lte/dist/js/pages/dashboard.js /phpci/public/assets/js/AdminLTE/dashboard.js
cp -f /phpci/node_modules/admin-lte/dist/js/demo.js /phpci/public/assets/js/AdminLTE/demo.js
cp -Rf /phpci/node_modules/admin-lte/dist/css/AdminLTE.min.css /phpci/public/assets/css/AdminLTE.min.css
cp -Rf /phpci/node_modules/admin-lte/dist/css/skins/_all-skins.min.css /phpci/public/assets/css/AdminLTE-skins.min.css
cp -Rf /phpci/node_modules/admin-lte/dist/img /phpci/public/assets/
cp -Rf /phpci/node_modules/admin-lte/plugins /phpci/public/assets/
cp -f /phpci/node_modules/bootstrap/dist/js/bootstrap.min.js /phpci/public/assets/js/bootstrap.min.js
cp -f /phpci/node_modules/moment/min/moment.min.js /phpci/public/assets/js/moment.min.js
cp -f /phpci/node_modules/sprintf-js/dist/sprintf.min.js /phpci/public/assets/js/sprintf.js

php-fpm

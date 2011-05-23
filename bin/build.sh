#!/bin/sh

# This file is part of the Symfony Standard Edition.
#
# (c) Fabien Potencier <fabien@symfony.com>
#
# For the full copyright and license information, please view the LICENSE
# file that was distributed with this source code.

DIR=`php -r "echo realpath(dirname(\\$_SERVER['argv'][0]));"`
cd $DIR
VERSION=`cat VERSION`

if [ ! -d "$DIR/build" ]; then
    mkdir -p $DIR/build
fi

$DIR/bin/build_bootstrap.php
$DIR/app/console assets:install web/

# Without vendors
rm -rf /tmp/Symfony
mkdir /tmp/Symfony
cp -r app /tmp/Symfony/
cp -r bin /tmp/Symfony/
cp -r src /tmp/Symfony/
cp -r web /tmp/Symfony/
cp -r README.rst /tmp/Symfony/
cp -r LICENSE /tmp/Symfony/
cp -r VERSION /tmp/Symfony/
cd /tmp/Symfony
sudo rm -rf app/cache/* app/logs/* .git*
chmod 777 app/cache app/logs

# DS_Store cleanup
find . -name .DS_Store | xargs rm -rf -

cd ..
# avoid the creation of ._* files
export COPY_EXTENDED_ATTRIBUTES_DISABLE=true
export COPYFILE_DISABLE=true
tar zcpf $DIR/build/Symfony_Standard_$VERSION.tgz Symfony
sudo rm -f $DIR/build/Symfony_Standard_$VERSION.zip
zip -rq $DIR/build/Symfony_Standard_$VERSION.zip Symfony

# With vendors
cd $DIR
rm -rf /tmp/vendor
mkdir /tmp/vendor
TARGET=/tmp/vendor

if [ ! -d "$DIR/vendor" ]; then
    echo "The master vendor directory does not exist"
    exit
fi

cp -r $DIR/vendor/* $TARGET/

# Assetic
cd $TARGET/assetic && rm -rf phpunit.xml* README* tests

# Doctrine ORM
cd $TARGET/doctrine && rm -rf UPGRADE* build* bin tests tools lib/vendor

# Doctrine DBAL
cd $TARGET/doctrine-dbal && rm -rf bin build* tests lib/vendor

# Doctrine Common
cd $TARGET/doctrine-common && rm -rf build* tests lib/vendor

# Swiftmailer
cd $TARGET/swiftmailer && rm -rf CHANGES README* build* docs notes test-suite tests create_pear_package.php package*

# Symfony
cd $TARGET/symfony && rm -rf README.md phpunit.xml* tests *.sh vendor

# Twig
cd $TARGET/twig && rm -rf AUTHORS CHANGELOG README.markdown bin doc package.xml.tpl phpunit.xml* test

# Twig Extensions
cd $TARGET/twig-extensions && rm -rf README doc phpunit.xml* test

# Monolog
cd $TARGET/monolog && rm -rf README.markdown phpunit.xml* tests

# Metadata
cd $TARGET/metadata && rm -rf README.rst phpunit.xml* tests

# cleanup
find $TARGET -name .git | xargs rm -rf -
find $TARGET -name .gitignore | xargs rm -rf -
find $TARGET -name .gitmodules | xargs rm -rf -
find $TARGET -name .svn | xargs rm -rf -

cd /tmp/
mv /tmp/vendor /tmp/Symfony/
tar zcpf $DIR/build/Symfony_Standard_Vendors_$VERSION.tgz Symfony
sudo rm -f $DIR/build/Symfony_Standard_Vendors_$VERSION.zip
zip -rq $DIR/build/Symfony_Standard_Vendors_$VERSION.zip Symfony

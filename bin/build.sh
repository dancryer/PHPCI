#!/bin/sh

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
cd /tmp/Symfony
git clone --depth 1 --branch master http://github.com/symfony/symfony-standard.git .
cp $DIR/app/bootstrap* app/
cp -r $DIR/web/bundles web/
chmod 777 app/cache app/logs

cd ..
# avoid the creation of ._* files
export COPY_EXTENDED_ATTRIBUTES_DISABLE=true
export COPYFILE_DISABLE=true
tar zcpf $DIR/build/Symfony_Standard_$VERSION.tgz Symfony
sudo rm -f $DIR/build/Symfony_Standard_$VERSION.zip
zip -rq $DIR/build/Symfony_Standard_$VERSION.zip Symfony

# With vendors
TARGET=/tmp/Symfony/vendor

cp -r $DIR/vendor/* $TARGET/

# Assetic
cd $TARGET
cd assetic && rm -rf phpunit.xml* README* tests

# Doctrine ORM
cd $TARGET
cd doctrine && rm -rf UPGRADE* build* bin tests tools lib/vendor

# Doctrine DBAL
cd $TARGET
cd doctrine-dbal && rm -rf bin build* tests lib/vendor

# Doctrine Common
cd $TARGET
cd doctrine-common && rm -rf build* tests lib/vendor

# Swiftmailer
cd $TARGET
cd swiftmailer && rm -rf CHANGES README* build* docs notes test-suite tests create_pear_package.php package*

# Symfony
cd $TARGET
cd symfony && rm -rf README.md phpunit.xml* tests *.sh vendor

# Twig
cd $TARGET
cd twig && rm -rf AUTHORS CHANGELOG README.markdown bin doc package.xml.tpl phpunit.xml* test

# Twig Extensions
cd $TARGET
cd twig-extensions && rm -rf README doc phpunit.xml* test

# Monolog
cd $TARGET
cd monolog && rm -rf README.markdown phpunit.xml* tests

# cleanup
find /tmp/Symfony -name .git | xargs rm -rf -
find /tmp/Symfony -name .gitignore | xargs rm -rf -
find /tmp/Symfony -name .gitmodules | xargs rm -rf -
find /tmp/Symfony -name .svn | xargs rm -rf -

cd /tmp/
tar zcpf $DIR/build/Symfony_Standard_Vendors_$VERSION.tgz Symfony
sudo rm -f $DIR/build/Symfony_Standard_Vendors_$VERSION.zip
zip -rq $DIR/build/Symfony_Standard_Vendors_$VERSION.zip Symfony

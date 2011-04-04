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
cp -r app /tmp/Symfony/
cp -r bin /tmp/Symfony/
cp -r src /tmp/Symfony/
cp -r web /tmp/Symfony/
cp -r README.md /tmp/Symfony/
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
cd $TARGET

if [ ! -d "$DIR/vendor" ]; then
    echo "The master vendor directory does not exist"
    exit
fi

cp -r $DIR/vendor/* .

# Assetic
cd assetic && rm -rf phpunit.xml* README* tests
cd $TARGET

# Doctrine ORM
cd doctrine && rm -rf UPGRADE* build* bin tests tools lib/vendor
cd $TARGET

# Doctrine DBAL
cd doctrine-dbal && rm -rf bin build* tests lib/vendor
cd $TARGET

# Doctrine Common
cd doctrine-common && rm -rf build* tests lib/vendor
cd $TARGET

# Swiftmailer
cd swiftmailer && rm -rf CHANGES README* build* docs notes test-suite tests create_pear_package.php package*
cd $TARGET

# Symfony
cd symfony && rm -rf README.md phpunit.xml* tests *.sh vendor
cd $TARGET

# Twig
cd twig && rm -rf AUTHORS CHANGELOG README.markdown bin doc package.xml.tpl phpunit.xml* test
cd $TARGET

# Twig Extensions
cd twig-extensions && rm -rf README doc phpunit.xml* test
cd $TARGET

# Monolog
cd monolog && rm -rf README.markdown phpunit.xml* tests
cd $TARGET

# cleanup
find . -name .git | xargs rm -rf -
find . -name .gitignore | xargs rm -rf -
find . -name .gitmodules | xargs rm -rf -
find . -name .svn | xargs rm -rf -

cd /tmp/
mv /tmp/vendor /tmp/Symfony/
tar zcpf $DIR/build/Symfony_Standard_Vendors_$VERSION.tgz Symfony
sudo rm -f $DIR/build/Symfony_Standard_Vendors_$VERSION.zip
zip -rq $DIR/build/Symfony_Standard_Vendors_$VERSION.zip Symfony

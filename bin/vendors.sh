#!/bin/sh

DIR=`php -r "echo realpath(dirname(\\$_SERVER['argv'][0]));"`
VENDOR=$DIR/vendor

# initialization
if [ "$1" = "--reinstall" ]; then
    rm -rf $VENDOR
fi

mkdir -p $VENDOR && cd $VENDOR

##
# @param destination directory (e.g. "doctrine")
# @param URL of the git remote (e.g. git://github.com/doctrine/doctrine2.git)
# @param revision to point the head (e.g. origin/HEAD)
#
install_git()
{
    INSTALL_DIR=$1
    SOURCE_URL=$2
    REV=$3

    if [ -z $REV ]; then
        REV=origin/HEAD
    fi

    if [ ! -d $INSTALL_DIR ]; then
        git clone $SOURCE_URL $INSTALL_DIR
    fi

    cd $INSTALL_DIR
    git fetch origin
    git reset --hard $REV
    cd ..
}

# Assetic
install_git assetic git://github.com/kriswallsmith/assetic.git

# Symfony
install_git symfony git://github.com/symfony/symfony.git

# Update the bootstrap files
../bin/build_bootstrap.php

# Doctrine ORM
install_git doctrine git://github.com/doctrine/doctrine2.git 2.0.2

# Doctrine Data Fixtures Extension
install_git doctrine-data-fixtures git://github.com/doctrine/data-fixtures.git

# Doctrine DBAL
install_git doctrine-dbal git://github.com/doctrine/dbal.git 2.0.2

# Doctrine Common
install_git doctrine-common git://github.com/doctrine/common.git 2.0.1

# Doctrine migrations
install_git doctrine-migrations git://github.com/doctrine/migrations.git

# Doctrine MongoDB
install_git doctrine-mongodb git://github.com/doctrine/mongodb.git

# Doctrine MongoDB
install_git doctrine-mongodb-odm git://github.com/doctrine/mongodb-odm.git 1.0.0BETA3

# Swiftmailer
install_git swiftmailer git://github.com/swiftmailer/swiftmailer.git origin/4.1

# Twig
install_git twig git://github.com/fabpot/Twig.git

# Twig Extensions
install_git twig-extensions git://github.com/fabpot/Twig-extensions.git

# Zend Framework Log
mkdir -p zend-log/Zend
cd zend-log/Zend
install_git Log git://github.com/symfony/zend-log.git
cd ../..

# FrameworkExtraBundle
mkdir -p bundles/Sensio/Bundle
cd bundles/Sensio/Bundle
install_git FrameworkExtraBundle git://github.com/sensio/FrameworkExtraBundle.git
cd ../../..

# WebConfiguratorBundle
mkdir -p bundles/Symfony/Bundle
cd bundles/Symfony/Bundle
install_git WebConfiguratorBundle git://github.com/symfony/WebConfiguratorBundle.git
cd ../../..

# Update assets
../app/console assets:install ../web/

#!/bin/sh

DIR=`php -r "echo dirname(dirname(realpath('$0')));"`
VENDOR="$DIR/vendor"
VERSION=`cat "$DIR/VERSION"`
BUNDLES=$VENDOR/bundles

# initialization
if [ "$1" = "--reinstall" -o "$2" = "--reinstall" ]; then
    rm -rf $VENDOR
fi

# just the latest revision
CLONE_OPTIONS=''
if [ "$1" = "--min" -o "$2" = "--min" ]; then
    CLONE_OPTIONS='--depth 1'
fi

mkdir -p "$VENDOR" && cd "$VENDOR"

##
# @param destination directory (e.g. "doctrine")
# @param URL of the git remote (e.g. http://github.com/doctrine/doctrine2.git)
# @param revision to point the head (e.g. origin/HEAD)
#
install_git()
{
    INSTALL_DIR=$1
    SOURCE_URL=$2
    REV=$3

    echo "> Installing/Updating " $INSTALL_DIR

    if [ -z $REV ]; then
        REV=origin/HEAD
    fi

    if [ ! -d $INSTALL_DIR ]; then
        git clone $CLONE_OPTIONS $SOURCE_URL $INSTALL_DIR
    fi

    cd $INSTALL_DIR
    git fetch origin
    git reset --hard $REV
    cd ..
}

# Assetic
install_git assetic http://github.com/kriswallsmith/assetic.git

# Symfony
install_git symfony http://github.com/symfony/symfony.git #v$VERSION

# Doctrine ORM
install_git doctrine http://github.com/doctrine/doctrine2.git 2.0.4

# Doctrine DBAL
install_git doctrine-dbal http://github.com/doctrine/dbal.git 2.0.4

# Doctrine Common
install_git doctrine-common http://github.com/doctrine/common.git 2.0.2

# Swiftmailer
install_git swiftmailer http://github.com/swiftmailer/swiftmailer.git origin/4.1

# Twig
install_git twig http://github.com/fabpot/Twig.git

# Twig Extensions
install_git twig-extensions http://github.com/fabpot/Twig-extensions.git

# Monolog
install_git monolog http://github.com/Seldaek/monolog.git

# SensioFrameworkExtraBundle
mkdir -p $BUNDLES/Sensio/Bundle
cd $BUNDLES/Sensio/Bundle
install_git FrameworkExtraBundle http://github.com/sensio/SensioFrameworkExtraBundle.git

# SecurityExtraBundle
mkdir -p $BUNDLES/JMS
cd $BUNDLES/JMS
install_git SecurityExtraBundle http://github.com/schmittjoh/SecurityExtraBundle.git

# Symfony bundles
mkdir -p $BUNDLES/Symfony/Bundle
cd $BUNDLES/Symfony/Bundle

# WebConfiguratorBundle
install_git WebConfiguratorBundle http://github.com/symfony/WebConfiguratorBundle.git

# Update the bootstrap files
$DIR/bin/build_bootstrap.php

# Update assets
$DIR/app/console assets:install $DIR/web/

#!/bin/sh

DIR=`php -r "echo dirname(dirname(realpath('$0')));"`

# Update vendors
git submodule update --init --recursive

# Update the bootstrap files
$DIR/bin/build_bootstrap.php

# Update assets
$DIR/app/console assets:install $DIR/web/

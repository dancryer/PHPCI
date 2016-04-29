PHPCI
=====

PHPCI is a free and open source (BSD License) continuous integration tool specifically designed for PHP. We've  built it with simplicity in mind, so whilst it doesn't do *everything* Jenkins can do, it is a breeze to set up and use.

**Current Build Status**

[![Build Status](http://phpci.block8.net/build-status/image/2?branch=master)](http://phpci.block8.net/build-status/view/2?branch=master)

**Chat Room**

We have a chat room for discussing PHPCI, you can access it here: [![Gitter](https://badges.gitter.im/Join Chat.svg)](https://gitter.im/Block8/PHPCI?utm_source=badge&utm_medium=badge&utm_campaign=pr-badge&utm_content=body_badge)

## What it does:
* Clones your project from Github, Bitbucket or a local path
* Allows you to set up and tear down test databases.
* Installs your project's Composer dependencies.
* Runs through any combination of the [supported plugins](https://www.phptesting.org/wiki#plugins).
* You can mark directories for the plugins to ignore.
* You can mark certain plugins as being allowed to fail (but still run.)

### What it doesn't do (yet):
* Virtualised testing.
* Multiple PHP-version tests.
* Install PEAR or PECL extensions.
* Deployments - We strongly recommend using [Deployer](http://phpdeployment.org)

## Getting Started:
We've got documentation on our website on [installing PHPCI](https://www.phptesting.org/install-phpci) and [adding support for PHPCI to your projects](https://www.phptesting.org/wiki/Adding-PHPCI-Support-to-Your-Projects).

## Contributing
Contributions from others would be very much appreciated! Please read our [guide to contributing](https://github.com/Block8/PHPCI/blob/master/.github/CONTRIBUTING.md) for more information on how to get involved.

## Questions?
Your best place to go is the [mailing list](https://groups.google.com/forum/#!forum/php-ci). If you're already a member of the mailing list, you can simply email php-ci@googlegroups.com.

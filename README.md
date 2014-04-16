PHPCI
=====

PHPCI is a free and open source continuous integration tool specifically designed for PHP. We've  built it with simplicity in mind, so whilst it doesn't do *everything* Jenkins can do, it is a breeze to set up and use.

_**Please be aware that PHPCI is a beta-release project, so whilst it is very stable, there may be bugs and/or missing features.**_

**Current Build Status**

![Build Status](http://phpci.block8.net/build-status/image/2)

##What it does:
* Clones your project from Github, Bitbucket or a local path
* Allows you to set up and tear down test databases.
* Installs your project's Composer dependencies.
* Runs through any combination of the [supported plugins](https://github.com/Block8/PHPCI/wiki#plugins).
* You can mark directories for the plugins to ignore.
* You can mark certain plugins as being allowed to fail (but still run.)

##What it doesn't do (yet):
* Virtualised testing.
* Multiple PHP-version tests.
* Multiple testing workers.
* Install PEAR or PECL extensions.
* Deployments.

## Getting Started:
We've got documentation on our wiki on [installing PHPCI](https://github.com/Block8/PHPCI/wiki/Installing-PHPCI) and [adding support for PHPCI to your projects](https://github.com/Block8/PHPCI/wiki/Adding-PHPCI-Support-to-Your-Projects).

##Contributing
Contributions from others would be very much appreciated! Please read our [guide to contributing](https://github.com/Block8/PHPCI/wiki/Contributing-to-PHPCI) for more information on how to get involved.

##Questions?
Your best place to go is the [mailing list](https://groups.google.com/forum/#!forum/php-ci), if you're already a member of the mailing list, you can simply email php-ci@googlegroups.com.

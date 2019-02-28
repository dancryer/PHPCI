PHPCI
=====

PHPCI is a free and open source (BSD License) continuous integration tool specifically designed for PHP. We've  built it with simplicity in mind, so whilst it doesn't do *everything* Jenkins can do, it is a breeze to set up and use.


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
We've got documentation on our website on [installing PHPCI](https://docs.phptesting.org/en/latest/installing-phpci/) and [adding support for PHPCI to your projects](https://docs.phptesting.org/en/latest/adding-phpci-support-to-your-projects/).

## Contributing
Contributions from others would be very much appreciated! Please read our [guide to contributing](https://github.com/dancryer/PHPCI/blob/master/.github/CONTRIBUTING.md) for more information on how to get involved.

## Questions?
Your best place to go is the [mailing list](https://groups.google.com/forum/#!forum/php-ci). If you're already a member of the mailing list, you can simply email php-ci@googlegroups.com.

## What you'll need:

* PHP 5.3.6 or above
* A web server (we recommend [nginx](http://nginx.org))
* [Composer](https://getcomposer.org/download/)
* [Git](http://git-scm.com/downloads)
* A MySQL server to connect to. This doesn't have to be on the same server as PHPCI.
* The following functions need to be enabled: `exec()`, `shell_exec()` and `proc_open()`
* PHP must have OpenSSL support enabled.

## Installing PHPCI from Composer:

* Go to the directory in which you want to install PHPCI, for example: `/var/www`
* Download Composer if you haven't already: `curl -sS https://getcomposer.org/installer | php`
* Download PHPCI: `./composer.phar create-project block8/phpci phpci --keep-vcs --no-dev`
* Go to the newly created PHPCI directory, and install Composer dependencies: `cd phpci && ../composer.phar install`
* Run the PHPCI installer: `php ./console phpci:install`
* [Add a virtual host to your web server](/Block8/PHPCI/wiki/Add-a-Virtual-Host), pointing to the `public` directory within your new PHPCI directory. You'll need to set up rewrite rules to point all non-existent requests to PHPCI.
* [Set up the PHPCI Worker](https://github.com/Block8/PHPCI/wiki/Run-Builds-Using-a-Worker), or you can run builds using the [PHPCI daemon](/Block8/PHPCI/wiki/Run-Builds-Using-a-Daemon) or [a cron-job](/Block8/PHPCI/wiki/Run-Builds-Using-Cron) to run PHPCI builds.

## Installing PHPCI Manually:

* Go to the directory in which you want to install PHPCI, for example: `/var/www`
* [Download PHPCI](https://github.com/Block8/PHPCI/releases/latest) and unzip it.
* Go to the PHPCI directory: `cd /var/www/phpci`
* Install dependencies using Composer: `composer install`
* Install PHPCI itself: `php ./console phpci:install`
* [Add a virtual host to your web server](/Block8/PHPCI/wiki/Add-a-Virtual-Host), pointing to the `public` directory within your new PHPCI directory. You'll need to set up rewrite rules to point all non-existent requests to PHPCI.
* [Set up the PHPCI Worker](https://github.com/Block8/PHPCI/wiki/Run-Builds-Using-a-Worker), or you can run builds using the [PHPCI daemon](/Block8/PHPCI/wiki/Run-Builds-Using-a-Daemon) or [a cron-job](/Block8/PHPCI/wiki/Run-Builds-Using-Cron) to run PHPCI builds.

### Extended Guides
- [Installing PHPCI on Mac OSX Mavericks](https://github.com/Block8/PHPCI/wiki/Vanilla-Mac-Mavericks-Server-Installation-Guide)
- [Installing PHPCI on Mac OSX Yosemite](https://github.com/Block8/PHPCI/wiki/Vanilla-Installation-on-OS-X-10.10-Yosemite-with-OS-X-Server-4)
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
* Runs through any combination of the following plugins:
    * PHP Unit
    * PHP Mess Detector
    * PHP Copy/Paste Detector
    * PHP Code Sniffer
    * PHP Spec
* You can mark directories for the plugins to ignore.
* You can mark certain plugins as being allowed to fail (but still run.)

##What it doesn't do (yet):
* Virtualised testing.
* Multiple PHP-version tests.
* Multiple testing workers.
* Install PEAR or PECL extensions.
* Deployments.

##Installing PHPCI:
####Pre-requisites:
* PHP 5.3.3+
* A web server. We prefer nginx.
* A MySQL server to connect to (doesn't have to be on the same server.)
* PHPCI needs to be able to run `exec()`, so make sure this is not disabled
* Php-openssl must be available.


####Installing from Github:
* Step 1: `git clone https://github.com/Block8/PHPCI.git`
* Step 2: `cd PHPCI`
* Step 3: `composer install`
* Step 4: `chmod +x ./console && ./console phpci:install`
    * When prompted, enter your database host, username, password and the database name that PHPCI should use.
    * The script will attempt to create the database if it does not exist already.
    * If you intend to use the MySQL plugin to create / destroy databases, the user you entered above will need CREATE / DELETE permissions on the server.
* Add a virtual host to your web server, pointing to the directory you cloned PHPCI into.
* You'll need to set up rewrite rules to point all non-existant requests to PHPCI.

**Apache Example**:

    RewriteEngine On
    RewriteBase /path-to-phpci
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteRule (.*)? index.php [L,E=PATH_INFO:/$1]
    
**Nginx Example**: 


    location / {
        try-files $uri $uri/ index.php
    }

Finally, you'll want to set up PHPCI to run as a regular cronjob, so run `crontab -e` and enter the following:

    * * * * * /usr/bin/php /path/to/phpci/console phpci:run-builds
    
Obviously, make sure you change the `/path/to/phpci` to the directory in which you installed PHPCI, and update the PHP path if necessary.

##Adding support for PHPCI to your projects:
Similar to Travis CI, to support PHPCI in your project, you simply need to add a `phpci.yml` file to the root of your repository. The file should look something like this:

    build_settings:
        ignore:
            - "vendor"
            - "tests"
        mysql:
            host: "localhost"
            user: "root"
            pass: ""        

    setup:
        mysql:
            - "DROP DATABASE IF EXISTS test;"
            - "CREATE DATABASE test;"
            - "GRANT ALL PRIVILEGES ON test.* TO test@'localhost' IDENTIFIED BY 'test';"
        composer:
            action: "install"
    
    test:
        php_unit:
            config:
                - "PHPUnit-all.xml"
                - "PHPUnit-ubuntu-fix.xml"
            directory:
                - "tests/"
            run_from: "phpunit/"
        php_mess_detector:
            allow_failures: true
        php_code_sniffer:
            standard: "PSR2"
        php_cpd:
            allow_failures: true
    
    complete:
        mysql:
            - "DROP DATABASE IF EXISTS test;"
            
As mentioned earlier, PHPCI is powered by plugins, there are several phases in which plugins can be run:

* `setup` - This phase is designed to initialise the build procedure.
* `test` - The tests that should be run during the build. Plugins run during this phase will contribute to the success or failure of the build.
* `complete` - Always called when the `test` phase completes, regardless of success or failure.
* `success` - Called upon success of the `test` phase.
* `failure` - Called upon the failure of the `test` phase.

The `ignore` section is merely an array of paths that should be ignored in all tests (where possible.)

##Contributing
Contributions from others would be very much appreciated! If you just want to make a simple change, simply fork the repository, and send us a pull request when you're ready. 

If you'd like to get more involved in developing PHPCI or to become a maintainer / committer on the main PHPCI repository, join the [mailing list](https://groups.google.com/forum/#!forum/php-ci).

##Questions?
Your best place to go is the [mailing list](https://groups.google.com/forum/#!forum/php-ci), if you're already a member of the mailing list, you can simply email php-ci@googlegroups.com.

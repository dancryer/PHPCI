PHPCI
=====

PHPCI is a free and open source continuous integration tool specifically designed for PHP. We've  built it with simplicity in mind, so whilst it doesn't do *everything* Jenkins can do, it is a breeze to set up and use.

_**Please be aware that this is a brand new project, in an alpha state, so there will be bugs and missing features.**_

##What it does:
* Clones your repository from Github or Bitbucket (support for standard Git repositories coming soon.)
* Allows you to set up and tear down test databases.
* Installs your project's Composer dependencies.
* Runs through any combination of the following plugins:
	* PHP Unit
	* PHP Mess Detector
	* PHP Copy/Paste Detector
	* PHP Code Sniffer
* You can mark directories for the plugins to ignore.
* You can mark certain plugins as being allowed to fail (but still run.)

##What it doesn't do (yet):
* Virtualised testing.
* Multiple PHP-version tests.
* Multiple testing workers.
* Install PEAR or PECL extensions.

##Installing PHPCI:
####Pre-requisites:
* PHP 5.3+
* A web server. We prefer nginx.
* The YAML extension: `pecl install yaml`
* A MySQL server to connect to (doesn't have to be on the same server.)
* PHPCI needs to be able to run `exec()`, so make sure this is not disabled.


####Installing from Github:
* Step 1: `git clone https://github.com/Block8/PHPCI.git`
* Step 2: `cd PHPCI`
* Step 3: `php install.php`
	* When prompted, enter your database host, username, password and the database name that PHPCI should use.
	* The script will attempt to create the database if it does not exist already.
	* If you intend to use the MySQL plugin to create / destroy databases, the user you entered above will need CREATE / DELETE permissions on the server.
* Add a virtual host to your web server, pointing to the directory you cloned PHPCI into.
* You'll need to set up rewrite rules to point all non-existant requests to PHPCI.

**Apache Example**:

	RewriteEngine On
	RewriteCond %{REQUEST_FILENAME} !-f
	RewriteCond %{REQUEST_FILENAME} !-d
	RewriteRule . index.php [L]
	
**Nginx Example**: 


    location / {
        try-files $uri $uri/ index.php
    }

Finally, you'll want to set up PHPCI to run as a regular cronjob, so run `crontab -e` and enter the following:

    `* * * * * /usr/bin/php /path/to/phpci/cron.php`
    
Obviously, make sure you change the /path/to/phpci to the directory in which you installed PHPCI, and update the PHP path if necessary.

##Adding support for PHPCI to your projects:
Similar to Travis CI, to support PHPCI in your project, you simply need to add a `phpci.yml` file to the root of your repository. The file should look something like this:

	setup:
		mysql:
    		- "DROP DATABASE IF EXISTS test;"
    		- "CREATE DATABASE test;"
    		- "GRANT ALL PRIVILEGES ON test.* TO test@'localhost' IDENTIFIED BY 'test';"
    	composer:
    		action: "install"
    
    ignore:
    	- "vendor/"
    	- "tests/"
    
    test:
    	php_unit:
    		directory: "tests/"
    	php_mess_detector:
    		allow_failures: true
    	php_code_sniffer:
    		standard: "PSR2"
    	php_cpd:
    		allow_failures: true
    
    complete:
    	mysql:
    		- "DROP DATABASE IF EXISTS b8_test;"
    		
As mentioned earlier, PHPCI is powered by plugins, there are several phases in which plugins can be run:

* `setup` - This phase is designed to initialise the build procedure.
* `test` - The tests that should be run during the build. Plugins run during this phase will contribute to the success or failure of the build.
* `complete` - Always called when the `test` phase completes, regardless of success or failure.
* `success` - Called upon success of the `test` phase.
* `failure` - Called upon the failure of the `test` phase.

The `ignore` section is merely an array of paths that should be ignored in all tests (where possible.)

##Contributing
Contributions from others would be very much appreciated! Simply fork the repository, and send us a pull request when you're ready. 

##Questions?
Email us at hello@block8.co.uk and we'll do our best to help!
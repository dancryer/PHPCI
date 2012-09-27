Symfony Standard Edition
========================

Welcome to the Symfony Standard Edition - a fully-functional Symfony2
application that you can use as the skeleton for your new applications.

This document contains information on how to download, install, and start
using Symfony. For a more detailed explanation, see the [Installation][1]
chapter of the Symfony Documentation.

1) Installing the Standard Edition
----------------------------------

When it comes to installing the Symfony Standard Edition, you have the
following options.

### Use Composer (*recommended*)

As Symfony uses [Composer][2] to manage its dependencies, the recommended way
to create a new project is to use it.

If you don't have Composer yet, download it following the instructions on
http://getcomposer.org/ or just run the following command:

    curl -s https://getcomposer.org/installer | php

Then, use the `create-project` command to generate a new Symfony application:

    php composer.phar create-project symfony/framework-standard-edition path/to/install 2.1.x-dev

For an exact version, replace 2.1.x-dev with the latest Symfony version (e.g. 2.1.1).

Composer will install Symfony and all its dependencies under the
`path/to/install` directory.

### Download an Archive File

To quickly test Symfony, you can also download an [archive][3] of the Standard
Edition and unpack it somewhere under your web server root directory.

If you downloaded an archive "without vendors", you also need to install all
the necessary dependencies. Download composer (see above) and run the
following command:

    php composer.phar install

2) Checking your System Configuration
-------------------------------------

Before starting coding, make sure that your local system is properly
configured for Symfony.

Execute the `check.php` script from the command line:

    php app/check.php

Access the `config.php` script from a browser:

    http://localhost/path/to/symfony/app/web/config.php

If you get any warnings or recommendations, fix them before moving on.

3) Browsing the Demo Application
--------------------------------

Congratulations! You're now ready to use Symfony.

From the `config.php` page, click the "Bypass configuration and go to the
Welcome page" link to load up your first Symfony page.

You can also use a web-based configurator by clicking on the "Configure your
Symfony Application online" link of the `config.php` page.

To see a real-live Symfony page in action, access the following page:

    web/app_dev.php/demo/hello/Fabien

4) Getting started with Symfony
-------------------------------

This distribution is meant to be the starting point for your Symfony
applications, but it also contains some sample code that you can learn from
and play with.

A great way to start learning Symfony is via the [Quick Tour][4], which will
take you through all the basic features of Symfony2.

Once you're feeling good, you can move onto reading the official
[Symfony2 book][5].

A default bundle, `AcmeDemoBundle`, shows you Symfony2 in action. After
playing with it, you can remove it by following these steps:

  * delete the `src/Acme` directory;

  * remove the routing entries referencing AcmeBundle in
    `app/config/routing_dev.yml`;

  * remove the AcmeBundle from the registered bundles in `app/AppKernel.php`;

  * remove the `web/bundles/acmedemo` directory;

  * remove the `security.providers`, `security.firewalls.login` and
    `security.firewalls.secured_area` entries in the `security.yml` file or
    tweak the security configuration to fit your needs.

What's inside?
---------------

The Symfony Standard Edition is configured with the following defaults:

  * Twig is the only configured template engine;

  * Doctrine ORM/DBAL is configured;

  * Swiftmailer is configured;

  * Annotations for everything are enabled.

It comes pre-configured with the following bundles:

  * **FrameworkBundle** - The core Symfony framework bundle

  * [**SensioFrameworkExtraBundle**][6] - Adds several enhancements, including
    template and routing annotation capability

  * [**DoctrineBundle**][7] - Adds support for the Doctrine ORM

  * [**TwigBundle**][8] - Adds support for the Twig templating engine

  * [**SecurityBundle**][9] - Adds security by integrating Symfony's security
    component

  * [**SwiftmailerBundle**][10] - Adds support for Swiftmailer, a library for
    sending emails

  * [**MonologBundle**][11] - Adds support for Monolog, a logging library

  * [**AsseticBundle**][12] - Adds support for Assetic, an asset processing
    library

  * [**JMSSecurityExtraBundle**][13] - Allows security to be added via
    annotations

  * [**JMSDiExtraBundle**][14] - Adds more powerful dependency injection
    features

  * **WebProfilerBundle** (in dev/test env) - Adds profiling functionality and
    the web debug toolbar

  * **SensioDistributionBundle** (in dev/test env) - Adds functionality for
    configuring and working with Symfony distributions

  * [**SensioGeneratorBundle**][15] (in dev/test env) - Adds code generation
    capabilities

  * **AcmeDemoBundle** (in dev/test env) - A demo bundle with some example
    code

Enjoy!

[1]:  http://symfony.com/doc/2.1/book/installation.html
[2]:  http://getcomposer.org/
[3]:  http://symfony.com/download
[4]:  http://symfony.com/doc/2.1/quick_tour/the_big_picture.html
[5]:  http://symfony.com/doc/2.1/index.html
[6]:  http://symfony.com/doc/2.1/bundles/SensioFrameworkExtraBundle/index.html
[7]:  http://symfony.com/doc/2.1/book/doctrine.html
[8]:  http://symfony.com/doc/2.1/book/templating.html
[9]:  http://symfony.com/doc/2.1/book/security.html
[10]: http://symfony.com/doc/2.1/cookbook/email.html
[11]: http://symfony.com/doc/2.1/cookbook/logging/monolog.html
[12]: http://symfony.com/doc/2.1/cookbook/assetic/asset_management.html
[13]: http://jmsyst.com/bundles/JMSSecurityExtraBundle/master
[14]: http://jmsyst.com/bundles/JMSDiExtraBundle/master
[15]: http://symfony.com/doc/2.1/bundles/SensioGeneratorBundle/index.html

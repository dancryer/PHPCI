Symfony Standard Edition
========================

Welcome to the Symfony Standard Edition - a fully-functionality Symfony2
application that you can use as the skeleton for your new app.

This document contains information on how to download and start using Symfony.
For a more detailed explanation, see the Installation chapter of the Symfony
Documentation.

1) Download the Standard Edition
--------------------------------

If you've already downloaded the standard edition, and unpacked it somewhere
within your web root directory, then move on to the "Installation" section.

To download the standard edition, you have two options:

### Download an archive file (*recommended*)

The easiest way to get started is to download an archive with vendors included
(http://symfony.com/download). Unpack it somewhere under your web server root
directory and you're done.

### Clone the git Repository

We highly recommend you that you download the packaged version of this
distribution. If you still want to use Git, you are on your own.

Run the following commands:

    git clone http://github.com/symfony/symfony-standard.git
    cd symfony-standard
    rm -rf .git

2) Installation
---------------

Once you've downloaded the standard edition, installation is easy, and basically
involves making sure your system is ready for Symfony.

### a) Check your System Configuration

Before you begin, make sure that your local system is properly configured
for Symfony. To do this, execute the following:

    php app/check.php

If you get any warnings or recommendations, fix these now before moving on.

### b) Install the Vendor Libraries

If you downloaded the archive "without vendors" or installed via git, then
you need to download all of the necessary vendor libraries. If you're not
sure if you need to do this, check to see if you have a ``vendor/`` directory.
If you don't, or if that directory is empty, run the following:

    php bin/vendors install

Note that you **must** have git installed to run this command. If you don't,
either install it or download Symfony with the vendor libraries already included.

### c) Access the Application via the Browser

Congratulations! You're now ready to use Symfony. If you've unzipped Symfony
in the web root of your computer, then you should be able to access the
web version of the Symfony requirements check via:

    http://localhost/Symfony/web/config.php

If everything look good, click the "Bypass configuration and go to the Welcome page"
link to load up your first Symfony page.

You can also use a web-based configurator by click on the "Configure your
Symfony Application online" link of the ``config.php`` page.

To see a real-live Symfony page in action, access the following page:

    web/app_dev.php/demo/hello/Fabien

3) Learn about Symfony!
-----------------------

This distribution is meant to be the starting point for your application,
but it also contains some sample code that you can learn from and play with.

A great way to start learning Symfony is via the Quick Tour (http://symfony.com/doc/current/quick_tour/the_big_picture.html),
which will take you through all the basic features of Symfony2 and test pages
that are available in the standard edition.

Once you're feeling good, you can move onto reading the official
Symfony2 book (http://symfony.com/doc/current/).

Using this Edition as the Base of your Application
--------------------------------------------------

Since the standard edition is fully-configured and comes with some examples,
you'll need to make a few changes before using it to build your application.

The distribution is configured with the following defaults:

* Twig is the only configured template engine;
* Doctrine ORM/DBAL is configured;
* Swiftmailer is configured;
* Annotations for everything are enabled.

A default bundle, ``AcmeDemoBundle``, shows you Symfony2 in action. After
playing with it, you can remove it by following these steps:

* delete the ``src/Acme`` directory;
* remove the routing entries referencing AcmeBundle in ``app/config/routing_dev.yml``;
* remove the AcmeBundle from the registered bundles in ``app/AppKernel.php``;

Enjoy!

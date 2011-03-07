Symfony Standard Edition
========================

What's inside?
--------------

Symfony Standard Edition comes pre-configured with the following bundles:

 * FrameworkBundle
 * SensioFrameworkExtraBundle
 * DoctrineBundle
 * TwigBundle
 * SwiftmailerBundle
 * ZendBundle
 * AsseticBundle
 * WebProfilerBundle (in dev/test env)
 * SymfonyWebConfiguratorBundle (in dev/test env)
 * AcmeDemoBundle (in dev/test env)

Installation from an Archive
----------------------------

If you have downloaded an archive, unpack it somewhere under your web server
root directory.

If you have downloaded an archive without the vendors, run the
`bin/vendors.sh` script (`git` must be installed on your machine). If you
don't have git, download the version with the vendors included.

Installation from Git
---------------------

We highly recommend you that you download the packaged version of this
distribution. If you still want to use Git, your are on your own.

Run the following scripts:

 * `bin/vendors.sh`
 * `bin/build_bootstrap.php`
 * `app/console assets:install web/`

Configuration
-------------

Check that everything is working fine by going to the `config.php` page in a
browser and follow the instructions.

The distribution is configured with the following defaults:

 * Twig is the only configured template engine;
 * Doctrine ORM/DBAL is configured;
 * Swiftmailer is configured;
 * Annotations for everything are enabled.

A default bundle, `AcmeDemoBundle`, shows you Symfony2 in action. It's only
available in the `dev` environment. After playing with it, you can remove it
by deleting the `src/Acme` directory and removing the routing entry in
`app/config/routing_dev.yml`.

Configure the distribution by editing `app/config/parameters.ini` or by
accessing `/web/config.php` in a browser.

A simple controller is configured at `/hello/{name}`. Access it via
`web/app_dev.php/_demo/hello/Fabien`.

If you want to use the CLI, a console application is available at
`app/console`. Check first that your PHP is correctly configured for the CLI
by running `app/check.php`.

Enjoy!

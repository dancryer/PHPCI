UPGRADE FROM 2.7 to 2.8
=======================

When upgrading Symfony from 2.7 to 2.8, beware of the following changes in the
Standard Edition:

 * Assetic is not included by default anymore.
 * It comes with a new major version of `sensio/distribution-bundle`. If you are
   updating the bundle in your project as well, the following changes are required:
   - The web configurator got removed. So you need to remove the `_configurator`
     routing entry from `app/config/routing_dev.yml`.
   - The generated `app/bootstrap.php.cache` does not include autoloading anymore.
     So you need to add the autoloading code in your front controllers `web/app.php`,
     `web/app_dev.php`, `app/console` and `app/phpunit.xml.dist` (bootstrap config).
   - If you have been using the Symfony 3 directory structure already, you need to
     overwrite the cache and log directories in your `AppKernel` as it is also done
     in Symfony 3 now (see
     [`app/AppKernel.php`](https://github.com/symfony/symfony-standard/blob/master/app/AppKernel.php#L31-L44)).
 * The `app/AppKernel.php` is now autoloaded via composer.

You can have a look at the
[diff](https://github.com/symfony/symfony-standard/compare/2.7...2.8)
between the 2.7 version of the Standard Edition and the 2.8 version that
should help you to apply the changes in your project.

Additionally, we recommend to
[add phpunit-bridge to handle deprecations](https://github.com/symfony/symfony-standard/pull/884)
in your test suite and to ensure tests are
[run with full error reporting](https://github.com/symfony/symfony-standard/pull/875).

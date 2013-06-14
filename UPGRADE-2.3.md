UPGRADE FROM 2.2 to 2.3
=======================

When upgrading Symfony from 2.2 to 2.3, you need to do the following changes
to the code that came from the Standard Edition:

 * The debugging tools are not enabled by default anymore and should be added
   to the
   [`web/app_dev.php`](https://github.com/symfony/symfony-standard/blob/2.3/web/app_dev.php)
   front controller manually, just after including the bootstrap cache:

        use Symfony\Component\Debug\Debug;

        Debug::enable();

   You also need to enable debugging in the
   [`app/console`](https://github.com/symfony/symfony-standard/blob/2.3/app/console)
   script, after the `$debug` variable is defined:

        use Symfony\Component\Debug\Debug;

        if ($debug) {
            Debug::enable();
        }

 * The `parameters.yml` file can now be managed by the
   `incenteev/composer-parameter-handler` bundle that comes with the 2.3
   Standard Edition:

    * add `"incenteev/composer-parameter-handler": "~2.0"` to your
      `composer.json` file;

    * add `/app/config/parameters.yml` to your `.gitignore` file;

    * create a
      [`app/config/parameters.yml.dist`](https://github.com/symfony/symfony-standard/blob/2.3/app/config/parameters.yml.dist)
      file with sensible values for all your parameters.

 * It is highly recommended that you switch the minimum stability to `stable`
   in your `composer.json` file.

 * If you are using Apache, have a look at the new
   [`.htaccess`](https://github.com/symfony/symfony-standard/blob/2.3/web/.htaccess)
   configuration and change yours accordingly.

 * In the
   [`app/autoload.php`](https://github.com/symfony/symfony-standard/blob/2.3/app/autoload.php)
   file, the section about `intl` should be removed as it is not needed anymore.

You can also have a look at the
[diff](https://github.com/symfony/symfony-standard/compare/v2.2.0%E2%80%A62.3)
between the 2.2 version of the Standard Edition and the 2.3 version.

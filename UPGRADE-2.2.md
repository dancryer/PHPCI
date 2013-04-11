UPGRADE FROM 2.1 to 2.2
=======================

 * The [`web/.htaccess`](https://github.com/symfony/symfony-standard/blob/2.2/web/.htaccess)
   file has been enhanced substantially to prevent duplicate content with and
   without `/app.php` in the URI. It also improves functionality when using
   Apache aliases or when mod_rewrite is not available. So you might want to
   update your `.htaccess` file as well.

 * The ``_internal`` route is not used any more. It should then be removed
   from both your routing and security configurations. A ``fragments`` key has
   been added to the framework configuration and must be specified when ESI or
   Hinclude are in use. No security configuration is required for this path as
   by default ESI access is only permitted for trusted hosts and Hinclude
   access uses an URL signing mechanism.

   ```
   framework:
       # ...
       fragments: { path: /_proxy }
   ```

Functional Tests
----------------

 * The profiler has been disabled by default in the test environment. You can
   enable it again by modifying the ``config_test.yml`` configuration file or
   even better, you can just enable it for the very next request by calling
   ``$client->enableProfiler()`` when you need the profiler in a test (that
   speeds up functional tests quite a bit).

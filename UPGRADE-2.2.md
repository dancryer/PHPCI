UPGRADE FROM 2.1 to 2.2
=======================

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

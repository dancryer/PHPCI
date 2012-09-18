UPGRADE FROM 2.1 to 2.2
=======================

Functional Tests
----------------

 * The profiler has been disabled by default in the test environment. You can
   enable it again by modifying the ``config_test.yml`` configuration file or
   even better, you can just enable it for the very next request by calling
   ``$client->enableProfiler()`` when you need the profiler in a test (that
   speeds up functional tests quite a bit).

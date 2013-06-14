UPGRADE FROM 2.2 to 2.3
=======================

 * You now have to manually enable the [debug component](http://symfony.com/blog/new-in-symfony-2-3-new-debug-component) in [`web/app_dev.php`](https://github.com/symfony/symfony-standard/blob/2.3/web/app_dev.php)
   by adding `Debug::enable();` just after including the boostrap cache.
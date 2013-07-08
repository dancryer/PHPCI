UPGRADE FROM 2.3 to 2.4
=======================

When upgrading Symfony from 2.3 to 2.4, you need to do the following changes
to the code that came from the Standard Edition:

 * We recommend to comment or remove the `firephp` and `chromephp` Monolog
   handlers as they might cause issues with some configuration (`chromephp`
   with Nginx for instance).

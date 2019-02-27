Runs a given Shell command.

**Note: ** Because this plugin could potentially be abused, it requires extra steps to enable it:

1. In the root of your PHPCI system, in the same directory where you'll find composer.json and vars.php, look for a file local_vars.php. If it does not exist, create it.
2. In local_vars.php add this code:

```php
<?php

define('ENABLE_SHELL_PLUGIN', true);
```

If `ENABLE_SHELL_PLUGIN` is either false or undefined, the shell plugin won't work.

### Configuration Options:

* **command** - Required - The shell command to run.

```yml
setup:
    shell:
        command: "bin/console build"
```
 You should understand, that in old configuration type, you can run only one command!

### New format of Configuration Options

```yml
setup:
   shell:
       - "cd /www"
       - "chmod u+x %BUILD_PATH%/bin/console"
       - "%BUILD_PATH%/bin/console build"
```

#### Each new command forgets about what was before

So if you want cd to directory and then run script there, combine those two commands into one like:

```yml
setup:
    shell:
        - "cd %BUILD_PATH% && php artisan migrate" # Laravel Migrations
```


[See variables which you can use in shell commands](https://github.com/Block8/PHPCI/wiki/Interpolation)
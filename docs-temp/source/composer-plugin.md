Allows you to run Composer within your build, to install dependencies prior to testing. Best run as a "setup" stage plugin.

## Configuration
### Options
* **directory** [optional, string] - Directory within which you want Composer to run (default: build root) 
* **action** [optional, string, update|install] - Action you wish Composer to run (default: 'install')
* **prefer_dist** [optional, bool, true|false] - whether Composer should run with the `--prefer-dist` flag (default: false)

### Example
```yml
setup:
  composer:
    directory: "my/composer/dir"
    action: "update"
    prefer_dist: true
```

## Warning

If you are using a Composer private repository like Satis, with HTTP authentication, you must check your username and password inside the ```auth.json``` file. PHPCI uses the ```--no-interaction``` flag, so it will not warn if you must provide that info.

For more info, please check the Composer documentation.

https://getcomposer.org/doc/04-schema.md#config
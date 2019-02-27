These are just some example yaml config files

```
build_settings:
    verbose: false
    prefer_symlink: false

setup:

test:
    php_unit:
        directory: "test/phpunit/"
        args: "--bootstrap 'test/phpunit/bootstrap.php' --configuration 'test/phpunit/phpunit.xml'"

complete:
```
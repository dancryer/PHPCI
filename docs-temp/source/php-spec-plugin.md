Runs [PHP Spec](http://www.phpspec.net/) tests against your build.

### Configuration Options:

* **bootstrap** - Optional - Path to a PHPSpec bootstrap file. 

### Example

```
build_settings:
    [...]

setup:
    [...]

test:
    php_spec:

complete:
    [...]

success:
    [...]
```
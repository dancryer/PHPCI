Allows you to create a [Phar](http://php.net/manual/en/book.phar.php) archive from your project.

### Example

```
phar:
    directory: /path/to/directory
    filename: foobar.phar
    regexp: /\.(php|phtml)$/
    stub: filestub.php
```

### Configuration Options

* **directory**: `phar` output directory. Default: `%buildpath%`;
* **filename**: `phar` filename inside output directory. Default: `build.phar`;
* **regexp**: regular expression for Phar iterator. Default: `/\.php$/`; and
* **stub**: stub content filename. No default value.
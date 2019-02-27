Similar to the [standard PHP Lint plugin](https://github.com/Block8/PHPCI/wiki/Lint-plugin), except that it uses the [PHP Parallel Lint](https://github.com/JakubOnderka/PHP-Parallel-Lint) project to run.

## Configuration
### Options
* **directory** [string, optional] - directory to inspect (default: build root)
* **ignore** [array, optional] - directory to ignore (default: inherits ignores specified in setup)
* **extensions** [string, optional] - comma separated list of file extensions of files containing PHP to be checked (default: php)

### Example
```yml
test:
  php_parallel_lint:
    directory: "app"
    ignore:
      - "vendor"
      - "test"
    extensions: php, html
```

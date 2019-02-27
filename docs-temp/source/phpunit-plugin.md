Runs PHPUnit tests against your build.
## Configuration
### Options
Has two modes:

#### phpunit.xml Configuration File
Its activated if you have phpunit.xml file in your build path, `tests/` subfolder, or you specify it as a parameter:
* **config** - Optional - Path to a PHP Unit XML configuration file.
* **run_from** - Optional - When running PHPUnit with an XML config, the command is run from this directory
* **coverage** - Optional - Value for the `--coverage-html` command line flag.
* **path** - Optional - In cases where tests files are in a sub path of the /tests path, allows this path to be set in the config.

#### Running Tests By Specifying Directory
* **directory** - Optional - The directory (or array of dirs) to run PHPUnit on

Both modes accept:
* **args** - Optional - Command line args (in string format) to pass to PHP Unit

### Examples
Specify config file and test directory:
```yml
test:
    php_unit:
        config:
            - "path/to/phpunit.xml"
        path: "app/tests/"
```

## Troubleshooting
If standard logging of PHPCI is not enough, to get standard output from any command, including PHPUnit, edit `BaseCommandExecutor::executeCommand()` to see what exactly is wrong
* Run `composer update` in phpunit plugin directory of PHPCI to get all of its dependencies
* If phpunit is inside of the project's composer.json, it might interfere with PHPCI's phpunit installation
* Make sure you have XDebug installed.`The Xdebug extension is not loaded. No code coverage will be generated.`
Otherwise test report parsing in `TapParser` will fail, wanting coverage report as well `Invalid TAP string, number of tests does not match specified test count.`
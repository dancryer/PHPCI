A simple plugin that allows you to run [Codeception](http://codeception.com/) tests.

### Configuration Options:

* **config** - Required - Can be either a single string pointing to a Codeception configuration file, or an array of configuration file paths. By default this is called `codeception.yml` and will be in the root of your project.

* **args** - Optional - The string of arguments to be passed to the run command. **Important**, due to the assumption made on line [132](https://github.com/Block8/PHPCI/blob/master/PHPCI/Plugin/Codeception.php#L132) regarding the value of `--xml` being the next argument which will not be correct if the user provides arguments using this config param, you must specify `report.xml` before any user input arguments to satisfy the report processing on line [146](https://github.com/Block8/PHPCI/blob/master/PHPCI/Plugin/Codeception.php#L146)

* **path** - Optional - The path from the root of your project to the root of the codeception _output directory

##### Default values

- config
 - `codeception.yml` if it exists in the root of the project
 - `codeception.dist.yml` if it exists in the root of the project
 - null if no option provided and the above two fail, this will cause an Exception to be thrown on execution

- args
 - Empty string

- path
 - `tests/_output/`

##### Example on running codeception with default settings (when tests are in tests/ directory):

```
  codeception:
    config: "codeception.yml"
    path: "tests/"
```

##### Example usage against the Yii2 framework

```
codeception:
        allow_failures: false
        config: "tests/codeception.yml"
        path: "tests/codeception/_output/"
        args: "report.xml --no-ansi --coverage-html"
```

The path value will need to be changed if you have your tests directory somewhere other than in the root of the project.
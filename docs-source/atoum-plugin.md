Allows you to run [Atoum](https://github.com/atoum/atoum) unit tests.

## Configuration
### Options

- **args** [string, optional] - Allows you to pass command line arguments to Atoum.
- **config** [string, optional] - Path to an Atoum configuration file.
- **directory** [string, optional] - Path in which to run Atom (defaults to the build root).
- **executable** [string, optional] - Allows you to provide a path to the Atom binary (defaults to PHPCI root, vendor/bin, or a system-provided Atom binary).

### Example
```yml
  test:
    atoum:
      args: "command line arguments go here"
      config: "path to config file"
      directory: "directory to run tests"
      executable: "path to atoum executable"
```

Runs PHP Coding Standards Fixer against your build.

## Configuration
### Options
* **verbose** [bool, optional] - Whether to run in verbose mode (default: false)
* **diff** [bool, optional] - Whether to run with the `--diff` flag enabled (default: false)
* **level** [string, optional] - `psr0`, `psr1`, `psr2`, or `symphony` (default: all)
* **workingdir** [string, optional] - The directory in which PHP CS Fixer should work (default: build root)

### Example
```yml
test:
  php_cs_fixer:
    verbose: true
    diff: true
    level: "psr2"
    workingdir: "my/dir/path"
```

## Warning
There is currently a bug with this plugin that will cause an error if you leave the level to default to `all`. That level does not exist and will cause the build to fail. Instead specify the level explicitly until this is fixed.
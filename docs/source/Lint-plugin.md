This plugin runs PHP's built in Lint (syntax / error check) functionality.

## Configuration
### Options
- **directory** [string, optional] - A single path in which you wish to lint files.
- **directories** [array, optional] - An array of paths in which you wish to lint files. This overrides  `directory`.
- **recursive** [bool, optional] - Whether or not you want to recursively check sub-directories of the above (defaults to true).

### Example
```yml
  test:
    lint:
      directory: "single path to lint files"
      directories:
        - "directory to lint files"
        - "directory to lint files"
        - "directory to lint files"
     recursive: false
```

This plugin runs [Grunt](http://gruntjs.com/) tasks.

## Configuration
### Options
- **directory** [string, optional] - The directory in which to run Grunt (defaults to build root.)
- **grunt** [string, optional] -  Allows you to provide a path to Grunt (defaults to PHPCI root, vendor/bin, or a system-provided Grunt).
- **gruntfile** [string, optional] - Gruntfile to run (defaults to `Gruntfile.js`).
- **task** [string, optional] - The Grunt task to run.

### Example
```yml
  test:
    grunt:
      directory: "path to run grunt in"
      grunt: "path to grunt executable"
      gruntfile: "gruntfile.js"
      task: "css"
```

This plugin allows you to use the Phing build system to build your project.

### Configuration options:
* **directory** - Relative path to the directory in which you want to run phing.
* **build_file** - Your phing build.xml file.
* **targets** - Which build targets you want to run.
* **properties** - Any custom properties you wish to pass to phing.
* **property_file** - A file containing properties you wish to pass to phing.

### Sample config:
```yml
phing:
      build_file: 'build.xml'
      targets:
        - "build:test"
      properties:
        config_file: "PHPCI"
```
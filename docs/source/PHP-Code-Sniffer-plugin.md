Runs PHP Code Sniffer against your build.

## Configuration
### Options
* **allowed_warnings** [int, optional] - The warning limit for a successful build.
* **allowed_errors** [int, optional] - The error limit for a successful build.
* **suffixes** [array, optional] - An array of file extensions to check.
* **standard** [string, optional] - The standard against which your files should be checked (defaults to PSR2.)
* **tab_width** [int, optional] - Your chosen tab width.
* **encoding** [string, optional] - The file encoding you wish to check for.
* **path** [string, optional] - Path in which to run PHP Code Sniffer.
* **ignore** [array, optional] - A list of files / paths to ignore, defaults to the build_settings ignore list.

### Example
Simple example where PHPCS will run on app directory, but ignore the views folder, and use PSR-1 and PSR-2 rules for validation:
```yml
test:
    php_code_sniffer:
        path: "app"
        ignore:
            - "app/views"
        standard: "PSR1,PSR2"
```

For use with an existing project:
```yml
test:
    php_code_sniffer:
        standard: "/phpcs.xml" # The leading slash is needed to trigger an external ruleset.
                               # Without it, PHPCI looks for a rule named "phpcs.xml"
        allowed_errors: -1 # Even a single error will cause the build to fail. -1 = unlimited
        allowed_warnings: -1
```
Runs PHP Mess Detector against your build. Records some key metrics, and also reports errors and warnings.

## Configuration
### Options
- **allowed_warnings** [int, optional] - The warning limit for a successful build (default: 0). -1 disables warnings. Setting allowed_warnings in conjunction with zero_config will override zero_config.
- **suffixes** [array, optional] - An array of file extensions to check (default: 'php')
- **ignore** [array, optional] - An array of files/paths to ignore (default: build_settings > ignore)
- **path** [string, optional] - Directory in which PHPMD should run (default: build root)
- **rules** [array, optional] - Array of rulesets that PHPMD should use when checking your build or a string containing at least one slash, will be treated as path to PHPMD ruleset. See http://phpmd.org/rules/index.html for complete details on the rules. (default: ['codesize', 'unusedcode', 'naming']).
- **zero_config** [bool, optional] - Suppresses build failure on errors and warnings if set to true. (default: false).


### Example
```yml
test:
  php_mess_detector:
    path: 'app'
    ignore:
      - 'vendor'
    allowed_warnings: -1
    rules:
      - "cleancode"
      - "controversial"
      - "codesize"
      - "design"
      - "naming"
      - "unusedcode"
      - "somedir/customruleset.xml"
    zero_config: true
```
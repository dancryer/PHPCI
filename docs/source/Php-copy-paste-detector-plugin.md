Runs PHP Copy / Paste Detector against your build.

## Configuration
### Options
* **path** - Optional - Path in which to run PHP Copy/Paste Detector (default: build root).
* **ignore** - Optional - A list of files / paths to ignore (default: build_settings > ignore).
* **standard** [string, optional] - which PSR standard to follow (default: 'PSR1').

### Example

```yml
test:
  php_cpd:
    standard: "PSR2"
    path: "app"
    ignore:
      - "app/my/path"
```

Continuous Integration build runner
===================================

Configuration file
------------------

```yaml
ci:
  stages:
    - name: 'Build'
      steps:
        - docker:
            name: my-machine
            machine: default
            dockerfile: docker/httpd.Dockerfile
        - docker:
            name: my-machine
            machine: default
            image: php:7.1-cli
        - parallel:
          - make: ~
          - make: ~
          - make: ~
        - make: ~
        - make: test
        - command: [ 'ls', '-l', '-a' ]
        - phpunit:
            config: phpunit.xml
            bootstrap: test/unit.php

    - name: 'Test'
      steps:
        - command: [ make, check ]
        - phpunit: ~

    - name: 'Deploy'
      steps:
        - command: [ make, publish ]
```

```php
<?php

$pipelines = new Stage()
```

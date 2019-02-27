# Installation

Installing 3rd party plugins is straight forward and handled through composer. In the root folder of your phpci instance update the ```composer.json``` file to include the new plugins you require:

```yaml
"require": {
   \\...
   "meadsteve/example-phpci-plugin" : "dev-master",
   \\...
}
```
running ```composer update plugin-provider/plugin-package ``` will then download the plugin.

# Usage

Once a new plugin has been installed to phpci any project can make use of this plugin. The plugin is referenced in the ```phpci.yml``` as a full class name including namespaces:

```yml
setup:
  test:
  php_mess_detector:
    allowed_warnings: 0
  \meadsteve\PhpciPlugins\ExamplePlugin:
    option_one: 2
```

# Extra dependency configuration
//TODO: document ```pluginconfig.php``` in project root.
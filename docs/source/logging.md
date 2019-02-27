# Basics
The phpci codebase makes use of the [psr3](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-3-logger-interface.md) logging standard. By default we use [Monolog](https://github.com/Seldaek/monolog) to handle the actual work implementing this standard.

# How to Setup Logging (For people running a PHPCI instance)
The only step required to activate logging is to create a file in the root directory called loggerconfig.php with content like the following:

```php
<?php
return array(
    /** Loggers attached to every command */
    "_" => function () {
        return array(
            new \Monolog\Handler\StreamHandler('path/to/log', \Monolog\Logger::ERROR),
        );
    }
);
```
This file should return an array of key value pairs. Each key tells phpci which command to attach the logger to (the underscore is a special value which matches all commands). For each command an array of [Monolog](https://github.com/Seldaek/monolog) handlers should be returned. In the example above we've used one that simply writes to the file system but in practise this could be any handler written for monolog.

Once this file is created all plugins and core phpci functionality should start writing to the configured handlers. 

# How to write to the Log (For people creating a new plugin)

## Using the plugin constructor to get a logger directly
For plugin creators the simplest way to get hold of an error logger is to add a parameter to the constructor and typehint on 'Psr\Log\LoggerInterface'. The code that loads your plugin will automatically inject the logger when it sees this. For example:
```php
class ExampleLoggingPlugin implements \PHPCI\Plugin
{
    protected $log;

    public function __construct(Psr\Log\LoggerInterface $log)
    {
        $this->log = $log;
    }

    public function execute()
    {
        $this->log->notice("You'll notice this in the log");
    }
}
```

## Using convenience methods provided by the Builder
Your plugin can also call a couple of messages on the Builder object:

logSuccess()
logFailure()
log()

All calls will get piped through to the appropriate logger.


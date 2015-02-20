<?php

namespace PHPCI\Plugin\Util;

/**
 * Plugin Factory - Loads Plugins and passes required dependencies.
 * @package PHPCI\Plugin\Util
 */
class Factory
{
    const TYPE_ARRAY = "array";
    const TYPE_CALLABLE = "callable";
    const INTERFACE_PHPCI_PLUGIN = '\PHPCI\Plugin';

    private $currentPluginOptions;

    /**
     * @var \Pimple
     */
    private $container;

    /**
     * @param \Pimple $container
     */
    public function __construct(\Pimple $container = null)
    {
        if ($container) {
            $this->container = $container;
        } else {
            $this->container = new \Pimple();
        }

        $self = $this;
        $this->registerResource(
            function () use ($self) {
                return $self->getLastOptions();
            },
            'options',
            'array'
        );
    }

    /**
     * Trys to get a function from the file path specified. If the
     * file returns a function then $this will be passed to it.
     * This enables the config file to call any public methods.
     *
     * @param $configPath
     * @return bool - true if the function exists else false.
     */
    public function addConfigFromFile($configPath)
    {
        // The file is expected to return a function which can
        // act on the pluginFactory to register any resources needed.
        if (file_exists($configPath)) {
            $configFunction = require($configPath);
            if (is_callable($configFunction)) {
                $configFunction($this);
                return true;
            }
        }
        return false;
    }

    /**
     * Get most recently used factory options.
     * @return mixed
     */
    public function getLastOptions()
    {
        return $this->currentPluginOptions;
    }

    /**
     * Builds an instance of plugin of class $className. $options will
     * be passed along with any resources registered with the factory.
     *
     * @param $className
     * @param array|null $options
     * @throws \InvalidArgumentException if $className doesn't represent a valid plugin
     * @return \PHPCI\Plugin
     */
    public function buildPlugin($className, $options = array())
    {
        $this->currentPluginOptions = $options;

        $reflectedPlugin = new \ReflectionClass($className);

        if (!$reflectedPlugin->implementsInterface(self::INTERFACE_PHPCI_PLUGIN)) {
            throw new \InvalidArgumentException(
                "Requested class must implement " . self:: INTERFACE_PHPCI_PLUGIN
            );
        }

        $constructor = $reflectedPlugin->getConstructor();

        if ($constructor) {
            $argsToUse = array();
            foreach ($constructor->getParameters() as $param) {
                $argsToUse = $this->addArgFromParam($argsToUse, $param);
            }
            $plugin = $reflectedPlugin->newInstanceArgs($argsToUse);
        } else {
            $plugin = $reflectedPlugin->newInstance();
        }

        return $plugin;
    }

    /**
     * @param callable $loader
     * @param string|null $name
     * @param string|null $type
     * @throws \InvalidArgumentException
     * @internal param mixed $resource
     */
    public function registerResource(
        $loader,
        $name = null,
        $type = null
    ) {
        if ($name === null && $type === null) {
            throw new \InvalidArgumentException(
                "Type or Name must be specified"
            );
        }

        if (!($loader instanceof \Closure)) {
            throw new \InvalidArgumentException(
                '$loader is expected to be a function'
            );
        }

        $resourceID = $this->getInternalID($type, $name);

        $this->container[$resourceID] = $loader;
    }

    /**
     * Get an internal resource ID.
     * @param null $type
     * @param null $name
     * @return string
     */
    private function getInternalID($type = null, $name = null)
    {
        $type = $type ? : "";
        $name = $name ? : "";
        return $type . "-" . $name;
    }

    /**
     * @param null $type
     * @param null $name
     * @return null
     */
    private function getResourceFor($type = null, $name = null)
    {
        $fullId = $this->getInternalID($type, $name);
        if (isset($this->container[$fullId])) {
            return $this->container[$fullId];
        }

        $typeOnlyID = $this->getInternalID($type, null);
        if (isset($this->container[$typeOnlyID])) {
            return $this->container[$typeOnlyID];
        }

        $nameOnlyID = $this->getInternalID(null, $name);
        if (isset($this->container[$nameOnlyID])) {
            return $this->container[$nameOnlyID];
        }

        return null;
    }

    /**
     * @param \ReflectionParameter $param
     * @return null|string
     */
    private function getParamType(\ReflectionParameter $param)
    {
        $class = $param->getClass();
        if ($class) {
            return $class->getName();
        } elseif ($param->isArray()) {
            return self::TYPE_ARRAY;
        } elseif ($param->isCallable()) {
            return self::TYPE_CALLABLE;
        } else {
            return null;
        }
    }

    /**
     * @param $existingArgs
     * @param \ReflectionParameter $param
     * @return array
     * @throws \DomainException
     */
    private function addArgFromParam($existingArgs, \ReflectionParameter $param)
    {
        $name = $param->getName();
        $type = $this->getParamType($param);
        $arg = $this->getResourceFor($type, $name);

        if ($arg !== null) {
            $existingArgs[] = $arg;
        } elseif ($arg === null && $param->isOptional()) {
            $existingArgs[] = $param->getDefaultValue();
        } else {
            throw new \DomainException(
                "Unsatisfied dependency: " . $param->getName()
            );
        }

        return $existingArgs;
    }
}

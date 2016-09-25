<?php
/**
 * PHPCI - Continuous Integration for PHP
 *
 * @copyright    Copyright 2014, Block 8 Limited.
 * @license      https://github.com/Block8/PHPCI/blob/master/LICENSE.md
 * @link         https://www.phptesting.org/
 */

namespace PHPCI\Plugin\Option;

/**
 * Class PhpUnitOptions validates and parse the option for the PhpUnitV2 plugin
 *
 * @author       Pablo Tejada <pablo@ptejada.com>
 * @package      PHPCI
 * @subpackage   Plugin
 */
class PhpUnitOptions
{
    protected $options;
    protected $arguments = array();

    public function __construct($options)
    {
        $this->options = $options;
    }

    /**
     * Remove a command argument
     *
     * @param $argumentName
     *
     * @return $this
     */
    public function removeArgument($argumentName)
    {
        unset($this->arguments[$argumentName]);
        return $this;
    }

    /**
     * Combine all the argument into a string for the phpunit command
     *
     * @return string
     */
    public function buildArgumentString()
    {
        $argumentString = '';
        foreach ($this->getCommandArguments() as $argumentName => $argumentValues) {
            $prefix = $argumentName[0] == '-' ? '' : '--';

            if (!is_array($argumentValues)) {
                $argumentValues = array($argumentValues);
            }

            foreach ($argumentValues as $argValue) {
                $postfix = ' ';
                if (!empty($argValue)) {
                    $postfix = ' "' . $argValue . '" ';
                }
                $argumentString .= $prefix . $argumentName . $postfix;
            }
        }

        return $argumentString;
    }

    /**
     * Get all the command arguments
     *
     * @return string[]
     */
    public function getCommandArguments()
    {
        /*
         * Return the full list of arguments
         */
        return $this->parseArguments()->arguments;
    }

    /**
     * Parse the arguments from the config options
     *
     * @return $this
     */
    private function parseArguments()
    {
        if (empty($this->arguments)) {
            /*
             * Parse the arguments from the YML options file
             */
            if (isset($this->options['args'])) {
                $rawArgs = $this->options['args'];
                if (is_array($rawArgs)) {
                    $this->arguments = $rawArgs;
                } else {
                    /*
                     * Try to parse old argument in a single string
                     */
                    preg_match_all('/--([a-z\-]+)\s?("?[^-]{2}[^"]*"?)?/', (string)$rawArgs, $argsMatch);

                    if (!empty($argsMatch) && sizeof($argsMatch) > 2) {
                        foreach ($argsMatch[1] as $index => $argName) {
                            $this->addArgument($argName, $argsMatch[2][$index]);
                        }
                    }
                }
            }

            /*
             * Handles command aliases outside of the args option
             */
            if (isset($this->options['coverage'])) {
                $this->addArgument('coverage-html', $this->options['coverage']);
            }

            /*
             * Handles command aliases outside of the args option
             */
            if (isset($this->options['config'])) {
                $this->addArgument('configuration', $this->options['config']);
            }
        }

        return $this;
    }

    /**
     * Add an argument to the collection
     * Note: adding argument before parsing the options will prevent the other options from been parsed.
     *
     * @param string $argumentName
     * @param string $argumentValue
     */
    public function addArgument($argumentName, $argumentValue)
    {
        if (isset($this->arguments[$argumentName])) {
            if (!is_array($this->arguments[$argumentName])) {
                // Convert existing argument values into an array
                $this->arguments[$argumentName] = array($this->arguments[$argumentName]);
            }

            // Appends the new argument to the list
            $this->arguments[$argumentName][] = $argumentValue;
        } else {
            // Adds new argument
            $this->arguments[$argumentName] = $argumentValue;
        }
    }

    /**
     * Get the list of directory to run phpunit in
     *
     * @return string[] List of directories
     */
    public function getDirectories()
    {
        $directories = $this->getOption('directory');

        if (is_string($directories)) {
            $directories = array($directories);
        } else {
            if (is_null($directories)) {
                $directories = array();
            }
        }

        return is_array($directories) ? $directories : array($directories);
    }

    /**
     * Get an option if defined
     *
     * @param $optionName
     *
     * @return string[]|string|null
     */
    public function getOption($optionName)
    {
        if (isset($this->options[$optionName])) {
            return $this->options[$optionName];
        }

        return null;
    }

    /**
     * Get the directory to execute the command from
     *
     * @return mixed|null
     */
    public function getRunFrom()
    {
        return $this->getOption('run_from');
    }

    /**
     * Ge the directory name where tests file reside
     *
     * @return string|null
     */
    public function getTestsPath()
    {
        return $this->getOption('path');
    }

    /**
     * Get the PHPUnit configuration from the options, or the optional path
     *
     * @param string $altPath
     *
     * @return string[] path of files
     */
    public function getConfigFiles($altPath = '')
    {
        $configFiles = $this->getArgument('configuration');
        if (empty($configFiles)) {
            $configFile = self::findConfigFile($altPath);
            if ($configFile) {
                $configFiles[] = $configFile;
            }
        }

        return $configFiles;
    }

    /**
     * Get options for a given argument
     *
     * @param $argumentName
     *
     * @return string[] All the options for given argument
     */
    public function getArgument($argumentName)
    {
        $this->parseArguments();

        if (isset($this->arguments[$argumentName])) {
            return is_array(
                $this->arguments[$argumentName]
            ) ? $this->arguments[$argumentName] : array($this->arguments[$argumentName]);
        }

        return array();
    }

    /**
     * Find a PHPUnit configuration file in a directory
     *
     * @param string $buildPath The path to configuration file
     *
     * @return null|string
     */
    public static function findConfigFile($buildPath)
    {
        $files = array(
            'phpunit.xml',
            'phpunit.xml.dist',
            'tests/phpunit.xml',
            'tests/phpunit.xml.dist',
        );

        foreach ($files as $file) {
            if (is_file($buildPath . $file)) {
                return $file;
            }
        }

        return null;
    }
}

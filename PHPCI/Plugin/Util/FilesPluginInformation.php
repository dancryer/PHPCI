<?php

namespace PHPCI\Plugin\Util;

use PHPCI\Plugin;

/**
 * Class FilesPluginInformation
 * @package PHPCI\Plugin\Util
 */
class FilesPluginInformation implements InstalledPluginInformation
{

    /**
     * A collection of all the file path information for
     * the installed plugins.
     *
     * @var \SplFileInfo[]
     */
    protected $files;

    /**
     * Each item in the array contains the information for
     * a single plugin.
     *
     * @var array
     */
    protected $pluginInfo = null;

    /**
     * @param $dirPath
     * @return FilesPluginInformation
     */
    public static function newFromDir($dirPath)
    {
        return new self(new \DirectoryIterator($dirPath));
    }

    /**
     * @param \Iterator $files
     */
    public function __construct(\Iterator $files)
    {
        $this->files = $files;
    }

    /**
     * Returns an array of objects. Each one represents an available plugin
     * and will have the following properties:
     *      name  - The friendly name of the plugin (may be an empty string)
     *      class - The class of the plugin (will include namespace)
     * @return \stdClass[]
     */
    public function getInstalledPlugins()
    {
        if ($this->pluginInfo === null) {
            $this->loadPluginInfo();
        }
        return $this->pluginInfo;
    }

    /**
     * Returns an array of all the class names of plugins that have been
     * loaded.
     *
     * @return string[]
     */
    public function getPluginClasses()
    {
        return array_map(
            function (\stdClass $plugin) {
               return $plugin->class;
            },
            $this->getInstalledPlugins()
        );
    }

    /**
     * Load plugin information from a given list of files.
     */
    protected function loadPluginInfo()
    {
        $this->pluginInfo = array();
        foreach ($this->files as $fileInfo) {
            if ($fileInfo instanceof \SplFileInfo) {
                if ($fileInfo->isFile() && $fileInfo->getExtension()=='php') {
                    $this->addPluginFromFile($fileInfo);
                }
            }
        }
    }

    /**
     * Add a plugin to the list from a given file.
     * @param \SplFileInfo $fileInfo
     */
    protected function addPluginFromFile(\SplFileInfo $fileInfo)
    {
        $class = $this->getFullClassFromFile($fileInfo);

        if (!is_null($class)) {
            $newPlugin = new \stdClass();
            $newPlugin->class = $class;
            $newPlugin->source = "core";
            $parts = explode('\\', $newPlugin->class);
            $newPlugin->name = end($parts);

            $this->pluginInfo[] = $newPlugin;
        }
    }

    /**
     * Determine plugin class name for a given file.
     * @param \SplFileInfo $fileInfo
     * @return null|string
     */
    protected function getFullClassFromFile(\SplFileInfo $fileInfo)
    {
        $contents = file_get_contents($fileInfo->getRealPath());
        $matches = array();

        preg_match('#class +([A-Za-z]+) +implements#i', $contents, $matches);

        if (isset($matches[1])) {
            $className = $matches[1];
    
            $matches = array();
            preg_match('#namespace +([A-Za-z\\\\]+);#i', $contents, $matches);
            $namespace = $matches[1];
    
            return $namespace . '\\' . $className;
        } else {
            return null;
        }
    }
}

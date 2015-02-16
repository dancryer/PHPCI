<?php

namespace PHPCI\Plugin\Util;

use PHPCI\Plugin;

/**
 * Class ComposerPluginInformation
 * @package PHPCI\Plugin\Util
 */
class ComposerPluginInformation implements InstalledPluginInformation
{
    /**
     * @var array
     */
    protected $composerPackages;

    /**
     * @var array
     */
    protected $pluginInfo = null;

    /**
     * @param string $filePath The path of installed.json created by composer.
     * @return ComposerPluginInformation
     */
    public static function buildFromYaml($filePath)
    {
        if (file_exists($filePath)) {
            $installed = json_decode(file_get_contents($filePath));
        } else {
            $installed = array();
        }
        return new self($installed);
    }

    /**
     * @param \stdClass[] $composerPackages This should be the contents of the
     *                                   installed.json file created by composer
     */
    public function __construct(array $composerPackages)
    {
        $this->composerPackages = $composerPackages;
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
        $this->loadPluginInfo();
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
            function (Plugin $plugin) {
                return $plugin->class;
            },
            $this->getInstalledPlugins()
        );
    }

    /**
     * Load a list of available plugins from the installed composer packages.
     */
    protected function loadPluginInfo()
    {
        if ($this->pluginInfo !== null) {
            return;
        }
        $this->pluginInfo = array();
        foreach ($this->composerPackages as $package) {
            $this->addPluginsFromPackage($package);
        }
    }

    /**
     * @param \stdClass $package
     */
    protected function addPluginsFromPackage($package)
    {
        if (isset($package->extra->phpci)) {
            $phpciData = $package->extra->phpci;

            if (isset($phpciData->pluginNamespace)) {
                $rootNamespace = $phpciData->pluginNamespace;
            } else {
                $rootNamespace = "";
            }

            if (is_array($phpciData->suppliedPlugins)) {
                $this->addPlugins(
                    $phpciData->suppliedPlugins,
                    $package->name,
                    $rootNamespace
                );
            }
        }
    }

    /**
     * @param \stdClass[] $plugins
     * @param string $sourcePackageName
     * @param string $rootNamespace
     */
    protected function addPlugins(
        array $plugins,
        $sourcePackageName,
        $rootNamespace = ""
    ) {
        foreach ($plugins as $plugin) {
            if (!isset($plugin->class)) {
                continue;
            }
            $this->addPlugin($plugin, $sourcePackageName, $rootNamespace);
        }
    }

    /**
     * @param \stdClass $plugin
     * @param string $sourcePackageName
     * @param string $rootNamespace
     */
    protected function addPlugin(
        $plugin,
        $sourcePackageName,
        $rootNamespace = ""
    ) {
        $newPlugin = clone $plugin;

        $newPlugin->class = $rootNamespace . $newPlugin->class;

        if (!isset($newPlugin->name)) {
            $newPlugin->name = "";
        }

        $newPlugin->source = $sourcePackageName;

        $this->pluginInfo[] = $newPlugin;
    }
}

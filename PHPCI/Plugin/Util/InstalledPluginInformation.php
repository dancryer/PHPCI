<?php
namespace PHPCI\Plugin\Util;

/**
 * Interface InstalledPluginInformation
 * @package PHPCI\Plugin\Util
 */
interface InstalledPluginInformation
{
    /**
     * Returns an array of objects. Each one represents an available plugin
     * and will have the following properties:
     *      name  - The friendly name of the plugin (may be an empty string)
     *      class - The class of the plugin (will include namespace)
     * @return \stdClass[]
     */
    public function getInstalledPlugins();

    /**
     * Returns an array of all the class names of plugins that have been
     * loaded.
     *
     * @return string[]
     */
    public function getPluginClasses();
}

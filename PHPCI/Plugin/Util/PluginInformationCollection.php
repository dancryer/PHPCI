<?php
namespace PHPCI\Plugin\Util;

/**
 * Class PluginInformationCollection
 */
class PluginInformationCollection implements InstalledPluginInformation
{
    /**
     * @type InstalledPluginInformation[]
     */
    protected $pluginInformations = [];

    /**
     * Add a plugin to the collection.
     *
     * @param InstalledPluginInformation $information
     */
    public function add(InstalledPluginInformation $information)
    {
        $this->pluginInformations[] = $information;
    }

    /**
     * Returns an array of objects. Each one represents an available plugin
     * and will have the following properties:
     *      name  - The friendly name of the plugin (may be an empty string)
     *      class - The class of the plugin (will include namespace)
     *
     * @return \stdClass[]
     */
    public function getInstalledPlugins()
    {
        $arr = [];

        foreach ($this->pluginInformations as $single) {
            $arr = array_merge($arr, $single->getInstalledPlugins());
        }

        return $arr;
    }

    /**
     * Returns an array of all the class names of plugins that have been
     * loaded.
     *
     * @return string[]
     */
    public function getPluginClasses()
    {
        $arr = [];

        foreach ($this->pluginInformations as $single) {
            $arr = array_merge($arr, $single->getPluginClasses());
        }

        return $arr;
    }
}

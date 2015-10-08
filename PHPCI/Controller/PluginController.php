<?php
/**
 * PHPCI - Continuous Integration for PHP
 *
 * @copyright    Copyright 2014, Block 8 Limited.
 * @license      https://github.com/Block8/PHPCI/blob/master/LICENSE.md
 * @link         https://www.phptesting.org/
 */

namespace PHPCI\Controller;

use b8;
use PHPCI\Helper\Lang;
use PHPCI\Plugin\Util\ComposerPluginInformation;
use PHPCI\Plugin\Util\FilesPluginInformation;
use PHPCI\Plugin\Util\PluginInformationCollection;

/**
 * Plugin Controller - Provides support for installing Composer packages.
 * @author       Dan Cryer <dan@block8.co.uk>
 * @package      PHPCI
 * @subpackage   Web
 */
class PluginController extends \PHPCI\Controller
{
    /**
     * List all enabled plugins, installed and recommend packages.
     * @return string
     */
    public function index()
    {
        $this->requireAdmin();

        $json = $this->getComposerJson();
        $this->view->installedPackages = $json['require'];

        $pluginInfo = new PluginInformationCollection();
        $pluginInfo->add(FilesPluginInformation::newFromDir(
            PHPCI_DIR . "PHPCI/Plugin/"
        ));
        $pluginInfo->add(ComposerPluginInformation::buildFromYaml(
            PHPCI_DIR . "vendor/composer/installed.json"
        ));

        $this->view->plugins = $pluginInfo->getInstalledPlugins();

        $this->layout->title = Lang::get('plugins');

        return $this->view->render();
    }

    /**
     * Get the json-decoded contents of the composer.json file.
     * @return mixed
     */
    protected function getComposerJson()
    {
        $json = file_get_contents(APPLICATION_PATH . 'composer.json');
        return json_decode($json, true);
    }
}

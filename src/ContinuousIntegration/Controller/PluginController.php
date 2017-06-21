<?php
/**
 * Kiboko CI - Continuous Integration for PHP
 *
 * @copyright    Copyright 2014, Block 8 Limited.
 * @license      https://github.com/kiboko-labs/ci/blob/master/LICENSE.md
 * @link         https://www.phptesting.org/
 */

namespace Kiboko\Component\ContinuousIntegration\Controller;

use b8;
use Kiboko\Component\ContinuousIntegration\Helper\Lang;
use Kiboko\Component\ContinuousIntegration\Plugin\Util\ComposerPluginInformation;
use Kiboko\Component\ContinuousIntegration\Plugin\Util\FilesPluginInformation;
use Kiboko\Component\ContinuousIntegration\Plugin\Util\PluginInformationCollection;

/**
 * Plugin Controller - Provides support for installing Composer packages.
 * @author       Dan Cryer <dan@block8.co.uk>
 * @package      Kiboko\Component\ContinuousIntegration
 * @subpackage   Web
 */
class PluginController extends \Kiboko\Component\ContinuousIntegration\Controller
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
            KIBOKO_CI_APP_DIR . "src/Plugin/"
        ));
        $pluginInfo->add(ComposerPluginInformation::buildFromYaml(
            KIBOKO_CI_APP_DIR . "vendor/composer/installed.json"
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

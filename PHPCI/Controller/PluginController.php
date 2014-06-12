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
use PHPCI\Model\Build;
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
    protected $required = array(
        'block8/b8framework',
        'ircmaxell/password-compat',
        'swiftmailer/swiftmailer',
        'symfony/yaml',
        'symfony/console',
        'psr/log',
        'monolog/monolog',
        'pimple/pimple'
    );

    protected $canInstall;
    protected $composerPath;

    public function index()
    {
        if (!$_SESSION['user']->getIsAdmin()) {
            throw new \Exception('You do not have permission to do that.');
        }

        $this->view->canWrite = is_writable(APPLICATION_PATH . 'composer.json');
        $this->view->required = $this->required;

        $json = $this->getComposerJson();
        $this->view->installedPackages = $json['require'];
        $this->view->suggestedPackages = $json['suggest'];

        $pluginInfo = new PluginInformationCollection();
        $pluginInfo->add(FilesPluginInformation::newFromDir(
            PHPCI_DIR . "PHPCI/Plugin/"
        ));
        $pluginInfo->add(ComposerPluginInformation::buildFromYaml(
            PHPCI_DIR . "vendor/composer/installed.json"
        ));

        $this->view->plugins = $pluginInfo->getInstalledPlugins();

        $this->config->set('page_title', 'Plugins');

        return $this->view->render();
    }

    public function remove()
    {
        if (!$_SESSION['user']->getIsAdmin()) {
            throw new \Exception('You do not have permission to do that.');
        }

        $package = $this->getParam('package', null);
        $json = $this->getComposerJson();

        if (!in_array($package, $this->required)) {
            unset($json['require'][$package]);
            $this->setComposerJson($json);

            header('Location: ' . PHPCI_URL . 'plugin?r=' . $package);
            die;
        }

        header('Location: ' . PHPCI_URL);
        die;
    }

    public function install()
    {
        if (!$_SESSION['user']->getIsAdmin()) {
            throw new \Exception('You do not have permission to do that.');
        }

        $package = $this->getParam('package', null);
        $version = $this->getParam('version', '*');

        $json = $this->getComposerJson();
        $json['require'][$package] = $version;
        $this->setComposerJson($json);

        header('Location: ' . PHPCI_URL . 'plugin?w=' . $package);
        die;
    }

    protected function getComposerJson()
    {
        $json = file_get_contents(APPLICATION_PATH . 'composer.json');
        return json_decode($json, true);
    }

    protected function setComposerJson($array)
    {
        $json = json_encode($array);
        file_put_contents(APPLICATION_PATH . 'composer.json', $json);
    }

    protected function findBinary($binary)
    {
        if (is_string($binary)) {
            $binary = array($binary);
        }

        foreach ($binary as $bin) {
            // Check project root directory:
            if (is_file(APPLICATION_PATH . $bin)) {
                return APPLICATION_PATH . $bin;
            }

            // Check Composer bin dir:
            if (is_file(APPLICATION_PATH . 'vendor/bin/' . $bin)) {
                return APPLICATION_PATH . 'vendor/bin/' . $bin;
            }

            // Use "which"
            $which = trim(shell_exec('which ' . $bin));

            if (!empty($which)) {
                return $which;
            }
        }

        return null;
    }

    public function packagistSearch()
    {
        $searchQuery = $this->getParam('q', '');
        $http = new \b8\HttpClient();
        $http->setHeaders(array('User-Agent: PHPCI/1.0 (+https://www.phptesting.org)'));
        $res = $http->get('https://packagist.org/search.json', array('q' => $searchQuery));

        die(json_encode($res['body']));
    }

    public function packagistVersions()
    {
        $name = $this->getParam('p', '');
        $http = new \b8\HttpClient();
        $http->setHeaders(array('User-Agent: PHPCI/1.0 (+https://www.phptesting.org)'));
        $res = $http->get('https://packagist.org/packages/'.$name.'.json');

        die(json_encode($res['body']));
    }
}

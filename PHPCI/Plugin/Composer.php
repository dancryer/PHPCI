<?php
/**
 * PHPCI - Continuous Integration for PHP
 *
 * @copyright    Copyright 2014, Block 8 Limited.
 * @license      https://github.com/Block8/PHPCI/blob/master/LICENSE.md
 * @link         https://www.phptesting.org/
 */

namespace PHPCI\Plugin;

use PHPCI\Builder;
use PHPCI\Helper\Environment;
use PHPCI\Helper\Lang;
use PHPCI\Model\Build;
use PHPCI\Plugin;
use PHPCI\ZeroConfigPlugin;

/**
 * Composer Plugin - Provides access to Composer functionality.
 * @author       Dan Cryer <dan@block8.co.uk>
 * @package      PHPCI
 * @subpackage   Plugins
 */
class Composer implements Plugin, ZeroConfigPlugin
{
    /**
     * @var string
     */
    protected $directory;

    /**
     * @var string
     */
    protected $action;

    /**
     * @var boolean
     */
    protected $preferDist;

    /**
     * @var Builder
     */
    protected $phpci;

    /**
     * @var Environment
     */
    protected $environment;

    /**
     * Check if this plugin can be executed.
     *
     * @param $stage
     * @param Builder $builder
     * @param Build $build
     *
     * @return bool
     */
    public static function canExecute($stage, Builder $builder, Build $build)
    {
        $path = $builder->buildPath . DIRECTORY_SEPARATOR . 'composer.json';

        if (file_exists($path) && $stage == 'setup') {
            return true;
        }

        return false;
    }

    /**
     * Set up the plugin, configure options, etc.
     *
     * @param Builder $phpci
     * @param Build $build
     * @param Environment $environment
     * @param array $options
     */
    public function __construct(Builder $phpci, Environment $environment, array $options = array())
    {
        $path = $phpci->buildPath;
        $this->phpci = $phpci;
        $this->directory = $path;
        $this->environment = $environment;
        $this->action = 'install';
        $this->preferDist = false;

        if (array_key_exists('directory', $options)) {
            $this->directory .= rtrim($options['directory'], '\\/');
        }

        if (array_key_exists('action', $options)) {
            $this->action = $options['action'];
        }

        if (array_key_exists('prefer_dist', $options)) {
            $this->preferDist = (bool) $options['prefer_dist'];
        }
    }

    /**
     * Executes Composer and runs a specified command (e.g. install / update)
     *
     * @return boolean
     */
    public function execute()
    {
        $composerLocation = $this->phpci->findBinary(array('composer', 'composer.phar'));

        if (!$composerLocation) {
            $this->phpci->logFailure(Lang::get('could_not_find', 'composer'));
            return false;
        }

        $composerJsonPath = $this->directory . DIRECTORY_SEPARATOR . "composer.json";

        if (!file_exists($composerJsonPath)) {
            $this->phpci->logFailure(sprintf("%s does not exist", $composerJsonPath));
            return false;
        }

        $sourceFlag = $this->preferDist ? '--prefer-dist' : '--prefer-source';
        $this->phpci->log(sprintf('Using %s flag', $sourceFlag));

        if (!$this->phpci->executeCommand(
            'php %s %s --no-interaction %s --working-dir="%s"',
            $composerLocation,
            $this->action,
            $sourceFlag,
            $this->directory
        )) {
            return false;
        }

        $binPath = $this->getBinDir($composerJsonPath);
        if ($binPath) {
            $this->phpci->log(sprintf('Adding %s to PATH', $binPath));
            $this->environment->addPath($binPath);
        }

        return true;
    }

    /** Extract the path of the package binaries.
     *
     * @param string $composerJsonPAth
     *
     * @return $composerJsonPath
     */
    public function getBinDir($composerJsonPath)
    {
        $json = json_decode(file_get_contents($composerJsonPath), true);

        $binPath = dirname($composerJsonPath) . DIRECTORY_SEPARATOR;

        if (isset($json['config']['bin-dir'])) {
            $binPath .= $json['config']['bin-dir'];
        } else {
            $binPath .= 'vendor' . DIRECTORY_SEPARATOR . 'bin';
        }

        if (is_dir($binPath)) {
            return $binPath;
        }
    }
}

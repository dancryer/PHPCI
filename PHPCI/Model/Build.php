<?php
/**
 * PHPCI - Continuous Integration for PHP
 *
 * @copyright    Copyright 2014, Block 8 Limited.
 * @license      https://github.com/Block8/PHPCI/blob/master/LICENSE.md
 * @link         https://www.phptesting.org/
 */

namespace PHPCI\Model;

use b8\Store\Factory;
use PHPCI\Model\Base\BuildBase;
use PHPCI\Builder;
use Symfony\Component\Yaml\Parser as YamlParser;

/**
* Build Model
* @uses         PHPCI\Model\Base\BuildBase
* @author       Dan Cryer <dan@block8.co.uk>
* @package      PHPCI
* @subpackage   Core
*/
class Build extends BuildBase
{
    const STATUS_NEW = 0;
    const STATUS_RUNNING = 1;
    const STATUS_SUCCESS = 2;
    const STATUS_FAILED = 3;

    public $currentBuildPath;

    /**
    * Get link to commit from another source (i.e. Github)
    */
    public function getCommitLink()
    {
        return '#';
    }

    /**
    * Get link to branch from another source (i.e. Github)
    */
    public function getBranchLink()
    {
        return '#';
    }

    /**
     * Return a template to use to generate a link to a specific file.
     * @return null
     */
    public function getFileLinkTemplate()
    {
        return null;
    }

    /**
    * Send status updates to any relevant third parties (i.e. Github)
    */
    public function sendStatusPostback()
    {
        return;
    }

    /**
     * @return string
     */
    public function getProjectTitle()
    {
        $project = $this->getProject();
        return $project ? $project->getTitle() : "";
    }

    /**
     * Store build metadata
     */
    public function storeMeta($key, $value)
    {
        $value = json_encode($value);
        Factory::getStore('Build')->setMeta($this->getProjectId(), $this->getId(), $key, $value);
    }

    /**
     * Is this build successful?
     */
    public function isSuccessful()
    {
        return ($this->getStatus() === self::STATUS_SUCCESS);
    }

    /**
     * @param Builder $builder
     * @param string  $buildPath
     *
     * @return bool
     */
    protected function handleConfig(Builder $builder, $buildPath)
    {
        $build_config = null;

        // Try getting the project build config from the database:
        if (empty($build_config)) {
            $build_config = $this->getProject()->getBuildConfig();
        }

        // Try .phpci.yml
        if (is_file($buildPath . '/.phpci.yml')) {
            $build_config = file_get_contents($buildPath . '/.phpci.yml');
        }

        // Try phpci.yml first:
        if (empty($build_config) && is_file($buildPath . '/phpci.yml')) {
            $build_config = file_get_contents($buildPath . '/phpci.yml');
        }

        // Fall back to zero config plugins:
        if (empty($build_config)) {
            $build_config = $this->getZeroConfigPlugins($builder);
        }

        if (is_string($build_config)) {
            $yamlParser = new YamlParser();
            $build_config = $yamlParser->parse($build_config);
        }

        $builder->setConfigArray($build_config);
        return true;
    }

    /**
     * Get an array of plugins to run if there's no phpci.yml file.
     * @param Builder $builder
     * @return array
     */
    protected function getZeroConfigPlugins(Builder $builder)
    {
        $pluginDir = PHPCI_DIR . 'PHPCI/Plugin/';
        $dir = new \DirectoryIterator($pluginDir);

        $config = array(
            'build_settings' => array(
                'ignore' => array(
                    'vendor',
                )
            )
        );

        foreach ($dir as $item) {
            if ($item->isDot()) {
                continue;
            }

            if (!$item->isFile()) {
                continue;
            }

            if ($item->getExtension() != 'php') {
                continue;
            }

            $className = '\PHPCI\Plugin\\'.$item->getBasename('.php');

            $reflectedPlugin = new \ReflectionClass($className);

            if (!$reflectedPlugin->implementsInterface('\PHPCI\ZeroConfigPlugin')) {
                continue;
            }

            foreach (array('setup', 'test', 'complete', 'success', 'failure') as $stage) {
                if ($className::canExecute($stage, $builder, $this)) {
                    $config[$stage][$className] = array(
                        'zero_config' => true
                    );
                }
            }
        }

        return $config;
    }

    /**
     * Return a value from the build's "extra" JSON array.
     * @param null $key
     * @return mixed|null|string
     */
    public function getExtra($key = null)
    {
        $data = json_decode($this->data['extra'], true);

        if (is_null($key)) {
            $rtn = $data;
        } elseif (isset($data[$key])) {
            $rtn = $data[$key];
        } else {
            $rtn = null;
        }

        return $rtn;
    }

    /**
     * Returns the commit message for this build.
     * @return string
     */
    public function getCommitMessage()
    {
        $rtn = htmlspecialchars($this->data['commit_message']);

        return $rtn;
    }

    /**
     * Allows specific build types (e.g. Github) to report violations back to their respective services.
     * @param Builder $builder
     * @param $plugin
     * @param $message
     * @param int $severity
     * @param null $file
     * @param null $lineStart
     * @param null $lineEnd
     * @return BuildError
     */
    public function reportError(
        Builder $builder,
        $plugin,
        $message,
        $severity = BuildError::SEVERITY_NORMAL,
        $file = null,
        $lineStart = null,
        $lineEnd = null
    ) {
        unset($builder);

        $error = new BuildError();
        $error->setBuild($this);
        $error->setCreatedDate(new \DateTime());
        $error->setPlugin($plugin);
        $error->setMessage($message);
        $error->setSeverity($severity);
        $error->setFile($file);
        $error->setLineStart($lineStart);
        $error->setLineEnd($lineEnd);

        return Factory::getStore('BuildError')->save($error);
    }

    /**
     * Return the path to run this build into.
     *
     * @return string|null
     */
    public function getBuildPath()
    {
        if (!$this->getId()) {
            return null;
        }

        if (empty($this->currentBuildPath)) {
            $buildDirectory = $this->getId() . '_' . substr(md5(microtime(true)), 0, 5);
            $this->currentBuildPath = PHPCI_BUILD_ROOT_DIR . $buildDirectory;
        }

        return $this->currentBuildPath;
    }

    /**
     * Removes the build directory.
     */
    public function removeBuildDirectory()
    {
        $buildPath = $this->getBuildPath();

        if (!$buildPath || !is_dir($buildPath)) {
            return;
        }

        exec(sprintf(IS_WIN ? 'rmdir /S /Q "%s"' : 'rm -Rf "%s"', $buildPath));
    }

    /**
     * Get the number of seconds a build has been running for.
     * @return int
     */
    public function getDuration()
    {
        $start = $this->getStarted();

        if (empty($start)) {
            return 0;
        }

        $end = $this->getFinished();

        if (empty($end)) {
            $end = new \DateTime();
        }

        return $end->getTimestamp() - $start->getTimestamp();
    }
}

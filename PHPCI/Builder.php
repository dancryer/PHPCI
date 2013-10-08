<?php
/**
* PHPCI - Continuous Integration for PHP
*
* @copyright    Copyright 2013, Block 8 Limited.
* @license      https://github.com/Block8/PHPCI/blob/master/LICENSE.md
* @link         http://www.phptesting.org/
*/

namespace PHPCI;

use PHPCI\Model\Build;
use b8\Store;
use b8\Config;

/**
* PHPCI Build Runner
* @author   Dan Cryer <dan@block8.co.uk>
*/
class Builder
{
    /**
    * @var string
    */
    public $buildPath;

    /**
    * @var string[]
    */
    public $ignore  = array();

    /**
    * @var string
    */
    protected $ciDir;

    /**
    * @var string
    */
    protected $directory;

    /**
    * @var bool
    */
    protected $success  = true;

    /**
    * @var string
    */
    protected $log      = '';

    /**
    * @var bool
    */
    protected $verbose  = false;

    /**
    * @var bool[]
    */
    protected $plugins  = array();

    /**
    * @var \PHPCI\Model\Build
    */
    protected $build;

    /**
    * @var callable
    */
    protected $logCallback;

    /**
    * @var array
    */
    protected $config;

    /**
     * @var string
     */
    protected $lastOutput;

    /**
     * An array of key => value pairs that will be used for 
     * interpolation and environment variables
     * @var array
     * @see setInterpolationVars()
     * @see getInterpolationVars()
     */
    protected $interpolation_vars = array();

    /**
     * @var \PHPCI\Store\BuildStore
     */
    protected $store;

    /**
    * Set up the builder.
    * @param \PHPCI\Model\Build
    * @param callable
    */
    public function __construct(Build $build, $logCallback = null)
    {
        $this->build = $build;
        $this->store = Store\Factory::getStore('Build');

        if (!is_null($logCallback) && is_callable($logCallback)) {
            $this->logCallback = $logCallback;
        }
    }

    /**
    * Set the config array, as read from phpci.yml
    * @param array
    */
    public function setConfigArray(array $config)
    {
        $this->config = $config;
    }

    /**
    * Access a variable from the phpci.yml file.
    * @param string
    */
    public function getConfig($key)
    {
        return isset($this->config[$key]) ? $this->config[$key] : null;
    }

    /**
     * Access a variable from the config.yml
     * @param $key
     * @return mixed
     */
    public function getSystemConfig($key)
    {
        return Config::getInstance()->get($key);
    }

    /**
    * Access the build.
    * @param Build
    */
    public function getBuild()
    {
        return $this->build;
    }

    /**
     * @return string   The title of the project being built.
     */
    public function getBuildProjectTitle() {
        return $this->getBuild()->getProject()->getTitle();
    }

    /**
     * Indicates if the build has passed or failed.
     * @return bool
     */
    public function getSuccessStatus()
    {
        return $this->success;
    }

    /**
    * Run the active build.
    */
    public function execute()
    {
        // Update the build in the database, ping any external services.
        $this->build->setStatus(1);
        $this->build->setStarted(new \DateTime());
        $this->store->save($this->build);
        $this->build->sendStatusPostback();

        try {
            if ($this->setupBuild()) {
                // Run setup steps:
                $this->executePlugins('setup');

                // Run the any tests:
                $this->executePlugins('test');
                $this->log('');

                // Run build complete steps:
                $this->executePlugins('complete');

                // Run success or failure plugins:
                if ($this->success) {
                    $this->build->setStatus(2);

                    $this->executePlugins('success');
                    $this->logSuccess('BUILD SUCCESSFUL!');
                } else {
                    $this->build->setStatus(3);

                    $this->executePlugins('failure');
                    $this->logFailure('BUILD FAILED!');
                }

                $this->log('');
            } else {
                $this->build->setStatus(3);
            }
        } catch (\Exception $ex) {
            $this->logFailure($ex->getMessage());
            $this->build->setStatus(3);
        }
        
        // Clean up:
        $this->removeBuild();

        // Update the build in the database, ping any external services, etc.
        $this->build->sendStatusPostback();
        $this->build->setFinished(new \DateTime());
        $this->build->setLog($this->log);
        $this->build->setPlugins(json_encode($this->plugins));
        $this->store->save($this->build);
    }

    /**
    * Used by this class, and plugins, to execute shell commands. 
    */
    public function executeCommand()
    {
        $command = call_user_func_array('sprintf', func_get_args());
        
        $this->log('Executing: ' . $command, '  ');

        $status = 0;
        exec($command, $this->lastOutput, $status);

        if (!empty($this->lastOutput) && ($this->verbose || $status != 0)) {
            $this->log($this->lastOutput, '       ');
        }

        return ($status == 0) ? true : false;
    }

    /**
     * Returns the output from the last command run.
     */
    public function getLastOutput()
    {
        return implode(PHP_EOL, $this->lastOutput);
    }

    /**
    * Add an entry to the build log. 
    * @param string|string[]
    * @param string
    */
    public function log($message, $prefix = '')
    {
        if (is_array($message)) {
            foreach ($message as $item) {
                if (is_callable($this->logCallback)) {
                    call_user_func_array($this->logCallback, array($prefix . $item));
                }
                
                $this->log .= $prefix . $item . PHP_EOL;
            }
        } else {
            $message = $prefix . $message;
            $this->log .= $message . PHP_EOL;

            if (isset($this->logCallback) && is_callable($this->logCallback)) {
                call_user_func_array($this->logCallback, array($message));
            }
        }

        $this->build->setLog($this->log);
        $this->build->setPlugins(json_encode($this->plugins));
        $this->store->save($this->build);
    }

    /**
    * Add a success-coloured message to the log. 
    * @param string
    */
    public function logSuccess($message)
    {
        $this->log("\033[0;32m" . $message . "\033[0m");
    }

    /**
    * Add a failure-coloured message to the log. 
    * @param string
    */
    public function logFailure($message)
    {
        $this->log("\033[0;31m" . $message . "\033[0m");
    }
    
    /**
     * Get an array key => value pairs that are used for interpolation
     * @return array
     */
    public function getInterpolationVars()
    {
        return $this->interpolation_vars;
    }
    
    /**
     * Replace every occurance of the interpolation vars in the given string
     * Example: "This is build %PHPCI_BUILD%" => "This is build 182"
     * @param string $input
     * @return string
     */
    public function interpolate($input)
    {
        $trans_table = array();
        foreach ($this->getInterpolationVars() as $key => $value) {
            $trans_table['%'.$key.'%'] = $value;
            $trans_table['%PHPCI_'.$key.'%'] = $value;
        }
        return strtr($input, $trans_table);
    }

    /**
     * Sets the variables that will be used for interpolation. This must be run 
     * from setupBuild() because prior to that, we don't know the buildPath
     */
    protected function setInterpolationVars()
    {
        $this->interpolation_vars = array(
            'PHPCI'               => 1,
            'COMMIT'        => $this->build->getCommitId(),
            'PROJECT'       => $this->build->getProject()->getId(),
            'BUILD'         => $this->build->getId(),
            'PROJECT_TITLE' => $this->build->getProject()->getTitle(),
            'BUILD_PATH'    => $this->buildPath,
            'BUILD_URI'     => PHPCI_URL . "build/view/" . $this->build->getId(),
        );
    }
    
    /**
    * Set up a working copy of the project for building.
    */
    protected function setupBuild()
    {
        $commitId           = $this->build->getCommitId();
        $buildId            = 'project' . $this->build->getProject()->getId() . '-build' . $this->build->getId();
        $this->ciDir        = dirname(__FILE__) . '/../';
        $this->buildPath    = $this->ciDir . 'build/' . $buildId . '/';
        
        $this->setInterpolationVars();
        
        // Setup environment vars that will be accessible during exec()
        foreach ($this->getInterpolationVars() as $key => $value) {
            putenv($key.'='.$value);
        }
        
        // Create a working copy of the project:
        if (!$this->build->createWorkingCopy($this, $this->buildPath)) {
            return false;
        }

        // Does the project's phpci.yml request verbose mode?
        if (!isset($this->config['build_settings']['verbose']) || !$this->config['build_settings']['verbose']) {
            $this->verbose = false;
        } else {
            $this->verbose = true;
        }

        // Does the project have any paths it wants plugins to ignore?
        if (isset($this->config['build_settings']['ignore'])) {
            $this->ignore = $this->config['build_settings']['ignore'];
        }

        $this->logSuccess('Working copy created: ' . $this->buildPath);
        return true;
    }

    /**
    * Execute a the appropriate set of plugins for a given build stage.
    */
    protected function executePlugins($stage)
    {
        // Ignore any stages for which we don't have plugins set:
        if (!array_key_exists($stage, $this->config) || !is_array($this->config[$stage])) {
            return;
        }

        foreach ($this->config[$stage] as $plugin => $options) {
            $this->log('');
            $this->log('RUNNING PLUGIN: ' . $plugin);

            // Is this plugin allowed to fail?
            if ($stage == 'test' && !isset($options['allow_failures'])) {
                $options['allow_failures'] = false;
            }

            $class = str_replace('_', ' ', $plugin);
            $class = ucwords($class);
            $class = 'PHPCI\\Plugin\\' . str_replace(' ', '', $class);

            if (!class_exists($class)) {
                $this->logFailure('Plugin does not exist: ' . $plugin);

                if ($stage == 'test') {
                    $this->plugins[$plugin] = false;

                    if (!$options['allow_failures']) {
                        $this->success = false;
                    }
                }

                continue;
            }

            try {
                $obj = new $class($this, $options);

                if (!$obj->execute()) {
                    if ($stage == 'test') {
                        $this->plugins[$plugin] = false;

                        if (!$options['allow_failures']) {
                            $this->success = false;
                        }
                    }

                    $this->logFailure('PLUGIN STATUS: FAILED');
                    continue;
                }
            } catch (\Exception $ex) {
                $this->logFailure('EXCEPTION: ' . $ex->getMessage());

                if ($stage == 'test') {
                    $this->plugins[$plugin] = false;

                    if (!$options['allow_failures']) {
                        $this->success = false;
                    }
                }

                $this->logFailure('PLUGIN STATUS: FAILED');
                continue;
            }

            if ($stage == 'test') {
                $this->plugins[$plugin] = true;
            }

            $this->logSuccess('PLUGIN STATUS: SUCCESS!');
        }
    }

    /**
    * Clean up our working copy.
    */
    protected function removeBuild()
    {
        $this->log('Removing build.');
        shell_exec(sprintf('rm -Rf "%s"', $this->buildPath));
    }

    /**
     * Store build meta data
     */
    public function storeBuildMeta($key, $value)
    {
        $value = json_encode($value);
        $this->store->setMeta($this->build->getProjectId(), $this->build->getId(), $key, $value);
    }

    /**
     * Find a binary required by a plugin.
     * @param $binary
     * @return null|string
     */
    public function findBinary($binary)
    {
        if (is_string($binary)) {
            $binary = array($binary);
        }

        foreach ($binary as $bin) {
            // Check project root directory:
            if (is_file(PHPCI_DIR . $bin)) {
                return PHPCI_DIR . $bin;
            }

            // Check Composer bin dir:
            if (is_file(PHPCI_DIR . 'vendor/bin/' . $bin)) {
                return PHPCI_DIR . 'vendor/bin/' . $bin;
            }

            // Use "which"
            $which = trim(shell_exec('which ' . $bin));

            if (!empty($which)) {
                return $which;
            }
        }

        return null;
    }
}

<?php
/**
 * PHPCI - Continuous Integration for PHP
 *
 * @copyright    Copyright 2013, Block 8 Limited.
 * @license      https://github.com/Block8/PHPCI/blob/master/LICENSE.md
 * @link         http://www.phptesting.org/
 */

namespace PHPCI;

use PHPCI\Helper\BuildInterpolator;
use PHPCI\Helper\CommandExecutor;
use PHPCI\Helper\MailerFactory;
use PHPCI\Logging\BuildLogger;
use PHPCI\Model\Build;
use b8\Config;
use b8\Store\Factory;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;
use PHPCI\Plugin\Util\Factory as PluginFactory;

/**
 * PHPCI Build Runner
 * @author   Dan Cryer <dan@block8.co.uk>
 */
class Builder implements LoggerAwareInterface
{
    /**
     * @var string
     */
    public $buildPath;

    /**
     * @var string[]
     */
    public $ignore = array();

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
    protected $success = true;

    /**
     * @var bool
     */
    protected $verbose = true;

    /**
     * @var \PHPCI\Model\Build
     */
    protected $build;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var array
     */
    protected $config;

    /**
     * @var string
     */
    protected $lastOutput;

    /**
     * @var BuildInterpolator
     */
    protected $interpolator;

    /**
     * @var \PHPCI\Store\BuildStore
     */
    protected $store;

    /**
     * @var bool
     */
    public $quiet = false;

    /**
     * @var \PHPCI\Plugin\Util\Executor
     */
    protected $pluginExecutor;

    /**
     * @var Helper\CommandExecutor
     */
    protected $commandExecutor;

    /**
     * @var Logging\BuildLogger
     */
    protected $buildLogger;

    /**
     * Set up the builder.
     * @param \PHPCI\Model\Build $build
     * @param LoggerInterface $logger
     */
    public function __construct(Build $build, LoggerInterface $logger = null)
    {
        $this->build = $build;
        $this->store = Factory::getStore('Build');

        $this->buildLogger = new BuildLogger($logger, $build);

        $pluginFactory = $this->buildPluginFactory($build);
        $pluginFactory->addConfigFromFile(PHPCI_DIR . "/pluginconfig.php");
        $this->pluginExecutor = new Plugin\Util\Executor($pluginFactory, $this->buildLogger);

        $this->commandExecutor = new CommandExecutor(
            $this->buildLogger,
            PHPCI_DIR,
            $this->quiet,
            $this->verbose
        );

        $this->interpolator = new BuildInterpolator();

    }

    /**
     * Set the config array, as read from phpci.yml
     * @param array|null $config
     * @throws \Exception
     */
    public function setConfigArray($config)
    {
        if (is_null($config) || !is_array($config)) {
            throw new \Exception('This project does not contain a phpci.yml file, or it is empty.');
        }

        $this->config = $config;
    }

    /**
     * Access a variable from the phpci.yml file.
     * @param string
     * @return mixed
     */
    public function getConfig($key)
    {
        $rtn = null;

        if (isset($this->config[$key])) {
            $rtn = $this->config[$key];
        }

        return $rtn;
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
     * @return string   The title of the project being built.
     */
    public function getBuildProjectTitle()
    {
        return $this->build->getProject()->getTitle();
    }

    /**
     * Run the active build.
     */
    public function execute()
    {
        // Update the build in the database, ping any external services.
        $this->build->setStatus(Build::STATUS_RUNNING);
        $this->build->setStarted(new \DateTime());
        $this->store->save($this->build);
        $this->build->sendStatusPostback();
        $this->success = true;

        try {
            // Set up the build:
            $this->setupBuild();

            // Run the core plugin stages:
            foreach (array('setup', 'test') as $stage) {
                $this->success &= $this->pluginExecutor->executePlugins($this->config, $stage);
            }

            // Set the status so this can be used by complete, success and failure
            // stages.
            if ($this->success) {
                $this->build->setStatus(Build::STATUS_SUCCESS);
            } else {
                $this->build->setStatus(Build::STATUS_FAILED);
            }

            // Complete stage plugins are always run
            $this->pluginExecutor->executePlugins($this->config, 'complete');

            if ($this->success) {
                $this->pluginExecutor->executePlugins($this->config, 'success');
                $this->buildLogger->logSuccess('BUILD SUCCESSFUL!');
            } else {
                $this->pluginExecutor->executePlugins($this->config, 'failure');
                $this->buildLogger->logFailure("BUILD FAILURE");
            }

            // Clean up:
            $this->buildLogger->log('Removing build.');

            $cmd = 'rm -Rf "%s"';
            if (IS_WIN) {
                $cmd = 'rmdir /S /Q "%s"';
            }
            $this->executeCommand($cmd, $this->buildPath);
        } catch (\Exception $ex) {
            $this->build->setStatus(Build::STATUS_FAILED);
            $this->buildLogger->logFailure('Exception: ' . $ex->getMessage());
        }


        // Update the build in the database, ping any external services, etc.
        $this->build->sendStatusPostback();
        $this->build->setFinished(new \DateTime());
        $this->store->save($this->build);
    }

    /**
     * Used by this class, and plugins, to execute shell commands.
     */
    public function executeCommand()
    {
        return $this->commandExecutor->buildAndExecuteCommand(func_get_args());
    }

    /**
     * Returns the output from the last command run.
     */
    public function getLastOutput()
    {
        return $this->commandExecutor->getLastOutput();
    }

    public function logExecOutput($enableLog = true)
    {
        $this->commandExecutor->logExecOutput = $enableLog;
    }

    /**
     * Find a binary required by a plugin.
     * @param $binary
     * @return null|string
     */
    public function findBinary($binary)
    {
        return $this->commandExecutor->findBinary($binary);
    }

    /**
     * Replace every occurrence of the interpolation vars in the given string
     * Example: "This is build %PHPCI_BUILD%" => "This is build 182"
     * @param string $input
     * @return string
     */
    public function interpolate($input)
    {
        return $this->interpolator->interpolate($input);
    }

    /**
     * Set up a working copy of the project for building.
     */
    protected function setupBuild()
    {
        $buildId = 'project' . $this->build->getProject()->getId()
                 . '-build' . $this->build->getId();
        $this->ciDir = dirname(dirname(__FILE__) . '/../') . '/';
        $this->buildPath = $this->ciDir . 'build/' . $buildId . '/';
        $this->build->currentBuildPath = $this->buildPath;

        $this->interpolator->setupInterpolationVars(
            $this->build,
            $this->buildPath,
            PHPCI_URL
        );

        // Create a working copy of the project:
        if (!$this->build->createWorkingCopy($this, $this->buildPath)) {
            throw new \Exception('Could not create a working copy.');
        }

        // Does the project's phpci.yml request verbose mode?
        if (!isset($this->config['build_settings']['verbose']) || !$this->config['build_settings']['verbose']) {
            $this->verbose = false;
        }

        // Does the project have any paths it wants plugins to ignore?
        if (isset($this->config['build_settings']['ignore'])) {
            $this->ignore = $this->config['build_settings']['ignore'];
        }

        $this->buildLogger->logSuccess('Working copy created: ' . $this->buildPath);
        return true;
    }

    /**
     * Sets a logger instance on the object
     *
     * @param LoggerInterface $logger
     * @return null
     */
    public function setLogger(LoggerInterface $logger)
    {
        $this->buildLogger->setLogger($logger);
    }

    public function log($message, $level = LogLevel::INFO, $context = array())
    {
        $this->buildLogger->log($message, $level, $context);
    }

   /**
     * Add a success-coloured message to the log.
     * @param string
     */
    public function logSuccess($message)
    {
        $this->buildLogger->logSuccess($message);
    }

    /**
     * Add a failure-coloured message to the log.
     * @param string $message
     * @param \Exception $exception The exception that caused the error.
     */
    public function logFailure($message, \Exception $exception = null)
    {
        $this->buildLogger->logFailure($message, $exception);
    }
    /**
     * Returns a configured instance of the plugin factory.
     *
     * @param Build $build
     * @return PluginFactory
     */
    private function buildPluginFactory(Build $build)
    {
        $pluginFactory = new PluginFactory();

        $self = $this;
        $pluginFactory->registerResource(
            function () use ($self) {
                return $self;
            },
            null,
            'PHPCI\Builder'
        );

        $pluginFactory->registerResource(
            function () use ($build) {
                return $build;
            },
            null,
            'PHPCI\Model\Build'
        );

        $logger = $this->logger;
        $pluginFactory->registerResource(
            function () use ($logger) {
                return $logger;
            },
            null,
            'Psr\Log\LoggerInterface'
        );

        $pluginFactory->registerResource(
            function () use ($self) {
                $factory = new MailerFactory($self->getSystemConfig('phpci'));
                return $factory->getSwiftMailerFromConfig();
            },
            null,
            'Swift_Mailer'
        );

        return $pluginFactory;
    }
}

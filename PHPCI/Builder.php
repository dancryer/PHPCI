<?php

namespace PHPCI;
use PHPCI\Model\Build;
use b8\Store;
use Symfony\Component\Yaml\Parser as YamlParser;

class Builder
{
	public $buildPath;
	public $ignore	= array();

	protected $ciDir;
	protected $directory;
	protected $success	= true;
	protected $log		= '';
	protected $verbose	= false;
	protected $plugins	= array();
	protected $build;
	protected $logCallback;
	protected $config;

	public function __construct(Build $build, $logCallback = null)
	{
		$this->build = $build;
		$this->store = Store\Factory::getStore('Build');

		if(!is_null($logCallback) && is_callable($logCallback))
		{
			$this->logCallback = $logCallback;
		}
	}

	public function setConfigArray(array $config)
	{
		$this->config = $config;
	}

	public function getConfig($key)
	{
		return isset($this->config[$key]) ? $this->config[$key] : null;
	}

	public function execute()
	{
		$this->build->setStatus(1);
		$this->build->setStarted(new \DateTime());
		$this->store->save($this->build);
		$this->build->sendStatusPostback();

		if($this->setupBuild())
		{
			$this->executePlugins('setup');
			$this->executePlugins('test');

			$this->log('');

			$this->executePlugins('complete');

			if($this->success)
			{
				$this->executePlugins('success');
				$this->logSuccess('BUILD SUCCESSFUL!');
				$this->build->setStatus(2);
			}
			else
			{
				$this->executePlugins('failure');
				$this->logFailure('BUILD FAILED!');
				$this->build->setStatus(3);
			}

			$this->log('');
		}
		else
		{
			$this->build->setStatus(3);
		}

		$this->removeBuild();

		$this->build->sendStatusPostback();
		$this->build->setFinished(new \DateTime());
		$this->build->setLog($this->log);
		$this->build->setPlugins(json_encode($this->plugins));
		$this->store->save($this->build);
	}

	public function executeCommand($command)
	{
		$this->log('Executing: ' . $command, '	');

		$output	= '';
		$status	= 0;
		exec($command, $output, $status);

		if(!empty($output) && ($this->verbose || $status != 0))
		{
			$this->log($output, '		');
		}

		return ($status == 0) ? true : false;
	}

	public function log($message, $prefix = '')
	{

		if(is_array($message))
		{
			
			foreach ($message as $item)
			{
				if(is_callable($this->logCallback))
				{
					$this->logCallback($prefix . $item);
				}
				
				$this->log .= $prefix . $item . PHP_EOL;
			}
			
		}
		else
		{
			$message = $prefix . $message;

			$this->log .= $message . PHP_EOL;

			if(isset($this->logCallback) && is_callable($this->logCallback))
			{
				$cb = $this->logCallback;
				$cb($message);
			}
		}

		$this->build->setLog($this->log);
		$this->build->setPlugins(json_encode($this->plugins));
		$this->store->save($this->build);
	}

	public function logSuccess($message)
	{
		$this->log("\033[0;32m" . $message . "\033[0m");
	}

	public function logFailure($message)
	{
		$this->log("\033[0;31m" . $message . "\033[0m");
	}

	protected function setupBuild()
	{
		$commitId			= $this->build->getCommitId();
		$buildId			= 'project' . $this->build->getProject()->getId() . '-build' . $this->build->getId();
		$this->ciDir		= realpath(dirname(__FILE__) . '/../') . '/';
		$this->buildPath	= $this->ciDir . 'build/' . $buildId . '/';

		// Create a working copy of the project:
		if(!$this->build->createWorkingCopy($this, $this->buildPath)) {
			return false;
		}

		// Does the project's phpci.yml request verbose mode?
		if(!isset($this->config['build_settings']['verbose']) || !$this->config['build_settings']['verbose']) {
			$this->verbose = false;
		}
		else {
			$this->verbose = true;
		}

		// Does the project have any paths it wants plugins to ignore?
		if(isset($this->config['build_settings']['ignore'])) {
			$this->ignore = $this->config['build_settings']['ignore'];
		}

		$this->logSuccess('Working copy created: ' . $this->buildPath);
		return true;
	}

	protected function removeBuild()
	{
		$this->log('Removing build.');
		shell_exec('rm -Rf ' . $this->buildPath);
	}

	protected function executePlugins($stage)
	{
		// Ignore any stages for which we don't have plugins set:
		if(!array_key_exists($stage, $this->config) || !is_array($this->config[$stage]))
		{
			return;
		}

		foreach($this->config[$stage] as $plugin => $options)
		{
			$this->log('');
			$this->log('RUNNING PLUGIN: ' . $plugin);

			// Is this plugin allowed to fail?
			if($stage == 'test' && !isset($options['allow_failures']))
			{
				$options['allow_failures'] = false;
			}

			$class = str_replace('_', ' ', $plugin);
			$class = ucwords($class);
			$class = 'PHPCI\\Plugin\\' . str_replace(' ', '', $class);

			if(!class_exists($class))
			{
				$this->logFailure('Plugin does not exist: ' . $plugin);

				if($stage == 'test')
				{
					$this->plugins[$plugin] = false;

					if(!$options['allow_failures'])
					{
						$this->success = false;
					}
				}

				continue;
			}

			try
			{
				$obj = new $class($this, $options);

				if(!$obj->execute())
				{
					if($stage == 'test')
					{
						$this->plugins[$plugin] = false;

						if(!$options['allow_failures'])
						{
							$this->success = false;
						}
					}

					$this->logFailure('PLUGIN STATUS: FAILED');
					continue;
				}
			}
			catch(\Exception $ex)
			{
				$this->logFailure('EXCEPTION: ' . $ex->getMessage());

				if($stage == 'test')
				{
					$this->plugins[$plugin] = false;

					if(!$options['allow_failures'])
					{
						$this->success = false;
					}
				}

				$this->logFailure('PLUGIN STATUS: FAILED');
				continue;
			}

			if($stage == 'test')
			{
				$this->plugins[$plugin] = true;
			}

			$this->logSuccess('PLUGIN STATUS: SUCCESS!');
		}
	}
}
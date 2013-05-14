<?php

namespace PHPCI;
use PHPCI\Model\Build;
use b8\Store;

class Builder
{
	public $buildPath;
	public $ignore	= array();

	protected $ciDir;
	protected $directory;
	protected $success	= true;
	protected $log		= '';
	protected $verbose	= false;
	protected $build;

	public function __construct(Build $build)
	{
		$this->build = $build;
		$this->store = Store\Factory::getStore('Build');
	}

	public function execute()
	{
		$this->build->setStatus(1);
		$this->build->setStarted(new \DateTime());
		$this->build = $this->store->save($this->build);

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

		$this->build->setFinished(new \DateTime());
		$this->build->setLog($this->log);
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

	protected function log($message, $prefix = '')
	{
		if(is_array($message))
		{
			$message = array_map(function($item) use ($prefix)
			{
				return $prefix . $item;
			}, $message);

			$message = implode(PHP_EOL, $message);

			$this->log .= $message;
			print $message . PHP_EOL;
		}
		else
		{
			$message = $prefix . $message . PHP_EOL;

			$this->log .= $message;
			print $message;
		}

		$this->build->setLog($this->log);
		$this->build = $this->store->save($this->build);
	}

	protected function logSuccess($message)
	{
		$this->log("\033[0;32m" . $message . "\033[0m");
	}

	protected function logFailure($message)
	{
		$this->log("\033[0;31m" . $message . "\033[0m");
	}

	protected function setupBuild()
	{
		$commitId			= $this->build->getCommitId();
		$url				= $this->build->getProject()->getGitUrl();
		$key				= $this->build->getProject()->getGitKey();
		$type				= $this->build->getProject()->getType();

		$buildId			= 'project' . $this->build->getProject()->getId() . '-build' . $this->build->getId();

		$this->ciDir		= realpath(dirname(__FILE__) . '/../') . '/';
		$this->buildPath	= $this->ciDir . 'build/' . $buildId . '/';


		switch ($type) {
			case 'local':
				$reference = $this->build->getProject()->getReference();
				$this->buildPath	= $this->ciDir . 'build/' . $buildId;
				if(is_link($this->buildPath) && is_file($this->buildPath)) {
				} else {
					symlink($reference, $this->buildPath);
				}
				$this->buildPath	.= '/';
				break;
			default:
				mkdir($this->buildPath, 0777, true);
				if(!empty($key))
				{
					// Do an SSH clone:
					$keyFile			= $this->ciDir . 'build/' . $buildId . '.key';
					file_put_contents($keyFile, $key);
					chmod($keyFile, 0600);
					$this->executeCommand('ssh-agent ssh-add '.$keyFile.' && git clone -b ' .$this->build->getBranch() . ' ' .$url.' '.$this->buildPath.' && ssh-agent -k');
					unlink($keyFile);
				}
				else
				{
					// Do an HTTP clone:
					$this->executeCommand('git clone -b ' .$this->build->getBranch() . ' ' .$url.' '.$this->buildPath);
				}
				break;
		}

		if(!is_file($this->buildPath . 'phpci.yml'))
		{
			$this->logFailure('Project does not contain a phpci.yml file.');
			return false;
		}

		$this->config		= yaml_parse_file($this->buildPath . 'phpci.yml');

		if(!isset($this->config['verbose']) || !$this->config['verbose'])
		{
			$this->verbose = false;
		}
		else
		{
			$this->verbose = true;
		}

		if(isset($this->config['ignore']))
		{
			$this->ignore = $this->config['ignore'];
		}

		$this->log('Set up build: ' . $this->buildPath);

		return true;
	}

	protected function removeBuild()
	{
		$this->log('Removing build.');
		shell_exec('rm -Rf ' . $this->buildPath);
	}

	protected function executePlugins($stage)
	{
		if ( !array_key_exists($stage, $this->config) || !is_array($this->config[$stage]) )
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

				if($stage == 'test' && !$options['allow_failures'])
				{
					$this->success = false;
				}

				continue;
			}

			try
			{
				$plugin = new $class($this, $options);

				if(!$plugin->execute())
				{
					if($stage == 'test' && !$options['allow_failures'])
					{
						$this->success = false;
					}

					$this->logFailure('PLUGIN STATUS: FAILED');
					continue;
				}
			}
			catch(\Exception $ex)
			{
				$this->logFailure('EXCEPTION: ' . $ex->getMessage());

				if($stage == 'test' && !$options['allow_failures'])
				{
					$this->success = false;
					continue;
				}
			}

			$this->logSuccess('PLUGIN STATUS: SUCCESS!');
		}
	}
}
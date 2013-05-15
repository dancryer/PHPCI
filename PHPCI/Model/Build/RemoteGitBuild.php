<?php

/**
 * Build model for table: build
 */

namespace PHPCI\Model\Build;
use PHPCI\Model\Build;
use PHPCI\Builder;
use Symfony\Component\Yaml\Parser as YamlParser;

/**
 * Build Model
 * @uses PHPCI\Model\Build
 */
abstract class RemoteGitBuild extends Build
{
	abstract protected function getCloneUrl();

	public function createWorkingCopy(Builder $builder, $buildPath)
	{
		$yamlParser	= new YamlParser();
		$success	= true;
		$key		= trim($this->getProject()->getGitKey());

		if(!empty($key)) {
			$success = $this->cloneBySsh($builder, $buildPath);
		}
		else {
			$success = $this->cloneByHttp($builder, $buildPath);
		}

		if(!$success) {
			$builder->logFailure('Failed to clone remote git repository.');
			return false;
		}

		if(!is_file($buildPath . 'phpci.yml')) {
			$builder->logFailure('Project does not contain a phpci.yml file.');
			return false;
		}

		$yamlFile = file_get_contents($buildPath . 'phpci.yml');
		$builder->setConfigArray($yamlParser->parse($yamlFile));

		return true;
	}

	protected function cloneByHttp(Builder $builder, $to)
	{
		return $builder->executeCommand('git clone -b ' .$this->getBranch() . ' ' .$this->getCloneUrl().' '.$to);
	}

	protected function cloneBySsh(Builder $builder, $to)
	{
		// Copy the project's keyfile to disk:
		$keyFile = realpath($to) . '.key';
		file_put_contents($keyFile, $this->getProject()->getGitKey());
		chmod($keyFile, 0600);

		// Use the key file to do an SSH clone:
		$success = $builder->executeCommand('ssh-agent ssh-add '.$keyFile.' && git clone -b ' .$build->getBranch() . ' ' .$this->getCloneUrl().' '.$to.' && ssh-agent -k');
		
		// Remove the key file:
		unlink($keyFile);

		return $success;
	}
}

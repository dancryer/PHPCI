<?php

namespace Kiboko\Bundle\ContinuousIntegrationBundle\ExecutionContext;

use Kiboko\Bundle\ContinuousIntegrationBundle\ExecutionContext\Command\CommandInterface;
use Symfony\Component\Process\Exception\InvalidArgumentException;
use Symfony\Component\Process\Process;

class ProcessBuilder
{
    private $command;
    private $cwd;
    private $env = [];
    private $input;
    private $timeout = 60;
    private $options;
    private $inheritEnv = true;
    private $outputDisabled = false;

    /**
     * Constructor.
     *
     * @param CommandInterface $command
     */
    public function __construct(CommandInterface $command)
    {
        $this->command = $command;
    }

    /**
     * Creates a process builder instance.
     *
     * @param CommandInterface $command
     *
     * @return static
     */
    public static function create(CommandInterface $command)
    {
        return new static($command);
    }

    /**
     * @param CommandInterface $command
     *
     * @return $this
     */
    public function setCommand(CommandInterface $command)
    {
        $this->command = $command;

        return $this;
    }

    /**
     * Sets the working directory.
     *
     * @param null|string $cwd The working directory
     *
     * @return $this
     */
    public function setWorkingDirectory($cwd)
    {
        $this->cwd = $cwd;

        return $this;
    }

    /**
     * Sets whether environment variables will be inherited or not.
     *
     * @param bool $inheritEnv
     *
     * @return $this
     *
     * @deprecated since version 3.3, to be removed in 4.0.
     */
    public function inheritEnvironmentVariables($inheritEnv = true)
    {
        $this->inheritEnv = $inheritEnv;

        return $this;
    }

    /**
     * Sets an environment variable.
     *
     * Setting a variable overrides its previous value. Use `null` to unset a
     * defined environment variable.
     *
     * @param string      $name  The variable name
     * @param null|string $value The variable value
     *
     * @return $this
     */
    public function setEnv($name, $value)
    {
        $this->env[$name] = $value;

        return $this;
    }

    /**
     * Adds a set of environment variables.
     *
     * Already existing environment variables with the same name will be
     * overridden by the new values passed to this method. Pass `null` to unset
     * a variable.
     *
     * @param array $variables The variables
     *
     * @return $this
     */
    public function addEnvironmentVariables(array $variables)
    {
        $this->env = array_replace($this->env, $variables);

        return $this;
    }

    /**
     * Sets the input of the process.
     *
     * @param resource|scalar|\Traversable|null $input The input content
     *
     * @return $this
     *
     * @throws InvalidArgumentException In case the argument is invalid
     */
    public function setInput($input)
    {
        $this->input = ProcessUtils::validateInput(__METHOD__, $input);

        return $this;
    }

    /**
     * Sets the process timeout.
     *
     * To disable the timeout, set this value to null.
     *
     * @param float|null $timeout
     *
     * @return $this
     *
     * @throws InvalidArgumentException
     */
    public function setTimeout($timeout)
    {
        if (null === $timeout) {
            $this->timeout = null;

            return $this;
        }

        $timeout = (float) $timeout;

        if ($timeout < 0) {
            throw new InvalidArgumentException('The timeout value must be a valid positive integer or float number.');
        }

        $this->timeout = $timeout;

        return $this;
    }

    /**
     * Adds a proc_open option.
     *
     * @param string $name  The option name
     * @param string $value The option value
     *
     * @return $this
     *
     * @deprecated since version 3.3, to be removed in 4.0.
     */
    public function setOption($name, $value)
    {
        $this->options[$name] = $value;

        return $this;
    }

    /**
     * Disables fetching output and error output from the underlying process.
     *
     * @return $this
     */
    public function disableOutput()
    {
        $this->outputDisabled = true;

        return $this;
    }

    /**
     * Enables fetching output and error output from the underlying process.
     *
     * @return $this
     */
    public function enableOutput()
    {
        $this->outputDisabled = false;

        return $this;
    }

    /**
     * Creates a Process instance and returns it.
     *
     * @return Process
     */
    public function getProcess()
    {
        $process = new Process($this->command, $this->cwd, $this->env, $this->input, $this->timeout, $this->options);

        if ($this->inheritEnv) {
            $process->inheritEnvironmentVariables();
        }
        if ($this->outputDisabled) {
            $process->disableOutput();
        }

        return $process;
    }
}

<?php

namespace PHPCI\Plugin;

class Atoum implements \PHPCI\Plugin
{
    private $args;
    private $config;
    private $directory;
    private $executable;

    public function __construct(\PHPCI\Builder $phpci, array $options = array())
    {
        $this->phpci = $phpci;

        if (isset($options['executable'])) {
            $this->executable = $options['executable'];
        }
        else {
            $this->executable = './vendor/bin/atoum';
        }

        if (isset($options['args'])) {
            $this->args = $options['args'];
        }

        if (isset($options['config'])) {
            $this->config = $options['config'];
        }

        if (isset($options['directory'])) {
            $this->directory = $options['directory'];
        }
    }

    public function execute()
    {
        $cmd = $this->phpci->buildPath . DIRECTORY_SEPARATOR . $this->executable;

        if ($this->args !== null) {
            $cmd .= " {$this->args}";
        }
        if ($this->config !== null) {
            $cmd .= " -c '{$this->config}'";
        }
        if ($this->directory !== null) {
            $cmd .= " -d '{$this->directory}'";
        }
        return $this->phpci->executeCommand($cmd);
    }
}

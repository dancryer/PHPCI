<?php

namespace PHPCI\Plugin;

class Atoum implements \PHPCI\Plugin
{
    private $args;
    private $config;
    private $directory;

    public function __construct(\PHPCI\Builder $phpci, array $options = array())
    {
        $this->phpci = $phpci;

        if (isset($options['executable'])) {
            $this->executable = $this->phpci->buildPath . DIRECTORY_SEPARATOR.$options['executable'];
        } else {
            $this->executable = PHPCI_BIN_DIR.'atoum';
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
        $cmd = $this->executable;

        if ($this->args !== null) {
            $cmd .= " {$this->args}";
        }
        if ($this->config !== null) {
            $cmd .= " -c '{$this->config}'";
        }
        if ($this->directory !== null) {
            $dirPath = $this->phpci->buildPath . DIRECTORY_SEPARATOR . $this->directory;
            $cmd .= " -d '{$dirPath}'";
        }
        chdir($this->phpci->buildPath);
        $output = '';
        $status = true;
        exec($cmd, $output);

        if (count(preg_grep("/Success \(/", $output)) == 0 ) {
            $status = false;
            $this->phpci->log($output, '       ');
        }
        if (count($output) == 0) {
            $status = false;
            $this->phpci->log("No test have been performed!", '       ');
        }
        
        return $status;
    }
}

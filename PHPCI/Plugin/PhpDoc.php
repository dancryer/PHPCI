<?php
/**
* PHPCI - Continuous Integration for PHP
*
* @copyright    Copyright 2013, Block 8 Limited.
* @license      https://github.com/Block8/PHPCI/blob/master/LICENSE.md
* @link         http://www.phptesting.org/
*/

namespace PHPCI\Plugin;

use PHPCI\Builder;
use PHPCI\Model\Build;

/**
* PHP Doc - Generate documentation.
* @author       Prikhodko Sergey <indigo.dp@gmail.com>
* @package      PHPCI
* @subpackage   Plugins
*/
class PhpDoc implements \PHPCI\Plugin
{
    /**
     * Builder object
     * @var Builder
     */
    protected $phpci;
    
    /**
     * Phpdoc template
     * @var string 
     */
    protected $template;
    
    /**
     * Build path
     * @var string 
     */
    protected $path;
    
    /**
     * Doc path
     * @var string
     */
    protected $outputPath;

    /**
     * @var array - paths to ignore
     */
    protected $ignore;

    public function __construct(Builder $phpci, Build $build, array $options = array())
    {
        $this->phpci = $phpci;
        $this->path = $phpci->buildPath;
        $this->ignore = $phpci->ignore;

        if (!empty($options['ignore'])) {
            $this->ignore = $options['ignore'];
        }
        
        if (!empty($options['template'])) {
            $this->template = $options['template'];
        }
        
        if (!empty($options['outputPath'])) {
            $this->outputPath = $this->path . $options['outputPath'];
        }        
    }

    /**
    * Runs phpdoc in a specified directory.
    */
    public function execute()
    {
        $ignore = '';
        if (count($this->ignore)) {
            $ignore = implode('', $ignore);
        }

        $phpdoc = $this->phpci->findBinary('phpdoc.php');

        if (!$phpdoc) {
            $this->phpci->logFailure('Could not find phpdoc.');
            return false;
        }

        $success = $this->phpci->executeCommand($phpdoc . ' -d %s -i %s --template=%s -t %s', 
                $this->path, $ignore, $this->template, $this->outputPath);

        print $this->phpci->getLastOutput();

        return $success;
    }
}

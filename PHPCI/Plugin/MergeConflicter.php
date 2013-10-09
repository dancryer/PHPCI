<?php
/**
* PHPCI - Continuous Integration for PHP
*
* @copyright    Copyright 2013, Block 8 Limited.
* @license      https://github.com/Block8/PHPCI/blob/master/LICENSE.md
* @link         http://www.phptesting.org/
*/

namespace PHPCI\Plugin;

/**
* Merge Conflicts Detector Plugin - Find errors after merge.
* @author       ErgoZ <dev@ergoz.ru>
* @site         http://habrahabr.ru/users/ErgoZru
* @package      PHPCI
* @subpackage   Plugins
*/
class MergeConflicter implements \PHPCI\Plugin
{
    protected $args;
    protected $phpci;
    
    /**
     * @var string $directory The directory path to be executed to search
     */
    protected $ignore_formats;
    /**
     * @var string $allow_failures Is always return build success
     */
    protected $allow_failures;

    public function __construct(\PHPCI\Builder $phpci, array $options = array())
    {
        $this->phpci          = $phpci;
        $this->ignore         = (isset($options['ignore'])) ? (array)$options['ignore'] : $this->phpci->ignore;
        $this->ignore_formats = (isset($options['ignore_file_formats'])) ? (array)$options['ignore_file_formats'] : '';
        $this->allow_failures = (isset($options['allow_failures'])) ? (boolean)$options['allow_failures'] : false;
    }

    /**
    * Runs the grep command.
    */
    public function execute()
    {
        $ignore_dirs = '';
        if (count($this->ignore)) {
            $ignore_dirs = '--exclude-dir='.implode(' --exclude-dir=', $this->ignore);
        }
        
        $ignore_file_formats = '';
        if( (is_array($this->ignore_formats)) && count($this->ignore_formats) ) {
            foreach($this->ignore_formats as $ignore_file_format_element) {
                $ignore_file_formats .= ' --exclude="*\.'.$ignore_file_format_element.'"';
            }
        }
        
        $cmd = 'grep -R -E "^<<<<<<<$|^>>>>>>>$|^=======$" %s %s --line-number "%s"; test $? -ne 0;';
        return $this->phpci->executeCommand($cmd, $ignore_file_formats, $ignore_dirs, $this->phpci->buildPath.$this->path);
    }
}

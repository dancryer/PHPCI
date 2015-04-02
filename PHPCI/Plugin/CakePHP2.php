<?php
/**
 * PHPCI - Continuous Integration for PHP
 *
 * @copyright    Copyright 2014, Block 8 Limited.
 * @license      https://github.com/Block8/PHPCI/blob/master/LICENSE.md
 * @link         https://www.phptesting.org/
 */

namespace PHPCI\Plugin;

use PHPCI;
use PHPCI\Builder;
use PHPCI\Helper\Lang;
use PHPCI\Model\Build;
use PHPCI\Plugin\Util\TapParser;

/**
 * CakePHP2 Plugin - Enables testing from the console for a CakePHP2 application
 *
 * @author       tranfuga25s <tranfuga25s@gmail.com>
 * @package      PHPCI
 * @subpackage   Plugins
 */
class CakePHP2 implements PHPCI\Plugin, PHPCI\ZeroConfigPlugin
{
    /**
     * @var string
     */
    protected $args = '';

    /**
     * @var Build
     */
    protected $build;

    /**
     * @var Builder
     */
    protected $phpci;

    /**
     * @var string The path to the app dir inside the build dir
     */
    protected $appPath;
    
    /**
     * @var string the test from the app/test/case directory to run
     */
    protected $runTest;
    
    /**
     *
     * @var boolean Allows to fail the execution but gives error when fails 
     */
    protected $allowFailure = true;
    
    /**
     *
     * @var array 
     */
    protected $options = null;

    /**
     * Set up the plugin, configure options, etc.
     *
     * @param Builder $phpci
     * @param Build $build
     * @param array $options
     */
    public function __construct(Builder $phpci, Build $build, array $options = array())
    {
        $this->phpci = $phpci;
        $this->build = $build;
        $this->options = $options;

        // Options avaliables
        // app = path to the app directory
        if (isset($options['app'])) {
            $this->appPath = $options['app'];
        }
        
        // args = extra arguments to pass to the testing console
        if (isset($options['args'])) {
            $this->args = (string) $options['args'];
        }
        
        // test = test to excecute
        if (isset($options['test'])) {
            $this->runTest = (string) $options['test'];
        }
        
        // test = test to excecute
        if (isset($options['allowFailure'])) {
            $this->allowFailure = (bool) $options['allowFailure'];
        }
        
        if (isset($options['debug'])) {
            $this->debug = (bool) $options['debug'];
        }        

    }

    /**
     * {@inheritDoc}
     */
    public function execute()
    {       
        $console_command = $this->phpci->buildPath;
        $console_command .= $this->appPath;
        $console_command .= '/Console/cake';
        
        if (!file_exists($console_command)) {
            $this->phpci->logFailure(Lang::get('could_not_find', $console_command));
            return false;
        }
        
        if (!is_executable($console_command)) {
            $this->phpci->logFailure("The file ".$console_command." is not executable!");
            return false;
        }
        
        $console_command .= ' test app ';
        
        if (!is_null($this->runTest)) {
            $path_to_test_file = $this->phpci->buildPath . $this->appPath . DIRECTORY_SEPARATOR . 'Test' . DIRECTORY_SEPARATOR . 'Case' . DIRECTORY_SEPARATOR . $this->runTest . 'Test.php';
            if (!file_exists($path_to_test_file)) {
                $this->phpci->logFailure(Lang::get('could_not_find', $path_to_test_file));
                return false;
            }
            $console_command .= $this->runTest;
        } else {
            $console_command .= 'all';
        }
        
        $tap_file_location = $this->phpci->buildPath . $this->appPath . DIRECTORY_SEPARATOR . 'tmp' . DIRECTORY_SEPARATOR . 'log' . DIRECTORY_SEPARATOR . 'report.tap.log';
        
        $console_command .= " --tap --log-tap ".$tap_file_location." --stderr";
        
        if ($this->debug) {
            $console_command .= " --debug";
        }
        
        $success = $this->phpci->executeCommand($console_command);
        
        /*if (!$success && !$this->allowFailure) {
            $this->phpci->logFailure("There was an error on the execution of the command: ".$console_command);
            return false;
        } else {
            $this->phpci->logSuccess("The test where finished correcty");
        }*/

        if (file_exists($tap_file_location)) {
            $tapString = file_get_contents($tap_file_location);

            try {
                $tapParser = new TapParser($tapString);
                $output = $tapParser->parse();
            } catch (\Exception $ex) {
                $this->phpci->logFailure($tapString);
                throw $ex;
            }

            $failures = $tapParser->getTotalFailures();

            $this->build->storeMeta('cakephp-errors', $failures);
            $this->build->storeMeta('cakephp-data', $output);
        }
        return $success;
    }

    /**
     * {@inheritDoc}
     */
    public static function canExecute($stage, Builder $builder, Build $build)
    {
        return ($stage == 'test');
    }
    
}

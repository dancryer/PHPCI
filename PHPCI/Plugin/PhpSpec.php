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
use PHPCI\Model\Build;

/**
* PHP Spec Plugin - Allows PHP Spec testing.
* @author       Dan Cryer <dan@block8.co.uk>
* @package      PHPCI
* @subpackage   Plugins
*/
class PhpSpec implements PHPCI\Plugin
{
    /**
     * @var \PHPCI\Builder
     */
    protected $phpci;

    /**
     * @var \PHPCI\Model\Build
     */
    protected $build;

    /**
     * @var array
     */
    protected $options;

    /**
     * Set up the plugin, configure options, etc.
     * @param Builder $phpci
     * @param Build $build
     * @param array $options
     */
    public function __construct(Builder $phpci, Build $build, array $options = array())
    {
        $this->phpci = $phpci;
        $this->build = $build;
        $this->options = $options;
    }

    /**
    * Runs PHP Spec tests.
    */
    public function execute()
    {
        $curdir = getcwd();
        chdir($this->phpci->buildPath);

        $phpspec = $this->phpci->findBinary(array('phpspec', 'phpspec.php'));

        if (!$phpspec) {
            $this->phpci->logFailure(PHPCI\Helper\Lang::get('could_not_find', 'phpspec'));
            return false;
        }

        $success = $this->phpci->executeCommand($phpspec . ' --format=junit --no-code-generation run');
        $output = $this->phpci->getLastOutput();

        chdir($curdir);

        /*
         * process xml output
         *
         * <testsuites time=FLOAT tests=INT failures=INT errors=INT>
         *   <testsuite name=STRING time=FLOAT tests=INT failures=INT errors=INT skipped=INT>
         *     <testcase name=STRING time=FLOAT classname=STRING status=STRING/>
         *   </testsuite>
         * </testsuites
         */

        $xml = new \SimpleXMLElement($output);
        $attr = $xml->attributes();
        $data = array(
            'time' => (float)$attr['time'],
            'tests' => (int)$attr['tests'],
            'failures' => (int)$attr['failures'],
            'errors' => (int)$attr['errors'],

            // now all the tests
            'suites' => array()
        );

        /**
         * @var \SimpleXMLElement $group
         */
        foreach ($xml->xpath('testsuite') as $group) {
            $attr = $group->attributes();
            $suite = array(
                'name' => (String)$attr['name'],
                'time' => (float)$attr['time'],
                'tests' => (int)$attr['tests'],
                'failures' => (int)$attr['failures'],
                'errors' => (int)$attr['errors'],
                'skipped' => (int)$attr['skipped'],

                // now the cases
                'cases' => array()
            );

            /**
             * @var \SimpleXMLElement $child
             */
            foreach ($group->xpath('testcase') as $child) {
                $attr = $child->attributes();
                $case = array(
                    'name' => (String)$attr['name'],
                    'classname' => (String)$attr['classname'],
                    'time' => (float)$attr['time'],
                    'status' => (String)$attr['status'],
                );

                if ($case['status']=='failed') {
                    $error = array();
                    /*
                     * ok, sad, we had an error
                     *
                     * there should be one - foreach makes this easier
                     */
                    foreach ($child->xpath('failure') as $failure) {
                        $attr = $failure->attributes();
                        $error['type'] = (String)$attr['type'];
                        $error['message'] = (String)$attr['message'];
                    }

                    foreach ($child->xpath('system-err') as $system_err) {
                        $error['raw'] = (String)$system_err;
                    }

                    $case['error'] = $error;
                }

                $suite['cases'][] = $case;
            }

            $data['suites'][] = $suite;
        }

        $this->build->storeMeta('phpspec', $data);


        return $success;
    }
}

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
 * Pdepend Plugin - Allows Pdepend report
 * @author       Johan van der Heide <info@japaveh.nl>
 * @package      PHPCI
 * @subpackage   Plugins
 */
class Pdepend implements \PHPCI\Plugin
{
    protected $args;
    /**
     * @var \PHPCI\Builder
     */
    protected $phpci;
    /**
     * @var string Directory which needs to be scanned
     */
    protected $directory;
    /**
     * @var string File where the summary.xml is stored
     */
    protected $summary;
    /**
     * @var string File where the chart.svg is stored
     */
    protected $chart;
    /**
     * @var string File where the pyramid.svg is stored
     */
    protected $pyramid;
    /**
     * @var string Location on the server where the files are stored. Preferably in the webroot for inclusion
     *             in the readme.md of the repository
     */
    protected $location;


    public function __construct(\PHPCI\Builder $phpci, array $options = array())
    {
        $this->phpci = $phpci;

        $this->directory = isset($options['directory']) ? $options['directory'] : $phpci->buildPath;

        $this->summary  = $phpci->getBuildProjectTitle() . '-summary.xml';
        $this->pyramid  = $phpci->getBuildProjectTitle() . '-pyramid.svg';
        $this->chart    = $phpci->getBuildProjectTitle() . '-chart.svg';
        $this->location = $this->phpci->buildPath . '..' . DIRECTORY_SEPARATOR . 'pdepend';
    }

    /**
     * Runs Pdepend with the given criteria as arguments
     */
    public function execute()
    {
        if (!is_writable($this->location)) {
            throw new \Exception(sprintf('The location %s is not writable.', $this->location));
        }

        $cmd = PHPCI_BIN_DIR . 'pdepend --summary-xml="%s" --jdepend-chart="%s" --overview-pyramid="%s" "%s"';

        $this->removeBuildArtifacts();
        $success = $this->phpci->executeCommand(
            $cmd,
            $this->location . DIRECTORY_SEPARATOR . $this->summary,
            $this->location . DIRECTORY_SEPARATOR . $this->chart,
            $this->location . DIRECTORY_SEPARATOR . $this->pyramid,
            $this->directory
        );

        $config = $this->phpci->getSystemConfig('phpci');

        if ($success) {
            $this->phpci->logSuccess(
                sprintf(
                    "Pdepend successful. You can use %s\n, ![Chart](%s \"Pdepend Chart\")\n
                    and ![Pyramid](%s \"Pdepend Pyramid\")\n
                    for inclusion in the readme.md file",
                    $config['url'] . '/build/pdepend/' . $this->summary,
                    $config['url'] . '/build/pdepend/' . $this->chart,
                    $config['url'] . '/build/pdepend/' . $this->pyramid
                )
            );
        } else {
            $this->phpci->logFailure(sprintf("The function '%s' failed"));
        }


        return $success;
    }

    /**
     * Remove files created from previous builds
     */
    protected function removeBuildArtifacts()
    {
        //Remove the created files first
        foreach (array($this->summary, $this->chart, $this->pyramid) as $file) {
            if (file_exists($this->location . DIRECTORY_SEPARATOR . $file)) {
                unlink($this->location . DIRECTORY_SEPARATOR . $file);
            }
        }
    }
}

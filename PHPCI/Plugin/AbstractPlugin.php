<?php

namespace PHPCI\Plugin;

use PHPCI\Plugin;

/**
 * Base for all of the standard PHPCI plugins. Provides the isAllowedInStage() functionality.
 * @package PHPCI\Plugin
 */
abstract class AbstractPlugin implements Plugin
{
    /**
     * Define the stages that this plugin is allowed to run in.
     * @see AbstractPlugin::canExecute()
     * @var array
     */
    protected $allowedStages = array('setup', 'test', 'complete', 'success', 'failure', 'fixed', 'broken');

    /**
     * Verify whether or not this plugin is allowed to execute in a given stage.
     * @param string $stage
     * @return bool
     */
    public function isAllowedInStage($stage)
    {
        return in_array($stage, $this->allowedStages);
    }

    /**
     * Execute the plugin and return its success or failure.
     * @return bool
     */
    abstract public function execute();
}

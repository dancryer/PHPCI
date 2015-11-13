<?php

namespace PHPCI\Plugin;

use PHPCI\Builder;
use PHPCI\Model\Build;
use PHPCI\Plugin;

/**
 * Base for all of the standard PHPCI plugins. Provides the isAllowedInStage() functionality.
 * @package PHPCI\Plugin
 */
abstract class AbstractPlugin implements Plugin
{
    /**
     * Define the stages that this plugin is allowed to run in.
     * @see AbstractPlugin::isAllowedInStage()
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
     * Check whether or not this plugin can execute in zero config mode.
     * Many plugins will check if their required config files can be found here.
     * @param string $stage
     * @param Builder $builder
     * @param Build $build
     * @return bool
     */
    public static function canRunZeroConfig($stage, Builder $builder, Build $build)
    {
        return false;
    }

    /**
     * Execute the plugin and return its success or failure.
     * @return bool
     */
    abstract public function execute();
}

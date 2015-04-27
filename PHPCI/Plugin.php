<?php
/**
 * PHPCI - Continuous Integration for PHP
 *
 * @copyright    Copyright 2014, Block 8 Limited.
 * @license      https://github.com/Block8/PHPCI/blob/master/LICENSE.md
 * @link         https://www.phptesting.org/
 */

namespace PHPCI;

/**
* PHPCI Plugin Interface - Used by all build plugins.
* @author   Dan Cryer <dan@block8.co.uk>
*/
interface Plugin
{
    /**
     * Sets the options for the current build stage.
     *
     * @param array $options
     *
     * @throws \Exception If the options are invalid.
     */
    public function setOptions(array $options);

    /**
     * Sets the settings for all stages.
     *
     * @param array $settings
     *
     * @throws \Exception If the settings are invalid.
     */
    public function setCommonSettings(array $settings);

    /**
     * Execute the plugin.
     *
     * @return bool true if all went nice, false if the plugin ended normally but failed.
     * @throws \Exception If something prevented the plugin to end normally.
     */
    public function execute();
}

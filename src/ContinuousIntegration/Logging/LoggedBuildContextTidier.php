<?php
/**
 * Kiboko CI - Continuous Integration for PHP
 *
 * @copyright    Copyright 2014, Block 8 Limited.
 * @license      https://github.com/kiboko-labs/ci/blob/master/LICENSE.md
 * @link         https://www.phptesting.org/
 */

namespace Kiboko\Component\ContinuousIntegration\Logging;

use Kiboko\Component\ContinuousIntegration\Model\Build;

/**
 * Class LoggedBuildContextTidier cleans up build log entries.
 * @package PHPCI\Logging
 */
class LoggedBuildContextTidier
{
    /**
     * @return array
     */
    public function __invoke()
    {
        return $this->tidyLoggedBuildContext(func_get_arg(0));
    }

    /**
     * Removes the build object from the logged record and adds the ID as
     * this is more useful to display.
     *
     * @param array $logRecord
     * @return array
     */
    protected function tidyLoggedBuildContext(array $logRecord)
    {
        if (isset($logRecord['context']['build'])) {
            $build = $logRecord['context']['build'];
            if ($build instanceof Build) {
                $logRecord['context']['buildID'] = $build->getId();
                unset($logRecord['context']['build']);
            }
        }
        return $logRecord;
    }
}

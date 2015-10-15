<?php

/**
 * BuildError model for table: build_error
 */

namespace PHPCI\Model;

use PHPCI\Model\Base\BuildErrorBase;

/**
 * BuildError Model
 * @uses PHPCI\Model\Base\BuildErrorBase
 */
class BuildError extends BuildErrorBase
{
    const SEVERITY_CRITICAL = 0;
    const SEVERITY_HIGH = 1;
    const SEVERITY_NORMAL = 2;
    const SEVERITY_LOW = 3;

    /**
     * Get the language string key for this error's severity level.
     * @return string
     */
    public function getSeverityString()
    {
        switch ($this->getSeverity()) {
            case self::SEVERITY_CRITICAL:
                return 'critical';

            case self::SEVERITY_HIGH:
                return 'high';

            case self::SEVERITY_NORMAL:
                return 'normal';

            case self::SEVERITY_LOW:
                return 'low';
        }
    }

    /**
     * Get the class to apply to HTML elements representing this error.
     * @return string
     */
    public function getSeverityClass()
    {
        switch ($this->getSeverity()) {
            case self::SEVERITY_CRITICAL:
                return 'danger';

            case self::SEVERITY_HIGH:
                return 'warning';

            case self::SEVERITY_NORMAL:
                return 'info';

            case self::SEVERITY_LOW:
                return 'default';
        }
    }
}

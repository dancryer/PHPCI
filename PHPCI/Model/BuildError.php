<?php

/**
 * BuildError model for table: build_error
 */

namespace PHPCI\Model;

use b8\Store\Factory;
use PHPCI\Model\Base\BuildErrorBase;
use PHPCI\Store;

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

    public function hash()
    {
        $hash = $this->getPlugin() . '|' . $this->getFile() . '|' . $this->getLineStart();
        $hash .= '|' . $this->getLineEnd() . '|' . $this->getSeverity() . '|' . $this->getMessage();

        $this->setHash(md5($hash));

        /** @var Store\BuildErrorStore $errorStore */
        $errorStore = Factory::getStore('BuildError');

        $this->setIsNew($errorStore->getIsNewError($this->getHash()));
    }
}

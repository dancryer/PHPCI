<?php
/**
 * PHPCI - Continuous Integration for PHP
 *
 * @copyright    Copyright 2015, Block 8 Limited.
 * @license      https://github.com/Block8/PHPCI/blob/master/LICENSE.md
 * @link         https://www.phptesting.org/
 */

namespace PHPCI\ProcessControl;

/**
 * Construct an appropriate ProcessControl instance.
 *
 * @author Adirelle <adirelle@gmail.com>
 */
class Factory
{
    /**
     * ProcessControl singleton.
     *
     * @var ProcessControlInterface
     */
    protected static $instance = null;

    /**
     * Returns the ProcessControl singleton.
     *
     * @return ProcessControlInterface
     */
    public static function getInstance()
    {
        if (static::$instance === null) {
            static::$instance = static::createProcessControl();
        }
        return static::$instance;
    }

    /**
     * Create a ProcessControl depending on available extensions and the underlying OS.
     *
     * Check PosixProcessControl, WindowsProcessControl and UnixProcessControl, in that order.
     *
     * @return ProcessControlInterface
     *
     * @internal
     */
    public static function createProcessControl()
    {
        switch (true) {
            case PosixProcessControl::isAvailable():
                return new PosixProcessControl();

            case WindowsProcessControl::isAvailable():
                return new WindowsProcessControl();

            case UnixProcessControl::isAvailable():
                return new UnixProcessControl();
        }

        throw new \Exception("No ProcessControl implementation available.");
    }
}

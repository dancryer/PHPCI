<?php

namespace PHPCI\Helper;

use Psr\Log\LogLevel;

class WindowsCommandExecutor extends BaseCommandExecutor
{
    /**
     * Find a binary required by a plugin.
     * @param string $binary
     * @return null|string
     */
    public function findBinary($binary)
    {
        $binaryPath = null;

        if (is_string($binary)) {
            $binary = array($binary);
        }

        foreach ($binary as $bin) {
            $this->logger->log("Looking for binary: " . $bin, LogLevel::DEBUG);

            if (is_file($this->rootDir . $bin)) {
                $this->logger->log("Found in root: " . $bin, LogLevel::DEBUG);
                $binaryPath = $this->rootDir . $bin;
                break;
            }

            if (is_file($this->rootDir . 'vendor/bin/' . $bin)) {
                $this->logger->log("Found in vendor/bin: " . $bin, LogLevel::DEBUG);
                $binaryPath = $this->rootDir . 'vendor/bin/' . $bin;
                break;
            }

            $findCmdResult = trim(shell_exec('where ' . $bin));
            if (!empty($findCmdResult)) {
                $this->logger->log("Found in " . $findCmdResult, LogLevel::DEBUG);
                $binaryPath = $findCmdResult;
                break;
            }
        }
        return $binaryPath;
    }
}

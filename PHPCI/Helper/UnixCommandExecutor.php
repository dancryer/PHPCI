<?php

namespace PHPCI\Helper;

class UnixCommandExecutor extends BaseCommandExecutor
{
    /**
     * Find a binary required by a plugin.
     * @param string $binary
     * @return null|string
     */
    public function findBinary($binary)
    {
        $binaryPath = parent::findBinary($binary);
        if (is_null($binaryPath)) {

            if (is_string($binary)) {
                $binary = array($binary);
            }

            foreach ($binary as $bin) {
                $findCmdResult = trim(shell_exec('which ' . $bin));

                if (!empty($findCmdResult)) {
                    $this->logger->log("Found in " . $findCmdResult, LogLevel::DEBUG);
                    $binaryPath = $findCmdResult;
                    break;
                }
            }
        }
        return $binaryPath;
    }
}

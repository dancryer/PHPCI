<?php

namespace PHPCI\Plugin;

/**
 * Phing Launcher Plugin - Provides access to Phing functionality with Phing Launcher.
 *
 * @author       kmelia
 * @package      PHPCI
 * @subpackage   Plugins
 */
class PhingLauncher extends Phing implements \PHPCI\Plugin
{
    /**
     * Executes Phing Launcher and runs a specified targets
     */
    public function execute()
    {
        $phingExecutable = $this->phpci->findBinary('phing.sh');
        
        $cmd[] = 'sh ' . $phingExecutable . ' -f ' . $this->getBuildFilePath();
        
        if ($this->getPropertyFile()) {
            $cmd[] = '-propertyfile ' . $this->getPropertyFile();
        }
        
        $cmd[] = $this->propertiesToString();
        
        $cmd[] = '-logger phing.listener.DefaultLogger';
        $cmd[] = $this->targetsToString();
        $cmd[] = '2>&1';
        
        return $this->phpci->executeCommand(implode(' ', $cmd), $this->directory, $this->targets);
    }
}

<?php
/**
 * PHPCI - Continuous Integration for PHP
 *
 * @copyright    Copyright 2014, Block 8 Limited.
 * @license      https://github.com/Block8/PHPCI/blob/master/LICENSE.md
 * @link         https://www.phptesting.org/
 */

namespace PHPCI\Plugin;

use PHPCI\Builder;
use PHPCI\Model\Build;

/**
 * TelegramHorn Plugin - Sends a notification to the Telegram using @bullhorn_bot
 * @author       Mayron Ceccon <mayron.ceccon@gmail.com>
 * @package      PHPCI
 * @subpackage   Plugins
 */
class TelegramHorn implements \PHPCI\Plugin
{
    protected $phpci;
    protected $build;
    private $destinataryHorn = array();

    public function __construct(Builder $phpci, Build $build, array $options = array())
    {
        $this->phpci = $phpci;
        $this->build = $build;

        if ($options['destinatary_horn']) {
                $this->destinataryHorn = $options['destinatary_horn'];
        }
    }

    public function execute()
    {
        if (!is_array($this->destinataryHorn) and count($this->destinataryHorn) <= 0) {
                return false;
        }

        $buildStatus  = $this->build->isSuccessful() ? "✅ Passing Build " : "❌  Failing Build";
        $projectName  = $this->build->getProject()->getTitle();

        $text = sprintf(
                "%s - PHPCI - (%s - %s#%s) \n *Your Commit - %s* \n You can review [your commit](%s) and the [build log](%s)",
                date('d/m/Y H:i:s'),
                $projectName,
                $buildStatus,
                $this->build->getId(),
                $this->build->getCommitMessage(),
                $this->build->getCommitLink(),
                PHPCI_URL . 'build/view/' . $this->build->getId()
        );
        return $this->sendMessage($text);
    }

    public function sendMessage($text)
    {
        $data = array("text" => $text);
        $dataString = json_encode($data);
        $errors = 0;
        foreach ($this->destinataryHorn as $destinatary) {
                $ch = curl_init($destinatary);
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
                curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                        'Content-Type: application/json',
                        'Content-Length: ' . strlen($dataString))
                );

                $result = curl_exec($ch);
                if (!$result) {
                        $errors++;
                }
                curl_close($ch);
        }

        if ($errors > 0) {
                return false;
        }
        return true;
    }
}

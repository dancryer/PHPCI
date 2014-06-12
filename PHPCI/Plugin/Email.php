<?php
/**
 * PHPCI - Continuous Integration for PHP
 *
 * @copyright    Copyright 2014, Block 8 Limited.
 * @license      https://github.com/Block8/PHPCI/blob/master/LICENSE.md
 * @link         https://www.phptesting.org/
 */

namespace PHPCI\Plugin;

use b8\View;
use PHPCI\Builder;
use PHPCI\Model\Build;

/**
* Email Plugin - Provides simple email capability to PHPCI.
* @author       Steve Brazier <meadsteve@gmail.com>
* @package      PHPCI
* @subpackage   Plugins
*/
class Email implements \PHPCI\Plugin
{
    /**
     * @var \PHPCI\Builder
     */
    protected $phpci;

    /**
     * @var array
     */
    protected $options;

    /**
     * @var \Swift_Mailer
     */
    protected $mailer;

    /**
     * @var string
     */
    protected $fromAddress;

    public function __construct(
        Builder $phpci,
        Build $build,
        \Swift_Mailer $mailer,
        array $options = array()
    ) {
        $this->phpci        = $phpci;
        $this->build        = $build;
        $this->options      = $options;

        $phpCiSettings      = $phpci->getSystemConfig('phpci');

        $this->fromAddress = isset($phpCiSettings['email_settings']['from_address'])
                           ? $phpCiSettings['email_settings']['from_address']
                           : "notifications-ci@phptesting.org";

        $this->mailer = $mailer;
    }

    /**
    * Connects to MySQL and runs a specified set of queries.
    */
    public function execute()
    {
        $addresses = $this->getEmailAddresses();

        // Without some email addresses in the yml file then we
        // can't do anything.
        if (count($addresses) == 0) {
            return false;
        }

        $subjectTemplate = "PHPCI - %s - %s";
        $projectName = $this->phpci->getBuildProjectTitle();
        $logText = $this->build->getLog();

        if ($this->build->isSuccessful()) {
            $sendFailures = $this->sendSeparateEmails(
                $addresses,
                sprintf($subjectTemplate, $projectName, "Passing Build"),
                sprintf("Log Output: <br><pre>%s</pre>", $logText)
            );
        } else {
            $view = new View('Email/failed');
            $view->build = $this->build;
            $view->project = $this->build->getProject();

            $emailHtml = $view->render();

            $sendFailures = $this->sendSeparateEmails(
                $addresses,
                sprintf($subjectTemplate, $projectName, "Failing Build"),
                $emailHtml
            );
        }

        // This is a success if we've not failed to send anything.
        $this->phpci->log(sprintf("%d emails sent", (count($addresses) - count($sendFailures))));
        $this->phpci->log(sprintf("%d emails failed to send", count($sendFailures)));

        return (count($sendFailures) == 0);
    }

    /**
     * @param string[]|string $toAddresses Array or single address to send to
     * @param string[] $ccList
     * @param string $subject Email subject
     * @param string $body Email body
     * @return array                      Array of failed addresses
     */
    public function sendEmail($toAddresses, $ccList, $subject, $body)
    {
        $message = \Swift_Message::newInstance($subject)
            ->setFrom($this->fromAddress)
            ->setTo($toAddresses)
            ->setBody($body)
            ->setContentType("text/html");

        if (is_array($ccList) && count($ccList)) {
            $message->setCc($ccList);
        }

        $failedAddresses = array();
        $this->mailer->send($message, $failedAddresses);

        return $failedAddresses;
    }

    public function sendSeparateEmails(array $toAddresses, $subject, $body)
    {
        $failures = array();
        $ccList = $this->getCcAddresses();

        foreach ($toAddresses as $address) {
            $newFailures = $this->sendEmail($address, $ccList, $subject, $body);
            foreach ($newFailures as $failure) {
                $failures[] = $failure;
            }
        }
        return $failures;
    }

    protected function getEmailAddresses()
    {
        $addresses = array();
        $committer = $this->build->getCommitterEmail();

        if (isset($this->options['committer']) && !empty($committer)) {
            $addresses[] = $committer;
        }

        if (isset($this->options['addresses'])) {
            foreach ($this->options['addresses'] as $address) {
                $addresses[] = $address;
            }
        }

        if (isset($this->options['default_mailto_address'])) {
            $addresses[] = $this->options['default_mailto_address'];
            return $addresses;
        }
        return $addresses;
    }

    protected function getCcAddresses()
    {
        $ccAddresses = array();

        if (isset($this->options['cc'])) {
            foreach ($this->options['cc'] as $address) {
                $ccAddresses[] = $address;
            }
        }

        return $ccAddresses;
    }
}

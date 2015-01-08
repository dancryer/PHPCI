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
use PHPCI\Helper\Lang;
use PHPCI\Model\Build;
use PHPCI\Helper\Email as EmailHelper;

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
     * @var \PHPCI\Model\Build
     */
    protected $build;

    /**
     * @var array
     */
    protected $options;

    /**
     * Set up the plugin, configure options, etc.
     * @param Builder $phpci
     * @param Build $build
     * @param \Swift_Mailer $mailer
     * @param array $options
     */
    public function __construct(
        Builder $phpci,
        Build $build,
        array $options = array()
    ) {
        $this->phpci        = $phpci;
        $this->build        = $build;
        $this->options      = $options;
    }

    /**
     * Send a notification mail.
     */
    public function execute()
    {
        $addresses = $this->getEmailAddresses();

        // Without some email addresses in the yml file then we
        // can't do anything.
        if (count($addresses) == 0) {
            return false;
        }

        $buildStatus  = $this->build->isSuccessful() ? "Passing Build" : "Failing Build";
        $projectName  = $this->build->getProject()->getTitle();
        $mailTemplate = $this->build->isSuccessful() ? 'Email/success' : 'Email/failed';

        $view = new View($mailTemplate);
        $view->build = $this->build;
        $view->project = $this->build->getProject();
        $body = $view->render();

        $sendFailures = $this->sendSeparateEmails(
            $addresses,
            sprintf("PHPCI - %s - %s", $projectName, $buildStatus),
            $body
        );

        // This is a success if we've not failed to send anything.
        $this->phpci->log(sprintf("%d emails sent", (count($addresses) - $sendFailures)));
        $this->phpci->log(sprintf("%d emails failed to send", $sendFailures));

        return ($sendFailures === 0);
    }

    /**
     * @param string $toAddress Single address to send to
     * @param string[] $ccList
     * @param string $subject Email subject
     * @param string $body Email body
     * @return array                      Array of failed addresses
     */
    public function sendEmail($toAddress, $ccList, $subject, $body)
    {
        $email = new EmailHelper();

        $email->setEmailTo($toAddress, $toAddress);
        $email->setSubject($subject);
        $email->setBody($body);
        $email->setHtml(true);

        if (is_array($ccList) && count($ccList)) {
            foreach ($ccList as $address) {
                $email->addCc($address, $address);
            }
        }

        return $email->send();
    }

    /**
     * Send an email to a list of specified subjects.
     *
     * @param array $toAddresses
     *   List of destinatary of message.
     * @param string $subject
     *   Mail subject
     * @param string $body
     *   Mail body
     *
     * @return int number of failed messages
     */
    public function sendSeparateEmails(array $toAddresses, $subject, $body)
    {
        $failures = 0;
        $ccList = $this->getCcAddresses();

        foreach ($toAddresses as $address) {
            if (!$this->sendEmail($address, $ccList, $subject, $body)) {
                $failures++;
            }
        }
        return $failures;
    }

    /**
     * Get the list of email addresses to send to.
     * @return array
     */
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

        if (empty($addresses) && isset($this->options['default_mailto_address'])) {
            $addresses[] = $this->options['default_mailto_address'];
        }

        return array_unique($addresses);
    }

    /**
     * Get the list of email addresses to CC.
     * @return array
     */
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

<?php

namespace PHPCI\Plugin\Tests\Command;

use Symfony\Component\Console\Application;
use Prophecy\PhpUnit\ProphecyTestCase;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\Console\Helper\HelperSet;

class InstallCommandTest extends ProphecyTestCase
{
    protected $config;
    protected $admin;
    protected $application;

    public function setup()
    {
        parent::setup();

        $this->application = new Application();
        $this->application->setHelperSet(new HelperSet());
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockBuilder
     */
    protected function getDialogHelperMock()
    {
        // We check that there's no interaction with user.
        $dialog = $this->getMockBuilder('Symfony\\Component\\Console\\Helper\\DialogHelper')
                       ->setMethods(array(
                           'ask',
                           'askConfirmation',
                           'askAndValidate',
                           'askHiddenResponse',
                           'askHiddenResponseAndValidate',
                       ))
                       ->getMock();

        return $dialog;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockBuilder
     */
    protected function getInstallCommandMock()
    {
        // Current command, we need to mock all method that interact with
        // Database & File system.
        $command = $this->getMockBuilder('PHPCI\\Command\\InstallCommand')
            ->setMethods(array(
                'reloadConfig',
                'verifyNotInstalled',
                'verifyDatabaseDetails',
                'setupDatabase',
                'createAdminUser',
                'writeConfigFile',
            ))
            ->getMock();

        $command->expects($this->once())->method('verifyNotInstalled')->willReturn(true);
        $command->expects($this->once())->method('verifyDatabaseDetails')->willReturn(true);
        $command->expects($this->once())->method('setupDatabase')->willReturn(true);
        $command->expects($this->once())->method('createAdminUser')->will(
            $this->returnCallback(function ($adm) {// use (&$admin) {
                $this->admin = $adm;
            })
        );
        $command->expects($this->once())->method('writeConfigFile')->will(
            $this->returnCallback(function ($cfg) { //use (&$config) {
                $this->config = $cfg;
            })
        );

        return $command;
    }

    protected function getCommandTester($dialog)
    {
        $this->application->getHelperSet()->set($dialog, 'dialog');
        $this->application->add($this->getInstallCommandMock());
        $command = $this->application->find('phpci:install');
        $commandTester = new CommandTester($command);

        return $commandTester;
    }

    protected function getConfig($exclude = null)
    {
        $config = array(
            '--db-host' => 'localhost',
            '--db-name' => 'phpci1',
            '--db-user' => 'phpci2',
            '--db-pass' => 'phpci3',
            '--admin-mail' => 'phpci@phpci.test',
            '--admin-name' => 'phpci4',
            '--admin-pass' => 'phpci5',
            '--url' => 'http://test.phpci.org',
        );

        if (!is_null($exclude)) {
          unset($config[$exclude]);
        }

        return $config;
    }

    protected function executeWithoutParam($param = null, $dialog)
    {
        // Clean result variables.
        $this->admin = array();
        $this->config = array();

        // Get tester and execute with extracted parameters.
        $commandTester = $this->getCommandTester($dialog);
        $parameters = $this->getConfig($param);
        $commandTester->execute($parameters);
    }

    public function testAutomaticInstallation()
    {
        $dialog = $this->getDialogHelperMock();
        $dialog->expects($this->never())->method('ask');
        $dialog->expects($this->never())->method('askConfirmation');
        $dialog->expects($this->never())->method('askAndValidate');
        $dialog->expects($this->never())->method('askHiddenResponse');
        $dialog->expects($this->never())->method('askHiddenResponseAndValidate');

        $this->executeWithoutParam(null, $dialog);
    }

    public function testDatabaseHostnameConfig()
    {
        $dialog = $this->getDialogHelperMock();

        // We specified an input value for hostname.
        $dialog->expects($this->once())->method('ask')->willReturn('testedvalue');
        $dialog->expects($this->never())->method('askConfirmation');
        $dialog->expects($this->never())->method('askAndValidate');
        $dialog->expects($this->never())->method('askHiddenResponse');
        $dialog->expects($this->never())->method('askHiddenResponseAndValidate');

        $this->executeWithoutParam('--db-host', $dialog);

        // Check that specified arguments are correctly loaded.
        $this->assertEquals('testedvalue', $this->config['b8']['database']['servers']['read']);
        $this->assertEquals('testedvalue', $this->config['b8']['database']['servers']['write']);
    }

    public function testDatabaseNameConfig()
    {
        $dialog = $this->getDialogHelperMock();

        // We specified an input value for hostname.
        $dialog->expects($this->once())->method('ask')->willReturn('testedvalue');
        $dialog->expects($this->never())->method('askConfirmation');
        $dialog->expects($this->never())->method('askAndValidate');
        $dialog->expects($this->never())->method('askHiddenResponse');
        $dialog->expects($this->never())->method('askHiddenResponseAndValidate');

        $this->executeWithoutParam('--db-name', $dialog);

        // Check that specified arguments are correctly loaded.
        $this->assertEquals('testedvalue', $this->config['b8']['database']['name']);
    }

    public function testDatabaseUserameConfig()
    {
        $dialog = $this->getDialogHelperMock();

        // We specified an input value for hostname.
        $dialog->expects($this->once())->method('ask')->willReturn('testedvalue');
        $dialog->expects($this->never())->method('askConfirmation');
        $dialog->expects($this->never())->method('askAndValidate');
        $dialog->expects($this->never())->method('askHiddenResponse');
        $dialog->expects($this->never())->method('askHiddenResponseAndValidate');

        $this->executeWithoutParam('--db-user', $dialog);

        // Check that specified arguments are correctly loaded.
        $this->assertEquals('testedvalue', $this->config['b8']['database']['username']);
    }

    public function testDatabasePasswordConfig()
    {
        $dialog = $this->getDialogHelperMock();

        // We specified an input value for hostname.
        $dialog->expects($this->never())->method('ask');
        $dialog->expects($this->never())->method('askConfirmation');
        $dialog->expects($this->never())->method('askAndValidate');
        $dialog->expects($this->once())->method('askHiddenResponse')->willReturn('testedvalue');
        $dialog->expects($this->never())->method('askHiddenResponseAndValidate');

        $this->executeWithoutParam('--db-pass', $dialog);

        // Check that specified arguments are correctly loaded.
        $this->assertEquals('testedvalue', $this->config['b8']['database']['password']);
    }

    public function testPhpciUrlConfig()
    {
        $dialog = $this->getDialogHelperMock();

        // We specified an input value for hostname.
        $dialog->expects($this->never())->method('ask');
        $dialog->expects($this->never())->method('askConfirmation');
        $dialog->expects($this->once())->method('askAndValidate')->willReturn('http://testedvalue.com');
        $dialog->expects($this->never())->method('askHiddenResponse');
        $dialog->expects($this->never())->method('askHiddenResponseAndValidate');

        $this->executeWithoutParam('--url', $dialog);

        // Check that specified arguments are correctly loaded.
        $this->assertEquals('http://testedvalue.com', $this->config['phpci']['url']);
    }

    public function testAdminEmailConfig()
    {
        $dialog = $this->getDialogHelperMock();

        // We specified an input value for hostname.
        $dialog->expects($this->never())->method('ask');
        $dialog->expects($this->never())->method('askConfirmation');
        $dialog->expects($this->once())->method('askAndValidate')->willReturn('test@phpci.com');
        $dialog->expects($this->never())->method('askHiddenResponse');
        $dialog->expects($this->never())->method('askHiddenResponseAndValidate');

        $this->executeWithoutParam('--admin-mail', $dialog);

        // Check that specified arguments are correctly loaded.
        $this->assertEquals('test@phpci.com', $this->admin['mail']);
    }

    public function testAdminUserameConfig()
    {
        $dialog = $this->getDialogHelperMock();

        // Define expectation for dialog.
        $dialog->expects($this->once())->method('ask')->willReturn('testedvalue');
        $dialog->expects($this->never())->method('askConfirmation');
        $dialog->expects($this->never())->method('askAndValidate');
        $dialog->expects($this->never())->method('askHiddenResponse');
        $dialog->expects($this->never())->method('askHiddenResponseAndValidate');

        $this->executeWithoutParam('--admin-name', $dialog);

        // Check that specified arguments are correctly loaded.
        $this->assertEquals('testedvalue', $this->admin['name']);
    }

    public function testAdminPasswordConfig()
    {
        $dialog = $this->getDialogHelperMock();

        // We specified an input value for hostname.
        $dialog->expects($this->never())->method('ask');
        $dialog->expects($this->never())->method('askConfirmation');
        $dialog->expects($this->never())->method('askAndValidate');
        $dialog->expects($this->once())->method('askHiddenResponse')->willReturn('testedvalue');
        $dialog->expects($this->never())->method('askHiddenResponseAndValidate');

        $this->executeWithoutParam('--admin-pass', $dialog);

        // Check that specified arguments are correctly loaded.
        $this->assertEquals('testedvalue', $this->admin['pass']);
    }
}

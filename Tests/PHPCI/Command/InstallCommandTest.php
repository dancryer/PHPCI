<?php

namespace PHPCI\Plugin\Tests\Command;

use Symfony\Component\Console\Application;
use PHPCI\Command\InstallCommand;
use Prophecy\PhpUnit\ProphecyTestCase;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\Console\Helper\HelperSet;

class InstallCommandTest extends ProphecyTestCase
{
    protected $config;
    protected $admin;
    protected $command;
    protected $dialog;
    protected $application;

    public function setup()
    {
        parent::setup();

        // Current command, we need to mock all method that interact with
        // Database & File system.
        $this->command = $this->getMockBuilder('PHPCI\\Command\\InstallCommand')
            ->setMethods(array(
                'reloadConfig',
                'verifyNotInstalled',
                'verifyDatabaseDetails',
                'setupDatabase',
                'createAdminUser',
                'writeConfigFile',
            ))
            ->getMock();

        $this->command->expects($this->once())->method('verifyDatabaseDetails')->willReturn(true);
        $this->command->expects($this->once())->method('setupDatabase')->willReturn(true);
        $this->command->expects($this->once())->method('createAdminUser')->will(
            $this->returnCallback(function ($adm) {// use (&$admin) {
                $this->admin = $adm;
            })
        );
        $this->command->expects($this->once())->method('writeConfigFile')->will(
            $this->returnCallback(function ($cfg) { //use (&$config) {
                $this->config = $cfg;
            })
        );

        // We check that there's no interaction with user.
        $this->dialog = $this->getMockBuilder('Symfony\\Component\\Console\\Helper\\DialogHelper')
            ->setMethods(array(
                'ask',
                'askConfirmation',
                'askAndValidate',
                'askHiddenResponse',
                'askHiddenResponseAndValidate',
            ))
            ->getMock();

        $this->application = new Application();
        $this->application->setHelperSet(new HelperSet());
    }

    protected function getCommandTester()
    {
        $this->application->getHelperSet()->set($this->dialog, 'dialog');
        $this->application->add($this->command);
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

    protected function executeWithoutParam($param = null)
    {
        // Clean result variables.
        $this->admin = array();
        $this->config = array();

        // Get tester and execute with extracted parameters.
        $commandTester = $this->getCommandTester();
        $parameters = $this->getConfig($param);
        $commandTester->execute($parameters);
    }

    public function testAutomticInstallation()
    {
        $this->dialog->expects($this->never())->method('ask');
        $this->dialog->expects($this->never())->method('askConfirmation');
        $this->dialog->expects($this->never())->method('askAndValidate');
        $this->dialog->expects($this->never())->method('askHiddenResponse');
        $this->dialog->expects($this->never())->method('askHiddenResponseAndValidate');

        $this->executeWithoutParam();
    }

    public function testDatabaseHostnameConfig()
    {
        // We specified an input value for hostname.
        $this->dialog->expects($this->once())->method('ask')->willReturn('testedvalue');
        $this->dialog->expects($this->never())->method('askConfirmation');
        $this->dialog->expects($this->never())->method('askAndValidate');
        $this->dialog->expects($this->never())->method('askHiddenResponse');
        $this->dialog->expects($this->never())->method('askHiddenResponseAndValidate');

        $this->executeWithoutParam('--db-host');

        // Check that specified arguments are correctly loaded.
        $this->assertEquals('testedvalue', $this->config['b8']['database']['servers']['read']);
        $this->assertEquals('testedvalue', $this->config['b8']['database']['servers']['write']);
    }

    public function testDatabaseNameConfig()
    {
        // We specified an input value for hostname.
        $this->dialog->expects($this->once())->method('ask')->willReturn('testedvalue');
        $this->dialog->expects($this->never())->method('askConfirmation');
        $this->dialog->expects($this->never())->method('askAndValidate');
        $this->dialog->expects($this->never())->method('askHiddenResponse');
        $this->dialog->expects($this->never())->method('askHiddenResponseAndValidate');

        $this->executeWithoutParam('--db-name');

        // Check that specified arguments are correctly loaded.
        $this->assertEquals('testedvalue', $this->config['b8']['database']['name']);
    }

    public function testDatabaseUserameConfig()
    {
        // We specified an input value for hostname.
        $this->dialog->expects($this->once())->method('ask')->willReturn('testedvalue');
        $this->dialog->expects($this->never())->method('askConfirmation');
        $this->dialog->expects($this->never())->method('askAndValidate');
        $this->dialog->expects($this->never())->method('askHiddenResponse');
        $this->dialog->expects($this->never())->method('askHiddenResponseAndValidate');

        $this->executeWithoutParam('--db-user');

        // Check that specified arguments are correctly loaded.
        $this->assertEquals('testedvalue', $this->config['b8']['database']['username']);
    }

    public function testDatabasePasswordConfig()
    {
        // We specified an input value for hostname.
        $this->dialog->expects($this->never())->method('ask');
        $this->dialog->expects($this->never())->method('askConfirmation');
        $this->dialog->expects($this->never())->method('askAndValidate');
        $this->dialog->expects($this->once())->method('askHiddenResponse')->willReturn('testedvalue');
        $this->dialog->expects($this->never())->method('askHiddenResponseAndValidate');

        $this->executeWithoutParam('--db-pass');

        // Check that specified arguments are correctly loaded.
        $this->assertEquals('testedvalue', $this->config['b8']['database']['password']);
    }

    public function testPhpciUrlConfig()
    {
        // We specified an input value for hostname.
        $this->dialog->expects($this->never())->method('ask');
        $this->dialog->expects($this->never())->method('askConfirmation');
        $this->dialog->expects($this->once())->method('askAndValidate')->willReturn('http://testedvalue.com');
        $this->dialog->expects($this->never())->method('askHiddenResponse');
        $this->dialog->expects($this->never())->method('askHiddenResponseAndValidate');

        $this->executeWithoutParam('--url');

        // Check that specified arguments are correctly loaded.
        $this->assertEquals('http://testedvalue.com', $this->config['phpci']['url']);
    }

    public function testAdminEmailConfig()
    {
        // We specified an input value for hostname.
        $this->dialog->expects($this->never())->method('ask');
        $this->dialog->expects($this->never())->method('askConfirmation');
        $this->dialog->expects($this->once())->method('askAndValidate')->willReturn('test@phpci.com');
        $this->dialog->expects($this->never())->method('askHiddenResponse');
        $this->dialog->expects($this->never())->method('askHiddenResponseAndValidate');

        $this->executeWithoutParam('--admin-mail');

        // Check that specified arguments are correctly loaded.
        $this->assertEquals('test@phpci.com', $this->admin['mail']);
    }

    public function testAdminUserameConfig()
    {
        // Define expectation for dialog.
        $this->dialog->expects($this->once())->method('ask')->willReturn('testedvalue');
        $this->dialog->expects($this->never())->method('askConfirmation');
        $this->dialog->expects($this->never())->method('askAndValidate');
        $this->dialog->expects($this->never())->method('askHiddenResponse');
        $this->dialog->expects($this->never())->method('askHiddenResponseAndValidate');

        $this->executeWithoutParam('--admin-name');

        // Check that specified arguments are correctly loaded.
        $this->assertEquals('testedvalue', $this->admin['name']);
    }

    public function testAdminPasswordConfig()
    {
        // We specified an input value for hostname.
        $this->dialog->expects($this->never())->method('ask');
        $this->dialog->expects($this->never())->method('askConfirmation');
        $this->dialog->expects($this->never())->method('askAndValidate');
        $this->dialog->expects($this->once())->method('askHiddenResponse')->willReturn('testedvalue');
        $this->dialog->expects($this->never())->method('askHiddenResponseAndValidate');

        $this->executeWithoutParam('--admin-pass');

        // Check that specified arguments are correctly loaded.
        $this->assertEquals('testedvalue', $this->admin['pass']);
    }
}

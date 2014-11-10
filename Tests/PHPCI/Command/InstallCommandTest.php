<?php

namespace PHPCI\Plugin\Tests\Command;

use Symfony\Component\Console\Application;
use PHPCI\Command\InstallCommand;
use Prophecy\PhpUnit\ProphecyTestCase;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\Console\Helper\HelperSet;

class InstallCommandTest extends ProphecyTestCase
{
    public function testAutomticInstallation()
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
            ))
            ->getMock();

        $command->expects($this->once())->method('verifyDatabaseDetails')->willReturn(true);
        $command->expects($this->once())->method('setupDatabase')->willReturn(true);
        $command->expects($this->once())->method('createAdminUser')->willReturn(true);

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

        $dialog->expects($this->never())->method('ask');
        $dialog->expects($this->never())->method('askConfirmation');
        $dialog->expects($this->never())->method('askAndValidate');
        $dialog->expects($this->never())->method('askHiddenResponse');
        $dialog->expects($this->never())->method('askHiddenResponseAndValidate');

        $application = new Application();
        $application->setHelperSet(new HelperSet());
        $application->getHelperSet()->set($dialog, 'dialog');

        $application->add($command);
        $command = $application->find('phpci:install');
        $commandTester = new CommandTester($command);

        $commandTester->execute(array(
            '--db-host' => 'localhost',
            '--db-name' => 'phpci',
            '--db-user' => 'phpci',
            '--db-pass' => 'phpci',

            '--admin-mail' => 'phpci@phpci.test',
            '--admin-name' => 'phpci',
            '--admin-pass' => 'phpci',

            '--url' => 'http://test.phpci.org',
        ));
    }
}

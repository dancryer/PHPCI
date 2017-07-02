<?php
/**
 * Kiboko CI - Continuous Integration for PHP
 *
 * @copyright    Copyright 2014, Block 8 Limited.
 * @license      https://github.com/kiboko-labs/ci/blob/master/LICENSE.md
 * @link         https://www.phptesting.org/
 */

namespace Kiboko\Component\ContinuousIntegration\Command;

use Kiboko\Component\ContinuousIntegration\Service\UserService;
use Kiboko\Component\ContinuousIntegration\Helper\Lang;
use Kiboko\Component\ContinuousIntegration\Store\UserStore;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Create admin command - creates an admin user
 * @author       Wogan May (@woganmay)
 * @package      PHPCI
 * @subpackage   Console
 */
class CreateAdminCommand extends Command
{
    /**
     * @var UserStore
     */
    protected $userStore;

    /**
     * @param UserStore $userStore
     */
    public function __construct(UserStore $userStore)
    {
        parent::__construct();

        $this->userStore = $userStore;
    }

    protected function configure()
    {
        $this
            ->setName('phpci:create-admin')
            ->setDescription(Lang::get('create_admin_user'));
    }

    /**
     * Creates an admin user in the existing Kiboko CI database
     *
     * {@inheritDoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $userService = new UserService($this->userStore);

        /** @var $dialog \Symfony\Component\Console\Helper\DialogHelper */
        $dialog = $this->getHelperSet()->get('dialog');

        // Function to validate mail address.
        $mailValidator = function ($answer) {
            if (!filter_var($answer, FILTER_VALIDATE_EMAIL)) {
                throw new \InvalidArgumentException(Lang::get('must_be_valid_email'));
            }

            return $answer;
        };

        $adminEmail = $dialog->askAndValidate($output, Lang::get('enter_email'), $mailValidator, false);
        $adminName = $dialog->ask($output, Lang::get('enter_name'));
        $adminPass = $dialog->askHiddenResponse($output, Lang::get('enter_password'));

        try {
            $userService->createUser($adminName, $adminEmail, $adminPass, true);
            $output->writeln(Lang::get('user_created'));
        } catch (\Exception $e) {
            $output->writeln(sprintf('<error>%s</error>', Lang::get('failed_to_create')));
            $output->writeln(sprintf('<error>%s</error>', $e->getMessage()));
        }
    }
}

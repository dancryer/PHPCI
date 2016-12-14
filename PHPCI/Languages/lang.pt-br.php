<?php
/**
 * PHPCI - Continuous Integration for PHP
 *
 * @copyright    Copyright 2014, Block 8 Limited.
 * @license      https://github.com/Block8/PHPCI/blob/master/LICENSE.md
 * @link         https://www.phptesting.org/
 */

$strings = array(
    'language_name' => 'Português Brasil',
    'language' => 'Idioma',

    // Log in:
    'log_in_to_phpci' => 'Acessar o PHPCI',
    'login_error' => 'Email ou senha incorretos',
    'forgotten_password_link' => 'Perdeu sua senha?',
    'reset_emailed' => 'We\'ve emailed you a link to reset your password.',
    'reset_header' => '<strong>Não se preocupe!</strong><br>Basta digitar o seu endereço de e-mail abaixo e nós lhe enviaremos um email com um link para redefinir sua senha.',
    'reset_email_address' => 'Digite seu endereço de e-mail:',
    'reset_send_email' => 'Solicitar nova senha',
    'reset_enter_password' => 'Please enter a new password',
    'reset_new_password' => 'New password:',
    'reset_change_password' => 'Change password',
    'reset_no_user_exists' => 'No user exists with that email address, please try again.',
    'reset_email_body' => 'Hi %s,

You have received this email because you, or someone else, has requested a password reset for PHPCI.

If this was you, please click the following link to reset your password: %ssession/reset-password/%d/%s

Otherwise, please ignore this email and no action will be taken.

Thank you,

PHPCI',

    'reset_email_title' => 'PHPCI Password Reset for %s',
    'reset_invalid' => 'Invalid password reset request.',
    'email_address' => 'Endereço de e-mail',
    'login' => 'Login / Email Address',
    'password' => 'Senha',
    'log_in' => 'Acessar',


    // Top Nav
    'toggle_navigation' => 'Toggle Navigation',
    'n_builds_pending' => '%d builds pending',
    'n_builds_running' => '%d builds running',
    'edit_profile' => 'Editar Perfil',
    'sign_out' => 'Sair',
    'branch_x' => 'Branch: %s',
    'created_x' => 'Criado: %s',
    'started_x' => 'Iniciado: %s',

    // Sidebar
    'hello_name' => 'Olá, %s',
    'dashboard' => 'Dashboard',
    'admin_options' => 'Opções do Admin',
    'add_project' => 'Adicionar Projeto',
    'settings' => 'Configurações',
    'manage_users' => 'Gerênciar Usuários',
    'plugins' => 'Plugins',
    'view' => 'View',
    'build_now' => 'Compilar Agora',
    'edit_project' => 'Editar Projeto',
    'delete_project' => 'Deletar Projeto',

    // Project Summary:
    'no_builds_yet' => 'Nenhuma compilação ainda!',
    'x_of_x_failed' => '%d out of the last %d builds failed.',
    'x_of_x_failed_short' => '%d / %d failed.',
    'last_successful_build' => ' The last successful build was %s.',
    'never_built_successfully' => ' This project has never built successfully.',
    'all_builds_passed' => 'All of the last %d builds passed.',
    'all_builds_passed_short' => '%d / %d passed.',
    'last_failed_build' => ' The last failed build was %s.',
    'never_failed_build' => ' This project has never failed a build.',
    'view_project' => 'Ver projeto',

    // Timeline:
    'latest_builds' => 'Últimas compilações',
    'pending' => 'Pendente',
    'running' => 'Correndo',
    'success' => 'Sucesso',
    'successful' => 'Bem sucedido',
    'failed' => 'Fracassado',
    'manual_build' => 'Compilação manual',

    // Add/Edit Project:
    'new_project' => 'New Project',
    'project_x_not_found' => 'Project with ID %d does not exist.',
    'project_details' => 'Detalhes do Projeto',
    'public_key_help' => 'Para tornar mais fácil de começar, Geramos Um par de chaves SSH para você usar para este projeto. Para usá-lo, basta adicionar a seguinte chave pública na seção "deploy keys" no provedor onde hospeda seu código.',
    'select_repository_type' => 'Selecione o tipo de repositório...',
    'github' => 'GitHub',
    'bitbucket' => 'Bitbucket',
    'gitlab' => 'GitLab',
    'remote' => 'Remote URL',
    'local' => 'Local Path',
    'hg'    => 'Mercurial',
    'svn'   => 'Subversion',

    'where_hosted' => 'Onde seu projeto está hospedado?',
    'choose_github' => 'Choose a GitHub repository:',

    'repo_name' => 'Nome do repositório / URL (Remota) ou Caminho (Local)',
    'project_title' => 'Titulo do projeto',
    'project_private_key' => 'Chave privada usada para acessar o repositório
                                (Deixe em branco para controles remotos locais e/ou anônimos)',
    'build_config' => 'PHPCI construir configuração para este projeto
                                (if you cannot add a phpci.yml file in the project repository)',
    'default_branch' => 'Nome padrão do branch',
    'allow_public_status' => 'Habilitar página de status pública e imagem para este projeto?',
    'archived' => 'Arquivado',
    'archived_menu' => 'Arquivado',
    'save_project' => 'Salvar Projeto',

    'error_mercurial' => 'Mercurial repository URL must be start with http:// or https://',
    'error_remote' => 'Repository URL must be start with git://, http:// or https://',
    'error_gitlab' => 'GitLab Repository name must be in the format "user@domain.tld:owner/repo.git"',
    'error_github' => 'Repository name must be in the format "owner/repo"',
    'error_bitbucket' => 'Repository name must be in the format "owner/repo"',
    'error_path' => 'The path you specified does not exist.',

    // View Project:
    'all_branches' => 'All Branches',
    'builds' => 'Builds',
    'id' => 'ID',
    'date' => 'Date',
    'project' => 'Project',
    'commit' => 'Commit',
    'branch' => 'Branch',
    'status' => 'Status',
    'prev_link' => '&laquo; Prev',
    'next_link' => 'Next &raquo;',
    'public_key' => 'Public Key',
    'delete_build' => 'Delete Build',

    'webhooks' => 'Webhooks',
    'webhooks_help_github' => 'To automatically build this project when new commits are pushed, add the URL below
                                as a new "Webhook" in the <a href="https://github.com/%s/settings/hooks">Webhooks
                                and Services</a>  section of your GitHub repository.',

    'webhooks_help_gitlab' => 'To automatically build this project when new commits are pushed, add the URL below
                                as a "WebHook URL" in the Web Hooks section of your GitLab repository.',

    'webhooks_help_bitbucket' => 'To automatically build this project when new commits are pushed, add the URL below
                                as a "POST" service in the
                                <a href="https://bitbucket.org/%s/admin/services">
                                Services</a> section of your Bitbucket repository.',

    // View Build
    'errors' => 'Erros',
    'information' => 'Informação',

    'build_x_not_found' => 'Build with ID %d does not exist.',
    'build_n' => 'Compilação %d',
    'rebuild_now' => 'Recompilar Agora',


    'committed_by_x' => 'Committed by %s',
    'commit_id_x' => 'Commit: %s',

    'chart_display' => 'This chart will display once the build has completed.',

    'build' => 'Build',
    'lines' => 'Lines',
    'comment_lines' => 'Comment Lines',
    'noncomment_lines' => 'Non-Comment Lines',
    'logical_lines' => 'Logical Lines',
    'lines_of_code' => 'Lines of Code',
    'build_log' => 'Log de compilação',
    'quality_trend' => 'Quality Trend',
    'codeception_errors' => 'Codeception Errors',
    'phpmd_warnings' => 'PHPMD Warnings',
    'phpcs_warnings' => 'PHPCS Warnings',
    'phpcs_errors' => 'PHPCS Errors',
    'phplint_errors' => 'Lint Errors',
    'phpunit_errors' => 'PHPUnit Errors',
    'phpdoccheck_warnings' => 'Missing Docblocks',
    'issues' => 'Issues',

    'codeception' => 'Codeception',
    'phpcpd' => 'PHP Copy/Paste Detector',
    'phpcs' => 'PHP Code Sniffer',
    'phpdoccheck' => 'Missing Docblocks',
    'phpmd' => 'PHP Mess Detector',
    'phpspec' => 'PHP Spec',
    'phpunit' => 'PHP Unit',
    'technical_debt' => 'Technical Debt',
    'behat' => 'Behat',

    'codeception_feature' => 'Feature',
    'codeception_suite' => 'Suite',
    'codeception_time' => 'Time',
    'codeception_synopsis' => '<strong>%1$d</strong> tests carried out in <strong>%2$f</strong> seconds.
                               <strong>%3$d</strong> failures.',

    'file' => 'File',
    'line' => 'Line',
    'class' => 'Class',
    'method' => 'Method',
    'message' => 'Message',
    'start' => 'Start',
    'end' => 'End',
    'from' => 'From',
    'to' => 'To',
    'result' => 'Result',
    'ok' => 'OK',
    'took_n_seconds' => 'Took %d seconds',
    'build_created' => 'Build Created',
    'build_started' => 'Build Started',
    'build_finished' => 'Build Finished',
    'test_message' => 'Message',
    'test_no_message' => 'No message',
    'test_success' => 'Successful: %d',
    'test_fail' => 'Failures: %d',
    'test_skipped' => 'Skipped: %d',
    'test_error' => 'Errors: %d',
    'test_todo' => 'Todos: %d',
    'test_total' => '%d test(s)',

    // Users
    'name' => 'Nome',
    'password_change' => 'Password (leave blank if you don\'t want to change)',
    'save' => 'Save &raquo;',
    'update_your_details' => 'Update your details',
    'your_details_updated' => 'Your details have been updated.',
    'add_user' => 'Adicionar Usuário',
    'is_admin' => 'É Administrador?',
    'yes' => 'Sim',
    'no' => 'Não',
    'edit' => 'Edit.',
    'edit_user' => 'Editar Usuário',
    'delete_user' => 'Deletar Usuário',
    'user_n_not_found' => 'User with ID %d does not exist.',
    'is_user_admin' => 'Este usuário é um administrador?',
    'save_user' => 'Salvar Usuário',

    // Settings:
    'settings_saved' => 'Your settings have been saved.',
    'settings_check_perms' => 'Your settings could not be saved, check the permissions of your config.yml file.',
    'settings_cannot_write' => 'PHPCI cannot write to your config.yml file, settings may not be saved properly
                                until this is rectified.',
    'settings_github_linked' => 'Your GitHub account has been linked.',
    'settings_github_not_linked' => 'Your GitHub account could not be linked.',
    'build_settings' => 'Build Settings',
    'github_application' => 'GitHub Application',
    'github_sign_in' => 'Before you can start using GitHub, you need to <a href="%s">sign in</a> and grant
                            PHPCI access to your account.',
    'github_phpci_linked' => 'PHPCI is successfully linked to GitHub account.',
    'github_where_to_find' => 'Where to find these...',
    'github_where_help' => 'If you own the application you would like to use, you can find this information within your
                            <a href="https://github.com/settings/applications">applications</a> settings area.',

    'email_settings' => 'Email Settings',
    'email_settings_help' => 'Before PHPCI can send build status emails,
                                you need to configure your SMTP settings below.',

    'application_id' => 'Application ID',
    'application_secret' => 'Application Secret',

    'smtp_server' => 'SMTP Server',
    'smtp_port' => 'SMTP Port',
    'smtp_username' => 'SMTP Username',
    'smtp_password' => 'SMTP Password',
    'from_email_address' => 'From Email Address',
    'default_notification_address' => 'Default Notification Email Address',
    'use_smtp_encryption' => 'Use SMTP Encryption?',
    'none' => 'None',
    'ssl' => 'SSL',
    'tls' => 'TLS',

    'failed_after' => 'Consider a build failed after',
    '5_mins' => '5 Minutes',
    '15_mins' => '15 Minutes',
    '30_mins' => '30 Minutes',
    '1_hour' => '1 Hour',
    '3_hours' => '3 Hours',

    // Plugins
    'cannot_update_composer' => 'PHPCI cannot update composer.json for you as it is not writable.',
    'x_has_been_removed' => '%s has been removed.',
    'x_has_been_added' => '%s has been added to composer.json for you and will be installed next time
                            you run composer update.',
    'enabled_plugins' => 'Plugins Habilitados',
    'provided_by_package' => 'Provided By Package',
    'installed_packages' => 'Pacotes instalados',
    'suggested_packages' => 'Suggested Packages',
    'title' => 'Title',
    'description' => 'Description',
    'version' => 'Version',
    'install' => 'Install &raquo;',
    'remove' => 'Remove &raquo;',
    'search_packagist_for_more' => 'Search Packagist for more packages',
    'search' => 'Search &raquo;',

    // Summary plugin
    'build-summary' => 'Summary',
    'stage' => 'Stage',
    'duration' => 'Duration',
    'plugin' => 'Plugin',
    'stage_setup' => 'Setup',
    'stage_test' => 'Test',
    'stage_complete' => 'Complete',
    'stage_success' => 'Success',
    'stage_failure' => 'Failure',
    'stage_broken'  => 'Broken',
    'stage_fixed' => 'Fixed',

    // Installer
    'installation_url' => 'PHPCI Installation URL',
    'db_host' => 'Database Host',
    'db_name' => 'Database Name',
    'db_user' => 'Database Username',
    'db_pass' => 'Database Password',
    'admin_name' => 'Admin Name',
    'admin_pass' => 'Admin Password',
    'admin_email' => 'Admin Email Address',
    'config_path' => 'Config File Path',
    'install_phpci' => 'Install PHPCI',
    'welcome_to_phpci' => 'Welcome to PHPCI',
    'please_answer' => 'Please answer the following questions:',
    'phpci_php_req' => 'PHPCI requires at least PHP 5.3.8 to function.',
    'extension_required' => 'Extension required: %s',
    'function_required' => 'PHPCI needs to be able to call the %s() function. Is it disabled in php.ini?',
    'requirements_not_met' => 'PHPCI cannot be installed, as not all requirements are met.
                                Please review the errors above before continuing.',
    'must_be_valid_email' => 'Must be a valid email address.',
    'must_be_valid_url' => 'Must be a valid URL.',
    'enter_name' => 'Admin Name: ',
    'enter_email' => 'Admin Email: ',
    'enter_password' => 'Admin Password: ',
    'enter_phpci_url' => 'Your PHPCI URL ("http://phpci.local" for example): ',

    'enter_db_host' => 'Please enter your MySQL host [localhost]: ',
    'enter_db_name' => 'Please enter your MySQL database name [phpci]: ',
    'enter_db_user' => 'Please enter your MySQL username [phpci]: ',
    'enter_db_pass' => 'Please enter your MySQL password: ',
    'could_not_connect' => 'PHPCI could not connect to MySQL with the details provided. Please try again.',
    'setting_up_db' => 'Setting up your database... ',
    'user_created' => 'User account created!',
    'failed_to_create' => 'PHPCI failed to create your admin account.',
    'config_exists' => 'The PHPCI config file exists and is not empty.',
    'update_instead' => 'If you were trying to update PHPCI, please use phpci:update instead.',

    // Update
    'update_phpci' => 'Update the database to reflect modified models.',
    'updating_phpci' => 'Updating PHPCI database: ',
    'not_installed' => 'PHPCI does not appear to be installed.',
    'install_instead' => 'Please install PHPCI via phpci:install instead.',

    // Poll Command
    'poll_github' => 'Poll GitHub to check if we need to start a build.',
    'no_token' => 'No GitHub token found',
    'finding_projects' => 'Finding projects to poll',
    'found_n_projects' => 'Found %d projects',
    'last_commit_is' => 'Last commit to GitHub for %s is %s',
    'adding_new_build' => 'Last commit is different to database, adding new build.',
    'finished_processing_builds' => 'Finished processing builds.',

    // Create Admin
    'create_admin_user' => 'Create an admin user',
    'incorrect_format' => 'Incorrect format',

    // Create Build Command
    'create_build_project' => 'Create a build for a project',
    'project_id_argument' => 'A project ID',
    'commit_id_option' => 'Commit ID to build',
    'branch_name_option' => 'Branch to build',
    'add_to_queue_failed' => 'Build created successfully, but failed to add to build queue. This usually happens
                                when PHPCI is set to use a beanstalkd server that does not exist, 
                                or your beanstalkd server has stopped.',

    // Run Command
    'run_all_pending' => 'Run all pending PHPCI builds.',
    'finding_builds' => 'Finding builds to process',
    'found_n_builds' => 'Found %d builds',
    'skipping_build' => 'Skipping Build %d - Project build already in progress.',
    'marked_as_failed' => 'Build %d marked as failed due to timeout.',

    // Builder
    'missing_phpci_yml' => 'This project does not contain a phpci.yml file, or it is empty.',
    'build_success' => 'BUILD SUCCESS',
    'build_failed' => 'BUILD FAILED',
    'removing_build' => 'Removing Build.',
    'exception' => 'Exception: ',
    'could_not_create_working' => 'Could not create a working copy.',
    'working_copy_created' => 'Working copy created: %s',
    'looking_for_binary' => 'Looking for binary: %s',
    'found_in_path' => 'Found in %s: %s',
    'running_plugin' => 'RUNNING PLUGIN: %s',
    'plugin_success' => 'PLUGIN: SUCCESS',
    'plugin_failed' => 'PLUGIN: FAILED',
    'plugin_missing' => 'Plugin does not exist: %s',
    'tap_version' => 'TapParser only supports TAP version 13',
    'tap_error' => 'Invalid TAP string, number of tests does not match specified test count.',

    // Build Plugins:
    'no_tests_performed' => 'No tests have been performed.',
    'could_not_find' => 'Could not find %s',
    'no_campfire_settings' => 'No connection parameters given for Campfire plugin',
    'failed_to_wipe' => 'Failed to wipe existing directory %s before copy',
    'passing_build' => 'Passing Build',
    'failing_build' => 'Failing Build',
    'log_output' => 'Log Output: ',
    'n_emails_sent' => '%d emails sent.',
    'n_emails_failed' => '%d emails failed to send.',
    'unable_to_set_env' => 'Unable to set environment variable',
    'tag_created' => 'Tag created by PHPCI: %s',
    'x_built_at_x' => '%PROJECT_TITLE% built at %BUILD_URI%',
    'hipchat_settings' => 'Please define room and authToken for hipchat_notify plugin',
    'irc_settings' => 'You must configure a server, room and nick.',
    'invalid_command' => 'Invalid command',
    'import_file_key' => 'Import statement must contain a \'file\' key',
    'cannot_open_import' => 'Cannot open SQL import file: %s',
    'unable_to_execute' => 'Unable to execute SQL file',
    'phar_internal_error' => 'Phar Plugin Internal Error',
    'build_file_missing' => 'Specified build file does not exist.',
    'property_file_missing' => 'Specified property file does not exist.',
    'could_not_process_report' => 'Could not process the report generated by this tool.',
    'shell_not_enabled' => 'The shell plugin is not enabled. Please enable it via config.yml.',


    // Error Levels:
    'critical' => 'Critical',
    'high' => 'High',
    'normal' => 'Normal',
    'low' => 'Low',

    // Plugins that generate errors:
    'php_mess_detector' => 'PHP Mess Detector',
    'php_code_sniffer' => 'PHP Code Sniffer',
    'php_unit' => 'PHP Unit',
    'php_cpd' => 'PHP Copy/Paste Detector',
    'php_docblock_checker' => 'PHP Docblock Checker',
    'behat' => 'Behat',
    'technical_debt' => 'Technical Debt',

);

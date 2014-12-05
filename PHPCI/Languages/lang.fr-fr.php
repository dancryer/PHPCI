<?php
/**
 * PHPCI - Continuous Integration for PHP
 *
 * @copyright    Copyright 2014, Block 8 Limited.
 * @license      https://github.com/Block8/PHPCI/blob/master/LICENSE.md
 * @link         https://www.phptesting.org/
 */

$strings = array(
    // Log in:
    'log_in_to_phpci' => 'Connectez-vous à PHPCI',
    'login_error' => 'Adresse email ou mot de passe invalide',
    'forgotten_password_link' => 'Mot de passe oublié ?',
    'reset_emailed' => 'Nous vous avons envoyé un email avec un lien pour réinitialiser votre mot de passe.',
    'reset_header' => '<strong>Pas d\'inquiétude</strong><br>Entrez simplement votre adresse email ci-dessous
                            et nous vous enverrons un message avec un lien pour réinitialiser votre mot de passe.',
    'reset_email_address' => 'Entrez votre adresse email:',
    'reset_send_email' => 'Envoyer le mail',
    'reset_enter_password' => 'Veuillez entrer un nouveau mot de passe',
    'reset_new_password' => 'Nouveau mot de passe :',
    'reset_change_password' => 'Modifier le mot de passe',
    'reset_no_user_exists' => 'Il n\'existe aucun utilisateur avec cette adresse email, merci de réessayer.',
    'reset_email_body' => 'Bonjour %s,

Vous avez reçu cet email parce qu\'une demande de réinitialisation de mot de passe a été faite pour votre compte PHPCI.

Si c\'est bien vous, merci de cliquer sur le lien suivant pour réinitialiser votre mot de passe : %ssession/reset-password/%d/%s

Sinon, merci d\'ignorer ce message.

Merci,

PHPCI',

    'reset_email_title' => 'Réinitialisation du mot de passe PHPCI pour %s',
    'reset_invalid' => 'Requête de réinitialisation de mot de passe invalide.',
    'email_address' => 'Adresse email',
    'password' => 'Mot de passe',
    'log_in' => 'Connexion',


    // Top Nav
    'toggle_navigation' => 'Afficher/cacher la navigation',
    'n_builds_pending' => '%d builds en attente',
    'n_builds_running' => '%d builds en cours d\'exécution',
    'edit_profile' => 'Éditer le profil',
    'sign_out' => 'Déconnexion',
    'branch_x' => 'Branche : %s',
    'created_x' => 'Créé à : %s',
    'started_x' => 'Démarré à : %s',

    // Sidebar
    'hello_name' => 'Salut, %s',
    'dashboard' => 'Tableau de bord',
    'admin_options' => 'Options d\'administration',
    'add_project' => 'Ajouter un projet',
    'settings' => 'Paramètres',
    'manage_users' => 'Gérer les utilisateurs',
    'plugins' => 'Plugins',
    'view' => 'Voir',
    'build_now' => 'Démarrer le build',
    'edit_project' => 'Éditer le projet',
    'delete_project' => 'Supprimer le projet',

    // Dashboard:
    'dashboard' => 'Tableau de bord',

    // Project Summary:
    'no_builds_yet' => 'Aucun build pour le moment!',
    'x_of_x_failed' => '%d parmis les derniers %d builds ont échoué.',
    'x_of_x_failed_short' => '%d / %d ont échoué.',
    'last_successful_build' => ' Le dernier build qui a réussi est %s.',
    'never_built_successfully' => ' Aucun build n\'a été exécuté avec succès sur ce projet.',
    'all_builds_passed' => 'Les derniers %d builds ont réussis.',
    'all_builds_passed_short' => '%d / %d ont réussis.',
    'last_failed_build' => ' Le dernier build en échec est %s.',
    'never_failed_build' => ' Ce projet n\'a jamais eu un build en échec.',
    'view_project' => 'Voir le projet',

    // Timeline:
    'latest_builds' => 'Les derniers Builds',
    'pending' => 'En attente',
    'running' => 'En cours',
    'success' => 'Terminé',
    'successful' => 'Réussi',
    'failed' => 'Échoué',

    // Add/Edit Project:
    'new_project' => 'Nouveau Projet',
    'project_x_not_found' => 'Il n\'existe pas de Projet avec l\'ID %d.',
    'project_details' => 'Détails du Projet',
    'public_key_help' => 'Pour pouvoir démarrer plus facilement, nous avons généré une paire de clés SSH à utiliser avec ce projet.
                            Pour l\'utiliser, il faut simplement ajouter la clé publique dans la section "Clés de déploiement"
                            de votre outil d\'hébergement de code.',
    'select_repository_type' => 'Sélectionnez le type de dépôt...',
    'github' => 'Github',
    'bitbucket' => 'Bitbucket',
    'gitlab' => 'Gitlab',
    'remote' => 'URL distante',
    'local' => 'Chemin local',
    'hg'    => 'Mercurial',

    'where_hosted' => 'Où est hébergé votre projet ?',
    'choose_github' => 'Choisissez un dépôt Github :',

    'repo_name' => 'Nom du dépôt / URL (distance) ou chemin (local)',
    'project_title' => 'Titre du projet',
    'project_private_key' => 'Clé privée à utiliser pour accéder au dépôt
                                (laissez vide pour les dépôts locaux ou les URLs distantes anonymes)',
    'build_config' => 'Configuration PHPCI spécifique pour ce projet
                                (si vous ne pouvez pas ajouter de fichier phpci.yml à la racine du dépôt)',
    'default_branch' => 'Nom de la branche par défaut',
    'allow_public_status' => 'Activer la page de statut publique et l\'image pour ce projet ?',
    'save_project' => 'Enregistrer le projet',

    'error_mercurial' => 'Les URLs de dépôt Mercurial doivent commencer par http:// ou https://',
    'error_remote' => 'Les URLs de dépôt doivent commencer par git://, http:// ou https://',
    'error_gitlab' => 'Le nom du dépôt GitLab doit avoir le format "user@domain.tld:owner/repo.git"',
    'error_github' => 'Le nom du dépôt doit être dans le format "proprietaire/dépôt"',
    'error_bitbucket' => 'Le nom du dépôt doit être dans le format "proprietaire/dépôt"',
    'error_path' => 'Le chemin que vous avez spécifié n\'existe pas.',

    // View Project:
    'all_branches' => 'Toutes les branches',
    'builds' => 'Builds',
    'id' => 'ID',
    'project' => 'Projet',
    'commit' => 'Commit',
    'branch' => 'Branche',
    'status' => 'Statut',
    'prev_link' => '&laquo; Précédent',
    'next_link' => 'Suivant &raquo;',
    'public_key' => 'Clé Publique',
    'delete_build' => 'Supprimer le build',

    'webhooks' => 'Webhooks',
    'webhooks_help_github' => 'To automatically build this project when new commits are pushed, add the URL below
                                as a new "Webhook" in the <a href="https://github.com/%s/settings/hooks">Webhooks
                                and Services</a>  section of your Github repository.',

    'webhooks_help_gitlab' => 'To automatically build this project when new commits are pushed, add the URL below
                                as a "WebHook URL" in the Web Hooks section of your Gitlab repository.',

    'webhooks_help_bitbucket' => 'To automatically build this project when new commits are pushed, add the URL below
                                as a "POST" service in the
                                <a href="https://bitbucket.org/%s/admin/services">
                                Services</a> section of your Bitbucket repository.',

    // View Build
    'build_x_not_found' => 'Le Build avec l\'ID %d n\'existe pas.',
    'build_n' => 'Build %d',
    'rebuild_now' => 'Relancer maintenant',


    'committed_by_x' => 'Committé par %s',
    'commit_id_x' => 'Commit: %s',

    'chart_display' => 'Ce graphique s\'affichera une fois que le build sera terminé.',

    'build' => 'Build',
    'lines' => 'Lignes',
    'comment_lines' => 'Lignes de commentaires',
    'noncomment_lines' => 'Lignes qui ne sont pas des commentaires',
    'logical_lines' => 'Lignes logiques',
    'lines_of_code' => 'Lignes de code',
    'build_log' => 'Log du build',
    'quality_trend' => 'Tendance de la qualité',
    'phpmd_warnings' => 'Alertes PHPMD',
    'phpcs_warnings' => 'Alertes PHPCS',
    'phpcs_errors' => 'Erreurs PHPCS',
    'phplint_errors' => 'Erreurs de Lint',
    'phpunit_errors' => 'Erreurs PHPUnit',
    'phpdoccheck_warnings' => 'Blocs de documentation manquants',
    'issues' => 'Tickets',

    'phpcpd' => 'PHP Copy/Paste Detector',
    'phpcs' => 'PHP Code Sniffer',
    'phpdoccheck' => 'Missing Docblocks',
    'phpmd' => 'PHP Mess Detector',
    'phpspec' => 'PHP Spec',
    'phpunit' => 'PHP Unit',

    'file' => 'Fichier',
    'line' => 'Ligne',
    'class' => 'Classe',
    'method' => 'Méthode',
    'message' => 'Message',
    'start' => 'Démarrage',
    'end' => 'Fin',
    'from' => 'À partir de',
    'to' => 'jusque',
    'suite' => 'Suite',
    'test' => 'Test',
    'result' => 'Resultat',
    'ok' => 'OK',
    'took_n_seconds' => 'Exécuté en %d secondes',
    'build_created' => 'Build créé',
    'build_started' => 'Build démarré',
    'build_finished' => 'Build terminé',

    // Users
    'name' => 'Nom',
    'password_change' => 'Mot de passe (laissez vide si vous ne voulez pas le changer)',
    'save' => 'Sauvegarder &raquo;',
    'update_your_details' => 'Mettre à jour vos préférences',
    'your_details_updated' => 'Vos préférences ont été bien mises à jour.',
    'add_user' => 'Ajouter un utilisateur',
    'is_admin' => 'Est-il administrateur ?',
    'yes' => 'Oui',
    'no' => 'Non',
    'edit' => 'Éditer',
    'edit_user' => 'Éditer l\'utilisateur',
    'delete_user' => 'Supprimer l\'utilisateur',
    'user_n_not_found' => 'L\'utilisateur avec l\'ID %d n\'existe pas.',
    'is_user_admin' => 'Est-ce que cet utilisateur est administrateur?',
    'save_user' => 'Sauvegarder l\'utilisateur',

    // Settings:
    'settings_saved' => 'Your settings have been saved.',
    'settings_check_perms' => 'Your settings could not be saved, check the permissions of your config.yml file.',
    'settings_cannot_write' => 'PHPCI cannot write to your config.yml file, settings may not be saved properly
                                until this is rectified.',
    'settings_github_linked' => 'Your Github account has been linked.',
    'settings_github_not_linked' => 'Your Github account could not be linked.',
    'build_settings' => 'Build Settings',
    'github_application' => 'Github Application',
    'github_sign_in' => 'Before you can start using Github, you need to <a href="%s">sign in</a> and grant
                            PHPCI access to your account.',
    'github_phpci_linked' => 'PHPCI is successfully linked to Github account.',
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
    'enabled_plugins' => 'Enabled Plugins',
    'provided_by_package' => 'Provided By Package',
    'installed_packages' => 'Installed Packages',
    'suggested_packages' => 'Suggested Packages',
    'title' => 'Title',
    'description' => 'Description',
    'version' => 'Version',
    'install' => 'Install &raquo;',
    'remove' => 'Remove &raquo;',
    'search_packagist_for_more' => 'Search Packagist for more packages',
    'search' => 'Search &raquo;',

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
    'enter_name' => 'Admin Name:',
    'enter_email' => 'Admin Email:',
    'enter_password' => 'Admin Password:',
    'enter_phpci_url' => 'Your PHPCI URL ("http://phpci.local" for example): ',

    'enter_db_host' => 'Please enter your MySQL host [localhost]: ',
    'enter_db_name' => 'Please enter your MySQL name [phpci]: ',
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
    'poll_github' => 'Poll github to check if we need to start a build.',
    'no_token' => 'No github token found',
    'finding_projects' => 'Finding projects to poll',
    'found_n_projects' => 'Found %d projects',
    'last_commit_is' => 'Last commit to github for %s is %s',
    'adding_new_build' => 'Last commit is different to database, adding new build.',
    'finished_processing_builds' => 'Finished processing builds.',

    // Create Admin
    'create_admin_user' => 'Create an admin user',
    'incorrect_format' => 'Incorrect format',

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
    'shell_not_enabled' => 'The shell plugin is not enabled. Please enable it via config.yml.'
);

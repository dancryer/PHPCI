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
    'log_in_to_phpci' => 'Accedi a PHPCI',
    'login_error' => 'Indirizzo email o password errati',
    'forgotten_password_link' => 'Hai dimenticato la tua password?',
    'reset_emailed' => 'Ti abbiamo inviato un link per ripristinare la tua password via email.',
    'reset_header' => '<strong>Non preoccuparti!</strong><br>E\' sufficiente inserire il tuo indirizzo email di seguito e ti invieremo una email con il link per il ripristino della tua password.',
    'reset_email_address' => 'Inserisci il tuo indirizzo email:',
    'reset_send_email' => 'Invia il link di reset della password',
    'reset_enter_password' => 'Per favore inserisci la nuova password',
    'reset_new_password' => 'Nuova password:',
    'reset_change_password' => 'Cambia password',
    'reset_no_user_exists' => 'No user exists with that email address, please try again.',
    'reset_no_user_exists' => 'Non esiste nessun utente con questo indirizzo email, per favore prova ancora.',
    'reset_email_body' => 'Ciao %s,

hai ricevuto questa email perché tu, o qualcun\'altro, ha richiesto un reset della password per PHPCI.

Se questa mail è tua, per favore apri il seguente link per resettare la tua password: %ssession/reset-password/%d/%s

altrimenti, per favore, ignora questa email e nessuna azione verrà intrapresa.

Grazie,

PHPCI',

    'reset_email_title' => 'Ripristino della password di PHPCI per %s',
    'reset_invalid' => 'Richeista di ripristino password non valida.',
    'email_address' => 'Indirizzo Email',
    'password' => 'Password',
    'log_in' => 'Accedi',

    // Top Nav
    'toggle_navigation' => 'Alterna navigazione',
    'n_builds_pending' => '%d build in attesa',
    'n_builds_running' => '%d build in corso',
    'edit_profile' => 'Modifica il Profilo',
    'sign_out' => 'Disconnettiti',
    'branch_x' => 'Branch: %s',
    'created_x' => 'Creato: %s',
    'started_x' => 'Avviato: %s',

    // Sidebar
    'hello_name' => 'Ciao, %s',
    'dashboard' => 'Dashboard',
    'admin_options' => 'Opzioni di amministrazione',
    'add_project' => 'Aggiungi un Progetto',
    'settings' => 'Impostazioni',
    'manage_users' => 'Gestisci Utenti',
    'plugins' => 'Plugins',
    'view' => 'Visualizzazione',
    'build_now' => 'Avvia una build ora',
    'edit_project' => 'Modifica il Progetto',
    'delete_project' => 'Cancella il Progetto',

    // Project Summary:
    'no_builds_yet' => 'Ancora nessuna build!',
    'x_of_x_failed' => '%d delle ultime %d build sono fallite.',
    'x_of_x_failed_short' => '%d / %d fallite.',
    'last_successful_build' => ' L\'ultima build è %s.',
    'never_built_successfully' => ' Questo progetto non ha nessuna build eseguita con successo.',
    'all_builds_passed' => 'Tutte le ultime %d build sono valide.',
    'all_builds_passed_short' => '%d / %d valide.',
    'last_failed_build' => ' L\'ultima build è %s.',
    'never_failed_build' => ' Questo progetto non ha nessuna build fallita.',
    'view_project' => 'Visualizza il Progetto',

    // Timeline:
    'latest_builds' => 'Ultime Build',
    'pending' => 'In attesa',
    'running' => 'In corso',
    'success' => 'Successo',
    'successful' => 'Con successo',
    'failed' => 'Fallita',
    'manual_build' => 'Build Manuale',

    // Add/Edit Project:
    'new_project' => 'Nuovo Progetto',
    'project_x_not_found' => 'Progetto con ID %d non esistente.',
    'project_details' => 'Dettagli del Progetto',
    'public_key_help' => 'Per rendere più facile la procedura, abbiamo generato una chiave SSH per te da
                          usare per questo progetto. Per usarla, aggiungi la chiave pubblica alle "deploy keys"
                          della piattaforma di gestione del codice che hai scelto.',
    'select_repository_type' => 'Seleziona il tipo di repository...',
    'github' => 'Github',
    'bitbucket' => 'Bitbucket',
    'gitlab' => 'Gitlab',
    'remote' => 'URL Remoto',
    'local' => 'Percorso Locale',
    'hg'    => 'Mercurial',

    'where_hosted' => 'Dove è archiviato il tuo progetto?',
    'choose_github' => 'Scegli il repository di Github:',

    'repo_name' => 'Nome del Repository / URL (Remoto) o Percorso (Locale)',
    'project_title' => 'Titolo del Progetto',
    'project_private_key' => 'Chiave provata da usare per accedere al repository
                                (lascia vuota per repository locali o remoti con accesso anonimo)',
    'build_config' => 'condigurazione della build di PHPCI per questo progetto
                                (se non puoi aggiungere il file phpci.yml nel repository di questo progetto)',
    'default_branch' => 'Nome del branch di default',
    'allow_public_status' => 'Vuoi rendere pubblica la pagina dello stato e l\'immagine per questo progetto?',
    'save_project' => 'Salca il Progetto',

    'error_mercurial' => 'L\'URL del repository Mercurial URL deve iniziare con http:// o https://',
    'error_remote' => 'L\'URL del repository deve iniziare con git://, http:// o https://',
    'error_gitlab' => 'Il nome del repository di GitLab deve essere nel seguente formato "utente@dominio.tld:proprietario/repository.git"',
    'error_github' => 'Il nome del repository deve essere nel formato "proprietario/repository"',
    'error_bitbucket' => 'Il nome del repository deve essere nel formato "proprietario/repository"',
    'error_path' => 'The path you specified does not exist.',
    'error_path' => 'Il percorso che hai indicato non esiste.',

    // View Project:
    'all_branches' => 'Tutti i Branche',
    'builds' => 'Builds',
    'id' => 'ID',
    'project' => 'Progetto',
    'commit' => 'Commit',
    'branch' => 'Branch',
    'status' => 'Stato',
    'prev_link' => '&laquo; Precedente',
    'next_link' => 'Successivo &raquo;',
    'public_key' => 'Chiave pubblica',
    'delete_build' => 'Rimuovi build',

    'webhooks' => 'Webhooks',
    'webhooks_help_github' => 'Per efettuare la build automatica di questo progetto quando vengono pushati nuovi commit,
                                aggiungi l\'URL seguente come "Webhook" nella sezione
                                <a href="https://github.com/%s/settings/hooks">Webhooks and Services</a> del tuo
                                repository su Github.',

    'webhooks_help_gitlab' => 'Per efettuare la build automatica di questo progetto quando vengono pushati nuovi commit,
                                aggiungi l\'URL seguente come "Webhook URL" nella sezione "WebHook URL" del tuo
                                repository GitLab.',

    'webhooks_help_bitbucket' => 'Per efettuare la build automatica di questo progetto quando vengono pushati nuovi
                                    commit, aggiungi l\'URL seguente come serizio "POST" nella sezione
                                    <a href="https://bitbucket.org/%s/admin/services">Services</a> del tuo repository su
                                    BITBUCKET.',

    // View Build
    'build_x_not_found' => 'La build con ID %d non esite.',
    'build_n' => 'Build %d',
    'rebuild_now' => 'Esegui nuovamente la build ora',


    'committed_by_x' => 'Committato da %s',
    'commit_id_x' => 'Commit: %s',

    'chart_display' => 'Questo grafico verrà mostrato una volta terminata la build.',

    'build' => 'Build',
    'lines' => 'Linee',
    'comment_lines' => 'Linee di commenti',
    'noncomment_lines' => 'Linee che non sono commenti',
    'logical_lines' => 'Linee di logica',
    'lines_of_code' => 'Linee di codice',
    'build_log' => 'Log della build',
    'quality_trend' => 'Trand della qualità',
    'phpmd_warnings' => 'Warning di PHPMD',
    'phpcs_warnings' => 'Warning di PHPCS',
    'phpcs_errors' => 'Errori di PHPCS',
    'phplint_errors' => 'Errori di Lint',
    'phpunit_errors' => 'Errori di PHPUnit',
    'phpdoccheck_warnings' => 'Docblocks mancanti',
    'issues' => 'Segnalazioni',

    'phpcpd' => 'PHP Copy/Paste Detector',
    'phpcs' => 'PHP Code Sniffer',
    'phpdoccheck' => 'Docblocks mancanti',
    'phpmd' => 'PHP Mess Detector',
    'phpspec' => 'PHP Spec',
    'phpunit' => 'PHP Unit',

    'file' => 'File',
    'line' => 'Lina',
    'class' => 'Classe',
    'method' => 'Metodo',
    'message' => 'Messaggio',
    'start' => 'Inizia',
    'end' => 'Finisci',
    'from' => 'Da',
    'to' => 'A',
    'suite' => 'Suite',
    'test' => 'Test',
    'result' => 'Risultati',
    'ok' => 'OK',
    'took_n_seconds' => 'Sono stati impiegati %d seconds',
    'build_created' => 'Build Creata',
    'build_started' => 'Build Avviata',
    'build_finished' => 'Build Terminata',

    // Users
    'name' => 'Nome',
    'password_change' => 'Password (lascia vuota se non vuoi modificarla)',
    'save' => 'Salva &raquo;',
    'update_your_details' => 'Aggiorna le tue informazioni',
    'your_details_updated' => 'Le tue informazioni sono state aggiornate.',
    'add_user' => 'Aggiung utent',
    'is_admin' => 'E\' amministratore?',
    'yes' => 'Si',
    'no' => 'No',
    'edit' => 'Modifica',
    'edit_user' => 'Modifica utente',
    'delete_user' => 'Cancella utente',
    'user_n_not_found' => 'L\'utente con ID %d non esiste.',
    'is_user_admin' => 'Questo utente è un amministratore?',
    'save_user' => 'Salva utente',

    // Settings:
    'settings_saved' => 'Le configurazioni sono state salvate.',
    'settings_check_perms' => 'Le configurazioni non possono essere salvate, controlla i permessi del filer config.yml.',
    'settings_cannot_write' => 'PHPCI non può scrivere il file config.yml, le configurazioni potrebbero non essere
                                salvate correttamente fintanto che il problema non verrà risolto.',
    'settings_github_linked' => 'Il tuo account Github è stato collegato.',
    'settings_github_not_linked' => 'Il tuo account Github non può essere collegato.',
    'build_settings' => 'Configurazioni della build',
    'github_application' => 'Applicatzione Github',
    'github_sign_in' => 'Prima di poter iniziare ad usare Github, è necessario <a href="%s">collegarsi</a> e garantire
                            a PHPCI l\'accesso al tuo account.',
    'github_phpci_linked' => 'PHPCI è stato collegato correttamente al tuo account Github.',
    'github_where_to_find' => 'Dove trovare queste...',
    'github_where_help' => 'Se sei il proprietario dell\'applicazione, puoi trovare queste informazioni nell\'are delle
                              configurazioni dell\'<a href="https://github.com/settings/applications">applicazione</a>.',

    'email_settings' => 'Impostazioni Email',
    'email_settings_help' => 'Prima che possa inviare le email con lo status PHPCI, devi configurare l\'SMTP qio sotto.',

    'application_id' => 'ID dell\'Applicazione',
    'application_secret' => 'Secret dell\'Applicazione',

    'smtp_server' => 'Server SMTP',
    'smtp_port' => 'Porta SMTP',
    'smtp_username' => 'Username SMTP',
    'smtp_password' => 'Password SMTP',
    'from_email_address' => 'Indirizzio Email del mittente',
    'default_notification_address' => 'Indirizzo email delle notifiche predefinito',
    'use_smtp_encryption' => 'Utilizzare l\'Encrypting per SMTP?',
    'none' => 'No',
    'ssl' => 'SSL',
    'tls' => 'TLS',

    'failed_after' => 'Considera la build fallita dopo',
    '5_mins' => '5 Minuti',
    '15_mins' => '15 Minuti',
    '30_mins' => '30 Minuti',
    '1_hour' => '1 Ora',
    '3_hours' => '3 Ore',

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

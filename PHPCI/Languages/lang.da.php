<?php
/**
 * PHPCI - Continuous Integration for PHP
 *
 * @copyright    Copyright 2014, Block 8 Limited.
 * @license      https://github.com/Block8/PHPCI/blob/master/LICENSE.md
 * @link         https://www.phptesting.org/
 */

$strings = array(
    'language_name' => 'Dansk',
    'language' => 'Sprog',

    // Log in:
    'log_in_to_phpci' => 'Log ind i PHPCI',
    'login_error' => 'Forkert email-adresse eller adgangskode',
    'forgotten_password_link' => 'Har du glemt din adgangskode?',
    'reset_emailed' => 'Vi har sendt dig en email med et link til at nulstille din adgangskode.',
    'reset_header' => '<strong>Bare rolig!</strong><br>Indtast blot din email-adresse, så sender
vi dig et link til at nulstille din adgangskode.',
    'reset_email_address' => 'Indtast din email-adresse:',
    'reset_send_email' => 'Send nulstillings-link',
    'reset_enter_password' => 'Indtast venligst en ny adgangskode',
    'reset_new_password' => 'Ny adgangskode:',
    'reset_change_password' => 'Skift adgangskode',
    'reset_no_user_exists' => 'Der findes ingen bruger med den email-adresse, prøv igen.',
    'reset_email_body' => 'Hej %s,

Du modtager denne email fordi du eller en anden person har anmodet om at nulstille din adgangskode til PHPCI.

Hvis det var dig kan du klikke følgende link for at nulstille din adgangskode: %ssession/reset-password/%d/%s

Hvis det ikke var dig kan du ignorere denne email og intet vil ske.

Tak,

PHPCI',

    'reset_email_title' => 'PHPCI Adgangskode-nulstilling for %s',
    'reset_invalid' => 'Ugyldig anmodning om adgangskode-nulstilling.',
    'email_address' => 'Email-addresse',
    'password' => 'Adgangskode',
    'log_in' => 'Log ind',


    // Top Nav
    'toggle_navigation' => 'Vis/skjul navigation',
    'n_builds_pending' => '%d builds i køen',
    'n_builds_running' => '%d builds kører',
    'edit_profile' => 'Redigér profil',
    'sign_out' => 'Log ud',
    'branch_x' => 'Branch: %s',
    'created_x' => 'Oprettet: %s',
    'started_x' => 'Startet: %s',

    // Sidebar
    'hello_name' => 'Hej %s',
    'dashboard' => 'Dashboard',
    'admin_options' => 'Administrator-indstillinger',
    'add_project' => 'Tilføj projekt',
    'settings' => 'Indstillinger',
    'manage_users' => 'Administrér brugere',
    'plugins' => 'Plugins',
    'view' => 'Vis',
    'build_now' => 'Start build nu',
    'edit_project' => 'Redigér projekt',
    'delete_project' => 'Slet projekt',

    // Project Summary:
    'no_builds_yet' => 'Ingen builds pt.!',
    'x_of_x_failed' => '%d af de sidste %d builds fejlede.',
    'x_of_x_failed_short' => '%d / %d fejlede.',
    'last_successful_build' => 'Sidste succesfulde build var %s.',
    'never_built_successfully' => 'Dette projekt har indtil videre ingen succesfulde builds.',
    'all_builds_passed' => 'All de sidste %d builds fejlede.',
    'all_builds_passed_short' => '%d / %d lykkedes.',
    'last_failed_build' => 'Det sidste mislykkede build var %s',
    'never_failed_build' => 'Dette projekt er endnu ikke blevet kørt.',
    'view_project' => 'Vis Projekt',

    // Timeline:
    'latest_builds' => 'Nyeste Builds',
    'pending' => 'Venter',
    'running' => 'Kører',
    'success' => 'Succes',
    'successful' => 'Lykkedes',
    'failed' => 'Fejlede',
    'manual_build' => 'Manuelt Build',

    // Add/Edit Project:
    'new_project' => 'Nyt Projekt',
    'project_x_not_found' => 'Projektet med ID %d findes ikke.',
    'project_details' => 'Projekt-detaljer',
    'public_key_help' => 'For at gøre det lettere at starte har vi genereret en SSH-nøgle som du kan bruge til dette projekt. For at bruge den behøver du blot tilføje den følgende public key til "deployment keys" sektionen
i din foretrukne hosting-platform.',
    'select_repository_type' => 'Vælg repository-type...',
    'github' => 'GitHub',
    'bitbucket' => 'Bitbucket',
    'gitlab' => 'GitLab',
    'remote' => 'Ekstern URL',
    'local' => 'Lokalt filsystem',
    'hg'    => 'Mercurial',

    'where_hosted' => 'Hvor er dit projekt hosted?',
    'choose_github' => 'Vælg et GitHub-repository:',

    'repo_name' => 'Repository-navn / URL (ekstern) eller filsystem-sti (lokal)',
    'project_title' => 'Projekt-titel',
    'project_private_key' => 'Privat nøgle med adgang til dette repository
(tom for lokal nøgle og/eller anonym adgang)',
    'build_config' => 'PHPCI build-konfiguration for dette projekt
(hvis du ikke har mulighed for at tilføje en phpci.yml fil i projektets repository)',
    'default_branch' => 'Default branch navn',
    'allow_public_status' => 'Tillad offentlig status-side og -billede for dette projekt?',
    'save_project' => 'Gem Projekt',

    'error_mercurial' => 'Mercurial repository-URL skal starte med http:// eller https://',
    'error_remote' => 'Repository-URL skal starte med git://, http:// eller https://',
    'error_gitlab' => 'GitLab repository-navn skal være i formatet "user@domæne.tld:ejernavn/repositorynavn.git"',
    'error_github' => 'Repository-navn skal være i formatet "ejernavn/repositorynavn"',
    'error_bitbucket' => 'Repository-navn skal være i formatet "ejernavn/repositorynavn"',
    'error_path' => 'Stien du indtastede findes ikke.',

    // View Project:
    'all_branches' => 'Alle branches',
    'builds' => 'Builds',
    'id' => 'ID',
    'project' => 'Projekt',
    'commit' => 'Commit',
    'branch' => 'Branch',
    'status' => 'Status',
    'prev_link' => '&laquo; Forrige',
    'next_link' => 'Næste &raquo;',
    'public_key' => 'Offentlig nøgle',
    'delete_build' => 'Slet Build',

    'webhooks' => 'Webhooks',
    'webhooks_help_github' => 'For at køre dette build automatisk når nye commits bliver pushed skal du tilføje nedenstående
URL som nyt "Webhook" i <a href="https://github.com/%s/settings/hooks">Webhooks
and Services</a> under dit GitHub-repository.',

    'webhooks_help_gitlab' => 'For at køre dette build automatisk når nye commits bliver pushed kan du tilføje nedenstående URL
som en "WebHook URL" i Web Hooks-sektionen i dit GitLab-repository.',

    'webhooks_help_bitbucket' => 'For at køre dette build automatisk når nye commits bliver pushed skal du tilføje nedenstående
URL som "POST" service i
<a href="https://bitbucket.org/%s/admin/services">
Services</a> sektionen under dit Bitbucket-repository.',

    // View Build
    'build_x_not_found' => 'Build med ID %d findes ikke.',
    'build_n' => 'Build %d',
    'rebuild_now' => 'Gentag Build',


    'committed_by_x' => 'Committed af %s',
    'commit_id_x' => 'Commit: %s',

    'chart_display' => 'Denne graf vises når buildet er færdigt.',

    'build' => 'Build',
    'lines' => 'Linjer',
    'comment_lines' => 'Kommentar-linjer',
    'noncomment_lines' => 'Ikke-kommentar-linjer',
    'logical_lines' => 'Logiske linjer',
    'lines_of_code' => 'Kode-linjer',
    'build_log' => 'Build-log',
    'quality_trend' => 'Kvalitets-trend',
    'codeception_errors' => 'Codeception-fejl',
    'phpmd_warnings' => 'PHPMD-advarsler',
    'phpcs_warnings' => 'PHPCS-advarsler',
    'phpcs_errors' => 'PHPCS-fejl',
    'phplint_errors' => 'Lint-fejl',
    'phpunit_errors' => 'PHPUnit-fejl',
    'phpdoccheck_warnings' => 'Manglende Docblocks',
    'issues' => 'Sager',

    'codeception' => 'Codeception',
    'phpcpd' => 'PHP Copy/Paste Detector',
    'phpcs' => 'PHP Code Sniffer',
    'phpdoccheck' => 'Manglende Docblocks',
    'phpmd' => 'PHP Mess Detector',
    'phpspec' => 'PHP Spec',
    'phpunit' => 'PHP Unit',

    'file' => 'Fil',
    'line' => 'Linje',
    'class' => 'Klasse',
    'method' => 'Funktion',
    'message' => 'Besked',
    'start' => 'Start',
    'end' => 'Slut',
    'from' => 'Fra',
    'to' => 'Til',
    'suite' => 'Suite',
    'test' => 'Test',
    'result' => 'Resultat',
    'ok' => 'OK',
    'took_n_seconds' => 'Tog %d sekunder',
    'build_created' => 'Build Oprettet',
    'build_started' => 'Build Startet',
    'build_finished' => 'Build Afsluttet',

    // Users
    'name' => 'Navn',
    'password_change' => 'Adgangskode (tom hvis du ikke ønsker at ændre koden)',
    'save' => 'Gem &raquo;',
    'update_your_details' => 'Opdatér oplysninger',
    'your_details_updated' => 'Dine oplysninger blev gemt.',
    'add_user' => 'Tilføj bruger',
    'is_admin' => 'Administrator?',
    'yes' => 'Ja',
    'no' => 'Nej',
    'edit' => 'Redigér',
    'edit_user' => 'Redigér Bruger',
    'delete_user' => 'Slet Bruger',
    'user_n_not_found' => 'Brugeren med ID %d findes ikke.',
    'is_user_admin' => 'Er denne bruger en administrator?',
    'save_user' => 'Gem Bruger',

    // Settings:
    'settings_saved' => 'Dine indstillinger blev gemt.',
    'settings_check_perms' => 'Dine indstillinger kunne ikke gemmes, kontrollér rettighederne på din config.yml fil.',
    'settings_cannot_write' => 'PHPCI kan ikke skrive til din config.yml fil, indstillinger bliver muligvis ikke gemt korrekt før dette problem løses.',
    'settings_github_linked' => 'Din GitHub-konto er nu tilsluttet.',
    'settings_github_not_linked' => 'Din GitHub-konto kunne ikke tilsluttes.',
    'build_settings' => 'Build-indstillinger',
    'github_application' => 'GitHub-applikation',
    'github_sign_in' => 'Før du kan bruge GitHub skal du <a href="%s">logge ind</a> og give PHPCI
adgang til din konto.',
    'github_phpci_linked' => 'PHPCI blev tilsluttet din GitHub-konto.',
    'github_where_to_find' => 'Hvor disse findes...',
    'github_where_help' => 'Hvis du ejer applikationen du ønsker at bruge kan du finde denne information i
<a href="https://github.com/settings/applications">applications</a> under indstillinger.',

    'email_settings' => 'Email-indstillinger',
    'email_settings_help' => 'Før PHPCI kan sende build-notifikationer via email
skal du konfigurere nedenstående SMTP-indstillinger.',

    'application_id' => 'Application ID',
    'application_secret' => 'Application Secret',

    'smtp_server' => 'SMTP-server',
    'smtp_port' => 'SMTP-port',
    'smtp_username' => 'SMTP-brugernavn',
    'smtp_password' => 'SMTP-adgangskode',
    'from_email_address' => 'Fra email-adresse',
    'default_notification_address' => 'Default notifikations-email-adresse',
    'use_smtp_encryption' => 'Brug SMTP-kryptering?',
    'none' => 'Ingen',
    'ssl' => 'SSL',
    'tls' => 'TLS',

    'failed_after' => 'Betragt et build som fejlet efter',
    '5_mins' => '5 minutter',
    '15_mins' => '15 minutter',
    '30_mins' => '30 minutter',
    '1_hour' => '1 time',
    '3_hours' => '3 timer',

    // Plugins
    'cannot_update_composer' => 'PHPCI kan ikke opdatere composer.json da filen ikke kan skrives.',
    'x_has_been_removed' => '%s er blevet slettet.',
    'x_has_been_added' => '%s blev tilføjet til composer.json for dig og vil blive installeret næste gang
du kører composer update.',
    'enabled_plugins' => 'Aktive plugins',
    'provided_by_package' => 'Via pakke',
    'installed_packages' => 'Installerede pakker',
    'suggested_packages' => 'Forslag til pakker',
    'title' => 'Titel',
    'description' => 'Beskrivelse',
    'version' => 'Version',
    'install' => 'Installér &raquo;',
    'remove' => 'Fjern &raquo;',
    'search_packagist_for_more' => 'Søg på Packagist efter flere pakker',
    'search' => 'Søg &raquo;',

    // Installer
    'installation_url' => 'PHPCI Installations-URL',
    'db_host' => 'Database-hostnavn',
    'db_name' => 'Database-navn',
    'db_user' => 'Database-brugernavn',
    'db_pass' => 'Database-adgangskode',
    'admin_name' => 'Administrator-navn',
    'admin_pass' => 'Administrator-adgangskode',
    'admin_email' => 'Administrators email-adresse',
    'config_path' => 'Konfigurations-fil',
    'install_phpci' => 'Installér PHPCI',
    'welcome_to_phpci' => 'Velkommen til PHPCI',
    'please_answer' => 'Besvar venligst følgende spørgsmål:',
    'phpci_php_req' => 'PHPCI kræver minimum PHP version 5.3.8 for at fungere.',
    'extension_required' => 'Extension påkrævet: %s',
    'function_required' => 'PHPCI behøver adgang til funktion %s() i PHP. Er den deaktiveret i php.ini?',
    'requirements_not_met' => 'PHPCI kan ikke installeres da nogle krav ikke opfyldtes.
Kontrollér venligst nedenstående fejl før du fortsætter.',
    'must_be_valid_email' => 'Skal være en gyldig email-adresse.',
    'must_be_valid_url' => 'Skal være en gyldig URL.',
    'enter_name' => 'Administrator-navn:',
    'enter_email' => 'Administrators email-adresse:',
    'enter_password' => 'Administrator-adgangskode:',
    'enter_phpci_url' => 'Din PHPCI URL (eksempelvis "http://phpci.local"):',

    'enter_db_host' => 'Indtast dit MySQL-hostnavn [localhost]:',
    'enter_db_name' => 'Indtast dit MySQL database-navn [phpci]:',
    'enter_db_user' => 'Indtast dit MySQL-brugernavn [phpci]:',
    'enter_db_pass' => 'Indtast dit MySQL-password:',
    'could_not_connect' => 'PHPCI kunne ikke forbinde til MySQL med de angivning oplysninger. Forsøg igen.',
    'setting_up_db' => 'Indlæser database...',
    'user_created' => 'Brugerkonto oprettet!',
    'failed_to_create' => 'PHPCI kunne ikke oprette din administrator-konto.',
    'config_exists' => 'PHPCI konfigurationsfilen findes og er ikke tom.',
    'update_instead' => 'Hvis du forsøgte at opdatere PHPCI, forsøg da venligst med phpci:update istedet.',

    // Update
    'update_phpci' => 'Opdatér databasen med ændrede modeller',
    'updating_phpci' => 'Opdaterer PHPCI-database:',
    'not_installed' => 'PHPCI lader til ikke at være installeret.',
    'install_instead' => 'Installér venligst PHPCI via phpci:install istedet.',

    // Poll Command
    'poll_github' => 'Check via GitHub om et build skal startes.',
    'no_token' => 'GitHub-token findes ikke',
    'finding_projects' => 'Finder projekter der kan forespørges',
    'found_n_projects' => '%d projekter fundet',
    'last_commit_is' => 'Sidste commit til GitHub for %s er %s',
    'adding_new_build' => 'Sidste commit er forskellig fra databasen, tilføjer nyt build.',
    'finished_processing_builds' => 'Kørsel af builds afsluttet.',

    // Create Admin
    'create_admin_user' => 'Tilføj en administrator',
    'incorrect_format' => 'Forkert format',

    // Run Command
    'run_all_pending' => 'Kør alle PHPCI builds i køen.',
    'finding_builds' => 'Finder builds der skal køres',
    'found_n_builds' => '%d builds fundet',
    'skipping_build' => 'Springer over Build %d - projektet kører et build lige nu.',
    'marked_as_failed' => 'Build %d blev markeret som fejlet pga. timeout.',

    // Builder
    'missing_phpci_yml' => 'Dette projekt har ingen phpci.yml fil, eller filen er tom.',
    'build_success' => 'BUILD SUCCES',
    'build_failed' => 'BUILD FEJLET',
    'removing_build' => 'Fjerner Build',
    'exception' => 'Undtagelse:',
    'could_not_create_working' => 'Kunne ikke oprette en arbejds-kopi.',
    'working_copy_created' => 'Arbejds-kopi oprettet: %s',
    'looking_for_binary' => 'Leder efter kommando: %s',
    'found_in_path' => 'Fundet i %s: %s',
    'running_plugin' => 'KØRER PLUGIN: %s',
    'plugin_success' => 'PLUGIN: SUCCES',
    'plugin_failed' => 'PLUGIN: FEJL',
    'plugin_missing' => 'Plugin findes ikke: %s',
    'tap_version' => 'TapParser understøtter kun TAP version 13.',
    'tap_error' => 'Ugyldig TAP-streng, antallet af tests passer ikke med det angivne antal tests.',

    // Build Plugins:
    'no_tests_performed' => 'Ingen tests udført.',
    'could_not_find' => 'Kunne ikke finde %s',
    'no_campfire_settings' => 'Ingen forbindelses-oplysninger angivet i Campfire plugin',
    'failed_to_wipe' => 'Kunne ikke slette eksisterende mappe %s før kopi',
    'passing_build' => 'Succesfuldt Build',
    'failing_build' => 'Fejlet Build',
    'log_output' => 'Log-output:',
    'n_emails_sent' => '%d emails afsendt.',
    'n_emails_failed' => '%d emails kunne ikke afsendes.',
    'unable_to_set_env' => 'Kunne ikke sætte environment-variabel',
    'tag_created' => 'Tag oprettet af PHPCI: %s',
    'x_built_at_x' => '%PROJECT_TITLE% bygget på %BUILD_URI%',
    'hipchat_settings' => 'Angiv venligst rum og autoToken i hipchat_notify plugin',
    'irc_settings' => 'Du skal som minimum indstille en server, et rum og et nicknavn.',
    'invalid_command' => 'Ugyldig kommando',
    'import_file_key' => 'Importen skal indeholde en \'file\' variabel',
    'cannot_open_import' => 'Kunne ikke åbne SQL import-fil: %s',
    'unable_to_execute' => 'Kunne ikke udføre instruktionerne i SQL-filen',
    'phar_internal_error' => 'Phar Plugin Internal Error',
    'build_file_missing' => 'Den angivne build-fil findes ikke.',
    'property_file_missing' => 'Den angivne property-fil findes ikke',
    'could_not_process_report' => 'Kunne ikke behandle rapporten, som dette værktøj genererede.',
    'shell_not_enabled' => 'Shell-plugin er ikke aktiveret. Aktivér det via config.yml.'
);

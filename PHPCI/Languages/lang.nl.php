<?php
/**
 * PHPCI - Continuous Integration for PHP
 *
 * @copyright    Copyright 2014, Block 8 Limited.
 * @license      https://github.com/Block8/PHPCI/blob/master/LICENSE.md
 * @link         https://www.phptesting.org/
 */

$strings = array(
    'language_name' => 'Nederlands',
    'language' => 'Taal',

    // Log in:
    'log_in_to_phpci' => 'Log in op PHPCI',
    'login_error' => 'Incorrect e-mailadres of wachtwoord',
    'forgotten_password_link' => 'Wachtwoord vergeten?',
    'reset_emailed' => 'We hebben je een link gemaild om je wachtwoord opnieuw in te stellen.',
    'reset_header' => '<strong>Geen zorgen!</strong><br>Vul hieronder gewoon je e-mailadres in en we sturen
je een link on je wachtwoord te resetten.',
    'reset_email_address' => 'Vul je e-mailadres in:',
    'reset_send_email' => 'Verstuur wachtwoord reset',
    'reset_enter_password' => 'Gelieve een nieuw wachtwoord in te voeren',
    'reset_new_password' => 'Nieuw wachtwoord:',
    'reset_change_password' => 'Wijzig wachtwoord',
    'reset_no_user_exists' => 'Er bestaat geen gebruiker met dit e-mailadres, gelieve opnieuw te proberen.',
    'reset_email_body' => 'Hallo %s,

Je ontvangt deze email omdat jij, of iemand anders, je wachtwoord voor PHPCI opnieuw wenst in te stellen.

Indien jij dit was, klik op deze link op je wachtwoord opnieuw in te stellen: %ssession/reset-password/%d/%s

Zoniet, negeer deze e-mail en er zal geen verdere actie ondernomen worden.

Bedankt,

PHPCI',

    'reset_email_title' => 'PHPCI wachtwoord reset voor %s',
    'reset_invalid' => 'Ongeldig wachtwoord reset verzoek',
    'email_address' => 'E-mailadres',
    'password' => 'Wachtwoord',
    'log_in' => 'Log in',


    // Top Nav
    'toggle_navigation' => 'Wissel Navigatie',
    'n_builds_pending' => '%d builds wachtend',
    'n_builds_running' => '%d builds lopende',
    'edit_profile' => 'Wijzig profiel',
    'sign_out' => 'Uitloggen',
    'branch_x' => 'Branch: %s',
    'created_x' => 'Aangemaakt: %s',
    'started_x' => 'Gestart: %s',

    // Sidebar
    'hello_name' => 'Hallo, %s',
    'dashboard' => 'Startpagina',
    'admin_options' => 'Administratie opties',
    'add_project' => 'Project toevoegen',
    'settings' => 'Instellingen',
    'manage_users' => 'Gebruikers beheren',
    'plugins' => 'Plugins',
    'view' => 'Bekijk',
    'build_now' => 'Build nu',
    'edit_project' => 'Wijzig project',
    'delete_project' => 'Verwijder project',

    // Project Summary:
    'no_builds_yet' => 'Nog geen builds!',
    'x_of_x_failed' => '%d van de laatste %d builds faalden.',
    'x_of_x_failed_short' => '%d / %d faalden.',
    'last_successful_build' => 'De laatste succesvolle build was %s.',
    'never_built_successfully' => 'Dit project heeft geen succesvolle build gehad.',
    'all_builds_passed' => 'Elk van de laatste %d builds slaagden.',
    'all_builds_passed_short' => '%d / %d slaagden.',
    'last_failed_build' => 'De laatste gefaalde build was %s.',
    'never_failed_build' => 'Dit project heeft geen gefaalde build gehad.',
    'view_project' => 'Bekijk project',

    // Timeline:
    'latest_builds' => 'Laatste builds',
    'pending' => 'In afwachting',
    'running' => 'Lopende',
    'success' => 'Succes',
    'successful' => 'Succesvol',
    'failed' => 'Gefaald',
    'manual_build' => 'Manuele build',

    // Add/Edit Project:
    'new_project' => 'Nieuw project',
    'project_x_not_found' => 'Project met ID %d bestaat niet.',
    'project_details' => 'Project details',
    'public_key_help' => 'Om eenvoudiger te kunnen starten, hebben we een SSH sleutelpaar gegenereerd
voor dit project. Om het te gebruiken, voeg onderstaande public key toe aan de "deploy keys" sectie
van je gekozen source code hosting platform',
    'select_repository_type' => 'Selecteer repository type...',
    'github' => 'GitHub',
    'bitbucket' => 'Bitbucket',
    'gitlab' => 'GitLab',
    'remote' => 'Externe URL',
    'local' => 'Lokaal pad',
    'hg'    => 'Mercurial',

    'where_hosted' => 'Waar wordt je project gehost?',
    'choose_github' => 'Selecteer een GitHub repository:',

    'repo_name' => 'Repository naam / URL (extern) of pad (lokaal)',
    'project_title' => 'Projecttitel',
    'project_private_key' => 'Private key voor toegang tot repository
(laat leeg voor lokaal en/of anonieme externen)',
    'build_config' => 'PHPCI build configuratie voor dit project
(indien je geen phpci.yml bestand aan de project repository kan toevoegen)',
    'default_branch' => 'Standaard branch naam',
    'allow_public_status' => 'Publieke statuspagina en afbeelding beschikbaar maken voor dit project?',
    'save_project' => 'Project opslaan',

    'error_mercurial' => 'Mercurial repository URL dient te starten met http:// of https://',
    'error_remote' => 'Repository URL dient te starten met git://, http:// of https://',
    'error_gitlab' => 'GitLab repository naam dient in het formaat "gebruiker@domain.tld/eigenaar/repo.git" te zijn',
    'error_github' => 'Repository naam dient in het formaat "eigenaar/repo" te zijn',
    'error_bitbucket' => 'Repository naam dient in het formaat "eigenaar/repo" te zijn',
    'error_path' => 'Het opgegeven pad bestaat niet.',

    // View Project:
    'all_branches' => 'Alle brances',
    'builds' => 'Builds',
    'id' => 'ID',
    'project' => 'Project',
    'commit' => 'Commit',
    'branch' => 'Branch',
    'status' => 'Status',
    'prev_link' => '&laquo; Vorig',
    'next_link' => 'Volgend &raquo;',
    'public_key' => 'Public Key',
    'delete_build' => 'Verwijder build',

    'webhooks' => 'Webhooks',
    'webhooks_help_github' => 'Voor automatische builds wanneer nieuwe commits worden gepusht, dient onderstaande URL
als nieuwe "Webhook" in de <a href="https://github.com/%s/settings/hooks">Webhooks
and Services</a> sectie van je GitHub repository toegevoegd worden.',

    'webhooks_help_gitlab' => 'Voor automatische builds wanneer nieuwe commits worden gepusht, dient onderstaande URL
als nieuwe "Webhook URL" in de Web Hooks sectie van je GitLab repository toegevoegd worden.',

    'webhooks_help_bitbucket' => 'Voor automatische builds wanneer nieuwe commits worden gepusht, dient onderstaande URL
als "POST" service in de in de
<a href="https://bitbucket.org/%s/admin/services">
Services</a> sectie van je Bitbucket repository toegevoegd worden.',

    // View Build
    'build_x_not_found' => 'Build met ID %d bestaat niet.',
    'build_n' => 'Build %d',
    'rebuild_now' => 'Rebuild nu',


    'committed_by_x' => 'Committed door %s',
    'commit_id_x' => 'Commit: %s',

    'chart_display' => 'Deze grafiek wordt getoond zodra de build compleet is.',

    'build' => 'Build',
    'lines' => 'Lijnen',
    'comment_lines' => 'Commentaarlijnen',
    'noncomment_lines' => 'Niet-commentaarlijnen',
    'logical_lines' => 'Logische lijnen',
    'lines_of_code' => 'Lijnen code',
    'build_log' => 'Build Log',
    'quality_trend' => 'Kwaliteitstrend',
    'codeception_errors' => 'Codeception Fouten',
    'phpmd_warnings' => 'PHPMD Waarschuwingen',
    'phpcs_warnings' => 'PHPCS Waarschuwingen',
    'phpcs_errors' => 'PHPCS Fouten',
    'phplint_errors' => 'Lint Fouten',
    'phpunit_errors' => 'PHPUnit Fouten',
    'phpdoccheck_warnings' => 'Ontbrekende Docblocks',
    'issues' => 'Problemen',

    'codeception' => 'Codeception',
    'phpcpd' => 'PHP Copy/Paste Detector',
    'phpcs' => 'PHP Code Sniffer',
    'phpdoccheck' => 'Ontbrekende Docblocks',
    'phpmd' => 'PHP Mess Detector',
    'phpspec' => 'PHP Spec',
    'phpunit' => 'PHPUnit',

    'file' => 'Bestand',
    'line' => 'Lijn',
    'class' => 'Class',
    'method' => 'Method',
    'message' => 'Boodschap',
    'start' => 'Start',
    'end' => 'Einde',
    'from' => 'Van',
    'to' => 'Tot',
    'suite' => 'Suite',
    'test' => 'Test',
    'result' => 'Resultaat',
    'ok' => 'OK',
    'took_n_seconds' => 'Duurde %d seconden',
    'build_created' => 'Build aangemaakt',
    'build_started' => 'Build gestart',
    'build_finished' => 'Build beëindigd',

    // Users
    'name' => 'Naam',
    'password_change' => 'Wachtwoord (laat leeg indien je niet wenst te veranderen)',
    'save' => 'Opslaan &raquo;',
    'update_your_details' => 'Wijzig je gegevens',
    'your_details_updated' => 'Je gegevens werden gewijzigd',
    'add_user' => 'Gebruiker toevoegen',
    'is_admin' => 'Is administrator?',
    'yes' => 'Ja',
    'no' => 'Nee',
    'edit' => 'Wijzig',
    'edit_user' => 'Gebruiker wijzigen',
    'delete_user' => 'Gebruiker wissen',
    'user_n_not_found' => 'Gebruiker met ID %d bestaat niet.',
    'is_user_admin' => 'Is deze gebruiker administrator?',
    'save_user' => 'Gebruiker opslaan',

    // Settings:
    'settings_saved' => 'Je instellingen werden opgeslagen.',
    'settings_check_perms' => 'Je instellingen konden niet worden opgeslagen, controleer de permissies van je config.yml bestand.',
    'settings_cannot_write' => 'PHPCI kan niet schrijven naar je config.yml bestand, instellingen worden mogelijks
niet goed opgeslagen tot dit opgelost is.',
    'settings_github_linked' => 'Je GitHub account werd gelinkt.',
    'settings_github_not_linked' => 'Je GitHub account kon niet gelinkt worden.',
    'build_settings' => 'Build instellingen',
    'github_application' => 'GitHub toepassing',
    'github_sign_in' => 'Vooraleer je GitHub kan gebruiken, dien je <a href="%s">in te loggen</a> en
PHPCI toegang te verlenen tot je account.',
    'github_phpci_linked' => 'PHP werd succesvol gelinkt aan je GitHub account.',
    'github_where_to_find' => 'Waar zijn deze te vinden...',
    'github_where_help' => 'Indien je eigenaar bent van de toepassing die je wens te gebruiken, kan je deze informatie
in je <a href="https://github.com/settings/applications">applications</a> instellingen pagina vinden.',

    'email_settings' => 'E-mail instellingen',
    'email_settings_help' => 'Vooraleer PHPCI je build status e-mails kan sturen,
dien je eerst je SMTP instellingen te configureren.',

    'application_id' => 'Toepassings ID',
    'application_secret' => 'Toepassings geheime code',

    'smtp_server' => 'SMTP Server',
    'smtp_port' => 'SMTP Poort',
    'smtp_username' => 'SMTP Gebruikersnaam',
    'smtp_password' => 'SMTP Wachtwoord',
    'from_email_address' => 'Van e-mailadres',
    'default_notification_address' => 'Standaard melding e-mailadres',
    'use_smtp_encryption' => 'SMTP Encryptie gebruiken?',
    'none' => 'Geen',
    'ssl' => 'SSL',
    'tls' => 'TLS',

    'failed_after' => 'Beschouw een build gefaald na',
    '5_mins' => '5 minuten',
    '15_mins' => '15 minuten',
    '30_mins' => '30 minuten',
    '1_hour' => '1 uur',
    '3_hours' => '3 uur',

    // Plugins
    'cannot_update_composer' => 'PHPCI kan composer.json niet aanpassen gezien het niet schrijfbaar is.',
    'x_has_been_removed' => '%s werd verwijderd.',
    'x_has_been_added' => '%s werd toegevoegd aan composer.json en zal geïnstalleerd worden de volgende
keer je composer update uitvoert.',
    'enabled_plugins' => 'Ingeschakelde plugins',
    'provided_by_package' => 'Voorzien door package',
    'installed_packages' => 'Geinstalleerde packages',
    'suggested_packages' => 'Voorgestelde packages',
    'title' => 'Titel',
    'description' => 'Beschrijving',
    'version' => 'Versie',
    'install' => 'Installeer &raquo;',
    'remove' => 'Verwijder &raquo;',
    'search_packagist_for_more' => 'Doorzoek Packagist naar meer packages',
    'search' => 'Zoek &raquo;',

    // Installer
    'installation_url' => 'PHPCI installatie URL',
    'db_host' => 'Database host',
    'db_name' => 'Database naam',
    'db_user' => 'Database gebruikersnaam',
    'db_pass' => 'Database wachtwoord',
    'admin_name' => 'Administrator naam',
    'admin_pass' => 'Administrator wachtwoord',
    'admin_email' => 'Administrator e-mailadres',
    'config_path' => 'Pad naar configuratiebestand',
    'install_phpci' => 'Installeer PHPCI',
    'welcome_to_phpci' => 'Welkom bij PHPCI',
    'please_answer' => 'Gelieve onderstaande vragen te beantwoorden:',
    'phpci_php_req' => 'PHPCI heeft ten minste PHP 5.3.8 nodig om te werken.',
    'extension_required' => 'Extensie benodigd: %s',
    'function_required' => 'PHPCI moet functie %s() kunnen aanroepen. Is deze uitgeschakeld in php.ini?',
    'requirements_not_met' => 'PHPCI kan niet worden geïnstalleerd omdat niet aan alle vereisten is voldaan.
Gelieve de fouten na te kijken vooraleer verder te gaan.',
    'must_be_valid_email' => 'Moet een geldig e-mailadres zijn.',
    'must_be_valid_url' => 'Moet een geldige URL zijn.',
    'enter_name' => 'Administrator naam:',
    'enter_email' => 'Administrator e-mailadres:',
    'enter_password' => 'Administrator wachtwoord:',
    'enter_phpci_url' => 'Je PHPCI URL (bijvoorbeeld "http://phpci.local")',

    'enter_db_host' => 'Vul je MySQL host in [localhost]:',
    'enter_db_name' => 'Vul je MySQL databasenaam in [phpci]:',
    'enter_db_user' => 'Vul je MySQL gebruikersnaam in [phpci]:',
    'enter_db_pass' => 'Vul je MySQL watchtwoord in:',
    'could_not_connect' => 'PHPCI kon met deze gegevens geen verbinding maken met MySQL. Gelieve opnieuw te proberen.',
    'setting_up_db' => 'Database wordt aangemaakt...',
    'user_created' => 'Gebruikersprofiel aangemaakt!',
    'failed_to_create' => 'PHPCI kon je administratorprofiel niet aanmaken.',
    'config_exists' => 'Het PHPCI configuratiebestand bestaat en is niet leeg.',
    'update_instead' => 'Liever phpci:update te gebruiken indien je PHPCI probeerde te updaten, ',

    // Update
    'update_phpci' => 'Update de database naar het beeld van gewijzigde modellen.',
    'updating_phpci' => 'PHPCI database wordt geüpdatet:',
    'not_installed' => 'PHPCI lijkt niet geïnstalleerd te zijn.',
    'install_instead' => 'Gelieve PHPCI via phpci:install te installeren.',

    // Poll Command
    'poll_github' => 'Poll GitHub om te controleren of we een build moeten starten.',
    'no_token' => 'Geen GitHub token gevonden',
    'finding_projects' => 'Vind projecten om te pollen',
    'found_n_projects' => '%d projecten gevonden',
    'last_commit_is' => 'Laatste commit naar GitHub voor %s is %s',
    'adding_new_build' => 'Laatste commit verschilt van database, nieuwe build wordt toegevoegd',
    'finished_processing_builds' => 'Verwerking builds voltooid.',

    // Create Admin
    'create_admin_user' => 'Administrator-gebruiker aanmaken',
    'incorrect_format' => 'Incorrect formaat',

    // Run Command
    'run_all_pending' => 'Voer alle wachtende PHPCI builds uit.',
    'finding_builds' => 'Zoekt builds om te verwerken',
    'found_n_builds' => '%d builds gevonden',
    'skipping_build' => 'Build %d overslaan - Project build reeds aan de gang.',
    'marked_as_failed' => 'Build %d gemarkeerd als falende door timeout.',

    // Builder
    'missing_phpci_yml' => 'Dit project bevat geen phpci.yml bestand, of het is leeg.',
    'build_success' => 'BUILD SUCCES',
    'build_failed' => 'BUILD GEFAALD',
    'removing_build' => 'Build wordt verwijderd.',
    'exception' => 'Uitzondering:',
    'could_not_create_working' => 'Kon geen werkende kopie maken.',
    'working_copy_created' => 'Werkende kopie aangemaakt: %s',
    'looking_for_binary' => 'Zoekend naar binary: %s',
    'found_in_path' => 'Gevonden in %s: %s',
    'running_plugin' => 'UITVOEREN PLUGIN: %s',
    'plugin_success' => 'PLUGIN: SUCCES',
    'plugin_failed' => 'PLUGIN: GEFAALD',
    'plugin_missing' => 'Plugin bestaat niet: %s',
    'tap_version' => 'TapParser ondersteunt enkel TAP versie 13',
    'tap_error' => 'Ongeldige TAP string, aantal tests niet in overeenstemming met opgegeven aantal.',

    // Build Plugins:
    'no_tests_performed' => 'Er werden geen tests uitgevoerd.',
    'could_not_find' => 'Kon %s niet vinden',
    'no_campfire_settings' => 'Geen verbindingsparameters opgegeven voor Campfire plugin',
    'failed_to_wipe' => 'Kon bestaande map %s niet wissen voor kopie',
    'passing_build' => 'Slagende build',
    'failing_build' => 'Falende build',
    'log_output' => 'Log output:',
    'n_emails_sent' => '%d e-mails versuurd.',
    'n_emails_failed' => '%d e-mails faalden te versturen.',
    'unable_to_set_env' => 'Niet geslaagd om environment variable in te stellen',
    'tag_created' => 'Tag aangemaakt door PHPCI: %s',
    'x_built_at_x' => '%PROJECT_TITLE% built op %BUILD_URI%',
    'hipchat_settings' => 'Gelieve kamer & authToken voor hipchat_notify plugin te definiëren',
    'irc_settings' => 'Je dient server, kamer & nick op te geven.',
    'invalid_command' => 'Ongeldig commando',
    'import_file_key' => 'Import statement moet \'file\' key bevatten',
    'cannot_open_import' => 'Het is niet mogelijk om het SQL bestand %s te openen',
    'unable_to_execute' => 'Het is niet mogelijk om het SQL bestand uit te voeren',
    'phar_internal_error' => 'Er is iets fout gegaan in de Phar Plugin',
    'build_file_missing' => 'Opgegeven build bestand bestaat niet.',
    'property_file_missing' => 'Opgegeven bestand bestaat niet',
    'could_not_process_report' => 'Het is niet mogelijk om het gegenereerde rapport van deze tool te verwerken.',
    'shell_not_enabled' => 'De shell plugin is niet ingeschakeld, schakel deze a.u.b. in via het config.yml bestand.'
);

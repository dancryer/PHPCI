<?php
/**
 * PHPCI - Continuous Integration for PHP
 *
 * @copyright    Copyright 2014, Block 8 Limited.
 * @license      https://github.com/Block8/PHPCI/blob/master/LICENSE.md
 * @link         https://www.phptesting.org/
 */

$strings = array(
    'language_name' => 'Deutsch',
    'language' => 'Sprache',

    // Log in:
    'log_in_to_phpci' => 'In PHPCI einloggen',
    'login_error' => 'Fehlerhafte Emailadresse oder fehlerhaftes Passwort',
    'forgotten_password_link' => 'Passwort vergessen?',
    'reset_emailed' => 'Wir haben Ihnen einen Link geschickt, um Ihr Passwort zurückzusetzen',
    'reset_header' => '<strong>Keine Panik!</strong><br>Geben Sie einfach unten Ihre Emailadresse an
                        und wir senden Ihnen einen Link, um Ihr Passwort zurückzusetzen',
    'reset_email_address' => 'Geben Sie Ihre Emailadresse an:',
    'reset_send_email' => 'Link senden',
    'reset_enter_password' => 'Bitte geben Sie ein neues Passwort ein',
    'reset_new_password' => 'Neues Passwort:',
    'reset_change_password' => 'Passwort ändern',
    'reset_no_user_exists' => 'Es existiert kein User mit dieser Emailadresse, versuchen Sie es bitte noch einmal.',
    'reset_email_body' => 'Hallo %s,

Sie haben diese Email erhalten, weil Sie, oder jemand anders, einen Link zum Zurücksetzen Ihres Passwortes für PHPCI verlangt hat.

Wenn Sie diesen Link verlangt haben, klicken Sie bitte hier, um Ihr Passwort zurückzusetzen: %ssession/reset-password/%d/%s

Falls nicht, ignorieren Sie diese Email bitte, und es wird nichts geändert.

Danke,

PHPCI',

    'reset_email_title' => 'PHPCI Passwort zurücksetzen für %s',
    'reset_invalid' => 'Fehlerhafte Anfrage für das Zurücksetzen eines Passwortes',
    'email_address' => 'Emailadresse',
    'password' => 'Passwort',
    'log_in' => 'Einloggen',


    // Top Nav
    'toggle_navigation' => 'Navigation umschalten',
    'n_builds_pending' => '%d Builds ausstehend',
    'n_builds_running' => '%d Builds werden ausgeführt',
    'edit_profile' => 'Profil bearbeiten',
    'sign_out' => 'Ausloggen',
    'branch_x' => 'Branch: %s',
    'created_x' => 'Erstellt: %s',
    'started_x' => 'Gestartet: %s',

    // Sidebar
    'hello_name' => 'Hallo, %s',
    'dashboard' => 'Dashboard',
    'admin_options' => 'Administration',
    'add_project' => 'Projekt hinzufügen',
    'settings' => 'Einstellungen',
    'manage_users' => 'Benutzereinstellungen',
    'plugins' => 'Plugins',
    'view' => 'Ansehen',
    'build_now' => 'Jetzt bauen',
    'edit_project' => 'Projekt bearbeiten',
    'delete_project' => 'Projekt löschen',

    // Project Summary:
    'no_builds_yet' => 'Bisher noch keine Builds!',
    'x_of_x_failed' => '%d der letzten %d Builds sind fehlgeschlagen.',
    'x_of_x_failed_short' => '%d / %d fehlgeschlagen.',
    'last_successful_build' => ' Der letzte erfolgreiche Build war %s.',
    'never_built_successfully' => ' Dieses Projekt hatte bisher noch keinen erfolgreichen Build.',
    'all_builds_passed' => 'Jeder der letzten %d Builds war erfolgreich.',
    'all_builds_passed_short' => '%d / %d erfolgreich.',
    'last_failed_build' => ' Der letzte fehlgeschlagene Build war %s.',
    'never_failed_build' => ' Dieses Projekt hat keine fehlgeschlagenen Builds.',
    'view_project' => 'Projekt ansehen',

    // Timeline:
    'latest_builds' => 'Die neusten Builds',
    'pending' => 'Ausstehend',
    'running' => 'Wird ausgeführt',
    'success' => 'Erfolg',
    'successful' => 'Erfolgreich',
    'failed' => 'Fehlgeschlagen',
    'manual_build' => 'Manueller Build',

    // Add/Edit Project:
    'new_project' => 'Neues Projekt',
    'project_x_not_found' => 'Projekt mit ID %d existiert nicht.',
    'project_details' => 'Projektdetails',
    'public_key_help' => 'Um Ihnen den Einstieg zu erleichtern, haben wir ein SSH-Key-Paar für dieses Projekt
generiert. Um es zu verwenden, fügen Sie einfach den folgenden Public Key im Abschnitt
"Deploy Keys" Ihrer bevorzugten Quellcodehostingplattform hinzu.',
    'select_repository_type' => 'Wählen Sie den Typ des Repositories...',
    'github' => 'GitHub',
    'bitbucket' => 'Bitbucket',
    'gitlab' => 'GitLab',
    'remote' => 'Externe URL',
    'local' => 'Lokaler Pfad',
    'hg'    => 'Mercurial',

    'where_hosted' => 'Wo wird Ihr Projekt gehostet?',
    'choose_github' => 'Wählen Sie ein GitHub Repository:',

    'repo_name' => 'Name/URL (extern) oder Pfad (lokal) des Repositories',
    'project_title' => 'Projekttitel',
    'project_private_key' => 'Private Key für den Zugang zum Repository
                                (leer lassen für lokale und oder anonyme externe Zugriffe)',
    'build_config' => 'PHPCI Buildkonfiguration für dieses Projekt
                                (falls Sie Ihrem Projektrepository kein phpci.yml hinzufügen können)',
    'default_branch' => 'Name des Standardbranches',
    'allow_public_status' => 'Öffentliche Statusseite und -bild für dieses Projekt einschalten?',
    'save_project' => 'Projekt speichern',

    'error_mercurial' => 'Mercurial Repository-URL muss mit http://, oder https:// beginnen',
    'error_remote' => 'Repository-URL muss mit git://, http://, oder https:// beginnen',
    'error_gitlab' => 'GitLab Repositoryname muss im Format "user@domain.tld:owner/repo.git" sein',
    'error_github' => 'Repositoryname muss im Format "besitzer/repo" sein',
    'error_bitbucket' => 'Repositoryname muss im Format "besitzer/repo" sein',
    'error_path' => 'Der angegebene Pfad existiert nicht',

    // View Project:
    'all_branches' => 'Alle Branches',
    'builds' => 'Builds',
    'id' => 'ID',
    'project' => 'Projekt',
    'commit' => 'Commit',
    'branch' => 'Branch',
    'status' => 'Status',
    'prev_link' => '&laquo; Vorherige',
    'next_link' => 'Nächste &raquo;',
    'public_key' => 'Public Key',
    'delete_build' => 'Build löschen',

    'webhooks' => 'Webhooks',
    'webhooks_help_github' => 'Um für dieses Projekt automatisch einen Build zu starten, wenn neue Commits gepushed
                                werden, fügen Sie die untenstehende URL in der
                                <a href="https://github.com/%s/settings/hooks">Webhooks and Services</a>-Sektion Ihres
                                GitHub Repositories als neuen "Webhook" hinzu.',

    'webhooks_help_gitlab' => 'Um für dieses Projekt automatisch einen Build zu starten, wenn neue Commits gepushed werden, fügen Sie die untenstehende URL in der Web Hooks Sektion Ihres GitLab Repositories hinzu.',

    'webhooks_help_bitbucket' => 'Um für dieses Projekt automatisch einen Build zu starten, wenn neue Commits gepushed werden, fügen Sie die untenstehende URL als "POST" Service in der <a href="https://bitbucket.org/%s/admin/services">Services</a>-Sektion Ihres Bitbucket Repositories hinzu.',

    // View Build
    'build_x_not_found' => 'Build mit ID %d existiert nicht.',
    'build_n' => 'Build %d',
    'rebuild_now' => 'Build neu starten',


    'committed_by_x' => 'Committed von %s',
    'commit_id_x' => 'Commit: %s',

    'chart_display' => 'Dieses Diagramm wird angezeigt, sobald der Build abgeschlossen ist.',

    'build' => 'Build',
    'lines' => 'Zeilen',
    'comment_lines' => 'Kommentarzeilen',
    'noncomment_lines' => 'Nicht-Kommentarzeilen',
    'logical_lines' => 'Zeilen mit Logik',
    'lines_of_code' => 'Anzahl Codezeilen',
    'build_log' => 'Buildprotokoll',
    'quality_trend' => 'Qualitätstrend',
    'codeception_errors' => 'Codeception Errors',
    'phpmd_warnings' => 'PHPMD Warnings',
    'phpcs_warnings' => 'PHPCS Warnings',
    'phpcs_errors' => 'PHPCS Errors',
    'phplint_errors' => 'Lint Errors',
    'phpunit_errors' => 'PHPUnit Errors',
    'phpdoccheck_warnings' => 'Fehlende Docblocks',
    'issues' => 'Probleme',

    'codeception' => 'Codeception',
    'phpcpd' => 'PHP Copy/Paste Detector',
    'phpcs' => 'PHP Code Sniffer',
    'phpdoccheck' => 'Fehlende Docblocks',
    'phpmd' => 'PHP Mess Detector',
    'phpspec' => 'PHP Spec',
    'phpunit' => 'PHP Unit',

    'file' => 'Datei',
    'line' => 'Zeile',
    'class' => 'Klasse',
    'method' => 'Methode',
    'message' => 'Nachricht',
    'start' => 'Start',
    'end' => 'Ende',
    'from' => 'Von',
    'to' => 'Bis',
    'suite' => 'Suite',
    'test' => 'Test',
    'result' => 'Resultat',
    'ok' => 'OK',
    'took_n_seconds' => 'Benötigte %d Sekunden',
    'build_created' => 'Build erstellt',
    'build_started' => 'Build gestartet',
    'build_finished' => 'Build abgeschlossen',

    // Users
    'name' => 'Name',
    'password_change' => 'Passwort (leerlassen, wenn Sie es nicht ändern möchten)',
    'save' => 'Speichern &raquo;',
    'update_your_details' => 'Aktualisieren Sie Ihre Details',
    'your_details_updated' => 'Ihre Details wurden aktualisiert.',
    'add_user' => 'Benutzer hinzufügen',
    'is_admin' => 'Administrator?',
    'yes' => 'Ja',
    'no' => 'Nein',
    'edit' => 'Bearbeiten',
    'edit_user' => 'Benutzer bearbeiten',
    'delete_user' => 'Benutzer löschen',
    'user_n_not_found' => 'Benutzer mit ID %d existiert nicht.',
    'is_user_admin' => 'Ist dieser Benutzer Administrator?',
    'save_user' => 'Benutzer speichern',

    // Settings:
    'settings_saved' => 'Ihre Einstellungen wurden gespeichert.',
    'settings_check_perms' => 'Ihre Einstellungen konnten nicht gespeichert werden, bitte überprüfen Sie die
                                Berechtigungen Ihrer config.yml-Datei',
    'settings_cannot_write' => 'PHPCI konnte config.yml nicht schreiben. Einstellungen könnten nicht richtig gespeichert werden, bis das Problem behoben ist.',
    'settings_github_linked' => 'Ihr GitHub-Konto wurde verknüpft.',
    'settings_github_not_linked' => 'Ihr GitHub-Konto konnte nicht verknüpft werden.',
    'build_settings' => 'Buildeinstellungen',
    'github_application' => 'GitHub-Applikation',
    'github_sign_in' => 'Bevor Sie anfangen GitHub zu verwenden, müssen Sie sich erst <a href="%s">einloggen</a> und PHPCI Zugriff auf Ihr Nutzerkonto gewähren',
    'github_phpci_linked' => 'PHPCI wurde erfolgreich mit Ihrem GitHub-Konto verknüpft.',
    'github_where_to_find' => 'Wo Sie diese finden...',
    'github_where_help' => 'Wenn Sie der Besitzer der Applikation sind, die Sie gerne verwenden möchten, können Sie
                            diese Einstellungen in Ihrem "<a href="https://github.com/settings/applications">applications</a>
                            settings"-Bereich finden.',

    'email_settings' => 'Emaileinstellungen',
    'email_settings_help' => 'Bevor PHPCI E-Mails zum Buildstatus verschicken kann,
                                müssen Sie Ihre SMTP-Einstellungen unten konfigurieren',

    'application_id' => 'Applikations-ID',
    'application_secret' => 'Applikations-Secret',

    'smtp_server' => 'SMTP Server',
    'smtp_port' => 'SMTP Port',
    'smtp_username' => 'SMTP Benutzername',
    'smtp_password' => 'SMTP Passwort',
    'from_email_address' => 'Absenderadresse',
    'default_notification_address' => 'Standardadresse für Benachrichtigungen',
    'use_smtp_encryption' => 'SMTP-Verschlüsselung verwenden?',
    'none' => 'Keine',
    'ssl' => 'SSL',
    'tls' => 'TLS',

    'failed_after' => 'Einen Build als fehlgeschlagen ansehen nach',
    '5_mins' => '5 Minuten',
    '15_mins' => '15 Minuten',
    '30_mins' => '30 Minuten',
    '1_hour' => '1 Stunde',
    '3_hours' => '3 Stunden',

    // Plugins
    'cannot_update_composer' => 'PHPCI kann composer.json nicht für Sie aktualisieren, da Schreibrechte benötigt werden.',
    'x_has_been_removed' => '%s wurde entfernt.',
    'x_has_been_added' => '%s wurde für Sie dem composer.json hinzugefügt und wird installiert, sobald Sie das nächste mal composer update ausführen.',
    'enabled_plugins' => 'Eingeschaltene Plugins',
    'provided_by_package' => 'Von Package bereitgestellt',
    'installed_packages' => 'Installierte Packages',
    'suggested_packages' => 'Vorgeschlagene Packages',
    'title' => 'Titel',
    'description' => 'Beschreibung',
    'version' => 'Version',
    'install' => 'Installieren &raquo;',
    'remove' => 'Entfernen &raquo;',
    'search_packagist_for_more' => 'Packagist nach mehr Packages durchsuchen',
    'search' => 'Suchen &raquo;',

    // Installer
    'installation_url' => 'PHPCI Installations-URL',
    'db_host' => 'Datenbankserver',
    'db_name' => 'Datenbankname',
    'db_user' => 'Datenbankbenutzer',
    'db_pass' => 'Datenbankpasswort',
    'admin_name' => 'Administratorname',
    'admin_pass' => 'Administratorpasswort',
    'admin_email' => 'Emailadresse des Administrators',
    'config_path' => 'Dateipfad für Konfiguration',
    'install_phpci' => 'PHPCI installieren',
    'welcome_to_phpci' => 'Willkommen bei PHPCI',
    'please_answer' => 'Bitte beantworten Sie die folgenden Fragen:',
    'phpci_php_req' => 'PHPCI benötigt mindestens PHP 5.3.8 um zu funktionieren.',
    'extension_required' => 'Benötigte Extensions: %s',
    'function_required' => 'PHPCI muss die Funktion %s() aufrufen können. Ist sie in php.ini deaktiviert?',
    'requirements_not_met' => 'PHPCI konnte nicht installiert werden, weil nicht alle Bedingungen erfüllt sind.
                                Bitte überprüfen Sie die Fehler, bevor Sie fortfahren,',
    'must_be_valid_email' => 'Muss eine gültige Emailadresse sein.',
    'must_be_valid_url' => 'Muss eine valide URL sein.',
    'enter_name' => 'Name des Administrators:',
    'enter_email' => 'Emailadresse des Administrators:',
    'enter_password' => 'Passwort des Administrators:',
    'enter_phpci_url' => 'Ihre PHPCI-URL (z.B. "http://phpci.local"): ',

    'enter_db_host' => 'Bitte geben Sie Ihren MySQL-Host ein [localhost]: ',
    'enter_db_name' => 'Bitte geben Sie Ihren MySQL-Namen ein [phpci]: ',
    'enter_db_user' => 'Bitte geben Sie Ihren MySQL-Benutzernamen ein [phpci]: ',
    'enter_db_pass' => 'Bitte geben Sie Ihr MySQL-Passwort ein: ',
    'could_not_connect' => 'PHPCI konnte wegen folgender Details nicht mit MySQL verbinden. Bitte versuchen Sie es erneut.',
    'setting_up_db' => 'Ihre Datenbank wird aufgesetzt... ',
    'user_created' => 'Benutzerkonto wurde erstellt!',
    'failed_to_create' => 'PHPCI konnte Ihr Administratorenkonto nicht erstellen.',
    'config_exists' => 'Die PHPCI-Konfigurationsdatei existiert und ist nicht leer..',
    'update_instead' => 'Falls Sie versucht haben PHPCI zu aktualisieren, benutzen Sie bitte stattdessen phpci:update.',

    // Update
    'update_phpci' => 'Datenbank wird aktualisiert, um den Änderungen der Models zu entsprechen.',
    'updating_phpci' => 'Aktualisiere PHPCI-Datenbank:',
    'not_installed' => 'PHPCI scheint nicht installiert zu sein.',
    'install_instead' => 'Bitte installieren Sie PHPCI stattdessen via phpci:install.',

    // Poll Command
    'poll_github' => 'GitHub abfragen, um herauszufinden, ob ein Build gestartet werden muss.',
    'no_token' => 'Kein GitHub-Token gefunden',
    'finding_projects' => 'Suche Projekte, um diese abzufragen',
    'found_n_projects' => '%d Projekte gefunden',
    'last_commit_is' => 'Der letzte Commit zu GitHub für %s ist %s',
    'adding_new_build' => 'Letzter Commit unterscheidet sich von der Datenbank, füge neuen Build hinzu.',
    'finished_processing_builds' => 'Bearbeiten der Builds abgeschlossen.',

    // Create Admin
    'create_admin_user' => 'Administratorenbenutzer erstellen',
    'incorrect_format' => 'Falsches Format',

    // Run Command
    'run_all_pending' => 'Führe alle ausstehenden PHPCI Builds aus.',
    'finding_builds' => 'Suche verarbeitbare Builds',
    'found_n_builds' => '%d Builds gefunden',
    'skipping_build' => 'Überspringe Build %d - Es wird bereits ein Build auf diesem Projekt ausgeführt.',
    'marked_as_failed' => 'Build %d wegen Zeitüberschreitung als fehlgeschlagen markiert.',

    // Builder
    'missing_phpci_yml' => 'Dieses Projekt beinhaltet keine phpci.yml-Datei, oder sie ist leer.',
    'build_success' => 'BUILD ERFOLGREICH',
    'build_failed' => 'BUILD FEHLGESCHLAGEN',
    'removing_build' => 'Entferne Build.',
    'exception' => 'Exception: ',
    'could_not_create_working' => 'Konnte keine Arbeitskopie erstellen.',
    'working_copy_created' => 'Arbeitskopie erstellt: %s',
    'looking_for_binary' => 'Suche Binärdatei: %s',
    'found_in_path' => 'Gefunden in %s: %s',
    'running_plugin' => 'AUSGEFÜHRTES PLUGIN: %s',
    'plugin_success' => 'PLUGIN: ERFOLGREICH',
    'plugin_failed' => 'PLUGIN: FEHLGECHLAGEN',
    'plugin_missing' => 'Plugin existiert nicht: %s',
    'tap_version' => 'TapParser unterstützt nur TAP version 13',
    'tap_error' => 'Ungültiger TAP String, Anzahl Tests entspricht nicht angegebener Testzahl.',

    // Build Plugins:
    'no_tests_performed' => 'Keine Tests wurden ausgeführt.',
    'could_not_find' => '%s wurde nicht gefunden',
    'no_campfire_settings' => 'Keine Verbindungsparameter für das Campfire plugin gefunden',
    'failed_to_wipe' => 'Konnte Ordner %s nicht vor dem Kopieren leeren',
    'passing_build' => 'Durchlaufender Build',
    'failing_build' => 'Fehlschlagender Build',
    'log_output' => 'Protokollausgabe: ',
    'n_emails_sent' => '%d Emails verschickt.',
    'n_emails_failed' => 'Konnte %d Emails nicht verschicken.',
    'unable_to_set_env' => 'Konnte Umgebungsvariable nicht setzen',
    'tag_created' => 'Tag erstellt durch PHPCI: %s',
    'x_built_at_x' => '%PROJECT_TITLE% gebuildet auf %BUILD_URI%',
    'hipchat_settings' => 'Bitte definieren Sie Room und AuthToken für das hipchat_notify-Plugin',
    'irc_settings' => 'Sie müssen einen Server, Room und Nick definieren.',
    'invalid_command' => 'Ungültiges Kommando',
    'import_file_key' => 'Import-Statements müssen einen \'file\'-Key enthalten',
    'cannot_open_import' => 'Konnte SQL-Importdatei nicht öffnen: %s',
    'unable_to_execute' => 'Konnte SQL-Datei nicht ausführen',
    'phar_internal_error' => 'Phar Plugin Interner Fehler',
    'build_file_missing' => 'Angegebene Builddatei existiert nicht.',
    'property_file_missing' => 'Angegebene Eigenschaftsdatei existiert nicht.',
    'could_not_process_report' => 'Konnte den von diesem Tool erstellten Bericht nicht verarbeiten.',
    'shell_not_enabled' => 'Das Shell-Plugin ist nicht aktiviert. Bitte aktivieren Sie es via config.yml.'
);

<?php
/**
 * PHPCI - Continuous Integration for PHP
 *
 * @copyright    Copyright 2014, Block 8 Limited.
 * @license      https://github.com/Block8/PHPCI/blob/master/LICENSE.md
 * @link         https://www.phptesting.org/
 */

$strings = array(

    'language_name' => 'Italiano',
    'language' => 'Lingua',

    // Log in:
    'log_in_to_phpci' => 'Accedi a PHPCI',
    'login_error' => 'Indirizzo email o password errati',
    'forgotten_password_link' => 'Hai dimenticato la tua password?',
    'reset_emailed' => 'Ti abbiamo inviato un link via email per ripristinare la tua password.',
    'reset_header' => '<strong>Non preoccuparti!</strong><br>E\' sufficiente inserire il tuo indirizzo email di seguito e ti invieremo una email con il link per il ripristino della tua password.',
    'reset_email_address' => 'Inserisci il tuo indirizzo email:',
    'reset_send_email' => 'Invia il link di reset della password',
    'reset_enter_password' => 'Per favore inserisci la nuova password',
    'reset_new_password' => 'Nuova password:',
    'reset_change_password' => 'Cambia password',
    'reset_no_user_exists' => 'Non esiste nessun utente con questo indirizzo email, per favore prova ancora.',
    'reset_email_body' => 'Ciao %s,

hai ricevuto questa email perché tu, o qualcun\'altro, ha richiesto un reset della password per PHPCI.

Se questa mail è tua, per favore apri il seguente link per ripristinare la tua password: %ssession/reset-password/%d/%s

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
    'github' => 'GitHub',
    'bitbucket' => 'Bitbucket',
    'gitlab' => 'GitLab',
    'remote' => 'URL Remoto',
    'local' => 'Percorso Locale',
    'hg'    => 'Mercurial',

    'where_hosted' => 'Dove è archiviato il tuo progetto?',
    'choose_github' => 'Scegli il repository di GitHub:',

    'repo_name' => 'Nome del Repository / URL (Remoto) o Percorso (Locale)',
    'project_title' => 'Titolo del Progetto',
    'project_private_key' => 'Chiave provata da usare per accedere al repository
                                (lascia vuota per repository locali o remoti con accesso anonimo)',
    'build_config' => 'condigurazione della build di PHPCI per questo progetto
                                (se non puoi aggiungere il file phpci.yml nel repository di questo progetto)',
    'default_branch' => 'Nome del branch di default',
    'allow_public_status' => 'Vuoi rendere pubblica la pagina dello stato e l\'immagine per questo progetto?',
    'save_project' => 'Salva il Progetto',

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
    'webhooks_help_github' => 'Per effettuare la build automatica di questo progetto quando vengono inseriti nuovi commit,
                                aggiungi l\'URL seguente come "Webhook" nella sezione
                                <a href="https://github.com/%s/settings/hooks">Webhooks and Services</a> del tuo
                                repository su GitHub.',

    'webhooks_help_gitlab' => 'Per effettuare la build automatica di questo progetto quando vengono inseriti nuovi commit,
                                aggiungi l\'URL seguente come "Webhook URL" nella sezione "WebHook URL" del tuo
                                repository GitLab.',

    'webhooks_help_bitbucket' => 'Per effettuare la build automatica di questo progetto quando vengono inseriti nuovi
                                    commit, aggiungi l\'URL seguente come serizio "POST" nella sezione
                                    <a href="https://bitbucket.org/%s/admin/services">Services</a> del tuo repository su
                                    BITBUCKET.',

    // View Build
    'build_x_not_found' => 'La build con ID %d non esite.',
    'build_n' => 'Build %d',
    'rebuild_now' => 'Esegui nuovamente la build ora',


    'committed_by_x' => 'Inviato da %s',
    'commit_id_x' => 'Commit: %s',

    'chart_display' => 'Questo grafico verrà mostrato una volta terminata la build.',

    'build' => 'Build',
    'lines' => 'Linee',
    'comment_lines' => 'Linee di commenti',
    'noncomment_lines' => 'Linee che non sono commenti',
    'logical_lines' => 'Linee di logica',
    'lines_of_code' => 'Linee di codice',
    'build_log' => 'Log della build',
    'quality_trend' => 'Trend della qualità',
    'codeception_errors' => 'Errori di Codeception',
    'phpmd_warnings' => 'Avvisi di PHPMD',
    'phpcs_warnings' => 'Avvisi di PHPCS',
    'phpcs_errors' => 'Errori di PHPCS',
    'phplint_errors' => 'Errori di Lint',
    'phpunit_errors' => 'Errori di PHPUnit',
    'phpdoccheck_warnings' => 'Docblocks mancanti',
    'issues' => 'Segnalazioni',

    'codeception' => 'Codeception',
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
    'settings_github_linked' => 'Il tuo account GitHub è stato collegato.',
    'settings_github_not_linked' => 'Il tuo account GitHub non può essere collegato.',
    'build_settings' => 'Configurazioni della build',
    'github_application' => 'Applicazione GitHub',
    'github_sign_in' => 'Prima di poter iniziare ad usare GitHub, è necessario <a href="%s">collegarsi</a> e garantire
                            a PHPCI l\'accesso al tuo account.',
    'github_phpci_linked' => 'PHPCI è stato collegato correttamente al tuo account GitHub.',
    'github_where_to_find' => 'Dove trovare queste...',
    'github_where_help' => 'Se sei il proprietario dell\'applicazione, puoi trovare queste informazioni nell\'area delle
                              configurazioni dell\'<a href="https://github.com/settings/applications">applicazione</a>.',

    'email_settings' => 'Impostazioni Email',
    'email_settings_help' => 'Prima che possa inviare le email con lo status PHPCI, devi configurare l\'SMTP qui sotto.',

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
    'cannot_update_composer' => 'PHPCI non può aggiornare composer.json per te non essendo scrivibile.',
    'x_has_been_removed' => '%s è stato rimosso.',
    'x_has_been_added' => '%s è stato aggiunto al file composer.json per te, verrà installato la prossima volta che eseguirai
                            composer update.',
    'enabled_plugins' => 'Plugins attivati',
    'provided_by_package' => 'Fornito dal pacchetto',
    'installed_packages' => 'Pacchetti installati',
    'suggested_packages' => 'Paccehtti suggeriti',
    'title' => 'Titolo',
    'description' => 'Descrizione',
    'version' => 'Versione',
    'install' => 'Installa &raquo;',
    'remove' => 'Rimuovi &raquo;',
    'search_packagist_for_more' => 'Cerca altri pacchetti su Packagist',
    'search' => 'Cerca &raquo;',

    // Installer
    'installation_url' => 'URL di installazione di PHPCI',
    'db_host' => 'Host del Database',
    'db_name' => 'Nome del Database',
    'db_user' => 'Username del Database',
    'db_pass' => 'Password del Database',
    'admin_name' => 'Nome dell\'amministratore',
    'admin_pass' => 'Password dell\'amministratore',
    'admin_email' => 'Email dell\'amministratore',
    'config_path' => 'Percorso del file di configurazione',
    'install_phpci' => 'Installa PHPCI',
    'welcome_to_phpci' => 'Benvenuto in PHPCI',
    'please_answer' => 'Per favore rispondi alle seguenti domande:',
    'phpci_php_req' => 'PHPCI richiede come minimo PHP 5.3.8 per funzionare.',
    'extension_required' => 'Le estensioni richieste sono: %s',
    'function_required' => 'PHPCI richiede di poter chiamare la funzione %s(). Questa funzionalità è disabibiltata nel
                              php.ini?',
    'requirements_not_met' => 'PHPCI non può essere installato, non tutti i requisiti sono soddisfatti.
                                Per favore controlla gli errori riportati prima di proseguire.',
    'must_be_valid_email' => 'Deve essere un indirizzo email valido.',
    'must_be_valid_url' => 'Deve essere un URL valido.',
    'enter_name' => 'Nome dell\'amministratore:',
    'enter_email' => 'Email dell\'amministratore:',
    'enter_password' => 'Password dell\'amministratore:',
    'enter_phpci_url' => 'L\'URL di PHPCI ("http://phpci.locale" ad esempio): ',

    'enter_db_host' => 'Per favore inserisci l\'host MySQL [localhost]: ',
    'enter_db_name' => 'Per favore inserisci il nome MySQL [phpci]: ',
    'enter_db_user' => 'Per favore inserisci l\'username MySQL [phpci]: ',
    'enter_db_pass' => 'Per favore inserisci la password MySQL: ',
    'could_not_connect' => 'PHPCI non può connettersi a MySQL con le informazioni fornite. Per favore prova ancora.',
    'setting_up_db' => 'Configurzione del tuo database... ',
    'user_created' => 'Account utente creato!',
    'failed_to_create' => 'PHPCI non è riuscito a creare il tuo account amministrativo.',
    'config_exists' => 'Il file di configurazione di PHPCI esiste e non è vuoto.',
    'update_instead' => 'Se stai cercando di aggiornare PHPCI, per favore usa phpci:update.',

    // Update
    'update_phpci' => 'Aggiorna il database per riflettere le modifiche ai model.',
    'updating_phpci' => 'Aggiornamenti del database di PHPCI: ',
    'not_installed' => 'PHPCI sembra non essere installato.',
    'install_instead' => 'Per favore installa PHPCI tramite phpci:install.',

    // Poll Command
    'poll_github' => 'Richiesta a GitHub per verificare se è necessario avviare una build.',
    'no_token' => 'Nessuno token per GitHub trovato',
    'finding_projects' => 'Ricerca dei progetti da aggiornare',
    'found_n_projects' => 'Trovati %d progetti',
    'last_commit_is' => 'Ultimo commit su GitHub per %s è %s',
    'adding_new_build' => 'L\'ultimo commit è diverso da quello registrato, new build aggiunta.',
    'finished_processing_builds' => 'Terminato di processare le build.',

    // Create Admin
    'create_admin_user' => 'Crea un nuovo utente amministrarore',
    'incorrect_format' => 'Formato errato',

    // Run Command
    'run_all_pending' => 'Esegui tutte le build in attesa su PHPCI.',
    'finding_builds' => 'Ricerca delel build da processare',
    'found_n_builds' => 'Trovate %d build',
    'skipping_build' => 'Saltata la build %d - La build del progetto è già in corso.',
    'marked_as_failed' => 'Build %d è stata contrassegnata come fallita per un timeout.',

    // Builder
    'missing_phpci_yml' => 'Questo progetto non contiene il file phpci.yml, o il file è vuoto.',
    'build_success' => 'BUILD PASSATA',
    'build_failed' => 'BUILD FALLITA',
    'removing_build' => 'Rimozione build.',
    'exception' => 'Eccezione: ',
    'could_not_create_working' => 'Non può essere creata una copia di lavoro.',
    'working_copy_created' => 'Copia di lavoro creata: %s',
    'looking_for_binary' => 'Ricerca per il binario: %s',
    'found_in_path' => 'Trovato in %s: %s',
    'running_plugin' => 'PLUGIN IN ESECUZIONE: %s',
    'plugin_success' => 'PLUGIN: PASSATO',
    'plugin_failed' => 'PLUGIN: FALLITO',
    'plugin_missing' => 'Plugin non esistente: %s',
    'tap_version' => 'TapParser supporta solo la versione TAP 13',
    'tap_error' => 'Stringa TAP non valida, il numero dei test non corrisponde con il numero di test contati.',

    // Build Plugins:
    'no_tests_performed' => 'Nessun test è stato eseguito.',
    'could_not_find' => 'Non posso trovare %s',
    'no_campfire_settings' => 'Nessun parametro di connessione trovato per il plugin Campfire.',
    'failed_to_wipe' => 'Errore nel pulire la cartella %s prima di effettuare la copia',
    'passing_build' => 'Build passata',
    'failing_build' => 'Build fallita',
    'log_output' => 'Log: ',
    'n_emails_sent' => '%d email inviate.',
    'n_emails_failed' => '%d email da inviare fallite.',
    'unable_to_set_env' => 'Errore nel settare la variabile di ambiente',
    'tag_created' => 'Tag creato da PHPCI: %s',
    'x_built_at_x' => '%PROJECT_TITLE% buildato in %BUILD_URI%',
    'hipchat_settings' => 'Per favore definire la stanza e authToken per il plugin hipchat_notify',
    'irc_settings' => 'Devi configurare server, stanza e nick.',
    'invalid_command' => 'Comando non valido',
    'import_file_key' => 'L\'import deve contenrere la chiave \'file\'',
    'cannot_open_import' => 'Impossobile aprire il file SQL da importare: %s',
    'unable_to_execute' => 'Impossibile eseguire il file SQL',
    'phar_internal_error' => 'Errore interno del plugin Phar',
    'build_file_missing' => 'Il file di build specificato non esiste.',
    'property_file_missing' => 'Il file di proprietà specificato non esiste.',
    'could_not_process_report' => 'Non è possibile processare il report generato da questo tool.',
    'shell_not_enabled' => 'Il plugin shell non è attivato. Per favore attivalo tramite config.yml.'
);

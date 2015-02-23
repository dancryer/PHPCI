<?php
/**
 * PHPCI - Continuous Integration for PHP
 *
 * @copyright    Copyright 2014, Block 8 Limited.
 * @license      https://github.com/Block8/PHPCI/blob/master/LICENSE.md
 * @link         https://www.phptesting.org/
 */

$strings = array(
    'language_name' => 'Polski',
    'language' => 'Język',

    // Log in:
    'log_in_to_phpci' => 'Zaloguj się do PHPCI',
    'login_error' => 'Nieprawidłowy email lub hasło',
    'forgotten_password_link' => 'Zapomniałeś hasła?',
    'reset_emailed' => 'Email z linkiem resetującym hasło został wysłany.',
    'reset_header' => '<strong>Spokojnie!</strong><br>Wpisz swój adres email w polu poniżej a my wyślemy Ci link
resetujący hasło.',
    'reset_email_address' => 'Podaj swój adres email:',
    'reset_send_email' => 'Wyślij reset hasła emailem',
    'reset_enter_password' => 'Wpisz nowe hasło',
    'reset_new_password' => 'Nowe hasło:',
    'reset_change_password' => 'Zmień hasło',
    'reset_no_user_exists' => 'Użytkownik o takim emailu nie istnieje. Spróbuj jeszcze raz.',
    'reset_email_body' => 'Witaj %s,

Otrzymałeś ten email ponieważ Ty, lub ktoś inny, wysłał prośbę o zmianę hasła w PHPCI.

Jeśli to faktycznie Ty, kliknij w następujący link aby zresetować hasło: %ssession/reset-password/%d/%s

Jeśli nie, zignoruj tego emaila i wszystko pozostanie bez zmian,

Pozdrawiamy,

PHPCI',

    'reset_email_title' => 'Reset Hasła PHPCI dla %s',
    'reset_invalid' => 'Prośba o zmianę hasła jest nieważna.',
    'email_address' => 'Adres email',
    'password' => 'Hasło',
    'log_in' => 'Zaloguj się',


    // Top Nav
    'toggle_navigation' => 'Otwórz/zamknij nawigację',
    'n_builds_pending' => '%d budowań w kolejce',
    'n_builds_running' => '%d budowań w toku',
    'edit_profile' => 'Edytuj Profil',
    'sign_out' => 'Wyloguj się',
    'branch_x' => 'Gałąź: %s',
    'created_x' => 'Utworzono: %s',
    'started_x' => 'Rozpoczęto: %s',

    // Sidebar
    'hello_name' => 'Witaj, %s',
    'dashboard' => 'Panel administracyjny',
    'admin_options' => 'Opcje Administratora',
    'add_project' => 'Dodaj Projekt',
    'settings' => 'Ustawienia',
    'manage_users' => 'Zarządaj Uzytkownikami',
    'plugins' => 'Pluginy',
    'view' => 'Podgląd',
    'build_now' => 'Zbuduj',
    'edit_project' => 'Edytuj Projekt',
    'delete_project' => 'Usuń Projekt',

    // Project Summary:
    'no_builds_yet' => 'Brak budowań!',
    'x_of_x_failed' => '%d z ostatnich %d budowań nie powiodło się',
    'x_of_x_failed_short' => '%d / %d nie powiodło się',
    'last_successful_build' => 'Ostatnie budowanie zakończone sukesem odbyło się %s',
    'never_built_successfully' => 'Projekt nie został zbudowany z powodzeniem.',
    'all_builds_passed' => 'Wszystkie z ostatnich %d budowań przeszły.',
    'all_builds_passed_short' => '%d / %d przeszło.',
    'last_failed_build' => 'Ostatnie budowanie zakończone niepowodzeniam było %s.',
    'never_failed_build' => 'Ten projekt nigdy nie zakończył się niepowodzeniem budowania',
    'view_project' => 'Podgląd Projektu',

    // Timeline:
    'latest_builds' => 'Ostatnie Budowania',
    'pending' => 'Oczekujące',
    'running' => 'W toku',
    'success' => 'Sukces',
    'successful' => 'Zakończone sukcesem',
    'failed' => 'Nieudane',
    'manual_build' => 'Budowanie Manualne',

    // Add/Edit Project:
    'new_project' => 'Nowy Projekt',
    'project_x_not_found' => 'Projekt o ID %d nie istnieje.',
    'project_details' => 'Szczegóły Projektu',
    'public_key_help' => 'Aby łatwiej zacząć, wygenerowaliśmy parę kluczy SSH, które możesz użyć
do tego projektu. Żeby je użyć, wystarczy dodać następujący klucz publiczny do sekcji "wdrożyć klucze"
od wybranego kodu źródłowego platformy hostingowej.',
    'select_repository_type' => 'Wybierz typ repozytorium...',
    'github' => 'GitHub',
    'bitbucket' => 'Bitbucket',
    'gitlab' => 'GitLab',
    'remote' => 'Zdalny URL ',
    'local' => 'Lokalna Ścieżka ',
    'hg'    => 'Mercurial',
    'svn' => 'Subversion',

    'where_hosted' => 'Gdzie hostowany jest Twój projekt?',
    'choose_github' => 'Wybierz repozytorium GitHub:',

    'repo_name' => 'Nazwa repozytorium / URL (Zdalne) lub Ścieżka (Lokalne)',
    'project_title' => 'Tytuł Projektu',
    'project_private_key' => 'Prywanty klucz dostępu do repozytoriów
(pozostaw puste pole dla zdalnych lokalnych i/lub anonimowych)',
    'build_config' => 'PHPCI zbudowało config dla tego projektu
(jeśli nie możesz dodać pliku phpci.yml do repozytorium projektu)',
    'default_branch' => 'Domyślna nazwa gałęzi',
    'allow_public_status' => 'Włączyć status publiczny dla tego projektu?',
    'save_project' => 'Zachowaj Projekt',

    'error_mercurial' => 'URL repozytorium Mercurialnego powinno zaczynać się od http:// and https://',
    'error_remote' => 'URL repozytorium powinno zaczynać się od git://, http:// lub https://',
    'error_gitlab' => 'Nazwa Repozytorium GitLab powinna być w następującym formacie:  "user@domain.tld:owner/repo.git"',
    'error_github' => 'Nazwa repozytorium powinna być w formacie: "użytkownik/repo"',
    'error_bitbucket' => 'Nazwa repozytorium powinna być w formacie: " użytkownik/repo\'',
    'error_path' => 'Wybrana sieżka nie istnieje',

    // View Project:
    'all_branches' => 'Wszystkie Gałęzie',
    'builds' => 'Budowania',
    'id' => 'ID',
    'project' => 'Projekt',
    'commit' => 'Commit',
    'branch' => 'Gałąź',
    'status' => 'Status',
    'prev_link' => '&laquo; Poprzedni',
    'next_link' => 'Następny &raquo;',
    'public_key' => 'Klucz Publiczny',
    'delete_build' => 'Usuń Budowanie',

    'webhooks' => 'Webhooks',
    'webhooks_help_github' => 'Aby automatycznie uruchomić nową budowę po wysłaniu commitów dodaj poniższy adres URL
 jako nowy "WebHook" w sekcji <a href="https://github.com/%s/settings/hooks">Webhooks and Services</a>
 Twojego repozytoria GitLab.',

    'webhooks_help_gitlab' => 'Aby automatycznie uruchomić nową budowę po wysłaniu commitów dodaj poniższy adres URL
 jako "WebHook URL" w sekcji Web Hook Twojego repozytoria GitLab.',

    'webhooks_help_bitbucket' => 'Aby automatycznie uruchomić nową budowę po wysłaniu commitów, dodaj poniższy adres URL
 jako usługę "POST" w sekcji
<a href="https://bitbucket.org/%s/admin/services">
Services</a> repozytoria Bitbucket.',

    // View Build
    'build_x_not_found' => 'Budowanie o ID %d nie istnieje.',
    'build_n' => 'Budowanie %d',
    'rebuild_now' => 'Przebuduj Teraz',


    'committed_by_x' => 'Commitowane przez %s',
    'commit_id_x' => 'Commit: %s',

    'chart_display' => 'Ten wykres wyświetli się po zakończeniu budowy.',

    'build' => 'Budowanie',
    'lines' => 'Linie',
    'comment_lines' => 'Linie Komentarza',
    'noncomment_lines' => 'Linie Bez Komentarza',
    'logical_lines' => 'Lokalne Linie',
    'lines_of_code' => 'Linie Kodu',
    'build_log' => 'Log Budowania',
    'quality_trend' => 'Trend Jakości',
    'codeception_errors' => 'Błędy Codeception',
    'phpmd_warnings' => 'Alerty PHPMD',
    'phpcs_warnings' => 'Alerty PHPCS',
    'phpcs_errors' => 'Błędy PHPCS',
    'phplint_errors' => 'Błędy Lint',
    'phpunit_errors' => 'Błędy PHPUnit',
    'phpdoccheck_warnings' => 'Brakuje sekcji DocBlock',
    'issues' => 'Problemy',

    'codeception' => 'Codeception',
    'phpcpd' => 'PHP Copy/Paste Detector',
    'phpcs' => 'PHP Code Sniffer',
    'phpdoccheck' => 'Brakuje sekcji DocBlock',
    'phpmd' => 'PHP Mess Detector',
    'phpspec' => 'PHPSpec',
    'phpunit' => 'PHPUnit',
    'technical_debt' => 'Dług technologiczny',
    'behat' => 'Behat',

    'file' => 'Plik',
    'line' => 'Linia',
    'class' => 'Klasa',
    'method' => 'Metoda',
    'message' => 'Wiadomość',
    'start' => 'Początek',
    'end' => 'Koniec',
    'from' => 'Od',
    'to' => 'Do',
    'suite' => 'Zestaw ',
    'test' => 'Test',
    'result' => 'Wynik',
    'ok' => 'OK',
    'took_n_seconds' => 'Zajęło %d sekund',
    'build_created' => 'Budowanie Stworzone',
    'build_started' => 'Budowanie Rozpoczęte',
    'build_finished' => 'Budowanie Zakończone',

    // Users
    'name' => 'Nazwa',
    'password_change' => 'Hasło (pozostaw puste jeśli nie chcesz zmienić hasła)',
    'save' => 'Zapisz &raquo;',
    'update_your_details' => 'Aktualizuj swoje dane',
    'your_details_updated' => 'Twoje dane zostały zaktualizowane.',
    'add_user' => 'Dodaj Użytkownika',
    'is_admin' => 'Jest Adminem?',
    'yes' => 'Tak',
    'no' => 'Nie',
    'edit' => 'Edytuj',
    'edit_user' => 'Edytuj Użytkownika',
    'delete_user' => 'Usuń Użytkownika',
    'user_n_not_found' => 'Użytkownik z ID %d nie istnieje.',
    'is_user_admin' => 'Czy użytkownik jest administratorem?',
    'save_user' => 'Zapisz Użytkownika',

    // Settings:
    'settings_saved' => 'Ustawienia zostały zapisane.',
    'settings_check_perms' => 'Twoje ustawienia nie mogły zostać zapisane. Sprawdź uprawnienia do pliku config.yml.',
    'settings_cannot_write' => 'PHPCI nie może zapisać do pliku config.yml. Dopóty nie będzie można poprawnie zachować ustawie,
dopóki nie będzie to naprawione.',
    'settings_github_linked' => 'Połaczono z Twoim kontem Github',
    'settings_github_not_linked' => 'Nie udało się połaczyć z Twoim kontem Github',
    'build_settings' => 'Ustawienia budowania',
    'github_application' => 'Aplikacja GitHub',
    'github_sign_in' => 'Zanim będzie można zacząć korzystać z GitHub, musisz najpierw  <a href="%s">Sign in</a>, a następnie udzielić dostęp dla PHPCI do Twojego konta.',
    'github_phpci_linked' => 'PHPCI zostało pomyślnie połączone z konten GitHub.',
    'github_where_to_find' => 'Gdzie można znaleźć...',
    'github_where_help' => 'Jeśli to jest Twoja aplikacjia i chcesz jej użyć to więcej informacji znajdziesz w sekcji ustawień:
 <a href="https://github.com/settings/applications">applications</a>',

    'email_settings' => 'Ustawienia Email',
    'email_settings_help' => 'Aby PHPCI mógł wysyłać emaile z stanem budowy, musisz najpierw skonfigurować poniższe ustawienia SMTP.',

    'application_id' => 'ID Aplikacji',
    'application_secret' => 'Klucz Secret aplikacji',

    'smtp_server' => 'Serwer SMTP',
    'smtp_port' => 'Port SMTP',
    'smtp_username' => 'SMTP Login',
    'smtp_password' => 'Hasło SMTP',
    'from_email_address' => 'E-mail adres Od:',
    'default_notification_address' => 'Domyślny adres email powiadamiania',
    'use_smtp_encryption' => 'Użyć szyfrowane SMTP?',
    'none' => 'Żadne',
    'ssl' => 'SSL',
    'tls' => 'TLS',

    'failed_after' => 'Uznaj, że budowanie nie powiodło się po',
    '5_mins' => '5 Minutach',
    '15_mins' => '15 Minutach',
    '30_mins' => '30 Minutach',
    '1_hour' => '1 Godzinie',
    '3_hours' => '3 Godzinach',

    // Plugins
    'cannot_update_composer' => 'PHPCI nie może zaktualizować copmposer.json, ponieważ nie ma uprawnień do zapisu.',
    'x_has_been_removed' => 'Usunięto %s. ',
    'x_has_been_added' => 'Dodano %s do composer.json. Zostanie zainstalowane po
wywołaniu polecenia composer update.',
    'enabled_plugins' => 'Aktywne Pluginy',
    'provided_by_package' => 'Dostarczone w pakiecie',
    'installed_packages' => 'Zainstalowane Pakiety',
    'suggested_packages' => 'Sugerowane Pakiety',
    'title' => 'Tytuł',
    'description' => 'Opis',
    'version' => 'Wersja',
    'install' => 'Zainstaluj &raquo;',
    'remove' => 'Usuń &raquo;',
    'search_packagist_for_more' => 'Przeszukaj Packagist po więcej pakietów',
    'search' => 'Szukaj &raquo;',

    // Installer
    'installation_url' => 'URL instalacyjny PHPCI',
    'db_host' => 'Host Bazy Danych',
    'db_name' => 'Nazwa Bazy Danych',
    'db_user' => 'Nazwa Użytkownika Bazy Danych',
    'db_pass' => 'Hasło Bazy Danych',
    'admin_name' => 'Imię Admina',
    'admin_pass' => 'Hasło Admina',
    'admin_email' => 'Adres Email Admina',
    'config_path' => 'Ścieżka Pliku Config',
    'install_phpci' => 'Zainstaluj PHPCI',
    'welcome_to_phpci' => 'Witaj w PHPCI',
    'please_answer' => 'Odpowiedz na poniższe pytania:',
    'phpci_php_req' => 'PHPCI wymaga przynajmniej PHP 5.3.8 do prawidłowego funkcjonowania.',
    'extension_required' => 'Wymagane rozszerzenie: %s',
    'function_required' => 'PHPCI musi mieć możliwość wywołania funkcji %s(). Czy ona jest wyłączona w php.ini?',
    'requirements_not_met' => 'Nie można zainstalować PHPCI, ponieważ nie wszystkie wymagania zostały spełnione.
Przejrzyj powyższą listę błędów przed kontynuowaniem.',
    'must_be_valid_email' => 'Poprawny adres email jest wymagany.',
    'must_be_valid_url' => 'Poprawny URL jest wymagany.',
    'enter_name' => 'Imię Admina:',
    'enter_email' => 'Email Admina:',
    'enter_password' => 'Hasło Admina:',
    'enter_phpci_url' => 'URL PHPCI (na przykład "http://phpci.local"):',

    'enter_db_host' => 'Wpisz hosta MySQL [host lokalny]:',
    'enter_db_name' => 'Wpisz nazwę bazy danych MySQL [phpci]:',
    'enter_db_user' => 'Wpisz nazwę użytkownika MySQL [phpci]:',
    'enter_db_pass' => 'Wpisz hasło MySQL:',
    'could_not_connect' => 'Z podanymi ustawieniami PHPCI nie udało się połączyć z MySQL. Spróbuj ponownie.',
    'setting_up_db' => 'Ustawianie Twojej bazy danych...',
    'user_created' => 'Utworzono konto użytkownika!',
    'failed_to_create' => 'PHPCI nie udało się założyc Twojego konta administratora.',
    'config_exists' => 'Plik konfiguracji PHPCI istnieje i nie jest pusty.',
    'update_instead' => 'Jeśli próbowałeś zaktualizować PHPCI, użyj phpci:update.',

    // Update
    'update_phpci' => 'Zaktualizuj bazę danych zgodnie ze zmodyfikowanymi modelami.',
    'updating_phpci' => 'Aktualizacja bazy danych PHPCI:',
    'not_installed' => 'Wygląda na to, że PHPCI nie jest zainstalowane.',
    'install_instead' => 'Proszę zainstalować PHPCI poprzez phpci:install',

    // Poll Command
    'poll_github' => 'Odpytuj GitHub, aby sprawdzić czy należy uruchomić budowę.',
    'no_token' => 'Nie znaleziono tokena GitHub',
    'finding_projects' => 'Szukanie projektów do odpytywania',
    'found_n_projects' => 'Znaleziono %d projektów',
    'last_commit_is' => 'Ostatni commit do GitHuba dla %s to %s',
    'adding_new_build' => 'Ostatni commit jest inny w bazie danych, dodaję nową budowę.',
    'finished_processing_builds' => 'Ukończono przetwarzanie budów.',

    // Create Admin
    'create_admin_user' => 'Utwórz admina',
    'incorrect_format' => 'Niepoprawny format',

    // Run Command
    'run_all_pending' => 'Uruchom wszystkie oczekujące budowy w PHPCI',
    'finding_builds' => 'Szukam budów do przetwarzania.',
    'found_n_builds' => 'Znaleziono %d budowań',
    'skipping_build' => 'Budowanie %d jest pomijane - Budowanie projektu jest już w toku',
    'marked_as_failed' => 'Budowanie %d nie powiodło się z powodu przekroczenia limitu czasu.',

    // Builder
    'missing_phpci_yml' => 'Projekt nie zawiera pliku phpci.yml lub projekt jest pusty.',
    'build_success' => 'BUDOWANIE ZAKOŃCZONE SUKCESEM',
    'build_failed' => 'BUDOWANIE NIE POWIODŁO SIĘ',
    'removing_build' => 'Usuwanie Budowania.',
    'exception' => 'Wyjątek:',
    'could_not_create_working' => 'Nie można utworzyć wersji roboczej.',
    'working_copy_created' => 'Stworzono wersję roboczą: %s',
    'looking_for_binary' => 'Szukam binarek: %s',
    'found_in_path' => 'Znaleziono w %s: %s',
    'running_plugin' => 'Uruchomiony Plugin: %s',
    'plugin_success' => 'Plugin: Sukces',
    'plugin_failed' => 'Plugin: Niepowodzenie',
    'plugin_missing' => 'Plugin nie istnieje: %s',
    'tap_version' => 'TapParser obsługuje tylko TAP w wersji 13',
    'tap_error' => 'Nieprawidłowy łańcuch TAP, liczba testów nie zgadza się z policzoną ilością testów.',

    // Build Plugins:
    'no_tests_performed' => 'Nie przeprowadzono żadnych testów.',
    'could_not_find' => 'Nie znaleziono %s',
    'no_campfire_settings' => 'Nie zdefiniowano parametrów połączenia dla pluginu Campfire',
    'failed_to_wipe' => 'Nie udało się wyczyścić istniejącego katalogu %s przed kopiowaniem',
    'passing_build' => 'Pomijanie Budowania',
    'failing_build' => 'Niepowodzenie Budowania',
    'log_output' => 'Log Wyjściowy:',
    'n_emails_sent' => 'Wysłano %d emaili.',
    'n_emails_failed' => 'Nie wysłano %d emaili.',
    'unable_to_set_env' => 'Nie można ustawić zmiennej środowiskowej',
    'tag_created' => 'Tag stworzony przez PHPCI: %s',
    'x_built_at_x' => '%PROJECT_TITLE% zbudowano pod %BUILD_URI%',
    'hipchat_settings' => 'Proszę podać pokój i authToken dla pluginu hipchat_notify.',
    'irc_settings' => 'Musisz skonfigurować serwer, pokój i swoją nazwę.',
    'invalid_command' => 'Nieprawidłowe polecenie',
    'import_file_key' => 'Potwierdzenie importu musi zawierać klucz "plik"',
    'cannot_open_import' => 'Nie można otworzyć importowanego pliku SQL: %s',
    'unable_to_execute' => 'Nie można wykonać pliku SQL',
    'phar_internal_error' => 'Wewnętrzny Błąd Pluginu Phar',
    'build_file_missing' => 'Podany plik budowy nie istnieje.',
    'property_file_missing' => 'Podany plik właściwości nie istnieje.',
    'could_not_process_report' => 'Nie udało się przetworzyć raportu wygenerowanego przez to narzędzie.',
    'shell_not_enabled' => 'Plugin powłoki jest nieaktywny. Aktywuj go poprzez config.yml.'
);

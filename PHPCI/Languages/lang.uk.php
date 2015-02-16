<?php
/**
 * PHPCI - Continuous Integration for PHP
 *
 * @copyright    Copyright 2014, Block 8 Limited.
 * @license      https://github.com/Block8/PHPCI/blob/master/LICENSE.md
 * @link         https://www.phptesting.org/
 */

$strings = array(
    'language_name' => 'Український',
    'language' => 'Мова',

    // Log in:
    'log_in_to_phpci' => 'Увійти до PHPCI',
    'login_error' => 'Невірний email або пароль',
    'forgotten_password_link' => 'Забули свій пароль?',
    'reset_emailed' => 'Ми відправили вам посилання для скидання вашого паролю.',
    'reset_header' => '<strong>Не хвилюйтесь!</strong><br>Просто введіть ваш email
і вам буде надіслано листа із посиланням на скидання паролю.',
    'reset_email_address' => 'Введіть свою email адресу:',
    'reset_send_email' => 'Скидання пароля',
    'reset_enter_password' => 'Введіть будь-ласка новий пароль',
    'reset_new_password' => 'Новий пароль:',
    'reset_change_password' => 'Змінити пароль',
    'reset_no_user_exists' => 'Не існує користувача з такою email адресою, будь-ласка повторіть знову.',
    'reset_email_body' => 'Привіт, %s,

Ви отримали цей лист, тому що ви або хтось інший запросили скидання пароля в PHPCI.

Якщо це були ви, будь ласка, перейдіть за посиланням нижче для скидання пароля: %ssession/reset-password/%d/%s,

або ж проігноруйте цей лист та нічого не робіть.

Дякуємо,

PHPCI',

    'reset_email_title' => 'Скидання пароль PHPCI для %s',
    'reset_invalid' => 'Невірний запит скидання паролю.',
    'email_address' => 'Email адреса',
    'password' => 'Пароль',
    'log_in' => 'Увійти',


    // Top Nav
    'toggle_navigation' => 'Сховати/відобразити панель навігації',
    'n_builds_pending' => '%d збірок очікує',
    'n_builds_running' => '%d збірок виконується',
    'edit_profile' => 'Редагувати профіль',
    'sign_out' => 'Вийти',
    'branch_x' => 'Гілка: %s',
    'created_x' => 'Створено: %s',
    'started_x' => 'Розпочато: %s',

    // Sidebar
    'hello_name' => 'Привіт, %s',
    'dashboard' => 'Панель управління',
    'admin_options' => 'Меню адміністратора',
    'add_project' => 'Додати проект',
    'settings' => 'Налаштування',
    'manage_users' => 'Управління користувачами',
    'plugins' => 'Плагіни',
    'view' => 'Переглянути',
    'build_now' => 'Збірати',
    'edit_project' => 'Редагувати проект',
    'delete_project' => 'Видалити проект',

    // Project Summary:
    'no_builds_yet' => 'Немає збірок!',
    'x_of_x_failed' => '%d із останніх %d збірок були провалені.',
    'x_of_x_failed_short' => '%d / %d провалені.',
    'last_successful_build' => 'Останнью успішною збіркою була %s.',
    'never_built_successfully' => 'У цього проекта ніколи не було успішних збірок.',
    'all_builds_passed' => 'Усі із останніх %d збірок успішні.',
    'all_builds_passed_short' => '%d / %d успішні.',
    'last_failed_build' => 'Останньою проваленою збіркою була %s.',
    'never_failed_build' => 'У цього проекта ніколи не було провалених збірок.',
    'view_project' => 'Переглянути проект',

    // Timeline:
    'latest_builds' => 'Останні збірки',
    'pending' => 'Очікує',
    'running' => 'Виконується',
    'success' => 'Успіх',
    'successful' => 'Успішно',
    'failed' => 'Провалена',
    'manual_build' => 'Ручна збірка',

    // Add/Edit Project:
    'new_project' => 'Новий проект',
    'project_x_not_found' => 'Проект із ID %d не існує.',
    'project_details' => 'Деталі проекта',
    'public_key_help' => 'Для полегшення початку, ми згенерували пару SSH-ключів для вас для використання в цьому проекті.
Для їх використання - просто додайте наступний публічний ключ у розділ "deploy keys" обраної вами системи зберігання програмного коду.',
    'select_repository_type' => 'Оберіть тип репозиторію...',
    'github' => 'GitHub',
    'bitbucket' => 'Bitbucket',
    'gitlab' => 'GitLab',
    'remote' => 'Віддалений URL',
    'local' => 'Локальний шлях',
    'hg'    => 'Mercurial',

    'where_hosted' => 'Де зберігається ваш проект?',
    'choose_github' => 'Оберіть GitHub репозиторій:',

    'repo_name' => 'Ім’я репозиторія / URL (зовнішній) / Шлях (локальний)',
    'project_title' => 'Заголовок проекту',
    'project_private_key' => 'Приватний ключ доступу до репозиторія
(залишити поле порожнім для локального використання та/або анонімного доступу)',
    'build_config' => 'Конфігурація збірки цього проекта для PHPCI
(якщо ви не додали файл phpci.yml до репозиторію вашого проекту)',
    'default_branch' => 'Назва гілки за замовчуванням',
    'allow_public_status' => 'Увімкнути публічну сторінку статусу та зображення для цього проекта?',
    'save_project' => 'Зберегти проект',

    'error_mercurial' => 'URL репозиторію Mercurial повинен починатись із http:// або https://',
    'error_remote' => 'URL репозиторію повинен починатись із git://, http:// або https://',
    'error_gitlab' => 'Ім’я репозиторія GitLab повинно бути у форматі "user@domain.tld:owner/repo.git"',
    'error_github' => 'Ім’я репозиторія повинно відповідати формату "owner/repo"',
    'error_bitbucket' => 'Ім’я репозиторія повинно відповідати формату "owner/repo"',
    'error_path' => 'Вказаний шлях не існує.',

    // View Project:
    'all_branches' => 'Усі гілки',
    'builds' => 'Збірки',
    'id' => 'ID',
    'project' => 'Проект',
    'commit' => 'Комміт',
    'branch' => 'Гілка',
    'status' => 'Статус',
    'prev_link' => '&laquo; Попер.',
    'next_link' => 'Наст. &raquo;',
    'public_key' => 'Публічний ключ',
    'delete_build' => 'Видалити збірку',

    'webhooks' => 'Webhooks',
    'webhooks_help_github' => 'Для автоматичної збірки цього проекту, при надходженні нових комітів, додайте наступний URL
у якості нового "Webhook" у розділі налаштувань
<a href="https://github.com/%s/settings/hooks">Webhooks and Services</a>
вашого GitHub репозиторію.',

    'webhooks_help_gitlab' => 'Для автоматичної збірки цього проекту, при надходженні нових комітів, додайте наступний URL
у якості нового "WebHook URL" у розділі "Web Hooks" вашого GitLab репозиторію.',

    'webhooks_help_bitbucket' => 'Для автоматичної збірки цього проекту, при надходженні нових комітів, додайте наступний URL
у якості нового "POST" сервісу у розділі
<a href="https://bitbucket.org/%s/admin/services">Services</a>
вашого Bitbucket репозиторію.',

    // View Build
    'build_x_not_found' => 'Збірка із ID %d не існує.',
    'build_n' => 'Збірка %d',
    'rebuild_now' => 'Перезібрати зараз',


    'committed_by_x' => 'Комміт від %s',
    'commit_id_x' => 'Комміт: %s',

    'chart_display' => 'Цей графік відобразиться після завершення збірки.',

    'build' => 'Збірка',
    'lines' => 'Рядків',
    'comment_lines' => 'Рядків коментарів',
    'noncomment_lines' => 'Рядків не коментарів',
    'logical_lines' => 'Рядків логіки',
    'lines_of_code' => 'Рядки коду',
    'build_log' => 'Лог збірки',
    'quality_trend' => 'Тенденція якості',
    'codeception_errors' => 'Помилки Codeception',
    'phpmd_warnings' => 'Попередження PHPMD',
    'phpcs_warnings' => 'Попередження PHPCS',
    'phpcs_errors' => 'Помилки PHPCS',
    'phplint_errors' => 'Помилки Lint',
    'phpunit_errors' => 'Помилки PHPUnit',
    'phpdoccheck_warnings' => 'Відсутні Docblocks',
    'issues' => 'Проблеми',

    'codeception' => 'Codeception',
    'phpcpd' => 'PHP Copy/Paste Detector',
    'phpcs' => 'PHP Code Sniffer',
    'phpdoccheck' => 'Відсутні Docblocks',
    'phpmd' => 'PHP Mess Detector',
    'phpspec' => 'PHP Spec',
    'phpunit' => 'PHP Unit',

    'file' => 'Файл',
    'line' => 'Строка',
    'class' => 'Клас',
    'method' => 'Метод',
    'message' => 'Повідомлення',
    'start' => 'Запуск',
    'end' => 'Кінець',
    'from' => 'Від',
    'to' => 'До',
    'suite' => 'Комплект',
    'test' => 'Тест',
    'result' => 'Результат',
    'ok' => 'OK',
    'took_n_seconds' => 'Зайняло %d секунд',
    'build_created' => 'Збірка створена',
    'build_started' => 'Збірка розпочата',
    'build_finished' => 'Збірка завершена',

    // Users
    'name' => 'Ім’я',
    'password_change' => 'Пароль (залишити порожнім, якщо не бажаєте змінювати його)',
    'save' => 'Зберегти &raquo;',
    'update_your_details' => 'Оновити ваші деталі',
    'your_details_updated' => 'Ваші деталі були оновлені.',
    'add_user' => 'Додати користувача',
    'is_admin' => 'Адміністратор?',
    'yes' => 'Так',
    'no' => 'Ні',
    'edit' => 'Редагувати',
    'edit_user' => 'Редагувати користувача',
    'delete_user' => 'Видалити користувача',
    'user_n_not_found' => 'Користувач із ID %d не існує.',
    'is_user_admin' => 'Чи є цей користувач адміністратором?',
    'save_user' => 'Зберегти користувача',

    // Settings:
    'settings_saved' => 'Ваші налаштування були збережені.',
    'settings_check_perms' => 'Ваші налаштування не можуть бути збережені, перевірте права на ваш файл налаштувань config.yml.',
    'settings_cannot_write' => 'PHPCI не може записати файл config.yml, налаштування не будуть коректно збережені,
доки це не буде виправлено.',
    'settings_github_linked' => 'Ваш GitHub аккаунт було підключено.',
    'settings_github_not_linked' => 'Ваш GitHub аккаунт не може бути підключеним.',
    'build_settings' => 'Налаштування збірки',
    'github_application' => 'GitHub додаток',
    'github_sign_in' => 'Перед початком користування GitHub, вам необхідно <a href="%s">увійти</a> та надати
доступ для PHPCI до вашого аккаунту.',
    'github_phpci_linked' => 'PHPCI успішно зв\'язаний з аккаунтом GitHub.',
    'github_where_to_find' => 'Де це знайти...',
    'github_where_help' => 'Якщо ви є власником додатку, який би ви хотіли використовувати, то ви можете знайти інформацію про це у розділі
налаштувань ваших <a href="https://github.com/settings/applications">додатків</a>.',

    'email_settings' => 'Налаштування Email',
    'email_settings_help' => 'Перед тим, як PHPCI почне надсилати статуси збірок на email,
вам необхідно налаштувати параметри SMTP нижче.',

    'application_id' => 'ID додатка',
    'application_secret' => 'Таємний ключ додатка',

    'smtp_server' => 'Сервер SMTP',
    'smtp_port' => 'Порт SMTP',
    'smtp_username' => 'Ім’я користувача SMTP',
    'smtp_password' => 'Пароль SMTP',
    'from_email_address' => 'Відправляти з Email',
    'default_notification_address' => 'Email для повідомлень за замовчуванням',
    'use_smtp_encryption' => 'Використовувати SMTP шифрування?',
    'none' => 'Ні',
    'ssl' => 'SSL',
    'tls' => 'TLS',

    'failed_after' => 'Вважати збірку проваленою після',
    '5_mins' => '5 хвилин',
    '15_mins' => '15 хвилин',
    '30_mins' => '30 хвилин',
    '1_hour' => '1 година',
    '3_hours' => '3 години',

    // Plugins
    'cannot_update_composer' => 'PHPCI не може оновити composer.json, оскільки він не є доступним для запису.',
    'x_has_been_removed' => '%s було видалено.',
    'x_has_been_added' => '%s був доданий до composer.json і буде встановлений, як тільки
ви виконаєте composer update.',
    'enabled_plugins' => 'Увімкнені плагіни',
    'provided_by_package' => 'Наданий пакетом',
    'installed_packages' => 'Встановлені пакети',
    'suggested_packages' => 'Запропоновані пакети',
    'title' => 'Заголовок',
    'description' => 'Опис',
    'version' => 'Версія',
    'install' => 'Встановити &raquo;',
    'remove' => 'Видалити &raquo;',
    'search_packagist_for_more' => 'Знайти більше пакетів на Packagist',
    'search' => 'Знайти &raquo;',

    // Installer
    'installation_url' => 'URL встановлення PHPCI',
    'db_host' => 'Хост бази даних',
    'db_name' => 'Назва бази даних',
    'db_user' => 'Ім’я користувача бази даних',
    'db_pass' => 'Пароль бази даних',
    'admin_name' => 'Ім’я адміністратора',
    'admin_pass' => 'Пароль адміністратора',
    'admin_email' => 'Email адреса адміністратора',
    'config_path' => 'Шлях до файла конфігурації',
    'install_phpci' => 'Встановити PHPCI',
    'welcome_to_phpci' => 'Ласкаво просимо до PHPCI',
    'please_answer' => 'Будь ласка, дайте відповідь на наступні питання:',
    'phpci_php_req' => 'PHPCI вимагає для роботи, принаймні, версію PHP 5.3.8.',
    'extension_required' => 'Необхідне розширення: %s',
    'function_required' => 'PHPCI необхідна можливість викликати функцію %s(). Вона відключена у php.ini?',
    'requirements_not_met' => 'Неможливо встановити PHPCI, оскільки не всі вимоги виконані.
Будь ласка, продивіться наявні помилки перед тим, як продовжити.',
    'must_be_valid_email' => 'Повинно бути коректною email адресою.',
    'must_be_valid_url' => 'Повинно бути коректним URL.',
    'enter_name' => 'Ім’я адміністратора:',
    'enter_email' => 'Email адміністратора:',
    'enter_password' => 'Пароль адміністратора:',
    'enter_phpci_url' => 'URL адреса вашого PHPCI (наприклад, "http://phpci.local"):',

    'enter_db_host' => 'Будь ласка, введіть хост MySQL [localhost]:',
    'enter_db_name' => 'Будь ласка, введить ім’я бази даних MySQL [phpci]:',
    'enter_db_user' => 'Будь ласка, введить ім’я користувача MySQL [phpci]:',
    'enter_db_pass' => 'Будь ласка, введить ваш пароль MySQL:',
    'could_not_connect' => 'PHPCI не може підключитися до MySQL із наданими параметрами. Будь ласка, спробуйте ще раз.',
    'setting_up_db' => 'Налаштування вашої бази даних...',
    'user_created' => 'Аккаунт користувача створено!',
    'failed_to_create' => 'PHPCI не вдалося створити ваш аккаунт адміністратора.',
    'config_exists' => 'Файл конфігурації PHPCI вже існує та не є порожнім.',
    'update_instead' => 'Якщо ви збираєтесь оновити PHPCI, будь ласка, використовуйте команду phpci:update.',

    // Update
    'update_phpci' => 'Оновити базу даних для відображення змінених моделей.',
    'updating_phpci' => 'Оновлення бази даних PHPCI:',
    'not_installed' => 'Неможливо встановити PHPCI.',
    'install_instead' => 'Будь ласка, встановіть PHPCI через команду phpci:install.',

    // Poll Command
    'poll_github' => 'Зробити запит до GitHub для перевірки запуску збірки.',
    'no_token' => 'GitHub токен не знайдено',
    'finding_projects' => 'Пошук проектів для запиту',
    'found_n_projects' => 'Знайдено %d проектів',
    'last_commit_is' => 'Останній коміт на GitHub для %s - %s',
    'adding_new_build' => 'Останній коміт має відмінності із базою даних, створена нова збірка.',
    'finished_processing_builds' => 'Завершено обробку збірок.',

    // Create Admin
    'create_admin_user' => 'Створити аккаунт адміністратора',
    'incorrect_format' => 'Невірний формат',

    // Run Command
    'run_all_pending' => 'Запустити всі PHPCI збірки, які очікують.',
    'finding_builds' => 'Пошук збірок для обробки',
    'found_n_builds' => 'Знайдено %d збірок',
    'skipping_build' => 'Збірка %d пропущена - Збірка проекта вже у процесі.',
    'marked_as_failed' => 'Збірка %d відмічена як невдала через перевищення ліміту часу.',

    // Builder
    'missing_phpci_yml' => 'Цей проект не містить файл phpci.yml або він є порожнім.',
    'build_success' => 'ЗБІРКА УСПІШНА',
    'build_failed' => 'ЗБІРКА НЕВДАЛА',
    'removing_build' => 'Видалення збірки.',
    'exception' => 'Виключення:',
    'could_not_create_working' => 'Не вдалося створити робочу копію.',
    'working_copy_created' => 'Робоча копія створена: %s',
    'looking_for_binary' => 'Пошук бінарного пакета: %s',
    'found_in_path' => 'Знайдено у %s: %s',
    'running_plugin' => 'ВИКОНУЄТЬСЯ ПЛАГІН: %s',
    'plugin_success' => 'ПЛАГІН: УСПІШНО',
    'plugin_failed' => 'ПЛАГІН: НЕВДАЛО',
    'plugin_missing' => 'Плагін не існує: %s',
    'tap_version' => 'TapParser підтримує тільки TAP версії 13',
    'tap_error' => 'Некоректний TAP-рядок, кількість тестів не співпадає із вказаними.',

    // Build Plugins:
    'no_tests_performed' => 'Жодних тестів не було запущено.',
    'could_not_find' => 'Неможливо знайти %s',
    'no_campfire_settings' => 'Не вказані параметри з’єднання для плагіна Campfire',
    'failed_to_wipe' => 'Не вдалося знищити існуючу директорію %s перед копіюванням',
    'passing_build' => 'Успішно збірка',
    'failing_build' => 'Невдала збірка',
    'log_output' => 'Вивід лога:',
    'n_emails_sent' => '%d листів відправлено.',
    'n_emails_failed' => '%d листів не вдалося відправити.',
    'unable_to_set_env' => 'Неможливо встановити змінну оточення',
    'tag_created' => 'Тег, створений PHPCI: %s',
    'x_built_at_x' => '%PROJECT_TITLE% зібрано у %BUILD_URI%',
    'hipchat_settings' => 'Будь ласка, вкажіть кімнату та "authToken" параметр для плагіна hipchat_notify',
    'irc_settings' => 'Ви повинні вказати сервер, кімнату та нік.',
    'invalid_command' => 'Невірна команда',
    'import_file_key' => 'Вираз імпорту повинен містити ключ \'file\'',
    'cannot_open_import' => 'Неможливо відкрити файл імпорту SQL: %s',
    'unable_to_execute' => 'Неможливо виконати файл SQL',
    'phar_internal_error' => 'Внутрішня помилка плагіну Phar',
    'build_file_missing' => 'Вказаний файл збірки не існує.',
    'property_file_missing' => 'Вказаний файл властивості не існує.',
    'could_not_process_report' => 'Неможливо обробити звіт, згенерований цією утилітою.',
    'shell_not_enabled' => 'Плагін shell не увімкнений. Будь ласка, увімкніть його через config.yml.'
);

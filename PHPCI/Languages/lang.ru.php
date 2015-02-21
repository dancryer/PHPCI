<?php
/**
 * PHPCI - Continuous Integration for PHP
 *
 * @copyright    Copyright 2014, Block 8 Limited.
 * @license      https://github.com/Block8/PHPCI/blob/master/LICENSE.md
 * @link         https://www.phptesting.org/
 */

$strings = array(
    'language_name' => 'Pусский',
    'language' => 'язык',

    // Log in:
    'log_in_to_phpci' => 'Войти в PHPCI',
    'login_error' => 'Неправильный email или пароль',
    'forgotten_password_link' => 'Забыли пароль?',
    'reset_emailed' => 'Вы получите письмо со ссылкой на сброс пароля.',
    'reset_header' => '<strong>Не волнуйтесь!</strong><br>Просто введите ваш email, и вам придет письмо со ссылкой на сброс пароля.',
    'reset_email_address' => 'Введите ваш email:',
    'reset_send_email' => 'Сброс пароля',
    'reset_enter_password' => 'Пожалуйста, введите новый пароль',
    'reset_new_password' => 'Новый пароль:',
    'reset_change_password' => 'Сменить пароль',
    'reset_no_user_exists' => 'Пользователь с таким email-адресом не найден, пожалуйста, попробуйте еще раз.',
    'reset_email_body' => 'Привет %s,

Вы получили это письмо, потому что вы или кто-то другой запросили сброс пароля в PHPCI.

Если это были вы, пожалуйста, перейдите по ссылке для сброса пароля: %ssession/reset-password/%d/%s,

иначе игнорируйте это письмо и ничего не предпринимайте.

Спасибо,

PHPCI',

    'reset_email_title' => 'Сброс пароля PHPCI для %s',
    'reset_invalid' => 'Некорректный запрос на сброс пароля.',
    'email_address' => 'Email',
    'password' => 'Пароль',
    'log_in' => 'Войти',


    // Top Nav
    'toggle_navigation' => 'Скрыть/показать панель навигации',
    'n_builds_pending' => '%d сборок ожидает',
    'n_builds_running' => '%d сборок запущено',
    'edit_profile' => 'Редактировать профиль',
    'sign_out' => 'Выйти',
    'branch_x' => 'Ветка: %s',
    'created_x' => 'Создан: %s',
    'started_x' => 'Запущен: %s',

    // Sidebar
    'hello_name' => 'Привет, %s',
    'dashboard' => 'Панель управления',
    'admin_options' => 'Меню администратора',
    'add_project' => 'Добавить проект',
    'settings' => 'Настройки',
    'manage_users' => 'Пользователи',
    'plugins' => 'Плагины',
    'view' => 'Отчет',
    'build_now' => 'Собрать',
    'edit_project' => 'Редактировать проект',
    'delete_project' => 'Удалить проект',

    // Project Summary:
    'no_builds_yet' => 'Нет сборок!',
    'x_of_x_failed' => '%d из последних %d сборок были провалены.',
    'x_of_x_failed_short' => '%d / %d провалены.',
    'last_successful_build' => ' Последняя успешная сборка была %s.',
    'never_built_successfully' => ' Этот проект никогда не собирался успешно.',
    'all_builds_passed' => 'Все последние сборки (%d) прошли успешно.',
    'all_builds_passed_short' => '%d / %d успешные.',
    'last_failed_build' => ' Последней проваленной сборкой была %s.',
    'never_failed_build' => ' У этого проекта никогда не было проваленных сборок.',
    'view_project' => 'Обзор проекта',

    // Timeline:
    'latest_builds' => 'Последние сборки',
    'pending' => 'Ожидает',
    'running' => 'Запущена',
    'success' => 'Успешно',
    'successful' => 'Успешна',
    'failed' => 'Провалена',
    'manual_build' => 'Запущена вручную',

    // Add/Edit Project:
    'new_project' => 'Новый проект',
    'project_x_not_found' => 'Проекта с ID %d не существует.',
    'project_details' => 'Подробности проекта',
    'public_key_help' => 'Чтобы было легче начать, мы сгенерировали пару SSH-ключей для использования в вашем проекте.
                        Для их использования, просто добавьте эту публичную часть ключа в поле "deploy keys" на выбранном вами хостинге исходного кода.',
    'select_repository_type' => 'Выберите тип репозитория...',
    'github' => 'GitHub',
    'bitbucket' => 'Bitbucket',
    'gitlab' => 'GitLab',
    'remote' => 'Внешний URL',
    'local' => 'Локальный путь',
    'hg'    => 'Mercurial',
    'svn'   => 'Subversion',

    'where_hosted' => 'Расположение проекта',
    'choose_github' => 'Выберите GitHub репозиторий:',

    'repo_name' => 'Репозиторий / Внешний URL / Локальный путь',
    'project_title' => 'Название проекта',
    'project_private_key' => 'Приватный ключ для доступа к репозиторию
                                (оставьте поле пустым для локального использования и/или анонимного доступа)',
    'build_config' => 'Конфигурация сборки проекта для PHPCI
                                (если вы не добавили файл phpci.yml в репозиторий вашего проекта)',
    'default_branch' => 'Ветка по умолчанию',
    'allow_public_status' => 'Разрешить публичный статус и изображение (статуса) для проекта',
    'save_project' => 'Сохранить проект',

    'error_mercurial' => 'URL репозитория Mercurial должен начинаться с http:// или https://',
    'error_remote' => 'URL репозитория должен начинаться с git://, http:// или https://',
    'error_gitlab' => 'Имя репозитория в GitLab должно иметь формат: "user@domain.tld:owner/repo.git"',
    'error_github' => 'Имя репозитория должно иметь формат: "owner/repo"',
    'error_bitbucket' => 'Имя репозитория должно иметь формат: "owner/repo"',
    'error_path' => 'Пути, который вы указали, не существует.',

    // View Project:
    'all_branches' => 'Все ветки',
    'builds' => 'Сборки',
    'id' => 'ID',
    'project' => 'Проект',
    'commit' => 'Коммит',
    'branch' => 'Ветка',
    'status' => 'Статус',
    'prev_link' => '&laquo; Пред.',
    'next_link' => 'След. &raquo;',
    'public_key' => 'Публичный ключ',
    'delete_build' => 'Удалить сборку',

    'webhooks' => 'Webhooks',
    'webhooks_help_github' => 'Чтобы Автоматически собирать этот проект при публикации новых коммитов, добавьте URL ниже в качестве нового хука в разделе настроек <a href="https://github.com/%s/settings/hooks">Webhooks
                                and Services</a> вашего GitHub репозитория.',

    'webhooks_help_gitlab' => 'Чтобы Автоматически собирать этот проект при публикации новых коммитов, добавьте URL ниже в качестве "WebHook URL"
                                в разделе "Web Hooks" вашего GitLab репозитория.',

    'webhooks_help_bitbucket' => 'Чтобы Автоматически собирать этот проект при публикации новых коммитов, добавьте URL ниже как "POST" сервис в разделе <a href="https://bitbucket.org/%s/admin/services">
                                Services</a> вашего Bitbucket репозитория.',

    // View Build
    'build_x_not_found' => 'Сборки с ID %d не существует.',
    'build_n' => 'Сборка %d',
    'rebuild_now' => 'Пересобрать сейчас',


    'committed_by_x' => 'Отправил %s',
    'commit_id_x' => 'Коммит: %s',

    'chart_display' => 'Этот график будет показан после окончания сборки.',

    'build' => 'Сборка',
    'lines' => 'Строк',
    'comment_lines' => 'Строк комментариев',
    'noncomment_lines' => 'Строк некомментариев',
    'logical_lines' => 'Строк логики',
    'lines_of_code' => 'Строк кода',
    'build_log' => 'Лог сборки',
    'quality_trend' => 'Тенденция качества',
    'codeception_errors' => 'Ошибки Codeception',
    'phpmd_warnings' => 'Предупреждения PHPMD',
    'phpcs_warnings' => 'Предупреждения PHPCS',
    'phpcs_errors' => 'Ошибки PHPCS',
    'phplint_errors' => 'Ошибки Lint',
    'phpunit_errors' => 'Ошибки PHPUnit',
    'phpdoccheck_warnings' => 'Пропущенные Docblocks',
    'issues' => 'Проблемы',

    'codeception' => 'Codeception',
    'phpcpd' => 'PHP Copy/Paste Detector',
    'phpcs' => 'PHP Code Sniffer',
    'phpdoccheck' => 'Missing Docblocks',
    'phpmd' => 'PHP Mess Detector',
    'phpspec' => 'PHP Spec',
    'phpunit' => 'PHP Unit',
    'technical_debt' => 'Технические долги',
    'behat' => 'Behat',

    'file' => 'Файл',
    'line' => 'Строка',
    'class' => 'Класс',
    'method' => 'Метод',
    'message' => 'Сообщение',
    'start' => 'Запуск',
    'end' => 'Конец',
    'from' => 'От',
    'to' => 'До',
    'suite' => 'Комплект',
    'test' => 'Тест',
    'result' => 'Результат',
    'ok' => 'OK',
    'took_n_seconds' => 'Заняло секунд: %d',
    'build_created' => 'Сборка создана',
    'build_started' => 'Сборка запущена',
    'build_finished' => 'Сборка окончена',

    // Users
    'name' => 'Имя',
    'password_change' => 'Пароль (оставьте поле пустым, если не собираетесь менять его)',
    'save' => 'Сохранить &raquo;',
    'update_your_details' => 'Обновить свои данные',
    'your_details_updated' => 'Ваши данные были обновлены.',
    'add_user' => 'Добавить пользователя',
    'is_admin' => 'Является администратором',
    'yes' => 'Да',
    'no' => 'Нет',
    'edit' => 'Править',
    'edit_user' => 'Редактировать пользователя',
    'delete_user' => 'Удалить пользователя',
    'user_n_not_found' => 'Пользователя с ID %d не существует.',
    'is_user_admin' => 'Этот пользователь является администратором',
    'save_user' => 'Сохранить пользователя',

    // Settings:
    'settings_saved' => 'Ваши настройки были сохранены.',
    'settings_check_perms' => 'Ваши настройки не могут быть сохранены, проверьте права на файл настроек config.yml.',
    'settings_cannot_write' => 'PHPCI не может записать config.yml файл, настройки не могут быть сохранены корректно, пока это не будет исправлено.',
    'settings_github_linked' => 'Ваш GitHub аккаунт привязан.',
    'settings_github_not_linked' => 'Ваш GitHub аккаунт не может быть привязан.',
    'build_settings' => 'Настройки сборки',
    'github_application' => 'GitHub приложение',
    'github_sign_in' => 'Перед тем как начать использовать GitHub аккаунт, вам необходимо <a href="%s">войти</a> и разрешить доступ для
                            PHPCI до вашего аккаунта.',
    'github_phpci_linked' => 'PHPCI успешно привязал GitHub аккаунт.',
    'github_where_to_find' => 'Где это найти...',
    'github_where_help' => 'Если вы владелец приложения, которое вы хотели бы использовать, то вы можете найти информацию об этом в разделе
    <a href="https://github.com/settings/applications">applications</a> настроек.',

    'email_settings' => 'Настройки email',
    'email_settings_help' => 'Перед тем, как PHPCI начнет отсылать статус сборок по почте,
                                вам необходимо настроить параметры SMTP ниже.',

    'application_id' => 'ID приложения',
    'application_secret' => 'Секретный ключ приложения',

    'smtp_server' => 'SMTP сервер',
    'smtp_port' => 'SMTP порт',
    'smtp_username' => 'SMTP пользователь',
    'smtp_password' => 'SMTP пароль',
    'from_email_address' => 'Отправлять с email',
    'default_notification_address' => 'Email по умолчанию для оповещений',
    'use_smtp_encryption' => 'Использовать SMTP шифрование',
    'none' => 'Нет',
    'ssl' => 'SSL',
    'tls' => 'TLS',

    'failed_after' => 'Признать сборку проваленной по прошествии',
    '5_mins' => '5 минут',
    '15_mins' => '15 минут',
    '30_mins' => '30 минут',
    '1_hour' => '1 часа',
    '3_hours' => '3 часов',

    // Plugins
    'cannot_update_composer' => 'PHPCI не может обновить composer.json, если он недоступен на запись.',
    'x_has_been_removed' => '%s был удален.',
    'x_has_been_added' => '%s был добавлен в composer.json и будет установлен, как только вы запустите composer update.',
    'enabled_plugins' => 'Включенные плагины',
    'provided_by_package' => 'Предоставляется пакетом',
    'installed_packages' => 'Установленные пакеты',
    'suggested_packages' => 'Рекомендуемые пакеты',
    'title' => 'Название',
    'description' => 'Описание',
    'version' => 'Версия',
    'install' => 'Установить &raquo;',
    'remove' => 'Удалить &raquo;',
    'search_packagist_for_more' => 'Искать на Packagist',
    'search' => 'Искать &raquo;',

    // Installer
    'installation_url' => 'URL-адрес PHPCI для установки',
    'db_host' => 'Хост базы данных',
    'db_name' => 'Имя базы данных',
    'db_user' => 'Пользователь базы данных',
    'db_pass' => 'Пароль базы данных',
    'admin_name' => 'Имя администратора',
    'admin_pass' => 'Пароль администратора',
    'admin_email' => 'Email администратора',
    'config_path' => 'Путь до файла конфигурации',
    'install_phpci' => 'Установить PHPCI',
    'welcome_to_phpci' => 'Добро пожаловать в PHPCI',
    'please_answer' => 'Пожалуйста, ответьте на несколько вопросов:',
    'phpci_php_req' => 'PHPCI необходима для работы версия PHP не ниже 5.3.8.',
    'extension_required' => 'Требуется расширение PHP: %s',
    'function_required' => 'PHPCI необходима возможность вызывать %s() функцию. Она выключена в php.ini?',
    'requirements_not_met' => 'PHPCI не может быть установлен, пока не все требования выполнены.
                                Пожалуйста, просмотрите возникшие ошибки перед тем, как продолжить.',
    'must_be_valid_email' => 'Должен быть корректным email-адресом.',
    'must_be_valid_url' => 'Должен быть корректным URL-адресом.',
    'enter_name' => 'Имя администратора:',
    'enter_email' => 'Email администратора:',
    'enter_password' => 'Пароль администратора:',
    'enter_phpci_url' => 'URL-адрес вашего PHPCI (например: "http://phpci.local"): ',

    'enter_db_host' => 'Пожалуйста, введите хост MySQL [localhost]: ',
    'enter_db_name' => 'Пожалуйста, введите имя базы данных MySQL [phpci]: ',
    'enter_db_user' => 'Пожалуйста, введите пользователя MySQL [phpci]: ',
    'enter_db_pass' => 'Пожалуйста, введите пароль MySQL: ',
    'could_not_connect' => 'PHPCI не может подключится к MySQL с переданными параметрами. Пожалуйста, попробуйте еще раз.',
    'setting_up_db' => 'Установка базы данных... ',
    'user_created' => 'Аккаунт пользователя создан!',
    'failed_to_create' => 'PHPCI не удалось создать аккаунт администратора.',
    'config_exists' => 'Файл конфигурации PHPCI уже существует, и он не пустой.',
    'update_instead' => 'Если вы собираетесь обновить PHPCI, пожалуйста, используйте команду phpci:update.',

    // Update
    'update_phpci' => 'Обновите базу данных с учетом обновленных моделей.',
    'updating_phpci' => 'Обновление базы данных PHPCI: ',
    'not_installed' => 'PHPCI не может быть установлен.',
    'install_instead' => 'Пожалуйста, установите PHPCI с помощью команды phpci:install.',

    // Poll Command
    'poll_github' => 'Опрос GitHub для проверки запуска сборки.',
    'no_token' => 'GitHub токен не найден',
    'finding_projects' => 'Поиск проектов для опроса',
    'found_n_projects' => 'Найдено проектов: %d',
    'last_commit_is' => 'Последний коммит на GitHub для %s - %s',
    'adding_new_build' => 'Последний коммит имеет различия с базой данных, создана сборка.',
    'finished_processing_builds' => 'Процесс сборки завершен.',

    // Create Admin
    'create_admin_user' => 'Добавить аккаунт администратора',
    'incorrect_format' => 'Неверный формат',

    // Run Command
    'run_all_pending' => 'Запустить все ожидающие PHPCI сборки.',
    'finding_builds' => 'Поиск сборок для запуска',
    'found_n_builds' => 'Найдено сборок: %d',
    'skipping_build' => 'Сборка %d пропущена - Сборка проекта уже идет.',
    'marked_as_failed' => 'Сборка %d отмечена как неудавшаяся из-за превышения лимита времени.',

    // Builder
    'missing_phpci_yml' => 'Этот проект не содержит файла phpci.yml, или файл пустой.',
    'build_success' => 'СБОРКА УСПЕШНА',
    'build_failed' => 'СБОРКА ПРОВАЛЕНА',
    'removing_build' => 'Удаление сборки.',
    'exception' => 'Исключение: ',
    'could_not_create_working' => 'Не удалось создать рабочую копию.',
    'working_copy_created' => 'Рабочая копия создана: %s',
    'looking_for_binary' => 'Поиск пакета: %s',
    'found_in_path' => 'Найден в %s: %s',
    'running_plugin' => 'ЗАПУЩЕН ПЛАГИН: %s',
    'plugin_success' => 'ПЛАГИН: УСПЕШНО',
    'plugin_failed' => 'ПЛАГИН: ПРОВАЛ',
    'plugin_missing' => 'Плагина не существует: %s',
    'tap_version' => 'TapParser поддерживает только TAP версии 13',
    'tap_error' => 'Некорректная TAP-строка, количество тестов не совпадает с заявленным.',

    // Build Plugins:
    'no_tests_performed' => 'Никакие тесты не были запущены.',
    'could_not_find' => 'Не удается найти %s',
    'no_campfire_settings' => 'Не переданы параметры подключения для плагина Campfire',
    'failed_to_wipe' => 'Не удалось уничтожить существующую директорию %s перед копированием',
    'passing_build' => 'Успех сборки',
    'failing_build' => 'Провал сборки',
    'log_output' => 'Вывод лога: ',
    'n_emails_sent' => 'Писем отправлено: %d.',
    'n_emails_failed' => 'Писем не удалось отправить: %d.',
    'unable_to_set_env' => 'Невозможно установить переменную окружения',
    'tag_created' => 'Метка создана PHPCI: %s',
    'x_built_at_x' => '%PROJECT_TITLE% собран: %BUILD_URI%',
    'hipchat_settings' => 'Пожалуйста, укажите комнату и токен (authToken) для плагина hipchat_notify',
    'irc_settings' => 'Вы должны задать сервер, комнату и ник.',
    'invalid_command' => 'Некорректная команда',
    'import_file_key' => 'Выражение импорта должно содержать ключ \'file\'',
    'cannot_open_import' => 'Не удалось открыть файл SQL для импорта: %s',
    'unable_to_execute' => 'Невозможно выполнить файл SQL',
    'phar_internal_error' => 'Внутренняя ошибка плагина Phar',
    'build_file_missing' => 'Указанного файла сборки не существует.',
    'property_file_missing' => 'Указанного файла сборки не существует.',
    'could_not_process_report' => 'Невозможно обработать отчет этой утилиты.',
    'shell_not_enabled' => 'Плагин shell не включен. Пожалуйста, включите его в файле config.yml.'
);

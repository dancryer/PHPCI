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
    'log_in_to_phpci' => 'Войти в PHPCI',
    'login_error' => 'Неправильный email-адрес или пароль',
    'forgotten_password_link' => 'Забыли пароль?',
    'reset_emailed' => 'Вы получите письмо с ссылкой на сброс пароля.',
    'reset_header' => '<strong>Не волнуйтесь!</strong><br>Просто введите ваш email-адрес и вам придет письмо со ссылкой на сброс пароля.',
    'reset_email_address' => 'Введите ваш email-адрес:',
    'reset_send_email' => 'Сброс пароля',
    'reset_enter_password' => 'Пожалуйста, введите новый пароль',
    'reset_new_password' => 'Новый пароль:',
    'reset_change_password' => 'Сменить пароль',
    'reset_no_user_exists' => 'Пользователь с таки email-адресом не найден, пожалуйста, попробуйте еще раз.',
    'reset_email_body' => 'Привет %s,

Вы получили это письмо, потому что вы или кто-то другой запросили сброс пароля в PHPCI.

Если это были вы, пожалуйста перейдите по ссылке для сброса пароля: %ssession/reset-password/%d/%s

Иначе игнорируйте это письмо и ничего не предпринимайте.

Спасибо,

PHPCI',

    'reset_email_title' => 'Сброс пароля PHPCI для %s',
    'reset_invalid' => 'Некорректный запрос на сброс пароля.',
    'email_address' => 'Email-адрес',
    'password' => 'Пароль',
    'log_in' => 'Войти',

    // Top Nav
    'toggle_navigation' => 'Убрать/вернуть панель навигации',
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
    'admin_options' => 'Административное меню',
    'add_project' => 'Добавить проект',
    'settings' => 'Настройки',
    'manage_users' => 'Управление пользователями',
    'plugins' => 'Плагины',
    'view' => 'Просмотр',
    'build_now' => 'Собрать',
    'edit_project' => 'Редактировать проект',
    'delete_project' => 'Удалить проект',

    // Project Summary:
    'no_builds_yet' => 'Нет сборок!',
    'x_of_x_failed' => '%d из последних %d сборок были неудачными.',
    'x_of_x_failed_short' => '%d / %d неудачные.',
    'last_successful_build' => ' Последний успешная сборка была %s.',
    'never_built_successfully' => ' Этот проект никогда не собирался успешно.',
    'all_builds_passed' => 'Все последние сборки (%d) прошли успешно.',
    'all_builds_passed_short' => '%d / %d успешные.',
    'last_failed_build' => ' Последняя неудачная сборка была %s.',
    'never_failed_build' => ' У этого проекта никогда не было неудачных сборок.',
    'view_project' => 'Обзор проекта',

    // Timeline:
    'latest_builds' => 'Последние сборки',
    'pending' => 'Ожидает',
    'running' => 'Запущена',
    'success' => 'Успешно',
    'successful' => 'Успешно',
    'failed' => 'Неудачно',

    // Add/Edit Project:
    'new_project' => 'Новый проект',
    'project_x_not_found' => 'Проекта с ID %d не существует.',
    'project_details' => 'Подробности проекта',
    'public_key_help' => 'Для того, чтобы было легче начать, мы сгенерировали пару SSH-ключей для использования с вашим проектом.
                        Чтобы начать их использовать просто добавьте публичный ключ в поле "deploy keys" на выбраном вами хостинге исходного кода.',
    'select_repository_type' => 'Выберите тип репозитория...',
    'github' => 'Github',
    'bitbucket' => 'Bitbucket',
    'gitlab' => 'Gitlab',
    'remote' => 'Внешний URL',
    'local' => 'Локальный путь',
    'hg'    => 'Mercurial',

    'where_hosted' => 'Где ваш проект располагается?',
    'choose_github' => 'Выберите Github репозиторий:',

    'repo_name' => 'Репозиторий / Внешний URL / Локальный путь',
    'project_title' => 'Название проекта',
    'project_private_key' => 'Приватный ключ для доступа к репозиторию
                                (Оставьте поле пустым для локального использования и/или анонимного доступа)',
    'build_config' => 'Конфигурация сборки вашего проекта для PHPCI
                                (Если вы не добавили файл phpci.yml в репозиторий вашего проекта)',
    'default_branch' => 'Ветка поумолчанию',
    'allow_public_status' => 'Разрешить публичный статус и изображение (статуса) для проекта?',
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
                                and Services</a> вашего Github репозитория.',

    'webhooks_help_gitlab' => 'Чтобы Автоматически собирать этот проект при публикации новых коммитов, добавьте URL ниже в качестве "WebHook URL"
                                в разделе "Web Hooks" вашего Gitlab репозитория.',

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
    'phpmd_warnings' => 'Предупреждения PHPMD',
    'phpcs_warnings' => 'Предупреждения PHPCS',
    'phpcs_errors' => 'Ошибки PHPCS',
    'phplint_errors' => 'Ошибки Lint',
    'phpunit_errors' => 'Ошибки PHPUnit',
    'phpdoccheck_warnings' => 'Пропущенные Docblocks',
    'issues' => 'Проблемы',

    'phpcpd' => 'PHP Copy/Paste Detector',
    'phpcs' => 'PHP Code Sniffer',
    'phpdoccheck' => 'Missing Docblocks',
    'phpmd' => 'PHP Mess Detector',
    'phpspec' => 'PHP Spec',
    'phpunit' => 'PHP Unit',

    'file' => 'Файл',
    'line' => 'Строка',
    'class' => 'Класс',
    'method' => 'Метод',
    'message' => 'Сообщение',
    'start' => 'Запуск',
    'end' => 'Конец',
    'from' => 'From',
    'to' => 'To',
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
    'password_change' => 'Пароль (Оставте поле пустым, если не собираетесь менять его)',
    'save' => 'Сохранить &raquo;',
    'update_your_details' => 'Обновить свои данные',
    'your_details_updated' => 'Ваши данные были обновлены.',
    'add_user' => 'Добавить пользователя',
    'is_admin' => 'Пользователь администратор?',
    'yes' => 'Да',
    'no' => 'нет',
    'edit' => 'Редактировать',
    'edit_user' => 'Редактировать пользователя',
    'delete_user' => 'Удалить пользователя',
    'user_n_not_found' => 'Пользователя с ID %d не существует.',
    'is_user_admin' => 'Этот пользователь администратор?',
    'save_user' => 'Сохранить пользователя',

    // Settings:
    'settings_saved' => 'Ваши настройки были сохранены.',
    'settings_check_perms' => 'Ваши настройки не могут быть сохранены, проверьте права на файл настроек config.yml.',
    'settings_cannot_write' => 'PHPCI не может записать config.yml файл, настройки не могут быть сохранены корректно, пока это не будет исправлено.',
    'settings_github_linked' => 'Ваш Github аккаунт привязан.',
    'settings_github_not_linked' => 'Ваш Github аккаунт не может быть привязан.',
    'build_settings' => 'Настройки сборки',
    'github_application' => 'Github приложение',
    'github_sign_in' => 'Перед тем как начать использовать Github аккаунт, вам необходимо <a href="%s">войти</a> и разрешить доступ для
                            PHPCI до вашего аккаунта.',
    'github_phpci_linked' => 'PHPCI успешно привязал Github аккаунт.',
    'github_where_to_find' => 'Где это найти...',
    'github_where_help' => 'Если вы владелец приложения, которое вы бы хотели использовать, то вы можете найти информацию об этом в разделе <a href="https://github.com/settings/applications">applications</a> настроек.',

    'email_settings' => 'Настройки email',
    'email_settings_help' => 'Перед тем, как PHPCI начнет отсылать статус сборок по почте,
                                вам необходимо настроить параметры SMTP ниже.',

    'application_id' => 'ID приложения',
    'application_secret' => 'Секретный ключ приложения',

    'smtp_server' => 'SMTP сервер',
    'smtp_port' => 'SMTP порт',
    'smtp_username' => 'SMTP пользователь',
    'smtp_password' => 'SMTP пароль',
    'from_email_address' => 'Отправлять с email-адреса',
    'default_notification_address' => 'Email поумолчанию для оповещений',
    'use_smtp_encryption' => 'Использовать SMTP Encryption?',
    'none' => 'Нет',
    'ssl' => 'SSL',
    'tls' => 'TLS',

    'failed_after' => 'Признать сборку неудачной по просшествию',
    '5_mins' => '5 минут',
    '15_mins' => '15 минут',
    '30_mins' => '30 минут',
    '1_hour' => '1 часа',
    '3_hours' => '3 часов',

    // Plugins
    'cannot_update_composer' => 'PHPCI не может обновить composer.json, если он не доступен на запись.',
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
);

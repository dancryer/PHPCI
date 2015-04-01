<?php
/**
 * PHPCI - Continuous Integration for PHP
 *
 * @copyright    Copyright 2014, Block 8 Limited.
 * @license      https://github.com/Block8/PHPCI/blob/master/LICENSE.md
 * @link         https://www.phptesting.org/
 */

$strings = array(
    'language_name' => 'Español',
    'language' => 'Lenguaje',

    // Log in:
    'log_in_to_phpci' => 'Ingresar a PHPCI',
    'login_error' => 'Dirección de email o passowrd incorrecto',
    'forgotten_password_link' => 'Olvidó su contraseña?',
    'reset_emailed' => 'Hemos enviado por email un link para resetar tu contraseña.',
    'reset_header' => '<strong>Don\'t worry!</strong><br>Just enter your email address below and we\'ll email
                            you a link to reset your password.',
    'reset_email_address' => 'Ingrese su dirección de email:',
    'reset_send_email' => 'Email password reset',
    'reset_enter_password' => 'Please enter a new password',
    'reset_new_password' => 'New password:',
    'reset_change_password' => 'Change password',
    'reset_no_user_exists' => 'No existe ningún usuario con esa dirección de email, intente nuevamente.',
    'reset_email_body' => 'Hola %s,

You have received this email because you, or someone else, has requested a password reset for PHPCI.

If this was you, please click the following link to reset your password: %ssession/reset-password/%d/%s

Otherwise, please ignore this email and no action will be taken.

Thank you,

PHPCI',

    'reset_email_title' => 'Reseto de contraseña PHPCI para %s',
    'reset_invalid' => 'Invalid password reset request.',
    'email_address' => 'Dirección de email',
    'password' => 'Contraseña',
    'log_in' => 'Ingresar',


    // Top Nav
    'toggle_navigation' => 'Toggle Navigation',
    'n_builds_pending' => '%d pruebas pendientes',
    'n_builds_running' => '%d pruebas correindo',
    'edit_profile' => 'Editar Perfil',
    'sign_out' => 'Salir',
    'branch_x' => 'Rama: %s',
    'created_x' => 'Creado: %s',
    'started_x' => 'Iniciado: %s',

    // Sidebar
    'hello_name' => 'Hola, %s',
    'dashboard' => 'Panel de control',
    'admin_options' => 'Optiones administrativas',
    'add_project' => 'Agregar Proyecto',
    'settings' => 'Preferencias',
    'manage_users' => 'Administrar Usuarios',
    'plugins' => 'Plugins',
    'view' => 'Ver',
    'build_now' => 'Probar ahora',
    'edit_project' => 'Editar Proyecto',
    'delete_project' => 'Eliminar Proyecto',

    // Project Summary:
    'no_builds_yet' => 'No hubo pruebas todavía!',
    'x_of_x_failed' => '%d de las ultimas %d pruebas fallaron.',
    'x_of_x_failed_short' => '%d / %d fallaron.',
    'last_successful_build' => ' la ultima prueba correcta fue %s.',
    'never_built_successfully' => ' Este proyecto nunca fue probado satisfactoriamente.',
    'all_builds_passed' => 'Todas las ultimas %d pruebas fallaron.',
    'all_builds_passed_short' => '%d / %d correctas.',
    'last_failed_build' => ' La ultima prueba fallida fue %s.',
    'never_failed_build' => ' Este proyecto nunca fallo al construirse.',
    'view_project' => 'Ver Proyecto',

    // Timeline:
    'latest_builds' => 'Ultimas pruebas',
    'pending' => 'Pendiente',
    'running' => 'Corriendo',
    'success' => 'Correcto',
    'successful' => 'Correctamente',
    'failed' => 'Fallo',
    'manual_build' => 'Testo manual',

    // Add/Edit Project:
    'new_project' => 'Nuevo Proyecto',
    'project_x_not_found' => 'Project with ID %d does not exist.',
    'project_details' => 'Detalles del proyecto',
    'public_key_help' => 'To make it easier to get started, we\'ve generated an SSH key pair for you to use
                            for this project. To use it, just add the following public key to the "deploy keys" section
                            of your chosen source code hosting platform.',
    'select_repository_type' => 'Seleccion el tipo de repositorio...',
    'github' => 'GitHub',
    'bitbucket' => 'Bitbucket',
    'gitlab' => 'GitLab',
    'remote' => 'URL Remota',
    'local' => 'Ruta Local',
    'hg'    => 'Mercurial',
    'svn'   => 'Subversion',

    'where_hosted' => 'Donde esta localizado tu proyecto?',
    'choose_github' => 'Elija un repostiorio de GitHub:',

    'repo_name' => 'Repository Name / URL (Remote) or Path (Local)',
    'project_title' => 'Titulo del proyecto',
    'project_private_key' => 'Private key to use to access repository
                                (leave blank for local and/or anonymous remotes)',
    'build_config' => 'PHPCI build config for this project
                                (if you cannot add a phpci.yml file in the project repository)',
    'default_branch' => 'Default branch name',
    'allow_public_status' => 'Enable public status page and image for this project?',
    'archived' => 'Archivado',
    'save_project' => 'Guardar Proyecto',

    'error_mercurial' => 'Mercurial repository URL must be start with http:// or https://',
    'error_remote' => 'Repository URL must be start with git://, http:// or https://',
    'error_gitlab' => 'GitLab Repository name must be in the format "user@domain.tld:owner/repo.git"',
    'error_github' => 'Repository name must be in the format "owner/repo"',
    'error_bitbucket' => 'Repository name must be in the format "owner/repo"',
    'error_path' => 'The path you specified does not exist.',

    // View Project:
    'all_branches' => 'Todas las ramas',
    'builds' => 'Pruebas',
    'id' => 'ID',
    'date' => 'Fecha',
    'project' => 'Proyecto',
    'commit' => 'Confirmación',
    'branch' => 'Rama',
    'status' => 'Estado',
    'prev_link' => '&laquo; Prev',
    'next_link' => 'Sig &raquo;',
    'public_key' => 'Clave publica',
    'delete_build' => 'Eliminar prueba',

    'webhooks' => 'Webhooks',
    'webhooks_help_github' => 'To automatically build this project when new commits are pushed, add the URL below
                                as a new "Webhook" in the <a href="https://github.com/%s/settings/hooks">Webhooks
                                and Services</a>  section of your GitHub repository.',

    'webhooks_help_gitlab' => 'To automatically build this project when new commits are pushed, add the URL below
                                as a "WebHook URL" in the Web Hooks section of your GitLab repository.',

    'webhooks_help_bitbucket' => 'To automatically build this project when new commits are pushed, add the URL below
                                as a "POST" service in the
                                <a href="https://bitbucket.org/%s/admin/services">
                                Services</a> section of your Bitbucket repository.',

    // View Build
    'build_x_not_found' => 'Build with ID %d does not exist.',
    'build_n' => 'Prueba %d',
    'rebuild_now' => 'Reintentar prueba ahora!',


    'committed_by_x' => 'Enviado por %s',
    'commit_id_x' => 'Confirmación: %s',

    'chart_display' => 'Este grafico se mostrara una vez que la prueba este completa.',

    'build' => 'Prueba',
    'lines' => 'Lineas',
    'comment_lines' => 'Lineas de Comentario',
    'noncomment_lines' => 'Lineas No Comentadas',
    'logical_lines' => 'Lineas de Logica',
    'lines_of_code' => 'Lineas de Codigo',
    'build_log' => 'Registro de prueba',
    'quality_trend' => 'Tendencia de calidad',
    'codeception_errors' => 'Errores de Codeception',
    'phpmd_warnings' => 'Alertas de PHPMD',
    'phpcs_warnings' => 'Alertas de PHPCS',
    'phpcs_errors' => 'Errores de PHPCS',
    'phplint_errors' => 'Errores de Lint',
    'phpunit_errors' => 'Errores de PHPUnit',
    'phpdoccheck_warnings' => 'Bloques de comentarios faltantes',
    'issues' => 'Incidencias',

    'codeception' => 'Codeception',
    'phpcpd' => 'Detector de Copiar/Pegar',
    'phpcs' => 'Rastreador de Código PHP',
    'phpdoccheck' => 'Bloques de Documentación faltantes',
    'phpmd' => 'Detector de desastres PHP',
    'phpspec' => 'Especificaciones PHP',
    'phpunit' => 'PHP Unit',
    'technical_debt' => 'Deuda técnica',
    'behat' => 'Behat',

    'file' => 'Archivo',
    'line' => 'Linea',
    'class' => 'Clase',
    'method' => 'Metodo',
    'message' => 'Mensaje',
    'start' => 'Inicio',
    'end' => 'Fin',
    'from' => 'Desde',
    'to' => 'Hasta',
    'suite' => 'Suite',
    'test' => 'Prueba',
    'result' => 'Resultado',
    'ok' => 'OK',
    'took_n_seconds' => 'Tardó %d segundos',
    'build_created' => 'Creada',
    'build_started' => 'Iniciada',
    'build_finished' => 'Finalizada',

    // Users
    'name' => 'Nombre',
    'password_change' => 'Password (leave blank if you don\'t want to change)',
    'save' => 'Guardar &raquo;',
    'update_your_details' => 'Actualizar tus detalles',
    'your_details_updated' => 'Tus detalles han sido actualizados.',
    'add_user' => 'Agregar usuario',
    'is_admin' => 'Es Administrador?',
    'yes' => 'Si',
    'no' => 'No',
    'edit' => 'Editar',
    'edit_user' => 'Editar Usuario',
    'delete_user' => 'Eliminar Usuario',
    'user_n_not_found' => 'Usuario con ID %d no existe.',
    'is_user_admin' => 'Es este usuario un administrador?',
    'save_user' => 'Guardar usuario',

    // Settings:
    'settings_saved' => 'Las preferencias han sido guardadas.',
    'settings_check_perms' => 'Your settings could not be saved, check the permissions of your config.yml file.',
    'settings_cannot_write' => 'PHPCI cannot write to your config.yml file, settings may not be saved properly
                                until this is rectified.',
    'settings_github_linked' => 'Your GitHub account has been linked.',
    'settings_github_not_linked' => 'Your GitHub account could not be linked.',
    'build_settings' => 'Preferencias de pruebas',
    'github_application' => 'Aplicacion de GitHub',
    'github_sign_in' => 'Before you can start using GitHub, you need to <a href="%s">sign in</a> and grant
                            PHPCI access to your account.',
    'github_phpci_linked' => 'PHPCI is successfully linked to GitHub account.',
    'github_where_to_find' => 'Where to find these...',
    'github_where_help' => 'If you own the application you would like to use, you can find this information within your
                            <a href="https://github.com/settings/applications">applications</a> settings area.',

    'email_settings' => 'Preferencias de correo electronico',
    'email_settings_help' => 'Before PHPCI can send build status emails,
                                you need to configure your SMTP settings below.',

    'application_id' => 'Application ID',
    'application_secret' => 'Application Secret',

    'smtp_server' => 'Servidor SMTP',
    'smtp_port' => 'Puerto SMTP ',
    'smtp_username' => 'Nombre de usuario SMTP',
    'smtp_password' => 'Contraseña SMTP',
    'from_email_address' => 'From Email Address',
    'default_notification_address' => 'Default Notification Email Address',
    'use_smtp_encryption' => 'Usar encriptacion SMTP?',
    'none' => 'Ninguno',
    'ssl' => 'SSL',
    'tls' => 'TLS',

    'failed_after' => 'Considerar una prueba como fallada luego de',
    '5_mins' => '5 Minutos',
    '15_mins' => '15 Minutos',
    '30_mins' => '30 Minutos',
    '1_hour' => '1 Hora',
    '3_hours' => '3 Horas',

    // Plugins
    'cannot_update_composer' => 'PHPCI no puede actualizar composer.json ya que no es un archivo escribible.',
    'x_has_been_removed' => '%s ha sido removido.',
    'x_has_been_added' => '%s ha sido agreado a composer.json y sera instalado la proxima vez que corras composer update.',
    'enabled_plugins' => 'Plugins Habilitados',
    'provided_by_package' => 'Provistos por Package',
    'installed_packages' => 'Paquetes Instalados',
    'suggested_packages' => 'Paquetes sugeridos',
    'title' => 'Titulo',
    'description' => 'Descripcion',
    'version' => 'Version',
    'install' => 'Instalar &raquo;',
    'remove' => 'Eliminar &raquo;',
    'search_packagist_for_more' => 'Search Packagist for more packages',
    'search' => 'Buscar &raquo;',

    // Installer
    'installation_url' => 'PHPCI InstallationPend URL',
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
    'enter_name' => 'Admin Name: ',
    'enter_email' => 'Admin Email: ',
    'enter_password' => 'Admin Password: ',
    'enter_phpci_url' => 'Your PHPCI URL ("http://phpci.local" for example): ',

    'enter_db_host' => 'Please enter your MySQL host [localhost]: ',
    'enter_db_name' => 'Please enter your MySQL database name [phpci]: ',
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
    'poll_github' => 'Poll GitHub to check if we need to start a build.',
    'no_token' => 'No GitHub token found',
    'finding_projects' => 'Finding projects to poll',
    'found_n_projects' => 'Found %d projects',
    'last_commit_is' => 'Last commit to GitHub for %s is %s',
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
    'removing_build' => 'Eliminando prueba.',
    'exception' => 'Exepcion: ',
    'could_not_create_working' => 'No se pudo crear la copia de trabajo.',
    'working_copy_created' => 'Working copy created: %s',
    'looking_for_binary' => 'Looking for binary: %s',
    'found_in_path' => 'Found in %s: %s',
    'running_plugin' => 'CORRIENDO PLUGIN: %s',
    'plugin_success' => 'PLUGIN: CORRECTO',
    'plugin_failed' => 'PLUGIN: FALLO',
    'plugin_missing' => 'El Plugin %s no existe',
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

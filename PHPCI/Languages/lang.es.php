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
    'login_error' => 'Email o contraseña incorrectos',
    'forgotten_password_link' => '¿Olvidaste tu contraseña?',
    'reset_emailed' => 'Te hemos enviado un email para reiniciar tu contraseña.',
    'reset_header' => '<strong>¡No te preocupes!</strong><br>Solo tienes que ingresar tu dirección de email
                            y te enviaremos por email un enlace para reiniciar tu contraseña.',
    'reset_email_address' => 'Ingresa tu dirección de email:',
    'reset_send_email' => 'Enviar enlace',
    'reset_enter_password' => 'Ingresa una nueva contraseña',
    'reset_new_password' => 'Nueva contraseña:',
    'reset_change_password' => 'Cambiar contraseña',
    'reset_no_user_exists' => 'No existe ningún usuario con ese email, por favor intenta  nuevamente.',
    'reset_email_body' => 'Hola %s,

Has recibido este correo porque tú, o alguien más, ha solicitado reiniciar la contraseña de PHPCI

Si fuiste tú, por favor haz click en el siguiente enlace para reiniciar tu contraseña: %ssession/reset-password/%d/%s

De lo contrario, por favor ignora este correo y ninguna acción será realizada.

Gracias,

PHPCI',

    'reset_email_title' => 'Reiniciar contraseña de PHPCI para %s',
    'reset_invalid' => 'Pedido inválido.',
    'email_address' => 'Dirección de email',
    'password' => 'Contraseña',
    'log_in' => 'Ingresar',


    // Top Nav
    'toggle_navigation' => 'Activar navegación',
    'n_builds_pending' => '%d builds pendientes',
    'n_builds_running' => '%d builds ejecutándose',
    'edit_profile' => 'Editar Perfil',
    'sign_out' => 'Cerrar Sesión',
    'branch_x' => 'Rama: %s',
    'created_x' => 'Creada el: %s',
    'started_x' => 'Comenzó: %s',

    // Sidebar
    'hello_name' => 'Hola, %s',
    'dashboard' => 'Escritorio',
    'admin_options' => 'Opciones de Admin.',
    'add_project' => 'Agregar Proyecto',
    'settings' => 'Configuración',
    'manage_users' => 'Administrar Usuarios',
    'plugins' => 'Plugins',
    'view' => 'Vista',
    'build_now' => 'Ejecutar Build',
    'edit_project' => 'Editar Proyecto',
    'delete_project' => 'Eliminar Proyecto',

    // Project Summary:
    'no_builds_yet' => '¡No existen builds aún!',
    'x_of_x_failed' => '%d de los últimos %d builds fallaron.',
    'x_of_x_failed_short' => '%d / %d fallaron.',
    'last_successful_build' => ' El último build exitoso fue %s.',
    'never_built_successfully' => ' Este proyecto nunca tuvo un build exitoso.',
    'all_builds_passed' => 'Todos los últimos %d builds pasaron.',
    'all_builds_passed_short' => '%d / %d pasaron.',
    'last_failed_build' => ' El último build en fallar fue %s.',
    'never_failed_build' => ' Este proyecto no tiene ningún build fallido.',
    'view_project' => 'Ver Proyecto',

    // Timeline:
    'latest_builds' => 'Últimos builds',
    'pending' => 'Pediente',
    'running' => 'Ejecutando',
    'success' => 'Éxito',
    'successful' => 'Exitoso',
    'failed' => 'Falló',
    'manual_build' => 'Build Manual',

    // Add/Edit Project:
    'new_project' => 'Nuevo Proyecto',
    'project_x_not_found' => 'El Proyecto con ID %d no existe.',
    'project_details' => 'Detalles del Proyecto',
    'public_key_help' => 'Para facilitarte, hemos generado un par de llaves SSH para que uses en este proyecto.
    Para usarlo, sólo agrega la siguiente llave pública a la sección de "deploy keys"
                            de tu plataforma de hosting de versionado de código.',
    'select_repository_type' => 'Selecciona tipo de repositorio...',
    'github' => 'GitHub',
    'bitbucket' => 'Bitbucket',
    'gitlab' => 'GitLab',
    'remote' => 'URL Remota',
    'local' => 'Path local',
    'hg'    => 'Mercurial',
    'svn'   => 'Subversion',

    'where_hosted' => '¿Dónde está alojado tu proyecto?',
    'choose_github' => 'Selecciona un repositorio de GitHub:',

    'repo_name' => 'Nombre del repositorio / URL (Remoto) o Ruta (Local)',
    'project_title' => 'Titulo del proyecto',
    'project_private_key' => 'Clave privada a usar para acceder al repositorio
                                (dejar en blanco para remotos locales o anónimos)',
    'build_config' => 'Configuración PHPCI para builds del proyecto
                                (en caso que no puedas agregar el archivo phpci.yml al repositorio)',
    'default_branch' => 'Nombre de la rama por defecto',
    'allow_public_status' => '¿Activar página pública con el estado del proyecto?',
    'archived' => 'Archivado',
    'archived_menu' => 'Archivado',
    'save_project' => 'Guardar Proyecto',

    'error_mercurial' => 'La URL del repositorio de Mercurial debe comenzar con http:// or https://',
    'error_remote' => 'La URL del repositorio debe comenzar con git://, http:// or https://',
    'error_gitlab' => 'El nombre del repositorio de GitLab debe tener el formato "user@domain.tld:owner/repo.git"',
    'error_github' => 'El nombre del repositorio debe tener el formato "owner/repo"',
    'error_bitbucket' => 'El nombre del repo debe tener el formato "owner/repo"',
    'error_path' => 'La ruta especificada no existe.',

    // View Project:
    'all_branches' => 'Todas las ramas',
    'builds' => 'Builds',
    'id' => 'ID',
    'project' => 'Proyecto',
    'commit' => 'Commit',
    'branch' => 'Rama',
    'status' => 'Estado',
    'prev_link' => '&laquo; Anterior',
    'next_link' => 'Siguiente &raquo;',
    'public_key' => 'Llave pública',
    'delete_build' => 'Eliminar Build',

    'webhooks' => 'Webhooks',
    'webhooks_help_github' => 'Para compilar automáticamente este proyecto cada vez que se realiza un commit, agreagar la siguiente URL
                                como un nuevo "webhook" en la sección <a href="https://github.com/%s/settings/hooks">Webhooks
                                and Services</a>  de tu repositorio en GitHub.',

    'webhooks_help_gitlab' => 'Para compilar automáticamente este proyecto, cada vez que se realiza un commit, agreagar la siguiente URL
                                como una "WebHook URL" en la sección "web hooks" de tu repositorio en GitLab.',

    'webhooks_help_bitbucket' => 'Para compilar automáticamente este proyecto, cada vez que se realiza un commit, agreagar la siguiente URL
                                como un servicio "POST" en la sección
                                <a href="https://bitbucket.org/%s/admin/services">
                                Services</a> de tu repositorio en Bitbucket.',

    // View Build
    'build_x_not_found' => 'El build con ID %d no existe.',
    'build_n' => 'Build %d',
    'rebuild_now' => 'Rebuild Ahora',


    'committed_by_x' => 'Commit hecho por %s',
    'commit_id_x' => 'Commit: %s',

    'chart_display' => 'Este gráfico será mostrado una vez que el build se haya completado.',

    'build' => 'Build',
    'lines' => 'Líneas',
    'comment_lines' => 'Líneas de comentario',
    'noncomment_lines' => 'Líneas no comentario',
    'logical_lines' => 'Líneas lógicas',
    'lines_of_code' => 'Líneas de código',
    'build_log' => 'Log',
    'quality_trend' => 'Tendencia de calidad',
    'codeception_errors' => 'Errores de Codeception',
    'phpmd_warnings' => 'PHPMD Warnings',
    'phpcs_warnings' => 'PHPCS Warnings',
    'phpcs_errors' => 'PHPCS Errors',
    'phplint_errors' => 'Lint Errors',
    'phpunit_errors' => 'PHPUnit Errors',
    'phpdoccheck_warnings' => 'Docblocks faltantes',
    'issues' => 'Incidencias',

    'codeception' => 'Codeception',
    'phpcpd' => 'PHP Copy/Paste Detector',
    'phpcs' => 'PHP Code Sniffer',
    'phpdoccheck' => 'Missing Docblocks',
    'phpmd' => 'PHP Mess Detector',
    'phpspec' => 'PHP Spec',
    'phpunit' => 'PHP Unit',
    'technical_debt' => 'Deuda Técnica',
    'behat' => 'Behat',

    'file' => 'Archivo',
    'line' => 'Línea',
    'class' => 'Clase',
    'method' => 'Método',
    'message' => 'Mensaje',
    'start' => 'Inicio',
    'end' => 'Fin',
    'from' => 'De',
    'to' => 'Para',
    'suite' => 'Suite',
    'test' => 'Test',
    'result' => 'Resultado',
    'ok' => 'OK',
    'took_n_seconds' => 'Tomó %d segundos',
    'build_created' => 'Build Creado',
    'build_started' => 'Build Comenzado',
    'build_finished' => 'Build Terminado',

    // Users
    'name' => 'Nombre',
    'password_change' => 'Contraseña (dejar en blanco si no quiere cambiarla)',
    'save' => 'Guardar &raquo;',
    'update_your_details' => 'Actualizar los detalles',
    'your_details_updated' => 'Tus detalles han sido actualizados.',
    'add_user' => 'Agregar Usuario',
    'is_admin' => '¿Es Admin?',
    'yes' => 'Si',
    'no' => 'No',
    'edit' => 'Editar',
    'edit_user' => 'Editar Usuario',
    'delete_user' => 'Delete Usuario',
    'user_n_not_found' => 'Usuario con ID %d no existe.',
    'is_user_admin' => '¿Es un usuario administrador?',
    'save_user' => 'Guardar Usuario',

    // Settings:
    'settings_saved' => 'Tu configuración ha sido guardada.',
    'settings_check_perms' => 'Tu configuración no fue guardada, verificar los permisos del archivo config.yml.',
    'settings_cannot_write' => 'PHPCI no puede escribir en el archivo config.yml file, la configuración no será guardada correctamente
                                hasta no corregir esto.',
    'settings_github_linked' => 'Tu cuenta GitHub ha sido conectada.',
    'settings_github_not_linked' => 'No se pudo conectar a tu cuenta GitHub.',
    'build_settings' => 'Configuración del Build ',
    'github_application' => 'Aplicación GitHub',
    'github_sign_in' => 'Antes de comenzar a utilizar GitHub, tienes que <a href="%s">ingresar</a> y permitir
                            el acceso a tu cuenta a PHPCI.',
    'github_phpci_linked' => 'PHPCI ha sido conectado a tu cuenta GitHub.',
    'github_where_to_find' => 'Donde encontrar estos...',
    'github_where_help' => 'Si eres priopietario de la aplicaión que quieres usar, puedes encontrar esta información en
                            el área de configuración de <a href="https://github.com/settings/applications">aplicaciones</a>.',

    'email_settings' => 'Configuraciones de Email',
    'email_settings_help' => 'Para que PHPCI pueda enviar email con el status de los builds,
                                debes configurar las siguientes propiedades SMTP.',

    'application_id' => 'ID de aplicación',
    'application_secret' => 'Application Secret',

    'smtp_server' => 'Servidor SMTP',
    'smtp_port' => 'Puerto SMTP',
    'smtp_username' => 'Usuario SMTP',
    'smtp_password' => 'Contraseña SMTP',
    'from_email_address' => 'Dirección de email DE',
    'default_notification_address' => 'Dirección de correo de notificación por defecto',
    'use_smtp_encryption' => 'Usar encriptación SMTP?',
    'none' => 'None',
    'ssl' => 'SSL',
    'tls' => 'TLS',

    'failed_after' => 'Considerar el build como fallido luego de ',
    '5_mins' => '5 Minutos',
    '15_mins' => '15 Minutos',
    '30_mins' => '30 Minutos',
    '1_hour' => '1 Hora',
    '3_hours' => '3 Horas',

    // Plugins
    'cannot_update_composer' => 'PHPCI no puede actualizar composer.json porque no tiene permisos de escritura.',
    'x_has_been_removed' => '%s ha sido elimiando.',
    'x_has_been_added' => '%s ha sido agregado a composer.json y será instalado la próxima vez que ejecutes composer update.',
    'enabled_plugins' => 'Activar Plugins',
    'provided_by_package' => 'Provisto por Paquete',
    'installed_packages' => 'Paquetes Instalados',
    'suggested_packages' => 'Paquetes Sugeridos',
    'title' => 'Título',
    'description' => 'Descripción',
    'version' => 'Versión',
    'install' => 'Instalar &raquo;',
    'remove' => 'Eliminar &raquo;',
    'search_packagist_for_more' => 'Buscar más paquetes en Packagist',
    'search' => 'Buscar &raquo;',

    // Installer
    'installation_url' => 'URL de la instalación PHPCI',
    'db_host' => 'Host',
    'db_name' => 'Nombre de la base de datos',
    'db_user' => 'Usuario de la base de datos',
    'db_pass' => 'Clave de la base de datos',
    'admin_name' => 'Nombre del Admin',
    'admin_pass' => 'Clave del Admin',
    'admin_email' => 'Email de Admin',
    'config_path' => 'Ruta al archivo config',
    'install_phpci' => 'Instalar PHPCI',
    'welcome_to_phpci' => 'Bienvenido a PHPCI',
    'please_answer' => 'Por favor, responde las siguientes preguntas:',
    'phpci_php_req' => 'PHPCI requiere al menos PHP 5.3.8 para funcionar.',
    'extension_required' => 'Extensión requerida: %s',
    'function_required' => 'PHPCI debe poder invocar la función %s(). Está deshabilitada en php.ini?',
    'requirements_not_met' => 'PHPCI no pudo ser instalado, ya que no se cumplen todos los requerimientos.
                                Por favor, corrige los errores antes de continuar.',
    'must_be_valid_email' => 'Debe ser una dirección de correos válida.',
    'must_be_valid_url' => 'Debe ser una URL válida.',
    'enter_name' => 'Nombre del Admin:',
    'enter_email' => 'Email del Admin:',
    'enter_password' => 'Contraseña de Admin:',
    'enter_phpci_url' => 'La URL de PHPCI ("Por ejemplo: http://phpci.local"): ',

    'enter_db_host' => 'Por favor, ingresa el servidor MySQL [localhost]: ',
    'enter_db_name' => 'Por favor, ingresa el nombre de la base de datos MySQL [phpci]: ',
    'enter_db_user' => 'Por favor, ingresa el usuario MySQL [phpci]: ',
    'enter_db_pass' => 'Por favor, ingresa la contraseña MySQL: ',
    'could_not_connect' => 'PHPCI no pudo conectarse a MySQL con los datos dados. Por favor, intenta nuevamente.',
    'setting_up_db' => 'Configurando base de datos... ',
    'user_created' => '¡Cuenta de usuario creada!',
    'failed_to_create' => 'PHPCI no pudo crear la cuenta de admin.',
    'config_exists' => 'El archivo config de PHPCI ya existe y no es vacío.',
    'update_instead' => 'Si está intentando actualizar PHPCI, por favor, utiliza phpci:update.',

    // Update
    'update_phpci' => 'Actuliza la base de datos para reflejar los modelos actualizados.',
    'updating_phpci' => 'Actualizando base de datos PHPCI: ',
    'not_installed' => 'PHPCI no está instalado.',
    'install_instead' => 'Por favor, instala PHPCI via phpci:install.',

    // Poll Command
    'poll_github' => 'Chequear en GitHub si se necesita comenzar un Build.',
    'no_token' => 'No se encontró ningún token GitHub',
    'finding_projects' => 'Buscando proyectos para chequear',
    'found_n_projects' => 'Se encontraron %d proyectos',
    'last_commit_is' => 'El último commit en GitHub para %s es %s',
    'adding_new_build' => 'Último commit es diferente a la base de datos, agregando nuevo build.',
    'finished_processing_builds' => 'Fin de procesamiento de builds.',

    // Create Admin
    'create_admin_user' => 'Crear un usuario Admin',
    'incorrect_format' => 'Formato incorrecto',

    // Run Command
    'run_all_pending' => 'Ejecutar todos los builds PHPCI pendientes.',
    'finding_builds' => 'Buscando builds a procesar',
    'found_n_builds' => 'Se encontraron %d builds',
    'skipping_build' => 'Saltando Build %d - Build del proyecto ya en ejecución.',
    'marked_as_failed' => 'Build %d falló debido a timeout.',

    // Builder
    'missing_phpci_yml' => 'Este proyecto no contiene el archivo phpci.yml o está vacío.',
    'build_success' => 'BUILD EXITOSO',
    'build_failed' => 'BUILD FALLIDO',
    'removing_build' => 'Eliminando Build.',
    'exception' => 'Excepción: ',
    'could_not_create_working' => 'Imposible crear copia de trabajo.',
    'working_copy_created' => 'Copia de trabajo creada: %s',
    'looking_for_binary' => 'Buscando binario: %s',
    'found_in_path' => 'Encontrado en %s: %s',
    'running_plugin' => 'EJECUTANDO PLUGIN: %s',
    'plugin_success' => 'PLUGIN: EXITO',
    'plugin_failed' => 'PLUGIN: FALLÓ',
    'plugin_missing' => 'No existe el plugin: %s',
    'tap_version' => 'TapParser únicamente soporta la verisón 13 de TAP',
    'tap_error' => 'Cadena de caracteres TAP inválida, el número de tests no coincide con la cuenta de tests declarada.',

    // Build Plugins:
    'no_tests_performed' => 'No se ejecutaron tests.',
    'could_not_find' => 'No se encontró %s',
    'no_campfire_settings' => 'No se especificaron parámetros de conexión para el plugin Campfire',
    'failed_to_wipe' => 'Imposible eliminar directorio existente %s antes de copiarlo',
    'passing_build' => 'Build Exitoso',
    'failing_build' => 'Build Fallido',
    'log_output' => 'Log de Salida: ',
    'n_emails_sent' => '%d emails enviados.',
    'n_emails_failed' => '%d emails no pudieron ser enviados.',
    'unable_to_set_env' => 'Imposible setear variable de entorno',
    'tag_created' => 'Tag creado por PHPCI: %s',
    'x_built_at_x' => 'Build de %PROJECT_TITLE% en %BUILD_URI%',
    'hipchat_settings' => 'Por favor, definir room y authToken para el plugin hipchat_notify',
    'irc_settings' => 'Debes configurar un servidor, room y apodo.',
    'invalid_command' => 'Comando inválido',
    'import_file_key' => 'Sentencia de importación debe contener una llave \'file\'',
    'cannot_open_import' => 'Imposible abrir archivo de importación SQL: %s',
    'unable_to_execute' => 'Imposible ejecutar archivo SQL',
    'phar_internal_error' => 'Error interno en plugin Phar',
    'build_file_missing' => 'El archivo de build especificado no existe.',
    'property_file_missing' => 'El archivo de propiedades especificado no existe.',
    'could_not_process_report' => 'Imposible procesar el reporte generado por la herramienta.',
    'shell_not_enabled' => 'El plugin shell no está habilitado. Por favor, habilitalo desde config.yml.'
);

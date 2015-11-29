<?php
/**
 * PHPCI - Continuous Integration for PHP
 *
 * @copyright    Copyright 2014, Block 8 Limited.
 * @license      https://github.com/Block8/PHPCI/blob/master/LICENSE.md
 * @link         https://www.phptesting.org/
 */

$strings = array(
    'language_name' => 'Português - Brasil',
    'language' => 'Idioma',

    // Log in:
    'log_in_to_phpci' => 'Log in to PHPCI',
    'login_error' => 'Email ou senha incorretos',
    'forgotten_password_link' => 'Esqueceu sua senha?',
    'reset_emailed' => 'Enviamos um email para redefinir sua senha.',
    'reset_header' => '<strong>¡Não se preocupe!</strong><br>Apenas informe seu endereço de email
                            e te enviaremos um link para redefinir seua senha.',
    'reset_email_address' => 'Digite seu endereço de e-mail:',
    'reset_send_email' => 'Enviar link',
    'reset_enter_password' => 'Digite uma nova senha',
    'reset_new_password' => 'Nova Senha:',
    'reset_change_password' => 'Alterar a senha',
    'reset_no_user_exists' => 'Não existe nenhum usuario com este email, por favor tente novamente.',
    'reset_email_body' => 'Olá %s,

Você recebeu este e-mail porque você ou alguém tenha pedido de redefinição de senha no PHPCI

Se fosse você, por favor, clique no seguinte link para redefinir sua senha: %ssession/reset-password/%d/%s

Do contrário, por favor ignore este email e nenhuma ação será realizada.

Obrigado,

PHPCI',

    'reset_email_title' => 'Redefinir senha do PHPCI para %s',
    'reset_invalid' => 'Pedido inválido.',
    'email_address' => 'Endereço de email',
    'password' => 'Senha',
    'login' => 'Login / Email Address',
    'password' => 'Senha',
    'log_in' => 'Enviar',


    // Top Nav
    'toggle_navigation' => 'Ativar navegação',
    'n_builds_pending' => '%d builds pendentes',
    'n_builds_running' => '%d builds sendo executados',
    'edit_profile' => 'Editar Perfil',
    'sign_out' => 'Encerrar Sessão',
    'branch_x' => 'Branch: %s',
    'created_x' => 'Criada em: %s',
    'started_x' => 'Iniciado em: %s',

    // Sidebar
    'hello_name' => 'Olá, %s',
    'dashboard' => 'Painel',
    'admin_options' => 'Opções de Admin.',
    'add_project' => 'Adicionar Projeto',
    'settings' => 'Configuração',
    'manage_users' => 'Administrar Usuários',
    'plugins' => 'Plugins',
    'view' => 'Visão',
    'build_now' => 'Executar Build',
    'edit_project' => 'Editar Projeto',
    'delete_project' => 'Excluir Projeto',

    // Project Summary:
    'no_builds_yet' => 'Ainda não existem builds!',
    'x_of_x_failed' => '%d dos últimos %d builds falharam.',
    'x_of_x_failed_short' => '%d / %d falharam.',
    'last_successful_build' => ' O último build com sucesso foi %s.',
    'never_built_successfully' => ' Este projeto nunca teve um build com sucesso.',
    'all_builds_passed' => 'Todos os últimos %d builds passaram.',
    'all_builds_passed_short' => '%d / %d passaram.',
    'last_failed_build' => ' O último build que falhou foi %s.',
    'never_failed_build' => ' Este projeto não tem nenhum build com falha.',
    'view_project' => 'Ver Projeto',

    // Timeline:
    'latest_builds' => 'Últimos builds',
    'pending' => 'Pendente',
    'running' => 'Executando',
    'success' => 'Sucesso',
    'successful' => 'Com sucesso',
    'failed' => 'Falhou',
    'manual_build' => 'Build Manual',

    // Add/Edit Project:
    'new_project' => 'Novo Projeto',
    'project_x_not_found' => 'O Projeto com ID %d não existe.',
    'project_details' => 'Detalhes do Projeto',
    'public_key_help' => 'Para facilitar, geramos um par de chaves SSH para serem usadas neste projeto.
    Para utilizá-las, acrescente a seguinte chave pública à seção de "deploy keys"
                            em sua plataforma de versionamento de código.',
    'select_repository_type' => 'Selecionar o tipo de repositório...',
    'github' => 'GitHub',
    'bitbucket' => 'Bitbucket',
    'gitlab' => 'GitLab',
    'remote' => 'URL Remota',
    'local' => 'Diretório local',
    'hg'    => 'Mercurial',
    'svn'   => 'Subversion',

    'where_hosted' => 'Onde está hospedado seu projeto?',
    'choose_github' => 'Selecione um repositório do GitHub:',

    'repo_name' => 'Nome do repositório / URL (Remoto) ou rota (Local)',
    'project_title' => 'Titulo do projeto',
    'project_private_key' => 'Chave privada para acessar o repositório
                                (deixar em branco para repositórios locais ou anônimos)',
    'build_config' => 'Configuração PHPCI para builds do projeto
                                (caso não possa adicionar o arquivo phpci.yml ao repositório)',
    'default_branch' => 'Nome do branch (ramo) padrão',
    'allow_public_status' => 'Habilitar página pública com o estado do projeto?',
    'archived' => 'Arquivar',
    'archived_menu' => 'Arquivo',
    'save_project' => 'Salvar Projeto',

    'error_mercurial' => 'A URL do repositório deve começar com http:// ou https://',
    'error_remote' => 'A URL do repositório deve começar com git://, http:// ou https://',
    'error_gitlab' => 'O nome do repositório do GitLab deve estar no formato "user@domain.tld:owner/repo.git"',
    'error_github' => 'O nome do repositório deve ter o formato "owner/repo"',
    'error_bitbucket' => 'O nome do repositório deve ter o formato "owner/repo"',
    'error_path' => 'A rota especificada não existe.',

    // View Project:
    'all_branches' => 'Todas os branches',
    'builds' => 'Builds',
    'id' => 'ID',
    'project' => 'Projeto',
    'commit' => 'Commit',
    'branch' => 'Branch',
    'status' => 'Status',
    'prev_link' => '&laquo; Anterior',
    'next_link' => 'Seguinte &raquo;',
    'public_key' => 'Chave pública',
    'delete_build' => 'Eliminar Build',

    'webhooks' => 'Webhooks',
    'webhooks_help_github' => 'Para compilar automaticamente este projeto cada vez que seja feito um commit, adicionar a seguiente URL
                                como um novo "webhook" na seção <a href="https://github.com/%s/settings/hooks">Webhooks
                                and Services</a> do seu repositório no GitHub.',

    'webhooks_help_gitlab' => 'Para compilar automaticamente este projeto cada vez que seja feito um commit, adicionar a seguiente URL
                                como um novo "WebHook URL" na seção "web hooks" do seu repositório no GitLab.',

    'webhooks_help_bitbucket' => 'Para compilar automaticamente este projeto cada vez que seja feito um commit, adicionar a seguiente URL
                                como um serviço "POST" na seção
                                <a href="https://bitbucket.org/%s/admin/services">
                                Services</a> do seu repositório no Bitbucket.',

    // View Build
    'build_x_not_found' => 'O build com ID %d não existe.',
    'build_n' => 'Build %d',
    'rebuild_now' => 'Rebuild agora',


    'committed_by_x' => 'Commit feito por %s',
    'commit_id_x' => 'Commit: %s',

    'chart_display' => 'Este gráfico será mostrado quando o build estiver completo.',

    'build' => 'Build',
    'lines' => 'Linhas',
    'comment_lines' => 'Linhas de comentário',
    'noncomment_lines' => 'Linhas não comentadas',
    'logical_lines' => 'Linhas lógicas',
    'lines_of_code' => 'Linhas de código',
    'build_log' => 'Log',
    'quality_trend' => 'Tendência de qualidade',
    'codeception_errors' => 'Erros de Codeception',
    'phpmd_warnings' => 'PHPMD Warnings',
    'phpcs_warnings' => 'PHPCS Warnings',
    'phpcs_errors' => 'PHPCS Errors',
    'phplint_errors' => 'Lint Errors',
    'phpunit_errors' => 'PHPUnit Errors',
    'phpdoccheck_warnings' => 'Docblocks faltando',
    'issues' => 'Incidências',

    'codeception' => 'Codeception',
    'phpcpd' => 'PHP Copy/Paste Detector',
    'phpcs' => 'PHP Code Sniffer',
    'phpdoccheck' => 'Missing Docblocks',
    'phpmd' => 'PHP Mess Detector',
    'phpspec' => 'PHP Spec',
    'phpunit' => 'PHP Unit',
    'technical_debt' => 'Déficit Técnica',
    'behat' => 'Behat',

    'file' => 'Arquivo',
    'line' => 'Linha',
    'class' => 'Classe',
    'method' => 'Método',
    'message' => 'Mensagem',
    'start' => 'Início',
    'end' => 'Fim',
    'from' => 'De',
    'to' => 'Para',
    'suite' => 'Suite',
    'test' => 'Teste',
    'result' => 'Resultado',
    'ok' => 'OK',
    'took_n_seconds' => 'Gastou %d segundos',
    'build_created' => 'Build Criado',
    'build_started' => 'Build Iniciado',
    'build_finished' => 'Build Terminado',

    // Users
    'name' => 'Nome',
    'password_change' => 'Senha (deixar em branco se não quiser trocar)',
    'save' => 'Arquivar &raquo;',
    'update_your_details' => 'Atualizar os dados',
    'your_details_updated' => 'Seu dados foram atualizados.',
    'add_user' => 'Adicionar Usuário',
    'is_admin' => 'É Admin?',
    'yes' => 'Sim',
    'no' => 'Não',
    'edit' => 'Editar',
    'edit_user' => 'Editar Usuário',
    'delete_user' => 'Deletar Usuário',
    'user_n_not_found' => 'Usuário com ID %d não existe.',
    'is_user_admin' => 'É um Usuário administrador?',
    'save_user' => 'Salvar Usuário',

    // Settings:
    'settings_saved' => 'Sua configuracão foi salva.',
    'settings_check_perms' => 'Sua configuracão não foi salva, verifique as permissões do arquivo config.yml.',
    'settings_cannot_write' => 'PHPCI não pode escrever no arquivo config.yml, a configuracão não será salva corretamente
                                até que este problema seja corrigido.',
    'settings_github_linked' => 'Sua conta no GitHub foi conectada.',
    'settings_github_not_linked' => 'Não foi possível conectar à sua conta no GitHub.',
    'build_settings' => 'configuracão do Build ',
    'github_application' => 'Aplicação GitHub',
    'github_sign_in' => 'Antes de começar a utilizar o GitHub, voc~e precisa <a href="%s">acessar</a> e permitir
                            o acesso a sua conta para o PHPCI.',
    'github_phpci_linked' => 'PHPCI foi conectado à sua conta do GitHub.',
    'github_where_to_find' => 'Onde encontrá-los...',
    'github_where_help' => 'Se você é priopietário da aplicação que você quer usar, pode encontrar esta informacão na
                            área de configuracão de <a href="https://github.com/settings/applications">aplicações</a>.',

    'email_settings' => 'Configurações de Email',
    'email_settings_help' => 'Para que PHPCI possa enviar email com o status dos builds,
                                você deve configurar as seguintes propiedades SMTP.',

    'application_id' => 'ID da aplicação',
    'application_secret' => 'Palavra secreta da Aplicação',

    'smtp_server' => 'Servidor SMTP',
    'smtp_port' => 'Porta SMTP',
    'smtp_username' => 'Usuário SMTP',
    'smtp_password' => 'Senha SMTP',
    'from_email_address' => 'Remetente',
    'default_notification_address' => 'Email de notificação padrão',
    'use_smtp_encryption' => 'Usar criptografia SMTP?',
    'none' => 'Nenhum',
    'ssl' => 'SSL',
    'tls' => 'TLS',

    'failed_after' => 'Considerar o build como falho depois de ',
    '5_mins' => '5 Minutos',
    '15_mins' => '15 Minutos',
    '30_mins' => '30 Minutos',
    '1_hour' => '1 Hora',
    '3_hours' => '3 Horas',

    // Plugins
    'cannot_update_composer' => 'PHPCI não pode atualizar o arquivo composer.json porque não tem permissão de escrita.',
    'x_has_been_removed' => '%s foi eliminado.',
    'x_has_been_added' => '%s foi adicionado ao composer.json e será instalado na próxima vez que você executar um: composer update.',
    'enabled_plugins' => 'Ativar Plugins',
    'provided_by_package' => 'Fornecido pelo Pacote',
    'installed_packages' => 'Pacotes Instalados',
    'suggested_packages' => 'Pacotes Sugeridos',
    'title' => 'Título',
    'description' => 'Descrição',
    'version' => 'Versão',
    'install' => 'Instalar &raquo;',
    'remove' => 'Eliminar &raquo;',
    'search_packagist_for_more' => 'Buscar mais pacotes no Packagist',
    'search' => 'Buscar &raquo;',

    // Installer
    'installation_url' => 'URL da instalação PHPCI',
    'db_host' => 'Host',
    'db_name' => 'Nome da base de dados',
    'db_user' => 'Usuário da base de dados',
    'db_pass' => 'Senha da base de dados',
    'admin_name' => 'Nome do Admin',
    'admin_pass' => 'Senha do Admin',
    'admin_email' => 'Email do Admin',
    'config_path' => 'Pasta do arquivo config',
    'install_phpci' => 'Instalar PHPCI',
    'welcome_to_phpci' => 'Bem-vindo ao PHPCI',
    'please_answer' => 'Por favor, responda as siguientes peguntas:',
    'phpci_php_req' => 'PHPCI requer ao menos PHP 5.3.8 para funcionar.',
    'extension_required' => 'Extensão requerida: %s',
    'function_required' => 'PHPCI deve poder invocar a funcão %s(). Ela está desabilitada no php.ini?',
    'requirements_not_met' => 'PHPCI não pode ser instalado, já que não foram cumpridos todos os requisitos.
                                Por favor, corrija os erros antes de continuar.',
    'must_be_valid_email' => 'Deve ser um e-mail válida.',
    'must_be_valid_url' => 'Deve ser uma URL válida.',
    'enter_name' => 'Nome do Admin:',
    'enter_email' => 'Email do Admin:',
    'enter_password' => 'Contraseña de Admin:',
    'enter_phpci_url' => 'A URL do PHPCI ("Por exemplo: http://phpci.local"): ',

    'enter_db_host' => 'Por favor, inserir o servidor MySQL [localhost]: ',
    'enter_db_name' => 'Por favor, inserir o nombre da base de dados MySQL [phpci]: ',
    'enter_db_user' => 'Por favor, inserir o usuário MySQL [phpci]: ',
    'enter_db_pass' => 'Por favor, inserir a senha MySQL: ',
    'could_not_connect' => 'PHPCI não pode conectar-se ao MySQL com os dados. Por favor, corrija e tente novamente.',
    'setting_up_db' => 'Configurando base de dados... ',
    'user_created' => 'Conta de usuário criada!',
    'failed_to_create' => 'PHPCI não pode criar a conta de admin.',
    'config_exists' => 'O arquivo config do PHPCI já existe e não está vazio.',
    'update_instead' => 'Se estás tentando atualizar o PHPCI, por favor, utilize o comando no console phpci:update.',

    // Update
    'update_phpci' => 'Atualizar a base de dados para refletir os modelos atualizados.',
    'updating_phpci' => 'Atualizando a base de dados PHPCI: ',
    'not_installed' => 'PHPCI não está instalado.',
    'install_instead' => 'Por favor, instale PHPCI via phpci:install.',

    // Poll Command
    'poll_github' => 'Verificar no GitHub se é preciso começar um Build.',
    'no_token' => 'Nenhum token GitHub encontrado',
    'finding_projects' => 'Buscando projetos para check-in',
    'found_n_projects' => 'Foram encontrados %d projetos',
    'last_commit_is' => 'O último commit no GitHub para %s é %s',
    'adding_new_build' => 'Último commit é diferente da base de dados, agregando novo build.',
    'finished_processing_builds' => 'Fim do processamento de builds.',

    // Create Admin
    'create_admin_user' => 'Criar um usuário Admin',
    'incorrect_format' => 'Formato incorreto',

    // Run Command
    'run_all_pending' => 'Executar todos os builds PHPCI pendentes.',
    'finding_builds' => 'Buscando builds para procesar',
    'found_n_builds' => 'Foram encontrados %d builds',
    'skipping_build' => 'Pulando Build %d - Build do projeto que já está em execução.',
    'marked_as_failed' => 'Build %d falhou devido a timeout.',

    // Builder
    'missing_phpci_yml' => 'Este projeto não contém o arquivo phpci.yml ou está vazio.',
    'build_success' => 'BUILD COM SUSESSO',
    'build_failed' => 'BUILD COM FALHAS',
    'removing_build' => 'Eliminando Build.',
    'exception' => 'Exceção: ',
    'could_not_create_working' => 'Impossível criar uma cópia do trabalho.',
    'working_copy_created' => 'Cópia do trabalho criada: %s',
    'looking_for_binary' => 'Buscando binário: %s',
    'found_in_path' => 'Encontrado em %s: %s',
    'running_plugin' => 'EXECUTANDO PLUGIN: %s',
    'plugin_success' => 'PLUGIN: SUCESSO',
    'plugin_failed' => 'PLUGIN: FALHOU',
    'plugin_missing' => 'Não existe o plugin: %s',
    'tap_version' => 'TapParser suporta apoenas a verisão 13 do TAP',
    'tap_error' => 'Cadeia de caractéres TAP inválida, o número de testes não coincide com a conta de testes definidos.',

    // Build Plugins:
    'no_tests_performed' => 'Não foram encontrados testes.',
    'could_not_find' => 'Não foram encontrados %s',
    'no_campfire_settings' => 'No se especificaron parámetros de conexión para el plugin Campfire',
    'failed_to_wipe' => 'Impossível eliminar o diretório existente %s antes de copiar-lo',
    'passing_build' => 'Build com sucesso',
    'failing_build' => 'Build com falha',
    'log_output' => 'Log de Saída: ',
    'n_emails_sent' => '%d emails enviados.',
    'n_emails_failed' => '%d emails não puderam ser enviados.',
    'unable_to_set_env' => 'Impossível definir a variável de ambiente',
    'tag_created' => 'Tag criada pelo PHPCI: %s',
    'x_built_at_x' => 'Build do %PROJECT_TITLE% em %BUILD_URI%',
    'hipchat_settings' => 'Por favor, definir room e authToken para o plugin hipchat_notify',
    'irc_settings' => 'Você deve configurar um servidor, sala (room) e apelido (nick).',
    'invalid_command' => 'Comando inválido',
    'import_file_key' => 'Declaração de importação deve conter uma chave \'file\'',
    'cannot_open_import' => 'Impossível abrir o arquivo de importação SQL: %s',
    'unable_to_execute' => 'Impossível executar o arquivo SQL',
    'phar_internal_error' => 'Erro interno no plugin Phar',
    'build_file_missing' => 'O arquivo de build especificado não existe.',
    'property_file_missing' => 'O aquivo de propriedades especificado não existe.',
    'could_not_process_report' => 'Impossível procesar o relatório gerado pela ferramenta.',
    'shell_not_enabled' => 'O plugin shell não está habilitado. Por favor, habilite em config.yml.',
	
	// Error Levels:
    'critical' => 'Crítico',
    'high' => 'Alto',
    'normal' => 'Normal',
    'low' => 'Baixo',

    // Plugins that generate errors:
    'php_mess_detector' => 'PHP Mess Detector',
    'php_code_sniffer' => 'PHP Code Sniffer',
    'php_unit' => 'PHP Unit',
    'php_cpd' => 'PHP Copy/Paste Detector',
    'php_docblock_checker' => 'PHP Docblock Checker',
    'behat' => 'Behat',
    'technical_debt' => 'Technical Debt',
);

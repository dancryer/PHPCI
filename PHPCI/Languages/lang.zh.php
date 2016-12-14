<?php
/**
 * PHPCI - Continuous Integration for PHP
 *
 * @copyright    Copyright 2014, Block 8 Limited.
 * @license      https://github.com/Block8/PHPCI/blob/master/LICENSE.md
 * @link         https://www.phptesting.org/
 */

$strings = array(
    'language_name' => '简体中文',
    'language' => '语言选择',

    // Log in:
    'log_in_to_phpci' => '登录 PHPCI',
    'login_error' => '邮箱或密码错误',
    'forgotten_password_link' => '忘记密码？',
    'reset_emailed' => '已发送重置密码邮件.',
    'reset_header' => '<strong>不用担心！</strong><br>只需要输入您的邮箱地址，我们将会给您的邮箱发送含有重置密码链接的邮件。',
    'reset_email_address' => '输入您的邮箱地址：',
    'reset_send_email' => '邮件重设密码',
    'reset_enter_password' => '请输入新密码',
    'reset_new_password' => '新密码：',
    'reset_change_password' => '更改密码',
    'reset_no_user_exists' => '不存该该邮箱用户，请检查后重试！',
    'reset_email_body' => '您好 %s,
    
您收到这封邮件，是因为您或者别人发起了来自PHPCI的密码重置请求。

如果确实是您发起的，请点击链接去重置您的密码：%ssession/reset-password/%d/%s

否则，请忽视这封邮件，这不会发生任何事情。

多谢,

PHPCI',

    'reset_email_title' => '给 %s 来自 PHPCI 的密码重置邮件',
    'reset_invalid' => '密码重置请求失败！',
    'email_address' => '邮箱地址',
    'password' => '密码',
    'log_in' => '登录',


    // Top Nav
    'toggle_navigation' => '导航切换',
    'n_builds_pending' => '%d 等待构建',
    'n_builds_running' => '%d 正在构建',
    'edit_profile' => '编辑资料',
    'sign_out' => '注销',
    'branch_x' => '分支: %s',
    'created_x' => '创建于: %s',
    'started_x' => '开始于: %s',

    // Sidebar
    'hello_name' => '您好, %s',
    'dashboard' => '仪表盘',
    'admin_options' => '管理选项',
    'add_project' => '新增项目',
    'settings' => '设置',
    'manage_users' => '用户管理',
    'plugins' => '插件',
    'view' => '查看',
    'build_now' => '立即构建',
    'edit_project' => '编辑项目',
    'delete_project' => '删除项目',

    // Project Summary:
    'no_builds_yet' => '没有任何构建',
    'x_of_x_failed' => '%d 的最后一个 %d 构建失败！',
    'x_of_x_failed_short' => '%d / %d 失败。',
    'last_successful_build' => ' 最后一次构建成功的是 %s.',
    'never_built_successfully' => ' 该项目构建从来没有成功过！',
    'all_builds_passed' => '构建记录中 %d 次构建通过。',
    'all_builds_passed_short' => '%d / %d 通过。',
    'last_failed_build' => ' 最近一次构建失败的是 %s。',
    'never_failed_build' => ' 该项目构建从未失败过。',
    'view_project' => '查看项目',

    // Timeline:
    'latest_builds' => '最近构建',
    'pending' => '等待中',
    'running' => '构建中',
    'success' => '成功',
    'successful' => '成功',
    'failed' => '失败',
    'manual_build' => 'Manual Build',

    // Add/Edit Project:
    'new_project' => '新项目',
    'project_x_not_found' => '项目 ID %d 不存在。',
    'project_details' => '项目详情',
    'public_key_help' => '为了帮助您更简单的开始项目构建，我们生成了以下 SSH key 对用于该项目。要使用它，请添加以下公钥 “deploy keys” 至您选择的源代码托管平台',

    'select_repository_type' => '选择仓库类型...',
    'github' => 'GitHub',
    'bitbucket' => 'Bitbucket',
    'gitlab' => 'GitLab',
    'remote' => 'Remote URL',
    'local' => 'Local Path',
    'hg'    => 'Mercurial',
    'svn'   => 'Subversion',

    'where_hosted' => '您的代码托管在？',
    'choose_github' => '选择一个 GitHub 仓库：',

    'repo_name' => '仓库名称 / URL (本地) or Path (本地)',
    'project_title' => '项目标题',
    'project_private_key' => '访问仓库私有秘钥
                                (本地或公共仓库可为空)',
    'build_config' => '该项目 PHPCI 构建配置文件
                                (如果您无法在该项目仓库创建 phpci.yml 文件)',
    'default_branch' => '默认分支名称',
    'allow_public_status' => '启用此项目的公共状态页和图像？',
    'archived' => '归档',
    'archived_menu' => '归档',
    'save_project' => '保存项目',

    'error_mercurial' => 'Mercurial 仓库 URL 必须以 http:// or https:// 开始',
    'error_remote' => '仓库 URL 必须以 git://, http:// or https:// 开始',
    'error_gitlab' => 'GitLab 仓库名称必须符合 "user@domain.tld:owner/repo.git" 格式',
    'error_github' => '仓库名称必须符合 "owner/repo" 格式',
    'error_bitbucket' => '仓库名称必须符合 "owner/repo" 格式',
    'error_path' => '您制定的路径不存在',

    // View Project:
    'all_branches' => 'All Branches',
    'builds' => 'Builds',
    'id' => 'ID',
    'date' => '日期',
    'project' => '项目',
    'commit' => '提交',
    'branch' => '分支',
    'status' => '状态',
    'prev_link' => '&laquo; 上一个',
    'next_link' => '下一个 &raquo;',
    'public_key' => '公共秘钥',
    'delete_build' => '删除构建',

    'webhooks' => 'Webhooks',
    'webhooks_help_github' => '要想当您的仓库由新的提交推送时自动构建，请在您的Github仓库的 
                                <a href="https://github.com/%s/settings/hooks">Webhooks and Services</a> 
                                将该URL添加至新增 "Webhook" 中。',

    'webhooks_help_gitlab' => '要想当您的仓库由新的提交推送时自动构建，请在您的GitLab仓库的 "WebHook URL" 添加该URL。',

    'webhooks_help_bitbucket' => '要想当您的仓库由新的提交推送时自动构建，请在您的GitLab仓库的
                                <a href="https://bitbucket.org/%s/admin/services">
                                Services</a> 将该URL添加成 “POST服务”。',

    // View Build
    'errors' => 'Errors',
    'information' => 'Information',

    'build_x_not_found' => '构建 ID %d 不存在。',
    'build_n' => 'Build %d',
    'rebuild_now' => '重新构建',


    'committed_by_x' => '由 %s 提交',
    'commit_id_x' => '提交: %s',

    'chart_display' => '构建一旦完成该图表将会显示',

    'build' => 'Build',
    'lines' => 'Lines',
    'comment_lines' => 'Comment Lines',
    'noncomment_lines' => 'Non-Comment Lines',
    'logical_lines' => 'Logical Lines',
    'lines_of_code' => 'Lines of Code',
    'build_log' => 'Build Log',
    'quality_trend' => 'Quality Trend',
    'codeception_errors' => 'Codeception Errors',
    'phpmd_warnings' => 'PHPMD Warnings',
    'phpcs_warnings' => 'PHPCS Warnings',
    'phpcs_errors' => 'PHPCS Errors',
    'phplint_errors' => 'Lint Errors',
    'phpunit_errors' => 'PHPUnit Errors',
    'phpdoccheck_warnings' => 'Missing Docblocks',
    'issues' => 'Issues',

    'codeception' => 'Codeception',
    'phpcpd' => 'PHP Copy/Paste Detector',
    'phpcs' => 'PHP Code Sniffer',
    'phpdoccheck' => 'Missing Docblocks',
    'phpmd' => 'PHP Mess Detector',
    'phpspec' => 'PHP Spec',
    'phpunit' => 'PHP Unit',
    'technical_debt' => 'Technical Debt',
    'behat' => 'Behat',

    'codeception_feature' => 'Feature',
    'codeception_suite' => 'Suite',
    'codeception_time' => 'Time',
    'codeception_synopsis' => '<strong>%1$d</strong> 测试进行了 <strong>%2$f</strong> 秒.
                               <strong>%3$d</strong> 失败。',

    'file' => 'File',
    'line' => 'Line',
    'class' => 'Class',
    'method' => 'Method',
    'message' => 'Message',
    'start' => 'Start',
    'end' => 'End',
    'from' => 'From',
    'to' => 'To',
    'result' => 'Result',
    'ok' => 'OK',
    'took_n_seconds' => 'Took %d seconds',
    'build_created' => 'Build Created',
    'build_started' => 'Build Started',
    'build_finished' => 'Build Finished',
    'test_message' => 'Message',
    'test_no_message' => 'No message',
    'test_success' => 'Successful: %d',
    'test_fail' => 'Failures: %d',
    'test_skipped' => 'Skipped: %d',
    'test_error' => 'Errors: %d',
    'test_todo' => 'Todos: %d',
    'test_total' => '%d test(s)',

    // Users
    'name' => '名称',
    'password_change' => '密码 (如果要修改密码请填入新密码,否则留空空)',
    'save' => '保存 &raquo;',
    'update_your_details' => '更新您的个人信息',
    'your_details_updated' => '您的信息已经更新。',
    'add_user' => '新增用户',
    'is_admin' => '是否设为管理员?',
    'yes' => '是',
    'no' => '否',
    'edit' => '编辑',
    'edit_user' => '编辑用户',
    'delete_user' => '删除用户',
    'user_n_not_found' => '用户 ID %d 不存在。',
    'is_user_admin' => '该用户是否为管理员',
    'save_user' => '保存用户',

    // Settings:
    'settings_saved' => '您的设置已经保存。',
    'settings_check_perms' => '权限不足,您的设置无法保存, 请检查 config.yml 文件.',
    'settings_cannot_write' => 'PHPCI 无法写入 config.yml 文件, 在这个问题解决前设置可能无法正常保存',
    'settings_github_linked' => '您的 GitHub 账户已经连接。',
    'settings_github_not_linked' => '您的 GitHub 无法连接。',
    'build_settings' => '构建设置',
    'github_application' => 'GitHub Application',
    'github_sign_in' => '在使用您的 GitHub 账号之前, 您需要登录 GitHub , 并允许 PHPCI 访问您的账户。',
    'github_phpci_linked' => 'PHPCI 成功连接到您的 GitHub 账户。',
    'github_where_to_find' => '在哪里可以找到...',
    'github_where_help' => '如果您想使用您自己的应用, 您可以在<a href="https://github.com/settings/applications">applications</a> 的 setting 中 找到相关信息。',

    'email_settings' => '邮箱设置',
    'email_settings_help' => 'PHPCI在发送构建状态的邮件之前,您需要配置您的SMTP设置如下。',

    'application_id' => 'Application ID',
    'application_secret' => 'Application Secret',

    'smtp_server' => 'SMTP Server',
    'smtp_port' => 'SMTP Port',
    'smtp_username' => 'SMTP Username',
    'smtp_password' => 'SMTP Password',
    'from_email_address' => '邮件来自',
    'default_notification_address' => '通知默认邮件',
    'use_smtp_encryption' => 'SMTP 使用哪种方式加密?',
    'none' => 'None',
    'ssl' => 'SSL',
    'tls' => 'TLS',

    'failed_after' => '构建失败后重新构建间隔',
    '5_mins' => '5 分钟',
    '15_mins' => '15 分钟',
    '30_mins' => '30 分钟',
    '1_hour' => '1 小时',
    '3_hours' => '3 小时',

    // Plugins
    'cannot_update_composer' => '由于 composer.json 文件不可写 PHPCI 无法为您更新该文件, ',
    'x_has_been_removed' => '%s 已经移除',
    'x_has_been_added' => '%s 已经为您添加至 composer.json , 当您下次执行 composer update 时相关库将会安装',
    'enabled_plugins' => '已启用插件',
    'provided_by_package' => '来自',
    'installed_packages' => '已安装插件',
    'suggested_packages' => '建议安装插件',
    'title' => '名称',
    'description' => '说明',
    'version' => '版本',
    'install' => '安装 &raquo;',
    'remove' => '移除 &raquo;',
    'search_packagist_for_more' => '搜索获取更多插件',
    'search' => '搜索 &raquo;',

    // Summary plugin
    'build-summary' => 'Summary',
    'stage' => 'Stage',
    'duration' => 'Duration',
    'plugin' => 'Plugin',
    'stage_setup' => 'Setup',
    'stage_test' => 'Test',
    'stage_complete' => 'Complete',
    'stage_success' => 'Success',
    'stage_failure' => 'Failure',
    'stage_broken'  => 'Broken',
    'stage_fixed' => 'Fixed',

    // Installer
    'installation_url' => 'PHPCI Installation URL',
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

    // Create Build Command
    'create_build_project' => 'Create a build for a project',
    'project_id_argument' => 'A project ID',
    'commit_id_option' => 'Commit ID to build',
    'branch_name_option' => 'Branch to build',
    'add_to_queue_failed' => 'Build created successfully, but failed to add to build queue. This usually happens
                                when PHPCI is set to use a beanstalkd server that does not exist, 
                                or your beanstalkd server has stopped.',

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
    'removing_build' => 'Removing Build.',
    'exception' => 'Exception: ',
    'could_not_create_working' => 'Could not create a working copy.',
    'working_copy_created' => 'Working copy created: %s',
    'looking_for_binary' => 'Looking for binary: %s',
    'found_in_path' => 'Found in %s: %s',
    'running_plugin' => 'RUNNING PLUGIN: %s',
    'plugin_success' => 'PLUGIN: SUCCESS',
    'plugin_failed' => 'PLUGIN: FAILED',
    'plugin_missing' => 'Plugin does not exist: %s',
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
    'shell_not_enabled' => 'The shell plugin is not enabled. Please enable it via config.yml.',


    // Error Levels:
    'critical' => 'Critical',
    'high' => 'High',
    'normal' => 'Normal',
    'low' => 'Low',

    // Plugins that generate errors:
    'php_mess_detector' => 'PHP Mess Detector',
    'php_code_sniffer' => 'PHP Code Sniffer',
    'php_unit' => 'PHP Unit',
    'php_cpd' => 'PHP Copy/Paste Detector',
    'php_docblock_checker' => 'PHP Docblock Checker',
    'behat' => 'Behat',
    'technical_debt' => 'Technical Debt',

);

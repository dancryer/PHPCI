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
    'language' => '语言',

    // Log in:
    'log_in_to_phpci' => '登录PHPCI',
    'login_error' => '错误的邮箱地址或者密码',
    'forgotten_password_link' => '忘记密码？',
    'reset_emailed' => '我们已经通过邮件发送一个链接给你重置密码。',
    'reset_header' => '<strong>别担心！</strong><br>在下面输入你的邮箱地址，我们会通过邮件发送一个链接给你重置密码。',
    'reset_email_address' => '输入你的邮箱地址：',
    'reset_send_email' => '通过邮箱重置密码',
    'reset_enter_password' => '请输入新密码',
    'reset_new_password' => '新密码：',
    'reset_change_password' => '修改密码',
    'reset_no_user_exists' => '不存在此邮箱的用户，请重试。',
    'reset_email_body' => '你好%s，

你会收到这封邮件，是因为你或者其他人请求重置PHPCI的密码。

如果是你本人，请点击链接重置你的密码：%ssession/reset-password/%d/%s

否则，请忽略这封邮件。

谢谢，

PHPCI',

    'reset_email_title' => '重置%s的PHPCI密码',
    'reset_invalid' => '不合法的密码重置请求。',
    'email_address' => '邮箱',
    'login' => '登录 / 邮箱',
    'password' => '密码',
    'log_in' => '登录',


    // Top Nav
    'toggle_navigation' => '切换导航',
    'n_builds_pending' => '%d个构建被挂起',
    'n_builds_running' => '%d个构建正在运行',
    'edit_profile' => '编辑个人信息',
    'sign_out' => '登出',
    'branch_x' => '分支： %s',
    'created_x' => '创建： %s',
    'started_x' => '开始： %s',

    // Sidebar
    'hello_name' => '你好，%s',
    'dashboard' => '控制面板',
    'admin_options' => '管理员选项',
    'add_project' => '添加项目',
    'settings' => '配置',
    'manage_users' => '用户管理',
    'plugins' => '插件',
    'view' => '查看',
    'build_now' => '立即构建',
    'edit_project' => '编辑项目',
    'delete_project' => '删除项目',

    // Project Summary:
    'no_builds_yet' => '还没有任何构建！',
    'x_of_x_failed' => '%d个构建失败（在最新的%d个构建中）。',
    'x_of_x_failed_short' => '%d / %d 失败。',
    'last_successful_build' => ' 最后一个成功的构建发生在%s。',
    'never_built_successfully' => ' 这个项目还没构建成功过。',
    'all_builds_passed' => '最后%d个构建全部通过。',
    'all_builds_passed_short' => '%d / %d 通过。',
    'last_failed_build' => ' 最后一个失败的构建是%s.',
    'never_failed_build' => ' 这个项目的构建还没有失败过。',
    'view_project' => '查看项目',

    // Timeline:
    'latest_builds' => '最新的构建',
    'pending' => '挂起',
    'running' => '运行中',
    'success' => '成功',
    'successful' => '成功',
    'failed' => '失败',
    'manual_build' => '手动构建',

    // Add/Edit Project:
    'new_project' => '新项目',
    'project_x_not_found' => '不存在ID为%d的项目。',
    'project_details' => '项目详情',
    'public_key_help' => '为了让你更轻松的开始使用，我们为你创建了这个项目所要使用的SSH密钥对。要使用它，只需把它下面的公钥添加到你源代码托管平台的"deploy keys"里。',
    'select_repository_type' => '选择仓库类型...',
    'github' => 'GitHub',
    'bitbucket' => 'Bitbucket',
    'gitlab' => 'GitLab',
    'remote' => '远程URL',
    'local' => '本地路径',
    'hg'    => 'Mercurial',
    'svn'   => 'Subversion',

    'where_hosted' => '你的项目托管在哪里？',
    'choose_github' => '选择一个GitHub仓库：',

    'repo_name' => '仓库名称/地址（远程）或者路径（本地）',
    'project_title' => '项目标题',
    'project_private_key' => '用于访问仓库的私钥
                                （本地或者匿名用户请留空）',
    'build_config' => '这个项目的PHPCI构建配置
                                （如果你不能添加phpci.yml文件到你的项目仓库里）',
    'default_branch' => '默认分支名称',
    'allow_public_status' => '启用这个项目的状态页面和图标',
    'archived' => '归档',
    'archived_menu' => '归档',
    'save_project' => '保存项目',

    'error_mercurial' => 'Mercurial的仓库URL必须以http://或者https://开头',
    'error_remote' => '仓库URL必须以git://，http://或者https://开头',
    'error_gitlab' => 'GitLab仓库名称的格式必须为"user@domain.tld:owner/repo.git"',
    'error_github' => '仓库名称的格式必须为"owner/repo"',
    'error_bitbucket' => '仓库名称的格式必须为"owner/repo"',
    'error_path' => '指定的路径不存在。',

    // View Project:
    'all_branches' => '所有分支',
    'builds' => '构建',
    'id' => 'ID',
    'date' => '日期',
    'project' => '项目',
    'commit' => '提交',
    'branch' => '分支',
    'status' => '状态',
    'prev_link' => '&laquo; 前一页',
    'next_link' => '后一页 &raquo;',
    'public_key' => '公钥',
    'delete_build' => '删除构建',

    'webhooks' => 'Webhooks',
    'webhooks_help_github' => '想要在新提交被推送的时候自动构建项目，可以在Github仓库的<a href="https://github.com/%s/settings/hooks">Webhooks
                                and Services</a>设置里添加下面的URL作为一个新的"Webhook"。',

    'webhooks_help_gitlab' => '想要在新提交被推送的时候自动构建项目，可以在GitLab仓库的Web Hooks设置里添加以下的URL作为"WebHook URL"。',

    'webhooks_help_bitbucket' => '想要在新提交被推送的时候自动构建项目，可以在Bitbucket仓库的<a href="https://bitbucket.org/%s/admin/services">
                                Services</a>设置里添加以下的URL作为一个"POST"服务。',

    // View Build
    'errors' => '错误',
    'information' => '信息',

    'build_x_not_found' => 'ID为%d的建构不存在。',
    'build_n' => '构建 %d',
    'rebuild_now' => '重新构建',


    'committed_by_x' => '由%s提交',
    'commit_id_x' => '提交： %s',

    'chart_display' => '图表将会在构建完成后显示。',

    'build' => 'Build',
    'lines' => '行数',
    'comment_lines' => '注释行数',
    'noncomment_lines' => '非注释行数',
    'logical_lines' => '逻辑行数',
    'lines_of_code' => '代码行数',
    'build_log' => '构建日志',
    'quality_trend' => '质量趋势',
    'codeception_errors' => 'Codeception错误',
    'phpmd_warnings' => 'PHPMD警告',
    'phpcs_warnings' => 'PHPCS警告',
    'phpcs_errors' => 'PHPCS错误',
    'phplint_errors' => 'Lint错误',
    'phpunit_errors' => 'PHPUnit错误',
    'phpdoccheck_warnings' => '缺失的Docblock',
    'issues' => '问题',

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
    'codeception_synopsis' => '<strong>%1$d</strong> tests carried out in <strong>%2$f</strong> seconds.
                               <strong>%3$d</strong> failures.',

    'file' => '文件',
    'line' => '行号',
    'class' => '类',
    'method' => '方法',
    'message' => '信息',
    'start' => '开始',
    'end' => '结束',
    'from' => '从',
    'to' => '到',
    'result' => '结果',
    'ok' => 'OK',
    'took_n_seconds' => '耗时%d秒',
    'build_created' => '构建已创建',
    'build_started' => '构建开始',
    'build_finished' => '构建完成',
    'test_message' => '信息',
    'test_no_message' => '没有信息',
    'test_success' => '成功： %d',
    'test_fail' => '失败： %d',
    'test_skipped' => '跳过： %d',
    'test_error' => '错误： %d',
    'test_todo' => '待办： %d',
    'test_total' => '%d个测试',

    // Users
    'name' => '名字',
    'password_change' => '密码（如果不想修改密码请留空）',
    'save' => '保存 &raquo;',
    'update_your_details' => '更新你的个人信息',
    'your_details_updated' => '你的个人信息已经更新。',
    'add_user' => '添加用户',
    'is_admin' => '是否管理员',
    'yes' => '是',
    'no' => '否',
    'edit' => '编辑',
    'edit_user' => '编辑用户',
    'delete_user' => '删除用户',
    'user_n_not_found' => 'ID为%d的用户不存在。',
    'is_user_admin' => '这个用户是管理员吗？',
    'save_user' => '保存用户',

    // Settings:
    'settings_saved' => '你的设置已经保存。',
    'settings_check_perms' => '设置保存失败，请检查config.yml文件的访问权限。',
    'settings_cannot_write' => 'PHPCI不能写你的config.yml文件，在解决这个问题前设置可能不能正常的进行保存。',
    'settings_github_linked' => '你的Github帐号已经被关联。',
    'settings_github_not_linked' => '你的Github帐号不能被关联。',
    'build_settings' => '构建配置',
    'github_application' => 'GitHub应用',
    'github_sign_in' => '在使用Github前，你需要<a href="%s">登录</a>并赋予PHPCI访问权限。',
    'github_phpci_linked' => '已成功把PHPCI关联到GitHub帐号。',
    'github_where_to_find' => '怎么获取...',
    'github_where_help' => '如果你拥有想要使用的那个应用，你可以在<a href="https://github.com/settings/applications">applications</a>设置里找到相关的信息。',

    'email_settings' => '邮箱配置',
    'email_settings_help' => '要使用PHPCI发送构建状态邮件，你需要在下面配置你的SMTP设置。',

    'application_id' => 'Application ID',
    'application_secret' => 'Application Secret',

    'smtp_server' => 'SMTP服务地址',
    'smtp_port' => 'SMTP服务端口',
    'smtp_username' => 'SMTP用户名',
    'smtp_password' => 'SMTP密码',
    'from_email_address' => '邮件发送地址',
    'default_notification_address' => '默认的通知邮件地址',
    'use_smtp_encryption' => '使用SMTP加密？',
    'none' => 'None',
    'ssl' => 'SSL',
    'tls' => 'TLS',

    'failed_after' => '构建的超时时间（这个时间后将会视为构建失败）',
    '5_mins' => '5分钟',
    '15_mins' => '15分钟',
    '30_mins' => '30分钟',
    '1_hour' => '1小时',
    '3_hours' => '3小时',

    // Plugins
    'cannot_update_composer' => '由于不可写，PHPCI不能更新你的composer.json文件。',
    'x_has_been_removed' => '%s已经被移除。',
    'x_has_been_added' => '%s已经添加到composer.json里，并将会在你下一次运行composer update时进行安装。',
    'enabled_plugins' => '启用的插件',
    'provided_by_package' => '由哪个包提供',
    'installed_packages' => '已安装的包',
    'suggested_packages' => '建议安装的包',
    'title' => '标题',
    'description' => '描述',
    'version' => '版本',
    'install' => '安装 &raquo;',
    'remove' => '卸载 &raquo;',
    'search_packagist_for_more' => '搜索Packagist',
    'search' => '搜索 &raquo;',

    // Summary plugin
    'build-summary' => '摘要',
    'stage' => '阶段',
    'duration' => '耗时',
    'plugin' => '插件',
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

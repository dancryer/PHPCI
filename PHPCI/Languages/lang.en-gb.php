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
    'log_in_to_phpci' => 'Log in to PHPCI',
    'login_error' => 'Incorrect email address or password',
    'forgotten_password_link' => 'Forgotten your password?',
    'reset_emailed' => 'We\'ve emailed you a link to reset your password.',
    'reset_header' => '<strong>Don\'t worry!</strong><br>Just enter your email address below and we\'ll email
                            you a link to reset your password.',
    'reset_email_address' => 'Enter your email address:',
    'reset_send_email' => 'Email password reset',
    'reset_enter_password' => 'Please enter a new password',
    'reset_new_password' => 'New password:',
    'reset_change_password' => 'Change password',
    'reset_no_user_exists' => 'No user exists with that email address, please try again.',
    'reset_email_body' => 'Hi %s,

You have received this email because you, or someone else, has requested a password reset for PHPCI.

If this was you, please click the following link to reset your password: %ssession/reset-password/%d/%s

Otherwise, please ignore this email and no action will be taken.

Thank you,

PHPCI',

    'reset_email_title' => 'PHPCI Password Reset for %s',
    'reset_invalid' => 'Invalid password reset request.',
    'email_address' => 'Email Address',
    'password' => 'Password',
    'log_in' => 'Log in',


    // Top Nav
    'toggle_navigation' => 'Toggle Navigation',
    'n_builds_pending' => '%d builds pending',
    'n_builds_running' => '%d builds running',
    'edit_profile' => 'Edit Profile',
    'sign_out' => 'Sign Out',
    'branch_x' => 'Branch: %s',
    'created_x' => 'Created: %s',
    'started_x' => 'Started: %s',

    // Sidebar
    'hello_name' => 'Hello, %s',
    'dashboard' => 'Dashboard',
    'admin_options' => 'Admin Options',
    'add_project' => 'Add Project',
    'settings' => 'Settings',
    'manage_users' => 'Manage Users',
    'plugins' => 'Plugins',
    'view' => 'View',
    'build_now' => 'Build Now',
    'edit_project' => 'Edit Project',
    'delete_project' => 'Delete Project',

    // Dashboard:
    'dashboard' => 'Dashboard',

    // Project Summary:
    'no_builds_yet' => 'No builds yet!',
    'x_of_x_failed' => '%d out of the last %d builds failed.',
    'x_of_x_failed_short' => '%d / %d failed.',
    'last_successful_build' => ' The last successful build was %s.',
    'never_built_successfully' => ' This project has never built successfully.',
    'all_builds_passed' => 'All of the last %d builds passed.',
    'all_builds_passed_short' => '%d / %d passed.',
    'last_failed_build' => ' The last failed build was %s.',
    'never_failed_build' => ' This project has never failed a build.',
    'view_project' => 'View Project',

    // Timeline:
    'latest_builds' => 'Latest Builds',
    'pending' => 'Pending',
    'running' => 'Running',
    'success' => 'Success',
    'successful' => 'Successful',
    'failed' => 'Failed',

    // Add/Edit Project:
    'new_project' => 'New Project',
    'project_x_not_found' => 'Project with ID %d does not exist.',
    'project_details' => 'Project Details',
    'public_key_help' => 'To make it easier to get started, we\'ve generated an SSH key pair for you to use
                            for this project. To use it, just add the following public key to the "deploy keys" section
                            of your chosen source code hosting platform.',
    'select_repository_type' => 'Select repository type...',
    'github' => 'Github',
    'bitbucket' => 'Bitbucket',
    'gitlab' => 'Gitlab',
    'remote' => 'Remote URL',
    'local' => 'Local Path',
    'hg'    => 'Mercurial',

    'where_hosted' => 'Where is your project hosted?',
    'choose_github' => 'Choose a Github repository:',

    'repo_name' => 'Repository Name / URL (Remote) or Path (Local)',
    'project_title' => 'Project Title',
    'project_private_key' => 'Private key to use to access repository
                                (leave blank for local and/or anonymous remotes)',
    'build_config' => 'PHPCI build config for this project
                                (if you cannot add a phpci.yml file in the project repository)',
    'default_branch' => 'Default branch name',
    'allow_public_status' => 'Enable public status page and image for this project?',
    'save_project' => 'Save Project',

    'error_mercurial' => 'Mercurial repository URL must be start with http:// or https://',
    'error_remote' => 'Repository URL must be start with git://, http:// or https://',
    'error_gitlab' => 'GitLab Repository name must be in the format "user@domain.tld:owner/repo.git"',
    'error_github' => 'Repository name must be in the format "owner/repo"',
    'error_bitbucket' => 'Repository name must be in the format "owner/repo"',
    'error_path' => 'The path you specified does not exist.',

    // View Project:
    'all_branches' => 'All Branches',
    'builds' => 'Builds',
    'id' => 'ID',
    'project' => 'Project',
    'commit' => 'Commit',
    'branch' => 'Branch',
    'status' => 'Status',
    'prev_link' => '&laquo; Prev',
    'next_link' => 'Next &raquo;',
    'public_key' => 'Public Key',
    'delete_build' => 'Delete Build',

    'webhooks' => 'Webhooks',
    'webhooks_help_github' => 'To automatically build this project when new commits are pushed, add the URL below
                                as a new "Webhook" in the <a href="https://github.com/%s/settings/hooks">Webhooks
                                and Services</a>  section of your Github repository.',

    'webhooks_help_gitlab' => 'To automatically build this project when new commits are pushed, add the URL below
                                as a "WebHook URL" in the Web Hooks section of your Gitlab repository.',

    'webhooks_help_bitbucket' => 'To automatically build this project when new commits are pushed, add the URL below
                                as a "POST" service in the
                                <a href="https://bitbucket.org/%s/admin/services">
                                Services</a> section of your Bitbucket repository.',

    // View Build
    'build_x_not_found' => 'Build with ID %d does not exist.',
    'build_n' => 'Build %d',
    'rebuild_now' => 'Rebuild Now',


    'committed_by_x' => 'Committed by %s',
    'commit_id_x' => 'Commit: %s',

    'chart_display' => 'This chart will display once the build has completed.',

    'build' => 'Build',
    'lines' => 'Lines',
    'comment_lines' => 'Comment Lines',
    'noncomment_lines' => 'Non-Comment Lines',
    'logical_lines' => 'Logical Lines',
    'lines_of_code' => 'Lines of Code',
    'build_log' => 'Build Log',
    'quality_trend' => 'Quality Trend',
    'phpmd_warnings' => 'PHPMD Warnings',
    'phpcs_warnings' => 'PHPCS Warnings',
    'phpcs_errors' => 'PHPCS Errors',
    'phplint_errors' => 'Lint Errors',
    'phpunit_errors' => 'PHPUnit Errors',
    'phpdoccheck_warnings' => 'Missing Docblocks',
    'issues' => 'Issues',

    'phpcpd' => 'PHP Copy/Paste Detector',
    'phpcs' => 'PHP Code Sniffer',
    'phpdoccheck' => 'Missing Docblocks',
    'phpmd' => 'PHP Mess Detector',
    'phpspec' => 'PHP Spec',
    'phpunit' => 'PHP Unit',

    'file' => 'File',
    'line' => 'Line',
    'class' => 'Class',
    'method' => 'Method',
    'message' => 'Message',
    'start' => 'Start',
    'end' => 'End',
    'from' => 'From',
    'to' => 'To',
    'suite' => 'Suite',
    'test' => 'Test',
    'result' => 'Result',
    'ok' => 'OK',
    'took_n_seconds' => 'Took %d seconds',
    'build_created' => 'Build Created',
    'build_started' => 'Build Started',
    'build_finished' => 'Build Finished',

    // Users
    'name' => 'Name',
    'password_change' => 'Password (leave blank if you don\'t want to change)',
    'save' => 'Save &raquo;',
    'update_your_details' => 'Update your details',
    'your_details_updated' => 'Your details have been updated.',
    'add_user' => 'Add User',
    'is_admin' => 'Is Admin?',
    'yes' => 'Yes',
    'no' => 'No',
    'edit' => 'Edit',
    'edit_user' => 'Edit User',
    'delete_user' => 'Delete User',
    'user_n_not_found' => 'User with ID %d does not exist.',
    'is_user_admin' => 'Is this user an administrator?',
    'save_user' => 'Save User',

    // Settings:
    'settings_saved' => 'Your settings have been saved.',
    'settings_check_perms' => 'Your settings could not be saved, check the permissions of your config.yml file.',
    'settings_cannot_write' => 'PHPCI cannot write to your config.yml file, settings may not be saved properly
                                until this is rectified.',
    'settings_github_linked' => 'Your Github account has been linked.',
    'settings_github_not_linked' => 'Your Github account could not be linked.',
    'build_settings' => 'Build Settings',
    'github_application' => 'Github Application',
    'github_sign_in' => 'Before you can start using Github, you need to <a href="%s">sign in</a> and grant
                            PHPCI access to your account.',
    'github_phpci_linked' => 'PHPCI is successfully linked to Github account.',
    'github_where_to_find' => 'Where to find these...',
    'github_where_help' => 'If you own the application you would like to use, you can find this information within your
                            <a href="https://github.com/settings/applications">applications</a> settings area.',

    'email_settings' => 'Email Settings',
    'email_settings_help' => 'Before PHPCI can send build status emails,
                                you need to configure your SMTP settings below.',

    'application_id' => 'Application ID',
    'application_secret' => 'Application Secret',

    'smtp_server' => 'SMTP Server',
    'smtp_port' => 'SMTP Port',
    'smtp_username' => 'SMTP Username',
    'smtp_password' => 'SMTP Password',
    'from_email_address' => 'From Email Address',
    'default_notification_address' => 'Default Notification Email Address',
    'use_smtp_encryption' => 'Use SMTP Encryption?',
    'none' => 'None',
    'ssl' => 'SSL',
    'tls' => 'TLS',

    'failed_after' => 'Consider a build failed after',
    '5_mins' => '5 Minutes',
    '15_mins' => '15 Minutes',
    '30_mins' => '30 Minutes',
    '1_hour' => '1 Hour',
    '3_hours' => '3 Hours',
);

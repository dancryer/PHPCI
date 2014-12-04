<?php
/**
 * PHPCI - Continuous Integration for PHP
 *
 * @copyright    Copyright 2014, Block 8 Limited.
 * @license      https://github.com/Block8/PHPCI/blob/master/LICENSE.md
 * @link         https://www.phptesting.org/
 */

$strings = array(
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
    'created' => 'Created',
    'started' => 'Started',
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
);
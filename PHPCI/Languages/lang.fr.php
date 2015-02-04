<?php
/**
 * PHPCI - Continuous Integration for PHP
 *
 * @copyright    Copyright 2014, Block 8 Limited.
 * @license      https://github.com/Block8/PHPCI/blob/master/LICENSE.md
 * @link         https://www.phptesting.org/
 */

$strings = array(
    'language_name' => 'Français',
    'language' => 'Langue',

    // Log in:
    'log_in_to_phpci' => 'Connectez-vous à PHPCI',
    'login_error' => 'Adresse email ou mot de passe invalide',
    'forgotten_password_link' => 'Mot de passe oublié&nbsp;?',
    'reset_emailed' => 'Nous vous avons envoyé un email avec un lien pour réinitialiser votre mot de passe.',
    'reset_header' => '<strong>Pas d\'inquiétude</strong><br>Entrez simplement votre adresse email ci-dessous
                            et nous vous enverrons un message avec un lien pour réinitialiser votre mot de passe.',
    'reset_email_address' => 'Entrez votre adresse email:',
    'reset_send_email' => 'Envoyer le mail',
    'reset_enter_password' => 'Veuillez entrer un nouveau mot de passe',
    'reset_new_password' => 'Nouveau mot de passe&nbsp;:',
    'reset_change_password' => 'Modifier le mot de passe',
    'reset_no_user_exists' => 'Il n\'existe aucun utilisateur avec cette adresse email, merci de réessayer.',
    'reset_email_body' => 'Bonjour %s,

Vous avez reçu cet email parce qu\'une demande de réinitialisation de mot de passe a été faite pour votre compte PHPCI.

Si c\'est bien vous, merci de cliquer sur le lien suivant pour réinitialiser votre mot de passe&nbsp;: %ssession/reset-password/%d/%s

Sinon, merci d\'ignorer ce message.

Merci,

PHPCI',

    'reset_email_title' => 'Réinitialisation du mot de passe PHPCI pour %s',
    'reset_invalid' => 'Requête de réinitialisation de mot de passe invalide.',
    'email_address' => 'Adresse email',
    'password' => 'Mot de passe',
    'log_in' => 'Connexion',


    // Top Nav
    'toggle_navigation' => 'Afficher/cacher la navigation',
    'n_builds_pending' => '%d builds en attente',
    'n_builds_running' => '%d builds en cours d\'exécution',
    'edit_profile' => 'Éditer le profil',
    'sign_out' => 'Déconnexion',
    'branch_x' => 'Branche&nbsp;: %s',
    'created_x' => 'Créé à&nbsp;: %s',
    'started_x' => 'Démarré à&nbsp;: %s',

    // Sidebar
    'hello_name' => 'Salut %s',
    'dashboard' => 'Tableau de bord',
    'admin_options' => 'Options d\'administration',
    'add_project' => 'Ajouter un projet',
    'settings' => 'Paramètres',
    'manage_users' => 'Gérer les utilisateurs',
    'plugins' => 'Plugins',
    'view' => 'Voir',
    'build_now' => 'Démarrer le build',
    'edit_project' => 'Éditer le projet',
    'delete_project' => 'Supprimer le projet',

    // Project Summary:
    'no_builds_yet' => 'Aucun build pour le moment&nbsp;!',
    'x_of_x_failed' => '%d des %d derniers builds ont échoué.',
    'x_of_x_failed_short' => '%d échecs / %d.',
    'last_successful_build' => ' Le dernier build réussi date du %s.',
    'never_built_successfully' => ' Aucun build de ce projet n\'a réussi.',
    'all_builds_passed' => 'Les %d derniers builds ont réussi.',
    'all_builds_passed_short' => '%d réussites / %d.',
    'last_failed_build' => ' Le dernier build en échec date du %s.',
    'never_failed_build' => ' Aucun build de ce projet n\'a échoué.',
    'view_project' => 'Voir le projet',

    // Timeline:
    'latest_builds' => 'Derniers builds',
    'pending' => 'En attente',
    'running' => 'En cours',
    'success' => 'Terminé',
    'successful' => 'Réussi',
    'failed' => 'Échoué',
    'manual_build' => 'Build manuel',

    // Add/Edit Project:
    'new_project' => 'Nouveau Projet',
    'project_x_not_found' => 'Il n\'existe pas de Projet avec l\'ID %d.',
    'project_details' => 'Détails du Projet',
    'public_key_help' => 'Pour pouvoir démarrer plus facilement, nous avons généré une paire de clés SSH à utiliser avec ce projet.
                            Pour l\'utiliser, il faut simplement ajouter la clé publique dans la section "Clés de déploiement"
                            de votre outil d\'hébergement de code.',
    'select_repository_type' => 'Sélectionnez le type de dépôt...',
    'github' => 'GitHub',
    'bitbucket' => 'Bitbucket',
    'gitlab' => 'GitLab',
    'remote' => 'URL distante',
    'local' => 'Chemin local',
    'hg'    => 'Mercurial',

    'where_hosted' => 'Où est hébergé votre projet&nbsp;?',
    'choose_github' => 'Choisissez un dépôt GitHub&nbsp;:',

    'repo_name' => 'Nom du dépôt / URL (distance) ou chemin (local)',
    'project_title' => 'Titre du projet',
    'project_private_key' => 'Clé privée à utiliser pour accéder au dépôt
                                (laissez le champ vide pour les dépôts locaux ou les URLs distantes anonymes)',
    'build_config' => 'Configuration PHPCI spécifique pour ce projet
                                (si vous ne pouvez pas ajouter de fichier phpci.yml à la racine du dépôt)',
    'default_branch' => 'Nom de la branche par défaut',
    'allow_public_status' => 'Activer la page de statut publique et l\'image pour ce projet&nbsp;?',
    'save_project' => 'Enregistrer le projet',

    'error_mercurial' => 'Les URLs de dépôt Mercurial doivent commencer par http:// ou https://',
    'error_remote' => 'Les URLs de dépôt doivent commencer par git://, http:// ou https://',
    'error_gitlab' => 'Le nom du dépôt GitLab doit avoir le format "user@domain.tld:owner/repo.git"',
    'error_github' => 'Le nom du dépôt doit être dans le format "proprietaire/dépôt"',
    'error_bitbucket' => 'Le nom du dépôt doit être dans le format "proprietaire/dépôt"',
    'error_path' => 'Le chemin que vous avez spécifié n\'existe pas.',

    // View Project:
    'all_branches' => 'Toutes les branches',
    'builds' => 'Builds',
    'id' => 'ID',
    'project' => 'Projet',
    'commit' => 'Commit',
    'branch' => 'Branche',
    'status' => 'Statut',
    'prev_link' => '&laquo; Précédent',
    'next_link' => 'Suivant &raquo;',
    'public_key' => 'Clé Publique',
    'delete_build' => 'Supprimer le build',

    'webhooks' => 'Webhooks',
    'webhooks_help_github' => 'Pour générer un build quand de nouveaux commits sont poussés, ajouter l\'url suivante
                                en tant que new "Webhook" dans la section <a href="https://github.com/%s/settings/hooks">Webhooks
                                and Services</a> de votre dépôt GitHub.',

    'webhooks_help_gitlab' => 'Pour générer un build quand de nouveaux commits sont poussés, ajouter l\'url suivante
                                and tant que "WebHook URL" dans la section Web Hooks de votre dépôt GitLab.',

    'webhooks_help_bitbucket' => 'Pour générer un build quand de nouveaux commits sont poussés, ajouter l\'url suivante
                                en tant que service "POST" dans la section
                                <a href="https://bitbucket.org/%s/admin/services">
                                Services</a> de votre dépôt Bitbucket.',

    // View Build
    'build_x_not_found' => 'Le Build avec l\'ID %d n\'existe pas.',
    'build_n' => 'Build %d',
    'rebuild_now' => 'Relancer maintenant',


    'committed_by_x' => 'Committé par %s',
    'commit_id_x' => 'Commit&nbsp;: %s',

    'chart_display' => 'Ce graphique s\'affichera une fois que le build sera terminé.',

    'build' => 'Build',
    'lines' => 'Lignes',
    'comment_lines' => 'Lignes de commentaires',
    'noncomment_lines' => 'Lignes qui ne sont pas des commentaires',
    'logical_lines' => 'Lignes logiques',
    'lines_of_code' => 'Lignes de code',
    'build_log' => 'Log du build',
    'quality_trend' => 'Tendance de la qualité',
    'codeception_errors' => 'Erreurs Codeception',
    'phpmd_warnings' => 'Alertes PHPMD',
    'phpcs_warnings' => 'Alertes PHPCS',
    'phpcs_errors' => 'Erreurs PHPCS',
    'phplint_errors' => 'Erreurs de Lint',
    'phpunit_errors' => 'Erreurs PHPUnit',
    'phpdoccheck_warnings' => 'Blocs de documentation manquants',
    'issues' => 'Tickets',

    'codeception' => 'Codeception',
    'phpcpd' => 'PHP Copy/Paste Detector',
    'phpcs' => 'PHP Code Sniffer',
    'phpdoccheck' => 'Missing Docblocks',
    'phpmd' => 'PHP Mess Detector',
    'phpspec' => 'PHP Spec',
    'phpunit' => 'PHP Unit',

    'file' => 'Fichier',
    'line' => 'Ligne',
    'class' => 'Classe',
    'method' => 'Méthode',
    'message' => 'Message',
    'start' => 'Démarrage',
    'end' => 'Fin',
    'from' => 'À partir de',
    'to' => 'jusque',
    'suite' => 'Suite',
    'test' => 'Test',
    'result' => 'Resultat',
    'ok' => 'OK',
    'took_n_seconds' => 'Exécuté en %d secondes',
    'build_created' => 'Build créé',
    'build_started' => 'Build démarré',
    'build_finished' => 'Build terminé',

    // Users
    'name' => 'Nom',
    'password_change' => 'Mot de passe (laissez vide si vous ne voulez pas le changer)',
    'save' => 'Sauvegarder &raquo;',
    'update_your_details' => 'Mettre à jour vos préférences',
    'your_details_updated' => 'Vos préférences ont été bien mises à jour.',
    'add_user' => 'Ajouter un utilisateur',
    'is_admin' => 'Est-il administrateur&nbsp;?',
    'yes' => 'Oui',
    'no' => 'Non',
    'edit' => 'Éditer',
    'edit_user' => 'Éditer l\'utilisateur',
    'delete_user' => 'Supprimer l\'utilisateur',
    'user_n_not_found' => 'L\'utilisateur avec l\'ID %d n\'existe pas.',
    'is_user_admin' => 'Est-ce que cet utilisateur est administrateur?',
    'save_user' => 'Sauvegarder l\'utilisateur',

    // Settings:
    'settings_saved' => 'Vos paramètres ont été sauvegardés.',
    'settings_check_perms' => 'Vos paramètres n\'ont pas pu être sauvegardés, vérifiez les permissions sur le fichier config.yml.',
    'settings_cannot_write' => 'PHPCI ne peut pas écrire dans votre fichier config.yml, les paramètres ne pourront pas être sauvegardés correctement
                                tant que ce ne sera pas corrigé.',
    'settings_github_linked' => 'Votre compte GitHub n\'a pas été lié.',
    'settings_github_not_linked' => 'Votre compte GitHub ne peut pas être lié.',
    'build_settings' => 'Configuration du Build',
    'github_application' => 'Application GitHub',
    'github_sign_in' => 'Avant de commencer à utiliser GitHub, vous devez vous <a href="%s">connecter</a> et autoriser
                            PHPCI à accéder à votre compte.',
    'github_phpci_linked' => 'PHPCI s\'est connecté avec succès au compte GitHub.',
    'github_where_to_find' => 'Où trouver ces informations...',
    'github_where_help' => 'Si vous souhaitez utiliser une application qui vous appartient, vous pouvez trouver ces informations dans
                            la zone de paramètres <a href="https://github.com/settings/applications">applications</a>.',

    'email_settings' => 'Configuration Email',
    'email_settings_help' => 'Avant que PHPCI puisse envoyer des emails concernant les statuts de build,
                                vous devez entrer les configurations SMTP ci-dessous.',

    'application_id' => 'Identifiant d\'application',
    'application_secret' => 'Clé secrète de l\'application',

    'smtp_server' => 'Serveur SMTP',
    'smtp_port' => 'Port SMTP',
    'smtp_username' => 'Nom d\'utilisateur SMTP',
    'smtp_password' => 'Mot de passe SMTP',
    'from_email_address' => 'Adresse à partir de laquelle sont envoyés les emails',
    'default_notification_address' => 'Adresse de notification par défaut',
    'use_smtp_encryption' => 'Est-ce que vous voulez utiliser le chiffrement SMTP',
    'none' => 'Non',
    'ssl' => 'SSL',
    'tls' => 'TLS',

    'failed_after' => 'Considérer qu\'un build a échoué après',
    '5_mins' => '5 Minutes',
    '15_mins' => '15 Minutes',
    '30_mins' => '30 Minutes',
    '1_hour' => '1 Heure',
    '3_hours' => '3 Heures',

    // Plugins
    'cannot_update_composer' => 'PHPCI ne peut pas mettre à jour le fichier composer.json pour vous, il n\'est pas modifiable.',
    'x_has_been_removed' => '%s a été supprimé.',
    'x_has_been_added' => '%s a été ajouté au fichier composer.json poru vous et il sera installé la prochaine fois
                            que vous lancerez "composer update".',
    'enabled_plugins' => 'Plugins activés',
    'provided_by_package' => 'Fournis par le paquet',
    'installed_packages' => 'Paquets installés',
    'suggested_packages' => 'Paquets suggérés',
    'title' => 'Titre',
    'description' => 'Description',
    'version' => 'Version',
    'install' => 'Installer &raquo;',
    'remove' => 'Supprimer &raquo;',
    'search_packagist_for_more' => 'Rechercher sur Packagist pour trouver plus de paquets',
    'search' => 'Rechercher &raquo;',

    // Installer
    'installation_url' => 'URL d\'installation de PHPCI',
    'db_host' => 'Hôte de la BDD',
    'db_name' => 'Nom de la BDD',
    'db_user' => 'Nom d\'utilisateur de la BDD',
    'db_pass' => 'Mot de passe de la BDD',
    'admin_name' => 'Nom de l\'admin',
    'admin_pass' => 'Mot de passe admin',
    'admin_email' => 'Adresse email de l\'admin',
    'config_path' => 'Chemin vers le fichier de configuration',
    'install_phpci' => 'Installer PHPCI',
    'welcome_to_phpci' => 'Bienvenue sur PHPCI',
    'please_answer' => 'Merci de répondre aux questions suivantes :',
    'phpci_php_req' => 'PHPCI requiert au moins PHP 5.3.8 pour fonctionner.',
    'extension_required' => 'Extensions requises : %s',
    'function_required' => 'PHPCI doit être capable d\'appeler la fonction %s(). Est-ce qu\'elle est désactivée dans votre php.ini?',
    'requirements_not_met' => 'PHPCI ne peut pas être installé parce que toutes les conditions requises ne sont pas respectées.
                                Merci de corriger les erreurs ci-dessus avant de continuer.',
    'must_be_valid_email' => 'Doit être une adresse email valide.',
    'must_be_valid_url' => 'Doit être une URL valide.',
    'enter_name' => 'Nom de l\'admin :',
    'enter_email' => 'Email de l\'admin :',
    'enter_password' => 'Mot de passe de l\'admin :',
    'enter_phpci_url' => 'Votre URL vers PHPCI (par exemple "http://phpci.local"): ',

    'enter_db_host' => 'Merci d\'entrer le nom d\'hôte MySQL [localhost]: ',
    'enter_db_name' => 'Merci d\'entrer le nom de la base MySQL [phpci]: ',
    'enter_db_user' => 'Merci d\'entrer le nom d\'utilisateur MySQL [phpci]: ',
    'enter_db_pass' => 'Merci d\'entrer le mot de passe MySQL: ',
    'could_not_connect' => 'PHPCI ne peut pas se connecter à MySQL à partir des informations fournies. Veuillez réessayer..',
    'setting_up_db' => 'Paramétrage de la base de données... ',
    'user_created' => 'Le compte utilisateur a été créé !',
    'failed_to_create' => 'PHPCI n\'a pas réussi à créer votre compte admin.',
    'config_exists' => 'Le fichier de configuration PHPCI existe et n\'est pas vide.',
    'update_instead' => 'Si vous essayez de mettre à jour PHPCI, merci d\'utiliser la commande phpci:update.',

    // Update
    'update_phpci' => 'Mise à jour de la base de données pour refléter les modifications apportées aux modèles.',
    'updating_phpci' => 'Mise à jour de la base de données PHPCI : ',
    'not_installed' => 'PHPCI n\'a pas l\'air d\'être installé.',
    'install_instead' => 'Merci d\'installer PHPCI grâce à la commande phpci:install.',

    // Poll Command
    'poll_github' => 'Demander à GitHub de vérifier si nous devons démarrer un build.',
    'no_token' => 'Aucun token GitHub n\'a été trouvé',
    'finding_projects' => 'Recherche des projets à sonder',
    'found_n_projects' => '%d projets trouvés',
    'last_commit_is' => 'Le dernier commit sur GitHub pour %s est %s',
    'adding_new_build' => 'Le dernier commit est différent de celui présent en base de données, ajout d\'un nouveau build.',
    'finished_processing_builds' => 'Traitement des builds terminé.',

    // Create Admin
    'create_admin_user' => 'Créer un utilisateur admin',
    'incorrect_format' => 'Format incorrect',

    // Run Command
    'run_all_pending' => 'Démarrage de tout les builds PHPCI en attente.',
    'finding_builds' => 'Découverte des builds à traiter',
    'found_n_builds' => '%d builds trouvés',
    'skipping_build' => 'Saut du build %d - Un build sur le projet est déjà en cours.',
    'marked_as_failed' => 'Le build %d a été marqué échoué à cause d\'un timeout.',

    // Builder
    'missing_phpci_yml' => 'Ce projet ne contient pas de fichier phpci.yml, ou il est vide.',
    'build_success' => 'BUILD RÉUSSI',
    'build_failed' => 'BUILD ÉCHOUÉ',
    'removing_build' => 'Suppression du build.',
    'exception' => 'Exception: ',
    'could_not_create_working' => 'Impossible de créer un copie de travail.',
    'working_copy_created' => 'Copie de travail créée: %s',
    'looking_for_binary' => 'Recherche du binaire: %s',
    'found_in_path' => 'Trouver dans %s: %s',
    'running_plugin' => 'EXÉCUTION DU PLUGIN: %s',
    'plugin_success' => 'PLUGIN: RÉUSSI',
    'plugin_failed' => 'PLUGIN: ÉCHOUÉ',
    'plugin_missing' => 'Le plugins n\'existe pas: %s',
    'tap_version' => 'TapParser supporte seulement les TAP version 13',
    'tap_error' => 'Chaîne de TAP invalide, le nombre de tests ne correspond pas à celui spécifié.',

    // Build Plugins:
    'no_tests_performed' => 'Aucun test n\'a été exécuté.',
    'could_not_find' => 'Impossible de trouver %s',
    'no_campfire_settings' => 'Aucune information de connexion n\'a été fournie pour le plugin Campfire',
    'failed_to_wipe' => 'Impossible de supprimer le dossier %s avant de copier',
    'passing_build' => 'Passing Build',
    'failing_build' => 'Failing Build',
    'log_output' => 'Sortie de log : ',
    'n_emails_sent' => '%d emails envoyés.',
    'n_emails_failed' => '%d emails dont l\'envoi a échoué.',
    'unable_to_set_env' => 'Impossible d\'initialiser la variable d\'environnement',
    'tag_created' => 'Tag créé par PHPCI : %s',
    'x_built_at_x' => '%PROJECT_TITLE% construit à %BUILD_URI%',
    'hipchat_settings' => 'Merci de définir une "room" et un "authToken" pour le plugin hipchat_notify',
    'irc_settings' => 'Vous devez configurer un serveur, une "room" et un "nick".',
    'invalid_command' => 'Commande invalide',
    'import_file_key' => 'La déclaration d\'import doit contenir un \'fichier\' clé',
    'cannot_open_import' => 'Impossible d\'importer le ficher SQL : %s',
    'unable_to_execute' => 'Impossible d\'exécuter le ficher SQL',
    'phar_internal_error' => 'Erreur interne au plugin Phar',
    'build_file_missing' => 'Le fichier de build spécifié n\'existe pas.',
    'property_file_missing' => 'Le fichier de propriété spécifié n\'existe pas.',
    'could_not_process_report' => 'Impossible de traiter le rapport généré par cet outil.',
    'shell_not_enabled' => 'Le plugn shell n\'est pas activé. Merci de l\'activer via le fichier config.yml.'
);

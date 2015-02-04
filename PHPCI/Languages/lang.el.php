<?php
/**
 * PHPCI - Continuous Integration for PHP
 *
 * @copyright    Copyright 2014, Block 8 Limited.
 * @license      https://github.com/Block8/PHPCI/blob/master/LICENSE.md
 * @link         https://www.phptesting.org/
 */

$strings = array(
    'language_name' => 'Ελληνικά',
    'language' => 'Γλώσσα',

    // Log in:
    'log_in_to_phpci' => 'Είσοδος στο PHPCI',
    'login_error' => 'Λάθος διεύθυνση e-mail ή κωδικός πρόσβασης',
    'forgotten_password_link' => 'Ξεχάσατε τον κωδικό σας;',
    'reset_emailed' => 'Σας έχουμε αποσταλεί ένα σύνδεσμο για να επαναφέρετε τον κωδικό πρόσβασής σας.',
    'reset_header' => '<strong> Μην ανησυχείτε! </strong> <br> Απλά εισάγετε το email σας παρακάτω και θα θα σας αποστείλουμε ένα email
με ένα σύνδεσμο για να επαναφέρετε τον κωδικό πρόσβασής σας.',
    'reset_email_address' => 'Εισάγετε τη διεύθυνση e-mail σας:',
    'reset_send_email' => 'Email επαναφοράς κωδικού πρόσβασης',
    'reset_enter_password' => 'Παρακαλώ εισάγετε ένα νέο κωδικό πρόσβασης',
    'reset_new_password' => 'Νέος κωδικός πρόσβασης:',
    'reset_change_password' => 'Αλλαγή κωδικού πρόσβασης',
    'reset_no_user_exists' => 'Δεν υπάρχει χρήστης με αυτή την διεύθυνση ηλεκτρονικού ταχυδρομείου, παρακαλώ προσπαθήστε ξανά.',
    'reset_email_body' => 'Γεια %s,

Έχετε λάβει αυτό το μήνυμα επειδή εσείς, ή κάποιος άλλος, ζήτησε επαναφορά κωδικού πρόσβασης για το PHPCI.

Αν ήσασταν εσείς, παρακαλώ κάντε κλικ στον παρακάτω σύνδεσμο για να επαναφέρετε τον κωδικό πρόσβασής σας: %ssession/reset-password/%d/%s

Σε αντίθετη περίπτωση, παρακαλούμε να αγνοήσετε αυτό το μήνυμα και δεν πρόκεται να πραγματοποιηθεί η επαναφορά.

Σας ευχαριστούμε,

PHPCI',

    'reset_email_title' => 'PHPCI Επαναφορά Κωδικού για %s',
    'reset_invalid' => 'Μη έγκυρο αίτημα επαναφοράς κωδικού πρόσβασης.',
    'email_address' => 'Διεύθυνση email',
    'password' => 'Κωδικός πρόσβασης',
    'log_in' => 'Είσοδος',


    // Top Nav
    'toggle_navigation' => 'Εναλλαγή πλοήγησης',
    'n_builds_pending' => '%d κατασκευές σε εκκρεμότητα',
    'n_builds_running' => '%d τρέχοντες κατασκευές',
    'edit_profile' => 'Επεξεργασία Προφίλ',
    'sign_out' => 'Έξοδος',
    'branch_x' => 'Διακλάδωση: %s',
    'created_x' => 'Δημιουργήθηκε: %s',
    'started_x' => 'Ξεκίνησε: %s',

    // Sidebar
    'hello_name' => 'Γειά, %s',
    'dashboard' => 'Πίνακας ελέγχου',
    'admin_options' => 'Επιλογές Διαχειριστή',
    'add_project' => 'Προσθήκη έργου',
    'settings' => 'Ρυθμίσεις',
    'manage_users' => 'Διαχείριση χρηστών',
    'plugins' => 'Πρόσθετα',
    'view' => 'Προβολή',
    'build_now' => 'Κατασκευή τώρα',
    'edit_project' => 'Επεξεργασία Έργου',
    'delete_project' => 'Διαγραφή Έργου',

    // Project Summary:
    'no_builds_yet' => 'Καμία κατασκευή ακόμα!',
    'x_of_x_failed' => '%d από τις %d τελευταίες κατασκευές απέτυχαν',
    'x_of_x_failed_short' => '%d / %d απέτυχαν.',
    'last_successful_build' => 'Η τελευταία επιτυχής κατασκεύη ήταν %s.',
    'never_built_successfully' => 'Αυτό το έργο δεν έχει ποτέ κατασκευαστεί με επιτυχία.',
    'all_builds_passed' => 'Όλες από τις %d κατασκευές πέρασαν',
    'all_builds_passed_short' => '%d / %d πέρασαν.',
    'last_failed_build' => 'H τελευταία αποτυχημένη κατασκευή ήταν %s.',
    'never_failed_build' => 'Το έργο αυτό δεν παρέλειψε ποτέ μια κατασκευή.',
    'view_project' => 'Προβολή του έργου',

    // Timeline:
    'latest_builds' => 'Τελευταίες κατασκευές',
    'pending' => 'Σε εκκρεμότητα',
    'running' => 'Τρέχοντα',
    'success' => 'Επιτυχία',
    'successful' => 'Επιτυχής',
    'failed' => 'Αποτυχία',
    'manual_build' => 'Χειροκίνητη κατασκευή',

    // Add/Edit Project:
    'new_project' => 'Νέο έργο',
    'project_x_not_found' => 'Το έργο με αριθμό %d δεν υπάρχει',
    'project_details' => 'Στοιχεία Έργου',
    'public_key_help' => 'Για να είναι πιο εύκολο να ξεκινήσετε, έχουμε δημιουργήσει  ένα ζεύγος κλειδιών SSH για να χρησιμοποιήσετε
για το έργο αυτό. Για να τα χρησιμοποιήσετε, απλά προσθέστε το ακόλουθο δημόσιο κλειδί στο τμήμα "ανάπτυξη κλειδιών"
του επιλεγμένου πηγαίου κώδικα της πλατφόρμας φιλοξενίας σας.',
    'select_repository_type' => 'Επιλέξτε τον τύπο του αποθετηρίου...',
    'github' => 'GitHub',
    'bitbucket' => 'Bitbucket',
    'gitlab' => 'GitLab',
    'remote' => 'Απομακρυσμένη διεύθυνση URL',
    'local' => 'Τοπική Διαδρομή',
    'hg'    => 'Ευμετάβλητο',

    'where_hosted' => 'Πού φιλοξενείται το έργο σας;',
    'choose_github' => 'Επιλέξτε ένα αποθετήριο GitHub:',

    'repo_name' => 'Αποθετήριο Όνομα / διεύθυνση URL (Απομακρυσμένα) ή Διαδρομή (Τοπικά)',
    'project_title' => 'Τίτλος Έργου',
    'project_private_key' => 'Ιδιωτικό κλειδί για πρόσβαση σε αποθετήριο
(αφήστε κενό για την τοπική ή / και ανώνυμα απομακρυσμένα)',
    'build_config' => 'Kατασκευή διαμόρφωσης PHPCI για αυτό το έργο
(αν δεν μπορείτε να προσθέσετε ένα αρχείο phpci.yml στο αποθετήριο έργων)',
    'default_branch' => 'Προκαθορισμένο όνομα διακλάδωσης',
    'allow_public_status' => 'Ενεργοποίηση της σελίδας δημόσιας κατάστασης και την εικόνα για το έργο αυτό;',
    'save_project' => 'Αποθήκευση έργου',

    'error_mercurial' => 'Ο σύνδεσμος URL του ευμετάβλητου αποθετηρίου πρέπει να ξεκινάει με http:// ή https://',
    'error_remote' => 'Ο σύνδεσμος URL του αποθετηρίου πρέπει να ξεκινάει με git://, http:// ή https://',
    'error_gitlab' => 'Το όνομα του αποθετηρίου GitLab πρέπει να είναι της μορφής "user@domain.tld:owner/repo.git"',
    'error_github' => 'Το όνομα του αποθετηρίου θα πρέπει να είναι της μορφής "owner/repo" ιδιοκτήτης/αποθετήριο',
    'error_bitbucket' => 'Το όνομα του αποθετηρίου θα πρέπει να είναι της μορφής "owner/repo" ιδιοκτήτης/αποθετήριο',
    'error_path' => 'Η διαδρομή που καθορίσατε δεν υπάρχει.',

    // View Project:
    'all_branches' => 'Όλες οι διακλαδώσεις',
    'builds' => 'Κατασκευές',
    'id' => 'Αριθμός αναγνώρισης',
    'project' => 'Έργο',
    'commit' => 'Συνεισφορά',
    'branch' => 'Διακλάδωση',
    'status' => 'Κατάσταση',
    'prev_link' => '&laquo; Προηγούμενο',
    'next_link' => 'Επόμενο &laquo;',
    'public_key' => 'Δημόσιο κλειδί',
    'delete_build' => 'Διαγραφή κλειδιού',

    'webhooks' => 'Webhooks',
    'webhooks_help_github' => 'Για την αυτόματη κατασκευή αυτού του έργου όταν υπάρχουν νέες συνεισφορές, προσθέστε τη διεύθυνση URL παρακάτω
ως ένα νέο "Webhook" στο τμήμα  <a href="https://github.com/%s/settings/hooks">Webhooks
and Services</a> του GitHub αποθετηρίου σας.',

    'webhooks_help_gitlab' => 'Για την αυτόματη κατασκευή αυτού του έργου όταν υπάρχουν νέες συνεισφορές, προσθέστε την διεύθυνση URL παρακάτω
σαν "WebHook URL" στο τμήμα Web Hooks του GitLab αποθετηρίου σας.',

    'webhooks_help_bitbucket' => 'Για την αυτόματη κατασκευή αυτού του έργου όταν υπάρχουν νέες συνεισφορές, προσθέστε τη διεύθυνση URL παρακάτω
ως μια υπηρεσία "POST" στο τμήμα <a href="https://bitbucket.org/%s/admin/services">
Services</a> του Bitbucket αποθετηρίου σας.',

    // View Build
    'build_x_not_found' => 'Η κατασκευή με αριθμό %d δεν υπάρχει',
    'build_n' => 'Κατασκευή %d',
    'rebuild_now' => 'Αναδόμηση τώρα',


    'committed_by_x' => 'Έγινε συνεισφορά από %s',
    'commit_id_x' => 'Συνεισφορά: %s',

    'chart_display' => 'Αυτό το γράφημα θα εμφανιστεί μόλις η κατασκευή έχει ολοκληρωθεί.',

    'build' => 'Κατασκευή',
    'lines' => 'Γραμμές',
    'comment_lines' => 'Γραμμές σχολίων',
    'noncomment_lines' => 'Μη σχολιασμένες γραμμές',
    'logical_lines' => 'Λογικές γραμμές',
    'lines_of_code' => 'Γραμμές Κώδικα',
    'build_log' => 'Αρχείο καταγραφής κατασκευών',
    'quality_trend' => 'Ποιότητα τρέντ',
    'codeception_errors' => 'Λάθη Codeception',
    'phpmd_warnings' => 'Προειδοποιήσεις PHPMD',
    'phpcs_warnings' => 'Προειδοποιήσεις PHPCS ',
    'phpcs_errors' => 'Λάθη PHPCS',
    'phplint_errors' => 'Λάθη Lint',
    'phpunit_errors' => 'Λάθη PHPUnit ',
    'phpdoccheck_warnings' => 'Χαμένα Docblocks',
    'issues' => 'Θέματα',

    'codeception' => 'Codeception',
    'phpcpd' => 'PHP Ανιχνευτής Αντιγραφής/Επικόλλησης',
    'phpcs' => 'Sniffer Κώδικα PHP',
    'phpdoccheck' => 'Χαμένα Docblocks',
    'phpmd' => 'Aνιχνευτής PHP Mess',
    'phpspec' => 'PHP Spec',
    'phpunit' => 'PHP Unit',

    'file' => 'Αρχείο',
    'line' => 'Γραμμή',
    'class' => 'Κατηγορία',
    'method' => 'Μέθοδος',
    'message' => 'Μήνυμα',
    'start' => 'Έναρξη',
    'end' => 'Τέλος',
    'from' => 'Από',
    'to' => 'Προς',
    'suite' => 'Σουίτα',
    'test' => 'Τέστ',
    'result' => 'Αποτέλεσμα',
    'ok' => 'ΟΚ',
    'took_n_seconds' => 'Χρειάστηκαν %d δευτερόλεπτα',
    'build_created' => 'Η κατασκευή δημιουργήθηκε',
    'build_started' => 'Η κατασκευή άρχισε',
    'build_finished' => 'Η κατασκευή ολοκληρώθηκε',

    // Users
    'name' => 'Όνομα',
    'password_change' => 'Κωδικός πρόσβασης (αφήστε κενό αν δεν θέλετε να αλλάξετε)',
    'save' => 'Αποθήκευση &raquo;',
    'update_your_details' => 'Ενημερώστε τα στοιχεία σας',
    'your_details_updated' => 'Τα στοιχεία σας έχουν ενημερωθεί.',
    'add_user' => 'Προσθήκη χρήστη',
    'is_admin' => 'Είναι διαχειριστής;',
    'yes' => 'Ναι',
    'no' => 'Όχι',
    'edit' => 'Επεξεργασία',
    'edit_user' => 'Επεξεργασία χρήστη',
    'delete_user' => 'Διαγραφή χρήστη',
    'user_n_not_found' => 'Ο χρήστης με αριθμό %d δεν υπάρχει.',
    'is_user_admin' => 'Είναι αυτός ο χρήστης διαχειριστής;',
    'save_user' => 'Αποθήκευση χρήστη',

    // Settings:
    'settings_saved' => 'Οι ρυθμίσεις σας έχουν αποθηκευτεί.',
    'settings_check_perms' => 'Οι ρυθμίσεις σας δεν αποθηκεύτηκαν, ελέγξτε τα δικαιώματα του αρχείου σας config.yml.',
    'settings_cannot_write' => 'Το PHPCI δεν μπορεί να γράψει στο αρχείο config.yml, οι ρυθμίσεις ενδέχεται να μην αποθηκευτούν σωστά
μέχρι να διορθωθεί.',
    'settings_github_linked' => 'Ο λογαριασμός σας GitHub έχει συνδεθεί.',
    'settings_github_not_linked' => 'Ο λογαριασμός σας Github δεν μπόρεσε να συνδεθεί.',
    'build_settings' => 'Ρυθμίσεις κατασκευής',
    'github_application' => 'GitHub Εφαρμογή',
    'github_sign_in' => 'Πριν αρχίσετε να χρησιμοποιείτε το GitHub, θα πρέπει να <a href="%s"> συνδεθείται </a> και να δώσει
το PHPCI πρόσβαση στο λογαριασμό σας.',
    'github_phpci_linked' => 'Το PHPCI συνδέθηκε με επιτυχία με το λογαριασμό Github.',
    'github_where_to_find' => 'Πού να βρείτε αυτά ...',
    'github_where_help' => 'Εάν έχετε στην κατοχή σας την εφαρμογή που θέλετε να χρησιμοποιήσετε, μπορείτε να βρείτε αυτές τις πληροφορίες στην περιοχή
<a href="https://github.com/settings/applications">Ρυθμίσεις εφαρμογών </a> ',

    'email_settings' => 'Ρυθμίσεις email',
    'email_settings_help' => 'Πριν το PHPCI μπορεί να στείλει μηνύματα ηλεκτρονικού ταχυδρομείου για την κατάσταση κατασκευής,
θα πρέπει να διαμορφώσετε τις ρυθμίσεις SMTP παρακάτω.',

    'application_id' => 'Αναγνωριστικό εφαρμογής',
    'application_secret' => 'Μυστική Εφαρμογή',

    'smtp_server' => 'Διακομισής SMTP',
    'smtp_port' => 'Θύρα SMTP',
    'smtp_username' => 'Όνομα χρήστη SMTP',
    'smtp_password' => 'Κωδικός πρόσβασης SMTP',
    'from_email_address' => 'Εmail διεύθυνση αποστολέα',
    'default_notification_address' => 'Προεπιλεγμένη διεύθυνση ειδοποίησης ηλεκτρονικού ταχυδρομείου ',
    'use_smtp_encryption' => 'Εφαρμογή SMTP κρυπτογράφησης;',
    'none' => 'Κανένα',
    'ssl' => 'Κρυπτογράφηση SSL',
    'tls' => 'Κρυπτογράφηση TLS',

    'failed_after' => 'Να θεωρηθεί μια κατασκευή αποτυχημένη μετά ',
    '5_mins' => '5 λεπτά',
    '15_mins' => '15 λεπτά',
    '30_mins' => '30 λεπτά',
    '1_hour' => '1 ώρα',
    '3_hours' => '3 ώρες',

    // Plugins
    'cannot_update_composer' => 'To PHPCI δεν μπορεί να ενημερώσει to composer.json για σας, γιατί δεν είναι εγγράψιμο.',
    'x_has_been_removed' => '%s έχει αφαιρεθεί.',
    'x_has_been_added' => '%s προσθέιηκε στο αρχείο composer.json για εσάς και θα εγκατασταθεί την επόμενη φορά
που θα τρέξετε την ενημέρωση για το composer.',
    'enabled_plugins' => 'Ενεργοποιημένα πρόσθετα',
    'provided_by_package' => 'Παρέχεται από πακέτο',
    'installed_packages' => 'Εγκατεστημένα πακέτα',
    'suggested_packages' => 'Προτεινόμενα πακέτα',
    'title' => 'Τίτλος',
    'description' => 'Περιγραφή',
    'version' => 'Έκδοση',
    'install' => 'Εγκατάσταση &raquo;',
    'remove' => 'Αφαίρεση &raquo;',
    'search_packagist_for_more' => 'Αναζήτηση στο Packagist για περισσότερα πακέτα',
    'search' => 'Αναζήτηση &raquo;',

    // Installer
    'installation_url' => 'Σύνδεσμος URL εγκατάστασης του PHPCI',
    'db_host' => 'Φιλοξενία βάσης δεδομένων',
    'db_name' => 'Όνομα βάσης δεδομένων',
    'db_user' => 'Όνομα χρήστη βάσης δεδομένων',
    'db_pass' => 'Κωδικός πρόσβασης βάσης δεδομένων',
    'admin_name' => 'Όνομα διαχειριστή',
    'admin_pass' => 'Κωδικός πρόσβασης διαχειριστή',
    'admin_email' => 'Διεύθυνση email διαχειριστή',
    'config_path' => 'Διαδρομή αρχείου ρυθμίσεων',
    'install_phpci' => 'Εγκατάσταση PHPCI',
    'welcome_to_phpci' => 'Καλώς ήρθατε στο PHPCI',
    'please_answer' => 'Παρακαλώ απαντήστε στις ακόλουθες ερωτήσεις:',
    'phpci_php_req' => 'Το PHPCI απαιτεί τουλάχιστον την έκδοση PHP 5.3.8 για να λειτουργήσει',
    'extension_required' => 'Απαιτούμενη επέκταση: %s ',
    'function_required' => 'Το PHPCI πρέπει να είναι σε θέση να καλέσει την %s() συνάρτηση. Είναι απενεργοποιημένη στο php.ini;',
    'requirements_not_met' => 'Το PHPCI δεν μπορεί να εγκατασταθεί, καθώς όλες οι απαιτήσεις δεν ικανοποιούνται.
Παρακαλούμε διαβάστε τα παραπάνω λάθη πριν συνεχίσετε.',
    'must_be_valid_email' => 'Πρέπει να είναι μια έγκυρη διεύθυνση ηλεκτρονικού ταχυδρομείου.',
    'must_be_valid_url' => 'Πρέπει να είναι μια έγκυρη διεύθυνση URL.',
    'enter_name' => 'Όνομα διαχειριστή:',
    'enter_email' => 'Ηλ. Διεύθυνση διαχειριστή:',
    'enter_password' => 'Κωδικός πρόσβασης διαχειριστή:',
    'enter_phpci_url' => 'Ο URL σύνδεσμος σας για το PHPCI ("http://phpci.local" για παράδειγμα): ',

    'enter_db_host' => 'Παρακαλώ εισάγετε τον MySQL οικοδεσπότη σας [localhost]:',
    'enter_db_name' => 'Παρακαλώ εισάγετε το όνομα της MySQL βάσης δεδομένων σας [phpci]: ',
    'enter_db_user' => 'Παρακαλώ εισάγετε το όνομα χρήστη της MySQL σας  [phpci]:',
    'enter_db_pass' => 'Παρακαλώ εισάγετε τον κωδικό χρήστη της MySQL σας:',
    'could_not_connect' => 'Το PHPCI δεν μπόρεσε να συνδεθεί με την MySQL με τα στοχεία που δώσατε. Παρακαλώ δοκιμάστε ξανά.',
    'setting_up_db' => 'Γίνεται ρύθμιση της βάσης δεδομένων σας ...',
    'user_created' => 'Λογαριασμός χρήστη δημιουργήθηκε!',
    'failed_to_create' => 'Το PHPCI απέτυχε να δημιουργήσει το λογαριασμό διαχειριστή σας.',
    'config_exists' => 'Το αρχείο ρυθμίσεων PHPCI υπάρχει και δεν είναι άδειο.',
    'update_instead' => 'Εάν προσπαθούσατε να ενημερώσετε PHPCI, παρακαλούμε χρησιμοποιήστε καλύτερα το phpci:update αντ \'αυτού.',

    // Update
    'update_phpci' => 'Ενημέρωστε την βάση δεδομένων ώστε να αντικατοπτρίζει τροποποιημένα μοντέλα.',
    'updating_phpci' => 'Γίνεται ενημέρωση της βάσης δεδομένων PHPCI:',
    'not_installed' => 'Το PHPCI δεν φένεται να είναι εγκατεστημένο',
    'install_instead' => 'Παρακαλούμε εγκαταστήστε το PHPCI καλύτερα με το phpci:install αντ \'αυτού.',

    // Poll Command
    'poll_github' => 'Δημοσκόπηση στο GitHub για να ελέγξετε αν θα πρέπει να ξεκινήσει μια κατασκευή.',
    'no_token' => 'Δεν βρέθηκε GitHub token',
    'finding_projects' => 'Αναζήτηση έργων για δημοσκόπηση',
    'found_n_projects' => 'Βρέθηκαν %d έργα',
    'last_commit_is' => 'H τελευταία συνεισφορά στο GitHub για %s είναι %s',
    'adding_new_build' => 'Τελευταία συνεισφορά είναι διαφορετική από τη βάση δεδομένων, γίνεται προσθήκη νέας κατασκευής.',
    'finished_processing_builds' => 'Ολοκληρώθηκε η επεξεργασία κατασκευής.',

    // Create Admin
    'create_admin_user' => 'Δημιουργήστε ένα χρήστη διαχειριστή',
    'incorrect_format' => 'Λανθασμένη μορφοποίηση',

    // Run Command
    'run_all_pending' => 'Εκτελέστε όλες τις εκκρεμείς PHPCI κατασκευές.',
    'finding_builds' => 'Αναζήτηση κατασκευών για επεξεργασία',
    'found_n_builds' => 'Βρέθηκαν %d κατασκευές',
    'skipping_build' => 'Παράκαμψη κατασκευής %d -  Η διαδικασία κατασκευής του έργου βρίσκεται ήδη σε εξέλιξη.',
    'marked_as_failed' => 'Η κατασκεύη %d επισημάνθηκε ως αποτυχημένη λόγω χρονικού ορίου',

    // Builder
    'missing_phpci_yml' => 'Το έργο δεν περιέχει το αρχείο phpci.yml ή είναι άδειο.',
    'build_success' => 'ΚΑΤΑΣΚΕΥΗ ΕΠΙΤΥΧΗΣ',
    'build_failed' => 'ΚΑΤΑΣΚΕΥΗ ΑΠΕΤΥΧΕ',
    'removing_build' => 'Γίνεται αφαίρεση κατασκευής',
    'exception' => 'Εξαίρεση:',
    'could_not_create_working' => 'Αδυναμία δημιουργίας αντίγραφου εργασίας.',
    'working_copy_created' => 'Αντίγραφο εργασίας που δημιουργήθηκαν: %s',
    'looking_for_binary' => 'Αναζήτηση για δυαδικό: %s',
    'found_in_path' => 'Βρέθηκε στο %s: %s',
    'running_plugin' => 'ΤΡΕΧΩΝ ΠΡΟΣΘΕΤΟ: %s',
    'plugin_success' => 'ΠΡΟΣΘΕΤΟ: ΕΠΙΤΥΧΙΑ',
    'plugin_failed' => 'ΠΡΟΣΘΕΤΟ: ΑΠΟΤΥΧΙΑ',
    'plugin_missing' => 'Το πρόσθετο δεν υπάρχει: %s',
    'tap_version' => 'Το TapParser υποστηρίζει μόνο το TAP έκδοση 13',
    'tap_error' => 'Μη έγκυρη συμβολοσειρά TAP, ο αριθμός των δοκιμών δεν ταιριάζει με την καθορισμένη καταμέτρηση της δοκιμής.',

    // Build Plugins:
    'no_tests_performed' => 'Δεν έγιναν δοκιμές.',
    'could_not_find' => 'Δεν ήταν δυνατή η εύρεση του %s',
    'no_campfire_settings' => 'Δεν έχουν δωθεί παράμετροι της σύνδεσης για το πρόσθετο Campfire',
    'failed_to_wipe' => 'Αποτυχία πλήρους διαγραφής του καταλόγου %s πριν την αντιγραφή',
    'passing_build' => 'Επιτυχημένη κατασκευή',
    'failing_build' => 'Αποτυχημένη κατασκευή',
    'log_output' => 'Σύνδεση εξόδου:',
    'n_emails_sent' => 'Στάλθηκαν %d emails ',
    'n_emails_failed' => 'Δεν στάλθηκαν %d emails ',
    'unable_to_set_env' => 'Δεν είναι δυνατός ο ορισμος μεταβλητής περιβάλλοντος',
    'tag_created' => 'Ετικέτα δημιουργήθηκε από PHPCI: %s',
    'x_built_at_x' => '%PROJECT_TITLE% χτισμένο σε %BUILD_URI%',
    'hipchat_settings' => 'Παρακαλώ ορίστε δωμάτιο και authToken για το πρόσθετο hipchat_notify',
    'irc_settings' => 'Θα πρέπει να ρυθμίσετε ένα διακομιστή, το δωμάτιο και το ψευδώνυμο.',
    'invalid_command' => 'Μη έγκυρη εντολή',
    'import_file_key' => 'Η δήλωση εισαγωγής πρέπει να περιέχει ένα κλειδί "αρχείο"',
    'cannot_open_import' => 'Δεν είναι δυνατό το άνοιγμα του SQL αρχείο εισαγωγής: %s ',
    'unable_to_execute' => 'Δεν είναι δυνατή η εκτέλεση του αρχείου SQL',
    'phar_internal_error' => 'Phar Πρόσθετο Εσωτερικό σφάλμα',
    'build_file_missing' => 'Καθορισμένο αρχείο κατασκευής δεν υπάρχει.',
    'property_file_missing' => 'Καθορισμένο αρχείο ιδιοκτησίας δεν υπάρχει.',
    'could_not_process_report' => 'Δεν ήταν δυνατή η επεξεργασία της έκθεσης που δημιουργείται από αυτό το εργαλείο.',
    'shell_not_enabled' => 'Το πρόσθετο για το κέλυφος δεν είναι ενεργοποιημένο. Παρακαλούμε ενεργοποιήστε το μέσω του αρχείου config.yml.'
);

<?php

require_once(dirname(__FILE__) . '/../bootstrap.php');

$installStage = 'start';
$formAction = '';
$config = array();
$ciUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "on" ? 'https' : 'http') . '://';
$ciUrl .= $_SERVER['HTTP_HOST'];
$ciUrl .= str_replace('/install.php', '', $_SERVER['REQUEST_URI']);

/**
 * Pre installation checks:
 */
$installOK = true;
$composerInstalled = true;
$isWriteable = true;
$phpOK = true;

$checkWriteable = function ($path) {
    if ($path{strlen($path)-1}=='/') {
        return is__writable($path.uniqid(mt_rand()).'.tmp');
    } elseif (is_dir($path)) {
        return is__writable($path.'/'.uniqid(mt_rand()).'.tmp');
    }

    // check tmp file for read/write capabilities
    $remove = file_exists($path);
    $file = @fopen($path, 'a');

    if ($file === false) {
        return false;
    }

    fclose($file);

    if (!$remove) {
        unlink($path);
    }

    return true;
};


if (!file_exists(PHPCI_DIR . 'vendor/autoload.php')) {
    $composerInstalled = false;
    $installOK = false;
}

if (!$checkWriteable(PHPCI_DIR . 'PHPCI/config.yml')) {
    $isWriteable = false;
    $installOK = false;
}

if (PHP_VERSION_ID < 50303) {
    $phpOK = false;
    $installOK = false;
}


/**
 * Installation processing:
 */
if ($installOK && strtoupper($_SERVER['REQUEST_METHOD']) == 'POST') {
    $installStage = $_POST['stage'];
    $config = json_decode(base64_decode($_POST['config']), true);

    unset($_POST['stage']);
    unset($_POST['config']);

    if (!empty($config)) {
        $config = array_merge_recursive($config, $_POST);
    } else {
        $config = $_POST;
    }

    if ($installStage == 'complete') {

        /**
         * Register autoloader:
         */
        require_once(PHPCI_DIR . 'vendor/autoload.php');

        /**
         * Write config file:
         */
        $config['b8']['database']['servers']['read'] = array($config['b8']['database']['servers']['read']);
        $config['b8']['database']['servers']['write'] = $config['b8']['database']['servers']['read'];

        $adminUser = $config['tmp']['user'];
        $adminPass = $config['tmp']['pass'];

        unset($config['tmp']);

        $dumper = new \Symfony\Component\Yaml\Dumper();
        $yaml = $dumper->dump($config, 5);

        file_put_contents(PHPCI_DIR . 'PHPCI/config.yml', $yaml);

        /**
         * Create database:
         */
        $dbhost = $config['b8']['database']['servers']['write'][0];
        $dbname = $config['b8']['database']['name'] ?: 'phpci';
        $dbuser = $config['b8']['database']['username'] ?: 'phpci';
        $dbpass = $config['b8']['database']['password'];

        $pdo = new PDO('mysql:host=' . $dbhost, $dbuser, $dbpass);

        $pdo->query('CREATE DATABASE IF NOT EXISTS `' . $dbname . '`');

        /**
         * Bootstrap PHPCI and populate database:
         */
        require(PHPCI_DIR . 'bootstrap.php');

        ob_start();
        $gen = new \b8\Database\Generator(\b8\Database::getConnection(), 'PHPCI', PHPCI_DIR . 'PHPCI/Model/Base/');
        $gen->generate();
        ob_end_clean();

        /**
         * Create our admin user:
         */
        $store = \b8\Store\Factory::getStore('User');

        try {
            $user = $store->getByEmail($adminUser);
        } catch (Exception $ex) {
        }

        if (empty($user)) {
            $user = new \PHPCI\Model\User();
            $user->setEmail($adminUser);
            $user->setName($adminUser);
            $user->setIsAdmin(1);
            $user->setHash(password_hash($adminPass, PASSWORD_DEFAULT));

            $store->save($user);
        }

        $formAction = rtrim( $config['phpci']['url'], '/' ) . '/session/login';
    }
}


switch ($installStage) {
    case 'start':
        $nextStage = 'database';
        break;

    case 'database':
        $nextStage = 'github';
        break;

    case 'github':
        $nextStage = 'email';
        break;

    case 'email':
        $nextStage = 'complete';
        break;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Install PHPCI</title>

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" type="text/css" href="assets/css/bootstrap.min.css">

    <style type="text/css">
        html {
            min-height: 100%;
        }

        body
        {
            background: #224466; /* Old browsers */
            background: radial-gradient(ellipse at center, #224466 0%,#112233 100%);
            min-height: 100%;
            font-family: Roboto, Arial, Sans-Serif;
            font-style: normal;
            font-weight: 300;
            padding-top: 0px;
        }

        #form-box
        {
            background: linear-gradient(to bottom, #fcfcfc 50%,#e0e0e0 100%);
            border-radius: 5px;
            box-shadow: 0 0 30px rgba(0,0,0, 0.3);
            margin: 0 auto;
            padding: 15px 30px;
            text-align: left;
            width: 550px;
        }

        #logo {
            background: transparent url('http://www.block8.co.uk/badge-dark-muted.png') no-repeat top left;
            display: inline-block;
            height: 26px;
            margin: 40px auto;
            width: 90px;
        }

        #logo:hover {
            background-image: url('http://www.block8.co.uk/badge-dark.png');
        }

        #phpci-logo img {
            margin-bottom: 30px;
        }

    </style>
</head>
<body>
<div class="container">
    <div class="row" style="margin-top: 30px; text-align: center">
        <a id="phpci-logo" href="http://www.phptesting.org">
            <img src="assets/img/logo-large.png">
        </a>
        <div class="" id="form-box">
            <form autocomplete="off" action="<?php print $formAction; ?>" method="POST" class="form-horizontal">
                <input type="hidden" name="prevstage" value="<?php print $installStage; ?>">
                <input type="hidden" name="stage" value="<?php print $nextStage; ?>">
                <input type="hidden" name="config" value="<?php print base64_encode(json_encode($config)); ?>">

                <?php if ($installStage == 'start'): ?>
                    <h3>Welcome to PHPCI!</h3>
                    <?php if ($installOK): ?>
                        <p>Your server has passed all of PHPCI's pre-installation checks, please press continue below to
                            begin installation.</p>
                    <?php else: ?>
                        <p>Please correct the problems below, then refresh this page to continue.</p>
                    <?php endif; ?>

                    <?php if (!$composerInstalled): ?>
                        <p class="alert alert-danger">
                            <strong>Important!</strong>
                            You need to run composer to install dependencies before running the installer.
                        </p>
                    <?php endif; ?>


                    <?php if (!$isWriteable): ?>
                        <p class="alert alert-danger">
                            <strong>Important!</strong>
                            ./PHPCI/config.yml needs to be writeable to continue.
                        </p>
                    <?php endif; ?>

                    <?php if (!$phpOK): ?>
                        <p class="alert alert-danger"><strong>Important!</strong> PHPCI requires PHP 5.3.3 or above.</p>
                    <?php endif; ?>


                <?php elseif ($installStage == 'database'): ?>
                    <h3>Database Details</h3>
                    <div class="form-group">
                        <label for="dbhost" class="col-lg-3 control-label">Host</label>
                        <div class="col-lg-9">
                            <input name="b8[database][servers][read]" type="text" class="form-control" id="dbhost" placeholder="localhost" value="localhost" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="dbname" class="col-lg-3 control-label">Name</label>
                        <div class="col-lg-9">
                            <input name="b8[database][name]" type="text" class="form-control" id="dbname" placeholder="phpci" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="dbuser" class="col-lg-3 control-label">Username</label>
                        <div class="col-lg-9">
                            <input autocomplete="off" name="b8[database][username]" type="text" class="form-control" id="dbuser" placeholder="phpci" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="dbpass" class="col-lg-3 control-label">Password</label>
                        <div class="col-lg-9">
                            <input autocomplete="off" name="b8[database][password]" type="password" class="form-control" id="dbpass">
                        </div>
                    </div>

                    <h3>PHPCI Details</h3>
                    <div class="form-group">
                        <label for="phpciurl" class="col-lg-3 control-label">URL</label>
                        <div class="col-lg-9">
                            <input name="phpci[url]" type="url" class="form-control" id="phpciurl" value="<?php print $ciUrl; ?>" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="adminuser" class="col-lg-3 control-label">Admin Email</label>
                        <div class="col-lg-9">
                            <input autocomplete="off" name="tmp[user]" type="email" class="form-control" id="adminuser" placeholder="admin@phptesting.org" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="adminpass" class="col-lg-3 control-label">Password</label>
                        <div class="col-lg-9">
                            <input autocomplete="off" name="tmp[pass]" type="password" class="form-control" id="adminpass" required>
                        </div>
                    </div>

                <?php elseif($installStage == 'github'): ?>

                    <h3>Github App Settings (Optional)</h3>
                    <div class="form-group">
                        <label for="appkey" class="col-lg-3 control-label">App ID</label>
                        <div class="col-lg-9">
                            <input autocomplete="off" name="phpci[github][id]" type="text" class="form-control" id="appkey">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="appsecret" class="col-lg-3 control-label">Secret</label>
                        <div class="col-lg-9">
                            <input autocomplete="off" name="phpci[github][secret]" type="text" class="form-control" id="appsecret">
                        </div>
                    </div>

                <?php elseif($installStage == 'email'): ?>

                    <h3>SMTP Settings (Optional)</h3>
                    <div class="form-group">
                        <label for="emailadd" class="col-lg-3 control-label">Server</label>
                        <div class="col-lg-9">
                            <input name="phpci[email_settings][smtp_address]" type="text" class="form-control" id="emailadd" placeholder="e.g. smtp.gmail.com">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="emailport" class="col-lg-3 control-label">Port</label>
                        <div class="col-lg-9">
                            <input autocomplete="off" name="phpci[email_settings][smtp_port]" type="text" class="form-control" id="emailport" placeholder="992">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="emailenc" class="col-lg-3 control-label">Encryption</label>
                        <div class="col-lg-9">
                            <input autocomplete="off" name="phpci[email_settings][smtp_encryption]" type="checkbox" class="form-" id="emailenc" checked>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="emailuser" class="col-lg-3 control-label">Username</label>
                        <div class="col-lg-9">
                            <input autocomplete="off" name="phpci[email_settings][smtp_username]" type="text" class="form-control" id="emailuser">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="emailpass" class="col-lg-3 control-label">Password</label>
                        <div class="col-lg-9">
                            <input autocomplete="off" name="phpci[email_settings][smtp_password]" type="password" class="form-control" id="emailpass">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="emailfrom" class="col-lg-3 control-label">From Address</label>
                        <div class="col-lg-9">
                            <input autocomplete="off" name="phpci[email_settings][from_address]" type="email" class="form-control" id="emailfrom">
                            <p>The address system emails should come from.</p>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="defaultto" class="col-lg-3 control-label">Default To</label>
                        <div class="col-lg-9">
                            <input autocomplete="off" name="phpci[email_settings][default_mailto_address]" type="email" class="form-control" id="defaultto">
                            <p class="desc">The address to which notifications should go by default.</p>
                        </div>
                    </div>

                <?php else: ?>

                    <p>Thank you for installing PHPCI. Click continue below to log in for the first time!</p>

                    <input type="hidden" name="email" value="<?php print $adminUser; ?>">
                    <input type="hidden" name="password" value="<?php print $adminPass; ?>">

                <?php endif; ?>

                <div class="form-group">
                    <div class="col-lg-12">
                        <button type="submit" class="pull-right btn btn-success"<?php print (!$installOK ? ' disabled' : ''); ?>>Continue &raquo;</button>
                    </div>
                </div>

            </form>
        </div>

        <a id="logo" href="http://www.block8.co.uk/"></a>
    </div>
</div>
</body>
</html>

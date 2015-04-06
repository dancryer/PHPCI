<?php
/**
 * PHPCI - Continuous Integration for PHP
 *
 * @copyright    Copyright 2014, Block 8 Limited.
 * @license      https://github.com/Block8/PHPCI/blob/master/LICENSE.md
 * @link         https://www.phptesting.org/
 */

namespace PHPCI\Plugin;

use PHPCI;
use PHPCI\Builder;
use PHPCI\Helper\Lang;
use PHPCI\Model\Build;
use PHPCI\Plugin\Util\TapParser;

/**
 * CakePHP2 Plugin - sets the auto configuration for the application
 *
 * @author       tranfuga25s <tranfuga25s@gmail.com>
 * @package      PHPCI
 * @subpackage   Plugins
 */
class CakePHP2Configurator implements PHPCI\Plugin, PHPCI\ZeroConfigPlugin
{
    /**
     * @var string
     */
    protected $args = '';

    /**
     * @var Build
     */
    protected $build;

    /**
     * @var Builder
     */
    protected $phpci;

    /**
     * @var string The path to the app dir inside the build dir
     */
    protected $appPath;

    /**
     * Options passed to the constructor
     * @var array
     */
    protected $options = null;

    /**
     * Set up the plugin, configure options, etc.
     *
     * @param Builder $phpci
     * @param Build $build
     * @param array $options
     */
    public function __construct(Builder $phpci, Build $build, array $options = array())
    {
        $this->phpci = $phpci;
        $this->build = $build;
        $this->options = $options;
    }

    /**
     * {@inheritDoc}
     */
    public function execute()
    {
        if (count($this->options) == 0 ) {
            // No configuration set
            return true;
        }

        if (!array_key_exists('app', $this->options)) {
        	if (!file_exists('app')) {
			$this->phpci->logError("Could not found the app directory to create the configuration. Plase specifie it on the project configuration.");
			return false;
		}
            $this->phpci->log("Using app/ as app dir");
            $this->options['app'] = 'app';
        }

        // Database file
        $configDir = $this->phpci->buildPath;
        $configDir .= $this->options['app'];

        // Generate config
        $database_file = $configDir.DIRECTORY_SEPARATOR."database.php";
        if (!file_exists($database_file)) {
            $this->phpci->log("There is no database.php config on the project", \Psr\Log\LogLevel::ALERT);
            if (array_key_exists('database', $this->options)) {
                $this->phpci->log("Creating database configuration");
                $string = $this->generateDatabaseConfig($this->options['database']);
                if (file_put_contents($database_file, $string) === FALSE) {
                    $this->phpci->logError("Could not write the database.php file");
                    return false;
                } else {
                    $this->phpci->logSuccess("Writed file ".$database_file." with database configuration");
                }
            } else {
                $this->phpci->logError("There is no database configuration for the application");
                return false;
            }
        }

        $email_file = $configDir.DIRECTORY_SEPARATOR."email.php";
        if (!file_exists($email_file)) {
            $this->phpci->log("There is no email.php config on the project", \Psr\Log\LogLevel::ALERT);
            if (array_key_exists('email', $this->options)) {
                if (array_key_exists('generate', $this->options) && $this->options['generate'] == false) {
                    $this->phpci->logSucces("No configuration writen - asked explicitly");
                } else {
                    $this->phpci->log("Creating email configuration");
                    $string = $this->generateEmailConfig($this->options['email']);
                    if (file_put_contents($email_file, $string) === FALSE) {
                        $this->phpci->logError("Could not write the email.php file");
                        return false;
                    } else {
                        $this->phpci->logSuccess("Writed file ".$email_file." with database configuration");
                    }
                }
            } else {
                $this->phpci->logError("There is no email configuration for the application");
            }
        }
        return true;
    }

    /**
     * Generates a database.php configuration file with the default parameters
     * and the passed parameters
     * 
     * @param array $options
     * @return string
     */
    private function generateDatabaseConfig( $options ) {
        $default_options = array(
            'datasource' => 'Datasource/Mysql',
            'persistent' => 'false',
            'host' => 'localhost',
            'login' => 'test',
            'password' => 'test',
            'database' => 'test',
            'prefix' => '',
            'encoding' => 'utf8',
        );
        $actual_config = array_merge($default_options, $options);
        $content = <<<EOT
<?php
class DATABASE_CONFIG {

	public \$test = array(
                'datasource' => {$actual_config['datasource']},
		'persistent' => {$actual_config['persistent']},
		'host' => {$actual_config['host']},
		'login' => {$actual_config['login']},
		'password' => {$actual_config['password']},
		'database' => {$actual_config['database']},
		'prefix' => {$actual_config['prefix']},
		'encoding' => {$actual_config['encoding']},
	);
}
EOT;
        return $content;
    }

    /**
     * Generates a email.php config example
     * 
     * @param array $options
     * @return string
     */
    private function generateEmailConfig( $options ) {
        $default_options = array(
            'from' => 'you@localhost.com',
            'transport' => 'Debug',
            'host' => 'localhost',
            'port' => 25
        );
        $actual_config = array_merge($default_options, $options);
        $content = <<<EOT
<?php                
class EmailConfig {

	public \$default = array(
		'from' => {$actual_config['from']},
		'transport' => {$actual_config['transport']},
                'host' => {$actual_config['host']},
		'port' => {$actual_config['port']},
		'log' => true,
	);
}
EOT;
        return $content;
    }

    /**
     * {@inheritDoc}
     */
    public static function canExecute($stage, Builder $builder, Build $build)
    {
        return ($step == 'setup');
    }

}


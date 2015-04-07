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
			$this->phpci->log("Could not found any options to create the configuration.");
			$this->phpci->logSuccess("Not creating any configuration for this project");
            return true;
        }

        if (!array_key_exists('app', $this->options)) {
			if (!file_exists('app')) {
				$this->phpci->log(print_r($this->options, true));
				$this->phpci->logFailure("Could not found the app directory to create the configuration. Plase specify it on the project configuration.");
				return false;
			}
            $this->phpci->log("Using app/ as app dir");
            $this->options['app'] = 'app';
        }

        // Database file
        $configDir = $this->phpci->buildPath;
        $configDir .= $this->options['app'];
		$configDir .= DIRECTORY_SEPARATOR;
		$configDir .= 'Config';

        // Generate config
        $database_file = $configDir.DIRECTORY_SEPARATOR."database.php";
        if (!file_exists($database_file)) {
            $this->phpci->log("There is no database.php config on the project", \Psr\Log\LogLevel::ALERT);
            if (array_key_exists('database', $this->options)) {
                $this->phpci->log("Creating database configuration");
                $string = $this->generateDatabaseConfig($this->options['database']);
                if (file_put_contents($database_file, $string) === FALSE) {
                    $this->phpci->logFailure("Could not write the database.php file");
                    return false;
                } else {
                    $this->phpci->logSuccess("Writed file ".$database_file." with database configuration");
                }
            } else {
                $this->phpci->logFailure("There is no database configuration for the application");
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
                        $this->phpci->logFailure("Could not write the email.php file");
                        return false;
                    } else {
                        $this->phpci->logSuccess("Writed file ".$email_file." with email configuration");
                    }
                }
            } else {
                $this->phpci->logFailure("There is no email configuration for the application");
            }
        }
		
		
		// Temporary files and folders
		$tmp_path = $this->phpci->buildPath;
        $tmp_path .= $this->options['app'];
		$tmp_path .= DIRECTORY_SEPARATOR;
		$tmp_path .= 'tmp';
		if (!file_exists($tmp_path)) {
			$this->phpci->log("No tmp folder inside app found");
			if (array_key_exists('tmp', $this->options) &&
			    $this->options['tmp']['create'] == false) {
				$this->phpci->log("Not creating temporal folder");
			} else {
				$this->phpci->log("Creating temporal folder");
				$folders[] = '';
				$folders[] = 'cache';
				$folders[] = 'logs';
				$folders[] = 'cache/models';
				$folders[] = 'cache/persistent';
				foreach( $folders as $folder) {
					if (mkdir($tmp_path.DIRECTORY_SEPARATOR.$folder, 0777)) {
						$this->phpci->logSuccess("Created folder: ".$tmp_path.DIRECTORY_SEPARATOR.$folder);
					} else {
						$this->phpci->logFailure("Could not create folder: ".$tmp_path.DIRECTORY_SEPARATOR.$folder);
					}
				}
			}
		}
		
		if (!file_exists($configDir.DIRECTORY_SEPARATOR.'core.php')) {
			$this->phpci->logFailure("There is no core.php file on the project configuration folder!");
			//return false;
			if (!copy($configDir.DIRECTORY_SEPARATOR.'core.php.ls1', $configDir.DIRECTORY_SEPARATOR.'core.php')) {
				$this->phpci->logFailure("There is no bootstrap.php file on the project configuration folder!");
			}
		}
		
		if (!file_exists($configDir.DIRECTORY_SEPARATOR.'bootstrap.php')) {
			$this->phpci->logFailure("There is no bootstrap.php file on the project configuration folder!");
			//return false;
			if (!copy($configDir.DIRECTORY_SEPARATOR.'bootstrap.php.ls1', $configDir.DIRECTORY_SEPARATOR.'bootstrap.php')) {
				$this->phpci->logFailure("There is no bootstrap.php file on the project configuration folder!");
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
            'datasource' => 'Database/Mysql',
            'host' => 'localhost',
            'login' => 'test',
            'password' => 'test',
            'database' => 'test',
            'prefix' => '',
            'encoding' => 'utf8',
        );
        $actual_config = array_merge($default_options, $options['test']);
        $content = <<<EOT
<?php
class DATABASE_CONFIG {
				
	public \$default = array(
        'datasource' => '{$actual_config['datasource']}',
		'host' => '{$actual_config['host']}',
		'login' => '{$actual_config['login']}',
		'password' => '{$actual_config['password']}',
		'database' => '{$actual_config['database']}',
		'prefix' => '{$actual_config['prefix']}',
		'encoding' => '{$actual_config['encoding']}',
	);
				
	public \$test = array(
        'datasource' => '{$actual_config['datasource']}',
		'host' => '{$actual_config['host']}',
		'login' => '{$actual_config['login']}',
		'password' => '{$actual_config['password']}',
		'database' => '{$actual_config['database']}',
		'prefix' => '{$actual_config['prefix']}',
		'encoding' => '{$actual_config['encoding']}',
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
		'from' => '{$actual_config['from']}',
		'transport' => '{$actual_config['transport']}',
        'host' => '{$actual_config['host']}',
		'port' => '{$actual_config['port']}',
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


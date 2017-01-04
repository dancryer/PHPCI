<?php

namespace PHPCI\Framework\Database;
use PHPCI\Framework\Database,
    PHPCI\Framework\Database\Map,
    PHPCI\Framework\View\Template;

class CodeGenerator
{
	protected $_db      = null;
	protected $_map     = null;
	protected $_tables  = null;
	protected $_ns      = null;
	protected $_path    = null;

    /**
     * @param Database $db
     * @param array $namespaces
     * @param string $path
     * @param bool $includeCountQueries
     */
    public function __construct(Database $db, array $namespaces, $path, $includeCountQueries = true)
	{
		$this->_db      = $db;
        $this->_ns      = $namespaces;
		$this->_path    = $path;
		$this->_map     = new Map($this->_db);
		$this->_tables  = $this->_map->generate();
		$this->_counts  = $includeCountQueries;
	}

    protected function getNamespace($modelName)
    {
        return array_key_exists($modelName, $this->_ns) ? $this->_ns[$modelName] : $this->_ns['default'];
    }

    public function getPath($namespace)
    {
        return array_key_exists($namespace, $this->_path) ? $this->_path[$namespace] : $this->_path['default'];
    }

	public function generateModels()
	{
		print PHP_EOL . 'GENERATING MODELS' . PHP_EOL . PHP_EOL;

		foreach($this->_tables as $tableName => $table)
		{
            $namespace = $this->getNamespace($table['php_name']);
            $modelPath = $this->getPath($namespace) . str_replace('\\', '/', $namespace) . '/Model/';
            $basePath = $modelPath . 'Base/';
            $modelFile = $modelPath . $table['php_name'] . '.php';
            $baseFile = $basePath . $table['php_name'] . 'Base.php';

            if (!is_dir($basePath)) {
                @mkdir($basePath, 0777, true);
            }

			$model  = $this->_processTemplate($tableName, $table, 'ModelTemplate');
			$base   = $this->_processTemplate($tableName, $table, 'BaseModelTemplate');

			print '-- ' . $table['php_name'] . PHP_EOL;

			if(!is_file($modelFile))
			{
				print '-- -- Writing new Model' . PHP_EOL;
				file_put_contents($modelFile, $model);
			}

			print '-- -- Writing base Model' . PHP_EOL;
			file_put_contents($baseFile, $base);
		}
	}

	public function generateStores()
	{
		print PHP_EOL . 'GENERATING STORES' . PHP_EOL . PHP_EOL;

		foreach($this->_tables as $tableName => $table)
		{
            $namespace = $this->getNamespace($table['php_name']);
            $storePath = $this->getPath($namespace) . str_replace('\\', '/', $namespace) . '/Store/';
            $basePath = $storePath . 'Base/';
            $storeFile = $storePath . $table['php_name'] . 'Store.php';
            $baseFile = $basePath . $table['php_name'] . 'StoreBase.php';

            if (!is_dir($basePath)) {
                @mkdir($basePath, 0777, true);
            }

			$model  = $this->_processTemplate($tableName, $table, 'StoreTemplate');
			$base   = $this->_processTemplate($tableName, $table, 'BaseStoreTemplate');

			print '-- ' . $table['php_name'] . PHP_EOL;

			if(!is_file($storeFile))
			{
				print '-- -- Writing new Store' . PHP_EOL;
				file_put_contents($storeFile, $model);
			}

			print '-- -- Writing base Store' . PHP_EOL;
			file_put_contents($baseFile, $base);
		}
	}

	public function generateControllers()
	{
		print PHP_EOL . 'GENERATING CONTROLLERS' . PHP_EOL . PHP_EOL;

		@mkdir($this->_path . 'Controller/Base/', 0777, true);

		foreach($this->_tables as $tableName => $table)
		{
            $namespace = $this->getNamespace($table['php_name']);
            $controllerPath = $this->getPath($namespace) . str_replace('\\', '/', $namespace) . '/Controller/';
            $basePath = $controllerPath . 'Base/';
            $controllerFile = $controllerPath . $table['php_name'] . 'Controller.php';
            $baseFile = $basePath . $table['php_name'] . 'ControllerBase.php';

            if (!is_dir($basePath)) {
                @mkdir($basePath, 0777, true);
            }

            $model  = $this->_processTemplate($tableName, $table, 'ControllerTemplate');
			$base   = $this->_processTemplate($tableName, $table, 'BaseControllerTemplate');

			print '-- ' . $table['php_name'] . PHP_EOL;

			if(!is_file($controllerFile))
			{
				print '-- -- Writing new Controller' . PHP_EOL;
				file_put_contents($controllerFile, $model);
			}

			print '-- -- Writing base Controller' . PHP_EOL;
			file_put_contents($baseFile, $base);
		}
	}

	protected function _processTemplate($tableName, $table, $template)
	{
        $tpl = Template::createFromFile($template, B8_PATH . 'Database/CodeGenerator/');
		$tpl->appNamespace  = $this->getNamespace($table['php_name']);
		$tpl->name          = $tableName;
		$tpl->table         = $table;
		$tpl->counts        = $this->_counts;
        $tpl->addFunction('get_namespace', function($args, $view) {
            return $this->getNamespace($view->getVariable($args['model']));
        });

		return $tpl->render();
	}
}
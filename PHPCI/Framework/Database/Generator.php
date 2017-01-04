<?php

/**
 * Database generator updates a database to match a set of Models.
 */

namespace PHPCI\Framework\Database;
use PHPCI\Framework\Database;

class Generator
{
	protected $_db      = null;
	protected $_map     = null;
	protected $_tables  = null;
	protected $_ns      = null;
	protected $_path    = null;

	public function __construct(Database $db, $namespace, $path)
	{
		$this->_db      = $db;
		$this->_ns      = $namespace;
		$this->_path    = $path;
		$this->_map     = new Map($this->_db);
		$this->_tables  = $this->_map->generate();
	}

	public function generate()
	{
		error_reporting(E_ERROR & E_WARNING);
		$di = new \DirectoryIterator($this->_path);

		$this->_todo = array(
			'drop_fk'   => array(),
			'drop_index'=> array(),
			'create'    => array(),
			'alter'     => array(),
			'add_index' => array(),
			'add_fk'    => array(),
		);

		foreach($di as $file)
		{
			if($file->isDot())
			{
				continue;
			}
			$fileName = explode('.', $file->getBasename());
			if ($fileName[count($fileName)-1] != 'php')
			{
				continue;
			}	

			$modelName = '\\' . $this->_ns . '\\Model\\Base\\' . str_replace('.php', '', $file->getFilename());

			require_once($this->_path . $file->getFilename());
			$model          = new $modelName();
			$columns        = $model->columns;
			$indexes        = $model->indexes;
			$foreignKeys    = $model->foreignKeys;
			$tableName      = $model->getTableName();

			if(!array_key_exists($tableName, $this->_tables))
			{
				$this->_createTable($tableName, $columns, $indexes, $foreignKeys);
				continue;
			}
			else
			{
				$table = $this->_tables[$tableName];
				$this->_updateColumns($tableName, $table, $columns);
				$this->_updateRelationships($tableName, $table, $foreignKeys);
				$this->_updateIndexes($tableName, $table, $indexes);
			}
		}

		print 'DROP FK: ' . count($this->_todo['drop_fk']) . PHP_EOL;
		print 'DROP INDEX: ' . count($this->_todo['drop_index']) . PHP_EOL;
		print 'CREATE TABLE: ' . count($this->_todo['create']) . PHP_EOL;
		print 'ALTER TABLE: ' . count($this->_todo['alter']) . PHP_EOL;
		print 'ADD INDEX: ' . count($this->_todo['add_index']) . PHP_EOL;
		print 'ADD FK: ' . count($this->_todo['add_fk']) . PHP_EOL;


		$order = array_keys($this->_todo);

		while($group = array_shift($order))
		{
			if(!isset($this->_todo[$group]) || !is_array($this->_todo[$group]) || !count($this->_todo[$group]))
			{
				continue;
			}

			foreach($this->_todo[$group] as $query)
			{
				try
				{
					//print $query . PHP_EOL;
					$this->_db->query($query);
				}
				catch(\Exception $ex)
				{
					print 'FAILED TO EXECUTE: ' . $query . PHP_EOL;
					print $ex->getMessage().PHP_EOL.PHP_EOL;
				}
			}
		}
	}

	protected function _createTable($tbl, $cols, $idxs, $fks)
	{
		$defs = array();
		$pks = array();
		foreach($cols as $colName => $def)
		{
			$add = '`' . $colName . '` ' . $def['type'];

			switch($def['type'])
			{
				case 'text':
				case 'longtext':
				case 'mediumtext':
				case 'date':
				case 'datetime':
				case 'float':
					$add .= '';
				break;

				default:
					$add .= !empty($def['length']) ? '(' . $def['length'] . ')' : '';
				break;
			}

			if(empty($def['nullable']) || !$def['nullable'])
			{
				$add .= ' NOT NULL ';
			}

			if(!empty($def['default']))
			{
				$add .= ' DEFAULT ' . (is_numeric($def['default']) ? $def['default'] : '\'' . $def['default'] . '\'');
			}

			if(!empty($def['auto_increment']) && $def['auto_increment'])
			{
				$add .= ' AUTO_INCREMENT ';
			}

			if(!empty($def['primary_key']) && $def['primary_key'])
			{
				$pks[] = '`' . $colName . '`';
			}

			$defs[] = $add;
		}

		if(count($pks))
		{
			$defs[] = 'PRIMARY KEY (' . implode(', ', $pks) . ')';
		}

		$stmt = 'CREATE TABLE `' . $tbl . '` (' . PHP_EOL;
		$stmt .= implode(", \n", $defs);

		$stmt .= PHP_EOL . ') ENGINE=InnoDB DEFAULT CHARSET=utf8';
		$stmt .= PHP_EOL;

		$this->_todo['create'][] = $stmt;

		foreach($idxs as $name => $idx)
		{
			$this->_addIndex($tbl, $name, $idx);
		}

		foreach($fks as $name => $fk)
		{
			$this->_addFk($tbl, $name, $fk);
		}
	}

	protected function _updateColumns($tableName, $table, $columns)
	{
		$currentColumns = $table['columns'];

		while($column = array_shift($currentColumns))
		{
			if(!array_key_exists($column['name'], $columns))
			{
				$this->_todo['alter'][$tableName.'.'.$column['name']] = 'ALTER TABLE `' . $tableName . '` DROP COLUMN `' . $column['name'] . '`';
			}
			else
			{
				$model = $columns[$column['name']];

				$model['nullable'] = !isset($model['nullable']) ? false : $model['nullable'];
				$model['default'] = !isset($model['default']) ? false : $model['default'];
				$model['auto_increment'] = !isset($model['auto_increment']) ? false : $model['auto_increment'];
				$model['primary_key'] = !isset($model['primary_key']) ? false : $model['primary_key'];
				$column['is_primary_key'] = !isset($column['is_primary_key']) ? false : $column['is_primary_key'];

				if( $column['type']             != $model['type'] ||
					($column['length'] != $model['length'] && !in_array($model['type'], array('text', 'longtext', 'mediumtext', 'date', 'datetime', 'float')))  ||
					$column['null']             != $model['nullable'] ||
					$column['default']          != $model['default'] ||
					$column['auto']             != $model['auto_increment'])
				{
					$this->_updateColumn($tableName, $column['name'], $column['name'], $model);
				}
			}

			unset($columns[$column['name']]);
		}

		if(count($columns))
		{
			foreach($columns as $name => $model)
			{
				// Check if we're renaming a column:
				if(isset($model['rename']))
				{
					unset($this->_todo['alter'][$tableName.'.'.$model['rename']]);
					$this->_updateColumn($tableName, $model['rename'], $name, $model);
					continue;
				}

				// New column
				$add = '`' . $name . '` ' . $model['type'];;
				switch($model['type'])
				{
					case 'text':
					case 'longtext':
					case 'mediumtext':
					case 'date':
					case 'datetime':
					case 'float':
						$add .= '';
					break;

					default:
						$add .= !empty($model['length']) ? '(' . $model['length'] . ')' : '';
					break;
				}

				if(empty($model['nullable']) || !$model['nullable'])
				{
					$add .= ' NOT NULL ';
				}

				if(!empty($model['default']))
				{
					$add .= ' DEFAULT ' . (is_numeric($model['default']) ? $model['default'] : '\'' . $model['default'] . '\'');
				}

				if(!empty($model['auto_increment']) && $model['auto_increment'])
				{
					$add .= ' AUTO_INCREMENT ';
				}

				if(!empty($model['primary_key']) && $model['primary_key'] && !isset($table['indexes']['PRIMARY']))
				{
					$add .= ' PRIMARY KEY ';
				}

				$this->_todo['alter'][] = 'ALTER TABLE `' . $tableName . '` ADD COLUMN ' . $add;
			}
		}
	}

	protected function _updateColumn($tableName, $prevName, $newName, $model)
	{
		$add = '`' . $newName . '` ' . $model['type'];;
		switch($model['type'])
		{
			case 'text':
			case 'longtext':
			case 'mediumtext':
			case 'date':
			case 'datetime':
			case 'float':
				$add .= '';
			break;

			default:
				$add .= !empty($model['length']) ? '(' . $model['length'] . ')' : '';
			break;
		}

		if(empty($model['nullable']) || !$model['nullable'])
		{
			$add .= ' NOT NULL ';
		}

		if(!empty($model['default']))
		{
			$add .= ' DEFAULT ' . (is_numeric($model['default']) ? $model['default'] : '\'' . $model['default'] . '\'');
		}

		if(!empty($model['auto_increment']) && $model['auto_increment'])
		{
			$add .= ' AUTO_INCREMENT ';
		}

		$this->_todo['alter'][] = 'ALTER TABLE `' . $tableName . '` CHANGE COLUMN `' . $prevName . '` ' . $add;
	}

	protected function _updateRelationships($tableName, $table, $foreignKeys)
	{
		$current = $table['relationships']['toOne'];

		while($foreignKey = array_shift($current))
		{
			if(!array_key_exists($foreignKey['fk_name'], $foreignKeys))
			{
				$this->_dropFk($tableName, $foreignKey['fk_name']);
			}
			elseif( $foreignKey['from_col'] != $foreignKeys[$foreignKey['fk_name']]['local_col'] ||
					$foreignKey['table'] != $foreignKeys[$foreignKey['fk_name']]['table'] ||
					$foreignKey['col'] != $foreignKeys[$foreignKey['fk_name']]['col'] ||
					$foreignKey['fk_update'] != $foreignKeys[$foreignKey['fk_name']]['update'] ||
					$foreignKey['fk_delete'] != $foreignKeys[$foreignKey['fk_name']]['delete'])
			{
				$this->_alterFk($tableName, $foreignKey['fk_name'], $foreignKeys[$foreignKey['fk_name']]);
			}

			unset($foreignKeys[$foreignKey['fk_name']]);
		}

		if(count($foreignKeys))
		{
			foreach($foreignKeys as $name => $foreignKey)
			{
				// New column
				$this->_addFk($tableName, $name, $foreignKey);
			}
		}
	}

	protected function _updateIndexes($tableName, $table, $indexes)
	{
		$current = $table['indexes'];

		while($index = array_shift($current))
		{
			if(!array_key_exists($index['name'], $indexes))
			{
				$this->_dropIndex($tableName, $index['name']);
			}
			elseif( $index['unique'] != $indexes[$index['name']]['unique'] ||
				$index['columns'] != $indexes[$index['name']]['columns'])
			{
				$this->_alterIndex($tableName, $index['name'], $index);
			}

			unset($indexes[$index['name']]);
		}

		if(count($indexes))
		{
			foreach($indexes as $name => $index)
			{
				if($name == 'PRIMARY')
				{
					continue;
				}

				// New index
				$this->_addIndex($tableName, $name, $index);
			}
		}
	}

	protected function _addIndex($table, $name, $idx, $stage = 'add_index')
	{
		if($name == 'PRIMARY')
		{
			return;
		}

		$q = 'CREATE ' . (isset($idx['unique']) && $idx['unique'] ? 'UNIQUE' : '') . ' INDEX `' . $name . '` ON `' . $table . '` (' . $idx['columns'] . ')';

		$this->_todo[$stage][] = $q;
	}

	protected function _alterIndex($table, $name, $idx, $stage = 'index')
	{
		$this->_dropIndex($table, $name, $stage);
		$this->_addIndex($table, $name, $idx, $stage);
	}

	protected function _dropIndex($table, $idxName, $stage = 'drop_index')
	{
		if($idxName == 'PRIMARY')
		{
			return;
		}

		$q = 'DROP INDEX `' . $idxName . '` ON `' . $table . '`';
		$this->_todo[$stage][] = $q;
	}

	protected function _addFk($table, $name, $fk)
	{
		$q = 'ALTER TABLE `' . $table . '` ADD CONSTRAINT `' . $name . '` FOREIGN KEY (`' . $fk['local_col'] . '`) REFERENCES `'.$fk['table'].'` (`'.$fk['col'].'`)';

		if(!empty($fk['delete']))
		{
			$q .= ' ON DELETE ' . $fk['delete'] . ' ';
		}

		if(!empty($fk['update']))
		{
			$q .= ' ON UPDATE ' . $fk['update'] . ' ';
		}

		$this->_todo['add_fk'][] = $q;
	}

	protected function _alterFk($table, $name, $fk)
	{
		$this->_dropFk($table, $name);
		$this->_addFk($table, $name, $fk);
	}

	protected function _dropFk($table, $name)
	{
		$q = 'ALTER TABLE `'.$table.'` DROP FOREIGN KEY `' . $name . '`';
		$this->_todo['drop_fk'][] = $q;
	}
}

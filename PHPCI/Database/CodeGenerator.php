<?php

namespace PHPCI\Database;

class CodeGenerator extends \Block8\Database\CodeGenerator
{
    protected function template($template)
    {
        $loader = new \Twig_Loader_Filesystem();
        $loader->addPath(PHPCI_DIR . 'PHPCI/Database/Templates/');

        $twig = new \Twig_Environment($loader, [
            'charset' => 'UTF-8',
            'cache' => false,
            'auto_reload' => true,
            'strict_variables' => false,
        ]);

        $twig->addFunction(new \Twig_SimpleFunction('getNamespace', function ($model) {
            return $this->getNamespace($model);
        }));

        return $twig->load($template . '.twig');
    }

    protected function processTemplate($tableName, $table, $template)
    {
        $methods = [];

        foreach ($table['columns'] as &$column) {

            if (isset($column['validate_int']) && $column['validate_int']) {
                $column['param_type'] = 'int';
            }

            if (isset($column['validate_string']) && $column['validate_string']) {
                $column['param_type'] = 'string';
            }

            if (isset($column['validate_float']) && $column['validate_float']) {
                $column['param_type'] = 'float';
            }

            if (isset($column['validate_date']) && $column['validate_date']) {
                $column['param_type'] = null;
            }

            $column['default_formatted'] = 'null';

            if (!empty($column['default'])) {
                if (is_numeric($column['default'])) {
                    $column['default_formatted'] = $column['default'];
                } elseif ($column['default'] != 'CURRENT_TIMESTAMP') {
                    $column['default_formatted'] = '\''.$column['default'].'\'';
                }
            }

            $methods[$column['name']] = $column['php_name'];
        }

        if (isset($table['relationships']['toOne'])) {
            foreach ($table['relationships']['toOne'] as $fk) {
                $methods[$fk['php_name']] = $fk['php_name'];
            }
        }

        return $this->template($template)->render([
            'itemNamespace' => $this->getNamespace($table['php_name']),
            'name' => $tableName,
            'table' => $table,
            'counts' => $this->counts,
            'methods' => $methods,
        ]);
    }
}

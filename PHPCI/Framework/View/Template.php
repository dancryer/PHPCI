<?php

namespace PHPCI\Framework\View;

use PHPCI\Framework\View;

class Template extends View
{
    public static $templateFunctions = [];
    protected static $extension = 'html';

    public function __construct($viewCode)
    {
        $this->viewCode = $viewCode;

        if (!count(self::$templateFunctions)) {
            self::$templateFunctions = ['include' => [$this, 'includeTemplate'], 'call' => [$this, 'callHelperFunction']];
        }
    }

    public static function createFromFile($file, $path = null)
    {
        if (!static::exists($file, $path)) {
            throw new \Exception('View file does not exist: ' . $file);
        }

        $viewFile = static::getViewFile($file, $path);

        return new static(file_get_contents($viewFile));
    }

    public static function createFromString($string)
    {
        return new static($string);
    }

    public function addFunction($name, $handler)
    {
        self::$templateFunctions[$name] = $handler;
    }

    public function removeFunction($name)
    {
        unset(self::$templateFunctions[$name]);
    }

    public function render()
    {
        return $this->parse($this->viewCode);
    }

    protected function parse($string)
    {
        $lastCond = null;
        $keywords = ['ifnot', 'if', 'else', 'for', 'loop', '@', '/ifnot', '/if', '/for', '/loop'];

        foreach (self::$templateFunctions as $function => $handler) {
            $keywords[] = $function;
        }

        $stack = ['children' => [['type' => 'string', 'body' => '']]];
        $stack['children'][0]['parent'] =& $stack;
        $current =& $stack['children'][0];

        while (!empty($string)) {
            $current['body'] .= $this->readUntil('{', $string);

            if (!empty($string)) {
                $gotKeyword = false;

                foreach ($keywords as $keyword) {
                    $kwLen = strlen($keyword) + 1;

                    if (substr($string, 0, $kwLen) == '{' . $keyword) {
                        $gotKeyword = true;
                        $item = ['type' => $keyword, 'cond' => '', 'children' => ''];
                        $string = substr($string, $kwLen);

                        $cond = trim($this->readUntil('}', $string));
                        $item['cond'] = $cond;
                        $lastCond = $cond;
                        $string = substr($string, 1);

                        if (array_key_exists($keyword, self::$templateFunctions)) {
                            $item['function_name'] = $keyword;
                            $item['type'] = 'function';
                        }

                        $str = ['type' => 'string', 'body' => ''];
                        $parent =& $current['parent'];

                        if (substr($current['body'], (0 - strlen(PHP_EOL))) === PHP_EOL) {
                            $current['body'] = substr($current['body'], 0, strlen($current['body']) - strlen(PHP_EOL));
                        }

                        $item['parent'] =& $parent;

                        $parent['children'][] = $item;

                        if ($keyword == '@' || $item['type'] == 'function') {
                            // If we're processing a variable, add a string to the parent and move up to that as current.
                            $parent['children'][] = $str;
                            $current =& $parent['children'][count($parent['children']) - 1];
                            $current['parent'] =& $parent;
                        } elseif (substr($keyword, 0, 1) == '/') {
                            // If we're processing the end of a block (if/loop), add a string to the parent's parent and move up to that.
                            $parent =& $parent['parent'];
                            $parent['children'][] = $str;
                            $current =& $parent['children'][count($parent['children']) - 1];
                            $current['parent'] =& $parent;
                        } else {
                            if (is_string($parent['children'][count($parent['children']) - 1]['children'])) {
                                $parent['children'][count($parent['children']) - 1]['children'] = [];
                            }

                            $parent['children'][count($parent['children']) - 1]['children'][] = $str;
                            $current =& $parent['children'][count($parent['children']) - 1]['children'][0];
                            $current['parent'] =& $parent['children'][count($parent['children']) - 1];
                        }

                        break;
                    }
                }

                if (!$gotKeyword) {
                    $current['body'] .= substr($string, 0, 1);
                    $string = substr($string, 1);
                }
            }
        }

        return $this->processStack($stack);
    }

    protected function processStack($stack)
    {
        $res = '';

        while (count($stack['children'])) {
            $current = array_shift($stack['children']);

            switch ($current['type']) {
                case 'string':
                    $res .= $current['body'];
                    break;

                case '@':
                    $res .= $this->doParseVar($current['cond']);
                    break;

                case 'if':
                    $res .= $this->doParseIf($current['cond'], $current);
                    break;

                case 'ifnot':
                    $res .= $this->doParseIfNot($current['cond'], $current);
                    break;

                case 'loop':
                    $res .= $this->doParseLoop($current['cond'], $current);
                    break;

                case 'for':
                    $res .= $this->doParseFor($current['cond'], $current);
                    break;

                case 'function':
                    $res .= $this->doParseFunction($current);
                    break;
            }
        }

        return $res;
    }

    protected function readUntil($until, &$string)
    {
        $read = '';

        while (!empty($string)) {
            $char = substr($string, 0, 1);

            if ($char == $until) {
                break;
            }

            $read .= $char;
            $string = substr($string, 1);
        }

        return $read;
    }

    protected function doParseVar($var)
    {
        if ($var == 'year') {
            return date('Y');
        }

        $val = $this->processVariableName($var);

        return $val;
    }

    protected function doParseIf($condition, $stack)
    {
        if ($this->ifConditionIsTrue($condition)) {
            return $this->processStack($stack);
        } else {
            return '';
        }
    }

    protected function doParseIfNot($condition, $stack)
    {
        if (!$this->ifConditionIsTrue($condition)) {
            return $this->processStack($stack);
        } else {
            return '';
        }
    }

    protected function ifConditionIsTrue($condition)
    {
        $matches = [];

        if (preg_match('/([a-zA-Z0-9_\-\(\):\s.\"]+)\s+?([\!\=\<\>]+)?\s+?([a-zA-Z0-9\(\)_\-:\s.\"]+)?/', $condition, $matches)) {
            $left = is_numeric($matches[1]) ? intval($matches[1]) : $this->processVariableName($matches[1]);
            $right = is_numeric($matches[3]) ? intval($matches[3]) : $this->processVariableName($matches[3]);
            $operator = $matches[2];

            switch ($operator) {
                case '==':
                case '=':
                    return ($left == $right);

                case '!=':
                    return ($left != $right);

                case '>=':
                    return ($left >= $right);

                case '<=':
                    return ($left <= $right);

                case '>':
                    return ($left > $right);

                case '<':
                    return ($left < $right);
            }
        } elseif (preg_match('/([a-zA-Z0-9_\-\(\):\s.]+)/', $condition, $matches)) {
            return $this->processVariableName($condition) ? true : false;
        }

        return false;
    }

    protected function doParseLoop($var, $stack)
    {
        $working = $this->processVariableName($var);

        if (is_null($working)) {
            return '';
        }

        if (!is_array($working)) {
            $working = [$working];
        }

        $rtn = '';
        foreach ($working as $key => $val) {
            // Make sure we support nesting loops:
            $keyWas = isset($this->key) ? $this->key : null;
            $valWas = isset($this->value) ? $this->value : null;
            $itemWas = isset($this->item) ? $this->item : null;

            // Set up the necessary variables within the stack:
            $this->parent = $this;
            $this->item = $val;
            $this->key = $key;
            $this->value = $val;
            $rtn .= $this->processStack($stack);

            // Restore state for any parent nested loops:
            $this->item = $itemWas;
            $this->key = $keyWas;
            $this->value = $valWas;
        }

        return $rtn;
    }

    /**
     * Processes loops in templates, of the following styles:
     *
     * <code>
     * {for myarray.items}
     *     {@item.title}
     * {/for}
     * </code>
     *
     * Or:
     *
     * <code>
     * {for 0:pages.count; i++}
     *     <a href="/item/{@i}">{@i}</a>
     * {/for}
     * </code>
     *
     * @param $cond string The condition string for the loop, to be parsed (e.g. "myarray.items" or "0:pages.count; i++")
     * @param $stack string The child stack for this loop, to be processed for each item.
     * @return string
     * @throws \Exception
     */
    protected function doParseFor($cond, $stack)
    {
        // If this is a simple foreach loop, jump over to parse loop:
        if (strpos($cond, ';') === false) {
            return $this->doParseLoop($cond, $stack);
        }

        // Otherwise, process as a for loop:
        $parts = explode(';', $cond);
        $range = explode(':', trim($parts[0]));

        // Process range:
        $rangeLeft = $this->getForRangePart($range[0]);
        $rangeRight = $this->getForRangePart($range[1]);

        // Process variable & incrementor / decrementor:
        $parts[1] = trim($parts[1]);

        $matches = [];
        if (preg_match('/([a-zA-Z0-9_]+)(\+\+|\-\-)/', $parts[1], $matches)) {
            $varName = $matches[1];
            $direction = $matches[2] == '++' ? 'increment' : 'decrement';
        } else {
            throw new \Exception('Syntax error in for loop: ' . $cond);
        }

        $rtn = '';

        if ($direction == 'increment') {
            for ($i = $rangeLeft; $i < $rangeRight; $i++) {
                $this->parent = $this;
                $this->{$varName} = $i;
                $rtn .= $this->processStack($stack);
            }
        } else {
            for ($i = $rangeLeft; $i > $rangeRight; $i--) {
                $this->parent = $this;
                $this->{$varName} = $i;
                $rtn .= $this->processStack($stack);
            }
        }

        return $rtn;
    }

    protected function getForRangePart($part)
    {
        if (is_numeric($part)) {
            return intval($part);
        }

        $varPart = $this->processVariableName($part);

        if (is_numeric($varPart)) {
            return intval($varPart);
        }

        throw new \Exception('Invalid range in for loop: ' . $part);
    }

    public function processVariableName($varName)
    {
        // Case one - Test for function calls:
        if (substr($varName, 0, 1) == '(' && substr($varName, -1) == ')') {

            $functionCall = substr($varName, 1, -1);
            $parts = explode(' ', $functionCall, 2);
            $functionName = $parts[0];
            $arguments = isset($parts[1]) ? $parts[1] : null;

            return $this->executeTemplateFunction($functionName, $arguments);
        }

        // Case two - Test if it is just a string:
        if (substr($varName, 0, 1) == '"' && substr($varName, -1) == '"') {
            return substr($varName, 1, -1);
        }

        // Case three - Test if it is just a number:
        if (is_numeric($varName)) {
            return $varName;
        }

        // Case four - Test for helper calls:
        if (strpos($varName, ':') !== false) {
            list($helper, $property) = explode(':', $varName);

            $helper = $this->{$helper}();

            if (property_exists($helper, $property) || method_exists($helper, '__get')) {
                return $helper->{$property};
            }

            return null;
        }

        // Case five - Process as a variable:
        $varPart = explode('.', $varName);
        $thisPart = array_shift($varPart);


        if (!array_key_exists($thisPart, $this->_vars)) {
            return null;
        }

        $working = $this->{$thisPart};

        while (count($varPart)) {
            $thisPart = array_shift($varPart);

            if (is_object($working)) {
                // Check if we're working with an actual property:
                if (property_exists($working, $thisPart)) {
                    $working = $working->{$thisPart};
                    continue;
                }

                // Check if the object has a magic __get method:
                if (method_exists($working, '__get')) {
                    $working = $working->{$thisPart};
                    continue;
                }
            }


            if (is_array($working) && array_key_exists($thisPart, $working)) {
                $working = $working[$thisPart];
                continue;
            }

            if ($thisPart == 'toLowerCase') {
                $working = strtolower($working);
                continue;
            }

            if ($thisPart == 'toUpperCase') {
                $working = strtoupper($working);
                continue;
            }

            if ($thisPart == 'isNumeric') {
                return is_numeric($working);
            }

            return null;
        }

        return $working;
    }

    protected function doParseFunction($stack)
    {
        return $this->executeTemplateFunction($stack['function_name'], $stack['cond']);
    }

    protected function executeTemplateFunction($function, $args)
    {
        if (array_key_exists($function, self::$templateFunctions)) {
            $handler = self::$templateFunctions[$function];
            $args = $this->processFunctionArguments($args);

            return $handler($args, $this);
        }

        return null;
    }

    protected function processFunctionArguments($args)
    {
        $rtn = [];

        $args = explode(';', $args);

        foreach ($args as $arg) {
            $arg = explode(':', $arg);

            if (count($arg) == 2) {

                $key = trim($arg[0]);
                $val = trim($arg[1]);

                if (strpos($val, ',') !== false) {
                    $val = explode(',', $val);
                }

                $rtn[$key] = $val;
            }
        }

        return $rtn;
    }

    public function getVariable($variable)
    {
        return $this->processVariableName($variable);
    }

    protected function includeTemplate($args, $view)
    {
        $template = static::createFromFile($view->getVariable($args['template']));

        if (isset($args['variables'])) {
            if (!is_array($args['variables'])) {
                $args['variables'] = [$args['variables']];
            }

            foreach ($args['variables'] as $variable) {

                $variable = explode('=>', $variable);
                $variable = array_map('trim', $variable);

                if (count($variable) == 1) {
                    $template->{$variable[0]} = $view->getVariable($variable[0]);
                } else {
                    $template->{$variable[1]} = $view->getVariable($variable[0]);
                }
            }
        }

        return $template->render();
    }

    protected function callHelperFunction($args)
    {
        $helper = $args['helper'];
        $function = $args['method'];

        return $this->{$helper}()->{$function}();
    }
}
<?php
/**
 * PHPCI - Continuous Integration for PHP
 *
 * @copyright    Copyright 2014, Block 8 Limited.
 * @license      https://github.com/Block8/PHPCI/blob/master/LICENSE.md
 * @link         https://www.phptesting.org/
 */

namespace PHPCI;

use PHPCI\Framework\Database;
use PHPCI\Framework\Exception\HttpException;

/**
 * PHPCI Base Store
 * @package PHPCI
 */
abstract class Store
{
    protected $modelName   = null;
    protected $tableName   = null;
    protected $primaryKey  = null;

    /**
     * @return \PHPCI\Framework\Model
     */
    abstract public function getByPrimaryKey($key, $useConnection = 'read');

    public function getWhere($where = [], $limit = 25, $offset = 0, $joins = [], $order = [], $manualJoins = [], $group = null, $manualWheres = [], $whereType = 'AND')
    {
        $query      = 'SELECT ' . $this->tableName . '.* FROM ' . $this->tableName;
        $countQuery = 'SELECT COUNT(*) AS cnt FROM ' . $this->tableName;

        $wheres = [];
        $params = [];
        foreach($where as $key => $value)
        {
            $key = $this->fieldCheck($key);

            if(!is_array($value))
            {
                $params[] = $value;
                $wheres[] = $key . ' = ?';
            }
            else
            {
                if(isset($value['operator']))
                {
                    if(is_array($value['value']))
                    {
                        if($value['operator'] == 'between')
                        {
                            $params[] = $value['value'][0];
                            $params[] = $value['value'][1];
                            $wheres[] = $key . ' BETWEEN ? AND ?';
                        }
                        elseif($value['operator'] == 'IN')
                        {
                            $in = [];

                            foreach($value['value'] as $item)
                            {
                                $params[] = $item;
                                $in[]     = '?';
                            }

                            $wheres[] = $key . ' IN (' . implode(', ', $in) . ') ';
                        }
                        else
                        {
                            $ors = [];
                            foreach($value['value'] as $item)
                            {
                                if($item == 'null')
                                {
                                    switch($value['operator'])
                                    {
                                        case '!=':
                                            $ors[] = $key . ' IS NOT NULL';
                                            break;

                                        case '==':
                                        default:
                                            $ors[] = $key . ' IS NULL';
                                            break;
                                    }
                                }
                                else
                                {
                                    $params[] = $item;
                                    $ors[]    = $this->fieldCheck($key) . ' ' . $value['operator'] . ' ?';
                                }
                            }
                            $wheres[] = '(' . implode(' OR ', $ors) . ')';
                        }
                    }
                    else
                    {
                        if($value['operator'] == 'like')
                        {
                            $params[] = '%' . $value['value'] . '%';
                            $wheres[] = $key . ' ' . $value['operator'] . ' ?';
                        }
                        else
                        {
                            if($value['value'] === 'null')
                            {
                                switch($value['operator'])
                                {
                                    case '!=':
                                        $wheres[] = $key . ' IS NOT NULL';
                                        break;

                                    case '==':
                                    default:
                                        $wheres[] = $key . ' IS NULL';
                                        break;
                                }
                            }
                            else
                            {
                                $params[] = $value['value'];
                                $wheres[] = $key . ' ' . $value['operator'] . ' ?';
                            }
                        }
                    }
                }
                else
                {
                    $wheres[] = $key . ' IN (' . implode(', ', array_map(array(Database::getConnection('read'), 'quote'), $value)) . ')';
                }
            }
        }

        if(count($joins))
        {
            foreach($joins as $table => $join)
            {
                $query .= ' LEFT JOIN ' . $table . ' ' . $join['alias'] . ' ON ' . $join['on'] . ' ';
                $countQuery .= ' LEFT JOIN ' . $table . ' ' . $join['alias'] . ' ON ' . $join['on'] . ' ';
            }
        }

        if(count($manualJoins))
        {
            foreach($manualJoins as $join)
            {
                $query .= ' ' . $join . ' ';
                $countQuery .= ' ' . $join . ' ';
            }
        }

        $hasWhere = false;
        if(count($wheres))
        {
            $hasWhere = true;
            $query .= ' WHERE (' . implode(' ' . $whereType . ' ', $wheres) . ')';
            $countQuery .= ' WHERE (' . implode(' ' . $whereType . ' ', $wheres) . ')';
        }

        if(count($manualWheres))
        {
            foreach($manualWheres as $where)
            {
                if(!$hasWhere)
                {
                    $hasWhere = true;
                    $query .= ' WHERE ';
                    $countQuery .= ' WHERE ';
                }
                else
                {
                    $query .= ' ' . $where['type'] . ' ';
                    $countQuery .= ' ' . $where['type'] . ' ';
                }

                $query .= ' ' . $where['query'];
                $countQuery .= ' ' . $where['query'];

                if(isset($where['params']))
                {
                    foreach($where['params'] as $param)
                    {
                        $params[] = $param;
                    }
                }
            }
        }

        if(!is_null($group))
        {
            $query .= ' GROUP BY ' . $group . ' ';
        }

        if(count($order))
        {
            $orders = [];
            if(is_string($order) && $order == 'rand')
            {
                $query .= ' ORDER BY RAND() ';
            }
            else
            {
                foreach($order as $key => $value)
                {
                    $orders[] = $this->fieldCheck($key) . ' ' . $value;
                }

                $query .= ' ORDER BY ' . implode(', ', $orders);
            }
        }

        if($limit)
        {
            $query .= ' LIMIT ' . $limit;
        }

        if($offset)
        {
            $query .= ' OFFSET ' . $offset;
        }

        try
        {
            $stmt = Database::getConnection('read')->prepare($countQuery);
            $stmt->execute($params);
            $res   = $stmt->fetch(\PDO::FETCH_ASSOC);
            $count = (int)$res['cnt'];
        }
        catch(\PDOException $ex)
        {
            $count = 0;
        }

        try
        {
            $stmt = Database::getConnection('read')->prepare($query);
            $stmt->execute($params);
            $res = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            $rtn = [];

            foreach($res as $data)
            {
                $rtn[] = new $this->modelName($data);
            }

            return array('items' => $rtn, 'count' => $count);
        }
        catch(\PDOException $ex)
        {
            throw $ex;
        }
    }

    public function save(Model $obj, $saveAllColumns = false)
    {
        if(!isset($this->primaryKey))
        {
            throw new HttpException\BadRequestException('Save not implemented for this store.');
        }

        if(!($obj instanceof $this->modelName))
        {
            throw new HttpException\BadRequestException(get_class($obj) . ' is an invalid model type for this store.');
        }

        $data = $obj->getDataArray();

        if(isset($data[$this->primaryKey]))
        {
            $rtn = $this->saveByUpdate($obj, $saveAllColumns);
        }
        else
        {
            $rtn = $this->saveByInsert($obj, $saveAllColumns);
        }

        return $rtn;
    }

    public function saveByUpdate(Model $obj, $saveAllColumns = false)
    {
        $rtn = null;
        $data = $obj->getDataArray();
        $modified = ($saveAllColumns) ? array_keys($data) : $obj->getModified();

        $updates = [];
        $update_params = [];
        foreach($modified as $key)
        {
            $updates[]       = $key . ' = :' . $key;
            $update_params[] = array($key, $data[$key]);
        }

        if(count($updates))
        {
            $qs = 'UPDATE ' . $this->tableName . '
											SET ' . implode(', ', $updates) . '
											WHERE ' . $this->primaryKey . ' = :primaryKey';
            $q  = Database::getConnection('write')->prepare($qs);

            foreach($update_params as $update_param)
            {
                $q->bindValue(':' . $update_param[0], $update_param[1]);
            }

            $q->bindValue(':primaryKey', $data[$this->primaryKey]);
            $q->execute();

            $rtn = $this->getByPrimaryKey($data[$this->primaryKey], 'write');
        }
        else
        {
            $rtn = $obj;
        }

        return $rtn;
    }

    public function saveByInsert(Model $obj, $saveAllColumns = false)
    {
        $rtn = null;
        $data = $obj->getDataArray();
        $modified = ($saveAllColumns) ? array_keys($data) : $obj->getModified();

        $cols    = [];
        $values  = [];
        $qParams = [];
        foreach($modified as $key)
        {
            $cols[]              = $key;
            $values[]            = ':' . $key;
            $qParams[':' . $key] = $data[$key];
        }

        if(count($cols))
        {
            $qs = 'INSERT INTO ' . $this->tableName . ' (' . implode(', ', $cols) . ') VALUES (' . implode(', ', $values) . ')';
            $q  = Database::getConnection('write')->prepare($qs);

            if($q->execute($qParams))
            {
                $id = !empty($data[$this->primaryKey]) ? $data[$this->primaryKey] : Database::getConnection('write')->lastInsertId();
                $rtn = $this->getByPrimaryKey($id, 'write');
            }
        }

        return $rtn;
    }

    public function delete(Model $obj)
    {
        if(!isset($this->primaryKey))
        {
            throw new HttpException\BadRequestException('Delete not implemented for this store.');
        }

        if(!($obj instanceof $this->modelName))
        {
            throw new HttpException\BadRequestException(get_class($obj) . ' is an invalid model type for this store.');
        }

        $data = $obj->getDataArray();

        $q = Database::getConnection('write')->prepare('DELETE FROM ' . $this->tableName . ' WHERE ' . $this->primaryKey . ' = :primaryKey');
        $q->bindValue(':primaryKey', $data[$this->primaryKey]);
        $q->execute();

        return true;
    }

    /**
     *
     */
    protected function fieldCheck($field)
    {
        if(empty($field))
        {
            throw new HttpException('You cannot have an empty field name.');
        }

        if(strpos($field, '.') === false)
        {
            return $this->tableName . '.' . $field;
        }

        return $field;
    }
}

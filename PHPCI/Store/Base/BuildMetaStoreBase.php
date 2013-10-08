<?php

/**
 * BuildMeta base store for table: build_meta
 */

namespace PHPCI\Store\Base;

use b8\Store;

/**
 * BuildMeta Base Store
 */
class BuildMetaStoreBase extends Store
{
    protected $tableName   = 'build_meta';
    protected $modelName   = '\PHPCI\Model\BuildMeta';
    protected $primaryKey  = 'id';

    public function getByPrimaryKey($value, $useConnection = 'read')
    {
        return $this->getById($value, $useConnection);
    }



    public function getById($value, $useConnection = 'read')
    {
        if (is_null($value)) {
            throw new \b8\Exception\HttpException('Value passed to ' . __FUNCTION__ . ' cannot be null.');
        }

        $query = 'SELECT * FROM build_meta WHERE id = :id LIMIT 1';
        $stmt = \b8\Database::getConnection($useConnection)->prepare($query);
        $stmt->bindValue(':id', $value);

        if ($stmt->execute()) {
            if ($data = $stmt->fetch(\PDO::FETCH_ASSOC)) {
                return new \PHPCI\Model\BuildMeta($data);
            }
        }

        return null;
    }

    public function getByBuildId($value, $limit = null, $useConnection = 'read')
    {
        if (is_null($value)) {
            throw new \b8\Exception\HttpException('Value passed to ' . __FUNCTION__ . ' cannot be null.');
        }

        $add = '';

        if ($limit) {
            $add .= ' LIMIT ' . $limit;
        }

        $query = 'SELECT COUNT(*) AS cnt FROM build_meta WHERE build_id = :build_id' . $add;
        $stmt = \b8\Database::getConnection($useConnection)->prepare($query);
        $stmt->bindValue(':build_id', $value);

        if ($stmt->execute()) {
            $res    = $stmt->fetch(\PDO::FETCH_ASSOC);
            $count  = (int)$res['cnt'];
        } else {
            $count = 0;
        }

        $query = 'SELECT * FROM build_meta WHERE build_id = :build_id' . $add;
        $stmt = \b8\Database::getConnection('read')->prepare($query);
        $stmt->bindValue(':build_id', $value);

        if ($stmt->execute()) {
            $res = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $map = function ($item) {
                return new \PHPCI\Model\BuildMeta($item);
            };
            $rtn = array_map($map, $res);

            return array('items' => $rtn, 'count' => $count);
        } else {
            return array('items' => array(), 'count' => 0);
        }
    }
}

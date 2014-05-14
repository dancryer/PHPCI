<?php
/**
 * PHPCI - Continuous Integration for PHP
 *
 * @copyright    Copyright 2014, Block 8 Limited.
 * @license      https://github.com/Block8/PHPCI/blob/master/LICENSE.md
 * @link         https://www.phptesting.org/
 */

namespace PHPCI\Model;

use PHPCI\Model\Base\ProjectBase;
use PHPCI\Model\Build;
use b8\Store;

/**
* Project Model
* @uses         PHPCI\Model\Base\ProjectBase
* @author       Dan Cryer <dan@block8.co.uk>
* @package      PHPCI
* @subpackage   Core
*/
class Project extends ProjectBase
{
    public function getLatestBuild($branch = 'master', $status = null)
    {
        $criteria       = array('branch' => $branch, 'project_id' => $this->getId());

        if (isset($status)) {
            $criteria['status'] = $status;
        }

        $order          = array('id' => 'DESC');
        $builds         = Store\Factory::getStore('Build')->getWhere($criteria, 1, 0, array(), $order);

        if (is_array($builds['items']) && count($builds['items'])) {
            $latest = array_shift($builds['items']);

            if (isset($latest) && $latest instanceof Build) {
                return $latest;
            }
        }

        return null;
    }

    public function getAccessInformation($key = null)
    {
        $data = unserialize($this->data['access_information']);

        if (is_null($key)) {
            $rtn = $data;
        } elseif (isset($data[$key])) {
            $rtn = $data[$key];
        } else {
            $rtn = null;
        }

        return $rtn;
    }
}

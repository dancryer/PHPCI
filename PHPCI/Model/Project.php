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
    /**
     * Get latest build for the project.
     *
     * @param string $branch
     *   The branch used in the build to search.
     *
     * @param int $status
     *   The result status for the build to search.
     *
     * @return PHPCI\Model\Build|null
     *   Build or null if no build match specified criteria.
     */
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

    /**
     * Setter for project access information property.
     *
     * @param mixed $value
     *   Information to be stored, if array the value will be converted as json
     *   string to be stored into DB.
     */
    public function setAccessInformation($value)
    {
        if (is_array($value)) {
            $value = json_encode($value);
        }

        parent::setAccessInformation($value);
    }

    public function getAccessInformation($key = null)
    {
        $info = $this->data['access_information'];

        // Handle old-format (serialized) access information first:
        if (!empty($info) && substr($info, 0, 1) != '{') {
            $data = unserialize($info);
        } else {
            $data = json_decode($info, true);
        }

        if (is_null($key)) {
            $rtn = $data;
        } elseif (isset($data[$key])) {
            $rtn = $data[$key];
        } else {
            $rtn = null;
        }

        return $rtn;
    }

    /**
     * Get the value of Branch / branch.
     *
     * @return string
     */
    public function getBranch()
    {
        if (empty($this->data['branch'])) {
            return $this->getType() === 'hg' ? 'default' : 'master';
        } else {
            return $this->data['branch'];
        }
    }

    /**
     * Check is project can be accessible
     *
     * The project access is validated using public visibility or -if set- by
     * authorization token.
     *
     * @param string $auth_token
     *   The autorization token used to validate access.
     *
     * @return bool
     *   Indicate if the project can be accessed.
     */
    public function isAccessAllowed($auth_token = null)
    {
        if ($this->getAllowPublicStatus()) {
            return true;
        }

        if (is_null($auth_token)) {
            return false;
        }

        if (is_null($this->getAuthToken())) {
            return false;
        }

        return $auth_token === $this->getAuthToken();
    }
}

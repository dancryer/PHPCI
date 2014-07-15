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
     * @author Jonathan Libby <j@thelibbster.com>
     *
     * @method getReferenceForLink
     *  Gets a project reference, safe for use in page links where a port
     *  number in the ssh://foo@example.com:port/user/repo.git form would
     *  invalidate the link.
     *
     * @return string
     */
    public function getReferenceForLink()
    {
        /**
         * Split the reference field into a few sections. If a port number is
         * included, it will be in $matches[1]; regardless, $matches[2] will
         * always be the username associated with the repo and $matches[3] will
         * always be the repository name.
         */
        $reference = $this->getReference();
        preg_match(
            "#\b0*([1-9][0-9]{0,3}|[1-5][0-9]{4}|6[0-4][0-9]{3}|65[0-4][0-9]{2}|655[0-2][0-9]|6553[0-5])?\b/?(.*)/(.*)#",
            $reference,
            $matches
        );
        $rtn = $matches[2]."/".$matches[3];

        return $rtn;
    }

}

<?php
/**
 * PHPCI - Continuous Integration for PHP
 *
 * @copyright    Copyright 2014, Block 8 Limited.
 * @license      https://github.com/Block8/PHPCI/blob/master/LICENSE.md
 * @link         https://www.phptesting.org/
 */

namespace PHPCI\Model\Build;

use PHPCI\Model\Build;
use PHPCI\Model\Build\RemoteGitBuild;

/**
* Gitlab Build Model
* @author       André Cianfarani <a.cianfarani@c2is.fr>
* @package      PHPCI
* @subpackage   Core
*/
class GitlabBuild extends RemoteGitBuild
{

    /**
    * Get link to commit from another source (i.e. Github)
    */
    public function getCommitLink()
    {
        $domain = $this->getProject()->getAccessInformation("domain");
        return 'http://' . $domain . '/' . $this->getProject()->getReference() . '/commit/' . $this->getCommitId();
    }

    /**
    * Get link to branch from another source (i.e. Github)
    */
    public function getBranchLink()
    {
        $domain = $this->getProject()->getAccessInformation("domain");
        return 'http://' . $domain . '/' . $this->getProject()->getReference() . '/tree/' . $this->getBranch();
    }

    /**
     * Get link to specific file (and line) in a the repo's branch
     */
    public function getFileLinkTemplate()
    {
        return sprintf(
            'http://%s/%s/blob/%s/{FILE}#L{LINE}',
            $this->getProject()->getAccessInformation("domain"),
            $this->getProject()->getReference(),
            $this->getBranch()
        );
    }

    /**
     * Convert status code to string format.
     *
     * @param int $status
     *   Build status code.
     *
     * @return string
     *   Status code, can be 'pending', 'running', 'success' or 'failed'.
     *
     * @throw Exception
     */
    public function getGilabStatus()
    {
        switch ($this->getStatus()) {
          case Build::STATUS_NEW:
            return 'pending';
            break;
          case Build::STATUS_RUNNING:
            return 'running';
            break;
          case Build::STATUS_SUCCESS:
            return 'success';
            break;
          case Build::STATUS_FAILED:
            return 'failed';
            break;
          default:
            throw new \Exception('Status not valid');
        }
    }

    /**
     * Convert build class to object that can be converted to JSON string.
     *
     * @param Build $build
     *   Build to be converted
     *
     * @return array
     *   Build data with:
     *     - branch: branch name
     *     - commit: commit hash
     *     - message: commit message
     *     - committer: committer mail
     *     - id: build id
     *     - project: project name configured on PHPCI
     *     - status: build status in string format
     *     - log: the build log result
     *     - created: build creation time in ISO8601 format
     *     - started: build starting time in ISO8601 format
     *     - finished: build finishing time in ISO8601 format
     *
     * @throw Exception
     */
    public function convertJSON()
    {
        $result = array(
          // Git related informations.
          'branch' => $this->getBranch(),
          'commit' => $this->getCommitId(),
          // Build related informations.
          'id' => $this->getId(),
          'status' => $this->getGilabStatus(),
          'log' => $this->getLog(),

          'created' => $this->getCreated()->format(\DateTime::ISO8601),
          'started' => $this->getStarted()->format(\DateTime::ISO8601),
          'finished' => $this->getFinished()->format(\DateTime::ISO8601),
        );

        return json_encode($result);
    }

    /**
    * Get the URL to be used to clone this remote repository.
    */
    protected function getCloneUrl()
    {
        $key = trim($this->getProject()->getSshPrivateKey());

        if (!empty($key)) {
            $user = $this->getProject()->getAccessInformation("user");
            $domain = $this->getProject()->getAccessInformation("domain");
            $port = $this->getProject()->getAccessInformation('port');

            $url = $user . '@' . $domain . ':';

            if (!empty($port)) {
                $url .= $port . '/';
            }

            $url .= $this->getProject()->getReference() . '.git';

            return $url;
        }
    }
}

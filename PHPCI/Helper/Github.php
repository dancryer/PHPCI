<?php
/**
 * PHPCI - Continuous Integration for PHP
 *
 * @copyright    Copyright 2014, Block 8 Limited.
 * @license      https://github.com/Block8/PHPCI/blob/master/LICENSE.md
 * @link         https://www.phptesting.org/
 */

namespace PHPCI\Helper;

use b8\Cache;
use b8\Config;
use b8\HttpClient;

class Github
{
    public function makeRequest($url, $params)
    {
        $http = new HttpClient('https://api.github.com');
        $res = $http->get($url, $params);

        return $res['body'];
    }

    /**
     * Get an array of repositories from Github's API.
     */
    public function getRepositories()
    {
        $token = Config::getInstance()->get('phpci.github.token');

        if (!$token) {
            die(json_encode(null));
        }

        $cache = Cache::getCache(Cache::TYPE_APC);
        $rtn = $cache->get('phpci_github_repos');

        if (!$rtn) {
            $orgs = $this->makeRequest('/user/orgs', array('access_token' => $token));

            $params = array('type' => 'all', 'access_token' => $token);
            $repos = array();
            $repos['user'] = $this->makeRequest('/user/repos', $params);


            foreach ($orgs as $org) {
                $repos[$org['login']] = $this->makeRequest('/orgs/'.$org['login'].'/repos', $params);
            }

            $rtn = array();
            foreach ($repos as $repoGroup) {
                foreach ($repoGroup as $repo) {
                    $rtn['repos'][] = $repo['full_name'];
                }
            }

            $cache->set('phpci_github_repos', $rtn);
        }

        return $rtn;
    }
}

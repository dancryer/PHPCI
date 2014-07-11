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
     * Make all GitHub requests following the Link HTTP headers.
     *
     * @param string $url
     * @param mixed $params
     * @param array $results
     *
     * @return array
     */
    public function makeRecursiveRequest($url, $params, $results = array())
    {
        $http = new HttpClient('https://api.github.com');
        $res = $http->get($url, $params);

        foreach ($res['body'] as $item) {

            $results[] = $item;

        }

        foreach ($res['headers'] as $header) {

            if (preg_match('/^Link: <([^>]+)>; rel="next"/', $header, $r)) {

                $host = parse_url($r[1]);

                parse_str($host['query'], $params);
                $results = $this->makeRecursiveRequest($host['path'], $params, $results);

                break;

            }

        }

        return $results;
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
            $repos = array('user' => array());
            $repos['user'] = $this->makeRecursiveRequest('/user/repos', $params);

            foreach ($orgs as $org) {
                $repos[$org['login']] = $this->makeRecursiveRequest('/orgs/'.$org['login'].'/repos', $params);
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

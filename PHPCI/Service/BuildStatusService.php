<?php
/**
 * PHPCI - Continuous Integration for PHP
 *
 * @copyright    Copyright 2014, Block 8 Limited.
 * @license      https://github.com/Block8/PHPCI/blob/master/LICENSE.md
 * @link         https://www.phptesting.org/
 */

namespace PHPCI\Service;

use PHPCI\Model\Project;
use PHPCI\Model\Build;

/**
 * Class BuildStatusService
 * @package PHPCI\Service
 */
class BuildStatusService
{
    /* @var BuildStatusService */
    protected $prevService = null;

    /* @var Project */
    protected $project;

    /** @var  string */
    protected $branch;

    /* @var Build */
    protected $build;

    /** @var  string */
    protected $url;

    /** @var array  */
    protected $finishedStatusIds = array(
        Build::STATUS_SUCCESS,
        Build::STATUS_FAILED,
    );

    /**
     * @param $branch
     * @param Project $project
     * @param Build $build
     * @param bool $isParent
     */
    public function __construct(
        $branch,
        Project $project,
        Build $build = null,
        $isParent = false
    ) {
        $this->project = $project;
        $this->branch = $branch;
        $this->build = $build;
        if ($this->build) {
            $this->loadParentBuild($isParent);
        }
        if (defined('PHPCI_URL')) {
            $this->setUrl(PHPCI_URL);
        }
    }

    /**
     * @param $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

    /**
     * @return Build
     */
    public function getBuild()
    {
        return $this->build;
    }

    /**
     * @param bool $isParent
     * @throws \Exception
     */
    protected function loadParentBuild($isParent = true)
    {
        if ($isParent === false && !$this->isFinished()) {
            $lastFinishedBuild = $this->project->getLatestBuild($this->branch, $this->finishedStatusIds);

            if ($lastFinishedBuild) {
                $this->prevService = new BuildStatusService(
                    $this->branch,
                    $this->project,
                    $lastFinishedBuild,
                    true
                );
            }
        }
    }

    /**
     * @return string
     */
    public function getActivity()
    {
        if (in_array($this->build->getStatus(), $this->finishedStatusIds)) {
            return 'Sleeping';
        } elseif ($this->build->getStatus() == Build::STATUS_NEW) {
            return 'Pending';
        } elseif ($this->build->getStatus() == Build::STATUS_RUNNING) {
            return 'Building';
        }
        return 'Unknown';
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->project->getTitle() . ' / ' . $this->branch;
    }

    /**
     * @return bool
     */
    public function isFinished()
    {
        if (in_array($this->build->getStatus(), $this->finishedStatusIds)) {
            return true;
        }
        return false;
    }

    /**
     * @return null|Build
     */
    public function getFinishedBuildInfo()
    {
        if ($this->isFinished()) {
            return $this->build;
        } elseif ($this->prevService) {
            return $this->prevService->getBuild();
        }
        return null;
    }

    /**
     * @return int|string
     */
    public function getLastBuildLabel()
    {
        if ($buildInfo = $this->getFinishedBuildInfo()) {
            return $buildInfo->getId();
        }
        return '';
    }

    /**
     * @return string
     */
    public function getLastBuildTime()
    {
        $dateFormat = 'Y-m-d\\TH:i:sO';
        if ($buildInfo = $this->getFinishedBuildInfo()) {
            return ($buildInfo->getFinished()) ? $buildInfo->getFinished()->format($dateFormat) : '';
        }
        return '';
    }

    /**
     * @param Build $build
     * @return string
     */
    public function getBuildStatus(Build $build)
    {
        switch ($build->getStatus()) {
            case Build::STATUS_SUCCESS:
                return 'Success';
            case Build::STATUS_FAILED:
                return 'Failure';
        }
        return 'Unknown';
    }

    /**
     * @return string
     */
    public function getLastBuildStatus()
    {
        if ($build = $this->getFinishedBuildInfo()) {
            return $this->getBuildStatus($build);
        }
        return '';
    }

    /**
     * @return string
     */
    public function getBuildUrl()
    {
         return $this->url . 'build/view/' . $this->build->getId();
    }

    /**
     * @return array
     */
    public function toArray()
    {
        if (!$this->build) {
            return array();
        }
        return array(
            'name' => $this->getName(),
            'activity' => $this->getActivity(),
            'lastBuildLabel' => $this->getLastBuildLabel(),
            'lastBuildStatus' => $this->getLastBuildStatus(),
            'lastBuildTime' => $this->getLastBuildTime(),
            'webUrl' => $this->getBuildUrl(),
        );
    }
}

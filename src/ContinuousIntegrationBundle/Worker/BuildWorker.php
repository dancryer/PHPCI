<?php

namespace Kiboko\Bundle\ContinuousIntegrationBundle\Worker;

use Kiboko\Bundle\ContinuousIntegrationBundle\Entity\Repository\BuildRepositoryInterface;
use Pheanstalk\Job;
use Pheanstalk\PheanstalkInterface;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;

class BuildWorker implements WorkerInterface, LoggerAwareInterface
{
    /**
     * @var bool
     */
    private $shouldStop;

    /**
     * @var int
     */
    private $maximumJobs;

    /**
     * @var int
     */
    private $totalJobs;

    /**
     * @var PheanstalkInterface
     */
    private $pheanstalk;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var string
     */
    protected $queue;

    /**
     * @var BuildRepositoryInterface
     */
    private $buildRepository;

    /**
     * BuildWorker constructor.
     *
     * @param string $queue
     * @param PheanstalkInterface $pheanstalk
     * @param LoggerInterface $logger
     * @param BuildRepositoryInterface $buildRepository
     */
    public function __construct(
        string $queue,
        PheanstalkInterface $pheanstalk,
        LoggerInterface $logger,
        BuildRepositoryInterface $buildRepository
    ) {
        $this->maximumJobs = -1;
        $this->totalJobs = 0;
        $this->queue = $queue;
        $this->pheanstalk = $pheanstalk;
        $this->logger = $logger;
        $this->buildRepository = $buildRepository;
    }

    public function run(): void
    {
        $this->pheanstalk->watch($this->queue);
        $this->pheanstalk->ignore('default');

        while ($this->shouldStop !== true) {
            /** @var Job $job */
            $job = $this->pheanstalk->reserve();
            $jobData = json_decode($job->getData(), true);

            if (!$this->validateJob($job, $jobData)) {
                continue;
            }

            $this->pheanstalk->delete($job);
        }
    }

    public function stop(): void
    {
        $this->shouldStop = true;
    }

    /**
     * Checks that the job received is actually from Kiboko CI, and has a valid type.
     * @param Job $job
     * @param $jobData
     * @return bool
     */
    protected function validateJob(Job $job, array $jobData)
    {
        if (empty($jobData)) {
            $this->pheanstalk->delete($job);
            return false;
        }

        if (!isset($jobData['type']) || $jobData['type'] !== 'phpci.build') {
            $this->pheanstalk->delete($job);
            return false;
        }

        return true;
    }

    /**
     * @return LoggerInterface
     */
    public function getLogger(): LoggerInterface
    {
        return $this->logger;
    }

    /**
     * @param LoggerInterface $logger
     *
     * @return WorkerInterface
     */
    public function setLogger(LoggerInterface $logger): WorkerInterface
    {
        $this->logger = $logger;

        return $this;
    }
}

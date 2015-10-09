<?php

namespace PHPCI\Worker;

use b8\Config;
use b8\Database;
use b8\Store\Factory;
use Monolog\Logger;
use Pheanstalk\Job;
use Pheanstalk\Pheanstalk;
use PHPCI\Builder;
use PHPCI\BuildFactory;
use PHPCI\Logging\BuildDBLogHandler;
use PHPCI\Model\Build;

/**
 * Class BuildWorker
 * @package PHPCI\Worker
 */
class BuildWorker
{
    /**
     * If this variable changes to false, the worker will stop after the current build.
     * @var bool
     */
    protected $run = true;

    /**
     * The maximum number of jobs this worker should run before exiting.
     * Use -1 for no limit.
     * @var int
     */
    protected $maxJobs = -1;

    /**
     * The logger for builds to use.
     * @var \Monolog\Logger
     */
    protected $logger;

    /**
     * beanstalkd host
     * @var string
     */
    protected $host;

    /**
     * beanstalkd queue to watch
     * @var string
     */
    protected $queue;

    /**
     * @var \Pheanstalk\Pheanstalk
     */
    protected $pheanstalk;

    /**
     * @var int
     */
    protected $totalJobs = 0;

    /**
     * @param $host
     * @param $queue
     */
    public function __construct($host, $queue)
    {
        $this->host = $host;
        $this->queue = $queue;
        $this->pheanstalk = new Pheanstalk($this->host);
    }

    /**
     * @param int $maxJobs
     */
    public function setMaxJobs($maxJobs = -1)
    {
        $this->maxJobs = $maxJobs;
    }

    /**
     * @param Logger $logger
     */
    public function setLogger(Logger $logger)
    {
        $this->logger = $logger;
    }

    /**
     * Start the worker.
     */
    public function startWorker()
    {
        $this->pheanstalk->watch($this->queue);
        $this->pheanstalk->ignore('default');
        $buildStore = Factory::getStore('Build');

        while ($this->run) {
            // Get a job from the queue:
            $job = $this->pheanstalk->reserve();

            $this->checkJobLimit();

            // Get the job data and run the job:
            $jobData = json_decode($job->getData(), true);

            if (!$this->verifyJob($job, $jobData)) {
                continue;
            }

            $this->logger->addInfo('Received build #'.$jobData['build_id'].' from Beanstalkd');

            // If the job comes with config data, reset our config and database connections
            // and then make sure we kill the worker afterwards:
            if (!empty($jobData['config'])) {
                $this->logger->addDebug('Using job-specific config.');
                $currentConfig = Config::getInstance()->getArray();
                $config = new Config($jobData['config']);
                Database::reset($config);
            }

            try {
                $build = BuildFactory::getBuildById($jobData['build_id']);
            } catch (\Exception $ex) {
                $this->logger->addWarning('Build #' . $jobData['build_id'] . ' does not exist in the database.');
                $this->pheanstalk->delete($job);
            }

            try {
                // Logging relevant to this build should be stored
                // against the build itself.
                $buildDbLog = new BuildDBLogHandler($build, Logger::INFO);
                $this->logger->pushHandler($buildDbLog);

                $builder = new Builder($build, $this->logger);
                $builder->execute();

                // After execution we no longer want to record the information
                // back to this specific build so the handler should be removed.
                $this->logger->popHandler($buildDbLog);
            } catch (\PDOException $ex) {
                // If we've caught a PDO Exception, it is probably not the fault of the build, but of a failed
                // connection or similar. Release the job and kill the worker.
                $this->run = false;
                $this->pheanstalk->release($job);
            } catch (\Exception $ex) {
                $build->setStatus(Build::STATUS_FAILED);
                $build->setFinished(new \DateTime());
                $build->setLog($build->getLog() . PHP_EOL . PHP_EOL . $ex->getMessage());
                $buildStore->save($build);
                $build->sendStatusPostback();
            }

            // Reset the config back to how it was prior to running this job:
            if (!empty($currentConfig)) {
                $config = new Config($currentConfig);
                Database::reset($config);
            }

            // Delete the job when we're done:
            $this->pheanstalk->delete($job);
        }
    }

    /**
     * Stops the worker after the current build.
     */
    public function stopWorker()
    {
        $this->run = false;
    }

    /**
     * Checks if this worker has done the amount of jobs it is allowed to do, and if so tells it to stop
     * after this job completes.
     */
    protected function checkJobLimit()
    {
        // Make sure we don't run more than maxJobs jobs on this worker:
        $this->totalJobs++;

        if ($this->maxJobs != -1 && $this->maxJobs <= $this->totalJobs) {
            $this->stopWorker();
        }
    }

    /**
     * Checks that the job received is actually from PHPCI, and has a valid type.
     * @param Job $job
     * @param $jobData
     * @return bool
     */
    protected function verifyJob(Job $job, $jobData)
    {
        if (empty($jobData) || !is_array($jobData)) {
            // Probably not from PHPCI.
            $this->pheanstalk->delete($job);
            return false;
        }

        if (!array_key_exists('type', $jobData) || $jobData['type'] !== 'phpci.build') {
            // Probably not from PHPCI.
            $this->pheanstalk->delete($job);
            return false;
        }

        return true;
    }
}

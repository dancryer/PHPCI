<?php

namespace PHPCI\Worker;

use b8\Config;
use b8\Database;
use b8\Store\Factory;
use Monolog\Logger;
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
     * @param $host
     * @param $queue
     */
    public function __construct($host, $queue)
    {
        $this->host = $host;
        $this->queue = $queue;
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
        $pheanstalk = new Pheanstalk($this->host);
        $pheanstalk->watch($this->queue);
        $pheanstalk->ignore('default');
        $buildStore = Factory::getStore('Build');

        $jobs = 0;

        while ($this->run) {
            // Get a job from the queue:
            $job = $pheanstalk->reserve();

            // Make sure we don't run more than maxJobs jobs on this worker:
            $jobs++;

            if ($this->maxJobs != -1 && $this->maxJobs <= $jobs) {
                $this->run = false;
            }

            // Get the job data and run the job:
            $jobData = json_decode($job->getData(), true);

            if (empty($jobData) || !is_array($jobData)) {
                // Probably not from PHPCI.
                $pheanstalk->release($job);
                continue;
            }

            if (!array_key_exists('type', $jobData) || $jobData['type'] !== 'phpci.build') {
                // Probably not from PHPCI.
                $pheanstalk->release($job);
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

            $build = BuildFactory::getBuildById($jobData['build_id']);

            if (empty($build)) {
                $this->logger->addWarning('Build #' . $jobData['build_id'] . ' does not exist in the database.');
                $pheanstalk->delete($job);
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
                $pheanstalk->release($job);
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
            $pheanstalk->delete($job);
        }
    }

    /**
     * Stops the worker after the current build.
     */
    public function stopWorker()
    {
        $this->run = false;
    }
}

<?php
namespace Tests\PHPCI\ProcessControl;

/**
 * Some helpers to
 */
abstract class ProcessControlTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var type
     */
    protected $process;

    /**
     * @var array
     */
    protected $pipes;

    /**
     * @var \PHPCI\ProcessControl\ProcessControlInterface
     */
    protected $object;

    /** Starts a process.
     *
     * @return int The PID of the process.
     */
    protected function startProcess()
    {
        $desc = array(array("pipe", "r"), array("pipe", "w"), array("pipe", "w"));
        $this->pipes = array();

        $this->process = proc_open($this->getTestCommand(), $desc, $this->pipes);
        usleep(500);

        $this->assertTrue(is_resource($this->process));
        $this->assertTrue($this->isRunning());

        $status = proc_get_status($this->process);
        return $status['pid'];
    }

    /** End the running process.
     *
     * @return int
     */
    protected function endProcess()
    {
        if (!is_resource($this->process)) {
            return;
        }
        array_map('fclose', $this->pipes);
        $exitCode = proc_close($this->process);
        $this->assertFalse($this->isRunning());
        $this->process = null;
        return $exitCode;
    }

    /**
     * @return bool
     */
    protected function isRunning()
    {
        if (!is_resource($this->process)) {
            return false;
        }
        $status = proc_get_status($this->process);
        return $status['running'];
    }

    public function testIsRunning()
    {
        if (!$this->object->isAvailable()) {
            $this->markTestSkipped();
        }

        $pid = $this->startProcess();

        $this->assertTrue($this->object->isRunning($pid));

        fwrite($this->pipes[0], PHP_EOL);

        $exitCode = $this->endProcess();

        $this->assertEquals(0, $exitCode);
        $this->assertFalse($this->object->isRunning($pid));
    }

    public function testSoftKill()
    {
        if (!$this->object->isAvailable()) {
            $this->markTestSkipped();
        }

        $pid = $this->startProcess();

        $this->object->kill($pid);
        usleep(500);

        $this->assertFalse($this->isRunning());
    }

    public function testForcefullyKill()
    {
        if (!$this->object->isAvailable()) {
            $this->markTestSkipped();
        }

        $pid = $this->startProcess();

        $this->object->kill($pid, true);
        usleep(500);

        $this->assertFalse($this->isRunning());
    }

    abstract public function testIsAvailable();

    abstract public function getTestCommand();

    protected function tearDown()
    {
        parent::tearDown();
        $this->endProcess();
    }
}

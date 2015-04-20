<?php
namespace PHPCI\Plugin\Tests;

use PHPCI\Plugin\Stash as StashPlugin;

class StashTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject $mockCiBuilder
     */
    protected $mockCiBuilder;
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject $mockBuild
     */
    protected $mockBuild;
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject $mockProject
     */
    protected $mockProject;

    public function setUp()
    {
        $this->mockProject = $this->getMock(
            '\PHPCI\Model\Project',
            array('getTitle'),
            array(),
            "mockProject",
            false
        );
        $this->mockProject->expects($this->any())
            ->method('getTitle')
            ->will($this->returnValue("Test-Project"));
        $this->mockBuild = $this->getMock(
            '\PHPCI\Model\Build',
            array('getLog', 'getStatus', 'getProject', 'isSuccessful'),
            array(),
            "mockBuild",
            false
        );
        $this->mockBuild->expects($this->any())
            ->method('getProject')
            ->will($this->returnValue($this->mockProject));
        $this->mockCiBuilder = $this->getMock(
            '\PHPCI\Builder',
            array(
                'getSystemConfig',
                'getBuild',
                'log'
            ),
            array(),
            "mockBuilder",
            false
        );
    }

    protected function getPlugin(array $options = array())
    {
        return new StashPlugin($this->mockCiBuilder, $this->mockBuild, $options);
    }

    /**
     * @covers PHPUnit::execute
     */
    public function testPluginNoOptions()
    {
        $plugin = $this->getPlugin();
        $this->assertInstanceOf('PHPCI\Plugin', $plugin);
        $returnValue = $plugin->execute();
        $expectedReturn = false;
        $this->assertEquals($expectedReturn, $returnValue);
    }

    /**
     * @covers PHPUnit::execute
     */
    public function testPluginNoPhpciHost()
    {
        $options = array('stash_hostname' => 'stash',
                         'stash_username' => 'username',
                         'stash_password' => 'password'
                         );
        $plugin = $this->getPlugin($options);
        $this->assertInstanceOf('PHPCI\Plugin', $plugin);
        $returnValue = $plugin->execute();
        $expectedReturn = false;
        $this->assertEquals($expectedReturn, $returnValue);
    }

    /**
     * @covers PHPUnit::execute
     */
    public function testPluginNoStashHost()
    {
        $options = array('phpci_hostname' => 'phpci',
                         'stash_username' => 'username',
                         'stash_password' => 'password'
                         );
        $plugin = $this->getPlugin($options);
        $this->assertInstanceOf('PHPCI\Plugin', $plugin);
        $returnValue = $plugin->execute();
        $expectedReturn = false;
        $this->assertEquals($expectedReturn, $returnValue);
    }

    /**
     * @covers PHPUnit::execute
     */
    public function testPluginNoStashUsername()
    {
        $options = array('phpci_hostname' => 'phpci',
                         'stash_hostname' => 'stash',
                         'stash_password' => 'password'
                         );
        $plugin = $this->getPlugin($options);
        $this->assertInstanceOf('PHPCI\Plugin', $plugin);
        $returnValue = $plugin->execute();
        $expectedReturn = false;
        $this->assertEquals($expectedReturn, $returnValue);
    }

    /**
     * @covers PHPUnit::execute
     */
    public function testPluginNoStashPassword()
    {
        $options = array('phpci_hostname' => 'phpci',
                         'stash_hostname' => 'stash',
                         'stash_username' => 'username'
                         );
        $plugin = $this->getPlugin($options);
        $this->assertInstanceOf('PHPCI\Plugin', $plugin);
        $returnValue = $plugin->execute();
        $expectedReturn = false;
        $this->assertEquals($expectedReturn, $returnValue);
    }

    /**
     * @covers PHPUnit::execute
     */
    public function testPlugin()
    {
        $options = array('phpci_hostname' => 'phpci',
                         'stash_hostname' => 'stash',
                         'stash_username' => 'username',
                         'stash_password' => 'password'
                         );
        $plugin = $this->getPlugin($options);
        $this->assertInstanceOf('PHPCI\Plugin', $plugin);
        $returnValue = $plugin->execute();
        $expectedReturn = true;
        $this->assertEquals($expectedReturn, $returnValue);
    }
}

<?php
namespace PHPCI\Plugin\Tests;

use PHPCI\Plugin\Stash as StashPlugin;
use Prophecy\PhpUnit\ProphecyTestCase;

class StashTest extends ProphecyTestCase
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
        $this->mockProject = $this->prophesize('\PHPCI\Model\Project');
        $this->mockBuild = $this->prophesize('\PHPCI\Model\Build');
        $this->mockCiBuilder = $this->prophesize('\PHPCI\Builder');
        $this->mockBuild->getProject()->willReturn($this->mockProject);
        $this->mockProject->getTitle()->willReturn('Test Project');
    }

    protected function getPlugin(array $options = array())
    {
        return new StashPlugin($this->mockCiBuilder->reveal(), $this->mockBuild->reveal(), $options);
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
    public function testPluginTokenFailed()
    {
        $this->mockBuild->isSuccessful()->willReturn('Failed');
        $this->mockBuild->getId()->willReturn(1);
        $this->mockBuild->getCommitId()->willReturn('000001');

        $options = array('phpci_hostname' => 'phpci',
                         'stash_hostname' => 'stash',
                         'stash_username' => 'username',
                         'stash_auth_token'    => 'token'
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
    public function testPluginTokenSuccessful()
    {
        $this->markTestIncomplete('Still to mock curl requests...???');
        $this->mockBuild->isSuccessful()->willReturn('Successful');
        $this->mockBuild->getId()->willReturn(2);
        $this->mockBuild->getCommitId()->willReturn('000002');

        $options = array('phpci_hostname' => 'phpci',
                         'stash_hostname' => 'stash',
                         'stash_username' => 'username',
                         'stash_auth_token'    => 'token'
                         );
        $plugin = $this->getPlugin($options);
        $this->assertInstanceOf('PHPCI\Plugin', $plugin);
        $returnValue = $plugin->execute();
        $expectedReturn = true;
        $this->assertEquals($expectedReturn, $returnValue);
    }
}

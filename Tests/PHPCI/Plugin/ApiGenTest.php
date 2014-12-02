<?php
namespace PHPCI\Plugin\Tests;

use PHPCI\Plugin\ApiGen as ApiGenPlugin;

class ApiGenTest extends \PHPUnit_Framework_TestCase
{
    protected function getPlugin(array $options = array())
    {
        $build = $this
            ->getMockBuilder('PHPCI\Model\Build')
            ->disableOriginalConstructor()
            ->getMock();

        $phpci = $this
            ->getMockBUilder('PHPCI\Builder')
            ->disableOriginalConstructor()
            ->getMock();

        return new ApiGenPlugin($phpci, $build, $options);
    }

    public function testPlugin()
    {
        $plugin = $this->getPlugin();
        $this->assertInstanceOf('PHPCI\Plugin', $plugin);
        $this->assertInstanceOf('PHPCI\Model\Build', $plugin->getBuild());
        $this->assertInstanceOf('PHPCI\Builder', $plugin->getPHPCI());
    }

    public function testExecute()
    {
        $plugin = $this->getPlugin();
        $this->assertTrue($plugin->execute());
    }
}

<?php
namespace PHPCI\Plugin\Tests;

use PHPCI\Plugin\PhpUnit as PhpUnitPlugin;
use RuntimeException;

class PhpUnitTest extends \PHPUnit_Framework_TestCase
{
    protected function getPlugin(array $options = array())
    {
        $build = $this
            ->getMockBuilder('PHPCI\Model\Build')
            ->disableOriginalConstructor()
            ->getMock();

        $phpci = $this
            ->getMockBuilder('PHPCI\Builder')
            ->disableOriginalConstructor()
            ->getMock();

        return new PhpUnitPlugin($phpci, $build, $options);
    }

    public function testPlugin()
    {
        $plugin = $this->getPlugin(['coverage' => 'directory']);
        $this->assertInstanceOf('PHPCI\Plugin', $plugin);
        $this->assertInstanceOf('PHPCI\Model\Build', $plugin->getBuild());
        $this->assertInstanceOf('PHPCI\Builder', $plugin->getPHPCI());
    }
}

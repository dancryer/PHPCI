<?php
namespace PHPCI\Plugin\Tests;

use PHPCI\Plugin\Phar as PharPlugin;

class PharTest extends \PHPUnit_Framework_TestCase
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

        return new PharPlugin($phpci, $build, $options);
    }

    public function testPlugin()
    {
        $plugin = $this->getPlugin();
        $this->assertInstanceOf('PHPCI\Plugin', $plugin);
        $this->assertInstanceOf('PHPCI\Model\Build', $plugin->getBuild());
        $this->assertInstanceOf('PHPCI\Builder', $plugin->getPHPCI());
    }

    public function testDirectory()
    {
        $plugin = $this->getPlugin();
        $plugin->getPHPCI()->buildPath = 'foo';
        $this->assertEquals('foo', $plugin->getDirectory());

        $plugin = $this->getPlugin(array('directory' => 'dirname'));
        $this->assertEquals('dirname', $plugin->getDirectory());
    }

    public function testFilename()
    {
        $plugin = $this->getPlugin();
        $this->assertEquals('build.phar', $plugin->getFilename());

        $plugin = $this->getPlugin(array('filename' => 'another.phar'));
        $this->assertEquals('another.phar', $plugin->getFilename());
    }
}

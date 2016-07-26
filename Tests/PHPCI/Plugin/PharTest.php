<?php

/**
 * PHPCI - Continuous Integration for PHP
 *
 * @copyright    Copyright 2015, Block 8 Limited.
 * @license      https://github.com/Block8/PHPCI/blob/master/LICENSE.md
 *
 * @link         https://www.phptesting.org/
 */
namespace Tests\PHPCI\Plugin;

use Phar as PHPPhar;
use PHPCI\Plugin\Phar as PharPlugin;

class PharTest extends \PHPUnit_Framework_TestCase
{
    protected $directory;

    protected function tearDown()
    {
        $this->cleanSource();
    }

    protected function getPlugin(array $options = [])
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

    protected function buildTemp()
    {
        $directory = tempnam(APPLICATION_PATH . '/Tests/temp', 'source');
        unlink($directory);

        return $directory;
    }

    protected function buildSource()
    {
        $directory = $this->buildTemp();
        mkdir($directory);
        file_put_contents($directory . '/one.php', '<?php echo "one";');
        file_put_contents($directory . '/two.php', '<?php echo "two";');
        mkdir($directory . '/config');
        file_put_contents($directory . '/config/config.ini', '[config]');
        mkdir($directory . '/views');
        file_put_contents($directory . '/views/index.phtml', '<?php echo "hello";');
        $this->directory = $directory;

        return $directory;
    }

    protected function cleanSource()
    {
        if ($this->directory) {
            $filenames = [
                '/build.phar',
                '/stub.php',
                '/views/index.phtml',
                '/views',
                '/config/config.ini',
                '/config',
                '/two.php',
                '/one.php',
            ];
            foreach ($filenames as $filename) {
                if (is_dir($this->directory . $filename)) {
                    rmdir($this->directory . $filename);
                } elseif (is_file($this->directory . $filename)) {
                    unlink($this->directory . $filename);
                }
            }
            rmdir($this->directory);
            $this->directory = null;
        }
    }

    protected function checkReadonly()
    {
        if (ini_get('phar.readonly')) {
            $this->markTestSkipped('phar writing disabled in php.ini.');
        }
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
        $plugin                        = $this->getPlugin();
        $plugin->getPHPCI()->buildPath = 'foo';
        $this->assertSame('foo', $plugin->getDirectory());

        $plugin = $this->getPlugin(['directory' => 'dirname']);
        $this->assertSame('dirname', $plugin->getDirectory());
    }

    public function testFilename()
    {
        $plugin = $this->getPlugin();
        $this->assertSame('build.phar', $plugin->getFilename());

        $plugin = $this->getPlugin(['filename' => 'another.phar']);
        $this->assertSame('another.phar', $plugin->getFilename());
    }

    public function testRegExp()
    {
        $plugin = $this->getPlugin();
        $this->assertSame('/\.php$/', $plugin->getRegExp());

        $plugin = $this->getPlugin(['regexp' => '/\.(php|phtml)$/']);
        $this->assertSame('/\.(php|phtml)$/', $plugin->getRegExp());
    }

    public function testStub()
    {
        $plugin = $this->getPlugin();
        $this->assertNull($plugin->getStub());

        $plugin = $this->getPlugin(['stub' => 'stub.php']);
        $this->assertSame('stub.php', $plugin->getStub());
    }

    public function testExecute()
    {
        $this->checkReadonly();

        $plugin                        = $this->getPlugin();
        $path                          = $this->buildSource();
        $plugin->getPHPCI()->buildPath = $path;

        $this->assertTrue($plugin->execute());

        $this->assertFileExists($path . '/build.phar');
        PHPPhar::loadPhar($path . '/build.phar');
        $this->assertFileEquals($path . '/one.php', 'phar://build.phar/one.php');
        $this->assertFileEquals($path . '/two.php', 'phar://build.phar/two.php');
        $this->assertFileNotExists('phar://build.phar/config/config.ini');
        $this->assertFileNotExists('phar://build.phar/views/index.phtml');
    }

    public function testExecuteRegExp()
    {
        $this->checkReadonly();

        $plugin                        = $this->getPlugin(['regexp' => '/\.(php|phtml)$/']);
        $path                          = $this->buildSource();
        $plugin->getPHPCI()->buildPath = $path;

        $this->assertTrue($plugin->execute());

        $this->assertFileExists($path . '/build.phar');
        PHPPhar::loadPhar($path . '/build.phar');
        $this->assertFileEquals($path . '/one.php', 'phar://build.phar/one.php');
        $this->assertFileEquals($path . '/two.php', 'phar://build.phar/two.php');
        $this->assertFileNotExists('phar://build.phar/config/config.ini');
        $this->assertFileEquals($path . '/views/index.phtml', 'phar://build.phar/views/index.phtml');
    }

    public function testExecuteStub()
    {
        $this->checkReadonly();

        $content = <<<STUB
<?php
Phar::mapPhar();
__HALT_COMPILER(); ?>
STUB;

        $path = $this->buildSource();
        file_put_contents($path . '/stub.php', $content);

        $plugin                        = $this->getPlugin(['stub' => 'stub.php']);
        $plugin->getPHPCI()->buildPath = $path;

        $this->assertTrue($plugin->execute());

        $this->assertFileExists($path . '/build.phar');
        $phar = new PHPPhar($path . '/build.phar');
        $this->assertSame($content, trim($phar->getStub())); // + trim because PHP adds newline char
    }

    public function testExecuteUnknownDirectory()
    {
        $this->checkReadonly();

        $directory = $this->buildTemp();

        $plugin                        = $this->getPlugin(['directory' => $directory]);
        $plugin->getPHPCI()->buildPath = $this->buildSource();

        $this->assertFalse($plugin->execute());
    }
}

<?php
namespace PHPCI\Plugin\Tests;

use PHPCI\Plugin\ApiGen as ApiGenPlugin;

class ApiGenTest extends \PHPUnit_Framework_TestCase
{
    protected $directory;

    protected function tearDown()
    {
        $this->clearSource();
    }

    protected function getPlugin(array $options = array())
    {
        $build = $this
            ->getMockBuilder('PHPCI\Model\Build')
            ->disableOriginalConstructor()
            ->getMock();

        $phpci = $this
            ->getMockBuilder('PHPCI\Builder')
            ->setMethods(null)
            ->disableOriginalConstructor()
            ->getMock();

        $directory = $this->buildSource();

        $phpci->buildPath = $directory . '/container';

        $executor = $this
            ->getMockBuilder('PHPCI\Helper\UnixCommandExecutor')
            ->setMethods(null)
            ->disableOriginalConstructor()
            ->getMock();
        $property = new \ReflectionProperty('PHPCI\Builder', 'commandExecutor');
        $property->setAccessible(true);
        $property->setValue($phpci, $executor);

        $logger = $this
            ->getMockBuilder('PHPCI\Logging\BuildLogger')
            ->disableOriginalConstructor()
            ->getMock();
        $property = new \ReflectionProperty('PHPCI\Builder', 'buildLogger');
        $property->setAccessible(true);
        $property->setValue($phpci, $logger);
        $property = new \ReflectionProperty('PHPCI\Helper\BaseCommandExecutor', 'logger');
        $property->setAccessible(true);
        $property->setValue($executor, $logger);
        $property = new \ReflectionProperty('PHPCI\Helper\BaseCommandExecutor', 'rootDir');
        $property->setAccessible(true);
        $property->setValue($executor, APPLICATION_PATH);

        return new ApiGenPlugin($phpci, $build, $options);
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
        mkdir($directory . '/container');
        file_put_contents($directory . '/container/apigen.neon', "source:\n    - src\n\ndestination: api\n");
        mkdir($directory . '/container/src');
        file_put_contents($directory . '/container/src/SomeClass.php', "<?php\nclass SomeClass\n{\n}");
        $this->directory = $directory;
        return $this->directory;
    }

    protected function clearSource()
    {
        if ($this->directory) {
            $cleaner = function ($cleaner, $directory) {
                $resource = opendir($directory);
                while (($filename = readdir($resource)) !== false) {
                    if ($filename != '.' && $filename != '..') {
                        $filename = $directory . DIRECTORY_SEPARATOR . $filename;
                        if (is_dir($filename)) {
                            $cleaner($cleaner, $filename);
                        } else {
                            unlink($filename);
                        }
                    }
                }
                closedir($resource);
                rmdir($directory);
            };
            $cleaner($cleaner, $this->directory);
        }
    }

    public function testPlugin()
    {
        $plugin = $this->getPlugin();
        $this->assertInstanceOf('PHPCI\Plugin', $plugin);
        $this->assertInstanceOf('PHPCI\Model\Build', $plugin->getBuild());
        $this->assertInstanceOf('PHPCI\Builder', $plugin->getPHPCI());
    }

    public function testSuccess()
    {
        $plugin = $this->getPlugin();

        $this->assertTrue($plugin->execute());
        $this->assertFileExists($plugin->getPHPCI()->buildPath . '/api/index.html');
    }

    public function testUnknownConfigurationFile()
    {
        $plugin = $this->getPlugin();
        // Remove Configuration File
        unlink($plugin->getPHPCI()->buildPath . '/apigen.neon');

        $this->assertFalse($plugin->execute());
    }
}

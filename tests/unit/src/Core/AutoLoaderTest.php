<?php

namespace Unit\Core;

use BlackFramework\Core\Mock\MockClass;
use \Throwable;
use BlackFramework\Core\AutoLoader;
use BlackFramework\Core\Exception\AutoLoaderException;
use PHPUnit\Framework\TestCase;

class AutoLoaderTest extends TestCase
{
    /**
     * @var AutoLoader
     */
    private $service;

    protected function setUp(): void
    {
        $this->service = new AutoLoader(false);
    }

    /**
     * Test registering namespace
     * @return AutoLoader
     * @throws AutoLoaderException
     */
    public function testRegisterNamespace(): AutoLoader
    {
        $result = $this->service->registerNamespace(
            "BlackFramework\\Core\\",
            [
                APPLICATION . "/src"
            ]
        );

        $this->assertEquals(
            true,
            $result
        );

        return $this->service;
    }

    /**
     * Test loading class
     * @param $service AutoLoader
     * @throws AutoLoaderException
     * @throws Throwable
     * @depends testRegisterNamespace
     * @return AutoLoader
     */
    public function testLoadPSR4(AutoLoader $service): AutoLoader
    {
        $this->service->registerNamespace(
            "BlackFramework\\Core\\",
            [
                APPLICATION_SRC
            ]
        );

        $result = new MockClass();

        $this->assertEquals(
            MockClass::class,
            get_class($result)
        );

        return $service;
    }

    /**
     * Test throwing Exception
     * @throws AutoLoaderException
     * @throws Throwable
     */
    public function testLoadPSR4Exception(): void
    {
        $this->service->setException(true);

        $this->expectException(
            AutoLoaderException::class
        );

        $this->service->loadPSR4(
            "Class not exists"
        );
    }

    public function testLoadFile()
    {
        $this->service->setException(false);
        $namespace = 'BlackFramework\\\\Core\\\\';
        $path = [
            APPLICATION_SRC
        ];
        $class = 'BlackFramework\Core\Mock\MockClass';

        $file = $this->service->findFile(
            $namespace,
            $path,
            $class
        );

        $this->assertEquals(
            dirname(dirname(dirname(__DIR__))) . DIRECTORY_SEPARATOR .'..' . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . 'Mock' . DIRECTORY_SEPARATOR . 'MockClass.php',
            $file
        );
    }

    public function testSetException()
    {
        $this->service->setException(false);

        $this->assertEquals(
            false,
            $this->service->getException()
        );
    }
}

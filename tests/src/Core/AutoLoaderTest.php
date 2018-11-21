<?php

namespace Tests\Core;

use Application\Core\AutoLoader;
use Application\Core\Exception\AutoLoaderException;
use PHPUnit\Framework\TestCase;

class AutoLoaderTest extends TestCase
{
    /**
     * @var AutoLoader
     */
    private $service;

    protected function setUp(): void
    {
        $this->service = new AutoLoader();
    }

    /**
     * Test registering namespace
     * @throws AutoLoaderException
     * @return AutoLoader
     */
    public function testRegisterNamespace(): AutoLoader
    {
        $result = $this->service->registerNamespace(
            "Application",
            "src"
        );

        $this->assertEquals(
            true,
            $result
        );

        return $this->service;
    }

    /**
     * @param $service AutoLoader
     * @return AutoLoader
     * @throws AutoLoaderException
     * @depends testRegisterNamespace
     */
    public function testRegisterNamespaceException(AutoLoader $service)
    {
        $this->expectException(
            AutoLoaderException::class
        );

        $service->registerNamespace(
            "Application",
            "src"
        );
        return $service;
    }

    /**
     * Test loading class
     * @param $service AutoLoader
     * @throws AutoLoaderException
     * @depends testRegisterNamespace
     */
    public function testLoadPSR4(AutoLoader $service): void
    {
        $result = $service->loadPSR4(
            AutoLoader::class
        );

        $this->assertEquals(
            true,
            $result
        );
    }

    /**
     * Test throwing exception
     * @param $service AutoLoader
     * @throws AutoLoaderException
     */
    public function testLoadPSR4Exception(): void
    {
        $this->expectException(
            AutoLoaderException::class
        );

        $this->service->loadPSR4(
            "Class not exists"
        );
    }
}

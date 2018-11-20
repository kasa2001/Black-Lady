<?php

namespace Tests\Core;

use Application\Core\AutoLoader;
use Application\Core\Exception\AutoLoaderException;
use PHPUnit\Framework\TestCase;

class AutoLoaderTest extends TestCase
{
    private $service;

    protected function setUp(): void
    {
        $this->service = new AutoLoader();
    }

    /**
     * Test registering namespace
     */
    public function testRegisterNamespace(): void
    {
        $result = $this->service->registerNamespace(
            "Application",
            "src"
        );

        $this->assertEquals(
            true,
            $result
        );
    }

    /**
     * Test loading class
     * @depends testRegisterNamespace
     */
    public function testLoadPSR4(): void
    {
        $result = $this->service->loadPSR4(
            AutoLoader::class
        );

        $this->assertEquals(
            true,
            $result
        );
    }

    /**
     * Test throwing exception
     * @depends testRegisterNamespace
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

    /**
     * @depends testRegisterNamespace
     */
    public function testRegisterNamespaceException(): void
    {
        $this->expectException(
            AutoLoaderException::class
        );

        $this->service->registerNamespace(
            "Application",
            "src"
        );
    }
}

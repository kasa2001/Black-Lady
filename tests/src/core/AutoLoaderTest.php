<?php

namespace Tests\Core;

use PHPUnit\Framework\TestCase;

use Application\Core\AutoLoader;
use Application\Core\Exception\AutoLoaderException;

class AutoLoaderTest extends TestCase
{
    private $service;

    protected function setUp()
    {
        $this->service = new AutoLoader();
    }

    public function testLoadPSR4()
    {
        $class = $this->service->loadPSR4(
            AutoLoader::class
        );

        $this->assertAttributeInstanceOf(
            AutoLoader::class,
            $class
        );
    }

    public function testAutoLoaderException()
    {
        $this->expectException(
            AutoLoaderException::class
        );

        $this->service->loadPSR4(
            "Class not exists"
        );
    }

    public function testRegisterNamespace()
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
}
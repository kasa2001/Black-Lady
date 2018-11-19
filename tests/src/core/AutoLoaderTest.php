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

    /**
     * Test loading class
     * @depends testRegisterNamespace
     */
    public function testLoadPSR4()
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
    public function testAutoLoaderException()
    {
        $this->expectException(
            AutoLoaderException::class
        );

        $this->service->loadPSR4(
            "Class not exists"
        );
    }

    /**
     * Test registering namespace
     */
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
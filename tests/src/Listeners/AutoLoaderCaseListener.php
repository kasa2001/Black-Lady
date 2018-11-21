<?php

namespace Tests\Listeners;

use Application\Core\AutoLoader;
use PHPUnit\Framework\TestListener;
use PHPUnit\Framework\TestListenerDefaultImplementation;
use PHPUnit\Framework\TestSuite;

class AutoLoaderCaseListener implements TestListener
{
    use TestListenerDefaultImplementation;

    public function startTestSuite(TestSuite $suite): void
    {
        require_once 'src/core/autoloader.php';
        if ($suite->getName() == "Tests\\Core\\AutoLoaderTest")
        {
            return;
        }

        $autoloader = new AutoLoader();
        $autoloader->registerNamespace('Application','src');
    }

}

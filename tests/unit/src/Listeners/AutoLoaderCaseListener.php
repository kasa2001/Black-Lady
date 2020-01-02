<?php

namespace Unit\Listeners;

use BlackFramework\Core\AutoLoader;
use BlackFramework\Core\Exception\AutoLoaderException;
use PHPUnit\Framework\TestListener;
use PHPUnit\Framework\TestListenerDefaultImplementation;
use PHPUnit\Framework\TestSuite;

class AutoLoaderCaseListener implements TestListener
{
    use TestListenerDefaultImplementation;

    public function startTestSuite(TestSuite $suite): void
    {
        require_once APPLICATION_SRC . '/AutoLoader.php';
        if ($suite->getName() == "Unit\\Core\\AutoLoaderTest")
        {
            return;
        }

        $autoloader = new AutoLoader(false);
        try {
            $autoloader->registerNamespace('BlackFramework\\Core',
                [
                    APPLICATION . '/src'
                ]
            );
        } catch (AutoLoaderException $e) {
            echo $e->getMessage();
            die;
        }
    }

}

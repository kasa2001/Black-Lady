<?php


namespace Application\Core;

require_once 'exception/autoloaderexception.php';

use Application\Core\Exception\AutoLoaderException;

class AutoLoader
{

    /**
     * @var array
     */
    private $namespace = [];

    public function __construct()
    {
        spl_autoload_register([$this, "loadPSR4"]);
    }

    /**
     * @param $namespace
     * @param $path
     * @throws AutoLoaderException if namespace is registered
     * @return bool
     */
    public function registerNamespace($namespace, $path): bool
    {
        if (isset($this->namespace[$namespace]))
        {
            throw new AutoLoaderException("Internal Server Error",500);
        }

        $this->namespace[$namespace] = $path;
        return true;
    }

    /**
     * @param $class
     * @return bool
     * @throws AutoLoaderException if namespace is not registered
     * @throws \Throwable if class not exists
     */
    public function loadPSR4($class): bool
    {
        $namespace = explode('\\', $class);

        if(isset($this->namespace[$namespace[0]]))
        {
            $namespace[0] = $this->namespace[$namespace[0]];
            spl_autoload(__DIR__ . '/../../' . implode('/', $namespace), 'php');
            return true;
        }

        throw new AutoLoaderException("Internal Server Error", 500);
    }
}
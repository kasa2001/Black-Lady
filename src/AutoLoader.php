<?php


namespace BlackFramework\Core;

use BlackFramework\Core\Exception\AutoLoaderException;

class AutoLoader
{

    /**
     * @var array
     */
    private $namespace = [];

    private $count = 0;

    /**
     * @var bool
     */
    private $exception;

    public function __construct($exception = false)
    {
        $this->exception = $exception;
        spl_autoload_register([$this, "loadPSR4"]);
    }

    /**
     * @param $namespace
     * @param $path
     * @return bool
     */
    public function registerNamespace($namespace, $path)
    {
        $this->namespace[$this->count] = [
            'namespace' => strtr($namespace, "\\", DIRECTORY_SEPARATOR),
            'path' => $path,
            'length' => strlen($namespace) - 1,
        ];
        $this->count++;
        return true;
    }

    /**
     * @param $class
     * @return bool
     * @throws AutoLoaderException if class not exists
     */
    public function loadPSR4($class): bool
    {
        $class = strtr($class, "\\", DIRECTORY_SEPARATOR);
        for($i = 0; $i < $this->count; $i++) {

            if (strrpos($class, $this->namespace[$i]['namespace']) === 0) {
                $class = substr($class, $this->namespace[$i]['length']) . ".php";

                foreach ($this->namespace[$i]['path'] as $path) {
                    $file = $path . $class;
                    if (is_file($file)) {
                        include $file;
                        return true;
                    }
                }
            }
        }

        if ($this->exception) {
            include 'Exception/AutoLoaderException.php';
            throw new AutoLoaderException("Internal Server Error", 500);
        }

        return false;
    }

    /**
     * @return bool
     */
    public function getException()
    {
        return $this->exception;
    }

    /**
     * @param bool $exception
     */
    public function setException($exception)
    {
        $this->exception = $exception;
    }
}

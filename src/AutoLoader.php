<?php


namespace BlackFramework\Core;

include 'Exception/AutoLoaderException.php';

use BlackFramework\Core\Exception\AutoLoaderException;

class AutoLoader
{

    /**
     * @var array
     */
    private $namespace = [];

    /**
     * @var bool
     */
    private $exception;

    public function __construct(bool $exception = false)
    {
        $this->exception = $exception;
        spl_autoload_extensions('php');
        spl_autoload_register([$this, "loadPSR4"]);
    }

    /**
     * @param $namespace
     * @param $path
     * @return bool
     */
    public function registerNamespace(string $namespace, array $path): bool
    {
        $this->namespace[$namespace] = $path;
        return true;
    }

    /**
     * @param $class
     * @return bool
     * @throws AutoLoaderException if class not exists
     */
    public function loadPSR4($class): bool
    {
        foreach ($this->namespace as $key => $value) {

            if (strpos($class, $key) !== false) {
                $this->findFile($value, substr($class, strlen($key)));
                break;
            }
        }

        if ($this->exception) {
            throw new AutoLoaderException("Internal Server Error", 500);
        }

        return false;
    }
    /**
     * @param array $value
     * @param string $class
     * @return string
     */
    public function findFile(array $value, string $class)
    {
        $class = str_replace("\\", DIRECTORY_SEPARATOR, $class);
        foreach ($value as $item) {
            $file = $item . $class;
            if (file_exists($file)) {
                include $file;
                return 0;
            }
        }

        return 1;
    }

    /**
     * @return bool
     */
    public function getException(): bool
    {
        return $this->exception;
    }

    /**
     * @param bool $exception
     */
    public function setException($exception): void
    {
        $this->exception = $exception;
    }
}

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
    public function registerNamespace($namespace, array $path): bool
    {
        $this->namespace[addslashes($namespace)] = $path;
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

            if (preg_match("/" . $key . "/", $class)) {

                if ($file = $this->findFile($key, $value, $class)) {
                    include $file;
                    return true;
                }

                break;
            }
        }

        if ($this->exception) {
            throw new AutoLoaderException("Internal Server Error", 500);
        }

        return false;
    }

    /**
     * @param string $path
     * @param array $value
     * @param string $class
     * @return string
     */
    public function findFile(string $path, array $value, string $class)
    {
        foreach ($value as $item) {
            $file = preg_replace(["/" . $path . "/", "/\\\\/"], [$item . DIRECTORY_SEPARATOR, DIRECTORY_SEPARATOR], $class) . ".php";

            if (file_exists($file)) {
                return $file;
            }
        }

        return null;
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

<?php


namespace BlackFramework\Core;

require_once 'Exception/AutoLoaderException.php';

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

    public function __construct(bool $exception)
    {
        $this->exception = $exception;
        spl_autoload_extensions('php');
        spl_autoload_register([$this, "loadPSR4"]);
    }

    /**
     * @param $namespace
     * @param $path
     * @return bool
     * @throws AutoLoaderException if namespace is registered
     */
    public function registerNamespace($namespace, array $path): bool
    {
        if (isset($this->namespace[$namespace])) {
            throw new AutoLoaderException("Internal Server Error", 500);
        }

        $this->namespace[$namespace] = $path;
        return true;
    }

    /**
     * @param $class
     * @return bool|void
     * @throws AutoLoaderException if class not exists
     */
    public function loadPSR4($class): bool
    {
        $match = [];

        foreach ($this->namespace as $key => $value) {

            $key = addslashes($key);

            preg_match("/" . $key . "/", $class, $match);

            if (!empty($match)) {

                $class = str_replace(
                    '\\',
                    DIRECTORY_SEPARATOR,
                    $class
                );

                $file = $this->findFile(
                    $key,
                    $value,
                    $class
                );

                if (!empty($file)) {
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

            $file = preg_replace("/" . $path . "/", $item . DIRECTORY_SEPARATOR, $class) . ".php";

            if (file_exists($file)) {
                return $file;
            }
        }

        return null;
    }

    /**
     * @return mixed
     */
    public function getException(): bool
    {
        return $this->exception;
    }

    /**
     * @param mixed $exception
     */
    public function setException($exception): void
    {
        $this->exception = $exception;
    }
}
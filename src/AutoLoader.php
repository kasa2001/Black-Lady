<?php


namespace BlackFramework\Core;

require_once 'Exception/AutoLoaderException.php';

use BlackFramework\Core\Exception\AutoLoaderException;
use \Throwable;

class AutoLoader
{

    /**
     * @var array
     */
    private $namespace = [];

    public function __construct()
    {
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
     * @return bool
     * @throws AutoLoaderException if namespace is not registered
     * @throws Throwable if class not exists
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

                include $this->findFile(
                    $key,
                    $value,
                    $class
                );

                return true;

            }
        }

        throw new AutoLoaderException("Internal Server Error", 500);
    }


    /**
     * @param string $path
     * @param array $value
     * @param string $class
     * @return string
     * @throws AutoLoaderException
     */
    public function findFile(string $path, array $value, string $class)
    {
        foreach ($value as $item) {

            $file = preg_replace("/" . $path . "/", $item, $class)  . ".php";
            echo $file;
            if (file_exists($file))
            {
                return $file;
            }
        }

        throw new AutoLoaderException("Internal Server Error", 500);
    }
}
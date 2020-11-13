<?php

namespace Brain\Injector\Facade;

use Brain\Injector\Container;

class Injector
{

    private static $container;

    /**
     *
     * @param string $path
     * @return void
     */
    public static function init (string $path) : void
    {
        static::$container = (new Container($path))->getContainer();
    }
    /**
     *
     * @param string $methode
     * @param array $arguments
     * @return mixed
     */
    public static function __callStatic(string $methode, array $arguments)
    {
        return call_user_func_array([static::$container, $methode], $arguments);
    }
}
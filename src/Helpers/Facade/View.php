<?php

namespace Brain\Helpers\Facade;

use Brain\Renderer\Renderer;
use Brain\Injector\Facade\Injector;

class View
{
    /**
     *
     * @param string $methode
     * @param array $parameters
     * @return void
     */
    public static function __callStatic(string $methode, array $parameters = [])
    {
        return \call_user_func_array(
            [Injector::get(Renderer::class), $methode], 
            $parameters
        );
    }
}
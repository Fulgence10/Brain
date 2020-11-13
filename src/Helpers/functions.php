<?php

use Brain\Renderer\Renderer;
use Brain\Injector\Facade\Injector;

if(! function_exists('isAjax')) {
    /**
     *
     * @return boolean
     */
    function isAjax () : bool
    {
        $header = $_SERVER["HTTP_X_REQUESTED_WITH"];
        return !empty($header) && strtolower($header) === "xmlhttprequest";
    }
}

if(! function_exists('view')) {
    /**
     *
     * @param string $view
     * @param array $param
     * @return string
     */
    function view (string $view, array $param = []) : string
    {
       $renderer = Injector::get(Renderer::class); 
       return $renderer->render($view, $param);
    }
}

if(! function_exists('redirectTo')) {
    /**
     *
     * @param string $url
     * @return void
     */
    function redirectTo (string $url = "/") : void
    {
        header("Location: $url", true, 301);
        exit();
    }
}

if(! function_exists('path')) {
    
    function path (string $name) : ?string
    {
        $router = Injector::get(\Brain\Router\Router::class);
        return $router->getRoutePath($name);
    }
}

if(! function_exists('dd')) {
    /**
     * 
     *
     * @param array ...$args
     * @return void
     */
    function dd (...$args) : void
    {
        echo "<pre>";
            print_r(...$args);
        echo "</pre>";
        die();
    }
}



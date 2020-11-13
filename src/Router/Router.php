<?php

namespace Brain\Router;

class Router
{
    /**
     * all routes
     *
     * @var array
     */
    private $routes = [];

    /**
     * add route in property routes
     *
     * @param string $method
     * @param string $path
     * @param string|callable $handler
     * @return Route
     */
    public function add(string $method, string $path, $handler): Route
    {
        $route = new Route($path, $handler); 
        $this->routes[$method][] = $route;
        return $route;
    }

    /**
     *
     * @param string $name
     * @return string
     */
    public function getRoutePath(string $name) : ?string 
    {
        $routes = array_merge(
            $this->routes['GET'] ?? [], 
            $this->routes['POST'] ?? []
        );
        foreach ($routes as $route) {
            if($route->getName() === $name) {
                return $route->getPath();
            }
        }
        return null;
    }

    /**
     * match route by url
     *
     * @param string $url
     * @return void
     */
    public function match(string $url) 
    {
        if (! isset($this->routes[$_SERVER["REQUEST_METHOD"]])) {
            return false;
        }

        foreach ($this->routes[$_SERVER["REQUEST_METHOD"]] as $route) {
            if($route->match($url)) {
                return $route;
            }
        }
    }
}
<?php

namespace Brain\Http\Middleware;

use Brain\Router\Route;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class RouteMiddleware
{
    public function delegate (ServerRequestInterface $request, $next) : ResponseInterface
    {
        $route = $request->getAttribute(Route::class);

        if(empty($route->getMiddleware())) {
            return $next($request);
        }
        
        $middleware = $route->getMiddleware();

        $obj = $next[0];

        $obj->addMiddleware($middleware);

        return $next($request);
    }
}
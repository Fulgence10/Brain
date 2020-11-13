<?php

namespace Brain\Http\Middleware;

use Brain\Http\Response;
use Brain\Router\Route;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class DispatcherMiddleware
{
    /**
     * 
     * @param ServerRequestInterface $request
     * @param [type] $next
     * @return ResponseInterface
     */
    public function delegate (ServerRequestInterface $request, $next) : ResponseInterface
    {
        $route = $request->getAttribute(Route::class);

        return new Response(200, [], $route->call($request));
    }
}
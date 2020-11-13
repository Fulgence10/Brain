<?php

namespace Brain\Application;

use Brain\Injector\Facade\Injector;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Brain\Application\Exception\BrainException;

class Dispatcher
{ 
    /**
     *
     * @var integer
     */
    private $index = 0;

    /**
     *
     * @var array
     */
    private $middleware = [];

    /**
     *
     * @param array $middleware
     */
    public function __construct (array $middleware)
    {
        $this->middleware = $middleware;
    }

    /**
     *
     * @param array $middleware
     * @return boolean
     */
    public function addMiddleware (array $middleware) : bool
    {
        $this->index -= 1;

        unset($this->middleware[$this->index]);

        $this->middleware = array_merge(
            $middleware,
            $this->middleware
        );
        
        return true;
    }

    /**
     *
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function delegate (ServerRequestInterface $request) : ResponseInterface
    {
        $middleware = $this->getMiddlewares();

        if(is_null($middleware)) {
            throw new BrainException("Aucun middleware n'a intercepté la demande", 404);
        }

        return \call_user_func_array($middleware, [$request, [$this, "delegate"]]);
    } 

    /**
     *
     * @return mixed
     */
    private function getMiddlewares () 
    {
        if(! isset($this->middleware[$this->index])) {
            return null;
        }

        $middleware = $this->middleware[$this->index];

        $this->index += 1;

        if(\is_callable($middleware)) {
            return $middleware;
        }

        if(is_string($middleware)) {
            if(Injector::has($middleware)) {
                $middleware = Injector::get($middleware);
                return [$middleware, "delegate"];
            }
        }
        return [new $middleware, "delegate"];
    }
}
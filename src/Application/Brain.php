<?php

namespace Brain\Application;

use Brain\Application\Exception\BrainException;
use Brain\Http\Request;
use Brain\Router\Route;
use Brain\Http\Response;
use Brain\Router\Router;
use Brain\Injector\Facade\Injector;
use Psr\Http\Message\ResponseInterface;
use Brain\Http\Middleware\RouteMiddleware;
use Psr\Http\Message\ServerRequestInterface;
use Brain\Http\Middleware\DispatcherMiddleware;

class Brain
{
    /**
     * config path
     *
     * @var string
     */
    private $config;

    /**
     * instance of router
     *
     * @var Router
     */
    private $router;

    /**
     * Undocumented variable
     *
     * @var array
     */
    private $middleware = [
        RouteMiddleware::class,
        DispatcherMiddleware::class
    ];

    /**
     *
     * @var Dispatcher
     */
    private $dispatcher;

    /**
     * constructor
     *
     * @param string $config
     */
    public function __construct(string $config) 
    {
        $this->config = $config;

        $this->router = new Router();

        $this->dispatcher = new Dispatcher($this->middleware);

        Injector::init($config);
    }

    /**
     *
     * @param string $path
     * @param $handler
     * @return Route
     */
    public function get (string $path, $handler) : Route
    {
        return $this->router->add('GET', $path, $this->resolveInsatnce($handler));
    }

    /**
     *
     * @param string $path
     * @param $handler
     * @return Route
     */
    public function post (string $path, $handler) : Route
    {
        return $this->router->add('POST', $path, $this->resolveInsatnce($handler));
    }

    /**
     *
     * @param string $methode
     * @param string $path
     * @param $handler
     * @return Route
     */
    public function maps (string $methode, string $path, $handler) : Route
    {
        return $this->router->add(
            $methode, 
            $path, 
            $this->resolveInsatnce($handler)
        );
    }


    /**
     * Undocumented function
     *
     * @param string $handler
     * @return mixed
     */
    public function resolveInsatnce ($handler)
    {
        if(is_array($handler)) {
            if(Injector::has($handler[0])) {
                $output[0] = Injector::get($handler[0]);
            } else {
                $output[0] = new $handler[0]();
            }
            $output[1] = $handler[1];
        } else {
            $output = $handler;
        }
        return $output;
    }

    /**
     * application started 
     * @param Request $request
     * @return Response
     */
    public function start (ServerRequestInterface $request) : void
    {
        $url = $request->getQueryParams()['url'] ?? $request->getUri()->getPath();

        $route = $this->router->match($url);

        Injector::set(Router::class, $this->router);

        if(! $route) {
            throw new BrainException("Aucune route disponible pour cette requete");
        }
        
        $request = $request->withAttribute(Route::class, $route);
        
        $response = $this->dispatcher->delegate($request);
        
        $this->send($response);
    }

    /**
     * send response and render
     * @param ResponseInterface $response
     * @return void
     */
    public function send (ResponseInterface $response) : void
    {
        $http_line = sprintf(
            'HTTP/%s %s %s',
            $response->getProtocolVersion(),
            $response->getStatusCode(),
            $response->getReasonPhrase()
        );

        header($http_line, true, $response->getStatusCode());

        foreach ($response->getHeaders() as $name => $values) {
            foreach ($values as $value) {
                header("$name: $value", false);
            }
        }

        $stream = $response->getBody();

        if ($stream->isSeekable()) {
            $stream->rewind();
        }

        while (! $stream->eof()) {
            echo $stream->read(1024 * 8);
        }
    } 
    
}
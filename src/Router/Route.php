<?php

namespace Brain\Router;

use Psr\Http\Message\ServerRequestInterface;

class Route
{
    private $path;

    private $name;
    
    private $handler;

    private $with = [];
    
    private $params = [];

    private $middleware = [];

    /**
     * constructor
     *
     * @param string $path
     * @param callable|string $handler
     * @param string $name
     */
    public function __construct(string $path, $handler, string $name = "default")
    {
        $this->path = $path;

        $this->name = $name;

        $this->handler = $handler;
    }

    /**
     *
     * @param string $url
     * @return boolean
     */
    public function match(string $url) : bool
    {
        // path of user
        $url = trim($url, '/');
        // path of developper
        $path = preg_replace_callback(
            "#:([\w]+)#", 
            [$this, "patternReplacer"], 
            trim($this->path, '/')
        );
        // path match
        $path = "#^$path$#";

        if(preg_match($path, $url, $matches)) {
            array_shift($matches);
            $this->params = (array) $matches;
            return true;
        }
        return false;
    }

    /**
     *
     * @param array $pattern
     * @return string
     */
    private function patternReplacer($key) : string
    {
        if (isset($this->with[$key[1]])) {
			return '(' . $this->with[$key[1]] . ')';
		}
		return '([^/]+)';
    }

    /**
     *
     * @param string $key
     * @param string $val
     * @return self
     */
    public function with(string $key, string $val) : self
    {
        $this->with[$key] = str_replace('(', '(?:', $val);

        return $this;
    }

    /**
     *
     * @return mixed
     */
    public function call(ServerRequestInterface $request)
    {
        $params = array_merge([$request], $this->getParams());
        return call_user_func_array($this->getHandler(), $params);
    }

    /**
     *
     * @param array $middleware
     * @return void
     */
    public function middleware(array $middleware) : void
    {
        $this->middleware = $middleware;
    }

    /**
     * Get the value of params
     *
     * @return array
     */
    public function getParams() : array
    {
        return $this->params;
    }

    /**
     * Get the value of handler
     * 
     * @return mixed
     */ 
    public function getHandler()
    {
        return $this->handler;
    }

    /**
     * Get the value of middleware
     * @return array
     */ 
    public function getMiddleware() : array
    {
        return $this->middleware;
    }

    /**
     * Set the value of name
     *
     * @return  self
     */ 
    public function name($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get the value of path
     * @return string 
     */ 
    public function getPath() : string
    {
        return $this->path;
    }

    /**
     * Get the value of name
     */ 
    public function getName()
    {
        return $this->name;
    }
}
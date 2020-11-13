<?php

namespace Brain\Renderer;

use Tintin\Tintin;
use Tintin\Loader\Filesystem;
use Brain\Injector\Facade\Injector;

class TintinRenderer implements RendererInterface
{
    /**
     *
     * @var Loader
     */
    private $loader;

    /**
     *
     * @var Tintin
     */
    private $tintin;
    /**
     * Undocumented function
     */
    public function __construct()
    {
        $this->loader = new Filesystem([
            'path' => Injector::get('path'),
            'cache' => Injector::get('cache', false),
            'extension' => Injector::get('extension')
        ]);
        $this->tintin = new Tintin($this->loader);
    }

    /**
     *
     * @param string $namespace
     * @param string|null $path
     * @return void
     */
    public function addPath (string $namespace, ?string $path = null) : void{}

    /**
     *
     * @param string $view
     * @param array $parameters
     * @return string
     */
    public function render (string $view, array $parameters = []) : string
    {
        return $this->tintin->render($view, $parameters);
    }
}
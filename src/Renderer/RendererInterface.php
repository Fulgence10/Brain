<?php

namespace Brain\Renderer;

interface RendererInterface
{
    /**
     *
     * @param string $namespace
     * @param string|null $path
     * @return void
    */
    public function addPath (string $namespace, ?string $path = null) : void;
 
     /**
      *
      * @param string $view
      * @param array $parameters
      * @return string
    */
     public function render (string $view, array $parameters = []) : string;
}
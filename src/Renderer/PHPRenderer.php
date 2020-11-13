<?php

namespace Brain\Renderer;

use Brain\Renderer\Exception\RendererException;

class PHPRenderer implements RendererInterface
{
    //
    const DEFAULT = "__YAPI__";

    /**
     *
     * @var array
     */
    private $path = [];

    /**
     * Undocumented function
     */
    public function __construct(string $path)
    {
        $this->addPath($path);
    }

    /**
     *
     * @param string $namespace
     * @param string|null $path
     * @return void
     */
    public function addPath (string $namespace, ?string $path = null) : void
    {
        if(is_null($path)) {
            $this->path[self::DEFAULT] = $namespace;
        }else {
            $this->path[$namespace] = $path;
        }
    }

    /**
     *
     * @param string $view
     * @param array $parameters
     * @return string
     */
    public function render (string $view, array $parameters = []) : string
    {
        $file = $this->getFile($view);
        if(! file_exists($file)) {
            throw new RendererException(sprintf("le ficher %s n'existe pas", $file));
        }
        \ob_start();
        extract($parameters);
        require $file;
        return ob_get_clean();
    }

    /**
     *
     * @param string $file
     * @return string
     */
    private function getFile (string $file) : string
    {
        if($this->hasNamespace($file)) {
            $path = $this->replaceNamespace($file);
        }else {
            $path = $this->path[self::DEFAULT] . DIRECTORY_SEPARATOR . $file;
        }
        return $path;
    }

    /**
     *
     * @param string $str
     * @return boolean
     */
    private function hasNamespace (string $str) : bool
    {
        return $str[0] === "@";
    }

    /**
     *
     * @param string $str
     * @return string
     */
    private function getNamespace (string $str) : string
    {
        return substr($str, 1, strpos($str, '/') - 1);
    }

    /**
     *
     * @param string $str
     * @return string
     */
    private function replaceNamespace (string $str) : string
    {
        $namespace = $this->getNamespace($str);
        return str_replace("@" . $namespace, $this->path[$namespace], $str);
    }
}
<?php

namespace Brain\Injector;

use DI\ContainerBuilder;
use Psr\Container\ContainerInterface;

class Container
{

    /**
     *
     * @var string
     */
    private $path;

    /**
     *
     * @var ContainerInterface
     */
    private $container;

    /**
     *
     * @param string $path
     */
    public function __construct (string $path)
    {
        $this->path = array_merge(
            glob($path . DIRECTORY_SEPARATOR . '*.php'),
            glob(dirname(__DIR__) . DIRECTORY_SEPARATOR . 'Config/*.php')
        );
    }

    /**
     *
     * @return ContainerInterface
     */
    public function getContainer () : ContainerInterface
    {
        if(is_null($this->container)) {

            $builder = new ContainerBuilder();

            $builder->useAutowiring(true);

            // $builder->useAnnotations(true);

            foreach ($this->path as $path) {
                $builder->addDefinitions($path);
            }

            $this->container = $builder->build();
        }
        return $this->container;
    }
}
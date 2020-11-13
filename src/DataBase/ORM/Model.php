<?php

namespace Brain\DataBase\ORM;

use ReflectionClass;
use Brain\DataBase\DataBase;

abstract class Model
{
    protected static $builder;

    protected static $entity;

    /**
     *
     * @param array $columns
     * @return array
     */
    public function all(array $columns = []) : array
    {
        $static = $this->query();

        if (count($columns) > 0) {
            $static->select($columns);
        }

        return $static->get();
    }

    /**
     *
     * @return static
     */
    public function findById(int $id)
    {
        return $this->query()->select()->where([
            'id' => $id
        ])->first();
    }

    /**
     *
     * @param string $key
     * @param mixed $param
     * @return void
     */
    public function findBy(string $key, $param)
    {
        return $this->query()->select()->where([
            $key => $param
        ])->first();
    }

    /**
     *
     * @return static
     */
    public function first()
    {
        return $this->query()->first();
    }

    /**
     *
     * @param [type] $entity
     * @return boolean
     */
    public function store($entity) : bool
    {
        $parameters = [];
        
        $columns = [];

        if(is_array($entity)) {
            foreach ($entity as $key => $value) {
                $columns[] = $key;
                $parameters[] = $value;
            }
        }

        if(is_object($entity)) {
            $obj = new ReflectionClass($entity);

            $properties = $obj->getProperties();

            foreach ($properties as $entry) {
                $columns[] = $entry->getName();
                $methode = 'get'.ucfirst($entry->getName());
                if(method_exists($entity, $methode)) {
                    $parameters[] = $entity->$methode();
                }
            }
        }
        return $this->query()->insert($columns)->flush($parameters);
    }

    /**
     * Undocumented function
     *
     * @return Builder
     */
    private function query(): Builder
    {
        if(static::$builder instanceof Builder) {
            return static::$builder;
        }

        $table = explode('\\',  static::class);
        $table = end($table);
        $table = strtolower(str_replace('Manager', 's', $table));

        static::$builder = new Builder(
            $table, 
            DataBase::getAdapterConnexion(),
            static::$entity ?? null
        );

        return static::$builder;
    }
}
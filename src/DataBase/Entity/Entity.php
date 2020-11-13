<?php

namespace Brain\DataBase\Entity;

class Entity
{
    /**
     *
     * @param array $param
     */
    public function __construct(array $param = []) 
    {
        $this->hydrated($param);
    }

    /**
     *
     * @param array $param
     * @return void
     */
    public function hydrated(array $param) : void
    {
        foreach ($param as $key => $value) {
            $methode = 'set'.ucfirst($key);
            if(method_exists($this, $methode)) {
                $this->$methode($value);
            }
        }
    }
}
<?php

namespace Brain\Helpers;

class Arr
{
    private static $_instance = null;

    /**
     *
     * @return self|null
     */
    public static function getInstance () : ?self
    {
        if(is_null(static::$_instance)) {
            static::$_instance = new Arr();
        }
        return static::$_instance;
    }
}
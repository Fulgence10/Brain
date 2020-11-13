<?php

namespace Brain\Helpers;

class Str
{

    private static $_instance = null;

    /**
     *
     * @return self|null
     */
    public static function getInstance () : ?self
    {
        if(is_null(static::$_instance)) {
            static::$_instance = new Str();
        }
        return static::$_instance;
    }

    /**
     *
     * @param string $str
     * @return string
     */
    public function upper (string $str) : string
    {
        return strtoupper($str);
    }

    /**
     *
     * @param string $str
     * @return string
     */
    public function lower (string $str) : string
    {
        return strtolower($str);
    }
    
}
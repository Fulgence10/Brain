<?php

namespace Brain\Services;

class HashSecurity
{
    /**
     *
     * @param string $str
     * @return string
     */
    public static function crypt(string $str) : string 
    {
        return password_hash($str. '__YAPI', PASSWORD_BCRYPT);
    }

    /**
     *
     * @param string $str
     * @param string $dbpass
     * @return boolean
     */
    public static function verify(string $str, string $dbpass) : bool 
    {
        return password_verify($str. '__YAPI', $dbpass);
    }
}
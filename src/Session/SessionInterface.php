<?php

namespace Brain\Session;

interface SessionInterface
{
    /**
     *
     * @param string $key
     * @return mixed
     */
    public function get(string $key, $default = null);
    
    /**
     *
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public function set(string $key, $value) : void;

    /**
     *
     * @param string $key
     * @return void
     */
    public function delete (string $key) : void;

    /**
     *
     * @return void
     */
    public function clear () : void;

}
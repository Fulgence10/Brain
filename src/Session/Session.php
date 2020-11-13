<?php

namespace Brain\Session;

class Session implements SessionInterface
{
    /**
     *
     * @var Session
     */
    private static $_instance;

    /**
     *
     * @return void
     */
    private function start () 
    {
        if(session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     *
     * @return Session
     */
    public static function getInstance() {
        if(is_null(self::$_instance)) {
            self::$_instance = new Session();
        }
        return self::$_instance;
    }

    /**
     *
     * @param string $key
     * @return mixed
     */
    public function has (string $key) 
    {
        $this->start();
        return isset($_SESSION[$key]);
    }

    /**
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function get (string $key, $default = null) 
    {
        $this->start();
        return isset($_SESSION[$key]) ? $_SESSION[$key] : $default;
    }
    
    /**
     *
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public function set (string $key, $value) : void
    {
        $this->start();
        $_SESSION[$key] = $value;
    }

    /**
     *
     * @param string $key
     * @return void
     */
    public function delete (string $key) : void
    {
        $this->start();
        unset($_SESSION[$key]);
    }

    /**
     *
     * @return void
     */
    public function clear () : void
    {
        $this->start();
        session_destroy();
    }
}
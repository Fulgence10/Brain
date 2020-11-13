<?php

namespace Brain\DataBase\Connexion;

use PDO;

class MySqlAdapter
{
    /**
     * Undocumented variable
     *
     * @var PDO
     */
    private $pdo;
    
    /**
     * Undocumented variable
     *
     * @var array
     */
    private $attributes;

    /**
     * Undocumented function
     *
     * @param array $config
     */
    public function __construct (array $config)
    {
        $this->connexion($config);
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    private function connexion (array $config) : void
    {
        if(! empty($config)) {

            $hostname = "mysql:host=".$config['db_host'];

            $db_name = ";dbname=".$config['db_name'];

            $user = $config['db_user'];

            $password = $config['db_pass'];

            $this->attributes = [
                PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ
            ];

            try {
                $this->pdo = new PDO($hostname.$db_name, $user, $password);
            } catch(\PDOException $e) {
                die("Impossible de se connecter à la base de donnée");
                exit();
            }

        }
    }

    /**
     * Undocumented function
     *
     * @return PDO
     */
    public function getConnexion () : PDO
    {
        return $this->pdo;
    }
}
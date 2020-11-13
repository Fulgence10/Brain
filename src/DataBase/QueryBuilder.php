<?php

namespace Brain\DataBase;

use PDO;

class QueryBuilder
{
    protected $connexion;

    protected $table;

    protected $entity;
    
    protected $first;

    private $select;

    private $where;

    private $insertSql;

    private $values;

    private $limit;

    private $whereParameters = [];
    
    /**
     * Undocumented function
     *
     * @param string $table
     * @param PDO $connexion
     */
    public function __construct(string $table, PDO $connexion, ?string $entity) 
    {
        $this->connexion = $connexion;

        $this->table = $table;

        $this->entity = $entity;
    }

    /**
     * Undocumented function
     *
     * @return static
     */
    public function first() 
    {
        $this->first = true;

        return $this->get();
    }
    
    /**
     * Undocumented function
     *
     * @param integer $limit
     * @return static
     */
    public function limit(int $limit)
    {
        $this->limit = $limit;

        return $this->get();
    }

    /**
     *
     * @param array $column
     * @return self
     */
    public function select(array $column = ['*']) : self
    {
        $this->select = implode(', ', $column);

        return $this;
    }

    /**
     *
     * @param array $column
     * @return self
     */
    public function insert(array $column = []) : self
    {
        $this->insertSql = "INSERT INTO $this->table(" .implode(', ', $column) . ")";

        $this->insertSql .= " VALUES(";
        
        foreach ($column as $value) {
            $this->insertSql .= "?, ";
        }
        $this->insertSql = trim($this->insertSql, ', ') . ")";

        return $this;
    }

    /**
     *
     * @param array $params
     * @return boolean
     */
    public function flush(array $params) : bool
    {
        if(! empty($this->insertSql)) {
            $stm = $this->connexion->prepare($this->insertSql);
            if(is_array($params)) {
                return $stm->execute($params);
            }
        }
        return false;
    }


    /**
     *
     * @param array $condition
     * @param string $sp
     * @return self
     */
    public function where(array $condition = [], string $sp = ' AND ') : self
    {
        if(! empty($condition)) {
            $this->whereParameters = $condition;
            foreach ($condition as $key => $value) {
                if(is_null($this->where)) {
                    $this->where = $key . '=:' . $key;
                } else {
                    $this->where .= $sp . $key . '=:' . $key;
                }
            }
        }
        return $this;
    }

    /**
     * Undocumented function
     *
     * @return string
     */
    private function toSql() : string
    {
        $sql = 'SELECT';

        if(empty($this->select)) {
            $sql .=  ' * ';
        } else {
            $sql .=  ' ' . $this->select;
        }
        
        $sql .= ' from ' . $this->table;

        // Ajout de la clause where
        if(! is_null($this->where)) {
            $sql .= ' WHERE ' . $this->where;
            $this->where = null;
        } 

        // Ajout de la clause limit
        if (!is_null($this->limit)) {
            $sql .= ' LIMIT ' . $this->limit;
            $this->limit = null;
        }

        return $sql;
    }

     
    /**
     *
     * @return mixed
     */
    public function get() 
    {
        $stm = $this->connexion->prepare($this->toSql());

        $stm->execute($this->whereParameters);

        if(! is_null($this->entity)) {
            $data = $stm->fetchAll(PDO::FETCH_CLASS, $this->entity);
        } else {
            $data = $stm->fetchAll(PDO::FETCH_OBJ);
        }

        if(! $this->first) {
            return $data;
        }

        $this->first = false;
        
        return current($data);
    }

   
}
<?php

    /**
     * Written by : Benjamin Sorriaux
     * Function : Connect to the database
     */

    class Database
    {
    
        // Database informations
        private $host = "10.5.3.1";
        private $db_name = "geobdd1";
        private $username = "geomAdmin";
        private $password = "root";
        private $port = "5432";
        public $conn;
    
        // Get the database connection
        public function getConnection()
        {

            if($this->conn == null)
            {
                $this->conn = pg_connect("host={$this->host} port={$this->port} dbname={$this->db_name} user={$this->username} password={$this->password}");
            }
            
            return $this->conn;

        }

    }

?>
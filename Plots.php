<?php

    /**
     * Written by : Benjamin Sorriaux
     * Function : get and stock data from the database corresponding to plots
     */

    class Plots
    {
    
        // Database connection and table name
        private $conn;
        private $table_name = "plots";

        private $local;
    
        // Object properties
        public $id;
        public $type = 'Feature';
        public $geometry;
        public $properties = [];
    
        // Constructor with $db as database connection
        public function __construct($db)
        {
            $this->conn = $db;
        }

        // Read plots function
        function read(){
        
            // Select all query
            $stmt = pg_query($this->conn, 'SELECT * FROM ' . $this->table_name . ' LIMIT 15');
        
            return $stmt;
        }

        function getDepartementsName()
        {
            $result = pg_query($this->conn, 'SELECT id, nom FROM departements ORDER BY nom ASC');

            return $result;
        }

        function getDepartements()
        {
            $result = pg_query($this->conn, 'SELECT st_asgeojson(geom) as geom FROM departements inner join geometry_tb on departements.id_geometry = geometry_tb.id');

            return $result;
        }

        function getDepartement($id)
        {
            $result = pg_query_params($this->conn, 'SELECT st_asgeojson(geom) as geom FROM departements inner join geometry_tb on departements.id_geometry = geometry_tb.id WHERE departements.id = $1', array($id));

            return $result;
        }

        function getCountries()
        {
            $result = pg_query($this->conn, 'SELECT st_asgeojson(geom) as geom FROM country inner join geometry_tb on country.id_geometry = geometry_tb.id');

            return $result;
        }

        function getCountry($gid)
        {
            die(var_dump($id));
            $result = pg_query_params($this->conn, 'SELECT st_asgeojson(geom) as geom FROM country inner join geometry_tb on country.id_geometry = geometry_tb.id WHERE gid = $1', array($gid));

            return $result;
        }

        function getMunicipality($id)
        {
            $result = pg_query_params($this->conn, 'SELECT st_asgeojson(geom) as geom FROM communes inner join geometry_tb on communes.id_geometry = geometry_tb.id WHERE communes.id_departement = $1', array($id));

            return $result;
        }

        function getMunicipalities()
        {
            $result = pg_query($this->conn, 'SELECT st_asgeojson(geom) as geom FROM communes inner join geometry_tb on communes.id_geometry = geometry_tb.id');

            return $result;
        }

        function getCommunes()
        {
            $result = pg_query($this->conn, 'SELECT st_asgeojson(geom) as geom FROM communes inner join geometry_tb on communes.id_geometry = geometry_tb.id');

            return $result;
        }

        function getCommunesWithKeyword($keywords)
        {
            $keywords = htmlspecialchars(strip_tags(strtolower($keywords)));

            $this->local = $keywords;

            $result = pg_query_params($this->conn, 'SELECT st_asgeojson(geom) as geom FROM communes inner join geometry_tb on communes.id_geometry = geometry_tb.id INNER JOIN departements on departements.id = communes.id_departement WHERE departements.id = $1', array($keywords));

            return $result;


        }

        function search($keywords)
        {
            // Sanitize
            $keywords = htmlspecialchars(strip_tags(strtolower($keywords)));

            $this->local = $keywords;

            // Execute query
            $result = pg_query_params($this->conn, 
            'SELECT p.id, p.type_ig, d.nom as denomination, h.nom as hierarchie, st_asgeojson(g.geom) as geom, c.nom as commune, a.name as appellation, dpt.nom as departement
            FROM plots p 
            INNER JOIN denominations d on p.id_denomination = d.id 
            INNER JOIN geometry_tb g ON g.id = p.id_geometry 
            INNER JOIN communes c ON c.id = p.insee 
            INNER JOIN appellations a ON a.id = d.id_appellation 
            INNER JOIN hierarchies h ON h.id = d.id_hierarchie 
            INNER JOIN departements dpt ON dpt.id = c.id_departement
            INNER JOIN regions r ON r.gid = dpt.gid_region
            INNER JOIN country ctry ON ctry.gid = r.gid_country
            WHERE lower(a."name") LIKE $1 OR lower(d.nom) LIKE $1 OR lower(r.nom) LIKE $1 OR lower(ctry.nom) LIKE $1 OR lower(c.nom) LIKE $1', array('%' . $keywords . '%'));

            return $result;
        }

        function searchAppellation($keywords)
        {
            $keywords = htmlspecialchars(strip_tags(strtolower($keywords)));
            $this->local = $keywords;

            $result = pg_query_params($this->conn, 
            'SELECT p.id, p.type_ig, d.nom as denomination, h.nom as hierarchie, st_asgeojson(g.geom) as geom, c.nom as commune, a.name as appellation, dpt.nom as departement
            FROM plots p 
            INNER JOIN denominations d on p.id_denomination = d.id 
            INNER JOIN geometry_tb g ON g.id = p.id_geometry 
            INNER JOIN communes c ON c.id = p.insee 
            INNER JOIN appellations a ON a.id = d.id_appellation 
            INNER JOIN hierarchies h ON h.id = d.id_hierarchie 
            INNER JOIN departements dpt ON dpt.id = c.id_departement
            INNER JOIN regions r ON r.gid = dpt.gid_region
            WHERE lower(a."name") LIKE $1', array('%' . $keywords . '%'));

            return $result;

        }

        function searchDenomination($keywords)
        {
            $keywords = htmlspecialchars(strip_tags(strtolower($keywords)));
            $this->local = $keywords;

            $result = pg_query_params($this->conn, 
            'SELECT p.id, p.type_ig, d.nom as denomination, h.nom as hierarchie, st_asgeojson(g.geom) as geom, c.nom as commune, a.name as appellation, dpt.nom as departement
            FROM plots p 
            INNER JOIN denominations d on p.id_denomination = d.id 
            INNER JOIN geometry_tb g ON g.id = p.id_geometry 
            INNER JOIN communes c ON c.id = p.insee 
            INNER JOIN appellations a ON a.id = d.id_appellation 
            INNER JOIN hierarchies h ON h.id = d.id_hierarchie 
            INNER JOIN departements dpt ON dpt.id = c.id_departement
            INNER JOIN regions r ON r.gid = dpt.gid_region
            WHERE lower(d.nom) LIKE $1', array('%' . $keywords . '%'));

            return $result;

        }

        public function __toString()
        {
            return $this->local;
        }
    }

?>

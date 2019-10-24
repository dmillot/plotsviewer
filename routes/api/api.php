<?php

    /**
     * Written by : Benjamin Sorriaux & Damien Millot
     * This script get the value entered by the client in the link and check if database contain elements with this appellation
     * This script will have to check all the database
     */

    // Create headers
    // header("Access-Control-Allow-Origin: *");
    // header("Content-Type: application/json; charset=UTF-8");
    
    // Include database and object files
    include_once '../Database.php';
    include_once '../Plots.php';
    include_once '../FeatureCollection.php';

    die(var_dump($_GET));
    
    // Instantiate database and product object
    $database = new Database();
    $db = $database->getConnection();

    // Variable to know if the query success
    $successRequest = false;
    
    // Initialize objects
    $plots = new Plots($db);
    $featureCollection = new FeatureCollection();
    
    if (isset($_GET['gid']))
    {
        // Query products
        $stmt = $plots->getCountry($_GET['gid']);
        $num = pg_num_rows($stmt);

        // Check if more than 0 record found
        if ($num > 0)
        {

            $rows = pg_fetch_all($stmt);
            
            foreach($rows as $row)
            {

                $plot = new Plots($db);
                $plot->geometry = json_decode($row['geom']);

                array_push($featureCollection->features, $plot);
            }

            $successRequest = true;
        }
    } else {
        // Query products
        $stmt = $plots->getCountries();
        $num = pg_num_rows($stmt);

        // Check if more than 0 record found
        if ($num > 0)
        {

            $rows = pg_fetch_all($stmt);
            
            foreach($rows as $row)
            {

                $plot = new Plots($db);
                $plot->geometry = json_decode($row['geom']);

                array_push($featureCollection->features, $plot);
            }

            $successRequest = true;
        }
    }

    if ($successRequest)
    {
        http_response_code(200);
        echo json_encode($featureCollection);
    } 
    else 
    {
        http_response_code(404);
        echo json_encode([ "message" => "No data found." ]);
    }
    
?>
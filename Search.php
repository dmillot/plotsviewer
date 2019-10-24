<?php

    /**
     * Written by : Benjamin Sorriaux & Damien Millot
     * This script get the value entered by the client in the link and check if database contain elements with this appellation
     * This script will have to check all the database
     */

    // Create headers
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    
    // Include database and object files
    include_once 'Database.php';
    include_once 'Plots.php';
    include_once 'FeatureCollection.php';
    
    // Instantiate database and product object
    $database = new Database();
    $db = $database->getConnection();

    // Variable to know if the query success
    $successRequest = false;
    
    // Initialize objects
    $plots = new Plots($db);
    $featureCollection = new FeatureCollection();
    
    if (isset($_GET["search"]))
    {
        $keywords = $_GET["search"];

        // Query products
        $stmt = $plots->search($keywords);
        $num = pg_num_rows($stmt);
        
        // Check if more than 0 record found
        if ($num > 0)
        {
            $rows = pg_fetch_all($stmt);
            
            foreach($rows as $row)
            {

                $plot = new Plots($db);
                $plot->geometry = json_decode($row['geom']);
                $plot->properties = [
                    "departement" => $row['departement'],
                    "commune" => $row['commune'],
                    "type" => $row['type_ig'],
                    "appellation" => $row['appellation'],
                    "denomination" => $row['denomination'],
                    "hierarchie" => $row['hierarchie']
                ];

                array_push($featureCollection->features, $plot);
            }

            $successRequest = true;
        }
    } else if (isset($_GET["appellation"])) {

        $keywords = $_GET["appellation"];

        // Query products
        $stmt = $plots->searchAppellation($keywords);
        $num = pg_num_rows($stmt);

        // Check if more than 0 record found
        if ($num > 0)
        {

            $rows = pg_fetch_all($stmt);

            $featurecollection = [
                "type" => "FeatureCollection",
                "features" => []
            ];
            
            foreach($rows as $row)
            {

                $plot_item = [
                    "type" => "Feature",
                    "geometry" => json_decode($row['geom']),
                    "properties" => [
                        "departement" => $row['departement'],
                        "commune" => $row['commune'],
                        "type" => $row['type_ig'],
                        "appellation" => $row['appellation'],
                        "denomination" => $row['denomination'],
                        "hierarchie" => $row['hierarchie'],
                    ]
                ];

                array_push($featurecollection['features'], $plot_item);
            }

            $successRequest = true;
        }
    } else if (isset($_GET["denomination"])) {

        $keywords = $_GET["denomination"];

        // Query products
        $stmt = $plots->searchDenomination($keywords);
        $num = pg_num_rows($stmt);

        // Check if more than 0 record found
        if ($num > 0)
        {

            $rows = pg_fetch_all($stmt);

            $featurecollection = [
                "type" => "FeatureCollection",
                "features" => []
            ];
            
            foreach($rows as $row)
            {

                $plot_item = [
                    "type" => "Feature",
                    "geometry" => json_decode($row['geom']),
                    "properties" => [
                        "departement" => $row['departement'],
                        "commune" => $row['commune'],
                        "type" => $row['type_ig'],
                        "appellation" => $row['appellation'],
                        "denomination" => $row['denomination'],
                        "hierarchie" => $row['hierarchie'],
                    ]
                ];

                array_push($featurecollection['features'], $plot_item);
            }

            $successRequest = true;
        }
    } else if (isset($_GET["departementselect"])) 
    {

        // Query products
        $stmt = $plots->getDepartementsName();
        $num = pg_num_rows($stmt);

        // Check if more than 0 record found
        if ($num > 0)
        {

            $rows = pg_fetch_all($stmt);

            http_response_code(200);
            echo json_encode($rows);
            die;

            $successRequest = true;
        }
    } else if (isset($_GET["communes"])) 
    {

        // Query products
        $stmt = $plots->getCommunes();
        $num = pg_num_rows($stmt);

        // Check if more than 0 record found
        if ($num > 0)
        {

            $rows = pg_fetch_all($stmt);

            $featurecollection = [
                "type" => "FeatureCollection",
                "features" => []
            ];
            
            foreach($rows as $row)
            {

                $plot_item = [
                    "type" => "Feature",
                    "geometry" => json_decode($row['geom']),
                    "properties" => [
                        "departement" => "none"
                    ]
                ];

                array_push($featurecollection['features'], $plot_item);
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
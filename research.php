
<!DOCTYPE html>
<html lang="en">

<head>
    <title>PlotsViewer - Search</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" type="image/png" href="assets/images/logo.png" />
    <link rel='stylesheet' href='css/mapbox/mapbox-gl.css' />
    <link rel="stylesheet" href="css/style.css" />
    <!-- <link rel="stylesheet" href="css/bootstrap/bootstrap.min.css" /> -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <!-- <script src="js/bootstrap/bootstrap.min.js"></script> -->
    <script src='js/mapbox/mapbox-gl.js'></script>
    
    <script src="js/jquery/jquery-3.4.1.min.js"></script>
    <script src="https://d3js.org/d3.v5.js"></script>
    <script src="https://d3js.org/d3-scale-chromatic.v1.min.js"></script>
    <script src="https://d3js.org/d3-geo-projection.v2.min.js"></script>
    <script src="js/main.js"></script>
</head>

<div id="mapStats">
    <div id="d3map"></div>
    <a href="stats.html" style="color: #fff; position: absolute; top: 0; left: 50%; transform: translateX(-50%);">Display evolution</a>
</div>

<body class="d-flex align-items-stretch m-0 p-0 minh-100 h-100">


    <div id="background-image" class="position-absolute minh-100 h-100 w-100"></div>

    <div class="container bg-custom d-flex flex-column align-items-center col-xl-8 p-4 minh-100 h-100">

        <div class="container d-flex flex-column align-items-center col-xl-10 p-0 m-0 h-100">

            <div class="row w-100 d-flex justify-content-between align-items-center p-0 m-0">

                <div class="col-11 h-100 p-0 m-0">
                    
                    <form action="" method="GET" class="h-100">

                        <div class="form-group h-100 m-0 p-0">
                            
                            <input type="text" class="form-control h-100" id="inputSearch" placeholder="Search..">
                            
                        </div>

                    </form>

                </div>

                <div class="col-1 d-flex justify-content-end align-items-center p-0 m-0">

                    <img id="filter" onclick="displayOptions();" src="assets/images/filter.png" alt="icon filter" width="28px" height="auto">

                </div>


            </div>

            <div id="searchOptions" class="row w-100 p-0 m-0 mt-4">

                <fieldset class="form-group col-6 p-0 m-0">
                    <div class="row" style="color: #fff !important">
                        <legend class="col-form-label col-12 pt-0">Display :</legend>
                        <div class="col-sm-12">
                            <div class="form-group m-0 p-0">
                                <div class="form-check">
                                    <input class="form-check-input" name="countries" type="checkbox" id="countries">
                                    <label class="form-check-label text-white" for="countries">
                                        Countries outlines
                                    </label>
                                </div>
                            </div>
                            <div class="form-group m-0 p-0">
                                <div class="form-check">
                                    <input class="form-check-input" name="departements" type="checkbox" id="departements">
                                    <label class="form-check-label text-white" for="departements">
                                        Departments outlines
                                    </label>
                                </div>
                            </div>
                            <div class="form-group m-0 p-0">
                                <div class="form-check">
                                    <label class="form-check-label text-white" for="departements">
                                        Communes outlines for department :
                                    </label>
                                    <select class="form-control w-50" name="departementselect" id="departementselect">
                                        <option value="none" selected>none</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </fieldset>

                <fieldset class="form-group col-6 p-0 m-0">
                    <div class="row" style="color: #fff !important">
                        <legend class="col-form-label col-12 pt-0">Search only for :</legend>
                        <div class="col-sm-12">
                            <div class="form-group m-0 p-0">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="denomination" id="denomination" value="denomination">
                                    <label class="form-check-label" for="denomination">
                                    Dénomination
                                    </label>
                                </div>
                            </div>
                            <div class="form-group m-0 p-0">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="appellation" id="appellation" value="appellation">
                                    <label class="form-check-label" for="appellation">
                                        Appellation
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </fieldset>

            </div>

            <div class="row w-100 d-flex justify-content-center p-0 m-0 mt-4">

                <div class="col-4 d-flex justify-content-center p-0 m-0">

                    <button id="btnSearch" class="btn btn-custom mr-2">SEARCH</button>
                    <button id="btnStats" class="btn btn-custom ml-2">STATS</button>

                </div>

            </div>

            <div class="row w-100 d-flex justify-content-center mt-4 mb-4">

                <div class="col-7 p-0 m-0">

                    <hr class="w-100"/>

                </div>

            </div>

        </div>

        <div class="container-fluid d-flex flex-column justify-content-around align-items-center col-12 flex-grow-1 overflow-hidden">

            <div class="lds-ring"><div></div><div></div><div></div><div></div></div>
            <div id="map"></div>
            <pre id="info"></pre>

        </div>
        
    </div>

</body>

</html>
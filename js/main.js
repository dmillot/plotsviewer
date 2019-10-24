function navigate(page)
{
    location.href = page;
}

function displayOptions()
{
    let element = document.getElementById('searchOptions');
    if (element.style.display == "flex")
    {
        element.style.display = "none";
    } else {
        element.style.display = "flex";
    }
        
}

function showDepartments(map, object)
{
    if (map.getLayer('departements-line')) map.removeLayer('departements-line');
    if (map.getSource('departements')) map.removeSource('departements');

    map.addSource("departements", {
        "type": "geojson",
        "data": object
    });

    map.addLayer({
        "id": "departements-line",
        "type": "line",
        "source": "departements",
        "paint": {
            "line-color": "#fff",
            "line-opacity": 1,
            "line-width": 2
        },
        "filter": ["==", "$type", "Polygon"]
    });

    if (map.getLayer('countries-line'))
    {
        map.moveLayer('departements-line', 'countries-line');
    }
}

function showCountries(map, object)
{
    if (map.getLayer('countries-line')) map.removeLayer('countries-line');
    if (map.getSource('countries')) map.removeSource('countries');

    map.addSource("countries", {
        "type": "geojson",
        "data": object
    });

    map.addLayer({
        "id": "countries-line",
        "type": "line",
        "source": "countries",
        "paint": {
            "line-color": "#8b0000",
            "line-opacity": 1,
            "line-width": 2
        },
        "filter": ["==", "$type", "Polygon"]
    });
}

function showMunicipalities(map, object)
{
    if (map.getLayer('communes-line')) map.removeLayer('communes-line');
    if (map.getSource('communes')) map.removeSource('communes');

    map.addSource("communes", {
        "type": "geojson",
        "data": object
    });

    map.addLayer({
        "id": "communes-line",
        "type": "line",
        "source": "communes",
        "paint": {
            "line-color": "#1ca3ec",
            "line-opacity": 1,
            "line-width": 2
        },
        "filter": ["==", "$type", "Polygon"]
    });
}

function showPlots(map, object)
{
    if (map.getLayer('plots-fill')) map.removeLayer('plots-fill');
    if (map.getLayer('plots-line')) map.removeLayer('plots-line');
    if (map.getSource('plots')) map.removeSource('plots');

    map.addSource("plots", {
        "type": "geojson",
        "data": object
    });

    // layer for plots filling
    map.addLayer({
        "id": "plots-fill",
        "type": "fill",
        "source": "plots",
        "paint": {
            "fill-color": "#553344",
            "fill-opacity": 0.5
        },
        "filter": ["==", "$type", "Polygon"]
        });

    // layer for plots borders
    map.addLayer({
        "id": "plots-line",
        "type": "line",
        "source": "plots",
        "paint": {
            "line-color": "#553344",
            "line-opacity": 1,
            "line-width": 2
        },
        "filter": ["==", "$type", "Polygon"]
    });
}

function initializeMapStats()
{
    // Dimensions of the SVG
    const width = 550, height = 550;

    // Creating a path object
    const path = d3.geoPath();

    // Define the projection
    const projection = d3.geoConicConformal()
    .center([2.454071, 46.279229])
    .scale(2600)
    .translate([width / 2, height / 2]);

    // Assign projection to the path
    path.projection(projection);

    // Link svg to the #d3map div
    const svg = d3.select('#d3map').append("svg")
        .attr("id", "svg")
        .attr("width", width)
        .attr("height", height);

    const deps = svg.append("g");

    // Using a tooltip
    var div = d3.select("body").append("div")   
        .attr("class", "tooltip")               
        .style("opacity", 0);

    // Adding the mouseover and the mouseout methods
    d3.json('js/file.json').then(function(geojson) {			
        deps.selectAll("path")
            .data(geojson.features)
            .enter()
            .append("path")
            .attr('class', 'department')
            .attr("d", path)
            .on("mouseover", function(d) {
                div.transition()        
                    .duration(200)
                    .style("opacity", .9);      
                div.html("Département : " + d.properties.CODE_DEPT + " - " + d.properties.NOM_DEPT + "<br/>"
                    +  "Région : " + d.properties.NOM_REGION + "<br/>"
                    +  "Superficie viticole : " + d.properties.SUPERFICIE + " m²<br/>"
                    +  "Nombre de parcelles viticoles : " + d.properties.NB_PLOTS)
                    .style("left", (d3.event.pageX + 30) + "px")     
                    .style("top", (d3.event.pageY - 30) + "px")
            })
            .on("mouseout", function(d) {
                div.style("opacity", 0);
                div.html("")
                    .style("left", "-500px")
                    .style("top", "-500px");
            });
            
    });
}

function initializeMap()
{
    mapboxgl.accessToken = 'pk.eyJ1IjoiZG1pbGxvdCIsImEiOiJjazFqYzZ4M2UxNHZ3M2RwNjN5cHYycm1pIn0.9tI8f2BQ4wTLGl3sm2-8iQ';

    var map = new mapboxgl.Map({
        container: 'map',// container id
        style: 'mapbox://styles/dmillot/ck237iljl05jj1cr45tf9w59n',// stylesheet location
        center: [2.3,46.5],// starting position [lng,lat]
        zoom: 5 // starting zoom
    });

    map.on('mousemove',function (e) {
        document.getElementById('info').innerHTML =
        'Longitude : <b>' + 
        JSON.stringify(e.lngLat.lng) + '</b><br />' +
        'Latitude : <b>' +
        JSON.stringify(e.lngLat.lat) + '</b>';
    });

    map.on('click', 'plots-fill', function (e) {
        var object = e.features[0].properties;
        var info = "<br>";
        $.each(object, function(key, value){
            info += ' ' + '<b>' + key + ':</b> ' + value + '<br>';
        })
        new mapboxgl.Popup()
            .setLngLat(e.lngLat)
            .setHTML(info)
            .addTo(map);
    });

    return map;
}

function getDepartments()
{
    $.ajax({
        
        url: "http://127.0.0.1/plotsviewer/Search.php",
        type: "GET",
        data: "departementselect=" + 'true',
        dataType: "json",
        success: function(response)
        {
            $.each(response, function(){
                var o = new Option(this.nom, this.id);
                $(o).html(this.nom);
                $('#departementselect').append(o);
            })
        },
        error: function(error)
        {
            console.log('Error while retrieving departments. Error code : ' + error);
        }
    
    });
}

jQuery(document).ready(function($)
{
    let map = initializeMap();

    initializeMapStats();

    getDepartments();

    $('#btnStats').on('click', function(){
        if ($('#mapStats').css('display') == 'none')
        {
            $('#mapStats').css('display', 'block');
            $('#map').css('pointer-events', 'none');
        }
        else
        {
            $('#mapStats').css('display', 'none');
            $('#map').css('pointer-events', 'auto');
        }
    })

    $('#d3map').on('click', function(){
        $('#mapStats').css('display', 'none');
        $('#map').css('pointer-events', 'auto');
    })
    
    


    $('input[name=departements]').change(function(){
        if ($('input[name=departements]').is(':checked'))
        {
            $.ajax({
        
                url: "http://127.0.0.1/plotsviewer/api/department/",
                type: "GET",
                dataType: "json",
                beforeSend: function()
                {
                    $('.lds-ring').css('visibility', "visible");
                },
                success: function(response,statut)
                {
                    showDepartments(map, response);
                },
                complete: function()
                {
                    $('.lds-ring').css('visibility', "hidden");
                },
                error: function(result, statut, error)
                {
                    alert('Data not found. \nerror code : ' + error);
                }
            
            });
        } else {
            if (map.getLayer('departements-line')) map.removeLayer('departements-line');
            if (map.getSource('departements')) map.removeSource('departements');
        }
    })

    $('input[name=countries]').change(function(){
        if ($('input[name=countries]').is(':checked'))
        {
            $.ajax({
        
                url: "http://127.0.0.1/plotsviewer/api/country/",
                type: "GET",
                dataType: "json",
                beforeSend: function()
                {
                    $('.lds-ring').css('visibility', "visible");
                },
                success: function(response,statut)
                {
                    showCountries(map, response);
                },
                complete: function()
                {
                    $('.lds-ring').css('visibility', "hidden");
                },
                error: function(result, statut, error)
                {
                    alert('Data not found. \nerror code : ' + error);
                }
            
            });
        } else {
            // delete old layers of countries
            if (map.getLayer('countries-line')) map.removeLayer('countries-line');
            if (map.getSource('countries')) map.removeSource('countries');
        }
    })

    $('#departementselect').change(function(){
        if (map.getLayer('communes-line')) map.removeLayer('communes-line');
        if (map.getSource('communes')) map.removeSource('communes');

        var dpt = $('#departementselect').val();

        if (dpt != "none")
        {
            $.ajax({
    
                url: "http://127.0.0.1/plotsviewer/api/municipality/" + dpt,
                type: "GET",
                dataType: "json",
                beforeSend: function()
                {
                    $('.lds-ring').css('visibility', "visible");
                },
                success: function(response)
                {
                    showMunicipalities(map, response);
                },
                complete: function()
                {
                    $('.lds-ring').css('visibility', "hidden");
                },
                error: function(error)
                {
                    alert('Data not found. \nerror code : ' + error);
                }
            
            });
        }
    })

    $('#btnSearch').on('click',function(){
        var search = $('#inputSearch').val();
        if (search != "")
        {
            if( $('input[name=appellation]').is(':checked') ){
                $.ajax({
        
                    url: "http://127.0.0.1/plotsviewer/search.php",
                    type: "GET",
                    data: "appellation=" + search,
                    dataType: "json",
                    beforeSend: function()
                    {
                        $('.lds-ring').css('visibility', "visible");
                    },
                    success: function(response)
                    {
                        showPlots(map, response);
                    },
                    complete: function()
                    {
                        $('.lds-ring').css('visibility', "hidden");
                    },
                    error: function(error)
                    {
                        alert('Data not found. \nerror code : ' + error);
                    }
                
                });
            } 
            
            
            else if ($('input[name=denomination]').is(':checked')) { 
                
                $.ajax({
        
                    url: "http://127.0.0.1/plotsviewer/search.php",
                    type: "GET",
                    data: "denomination=" + search,
                    dataType: "json",
                    beforeSend: function()
                    {
                        $('.lds-ring').css('visibility', "visible");
                    },
                    success: function(response)
                    {
                        showPlots(map, response);
                    },
                    complete: function()
                    {
                        $('.lds-ring').css('visibility', "hidden");
                    },
                    error: function(error)
                    {
                        alert('Data not found. \nerror code : ' + error);
                    }

                });
            } 

            else {

                $.ajax({
        
                    url: "http://127.0.0.1/plotsviewer/search.php",
                    type: "GET",
                    data: "search=" + search,
                    dataType: "json",
                    beforeSend: function()
                    {
                        // display a loading icon
                        $('.lds-ring').css('visibility', "visible");
                    },
                    success: function(response)
                    {
                        showPlots(map, response);
                    },
                    complete: function()
                    {
                        // hide loading icon
                        $('.lds-ring').css('visibility', "hidden");
                    },
                    error: function(error)
                    {
                        alert('Data not found. \nerror code : ' + error);
                    }
                
                });
            }

        } else {
            alert('Enter a search value');
        }
    
    });
        
});





// if (navigator.geolocation) {
//     navigator.geolocation.getCurrentPosition(showPosition);
// } else {
//     console.log("geolocalisation error.");
// }



<link rel="stylesheet" href="<?php echo base_url("res/css/bootstrap-tagsinput.css") ?>"> 
<link rel="stylesheet" href="<?php echo base_url("res/css/typeahead.css") ?>">
<link rel="stylesheet" href="<?php echo base_url("res/css/bootstrap-datetimepicker.min.css") ?>">
<script type="text/javascript" charset="utf8" src="<?php echo base_url("res/js/vendor/typeahead.bundle.min.js") ?>"></script>
<script type="text/javascript" charset="utf8" src="<?php echo base_url("res/js/vendor/bootstrap-tagsinput.min.js") ?>"></script>
<script type="text/javascript" charset="utf8" src="<?php echo base_url("res/js/vendor/moment-with-locales.min.js") ?>"></script> 
<script type="text/javascript" charset="utf8" src="<?php echo base_url("res/js/vendor/bootstrap-datetimepicker.min.js") ?>"></script> 

    <style>
      .controls {
        margin-top: 16px;
        border: 1px solid transparent;
        border-radius: 2px 0 0 2px;
        box-sizing: border-box;
        -moz-box-sizing: border-box;
        height: 32px;
        outline: none;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);
      }

      #pac-input {
        background-color: #fff;
        font-family: Roboto;
        font-size: 15px;
        font-weight: 300;
        margin-left: 12px;
        padding: 0 11px 0 13px;
        text-overflow: ellipsis;
        width: 400px;
      }

      #pac-input:focus {
        border-color: #4d90fe;
      }

      .pac-container {
        font-family: Roboto;
      }

      #type-selector {
        color: #fff;
        background-color: #4d90fe;
        padding: 5px 11px 0px 11px;
      }

      #type-selector label {
        font-family: Roboto;
        font-size: 13px;
        font-weight: 300;
      }

    </style>


<script type="text/javascript"
  src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDWUbQndksJLY1bIzZOvYrO6denngD61u4&libraries=places">
</script>

<script>
var selectorMarker;
var selectorInfoWindow;
var markersInfoWindow;
var crimeMarkers = [];
var policeMarkers = [];
var crimeMap;
//var selectorInfoWindowHTML = "";
    
function initialize() {
    var mapProp = {
        center:new google.maps.LatLng(7.086457,125.617170),
        zoom:13,
        mapTypeId:google.maps.MapTypeId.ROADMAP,
        draggableCursor: 'crosshair'
    };
    
    crimeMap=new google.maps.Map(document.getElementById("googleMap"),mapProp);
    
    selectorMarker = new google.maps.Marker({
        position:crimeMap.getCenter(),
        draggable: true
    });
    
    // <button type="button" class="btn btn-sm btn-default" id="btn-add-crime-cancel"><span class="glyphicon glyphicon-plus"></span> Plot crime incident</button>
    selectorInfoWindow = new google.maps.InfoWindow({
        content: '<a type="button" class="btn btn-xs btn-primary" id="btn-marker-plot-crime"><span class="glyphicon glyphicon-plus"></span> Add crime incident</a> <br/>' +
                 '<a style="margin-top: 5px;" type="button" class="btn btn-xs btn-primary" id="btn-marker-plot-outpost"><span class="glyphicon glyphicon-plus"></span> Add police outpost</a>'
    });
    
    markersInfoWindow = new google.maps.InfoWindow({
        content: '',
        maxWidth: 400
    });
    
    google.maps.event.addListener(crimeMap, 'click', function(e) {
        placeMarker(e.latLng, crimeMap);
    });
    
    google.maps.event.addListener(crimeMap, 'idle', function() {
      showMarkers(crimeMap);
    });

    google.maps.event.addListener(selectorInfoWindow, 'closeclick', function() {
       selectorInfoWindow.close();
       selectorMarker.setMap(null);
    });

    var input = /** @type {HTMLInputElement} */(
        document.getElementById('pac-input'));
    crimeMap.controls[google.maps.ControlPosition.TOP_LEFT].push(input);
    
    var searchBox = new google.maps.places.SearchBox(
      /** @type {HTMLInputElement} */(input));
    
    google.maps.event.addListener(searchBox, 'places_changed', function() {
        var places = searchBox.getPlaces();

        if (places.length === 0) {
            return;
        }
        var bounds = new google.maps.LatLngBounds();
        for (var i = 0, place; place = places[i]; i++) {
            bounds.extend(place.geometry.location);
        }
        crimeMap.fitBounds(bounds);
    });

    google.maps.event.addListener(crimeMap, 'bounds_changed', function() {
        var bounds = crimeMap.getBounds();
        searchBox.setBounds(bounds);
    });

}

function placeMarker(position, map) {
    selectorMarker.setPosition(position);
    selectorMarker.setMap(map);
    selectorInfoWindow.open(map, selectorMarker);
    
    $(document).ready(function () {
        $('#btn-marker-plot-crime').on('click', function(){
            showPlotCrimeModal();
        });
        $('#btn-marker-plot-outpost').on('click', function(){
            showPlotOutpostModal();
        });
    });
}

function showMarkers(map) {

    var bounds = map.getBounds();

    // Call you server with ajax passing it the bounds
    // In the ajax callback delete the current markers and add new markers

    var southWest = bounds.getSouthWest();
    var northEast = bounds.getNorthEast();

    $.ajax({

        url: '<?php echo site_url("administrator/fetch_crime_markers"); ?>',
        cache: false,
        data: {
            'fromlat': southWest.lat(),
            'tolat': northEast.lat(),
            'fromlng': southWest.lng(),
            'tolng': northEast.lng()
        },

        dataType: 'json',
        type: 'GET',

        async: false,

        success: function (data) {
            if (data) {
                var newMarkers = [];
                
                $.each(data, function (i, item) {
                    var key = item.Latitude + " " + item.Longitude;
                    newMarkers[key] = true;
                    if (typeof crimeMarkers[key] === 'undefined'){
                        crimeMarkers[key] = createCrimeMarker(item);
                    }
                });
                
                for (var key in crimeMarkers){
                    if (typeof newMarkers[key] === 'undefined'){
                        crimeMarkers[key].setMap(null);
                        delete crimeMarkers[key];
                    }
                }
            }
        }
    });
    
    
    $.ajax({

        url: '<?php echo site_url("administrator/fetch_policeoutpost_markers"); ?>',
        cache: false,
        data: {
            'fromlat': southWest.lat(),
            'tolat': northEast.lat(),
            'fromlng': southWest.lng(),
            'tolng': northEast.lng()
        },

        dataType: 'json',
        type: 'GET',

        async: false,

        success: function (data) {
            if (data) {
                var newMarkers = [];
                
                $.each(data, function (i, item) {
                    var key = item.Latitude + " " + item.Longitude;
                    newMarkers[key] = true;
                    if (typeof policeMarkers[key] === 'undefined'){
                        policeMarkers[key] = createOutpostMarkers(item);
                    }
                });
                
                for (var key in policeMarkers){
                    if (typeof newMarkers[key] === 'undefined'){
                        policeMarkers[key].setMap(null);
                        delete policeMarkers[key];
                    }
                }
            }
        }
    });
}

function createCrimeMarker(item) {

    var marker = new google.maps.Marker({
        position: new google.maps.LatLng(item.Latitude, item.Longitude),
        map: crimeMap,
        draggable: false
    });

    //crimeMarkers.push(marker);
    //marker.setMap(crimeMap);
    
    google.maps.event.addListener(marker, 'click', (function(map, marker, incidentID) {
        return function() {
            
            $.get("<?php echo site_url("administrator/get_incident_info"); ?>",
                {
                    incident_id: incidentID
                },
                function(data, status){
                    if (status === "success"){
                        var jsonResponse = $.parseJSON(data);
                        
                        var geocoder = new google.maps.Geocoder();
                        var latlng = new google.maps.LatLng(jsonResponse.Latitude, jsonResponse.Longitude);
                        geocoder.geocode({'latLng': latlng}, function(results, status) {
                          if (status === google.maps.GeocoderStatus.OK) {
                            if (results[0]) {
                                var htmlContent = '<div style="margin: -5px 15px 10px 10px;"><div class="row">' +
                                                    '<div class="col-sm-12"><strong><h3><span class="glyphicon glyphicon-map-marker" aria-hidden="true"></span> ' + jsonResponse.Crime_Name + '</h3></strong></div>' + 
                                                    '<div class="col-sm-4"><strong>Narrative</strong></div><div class="col-sm-8">' + jsonResponse.Incident_Description + '</div>' + 
                                                    '<div class="col-sm-4"><strong>Occured on</strong></div><div class="col-sm-8">' + moment(jsonResponse.Time).format('MMMM D, YYYY h:mm A') + '</div>' + 
                                                    '<div class="col-sm-4"><strong>Location</strong></div><div class="col-sm-8">' + results[0].formatted_address + '</div>' + 
                                                    '<div class="col-sm-4"><strong>Reported by</strong></div><div class="col-sm-8">' + jsonResponse.Username + '</div>' +
                                                    '</div> <div class="row pull-right">' +
                                                    '<a type="button" class="btn btn-xs btn-success" id="btn-edit-incident"><span class="glyphicon glyphicon-edit"></span> Edit</a> ' +
                                                    '<a type="button" class="btn btn-xs btn-danger" id="btn-delete-incident"><span class="glyphicon glyphicon-trash"></span> Delete</a>' +
                                                    '</div></div>';

                                markersInfoWindow.setContent(htmlContent);
                                markersInfoWindow.open(map, marker);
                                
                                $(document).ready(function () {
                                    $('#btn-edit-incident').on('click', function(){
                                        showEditIncidentModal(incidentID);
                                    });
                                    $('#btn-delete-incident').on('click', function(){
                                        showDeleteIncidentModal(incidentID);
                                    });
                                });
                                
                            } else {
                              alert('Geocoder error.');
                            }
                          } else {
                            alert('Geocoder failed due to: ' + status);
                          }
                        });
                       //selectorInfoWindow.close();
                       //selectorMarker.setMap(null);
                       //showMarkers(crimeMap);
                    }
                }
            );
            
            //infowindow.setContent(locations[i][0]);
            //infowindow.open(map, marker);
            
        };
    })(crimeMap, marker, item.Incident_ID));
    
    return marker;
}

function createOutpostMarkers(item){
    var marker = new google.maps.Marker({
        position: new google.maps.LatLng(item.Latitude, item.Longitude),
        map: crimeMap,
        draggable: false,
        icon: "<?php echo base_url("img/outpost_marker.png") ?>"
    });

    //policeMarkers.push(marker);
    //marker.setMap(crimeMap);
    
    
    
    google.maps.event.addListener(marker, 'click', (function(map, marker, outpostID) {
        return function() {
            
            $.get("<?php echo site_url("administrator/get_outpost_info"); ?>",
                {
                    outpost_id: outpostID
                },
                function(data, status){
                    if (status === "success"){
                        var jsonResponse = $.parseJSON(data);
                        
                        var geocoder = new google.maps.Geocoder();
                        var latlng = new google.maps.LatLng(jsonResponse.Latitude, jsonResponse.Longitude);
                        geocoder.geocode({'latLng': latlng}, function(results, status) {
                          if (status === google.maps.GeocoderStatus.OK) {
                            if (results[0]) {
                                var htmlContent = '<div style="margin: -5px 15px 10px 10px;"><div class="row">' +
                                                    '<div class="col-sm-12"><strong><h3><span class="glyphicon glyphicon-map-marker" aria-hidden="true"></span> ' + jsonResponse.Outpost_Name + '</h3></strong></div>' + 
                                                    '<div class="col-sm-4"><strong>Description</strong></div><div class="col-sm-8">' + jsonResponse.Outpost_Description + '</div>' + 
                                                    '<div class="col-sm-4"><strong>Location</strong></div><div class="col-sm-8">' + results[0].formatted_address + '</div>' +
                                                    '</div> <div class="row pull-right">' +
                                                    '<a type="button" class="btn btn-xs btn-success" id="btn-edit-outpost"><span class="glyphicon glyphicon-edit"></span> Edit</a> ' +
                                                    '<a type="button" class="btn btn-xs btn-danger" id="btn-delete-outpost"><span class="glyphicon glyphicon-trash"></span> Delete</a>' +
                                                    '</div></div>';

                                markersInfoWindow.setContent(htmlContent);
                                markersInfoWindow.open(map, marker);
                                
                                $(document).ready(function () {
                                    $('#btn-edit-outpost').on('click', function(){
                                        showEditOutpostModal(outpostID);
                                    });
                                    $('#btn-delete-outpost').on('click', function(){
                                        showDeleteOutpostModal(outpostID);
                                    });
                                });
                                
                            } else {
                              alert('Geocoder error.');
                            }
                          } else {
                            alert('Geocoder failed due to: ' + status);
                          }
                        });
                       //selectorInfoWindow.close();
                       //selectorMarker.setMap(null);
                       //showMarkers(crimeMap);
                    }
                }
            );
            
            //infowindow.setContent(locations[i][0]);
            //infowindow.open(map, marker);
            
        };
    })(crimeMap, marker, item.Outpost_ID));
    
    return marker;
}


google.maps.event.addDomListener(window, 'load', initialize);
</script>

 
/*<link rel='shortcut icon' type='image/x-icon' href='/favicon.ico' />
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">*/
 // This example requires the Places library. Include the libraries=places
      // parameter when you first load the API. For example:
      // <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&libraries=places">


//console.log(document.getElementById('cameraData').innerHTML);
      // Convert php output (stored in id=allData) of "safety facilities locations" into javacript variable
      var cameraData = JSON.parse(document.getElementById('cameraData').innerHTML);
      var policeData = JSON.parse(document.getElementById('policeData').innerHTML);
      var taxiData = JSON.parse(document.getElementById('taxiData').innerHTML);


/*console.log(taxiData);*/
var visibleSafetyMarkers = ["cameraMarker","policeMarker","taxiMarker"];
/*console.log(visibleSafetyMarkers);*/
var toiletMarkerStatus = "show";

var markerGroups = {
    "cameraMarker": [],
        "policeMarker": [],
        "taxiMarker": [],
        "toiletMarker": []
};

var hazardMarkerGroup;

function toggleGroup(type) {
    for (var i = 0; i < markerGroups[type].length; i++) {
        var marker = markerGroups[type][i];
        if (!marker.getVisible()) {
            marker.setVisible(true);
        } else {
            marker.setVisible(false);
        }
    }

    if(type === "toiletMarker"){
        if(toiletMarkerStatus==="hide"){
          toiletMarkerStatus ="show";
        }
        else{
          toiletMarkerStatus="hide";
        }
        //console.log(toiletMarkerStatus);
    }
    else{
       var index = visibleSafetyMarkers.indexOf(type);

       if (index > -1) {
        visibleSafetyMarkers.splice(index, 1);
        }
       else {
          visibleSafetyMarkers.push(type);
        }
    }

    /*console.log(visibleSafetyMarkers);*/
}

 /*console.log(visibleSafetyMarkers.length);*/

function toggleSafetyMarkers(trueFalse) {
  /*console.log(trueFalse);*/
  for (var type in markerGroups) {
    var index = visibleSafetyMarkers.indexOf(type);
    if (index > -1) {
      for (var i = 0; i < markerGroups[type].length; i++) {
          var marker = markerGroups[type][i];
          marker.setVisible(trueFalse);
        }
    }
  }
}
function toggleToiletMarkers(trueFalse) {
  /*console.log(trueFalse);*/
if(toiletMarkerStatus === "show"){
   for (var i = 0; i < markerGroups["toiletMarker"].length; i++) {
          var marker = markerGroups["toiletMarker"][i];
          marker.setVisible(trueFalse);

        }
      } 
}


function geocodeLatLng(lat,lng) {
        var latlng = new google.maps.LatLng(lat, lng);
        geocoder.geocode({'location': latlng}, function(results, status) {
          if (status === 'OK') {
            if (results[0]) {
              infowindow.setContent(results[0].formatted_address);
              infowindow.open(map, hazardMarker);
            } else {
              infowindow.close();
            }
          } else {
            infowindow.close();
          }
        });
      }


var map;
var geocoder;
var infowindow

function initMap() {
   map = new google.maps.Map(document.getElementById('map'), {
    mapTypeControl: false,
    center: {lat: -37.8136, lng: 144.9631},
    streetViewControl: false,
    fullscreenControl: false,
    zoom: 16
  });

  geocoder = new google.maps.Geocoder;
  infowindow = new google.maps.InfoWindow;

  var cameraIcon = {
    path: "M17 10.5V7c0-.55-.45-1-1-1H4c-.55 0-1 .45-1 1v10c0 .55.45 1 1 1h12c.55 0 1-.45 1-1v-3.5l4 4v-11l-4 4z",
    fillColor: '#6600ff',
    fillOpacity: 1,
    background: '#ffffff',
    anchor: new google.maps.Point(0,0),
    strokeWeight: 0,
    scale: 0.7
} 

// Place "camera location" makers
Array.prototype.forEach.call(cameraData, function(data){
  var marker = new google.maps.Marker({
    position: new google.maps.LatLng(data.lat,data.lon),
    map: map,
    icon: cameraIcon,
    title: data.name
  })

    if (!markerGroups["cameraMarker"]) markerGroups["cameraMarker"] = [];
        markerGroups["cameraMarker"].push(marker);
})

     

var policeIcon = {
    path: "M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4zm0 10.99h7c-.53 4.12-3.28 7.79-7 8.94V12H5V6.3l7-3.11v8.8z",
    fillColor: '#993300',
    fillOpacity: 1,
    background: '#ffffff',
    anchor: new google.maps.Point(0,0),
    strokeWeight: 0,
    scale: 0.7
}  

Array.prototype.forEach.call(policeData, function(data){
  var marker = new google.maps.Marker({
    position: new google.maps.LatLng(data.lat,data.lon),
    map: map,
    icon: policeIcon,
    title: data.name
  })

    if (!markerGroups["policeMarker"]) markerGroups["policeMarker"] = [];
         markerGroups["policeMarker"].push(marker);
    })

// Place "taxi_rank location" makers
var taxiIcon = {
    path: "M18.92 6.01C18.72 5.42 18.16 5 17.5 5H15V3H9v2H6.5c-.66 0-1.21.42-1.42 1.01L3 12v8c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-1h12v1c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-8l-2.08-5.99zM6.5 16c-.83 0-1.5-.67-1.5-1.5S5.67 13 6.5 13s1.5.67 1.5 1.5S7.33 16 6.5 16zm11 0c-.83 0-1.5-.67-1.5-1.5s.67-1.5 1.5-1.5 1.5.67 1.5 1.5-.67 1.5-1.5 1.5zM5 11l1.5-4.5h11L19 11H5z",
    fillColor: '#FF3366',
    fillOpacity: 1,
    background: '#ffffff',
    anchor: new google.maps.Point(0,0),
    strokeWeight: 0,
    scale: 0.7
} 


Array.prototype.forEach.call(taxiData, function(data){
  var marker = new google.maps.Marker({
    position: new google.maps.LatLng(data.lat,data.lon),
    map: map,
    icon: taxiIcon
  })

    if (!markerGroups["taxiMarker"]) markerGroups["taxiMarker"] = [];
         markerGroups["taxiMarker"].push(marker);
})




new AutocompleteDirectionsHandler(map);
}

 /**
  * @constructor
  */
 function AutocompleteDirectionsHandler(map) {
    this.map = map;
    this.originPlaceId = null;
    this.destinationPlaceId = null;
    this.travelMode = 'WALKING';
    var originInput = document.getElementById('origin-input');
    //var destinationInput = document.getElementById('destination-input');
    var modeSelector = document.getElementById('mode-selector');
    this.directionsService = new google.maps.DirectionsService;
    this.directionsDisplay = new google.maps.DirectionsRenderer;
    this.directionsDisplay.setMap(map);
    var originAutocomplete = new google.maps.places.Autocomplete(
        originInput, {placeIdOnly: true});
    //var destinationAutocomplete = new google.maps.places.Autocomplete(
    //    destinationInput, {placeIdOnly: true});
    this.setupClickListener('changemode-walking', 'WALKING');
    this.setupClickListener('changemode-bicycling', 'BICYCLING');
    this.setupClickListener('changemode-transit', 'TRANSIT');
    this.setupClickListener('changemode-driving', 'DRIVING');
    this.setupPlaceChangedListener(originAutocomplete, 'ORIG');
  }

  // Sets a listener on a radio button to change the filter type on Places
  // Autocomplete.
  AutocompleteDirectionsHandler.prototype.setupClickListener = function(id, mode) {
    var radioButton = document.getElementById(id);
    var me = this;
    radioButton.addEventListener('click', function() {
      me.travelMode = mode;
      me.route();
    });
  };

  AutocompleteDirectionsHandler.prototype.setupPlaceChangedListener = function(autocomplete, mode) {
    var me = this;
    autocomplete.bindTo('bounds', this.map);
    autocomplete.addListener('place_changed', function() {
      var place = autocomplete.getPlace();
      if (!place.place_id) {
        window.alert("Please select an option from the dropdown list.");
        return;
      }
      if (mode === 'ORIG') {
        me.originPlaceId = place.place_id;
      } else {
        me.destinationPlaceId = place.place_id;
      }
      me.route();
    });

  };


  var eventCoords = [{lat:"-37.8136",lng:"144.9631"}];

  function updateEventCoords() {
    eventCoords =[{lat: eventlat, lng:eventlon}];
  }

      AutocompleteDirectionsHandler.prototype.route = function() {
        if (!this.originPlaceId /*|| !this.destinationPlaceId*/) {
          return;
        }
        var me = this;
        this.directionsService.route({
          origin: {'placeId': this.originPlaceId},
          destination: new google.maps.LatLng(eventCoords[0].lat, eventCoords[0].lng), 
          travelMode: this.travelMode
        }, function(response, status) {
          if (status === 'OK') {
            me.directionsDisplay.setDirections(response);
            writeDirectionsSteps(me.directionsDisplay.directions.routes[0].legs[0].steps);
          } else {
            window.alert('Directions request failed due to ' + status);
          }
        });
      };  
      
  /*Get directions for each type of transport*/
     function writeDirectionsSteps(steps) {
  var directions = document.getElementById('directionsOutput');
  directions.innerHTML = '';
  for (var i = 0; i < steps.length; i++) {
    directions.innerHTML += '<br/><br/>' + steps[i].instructions + '<br/>' + steps[i].distance.text;
    // if .transit property exists
    if (!!steps[i].transit) {
      directions.innerHTML += '<br/>' + steps[i].transit.arrival_stop.name;
    }
  }
} 


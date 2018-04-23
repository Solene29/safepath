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

var map;

function initMap() {
   map = new google.maps.Map(document.getElementById('map'), {
    mapTypeControl: false,
    center: {lat: -37.8136, lng: 144.9631},
    zoom: 13
  });

  var icon3 = {
    url: "http://maps.google.com/mapfiles/kml/paddle/grn-blank.png", // url
    scaledSize: new google.maps.Size(15,15), // scaled size
    origin: new google.maps.Point(0,0), // origin
    anchor: new google.maps.Point(0, 0) // anchor
};

// Place "camera location" makers
Array.prototype.forEach.call(cameraData, function(data){
  var marker = new google.maps.Marker({
    position: new google.maps.LatLng(data.lat,data.lon),
    map: map,
    icon: icon3
  })

    if (!markerGroups["cameraMarker"]) markerGroups["cameraMarker"] = [];
        markerGroups["cameraMarker"].push(marker);
})


var icon = {
    url: "http://maps.google.com/mapfiles/kml/paddle/purple-blank.png", // url
    scaledSize: new google.maps.Size(15,15), // scaled size
    origin: new google.maps.Point(0,0), // origin
     anchor: new google.maps.Point(0, 0) // anchor
};
       
/*<img style="-webkit-user-select: none;" src="https://maps.gstatic.com/intl/en_us/mapfiles/markers2/measle_blue.png">*/

Array.prototype.forEach.call(policeData, function(data){
  var marker = new google.maps.Marker({
    position: new google.maps.LatLng(data.lat,data.lon),
    map: map,
    icon: icon,
    title: data.name
  })

    if (!markerGroups["policeMarker"]) markerGroups["policeMarker"] = [];
         markerGroups["policeMarker"].push(marker);
    })

// Place "taxi_rank location" makers
var iconTaxi = {
  url: "http://maps.google.com/mapfiles/kml/paddle/pink-blank.png", // url
  scaledSize: new google.maps.Size(15,15), // scaled size
  origin: new google.maps.Point(0,0), // origin
  anchor: new google.maps.Point(0, 0) // anchor
};

Array.prototype.forEach.call(taxiData, function(data){
  var marker = new google.maps.Marker({
    position: new google.maps.LatLng(data.lat,data.lon),
    map: map,
    icon: iconTaxi 
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
    console.log([{lat: eventlat, lng:eventlon}]);
    eventCoords =[{lat: eventlat, lng:eventlon}];
    console.log(eventCoords);
    //eventCoords = JSON.parse([{lat: eventlat[0],lng:eventlon[0]}]);
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


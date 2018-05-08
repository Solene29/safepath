
var hazardMarkers = {"20":[],"40":[],"60":[],"80":[],"100":[],"120":[]};
var hazardLines = {"20":[],"40":[],"60":[],"80":[],"100":[],"120":[]};

//var placeIdArray = [];
//var polylines = [];
var snappedCoordinates = [];


function showCurrentHazards(sliderVal){
	//console.log(timeVal);
	for(var i=sliderVal; i<=6; i++){
		var time = 140-i*20;
		//console.log(time+" show");
		//console.log("time="+time, "harzardMarkers[time]=");
		//console.log(hazardMarkers[time]);
		//console.log("time="+time, "harzardLines[time]=");
		//console.log(hazardLines[time]);
		for(pinpointID in hazardMarkers[time] ){
			var hazardLine = hazardLines[time][pinpointID];
			console.log(hazardLine.strokeColor);
			hazardLine.setVisible(true);
			hazardMarkers[time][pinpointID].setVisible(true);
			switch (numberOfNearbyHazards(sliderVal,hazardMarkers[time][pinpointID])) {
            case 1:
            	console.log(hazardLine);
                hazardLine.setOptions({strokeColor: 'yellow'}); 
                break;
            case 2:
            	console.log(hazardLine);
                hazardLine.setOptions({strokeColor: 'orange'});
                break;
            default:
            	console.log(hazardLine);
                hazardLine.setOptions({strokeColor: 'red'});
                break;
            }

		}
	}
	for(var i= 1; i< sliderVal; i++){
		var time = 140-i*20;
		//console.log(time+" hide");
		for(pinpointID in hazardMarkers[time] ){
			hazardLines[time][pinpointID].setVisible(false);
			hazardMarkers[time][pinpointID].setVisible(false);
		}
	}
}

function getHarzardStatus(timeVal,sliderVal,marker){

	var trueFalse;
	var color;
	var time = 140-timeVal*20;
	if(timeVal >= sliderVal){
		trueFalse = true;
		switch (numberOfNearbyHazards(sliderVal,marker)) {
            case 1:
                color = 'yellow'; 
                break;
            case 2:
                color = 'orange';
                break;
            default:
                color = 'red' ;
                break;
        };
	}
	else{
		trueFalse = false;
		color = 'red' ;
	}	

	return [trueFalse,color,numberOfNearbyHazards(sliderVal,marker)];
}

function numberOfNearbyHazards(sliderVal,marker){
	var num = 0;
	for(var i = sliderVal; i<=6; i++){
		var time = 140-i*20;
		//console.log(time,hazardMarkers[time].length);
		for(pinpointID in hazardMarkers[time] ){
			if(distanceBetweenCoords(	hazardMarkers[time][pinpointID].getPosition().lat(),
												hazardMarkers[time][pinpointID].getPosition().lng(),
												marker.getPosition().lat(),
												marker.getPosition().lng()
												) < 0.1 ){
				//console.log(hazardMarkers[time][pinpointID].getTitle());
				num++;
			}
		}
	}
	console.log(num +"," + sliderVal);
	return num;
}

function distanceBetweenCoords(lat1,lon1,lat2,lon2){
	return 6371*2*3.141596536/360*Math.sqrt(Math.pow(lat1-lat2,2)+Math.pow(lon1-lon2,2));
}


function runSnapToRoad(path,marker,timePast,pinpointID) {
  //var pathValues = [];
  //for (var i = 0; i < path.length; i++) {
  //  pathValues.push(path.getAt(i).toUrlValue());
  //}
  //console.log(path.join('|'));
  $.get('https://roads.googleapis.com/v1/snapToRoads?path='+path, {
    interpolate: true,
    key: 'AIzaSyBvo9WExDGqsikBGfsqvdP0mHGGBDh79iE'
  }, function(data) {
    processSnapToRoadResponse(data);
    drawSnappedPolyline(timePast,marker,pinpointID);
    //getAndDrawSpeedLimits();
  });
}

function processSnapToRoadResponse(data) {
  snappedCoordinates = [];
  placeIdArray = [];
  for (var i = 0; i < data.snappedPoints.length; i++) {
    var latlng = new google.maps.LatLng(
        data.snappedPoints[i].location.latitude,
        data.snappedPoints[i].location.longitude);
    snappedCoordinates.push(latlng);
    //placeIdArray.push(data.snappedPoints[i].placeId);
  }
}

function drawSnappedPolyline(timePast,marker,pinpointID) {
  var timeVal = (140-timePast)/20;
  var sliderVal = document.getElementById("myRange").value;
  var options = getHarzardStatus(timeVal,sliderVal,marker);
  
  console.log(options);

  //var numNearby = numberOfNearbyHazards(timeVal,marker);
  //console.log(numNearby);
  var snappedPolyline = new google.maps.Polyline({
    path: snappedCoordinates,
    strokeColor: options[1],
    strokeWeight: 7,
    visible: options[0]
  });

  snappedPolyline.setMap(map);
  hazardLines[timePast][pinpointID] = snappedPolyline;
}
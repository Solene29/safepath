
var hazardMarkers = {"20":[],"40":[],"60":[],"80":[],"100":[],"120":[]};
var hazardLines = {"20":[],"40":[],"60":[],"80":[],"100":[],"120":[]};

//var placeIdArray = [];
//var polylines = [];
var snappedCoordinates = [];


function showCurrentHazards(timeVal){
	//console.log(timeVal);
	for(var i=timeVal; i<=6; i++){
		var time = 140-i*20;
		//console.log(time+" show");
		console.log(hazardLines[time]);
		for(var j =0; j <hazardLines[time].length; j++){
			hazardLines[time][j].setVisible(true);
			hazardMarkers[time][j].setVisible(true);
			switch (numberOfNearbyHazards(timeVal,hazardMarkers[time][j])) {
            case 1:
                hazardLines[time][j].setOptions({strokeColor: 'yellow'}); 
                break;
            case 2:
                hazardLines[time][j].setOptions({strokeColor: 'orange'});
                break;
            default:
                hazardLines[time][j].setOptions({strokeColor: 'red'});
                break;
            }

		}
	}
	for(var i= 1; i< timeVal; i++){
		var time = 140-i*20;
		//console.log(time+" hide");
		for(var j =0; j <hazardLines[time].length; j++){
			hazardLines[time][j].setVisible(false);
			hazardMarkers[time][j].setVisible(false);
		}
	}
}

function numberOfNearbyHazards(timeVal,marker){
	var num = 0;
	for(var i = timeVal; i<=6; i++){
		var time = 140-i*20;
		for(var j =0; j <hazardLines[time].length; j++){
			if(distanceBetweenCoords(	hazardMarkers[time][j].getPosition().lat(),
												hazardMarkers[time][j].getPosition().lng(),
												marker.getPosition().lat(),
												marker.getPosition().lng()
												) < 0.1 ){
				console.log(hazardMarkers[time][j].getTitle());
				num++;
			}
		}
	}
	console.log(num +"," + timeVal);
	return num;
}

function distanceBetweenCoords(lat1,lon1,lat2,lon2){
	return 6371*2*3.141596536/360*Math.sqrt(Math.pow(lat1-lat2,2)+Math.pow(lon1-lon2,2));
}


function runSnapToRoad(path,timePast) {
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
    drawSnappedPolyline(timePast);
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

function drawSnappedPolyline(timePast) {
  var snappedPolyline = new google.maps.Polyline({
    path: snappedCoordinates,
    strokeColor: 'black',
    strokeWeight: 7,
    visible: trueFalse
  });

  snappedPolyline.setMap(map);
  hazardLines[timePast].push(snappedPolyline);
}
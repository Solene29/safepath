
var hazardMarkers = {"20":[],"40":[],"60":[],"80":[],"100":[],"120":[]};
var hazardLines = {"20":[],"40":[],"60":[],"80":[],"100":[],"120":[]};

//var placeIdArray = [];
//var polylines = [];
var snappedCoordinates = [];
//var pathCoordinates = [];





function compareNewAndOldHazards(currentHazardData){
  var newHazardData = currentHazardData;
  //console.log(newHazardData);
  for(var i=1; i<=6; i++){
    var time = (140-i*20).toString();
    for(pinpointID in hazardMarkers[time] ){
      var output = getUpdatedTimeForHazard(pinpointID,currentHazardData);
      var newTime = output[0].toString();
      var index = output[1]; 
      //console.log("pinpoint= " + pinpointID + ", newTime =" + newTime +"index=" + index);

      if(newTime === time ){  // Remove hazards from "new" list that haven't changed the "timePast".
        //console.log("here");
        newHazardData.splice(index,1);   
        //console.log(newHazardData);
      }
      else if(newTime === "too old"){   // Remove old hazards from "old".
        hazardMarkers[time][pinpointID].setMap(null);  //remove marker from map
        var j = hazardMarkers[time].indexOf(pinpointID);  
        hazardMarkers[time].splice(j,1);  //remove marker from list

        for(var k = 0; k < hazardLines[time][pinpointID].length; k++){
          hazardLines[time][pinpointID][k].setMap(null);    //remove hazardLines from map.
        }
        j = hazardMarkers[time].indexOf(pinpointID);
        hazardLines[time].splice(j,1);  //remove hazardLines from list
      }
      else{ // These are the the hazards that have changed from one "timePast" to another. eg "20" -> "40"
        hazardMarkers[newTime][pinpointID] = hazardMarkers[time][pinpointID];  //update marke rlist
        var j = hazardMarkers[time].indexOf(pinpointID);  
        hazardMarkers[time].splice(j,1);  //remove old entry from marker list

        hazardLines[newTime][pinpointID] = hazardLines[time][pinpointID];  //update line list
        var j = hazardMarkers[time].indexOf(pinpointID);  
        hazardLines[time].splice(j,1);  //remove old entry from line list

        newHazardData.splice(index,1); // Remove hazards from list of "new" hazards.
      }

      showCurrentHazards(document.getElementById("myRange").value);

    }
  }

  return newHazardData;
}

function getUpdatedTimeForHazard(currentPinpointID,currentHazardData){
  var newTime = "too old";
  var index = "none";
  var j=0;
  Array.prototype.forEach.call(currentHazardData, function(data){
              //console.log(data.pinpointID.toString(),currentPinpointID);
              //console.log(data.pinpointID.toString() === currentPinpointID);
              if(data.pinpointID.toString() === currentPinpointID){
                newTime = data.timePast;
                index = j;
              }

            });
  return [newTime,index];
}

function createHazardMarkers(serverData){
  Array.prototype.forEach.call(serverData, function(data){
                var marker = new google.maps.Marker({
                  position: new google.maps.LatLng(data.lat,data.lon),
                  map: map,
                  title: data.description,
                  visible: false,
                  id: data.pinpointID
                });
              //console.log(data.timePast + "," + data.pinpointID);
              var t = data.timePast.toString();
              var pin = data.pinpointID;
              if(hazardMarkers[t] == null){
                hazardMarkers[t] = [];
              }
              hazardMarkers[t][pin] = marker;
            });
}


function hideAllHazards(){
    for(var i=1; i<=6; i++){
      var time = (140-i*20).toString();
      for(pinpointID in hazardMarkers[time] ){
        var hazardPaths = hazardLines[time][pinpointID];
        //console.log(hazardPaths);
        for(var j = 0; j < hazardPaths.length; j++) {
           hazardPaths[j].setVisible(false);
        };
      };
    };
}


function showCurrentHazards(sliderVal){
  //console.log(hazardMarkers["80"]);
  //console.log(hazardLines["60"]);
	//console.log(timeVal);
	for(var i=sliderVal; i<=6; i++){
		var time = (140-i*20).toString();
		for(pinpointID in hazardMarkers[time] ){
			var hazardPaths = hazardLines[time][pinpointID];
			//console.log(hazardPaths);
      for(var j = 0; j < hazardPaths.length; j++) {
         
         if(hazardPaths[j] == null){
          console.log(hazardPaths);
          console.log("time = ");
          console.log(time);
          console.log("j= ");
          console.log( j  );
          console.log(hazardPaths[j]);
         }

			   hazardPaths[j].setVisible(true);
       }
			//hazardMarkers[time][pinpointID].setVisible(true);
			switch (numberOfNearbyHazards(sliderVal,hazardMarkers[time][pinpointID])) {
            case 1:
            	//console.log(hazardPaths);
                for(var j = 0; j < hazardPaths.length; j++) 
                  hazardPaths[j].setOptions({strokeColor: 'yellow'}); 
                break;
            case 2:
            	//console.log(hazardPaths);
                for(var j = 0; j < hazardPaths.length; j++) 
                  hazardPaths[j].setOptions({strokeColor: 'orange'});
                break;
            default:
            	//console.log(hazardPaths);
                for(var j = 0; j < hazardPaths.length; j++) 
                  hazardPaths[j].setOptions({strokeColor: 'red'});
                break;
            }

		}
	}
	for(var i= 1; i< sliderVal; i++){
		var time = (140-i*20).toString();
		//console.log(time+" hide");
		for(pinpointID in hazardMarkers[time] ){
      var hazardPaths = hazardLines[time][pinpointID];
      for(var j = 0; j < hazardPaths.length; j++) 
         hazardPaths[j].setVisible(false);
			//hazardMarkers[time][pinpointID].setVisible(false);
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
												) < 0.05 ){
				//console.log(hazardMarkers[time][pinpointID].getTitle());
				num++;
			}
		}
	}
	//console.log(num +"," + sliderVal);
	return num;
}


function numberAndTypeOfNearbyHazards(lat,lng){
  var types = ["harrassment","trafficAccident","aggression","illegalActivity"];
  var counts = [0,0,0,0];
  var sliderVal = document.getElementById("myRange").value;

  for(var i = sliderVal; i<=6; i++){
    var time = 140-i*20;
    //console.log(time,hazardMarkers[time].length);
    for(pinpointID in hazardMarkers[time] ){
      if(distanceBetweenCoords( hazardMarkers[time][pinpointID].getPosition().lat(),
                        hazardMarkers[time][pinpointID].getPosition().lng(),
                        lat,
                        lng
                        ) < 0.05 ){
        var type = hazardMarkers[time][pinpointID].getTitle();
        var j = types.indexOf(type);
        //console.log(type);
        //console.log(j);
        counts[j] = counts[j] + 1;
      }
    }
  }
  //console.log(num +"," + sliderVal);
  return counts;
}



function distanceBetweenCoords(lat1,lon1,lat2,lon2){
	return Math.sqrt(Math.pow(111*(lat1-lat2),2)+Math.pow(85*(lon1-lon2),2));
}






function processPolyline(rawPathData,marker,timePast,pinpointID){
  
  //console.log(rawPathData);
  var paths = rawPathData.split("#");



  hazardLines[timePast][pinpointID]=[];


  for(var j = 0; j < paths.length; j++){

    var points = paths[j].split("|");

    snappedCoordinates = [];

    for (var i = 0; i < points.length; i++) {

      var latlngraw = points[i].split(",");
      var lat = (-37.84 - parseFloat(latlngraw[0])).toPrecision(7);
      var lng = (145 - parseFloat(latlngraw[1])).toPrecision(8);
      var latlng = new google.maps.LatLng(lat,lng);
      //console.log(lat +  "," + lng);
      snappedCoordinates.push(latlng);

    }
   
   
   //runSnapToRoad(newPath,marker,timePast,pinpointID,j)

    drawSnappedPolyline(timePast,marker,pinpointID,j)
    //console.log(pathCoordinates);
  }
//console.log(hazardLines[timePast][pinpointID]);
}














function processPolylineWithSnap(rawPathData,marker,timePast,pinpointID){
  
  //console.log(rawPathData);
  var paths = rawPathData.split("#");

  hazardLines[timePast][pinpointID]=[];


  for(var j = 0; j < paths.length; j++){

    var points = paths[j].split("|");

    //snappedCoordinates = [];
    var newPath = "";
    for (var i = 0; i < points.length; i++) {
      if(i!=0)
        newPath+= "|";
      var latlngraw = points[i].split(",");
      var lat = (-37.84 - parseFloat(latlngraw[0])).toPrecision(7);
      var lng = (145 - parseFloat(latlngraw[1])).toPrecision(8);
      //var latlng = new google.maps.LatLng(lat,lng);
      //console.log(lat +  "," + lng);
      //snappedCoordinates.push(latlng);
      newPath += lat + "," + lng;
    }
   
   
   runSnapToRoad(newPath,marker,timePast,pinpointID,j)

    //drawSnappedPolyline(timePast,marker,pinpointID,j)
    //console.log(pathCoordinates);
  }
//console.log(hazardLines[timePast][pinpointID]);
}



function runSnapToRoad(path,marker,timePast,pinpointID,index) {
  //var pathValues = [];
  //for (var i = 0; i < path.length; i++) {
  //  pathValues.push(path.getAt(i).toUrlValue());
  //}
  //console.log(path.join('|'));
  //console.log(path);
  $.get('https://roads.googleapis.com/v1/snapToRoads?path='+path, {
    interpolate: true,
    key: 'AIzaSyBqNkm0anMe79zfctQcyc2tisZpKIxC0SQ'
  }, function(data) {
    processSnapToRoadResponse(data);
    drawSnappedPolyline(timePast,marker,pinpointID,index);
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


function drawSnappedPolyline(timePast,marker,pinpointID,index) {
  var timeVal = (140-timePast)/20;
  var sliderVal = document.getElementById("myRange").value;
  var options = getHarzardStatus(timeVal,sliderVal,marker);
  
  //console.log(options);

  //var numNearby = numberOfNearbyHazards(timeVal,marker);
  //console.log(numNearby);
  var snappedPolyline = new google.maps.Polyline({
    path: snappedCoordinates,
    strokeColor: options[1],
    strokeWeight: 7,
    visible: options[0]
  });


  snappedPolyline.setMap(map);
  hazardLines[timePast][pinpointID][index] = snappedPolyline;

  addClickListener(pinpointID,snappedPolyline,marker);
}


function addClickListener(pinpointID,line,marker){
  var position = marker.getPosition();
  new google.maps.event.addListener(line, 'click', function(event) {
    var lat = position.lat();
    var lng = position.lng();
    var counts = numberAndTypeOfNearbyHazards(lat,lng);
    var modalContentString = createHazardModalContent(counts);

    alertModal(modalContentString);

  });
}



function createHazardModalContent(counts){
    var s = "";
    var plural = "";
    if(counts[0]!=0)
      s += "<p> Harrassment: " + counts[0] +"</p>";
    if(counts[1]!=0){
      if(counts[1]>1)
        plural = "s";
      s += "<p> Traffic Accident" + plural + ": " + counts[1] +"</p>";
    }
    if(counts[2]!=0)
      s += "<p> Aggression: " + counts[2] +"</p>";
    if(counts[3]!=0){
      var plural = "y";
      if(counts[1]>1)
        plural = "ies"
      s += "<p> Illegal Activit" + plural + ": " + counts[3] +"</p>";
    }

      return s;
}



            /*
              paths = data.polycoords.split("#");
              Array.prototype.forEach.call(paths, function(path){
                points = path.split("|");

                Array.prototype.forEach.call(points, function(point){
                  var latlngraw = point.split(",");
                    var lat = (-37.84 - parseFloat(latlngraw[0])).toPrecision(7);
                    var lng = (145 - parseFloat(latlngraw[1])).toPrecision(8);
                  //console.log(lat + "," + lng);
                  var marker = new google.maps.Marker({
                    position: new google.maps.LatLng(lat,lng),
                    map: map,
                    visible: true
                  });
                });
              });
              */


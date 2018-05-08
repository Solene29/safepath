<?php

function getUserData(){

$servername = "localhost";
$username = "client";
$password = "password";
$dbname = "safepath";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

// Fetch camera data 
$sqlHazard = "	SELECT pinpointID, description, dt, ceil(TIME_TO_SEC(TIMEDIFF(CURRENT_TIMESTAMP,dt))/60/20)*20 as timePast, lat, lon, innerlat1, innerlon1, innerlat2, innerlon2, outerlat1, outerlon1, outerlat2, outerlon2 
				FROM pinpoint
				HAVING timePast < 140	";

$result = $conn->query($sqlHazard);

if (mysqli_num_rows($result)>0 ) {
// Convert data to correct format for json_encode
while($row = mysqli_fetch_assoc($result))
    $result1[] = $row; 

// Convert data to format for Javascript
$result = json_encode($result1,JSON_NUMERIC_CHECK);
//$result1 = str_replace('"','\"',$result);

//echo '<div id="userData">' . $result . '</div>';
echo '<script  language="javascript">';
echo 'hazardData =' . $result .';';
echo 'console.log(hazardData);';
/*echo 'for (var i = 0; i < markerGroups["hazardMarker"].length; i++) {
          var marker = markerGroups["hazardMarker"][i];
          marker.setMap(null);
      };';
//echo 'markerCluster.clearMarkers();';
echo 'markerGroups["hazardMarker"] = [];';*/
echo 'async function asyncCall() {
	  console.log("calling");
	  var result = await Array.prototype.forEach.call(hazardData, function(data){
							  var marker = new google.maps.Marker({
							    position: new google.maps.LatLng(data.lat,data.lon),
							    map: map,
							    title: data.description,
							    visible: false,
							    id: data.pinpointID
							  })

							hazardMarkers[data.timePast][data.pinpointID] = marker;
						});
		console.log(hazardMarkers);
		//var count = 0;
		Array.prototype.forEach.call(hazardData, function(data){
							var temp = data.outerlat2 + "," + data.outerlon2 + "|" + data.innerlat2 + "," + data.innerlon2 + "|" + data.lat + "," + data.lon + "|" + data.innerlat1 + "," + data.innerlon1 + "|" + data.outerlat1 + "," + data.outerlon1;
							//console.log(temp);

							runSnapToRoad(temp,hazardMarkers[data.timePast][data.pinpointID],data.timePast,data.pinpointID);
							//count++;
						});
	  //console.log("done waiting");
	  //console.log(hazardMarkers);
	  //console.log(document.getElementById("myRange").value);
	  //showCurrentHazards(document.getElementById("myRange").value);
	  }';
echo 'asyncCall();';
//echo 'markerCluster.addMarkers(markerGroups["hazardMarker"]);';
//echo 'markerCluster.redraw();';*/
echo '</script>';

}





$conn->close();
}


getUserData();
?> 

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
$sqlHazard = "	SELECT pinpointID, description, dt, ceil(TIME_TO_SEC(TIMEDIFF(CURRENT_TIMESTAMP,dt))/60/20)*20 as timePast, lat, lon, polycoords
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
//echo '//console.log(hazardData);';
echo 'async function asyncCall(newHazardData) {
		
		var result = await createHazardMarkers(newHazardData);

		Array.prototype.forEach.call(newHazardData, function(data){

				processPolyline(data.polycoords,hazardMarkers[data.timePast.toString()][data.pinpointID],data.timePast.toString(),data.pinpointID.toString());
							
				});
	  }';
echo 'asyncCall(compareNewAndOldHazards(hazardData));';
//echo 'markerCluster.addMarkers(markerGroups["hazardMarker"]);';
//echo 'markerCluster.redraw();';*/
echo '</script>';

}





$conn->close();
}


getUserData();
?> 

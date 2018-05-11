

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

echo '<script  language="javascript">';
echo 'newHazardData =' . $result .';';
echo '//console.log(newHazardData);';
echo 'async function asyncCall() {
	  var result = await compareNewAndOldHazards();

	  showCurrentHazards(document.getElementById("myRange").value);


	  };';






echo 'asyncCall();';
echo '</script>';

}





$conn->close();
}


getUserData();

?> 

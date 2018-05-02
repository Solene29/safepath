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
$sqlHazard = "SELECT description, dt, lat, lon FROM pinpoint HAVING minute(TIMEDIFF(dt,CURRENT_TIMESTAMP))<30 and hour(TIMEDIFF(dt,CURRENT_TIMESTAMP))=0";

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
echo 'hazardMarkerGroup = [];';
echo '	Array.prototype.forEach.call(hazardData, function(data){
			  var marker = new google.maps.Marker({
			    position: new google.maps.LatLng(data.lat,data.lon),
			    map: map,
			    title: data.description
			  })

		
			hazardMarkerGroup.push(marker);
		});';
//echo '$("#userData").html("'.$result1.'")';
echo '</script>';

}





$conn->close();
}


getUserData();
?> 

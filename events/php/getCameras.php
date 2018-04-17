<?php

function getCameraData(){

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
$sql = "SELECT camID, lat, lon FROM cctv";
$result = $conn->query($sql);

// Convert data to correct format for json_encode
while($row = mysqli_fetch_assoc($result))
    $test[] = $row; 

// Convert data to format for Javascript
$result = json_encode($test,true);
echo '<div id="cameraData">' . $result . '</div>';


$conn->close();
}


getCameraData();
?> 

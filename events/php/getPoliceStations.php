<?php

function getPoliceData(){

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

// Fetch police data
$sql = "SELECT psID, name, lat, lon FROM police_station";
$result = $conn->query($sql);

// Convert data to correct format for json_encode
while($row = mysqli_fetch_assoc($result))
    $test[] = $row; 

// Convert data to format for Javascript
$result = json_encode($test,true);
echo '<div id="policeData">' . $result . '</div>';


$conn->close();
}


getPoliceData();
?> 

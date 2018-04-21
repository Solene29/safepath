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
$sqlUser = "SELECT description, count(userip) as total FROM user_input_data WHERE eventid = '" . $_GET["eventIdValue"] . "' GROUP BY description" ; 
$result = $conn->query($sqlUser);

$sqlIp = "SELECT count(distinct userip) as totalIP FROM user_input_data WHERE eventid = ". $_GET["eventIdValue"];
$ip = $conn->query($sqlIp);

// Convert data to correct format for json_encode
while($row = mysqli_fetch_assoc($result))
    $result1[] = $row; 
while($row = mysqli_fetch_assoc($ip))
    $ip1[] = $row;


// Convert data to format for Javascript
$result = json_encode($result1,JSON_NUMERIC_CHECK);
$result1 = str_replace('"','\"',$result);
$ip = json_encode($ip1,JSON_NUMERIC_CHECK);
$ip1 = str_replace('"','\"',$ip);

//echo '<div id="userData">' . $result . '</div>';
echo '<script  language="javascript">';
echo '$("#userData").html("'.$result1.'")';
echo '</script>';
echo '<script  language="javascript">';
echo '$("#ipData").html("'.$ip1.'")';
echo '</script>';






$conn->close();
}


getUserData();
?> 

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
$sqlUser = "SELECT T03.description, ROUND(100*(count(T03.userip)/T03.TotalIP),0) as descPercentage
			FROM (	SELECT T01.*, T02.totalIP as totalIP
					FROM user_input_data T01
					INNER JOIN (SELECT eventid, count(distinct userip) as totalIP 
								FROM user_input_data
								WHERE eventid = '" .  $_GET["eventIdValue"] . "') T02
					ON T01.eventid = T02.eventid
     			) T03
			GROUP BY T03.description" ; 
$sqlIp = "SELECT count(distinct userip) as totalIP FROM user_input_data WHERE eventid = ". $_GET["eventIdValue"];

$result = $conn->query($sqlUser);
$ip = $conn->query($sqlIp);

if (mysqli_num_rows($result)>0 ) {
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
}





$conn->close();
}


getUserData();
?> 

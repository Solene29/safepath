
<?php


function sendInput(){

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

$IP = $_SERVER['REMOTE_ADDR'];

$sql = "INSERT INTO user_input_data(eventid, description, userip) values('" .  $_GET["eventIdValue"] . "', '" . $_GET["description"] . "' , '" . $IP . "')";

if ($conn->query($sql) === TRUE) {
   // echo "New record created successfully";
    echo '<script language="javascript">';
	echo 'alertModal("Your input has been recorded")';
	echo '</script>';
} else {
        echo '<script language="javascript">';
	echo 'alertModal("Your input has already been recorded")';
	echo '</script>';
}

$conn->close();
}


sendInput();

?> 

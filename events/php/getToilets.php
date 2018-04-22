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
$sql = "SELECT *, ROUND(6371*2*3.141596536/360*sqrt(POW(" .  $_GET["eventLatValue"] . " -lat,2)+POW(" .  $_GET["eventLonValue"] . " -lon,2)),3) as distance
FROM toilet 
WHERE Lat > -37.9035 and Lat < -37.7237 and Lon < 145.053 and Lon > 144.873 and No_Access = \"FALSE\"
HAVING distance <= 0.5";
$result = $conn->query($sql);

if (mysqli_num_rows($result)>0 ) {

// Convert data to correct format for json_encode
while($row = mysqli_fetch_assoc($result))
    $test[] = $row; 


// Convert data to format for Javascript
$result = json_encode($test,JSON_NUMERIC_CHECK);
$result1 = str_replace('"','\"',$result);
echo '<script  language="javascript">';
echo '$("#toiletData").html("'.$result1.'")';
echo '</script>';

echo '<script  language="javascript">
function addToiletMarkers() {

  var toiletData = JSON.parse(document.getElementById(\'toiletData\').innerHTML);
        console.log(toiletData);

        var toiletIcon = {
          url: "http://maps.google.com/mapfiles/kml/shapes/toilets.png", // url
          scaledSize: new google.maps.Size(15,15), // scaled size
          origin: new google.maps.Point(0,0), // origin
          anchor: new google.maps.Point(0, 0) // anchor
        };

        Array.prototype.forEach.call(toiletData, function(data){
          //console.log(data.Lat);
        var marker = new google.maps.Marker({
          position: new google.maps.LatLng(data.Lat,data.Lon),
          map: map,
          icon: toiletIcon
        })

        if (!markerGroups["toiletMarker"]) markerGroups["toiletMarker"] = [];
          markerGroups["toiletMarker"].push(marker);

      })
};

addToiletMarkers();
toggleGroup(\'toiletMarker\');
toggleSafetyMarkers(false);
toggleToiletMarkers(false);
</script>';
}

else {
echo '<script language="javascript">';
echo 'alert("Unfortunately, no toilets were found within 500m of this event.")';
echo '</script>';
}

$conn->close();
}


getCameraData();
?> 

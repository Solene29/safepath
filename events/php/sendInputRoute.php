
<?php


function snapToRoad($LAT,$LON){

		$URL = "https://roads.googleapis.com/v1/snapToRoads?path=" . $LAT . "," . $LON . "&key=AIzaSyBvo9WExDGqsikBGfsqvdP0mHGGBDh79iE";
		$curl = curl_init();
		curl_setopt_array($curl, array(
		  CURLOPT_URL => $URL,
		  CURLOPT_RETURNTRANSFER => true,
		));

		$response = curl_exec($curl);
		$response = json_decode($response, true);
		$response = array_filter($response);

		if(!empty($response)){
    		return [ $response["snappedPoints"][0]["location"]["latitude"] , $response["snappedPoints"][0]["location"]["longitude"] ];
		}
		else{
			return $response;
		}
}

function getRoad($LAT,$LON){

		$URL = "http://35.197.185.129/nominatim/reverse?format=json&lat=" . $LAT . "&lon=" . $LON;
		$curl = curl_init();
		curl_setopt_array($curl, array(
		  CURLOPT_URL => $URL,
		  CURLOPT_RETURNTRANSFER => true,
		));

		$response = curl_exec($curl);
		$response = json_decode($response, true);
		$response = array_filter($response);

		if(!empty($response)){
			if(!empty($response['address']['road']) && !is_null($response['address']['road'])){
				return $response['address']['road'];
			}
			elseif(!empty($response['address']['pedestrian']) && !is_null($response['address']['pedestrian'])){
				if($response['address']['pedestrian'] == 'Bourke Street Mall'){
					return 'Bourke Street';
				}
				else{
					return $response['address']['pedestrian'];
				}
			}
		}
}

function distPair($Point1,$Point2){
	return sqrt(pow($Point1[0]-$Point2[0],2)+pow($Point1[1]-$Point2[1],2));
}

function iteration($LAT,$LON,$hazardLat,$hazardLon,$hazardRoad,$num){
	$kmToCoordFactor = 360/(6371*2*3.141596536);
	$points = array();

	for($t = 0; $t <= 7; $t++){

		$unsnappedLat = $LAT - $kmToCoordFactor*0.015*sin($t*3.141596536/4);
    	$unsnappedLon = $LON - $kmToCoordFactor*0.015*cos($t*3.141596536/4);

    	$point = snapToRoad($unsnappedLat,$unsnappedLon);

      	if(!empty($point)){
      		$road = getRoad($point[0],$point[1]);
      		//var_dump($road);
      		if($road == $hazardRoad){
      			array_push($points,$point);
      		}
      	}
	}

	//var_dump($points);

	$numOfPoints = sizeof($points);
	$max_dist = 0;
	if($num == 1  && $numOfPoints > 1){
		$max_pair = 0;
		for($i = 0; $i < $numOfPoints; $i++){
			for( $j = 0; $j< $numOfPoints; $j++){
				if($i>$j){
					if(distPair($points[$i],$points[$j])>$max_dist){
						$max_dist = distPair($points[$i],$points[$j]);
						$max_pair = [$points[$i],$points[$j]];
					}
				}
			}
		}
		return $max_pair;
	}
	else{
		$max_point = 0;
		for($i = 0; $i < $numOfPoints; $i++){
			if(distPair($points[$i],[$hazardLat,$hazardLon])>$max_dist){
				$max_dist = distPair($points[$i],[$hazardLat,$hazardLon]);
				$max_point = $points[$i];
			}		
		}
		return $max_point;
	}
	
}


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

$hazardLat = $_GET["lat"];// -37.813599996508;
$hazardLon = $_GET["lon"];// 144.96489864099;
$hazardRoad = getRoad($hazardLat,$hazardLon);




$InnerPair = iteration($hazardLat,$hazardLon,$hazardLat,$hazardLon,$hazardRoad,1);
//var_dump($FirstPair);
$Outer1 = iteration($InnerPair[0][0],$InnerPair[0][1],$hazardLat,$hazardLon,$hazardRoad,2);
//var_dump($ThirdPoint);
if(sizeof($InnerPair)>1){
	$Outer2 = iteration($InnerPair[1][0],$InnerPair[1][1],$hazardLat,$hazardLon,$hazardRoad,2);
	//var_dump($FourthPoint);
}
else{
	$InnerPair = [$InnerPair,[$hazardLat,$hazardLon]];
	$Outer2 = [$hazardLat,$hazardLon];

}

  

$IP = $_SERVER['REMOTE_ADDR'];


$sql = "INSERT INTO pinpoint(eventID, description, userip, lat, lon, innerlat1,innerlon1, innerlat2,innerlon2,outerlat1,outerlon1,outerlat2,outerlon2) values('" . $_GET["eventIdValue"]  . "', '" . $_GET["description"] . "' , '" . $IP . "' , '". $hazardLat ."' , '". $hazardLon."' , '". $InnerPair[0][0] ."' , '". $InnerPair[0][1] ."' , '". $InnerPair[1][0] ."' , '". $InnerPair[1][1] ."' , '". $Outer1[0] ."' , '". $Outer1[1] ."' , '".  $Outer2[0]."' , '".  $Outer2[1] . "')";

if ($conn->query($sql) === TRUE) {
   // echo "New record created successfully";
    echo '<script language="javascript">';
	echo 'alert("Your input has been recorded")';
	echo '</script>';
} else {
    echo '<script language="javascript">';
	echo 'alert("Your input has already been recorded")';
	echo '</script>';
}


$conn->close();
}


sendInput();

?> 


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

$hazardLat = $_GET["lat"];// -37.813599996508;
$hazardLon = $_GET["lon"];// 144.96489864099;
$polycoords = makePolycoords($hazardLat,$hazardLon);


$IP = $_SERVER['REMOTE_ADDR'];


$sql = "INSERT INTO pinpoint(eventID, description, userip, lat, lon, innerlat1,innerlon1, innerlat2,innerlon2,outerlat1,outerlon1,outerlat2,outerlon2,polycoords) values('" . $_GET["eventIdValue"]  . "', '" . $_GET["description"] . "' , '" . $IP . "' , '". $hazardLat ."' , '". $hazardLon."' , '0','0','0','0','0','0','0','0','" . $polycoords . "')";

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



function makePolycoords($LAT,$LON){
	$response = getNearbyRoadsFromCoords($LAT,$LON);
	$ways = $response[0];
	$nodes = $response[1];
	//var_dump($nodes);
	$polycoords = "";

	$i = 0;
	foreach ($ways as $way) {
		$j = 0;

		if( $i > 0 )
			$polycoords = $polycoords . "#" ;

		foreach( $way["nodes"] as $nodeID ) {
			if( $j > 0 )
				$polycoords = $polycoords . "|" ;
			
			$id = strval($nodeID);
			$encodeLAT = round(-37.84 - $nodes[$id]["lat"],6);
			$encodeLON = round(145 - $nodes[$id]["lon"],6);
			$polycoords = $polycoords . $encodeLAT . "," . $encodeLON;

			$j++;
		}

		$i++;
	}

	return $polycoords;
}


function getNearbyRoadsFromCoords($LAT,$LON){
		$URL = "http://overpass-api.de/api/interpreter?data=[out:json];way(around:50," . $LAT . "," . $LON . ")[highway];(._;>;);out;";
		$curl = curl_init();
		curl_setopt_array($curl, array(
		  CURLOPT_URL => $URL,
		  CURLOPT_RETURNTRANSFER => true,
		));

		$response = curl_exec($curl);
		$response = json_decode($response, true);
		$response = array_filter($response);

		//var_dump($response);

		if(!empty($response)){
				$ways = separateWaysAndNodes($response["elements"],"way");
				$nodes = separateWaysAndNodes($response["elements"],"node");
				//var_dump($nodes);
				//$ways = findPrimaryRoads($LAT,$LON,$ways,$nodes);
				
				//var_dump($primaryRoads);
				
				$newNodes = array();
				$newWays = array();
				foreach ($ways as $way) {
					$response = removeOutsideNodesExceptCrossing($LAT,$LON,$way,$nodes,35);
					if(sizeof($response[0]["nodes"])!=0){
						//var_dump($response[0]);
						$newWays[$response[0]["id"]] = $response[0];
						$newNodes = array_merge($newNodes,$response[1]);
					};
				}

				$nodes = array_merge($nodes,$newNodes);

				$newNodes = array();
				foreach ($nodes as $node) {
					$index = $node["id"];
					$newNodes[$index] = $node;
				}


				//var_dump($newNodes);
				return [$newWays, $newNodes];

		}
		else{
				return 'error';
		}
}

function separateWaysAndNodes($rawOutput,$Type){

		$wayOutput = array();

		foreach($rawOutput as $element){
			if($element["type"] === $Type){
				$element["id"] = strval($element["id"]);
				$A = $element["id"];
				$wayOutput[$A] = $element;
			}
		}

		return $wayOutput;
}


//================== Restrict road points to those within a given radius (in metres) ===========================

function removeOutsideNodesExceptCrossing($LAT,$LON,$way,$nodes,$radius){
	$kmRadius = $radius*0.001;
	$wayNodes = $way["nodes"];
	$wayID = $way["id"];
	$crossingPairs = array();

	$j = 0;
	$previousCondition = "unknown";
	$previousNode = "unknown";
	foreach ($wayNodes as $index => $wayNode) {

		$node1ID = strval($wayNodes[$index]);
		$node1LAT = $nodes[$node1ID]["lat"];
		$node1LON = $nodes[$node1ID]["lon"];

		if(distanceBetweenCoords($LAT,$LON,$node1LAT,$node1LON) <= $kmRadius)
			$condition = "inside";
		else
			$condition = "outside";
		

		if($index>0){
			if( $condition!=$previousCondition ){
				$direction = "outIn";
				if($previousCondition!="outside")
					$direction = "inOut";

				$crossingPairs[$j] = [ $wayNodes[$index-1] , $node1ID, $direction ];

				if($condition === "inside"){
					$way["nodes"][$index-1] = $previousNode . "n" . $wayID . "w";
				}
				else
					$way["nodes"][$index] = $way["nodes"][$index] . "n" . $wayID . "w";

				$j++;
			}
			elseif($condition === "outside"){
				unset($way["nodes"][$index]);
			}
			
		}
		else{
			$node2ID = strval($wayNodes[$index]);
			$node2LAT = $nodes[$node2ID]["lat"];
			$node2LON = $nodes[$node2ID]["lon"];

			if(distanceBetweenCoords($LAT,$LON,$node2LAT,$node2LON) <= $kmRadius)
				$nextCondition = "inside";
			else
				$nextCondition = "outside";

			if($nextCondition === $condition && $condition === "outside")
				unset($way["nodes"][$index]);
		}

		$previousNode = $node1ID;
		$previousCondition = $condition;
	}

	ksort($way["nodes"]);
	
	$newNodes = array();
	//var_dump($wayID);
	foreach ($crossingPairs as $crossingPair) {
		$newNode = makeBoundaryPoints($LAT,$LON,$crossingPair,$nodes,$wayID,$kmRadius);
		//var_dump($newNode);
		$newNodes[$newNode["id"]] = $newNode;

	}
	//var_dump($newNodes);
	return [$way,$newNodes];
}


function distanceBetweenCoords($latA,$lonA,$latB,$lonB){
	return norm([111*($latA-$latB),85*($lonA-$lonB)]);
}


function makeBoundaryPoints($latC,$lonC,$crossingPair,$nodes,$wayID,$kmRadius){
	$idA = strval($crossingPair[0]);
	$idB = strval($crossingPair[1]);

	$latA = $nodes[$idA]["lat"];
	$lonA = $nodes[$idA]["lon"];
	$latB = $nodes[$idB]["lat"];
	$lonB = $nodes[$idB]["lon"];

	$vectAB = [111*($latB-$latA),85*($lonB-$lonA)];
	$vectCA = [111*($latA-$latC),85*($lonA-$lonC)];

	$factor1 = dotProduct($vectCA,$vectAB)/pow(norm($vectAB),2);
	$factor2 =sqrt(pow(dotProduct($vectCA,$vectAB),2)-(pow(norm($vectCA),2)-pow($kmRadius,2))*pow(norm($vectAB),2))/pow(norm($vectAB),2);
	$factor = ($factor1 + $factor2);
	if( abs($factor) < 0 || abs($factor) > 1){
		$factor = ($factor1 - $factor2);
	}

	$vectCD = [ $vectCA[0]-$factor*$vectAB[0] , $vectCA[1]-$factor*$vectAB[1] ];

	$latNew = -$vectCD[0]/111+$latC;
	$lonNew = -$vectCD[1]/85+$lonC;

	//echo distanceBetweenCoords($latC,$lonC,$latNew,$lonNew) . "|";

	$direction = $crossingPair[2];
	if($direction === "outIn")
		$index = 0;
	else
		$index = 1;

	$index = strval($crossingPair[$index]) . "n" . $wayID . "w";
	$newNode =  array( "type" => "node", "id" => $index, "lat" => $latNew, "lon" => $lonNew);

	return $newNode;
}




//=======================This section gets close roads ie <10metres away. We call these 'primary roads'================

function findPrimaryRoads($LAT,$LON,$ways,$nodes){
	$primaryRoads = array();

	foreach ($ways as $index => $way) {
		$closeNodePairs = getClosestNodesOnWay($LAT,$LON,$way,$nodes);

		if(sizeof($closeNodePairs) != 0){
			$distance = getDistanceToRoad($LAT,$LON,$closeNodePairs);
			if( $distance < 0.01 ){
				$primaryRoads[$index] = $way;
			} 
		}
	}

	return $primaryRoads;
}

function getClosestNodesOnWay($LAT,$LON,$way,$nodes){
	$wayNodes = $way["nodes"];
	$closeNodePairs = array();

	$j=0;
	foreach ($wayNodes as $index => $wayNode) {
		if($index>0){
			$node1ID = strval($wayNodes[$index-1]);
			$node1LAT = $nodes[$node1ID]["lat"];
			$node1LON = $nodes[$node1ID]["lon"];
			$node2ID = strval($wayNodes[$index]);
			$node2LAT = $nodes[$node2ID]["lat"];
			$node2LON = $nodes[$node2ID]["lon"];
			if(testAngleIsAcute($LAT,$LON,$node1LAT,$node1LON,$node2LAT,$node2LON)){
				$closeNodePairs[$j] = [ $nodes[$node1ID] , $nodes[$node2ID] ];
				$j++;
			}
		}
	}

	return $closeNodePairs;
}

function dotProduct($v1,$v2){
	return $v1[0]*$v2[0]+$v1[1]*$v2[1];
}

function norm($v){
	return sqrt(dotProduct($v,$v));
}

function testAngleIsAcute($latA,$lonA,$latB,$lonB,$latC,$lonC){
	$vectBA = [$latA-$latB,$lonA-$lonB];
	$vectBC = [$latC-$latB,$lonC-$lonB];

	$vectCA = [$latA-$latC,$lonA-$lonC];
	$vectCB = [$latB-$latA,$lonB-$lonC];

	if(dotProduct($vectBA,$vectBC) >= 0 && dotProduct($vectCA,$vectCB) >= 0)
		return true;
	else
		return false;
}

function getDistanceToRoadSection($latA,$lonA,$latB,$lonB,$latC,$lonC){
	$vectBA = [111*($latA-$latB),85*($lonA-$lonB)]; //Approximately 111km for deg of latitude, 85km per deg of lon.
	$vectBC = [111*($latC-$latB),85*($lonC-$lonB)]; //Approximately 111km for deg of latitude, 85km per deg of lon.

	$scalarProjection = dotProduct($vectBA,$vectBC)/norm($vectBC);
	$vectorProjection = [ $scalarProjection*$vectBC[0]/norm($vectBC) , $scalarProjection*$vectBC[1]/norm($vectBC) ];

	$perpendicularVector = [ $vectBA[0]-$vectorProjection[0] , $vectBA[1]-$vectorProjection[1] ];
	$distance = norm($perpendicularVector);

	return $distance;
}

function getDistanceToRoad($LAT,$LON,$closeNodePairs){
	$distance = 51;

	foreach ($closeNodePairs as $nodePair) {
		$newDistance = getDistanceToRoadSection($LAT,$LON,$nodePair[0]["lat"],$nodePair[0]["lon"],$nodePair[1]["lat"],$nodePair[1]["lon"]);
		if ($newDistance < $distance)
			$distance = $newDistance;
	}

	return $distance;
}
//============ end of "primary roads" code ==========================================================================

















//=======================================================================================================

function getRoadString($LAT,$LON){
	$ID = getRoadIdFromCoords($LAT,$LON);
	//var_dump($ID);
	$way = getRoadPoints($ID);
	$pointString = createString($way);
	//var_dump($pointString);

	return $pointString;
}


function getRoadIdFromCoords($LAT,$LON){
		$URL = "http://35.197.185.129/nominatim/reverse?format=json&lat=" . $LAT . "&lon=" . $LON . "&addressdetails=1";
		$curl = curl_init();
		curl_setopt_array($curl, array(
		  CURLOPT_URL => $URL,
		  CURLOPT_RETURNTRANSFER => true,
		));

		$response = curl_exec($curl);
		$response = json_decode($response, true);
		$response = array_filter($response);

		if(!empty($response)){
				//var_dump($response);
				//$street = parseWayType($response['address']);
				$streetid = getRoadIdUsingName($response["place_id"],$LAT,$LON);
				//var_dump($streetid);
				//$streetid = $street['osm_id'];
				return $streetid;
		}
		else{
			return 'error';
		}
}

function parseWayType($address){

			if(!empty($address['road']) && !is_null($address['road']))
				return $address['road'];
			elseif(!empty($address['pedestrian']) && !is_null($address['pedestrian']))
					return $address['pedestrian'];
}


function getRoadIdUsingName($Name,$LAT,$LON){
		$F = 0.05*360/(6371*2*3.141596536); //Fifty Metres In Coords
		$nameString = str_replace(" ","+",$Name);

		$URL = 'http://35.197.185.129/nominatim/details?place_id=' . $nameString . "format=json";
			//echo $Name;
		$curl = curl_init();
		curl_setopt_array($curl, array(
		  CURLOPT_URL => $URL,
		  CURLOPT_RETURNTRANSFER => true,
		));

		$response = curl_exec($curl);
		$n = stripos($response,"highway:");
		$response = substr($response, $n);
		$n = stripos($response,"/way/");
		$n = $n + 5;
		$response = substr($response, $n);
		$n = stripos($response,'"');
		$response = substr($response,0,$n);

		return $response;
}


function getRoadPoints($ID){

		$URL = 'http://overpass-api.de/api/interpreter?data=[out:json];way(' . $ID . ');(._;>;);out;';
		$curl = curl_init();
		curl_setopt_array($curl, array(
		  CURLOPT_URL => $URL,
		  CURLOPT_RETURNTRANSFER => true,
		));

		$response = curl_exec($curl);
		$response = json_decode($response, true);
		$response = array_filter($response);
		//var_dump($response);

		$response = $response["elements"];
		foreach($response as $key => $i){
			if($i["type"] != "node")
				unset($response[$key]);
		}
		return $response;
}

function createString($way){

	$pointString = '';

	foreach($way as $node)
    	$pointString = $pointString . $node["lat"] . ',' . $node["lon"] . '|';

	$pointString = rtrim($pointString,'|');
	return $pointString;
}



function sendInputO(){

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
$polycoords = getRoadString($hazardLat,$hazardLon);


$IP = $_SERVER['REMOTE_ADDR'];


$sql = "INSERT INTO pinpoint(eventID, description, userip, lat, lon, innerlat1,innerlon1, innerlat2,innerlon2,outerlat1,outerlon1,outerlat2,outerlon2,polycoords) values('" . $_GET["eventIdValue"]  . "', '" . $_GET["description"] . "' , '" . $IP . "' , '". $hazardLat ."' , '". $hazardLon."' , '0','0','0','0','0','0','0','0','" . $polycoords . "')";

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




//--------------------------------------------------------------------




function snapToRoadArray($LatLonString){

		$URL = "https://roads.googleapis.com/v1/snapToRoads?path=" . $LatLonString . "&key=AIzaSyBvo9WExDGqsikBGfsqvdP0mHGGBDh79iE";
		$curl = curl_init();
		curl_setopt_array($curl, array(
		  CURLOPT_URL => $URL,
		  CURLOPT_RETURNTRANSFER => true,
		));

		$response = curl_exec($curl);
		$response = json_decode($response, true);
		$response = array_filter($response);

		if(!empty($response)){
    		var_dump($response);
    		//return [ $response["snappedPoints"][0]["location"]["latitude"] , $response["snappedPoints"][0]["location"]["longitude"] ];
		}
		else{
			return $response;
		}
}

function createSurroundingPoints($LAT,$LON){
	$kmToCoordFactor = 360/(6371*2*3.141596536);
	$pointString = '';

	for($t = 0; $t <= 9; $t++){
		for($a = 1; $a <= 4; $a++){

		$unsnappedLat = $LAT - $kmToCoordFactor*0.015*$a*sin($t*3.141596536/5);
    	$unsnappedLon = $LON - $kmToCoordFactor*0.015*$a*cos($t*3.141596536/5);

    	$pointString = $pointString . $unsnappedLat . ',' . $unsnappedLon . '|';
    	}
	};
	$pointString = rtrim($pointString,'|');
	return $pointString;
}






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


function sendInputOld(){

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

//snapToRoadArray("-37.7982338,144.9568716|-37.7983339,144.9566315|-37.7981147,144.9569083|-37.7983160,144.9565005");

//getRoadString(-37.8137488,144.9714005);
//getRoadString(-37.8120312,144.9669423);
//getRoadPoints();
//getNearbyRoadsFromCoords(-37.81930714531468,144.943419748402);

//getNearbyRoadsFromCoords(-37.81951033125294,144.94348977810898);

//makePolycoords(-37.7991922535573,144.9671385028555);
?> 


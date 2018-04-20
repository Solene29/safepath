
<html>
<head>
<title>Document sans-titre</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta name="robots" content="noindex" />
  <script src="https://code.jquery.com/jquery-2.1.4.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.3/moment.js"></script>
   <link href="css/test.css" rel="stylesheet">
   <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
   
<style>
	#log, #pass { color: white; background-color:black; padding-left:8px; padding-right:8px; }

label > input{ /* HIDE RADIO */
  visibility: hidden; /* Makes input not-clickable */
  position: absolute; /* Remove input from document flow */
}
label > input + i{ /* IMAGE STYLES */
  cursor:pointer;
  border:2px solid transparent;
  color:grey;
}
label > input:checked + i{ /* (RADIO CHECKED) IMAGE STYLES */
  color:black;
}

</style>

<script type="text/javascript">
	
	function testAddress(address1,name) {
    if(address1===null){
        if(name===null){
            return "";
        }
        else{
            return name + ", ";
        }
    }
    return address1 + ", ";
}


function testPicture(logo) {
    if(!logo){
        return '../images/event1.jpg'
    }
    if(!logo.url){
        return '../images/event1.jpg'
    }
    if(UrlExists(logo.url)){
        return logo.url
    }
    return '../images/event1.jpg'

}

function UrlExists(url)
{
    var http = new XMLHttpRequest();
    http.open('HEAD', url, false);
    http.send();
    //console.log(http.status!=404);
    return http.status!=404;
}

</script>



</head>

<body>

  <!-- Get Camera, taxi ranks and Police stations data from database -->

<?php
  require "php/getCameras.php"
?>
<?php
  require "php/getPoliceStations.php"
?>
<?php
  require "php/getTaxi.php"
?>


<!--<div id="cameraData">[{"camID":"1","lat":"-37.9665640","lon":"145.1737420"},{"camID":"2","lat":"-37.9653610","lon":"145.1738210"},{"camID":"3","lat":"-37.9654370","lon":"145.1739190"},{"camID":"4","lat":"-37.9664380","lon":"145.1743700"}]</div> 
<div id="policeData">[{"psID":"1","name":"Alexandra Police Station","lat":"-37.1885386","lon":"145.7078915"},{"psID":"2","name":"Altona North Police Staion","lat":"-37.8359990","lon":"144.8448483"}]</div> 
<div id="taxiData">[{"rankID":"25","lat":"-37.8180760128761","lon":"144.967531439296"},{"rankID":"53","lat":"-37.8234785115904","lon":"144.980467189591"},{"rankID":"54","lat":"-37.8158008159014","lon":"144.982752695403"},{"rankID":"57","lat":"-37.8118983657603","lon":"144.975326526605"},{"rankID":"56","lat":"-37.8091517635133","lon":"144.975898423805"}]</div> -->


<script type=text/javascript src=js/route.js></script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBvo9WExDGqsikBGfsqvdP0mHGGBDh79iE&libraries=places&callback=initMap"
        async defer>
</script>

<div class=HeadEvent></div>

<div align="center"><h1><span id="eventName"></span></h1></div>


<div style="content: ""; display: table; clear: both;">
  <div id="eventDate" style="float: left; width: 50%;" align="center"></div>
  <div id="eventUrl" style="float: left; width: 50%;" align="center"></div>
</div>

<hr width="100%";>  

<div style="content: ""; display: table; clear: both;">
  <div id="directionsPanel" style="float: left; width: 30%;" align="center">
    <b>Choose starting point:</b>    
        <input id="origin-input" class="controls" type="text" style ="width:250px" placeholder="Enter an origin location"></br></br>
          <b>Transport type: </b>
          <div id="mode-selector" class="controls">
            <label><input type="radio" name="type" id="changemode-walking" checked="checked">
            <i class="material-icons">directions_walk</i></label>
            <label><input type="radio" name="type" id="changemode-bicycling">
            <i class="material-icons">directions_bike</i></label>
            <label><input type="radio" name="type" id="changemode-transit">
            <i class="material-icons">directions_transit</i></label>
            <label><input type="radio" name="type" id="changemode-driving">
            <i class="material-icons">directions_car</i></label>
            </br>
            <b>Directions:</b>
            <div id="panel" style="width: 90%;"></div>
          </div>
  </div>
  <div id="mapAndInfoPanel" style="float: left; width: 70%;" align="center">
    <div class="horizontalTab">
  <button class="tablinks" id="defaultOpen" onclick="openMapOrInfo(event, 'safety')">Safety Features</button>
  <button class="tablinks" onclick="openMapOrInfo(event, 'event')">Event Info</button>
  <button class="tablinks" onclick="openMapOrInfo(event, 'route')">Route Updates</button>
  </div>
    
  <div id="mapPanel" class="mapOrInfoPanelContent">
    <div id="safetyOptions" class="mapOptions">
    <b>Safety features: </b>
          <table>
            <tr>
              <td ><input id="cameraCheckbox" type="checkbox" onclick="toggleGroup('cameraMarker')" checked="checked">Cameras</input></td>
              <td> <img src="http://maps.google.com/mapfiles/kml/paddle/grn-blank.png" style="height:15px;"></td>
            </tr>
            <tr>
              <td><input id="cameraCheckbox" type="checkbox" onclick="toggleGroup('policeMarker')" checked="checked">Police stations</input></td>
              <td><img src="http://maps.google.com/mapfiles/kml/paddle/purple-blank.png" style="height:15px;"></td>
            </tr>
            <tr>
              <td><input id="cameraCheckbox" type="checkbox" onclick="toggleGroup('taxiMarker')" checked="checked">Taxi ranks</input></td>
              <td><img src="http://maps.google.com/mapfiles/kml/paddle/pink-blank.png" style="height:15px;"></td>
            </tr>
          </table>
      </div>

      <div id="routeOptions" class="mapOptions">
    <b>Other Options</b>
      </div>

  <div id="map" style="width: 100%; height:300px;"></div>

  </div>

  <div id="eventPanel" class="mapOrInfoPanelContent">

  <div id="phpOutput"></div>

  <button onclick="setValue('noToilets');">No Toilets</button>
  <button onclick="setValue('longQueue');">Long Queues</button>
  <button onclick="setValue('foodRange');">Wide Range of Food</button>
  <button onclick="setValue('quickService');">Quick Service</button></br>

  <button onclick="getUserData();">Show event data</button></br></br>

  <button onclick="barChart();">Show event data</button></br></br>




  <div id="userData"></div>
  <div id="ipData"></div>


  <script>
    function barChart() {
  var userData = JSON.parse(document.getElementById('userData').innerHTML);
  var ipData = JSON.parse(document.getElementById('ipData').innerHTML);
  console.dir(userData);
 console.dir(ipData);
  }
  </script>


  <script>
function setValue(desc){
        $("#phpOutput").load("php/sendInput.php?eventIdValue="+eventId+"&description="+desc);
    };
</script>

<script>
function getUserData(){
        $("#phpOutput").load("php/getUserData.php?eventIdValue="+eventId);

    };
</script>


  </div>

    
  </div>
</div>





<p><b>Description: </b> <div style="height: 200px; overflow: auto;"" id="eventDescription" ></div> </p>








<script language="JavaScript">

	var eventId;

	function getEventId() {
		eventId =  unescape(location.search.substring(1).split("&")[0]);
	}
	
	getEventId();

	console.log(eventId);

	function getEventById() {

		var token = 'BY5GBM6TSQPL3NTR5S6E';

		$.get('https://www.eventbriteapi.com/v3/events/' + eventId + '/?token=' + token + '&expand=venue', function(event) {

				console.dir(event);

                document.getElementById("eventName").innerHTML = event.name.text;
                document.getElementById("eventUrl").innerHTML = "<a href='" + event.url + "' target=\"_blank\" style=\";font-weight:700;\">Click here for event page</a>";
                document.getElementById("eventDate").innerHTML = moment(event.start.local).format('D/M/YYYY h:mm A');
                document.getElementById("eventDescription").innerHTML = event.description.html;
        });
        					
	};


	getEventById();

</script>


<script>
function openMapOrInfo(evt, panelName) {
    var i, tabcontent, tablinks, mapoptions;
    var targetName = "event";

    if(panelName != "event"){
      targetName = "map";

      mapoptions = document.getElementsByClassName("mapOptions");
      for (i = 0; i < mapoptions.length; i++) {
          mapoptions[i].style.display = "none";
      }

      document.getElementById(panelName+"Options").style.display = "block";
      evt.currentTarget.className += " active";
      

      if(panelName === "safety"){
        toggleAllMarkers(true);
      }
      else{
        toggleAllMarkers(false);
      }

    }


    tabcontent = document.getElementsByClassName("mapOrInfoPanelContent");
    for (i = 0; i < tabcontent.length; i++) {
        tabcontent[i].style.display = "none";
    }
    tablinks = document.getElementsByClassName("tablinks");
    for (i = 0; i < tablinks.length; i++) {
        tablinks[i].className = tablinks[i].className.replace(" active", "");
    }
    document.getElementById(targetName+"Panel").style.display = "block";
    evt.currentTarget.className += " active";
}


    
</script>

<script> 
document.getElementById("defaultOpen").click();
</script>


<form method="get" action="parameters.html"> </form>
</body>
</html>

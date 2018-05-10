
<html>
<head>
<meta charset="utf-8">
  <title>SafePath|Event Info</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta name="robots" content="noindex" />
  <link href="css/bootstrap.css" rel="stylesheet"> 
  <link href="css/animate.min.css" rel="stylesheet">
  <link href="css/style.css" rel="stylesheet"> 
  <link href="css/test.css" rel="stylesheet">
  <link rel='shortcut icon' type='image/x-icon' href='/favicon.ico' />
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">


  <script type=text/javascript src="js/markerclusterer.js"></script>  
  <script src="https://code.jquery.com/jquery-2.1.4.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.3/moment.js"></script>
  <!--<script type=text/javascript src=js/displayEvents.js></script>-->
  <script type=text/javascript src=js/test.js></script>
  <script type=text/javascript src=js/categoryTabs.js></script>
  <script type=text/javascript src=js/AccordionEventDesc.js></script>
  <script type=text/javascript src=js/changeRoute.js></script> 
  <script type=text/javascript src=js/createHazards.js></script> 
    
  <script type="text/javascript" src="js/jquery.min.js"></script>
  <script type="text/javascript" src="../bootstrap/js/bootstrap.min.js"></script>
  <script type="text/javascript" src="../js/jquery.backstretch.min.js"></script>
  <script type="text/javascript" src="../js/function.js"></script>
  <script src="js/wow.min.js"></script>
  <script>
  new WOW().init();
  </script>

<script>

var hazardRoad;

function snapToRoad(marker){
  $.get('https://roads.googleapis.com/v1/snapToRoads?path='+marker.getPosition().lat()+','+marker.getPosition().lng()+'&key=AIzaSyBvo9WExDGqsikBGfsqvdP0mHGGBDh79iE',
      function(res){
        //console.log(res.snappedPoints[0].location.latitude);
        marker.setPosition(new google.maps.LatLng(res.snappedPoints[0].location.latitude,res.snappedPoints[0].location.longitude));
        if(marker === hazardMarker){
          geocodeLatLng(res.snappedPoints[0].location.latitude,res.snappedPoints[0].location.longitude);
        }
      });

};



function getActiveTab(){
  tablinks = $(".tablinks");
  //console.log(tablinks);
    for (i = 0; i < tablinks.length; i++) {
      if (tablinks[i].className === "tablinks active") {
        return tablinks[i].id;
      }
    }
}

var hazardClickListener;
var hazardDragListener;

function openHazardInput() {
toggleHazardMarkers(false);
document.getElementById("trafficHazardClosedPanel").style.display = "none";
document.getElementById("trafficHazardOpenPanel").style.display = "block";

hazardClickListener = new google.maps.event.addListener(map, 'click', function(event) {
    //console.log(event.latLng.lat());
    hazardMarker.setPosition(event.latLng);
    hazardMarker.setVisible(true);
    snapToRoad(hazardMarker);

    });

hazardDragListener = new google.maps.event.addListener(hazardMarker, 'dragend', function(event) {
    //console.log(hazardMarker.getPosition());
    snapToRoad(hazardMarker);

    });

};


/*
var tempMarkers =[];
function getPointsOnRoad(){
  var kmToCoordFactor = 360/(6371*2*3.141596536);
  var points = [];

  for(var t = 0; t <= 9; t++){
    for(var r = 0; r <= 3; r++){
      point =[hazardMarker.getPosition().lat() - kmToCoordFactor*0.025*(1+r)*Math.sin(t*3.141596536/5), hazardMarker.getPosition().lng() - kmToCoordFactor*0.05*(1+r)*Math.cos(t*3.141596536/5)];
      var marker = new google.maps.Marker({
            position: new google.maps.LatLng(point[0],point[1]),
            map: map
          });
      points.push(point);
      tempMarkers.push(marker);
    }
  }
console.log(points);

console.log(tempMarkers[0].getPosition().lat());
for(marker in tempMarkers){
  snapToRoad(tempMarkers[marker]);
};
}



function testPointsOnRoad(){
  console.log(tempMarkers);
  for(i = 0; i < tempMarkers.length; i++){
    
      $.get("https://35.197.185.129/nominatim/reverse?format=json&lat="+ tempMarkers[i].getPosition().lat() +"&lon="+tempMarkers[i].getPosition().lng()+"", 
        function(res){
          console.log(res);
        }

        )
    
              //if( hazardRoad != results[0].address_components[1].short_name){
                //console.log(results[0].address_components[1].short_name);
                //console.log(hazardRoad);
                //tempMarkers[i].setMap(null)
              //}


};
}


*/





function closeHazardInput() {
toggleHazardMarkers(true);
document.getElementById("trafficHazardClosedPanel").style.display = "block";
document.getElementById("trafficHazardOpenPanel").style.display = "none";

google.maps.event.removeListener(hazardClickListener);
google.maps.event.removeListener(hazardDragListener);
hazardMarker.setVisible(false);
infowindow.close();
if($('input[name=hazard-type]:checked').length>0){
    $('input[name=hazard-type]:checked')[0].checked = false;
  }
}

</script>
   

<script type="text/javascript">
//testing address is right format and change to if not
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

//testing event has a logo, if not display dummy
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

//testing url exists
function UrlExists(url)
{
    var http = new XMLHttpRequest();
    http.open('HEAD', url, false);
    http.send();
    return http.status!=404;
}

</script>

<body>

<!-- header start --> 
    <!-- header start --> 
    <header class="header fixed clearfix navbar navbar-fixed-top">
      <div class="container">
        <div class="row">
          <div class="col-md-4">
            <!-- header-left start --> 
            <div class="header-left">
              <!-- logo -->
              <div class="logo smooth-scroll">
                <a href="#banner"><img id="logo" src="../images/logo2.png" alt="Worthy"></a>
              </div>
              <!-- name-and-slogan -->
              <div class="logo-section smooth-scroll">
                <div class="brand"><a href="#banner">SAFE PATH</a></div>                
              </div>
            </div>
            <!-- header-left end -->
          </div>
          <div class="col-md-8">
            <!-- header-right start --> 
            <div class="header-right">
              <!-- main-navigation start --> 
              <div class="main-navigation animated">
                <!-- navbar start --> 
                <nav class="navbar navbar-default" role="navigation">
                  <div class="container-fluid">
                    <!-- Toggle get grouped for better mobile display -->
                    <div class="navbar-header">
                      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar-collapse-1">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                      </button>
                    </div>
                    <!-- Collect the nav links, forms, and other content for toggling -->
                    <div class="collapse navbar-collapse scrollspy smooth-scroll" id="navbar-collapse-1">
                      <ul class="nav navbar-nav navbar-right search">
                        <li><a href="../index.php">Home</a></li>
                        <li><a href="index.php">Events</a></li>
                        <li><a href="../about.php">About Us</a></li>
                        <li><a><form action="index.php" class="search-form" >
                      <input type="text" name="search" placeholder="Search for event..." required="">
                       <input type="submit" value="" class="search-submit" >
                        </form></a></li>
                      </ul>
                    </div>
                  </div>
                </nav>
                <!-- navbar end -->
              </div>
              <!-- main-navigation end -->
            </div>
            <!-- header-right end -->
          </div>
        </div>
      </div>
    </header>
    <!-- header end -->
    <!-- banner start --> 
    <div class="banner1"></div>
    <!-- banner end -->
   


<script type="text/javascript">
//Add the event location marker
var hazardMarker;

function addEventMarker(lat,lon) {
var hazardIcon = {url: 'images/ylw-pushpin.png',
                  anchor: new google.maps.Point(22, 64)
                  };
var marker = new google.maps.Marker({
          position: new google.maps.LatLng(lat,lon),
          map: map
        });
hazardMarker = new google.maps.Marker({
          position: new google.maps.LatLng(lat,lon),
          map: map,
          icon: hazardIcon,
          draggable: true
        });
hazardMarker.setVisible(false);
};
</script>

</head>

<body background="images/dust_scratches.png">
<form method="get" action="parameters.html"> </form>

<script language="JavaScript">

  var eventlat = "";
  var eventlon = "";

  var eventId;

//Get the ID for event
  function getEventId() {
    eventId =  unescape(location.search.substring(1).split("&")[0]);
  }
  
  getEventId();

//Get event data from eventbright
  function getEventById() {

    var token = 'BY5GBM6TSQPL3NTR5S6E';

    $.get('https://www.eventbriteapi.com/v3/events/' + eventId + '/?token=' + token + '&expand=venue', function(event) {


                document.getElementById("eventName").innerHTML = event.name.text;
                document.getElementById("eventUrl").innerHTML = "<a href='" + event.url + "' target=\"_blank\" style=\";font-weight:700; color: black; padding: 5px;cursor: pointer;background-color: #FEB728; border: 1px; box-shadow: none; border-radius: 0px; width:30px; text-align: center; height:30px\" ><u>Click here for event original page</u></a>";
                document.getElementById("eventDate").innerHTML = moment(event.start.local).format('D/M/YYYY h:mm A');
                document.getElementById("eventLoc").innerHTML = event.venue.address.address_1 + ", " + event.venue.address.city;
                document.getElementById("eventDescription").innerHTML = event.description.html;
                eventlat = event.venue.latitude;
                eventlon = event.venue.longitude;

                addEventMarker(eventlat,eventlon);

                updateEventCoords();

                var pt = new google.maps.LatLng(eventlat, eventlon);
                map.setCenter(pt);

                //markerCluster = new MarkerClusterer(map, [], {imagePath:"images/m", minimumClusterSize:1, ignoreHidden: true});

                $("#phpOutput").load("php/getToilets.php?eventLatValue="+eventlat+"&eventLonValue="+eventlon);


        });
                  
  };

  getEventById();
</script>



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

<!-- Get toilet data from database -->

<div id="toiletData" style="display: none;">[]</div>

<!-- Initialise data for plot where there is no user input yet -->

<div id="userData" style="display: none;">[{"description": "foodRange", "descPercentage": 0},{"description":"longQueue","descPercentage":0}, {"description":"noToilets","descPercentage":0}, {"description":"quickService","descPercentage":0}]</div>
<div id="ipData" style="display: none;">[{"totalIP": 0}]</div>
  

<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

<script type=text/javascript src=js/route.js></script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBvo9WExDGqsikBGfsqvdP0mHGGBDh79iE&libraries=places&callback=initMap"
        async defer>

</script>

<!-- Display content event information -->
<div class=HeadEvent></div>

<div align="center"><h1><span id="eventName"></span></h1></div>

<hr width="100%"; style="border-color:#BEBEBE"></br>  


<div id="mainTable" >
  <!-- Display directions panel -->

  <div id="directionsPanel" align="left" style="padding-right:25px">
    <div style="background:white">
        <u><b>Date:</b></u></br>
        <div id="eventDate" style="float: left;" align="left"></div></br>
        <u><b>Location:</b></u></br>
        <div id="eventLoc" style="float: left;" align="left"></div></br></br>
        <a href =#eventDescription style="color:black"><div style="padding: 5px;cursor: pointer;background-color: #FEB728; border: 1px; box-shadow: none; border-radius: 0px; width:100%; text-align: center;">Click here for event summary</div></a>
      </div>
    </br>
    <div style="background:white">
        <u><b>Route:</b></u></br></br>    
        <input id="origin-input" class="controls" type="text" placeholder="Enter an origin location"></br></br>
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
          </div>
            </br>

            <b>Directions:</b>
            <div id="directionsTab"></div>
              <div id="route-selector" class="controls" style="display:none">
                <input type="radio" name="routeNo" id="changeroute-1" checked="checked">
                <input type="radio" name="routeNo" id="changeroute-2"> 
                <input type="radio" name="routeNo" id="changeroute-3">
                <input type="radio" name="routeNo" id="changeroute-4">
                <input type="radio" name="routeNo" id="changeroute-5">
              </div>
            <div id="directionsOutputContent">Please input your starting location for directions.</div>
          
  </div>
</div>

  <!-- Display map panel -->
  <div id="mapAndInfoPanel" align="left">
    <div class="horizontalTab">
  <button class="tablinks" id="directions" onclick="openMapOrInfo(event, 'directions')">Direction to event</button>
  <button class="tablinks" id="safety" onclick="openMapOrInfo(event, 'safety')">Safety Features</button>
  <button class="tablinks" id="route" onclick="openMapOrInfo(event, 'route')">Route Hazards</button>
  <button class="tablinks" id="event" onclick="openMapOrInfo(event, 'event')">Event Feedback</button>
  </div>
    
  <div id="mapPanel" class="mapOrInfoPanelContent">
    <div id="safetyOptions" class="mapOptions">
          <table>
          </br>
            <tr>
              <td ><input id="cameraCheckbox" type="checkbox" onclick="toggleGroup('cameraMarker')" checked="checked">Cameras </input><i class="material-icons" style="color:#6600ff">videocam &#160;</i></td>
            <!-- </tr>
            <tr> -->
              <td><input id="cameraCheckbox" type="checkbox" onclick="toggleGroup('policeMarker')" checked="checked">Police stations </input><i class="material-icons" style="color:#993300">security &#160;</i></td>
            <!-- </tr>
            <tr> -->
              <td><input id="cameraCheckbox" type="checkbox" onclick="toggleGroup('taxiMarker')" checked="checked">Taxi ranks </input><i class="material-icons" style="color:#FF3366">local_taxi</i></td>
              
            </tr>
          </table>
      </div>

    <div id="routeOptions" class="mapOptions">
      <div id="trafficHazardClosedPanel">
        <button onclick="openHazardInput()"> Add Hazard</button><button onclick="showCurrentHazards(document.getElementById('myRange').value);"> test</button></br></br>
        <div class="slidecontainer">
          <input type="range" min="1" max="6" value="6" class="slider" id="myRange">
          <p>Showing hazards reported up to <span id="timePastSlider"></span> minutes ago</p><span id="demo1"></span>
        </div></br></br>

        <button onclick="getHazards()">Get Hazards</button>
      </div>
      <div id="trafficHazardOpenPanel" style="display:none; background-color:#ccc">
 
      <h3><b>What would you like to report on the way?</b></h3>
      </br>
          <div id="hazard-selector" class="controls">
            <label><input type="radio" name="hazard-type" id="trafficAccident">
             <div align="center">Traffic accident</div></label>
            <label><input type="radio" name="hazard-type" id="harrassment">
            <div align="center">Harrassment</div></label>
            <label><input type="radio" name="hazard-type" id="aggression">
            <div align="center">Aggression</div></label>
            <label><input type="radio" name="hazard-type" id="illegalActivity">
            <div align="center">Illegal activity</div></label>
          </div>
      </br>
        <button onclick="addHazard()"> Submit Hazard </button>
        <button onclick="closeHazardInput()"> Cancel</button>
      </div>


    </div>


      <div id="directionsOptions" class="mapOptions">
    <b style="font-size: 20px">Filter Options</b>
    

    </br></br><input id="toiletCheckbox" type="checkbox" onclick="toggleGroup('toiletMarker')">Toilets</input>
      </div></br>

  <div id="map" style="width: 100%; height:500px;"></div>

  </div>

  <!-- Display Feedback panel -->

  <div id="eventPanel" class="mapOrInfoPanelContent">

  <div id="phpOutput"></div>

  <p><h3><b>What would you like to report? </b></h3>   

  </p>

  <button class="choice" onclick="setValue('noToilets');" style=" background-color:#EEC440">No Toilets</button>
  <button class="choice" onclick="setValue('longQueue');" style=" background-color:#EEC440">Long Queues</button>
  <button class="choice" onclick="setValue('foodRange');" style=" background-color:#EEC440">Varied Food</button>
  <button class="choice" onclick="setValue('quickService');" style=" background-color:#EEC440">Great Event  </button>

  <label><input type="submit" id="myBtn" style="display:none">
            <i class="material-icons">info_outline</i></label>

</br>

  

  
  <div id="chartDiv"></div>


  <button onclick="goToToilets();" style="padding: 5px;
    cursor: pointer;
    background-color: #bbb; 
    border: 1px; 
    box-shadow: none; 
    border-radius: 0px; 
    width:200px;" >Show me nearby toilets! <i class="material-icons">wc</i>
  </button>
<!-- <input type="image" src="images/twit.png" class="twitter-share-button"
  href="https://twitter.com/intent/tweet?text=Hello%20world"><a class="twitter-share-button"
  href="https://twitter.com/intent/tweet?text=Hello%20world"></a></input>
Tweet</a> -->

  </div>

    
  </div>
</div>

</br></br><hr width="100%"; style="border-color:#BEBEBE">

<!-- Display event description -->

<div style="display: block; clear: both">
  <div style="padding: 25px;">
<p style="display: block; "> 
  <table>
    <tr>
      <td>
  <div><h2><b>Description:</b></h2></div>
</td>
<td>
  <div id="eventUrl" style="padding-left: 10px"></div>
</td>
</tr>
<table>
  <div id="eventDescription" style="text-align:justify"></div> </p>
</table>
  </table>
</div>
</div>





<!-- ===================================== Reformating data for the bar chart in Feedback tab ========================================-->
  <script>



function switchNames(codeName) {
  var out = '';
   switch (codeName) {
        case 'foodRange':
            out = 'Varied Food';
            break;
        case 'longQueue':
            out = 'Long queues';
            break;
        case 'noToilets':
            out = 'No toilets';
            break;
        case 'quickService':
            out = 'Great Event';
            break;
    }
    return out;
}

function switchColours(codeName) {
  var out = '';
   switch (codeName) {
        case 'foodRange':
            out = 'green';
            break;
        case 'longQueue':
            out = 'red';
            break;
        case 'noToilets':
            out = 'red';
            break;
        case 'quickService':
            out = 'green';
            break;
    }
    return out;
}


//Display bar chart

  function barChart() {
  var userData = JSON.parse(document.getElementById('userData').innerHTML);
  var ipData = JSON.parse(document.getElementById('ipData').innerHTML);

  function addZeroCounts(){
  var allDescriptions = ["foodRange", "longQueue", "noToilets", "quickService"];
  var currentDescriptions = [];


  for(var i = 0; i < userData.length; i++) {
    currentDescriptions.push(userData[i].description);
  }


  for(var i = 0; i < allDescriptions.length; i++) {
    if(currentDescriptions.indexOf(allDescriptions[i])<0){
      userData.push({description: allDescriptions[i], total:0});
 
    }
  }


  }


  addZeroCounts();



  var dataTable = new Array();
  dataTable[0] = ['Description', 'Percentage of votes', { role: 'style' }];
   for(var i=0; i<userData.length;i++) {
    dataTable[i+1] = [ switchNames(userData[i].description) , userData[i].descPercentage,  switchColours(userData[i].description) ];
   }

  

google.charts.load('current', {packages: ['corechart', 'bar']});
google.charts.setOnLoadCallback(drawBasic);

//Draw bar chart

function drawBasic() {

      var data = google.visualization.arrayToDataTable(dataTable,false);

      var options = {
        title: '',
        legend: 'none',
        chartArea: {width: '50%'},
        hAxis: {
          viewWindowMode:'explicit',
          title: 'Percentage of Voters (Total voters = ' + ipData[0].totalIP +')',
          minValue: 0,
          maxValue: 100
        },
        vAxis: {
          title: ''
        }
      };

      var chart = new google.visualization.BarChart(document.getElementById('chartDiv'));

      chart.draw(data, options);
    }




  }
  



  </script>

<script>


//Get nearby toilets

function goToToilets(){
        
        if(markerGroups['toiletMarker'].length ===0){
          alert("Unfortunately, no toilets were found within 500m of this event.")
        }
        else {
        $('#toiletCheckbox').prop('checked', true)
        if(toiletMarkerStatus==="hide"){
          toggleGroup('toiletMarker');
        }
        openMapOrInfo(event, 'directions');
        var pt = new google.maps.LatLng(eventlat, eventlon);
        map.setCenter(pt);
        map.setZoom(15);
      }
    };
</script>

  <script>
function setValue(desc){
        $("#phpOutput").load("php/sendInput.php?eventIdValue="+eventId+"&description="+desc);
    };
</script>

<script>
function addHazard(){
      if(hazardMarker.getVisible() && $('input[name=hazard-type]:checked').length>0){
        console.log($('input[name=hazard-type]:checked')[0].id);
        console.log(hazardMarker.getPosition().lat());
        console.log(hazardMarker.getPosition().lng());
        $("#phpOutput").load("php/sendInputRoute.php?eventIdValue="+eventId+"&description="+$('input[name=hazard-type]:checked')[0].id+"&lat="+hazardMarker.getPosition().lat()+"&lon="+hazardMarker.getPosition().lng());
        closeHazardInput();
      }
      else{
        if($('input[name=hazard-type]:checked').length < 1){
          alert("Please choose a hazard type.");
        }
        else{
          alert("Please select a hazard location");
        }
      }
    };

    function getHazards(){
        $("#phpOutput").load("php/getHazardData.php");

    };
</script>

<script>
function getUserData(){
        $("#phpOutput").load("php/getUserData.php?eventIdValue="+eventId);

    };

    getUserData();

    barChart();

    function updateData(){
      var activeTab = getActiveTab();
      if(activeTab === "route" && document.getElementById("trafficHazardOpenPanel").style.display === "none"){
        //getHazards();
      };
      if(activeTab === "event"){
        getUserData(); 
        barChart();
      };
    }

    var intervalID = setInterval(function(){updateData();}, 1000);
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
      
      if(document.getElementById("trafficHazardOpenPanel").style.display === "block"){
        closeHazardInput();
      };
      showMarkerGroup(panelName);  
    }

    
    tabcontent = document.getElementsByClassName("mapOrInfoPanelContent");
    for (i = 0; i < tabcontent.length; i++) {
        tabcontent[i].style.display = "none";
    }
    document.getElementById(targetName+"Panel").style.display = "block";

    
    tablinks = document.getElementsByClassName("tablinks");
    for (i = 0; i < tablinks.length; i++) {
        tablinks[i].className = tablinks[i].className.replace(" active", "");
    }
    evt.currentTarget.className += " active";

    updateData();
}


    
</script>

<script> 
document.getElementById("directions").click(); // default open tab
</script>


<script>
var slider = document.getElementById("myRange");
var output = document.getElementById("timePastSlider");
output.innerHTML = 140-slider.value*20;


slider.oninput = function() {
  output.innerHTML = 140-this.value*20;
  console.log("-----------slider changed to"+ this.value +" ---------");
  showCurrentHazards(this.value);
}
</script>




<!-- ================   Modal code start  ====================    -->
<div id="myModal" class="modal">

  <!-- Modal content -->
  <div class="modal-content">
    <span class="close">&times;</span>
    <p><b>The bar chart combines user opinions about the event. Share your thoughts by clicking on the following buttons:</b></br></br>
No Toilet - insufficient facilities</br>
Long Queues - access or service is slow</br>
Varied Food - plenty of tasty treats</br>
Great Event - no problems encountered</p>
  </div>

</div>

<script>
// Get the modal
var modal = document.getElementById('myModal');

// Get the button that opens the modal
var btn = document.getElementById("myBtn");

// Get the <span> element that closes the modal
var span = document.getElementsByClassName("close")[0];

// When the user clicks the button, open the modal 
btn.onclick = function() {
    modal.style.display = "block";
}

// When the user clicks on <span> (x), close the modal
span.onclick = function() {
    modal.style.display = "none";
}

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
    if (event.target == modal) {
        modal.style.display = "none";
    }
}
</script>

<!-- ================   Modal code end  ====================    -->

 <!-- footer start -->
    <div id="footer">
      <div class="subfooter">
        <div class="container">
          <div class="row">
            <div class="col-md-12">
              <p class="text-center">&copy; 2018 SafePath</p>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- footer end -->


</body>
</html>

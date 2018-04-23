
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
  
  <script src="https://code.jquery.com/jquery-2.1.4.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.3/moment.js"></script>
  <script type=text/javascript src=js/displayEvents.js></script>
  <script type=text/javascript src=js/categoryTabs.js></script>
  <script type=text/javascript src=js/AccordionEventDesc.js></script> 
    
  <script type="text/javascript" src="../js/jquery.min.js"></script>
  <script type="text/javascript" src="../js/bootstrap.min.js"></script>
  <script type="text/javascript" src="../js/jquery.backstretch.min.js"></script>
  <script type="text/javascript" src="../js/function.js"></script>
  <script src="js/wow.min.js"></script>
  <script>
  new WOW().init();
  </script>

   
   
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
                <a href="#banner"><img id="logo" src="../images/logo3.png" alt="Worthy"></a>
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
                        <li><a href="index.php">Home</a></li>
                        <li><a href="index.php">Events</a></li>
                        <li><a href="../about.php">About Us</a></li>
			<li><a><form action="#" class="search-form">
			<input type="text" value="Search" onfocus="this.value = '';" onblur="if (this.value == '') {this.value = "Search";}" required="">
			 <input type="submit" value="" class="search-submit" name="submit">
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
  function addEventMarker(lat,lon) {
var marker = new google.maps.Marker({
          position: new google.maps.LatLng(lat,lon),
          map: map
        })
}
</script>

</head>

<body>
<form method="get" action="parameters.html"> </form>



<script language="JavaScript">

  var eventlat = "";
  var eventlon = "";

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
                eventlat = event.venue.latitude;
                eventlon = event.venue.longitude;

                addEventMarker(eventlat,eventlon);

                updateEventCoords();

                var pt = new google.maps.LatLng(eventlat, eventlon);
                map.setCenter(pt);
                map.setZoom(13);

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

<div id="toiletData" style="display: none;">[]</div>

<div id="userData" style="display: none;">[{"description": "foodRange", "total": 0},{"description":"longQueue","total":0}, {"description":"noToilets","total":0}, {"description":"quickService","total":0}]</div>
<div id="ipData" style="display: none;">[{"totalIP": 0}]</div>
  

<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

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

</br></br><hr width="100%";></br>  

<div id="mainTable">
  <div id="directionsPanel" align="left">

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
          </div>
            </br>

            <b>Directions:</b>
            <div id="directionsOutput">Please input your starting location for directions.</div>
          
  </div>
  <div id="mapAndInfoPanel" align="left">
    <div class="horizontalTab">
  <button class="tablinks" id="defaultOpen" onclick="openMapOrInfo(event, 'directions')">Direction to event</button>
  <button class="tablinks" onclick="openMapOrInfo(event, 'safety')">Safety Features</button>
  <button class="tablinks" onclick="openMapOrInfo(event, 'route')">Route Hazards</button>
  <button class="tablinks" onclick="openMapOrInfo(event, 'event')">Event Feedback</button>
  </div>
    
  <div id="mapPanel" class="mapOrInfoPanelContent">
    <div id="safetyOptions" class="mapOptions">
          <table>
            <tr>
              <td ><input id="cameraCheckbox" type="checkbox" onclick="toggleGroup('cameraMarker')" checked="checked">Cameras</input></td>
              <td> <img src="http://maps.google.com/mapfiles/kml/paddle/grn-blank.png" style="height:15px;"></td>
            <!-- </tr>
            <tr> -->
              <td><input id="cameraCheckbox" type="checkbox" onclick="toggleGroup('policeMarker')" checked="checked">Police stations</input></td>
              <td><img src="http://maps.google.com/mapfiles/kml/paddle/purple-blank.png" style="height:15px;"></td>
            <!-- </tr>
            <tr> -->
              <td><input id="cameraCheckbox" type="checkbox" onclick="toggleGroup('taxiMarker')" checked="checked">Taxi ranks</input></td>
              <td><img src="http://maps.google.com/mapfiles/kml/paddle/pink-blank.png" style="height:15px;"></td>
            </tr>
          </table>
      </div>

      <div id="routeOptions" class="mapOptions">
    <b>Coming soon...</b>
      </div>

      <div id="directionsOptions" class="mapOptions">
    <b>Direction Options</b>
    

    <input id="toiletCheckbox" type="checkbox" onclick="toggleGroup('toiletMarker')">Toilets</input>
      </div>

  <div id="map" style="width: 100%; height:350px;"></div>

  </div>

  <div id="eventPanel" class="mapOrInfoPanelContent">

  <div id="phpOutput"></div>

  <p>What would you like to report?    

  </p>

  <button class="choice" onclick="setValue('noToilets');">No Toilets</button>
  <button class="choice" onclick="setValue('longQueue');" >Long Queues</button>
  <button class="choice" onclick="setValue('foodRange');" >Wide Range of Food</button>
  <button class="choice" onclick="setValue('quickService');" >Quick Service  </button><!-- Trigger/Open The Modal -->

  <label><input type="image" id="myBtn" style="height:24px">
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


  </div>

    
  </div>
</div>



<div style="display: block; clear: both">
  <div style=" background-color:#bbb; padding: 25px;">
<p style="display: block; "> 
  <b>Description:</b>
  <div id="eventDescription" ></div> </p>
</div>
</div>







  <script>



function switchNames(codeName) {
  var out = '';
   switch (codeName) {
        case 'foodRange':
            out = 'Wide range of food';
            break;
        case 'longQueue':
            out = 'Long queues';
            break;
        case 'noToilets':
            out = 'No toilets';
            break;
        case 'quickService':
            out = 'Quick service';
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
      //console.dir(allDescriptions[i]);
    }
  }


  }


  addZeroCounts();
    //console.dir(userData);


  var dataTable = new Array();
  dataTable[0] = ['Description', 'Total', { role: 'style' }];
   for(var i=0; i<userData.length;i++) {
    /*console.log(switchNames(userData[i].description));*/
    dataTable[i+1] = [ switchNames(userData[i].description) , userData[i].total,  switchColours(userData[i].description) ];
   }

   //console.log(dataTable);
  

google.charts.load('current', {packages: ['corechart', 'bar']});
google.charts.setOnLoadCallback(drawBasic);

function drawBasic() {

      var data = google.visualization.arrayToDataTable(dataTable,false);

      var options = {
        title: '',
        legend: 'none',
        chartArea: {width: '50%'},
        hAxis: {
          viewWindowMode:'explicit',
          title: 'Total voters',
          minValue: 0,
          maxValue: ipData[0].totalIP
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
function getUserData(){
        $("#phpOutput").load("php/getUserData.php?eventIdValue="+eventId);

    };

    getUserData();

    barChart();

    var intervalID = setInterval(function(){getUserData(); barChart();}, 1000);
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
        toggleSafetyMarkers(true);
        toggleToiletMarkers(false);
      }
      else{
        if(panelName === "directions"){
        toggleSafetyMarkers(false);
        toggleToiletMarkers(true);
      }
      else{
        toggleSafetyMarkers(false);
        toggleToiletMarkers(false);
      }

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




</body>
</html>

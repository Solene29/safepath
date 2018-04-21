<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>SafePath|Events</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="css/bootstrap.css" rel="stylesheet"> 
  <link href="css/animate.min.css" rel="stylesheet">
  <link href="css/style.css" rel="stylesheet"> 
  <link href="css/test.css" rel="stylesheet">
  <link rel='shortcut icon' type='image/x-icon' href='/favicon.ico' />
  
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
<link rel="shortcut icon" href="favicon.ico">
<!-- Display events in tabs that we can show or hide -->
<script>
function toggleAccordion(Input) {
var panel = document.getElementById("panel" + Input);
var upArrow = document.getElementById("upArrow" + Input);
var downArrow = document.getElementById("downArrow" + Input);
var accordion = document.getElementById("accordion" + Input);

if (panel.style.display === "block") {
            panel.style.display = "none";
            downArrow.style.display = "block";
            upArrow.style.display = "none";
            accordion.style.backgroundColor = "#eee";
        } else {
            panel.style.display = "block";
            downArrow.style.display = "none";
            upArrow.style.display = "block";
            accordion.style.backgroundColor = "#ccc";
        }

}
</script>

<!-- Events tab content - onclick map -->
<script>
function openModal(eventName,address,lat,lng) {
    modal.style.display = "block";
    
    var t = '';
    t += '<p><b>Event Name: </b>'+ eventName +'</p>';
    t += '<p><b>Address: </b>'+ address +'</p>';
    t += '<div id="eventCoords", style="display: none;"> [{"lat":"' + lat + '","lng":\"' +lng +'"}] </div>' ;

    document.getElementById("modalDiv").innerHTML = t;
    
    updateEventCoords();
    }
</script>

</head>

<body>

<!-- header start --> 
    <header class="header fixed clearfix navbar navbar-fixed-top">
      <div class="container">
        <div class="row">
          <div class="col-md-4">
            <!-- header-left start --> 
            <div class="header-left">
              <!-- logo -->
              <div class="logo smooth-scroll">
                <a href="../index.php"><img id="logo" src="../images/logo3.png" alt="Worthy"></a>
              </div>
              <!-- name-and-slogan -->
              <div class="logo-section smooth-scroll">
                <div class="brand"><a href="../index.php">SAFE PATH</a></div>               
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
                      <ul class="nav navbar-nav navbar-right">
                        <li><a href="../index.php">Home</a></li>
                        <li><a  style="color:#ffc400;" href="index.php">Events</a></li>
						  <li><a href="../about.php">About us</a></li>
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

<script type=text/javascript src=js/route.js></script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBvo9WExDGqsikBGfsqvdP0mHGGBDh79iE&libraries=places&callback=initMap"
        async defer>
</script>

<!-- Event category tabs: -->
<div class="tabb"><p><a href="../index.php">Home</a> - <a style="color:black" href="index.php">Event</a></p></div>
<div class="tab">
  <h3>Category</h3>
  <button class="tablinks" onclick="openCategory(event, 'eventArts')">Arts</button>
  <button class="tablinks" onclick="openCategory(event, 'eventFood')">Food and Drink</button>
  <button class="tablinks" id="defaultOpen" onclick="openCategory(event, 'eventMusic')">Music</button>
  <button class="tablinks" onclick="openCategory(event, 'eventSports')">Sports</button>
  <button class="tablinks" onclick="openCategory(event, 'eventOther')">Other</button>
</div>

<!-- Event category tabs content -->
<div id="eventArtsTab" class="tabcontent">
   <p style="background-color: #454445; border: none; font-weight: 900; text-align: center; color: white; font-size: 28pt; padding:0 ; text-decoration:none; margin: 1.5em 0px;">
Arts</p>
<div id="eventArts"></div>    
</div>

<div id="eventFoodTab" class="tabcontent">
  <p style="background-color: #454445; border: none; font-weight: 900; text-align: center; color: white; font-size: 28pt; padding:0 ; text-decoration:none; margin: 1.5em 0px;">
  Food and Drink</p> 
  <div id="eventFood"></div>
</div>

<div id="eventMusicTab" class="tabcontent">
  <p style="background-color: #454445; border: none; font-weight: 900; text-align: center; color: white; font-size: 28pt; padding:0 ; text-decoration:none; margin: 1.5em 0px;">
  Music</p> 
  <div id="eventMusic"></div>
</div>

<div id="eventSportsTab" class="tabcontent">
  <p style="background-color: #454445; border: none; font-weight: 900; text-align: center; color: white; font-size: 28pt; padding:0 ; text-decoration:none; margin: 1.5em 0px;">
  Sports</p>
  <div id="eventSports"></div>
</div>

<div id="eventOtherTab" class="tabcontent">
  <p style="background-color: #454445; border: none; font-weight: 900; text-align: center; color: white; font-size: 28pt; padding:0 ; text-decoration:none; margin: 1.5em 0px;">
  Other</p> 
  <div id="eventOther"></div>
</div>

<!-- Event category tabs open: -->
<script> 
document.getElementById("defaultOpen").click();
var acc = document.getElementsByClassName("accordion");
var i;

for (i = 0; i < acc.length; i++) {
    acc[i].addEventListener("click", function() {
        this.classList.toggle("active");
        var panel = this.nextElementSibling;
        if (panel.style.display === "block") {
            panel.style.display = "none";
        } else {
            panel.style.display = "block";
        }
    });
}
</script>
  
<!-- The Modal -->
<div id="myModal" class="modal">

  <!-- Modal content -->
  <div class="modal-content">
    <span class="close">&times;</span>
    <div id="modalDiv"></div>
    <b>Choose starting point: </b>    
    <input id="origin-input" class="controls" type="text" style ="width:250px" placeholder="Enter an origin location"></br></br>
    <table>
      <col width="200px">
      <tr>
        <td valign="top">
          <b>Transport type: </b>
          <div id="mode-selector" class="controls">
            <input type="radio" name="type" id="changemode-walking" checked="checked">
            <label for="changemode-walking">Walking</label></br>
            <input type="radio" name="type" id="changemode-bicycling">
            <label for="changemode-bicycling">Cycling</label></br>
            <input type="radio" name="type" id="changemode-transit">
            <label for="changemode-transit">Transit</label></br>
            <input type="radio" name="type" id="changemode-driving">
            <label for="changemode-driving">Driving</label>
            </br>
          </div></br>
        </td>

        <td valign="top">
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

        </td>
      </tr>
    </table>

    <table style="width:100%">
      <tr>
        <td>
            <div id="map" style="width: 100%; height:300px;"></div></br>
        </td>
      </tr>
      <tr>
        <td> 
          <b>Directions:</b>
          <div id="panel" style="width: 90%;"></div>
        </td>
      </tr>
    </table>
  </div>
</div>


<!-- Get modal: -->
<script>
// Get the modal
var modal = document.getElementById('myModal');
// Get the <span> element that closes the modal
var span = document.getElementsByClassName("close")[0];
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
</body>
</html>
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
  <script src="http://code.jquery.com/jquery-latest.js"></script>
  <!-- <script src="jquery.easyPaginate.js"></script> -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.3/moment.js"></script>
  <script type=text/javascript src=js/displayEvents.js></script>
  <script type=text/javascript src=js/test.js></script>
  <script type=text/javascript src=js/categoryTabs.js></script>
  <script type=text/javascript src=js/pageTab.js></script>
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
        //comment

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

<div class="wrapper">
    
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
                      <ul class="nav navbar-nav navbar-right search">
                        <li><a href="../index.php">Home</a></li>
                        <li><a href="index.php">Events</a></li>
                        <li><a href="../about.php">About Us</a></li>
      <li><a><form action="index.php" class="search-form">
      <input type="text" name="search" placeholder="Search" required="">
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


<body>
 

<!-- Importing the search string from another page -->
<form method="get" action="parameters.html"> </form>
<script language="JavaScript">
var searchString = "";
var searchQn = "no";
function getSearchString() {
    var parameters =  unescape(location.search.substring(1).split("&")[0]);
    var temp = parameters.split("=");
    if(temp.length>0){
      if(temp[0]==="search"){
        searchQn = "yes";
        searchString = temp[1];
      }
    }
  };
getSearchString();
console.log(searchString);
console.log(searchQn);
</script>


<!-- Event category tabs: -->
<div class="tabb"><p><a href="../index.php">Home</a> - <a style="color:black" href="index.php">Event</a></p></div>
<div class="tab">
  <button class="tablinks" onclick="openCategory(event, 'eventArts', '')">Arts</button>
  <button class="tablinks" onclick="openCategory(event, 'eventFood', '')">Food and Drink</button>
  <button class="tablinks" id="defaultOpen" onclick="openCategory(event, 'eventMusic', '')">Music</button>
  <button class="tablinks" onclick="openCategory(event, 'eventSports', '')">Sports</button>
  <button class="tablinks" onclick="openCategory(event, 'eventOther', '')">Other</button>
  <button class="tablinks" id="searchOpen" onclick="openCategory(event, 'eventSearch', '')">Search</button>
</div>



<!-- Event category tabs content -->
<div id="eventArtsTab" class="tabcontent">
  <p style="background-color: #454445; border: none; font-weight: 900; text-align: center; color: white; font-size: 28pt; padding:0 ; text-decoration:none;">
Arts</p>
<div id="eventArts"></div>    
</div>

<div id="eventFoodTab" class="tabcontent">
  <p style="background-color: #454445; border: none; font-weight: 900; text-align: center; color: white; font-size: 28pt; padding:0 ; text-decoration:none;">
  Food and Drink</p> 
  <div id="eventFood"></div>
</div>

<div id="eventMusicTab" class="tabcontent">
  <p style="background-color: #454445; border: none; font-weight: 900; text-align: center; color: white; font-size: 28pt; padding:0 ; text-decoration:none;">
  Music</p> 
  <div id="eventMusic"></div>
</div>

<div id="eventSportsTab" class="tabcontent">
  <p style="background-color: #454445; border: none; font-weight: 900; text-align: center; color: white; font-size: 28pt; padding:0 ; text-decoration:none;">
  Sports</p>
  <div id="eventSports"></div>
</div>

<div id="eventOtherTab" class="tabcontent">
  <p style="background-color: #454445; border: none; font-weight: 900; text-align: center; color: white; font-size: 28pt; padding:0 ; text-decoration:none;">
  Other</p> 
  <div id="eventOther"></div>
</div>

<div id="eventSearchTab" class="tabcontent">
  <p style="background-color: #454445; border: none; font-weight: 900; text-align: center; color: white; font-size: 28pt; padding:0 ; text-decoration:none;">
  Search</p> 
  <input id="seachInputMain" class="controls" type="text" placeholder="Search for event..."> 
  <button style="padding: 5px;cursor: pointer;background-color: #FEB728; border: 1px; box-shadow: none; border-radius: 0px; width:80px; text-align: center;" onclick="openCategory(event,'eventSearch', document.getElementById('seachInputMain').value)">Search</button>
  <div id="eventSearch"></div>
</div>











<!-- footer start -->
<div style="clear:both; display:block;">
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
    
</div>
<!-- footer end -->



<!-- Event category tabs open: -->
<script> 
  if(searchQn==="no"){
    document.getElementById("defaultOpen").click();
  }
  else {
    openCategory(event, 'eventSearch', searchString);
  }
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
</body>

</html>

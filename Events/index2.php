 <html>
<head>
    <script src="https://code.jquery.com/jquery-2.1.4.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.3/moment.js"></script>
    <script type="text/javascript" src="Events.js"></script>
    <script type="text/javascript" src="listEvents.js"></script> 
    <link rel="stylesheet" type="text/css" href="Events.css" /> 
</head>
<body>
  
<p>Click on the buttons inside the tabbed menu:</p>

<div class="tab">
  <button class="tablinks" onclick="openCategory(event, 'Arts')">Arts</button>
  <button class="tablinks" onclick="openCategory(event, 'Music')">Music</button>
  <button class="tablinks" onclick="openCategory(event, 'FoodDrink')">Food and Drink</button>
  <button class="tablinks" onclick="openCategory(event, 'Other')">Other</button>
</div>

<div id="Arts" class="tabcontent" 
  <h3>Arts</h3>
  
</div>

<div id="Music" class="tabcontent">
  <h3>Music</h3>
  <h2>Upcoming Events:</h2>
<div id="events"></div>
</div>

<div id="FoodDrink" class="tabcontent">
  <h3>FoodDrink</h3>
  <p>Tokyo is the capital of Japan.</p>
</div>

<div id="Other" class="tabcontent">
  <h3>Other</h3>
  <p>Tokyo is the capital of Japan.</p>
</div>


</body>
</html>
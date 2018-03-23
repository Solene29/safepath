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
  <button class="tablinks" onclick="openCategory(event, 'FoodDrink')">Food and Drink</button>
  <button class="tablinks" onclick="openCategory(event, 'Music')">Music</button>
  <button class="tablinks" onclick="openCategory(event, 'Other')">Other</button>
  <button class="tablinks" onclick="openCategory(event, 'Sports')">Sports</button>
</div>

<div id="Arts" class="tabcontent">
  <h1>Arts</h1>
  <h2>Upcoming Events:</h2>
  <div id="events"></div>
</div>

<div id="FoodDrink" class="tabcontent">
  <!-- <h1>Food and Drink</h1>
  <h2>Upcoming Events:</h2> -->
  <!-- <div id="events"></div> -->
</div>

<div id="Music" class="tabcontent">
  <!-- <h1>Music</h1>
  <h2>Upcoming Events:</h2> -->

</div>

<div id="Other" class="tabcontent">
  <!-- <h1>Other</h1>
  <h2>Upcoming Events:</h2> -->
</div>

<div id="Sports" class="tabcontent">
  <!-- <h1>Sports</h1>
  <h2>Upcoming Events:</h2> -->
</div>

</body>
</html>
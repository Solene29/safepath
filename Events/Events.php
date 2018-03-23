 <html>
<head>
    <script src="https://code.jquery.com/jquery-2.1.4.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.3/moment.js"></script>

</head>
<body>
    
<h2>Upcoming Events:</h2>
<div id="events"></div>
<!-- Tab links -->
<div class="tab">
  <button class="tablinks" onclick="openCategory(event, 'Arts')">Arts</button>
  <button class="tablinks" onclick="openCategory(event, 'Music')">Music</button>
  <button class="tablinks" onclick="openCategory(event, 'Food & Drink')">FoodDrink</button>
  <button class="tablinks" onclick="openCategory(event, 'Other')">Other</button>
</div>

<!-- Tab content -->
<div id="Arts" class="tabcontent">
  <h3>Arts</h3>
  <script type="text/javascript" src="Events.js"></script>
</div>

<div id="Music" class="tabcontent">
  <h3>Music</h3>
  <p>Paris is the capital of France.</p> 
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
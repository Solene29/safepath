<!DOCTYPE html>
<html >
  <head>
    <meta charset="utf-8">
    <title>Home</title>
    <!-- Mobile Meta -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="css/bootstrap.css" rel="stylesheet"> 
    <link href="css/style.css" rel="stylesheet"> 
    <!-- JavaScript --> 
    <script type="text/javascript" src="js/jquery.min.js"></script>
    <script type="text/javascript" src="js/bootstrap.min.js"></script>
    <script type="text/javascript" src="js/jquery.backstretch.min.js"></script>
    <!-- function Scripts -->
    <script type="text/javascript" src="js/function.js"></script>
    <!-- animation effect -->
    <link href="css/animate.min.css" rel="stylesheet"> 
    <script src="js/wow.min.js"></script>
    <script>
    new WOW().init();
    </script>
    <style type="text/css">
  .mobileHide { display: inline; } 

  /* Smartphone Portrait and Landscape */ 
  @media only screen 
    and (max-device-width : 700px) 
  { 
     .mobileHide { display: none;}
  }
</style>
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
                <a href="index.php"><img id="logo" src="images/logo3.png" alt="Worthy"></a>
              </div>
              <!-- name-and-slogan -->
              <div class="logo-section smooth-scroll">
                <div class="brand"><a href="index.php">SAFEPATH</a></div>               
              </div>
            </div>
            <!-- header-left end -->
          </div>
          <div class="">
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
                        <li><a href="index.php">Home</a></li>
                        <li><a href="events/index.php">Events</a></li>
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
    <div class="banner">
      <div class="banner-image"></div>
      <div class="banner-caption">
        <div class="container">
          <div class="row">          
            <div class="caption-data" style="margin-top: 0px; opacity: 1;" data-animation-effect="fadeIn">
                </br>
                </br>
                </br>
                </br>
                <div class="mobileHide"><h1>SAFE PATH</h1></div>
                <h3 class="">shows you safer paths to events.</h3>
                <div style="color: #C0C0C0;"> There’s nothing worse than coming out of an amazing event and then having to navigate deserted backstreets home. SafePath provides the tools to make better safety decisions, and reduces the risk of a great night turning bad. </div>
                <div class="contact-form">
                  <a href="events/index.php"><button class="btn cta-button" style="font-family: ‘Open Sans’, sans-serif;">go to events</button></a>
                </div>
              </div>
          </div>
        </div>
      </div>
    </div>
    <!-- banner end -->
    <!-- footer start --> 
    <footer id="footer">
      <div class="subfooter">
        <div class="container">
          <div class="row">
            <div class="col-md-12">
              <p class="text-center">FIT5120 &copy; 2018.Sympatico</p>
            </div>
          </div>
        </div>
      </div>
    </footer>
    <!-- footer end -->
  </body>
</html>

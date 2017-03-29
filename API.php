<?php

 session_start(); //Start the session
 if (!isset($_SESSION['userData']))
    header("Location: index.php");

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>AstroWars.in|API</title>
    <link rel="shortcut icon" href="images/logo.png" />
    <!-- Bootstrap core CSS -->
    <link href="bootstrap/css/theme.css" rel="stylesheet">
    <!-- Custom styles for this template -->
    <link href="style.css" rel="stylesheet">
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <link href='https://fonts.googleapis.com/css?family=Roboto+Slab:400,300,700,100' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Raleway:300,700,900,500' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/typicons/2.0.8/typicons.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="assets/css/pushy.css">
    <link rel="stylesheet" href="assets/css/masonry.css">
    <link rel="stylesheet" href="assets/css/animate.css">
    <link rel="stylesheet" href="assets/css/magnific-popup.css">
    <link rel="stylesheet" href="assets/css/odometer-theme-default.css">

</head>
<body class="">
<!-- Main Menu -->
      <nav id="style-1" class="pushy pushy-left scrollbar">
   	 <ul class="list-unstyled force-overflow">
       
	    <li class="colorcyan text-center">
	    <img src=<?php  session_start(); if(isset($_SESSION[userData])){
	    echo $_SESSION['PICT']; 
	    }
	    else{
	    echo "http://icons.veryicon.com/png/System/Square/power.png";
	    }?> class="imag" alt="profile picture" id="" width="100px" height="100px "</li>
            <li id="profile_name" class="colorcyan text-center">Hi, <?php  
            if(isset($_SESSION[userData])){
	     echo $_SESSION['NAME'] ; 
	    }
	    else{
	    echo "Guest";
	    }
            
            
            
            ?></li>
            <li><a href="index.php#home">Home</a></li>
            <li><a href="index.php#rules">Rules</a></li>            
            <li><a href="API.php#API">API</a></li>         
            <li><a href="code.php">Code</a></li>
            <li><a href="fight.php">Fight</a></li>
            <li><a href="record.php">Record</a></li>
            <li><a href="logout.php"><?php if(isset($_SESSION[userData])){
             echo "Log out";
            }
            else{
            echo "Log In";
            } ?></a></li>
        </ul>
      </nav>

<!-- Site Overlay -->
<div class="site-overlay"></div>

<header id="home">
    <div class="container-fluid">
        <!-- change the image in style.css to the class header .container-fluid [approximately row 50] -->
        <div class="container">
            <div  class="row">
                <div class="col-md-1 col-xs-10">
                            <div class="menu-btn"><span class="hamburger"><i class="fa fa-bars"></i></span></div>
                        </div>
                        <div class="col-md-4 col-md-offset-8 col-xs-2">
                            <a href="#" class="thumbnail logo">
                                <img src="images/your_logo.png" alt="" class="img-responsive">
                            </a>
                        </div>
            </div>
            <div class="cyancolor">
                <p>
                    <h2 class="red">Functions</h2>
                    <h3>this.collision(robot)</h3>
                    It is a function that will execute when user's Shuttle will collide with opponent's shuttle. <br>
                    <h3>this.inView(robot)</h3>
                    It is a function that will execute when opponent's shuttle is at deg(90) straight in user's shuttle.
                    <br>
                    <h3>this.bulletHit(robot)</h3>
                    It is a function that will execute when user's shuttle is hitted by opponent's shuttle. <br>
                    <h3>this.wallHit(robot)</h3>
                    It is a function that will execute when user's shuttle hitted by wall. <br>
                    <h3>this.idle(robot)</h3>
                    It is a function that will execute when Position of user's shuttle is not performing any opration. <br>
		</div>
		<div class="cyancolor">
                  
                    <h2 class="red">Events</h2>
                    <h3>robot.rotatemissileGun(deg)</h3>
                    if you want to rotate missileGun then use this event in your function.
                    <h3>robot.rotatespaceShip(deg)</h3>
                    if you want to rotate spaceShip then use this event in your function.
                    <h3>robot.firefireBall()</h3>
                    if you want to fire an fireBall then use this event in your function.
                    <h3>robot.firemissile()</h3>
                    if you want to fire an missile then use this event in your function.
                    <h3>robot.forward(value)</h3>
                    if you want to move your spaceShip forward then use this event in your function.
                    <h3>robot.backward(value)</h3>
                    if you want to move your spaceShip backward then use this event in your function.

                </p>
            </div>

        </div>
    </div>
</header>



<!-- Bootstrap core JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<script src="assets/js/jquery.min.js"></script>
<script src="bootstrap/js/bootstrap.min.js"></script>
<script src="https://netdna.bootstrapcdn.com/twitter-bootstrap/2.0.4/js/bootstrap-scrollspy.js"></script>
<!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
<script src="assets/js/ie10-viewport-bug-workaround.js"></script>
<script src="http://masonry.desandro.com/masonry.pkgd.js"></script>
<script src="assets/js/masonry.js"></script>
<script src="assets/js/pushy.min.js"></script>
<script src="assets/js/jquery.magnific-popup.min.js"></script>
<script src="assets/js/wow.min.js"></script>
<script src="assets/js/scripts.js"></script>
<script src="assets/js/odometer.js"></script>
</body>
</html>



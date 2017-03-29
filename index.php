<?php    
//original

  //profile information will be fetched and pass to the User class
//Include GP config file && User class
include_once 'gpConfig.php';
include_once 'User.php';


if(isset($_GET['code'])){
	$gClient->authenticate($_GET['code']);
	$_SESSION['token'] = $gClient->getAccessToken();
	header('Location: ' . filter_var($redirectURL, FILTER_SANITIZE_URL));
         
}

if (isset($_SESSION['token'])) {
	$gClient->setAccessToken($_SESSION['token']);
               

}

if ($gClient->getAccessToken()) {
	//Get user profile data from google
	$gpUserProfile = $google_oauthV2->userinfo->get();
	
	//Initialize User class
	$user = new User();
	
	//Insert or update user data to the database
    $gpUserData = array(
        'oauth_provider'=> 'google',
        'oauth_uid'     => $gpUserProfile['id'],
        'first_name'    => $gpUserProfile['given_name'],
        'last_name'     => $gpUserProfile['family_name'],
        'email'         => $gpUserProfile['email'],
        'tank_name'     => $gpUserProfile['given_name'].$gpUserProfile['id'],
        'gender'        => $gpUserProfile['gender'],
        'locale'        => $gpUserProfile['locale'],
        'picture'       => $gpUserProfile['picture'],
        'link'          => $gpUserProfile['link']
    );
    $userData = $user->checkUser($gpUserData);
	
	//Storing user data into session
	$_SESSION['userData'] = $userData;
	$_SESSION['TNKNM']=$gpUserProfile['given_name'].$gpUserProfile['id'];
	$_SESSION['NAME']=$gpUserProfile['given_name'];
	$_SESSION['PICT']=$gpUserProfile['picture'];
	//Render google profile data
    if(!empty($userData)){
        
       $output1 = '<br/>Logout from <a href="logout.php">Google</a>'; 
    }else{
     //   $output = '<h3>Some problem occurred, please try again.</h3>';
    }
} else {
	$authUrl = $gClient->createAuthUrl();
	$output = '<a id="logingoogle" class="btn btn-danger" role="button" href="'.filter_var($authUrl, FILTER_SANITIZE_URL).'"><i  class="fa fa-google" aria-hidden="true"></i> Login with Google</a>';
    
}


?>




<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">
        <title>AstroWars.in</title>
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
                    <div class="jumbotron">
                       <h3 style="background-color: rgba(49, 50, 71, 0.77);"> <small>Got bored of playing games!<br/>
                            Here is another challenge for you to code the behavior of the Spaceship
                            and its behaviors in response to events. <br>Astro-Wars will constitute of
                            two players Spaceship going against each other. <br/>First player to finish
                            off the other Spaceship by bringing its health to zero will be the winner. <br/>
                            So,its time to rule your own planet !!! </small></h3><br>

                        
			<div><?php echo $output; ?></div>
                    </div>

               
        </header>
       <section id="rules" class="number wow fadeInUp" data-wow-delay="300ms">
            <!-- change the image in style.css to the class .number .container-fluid [approximately row 102] -->
            <div class="container-fluid">
                <div class="container">
                    <div class="row">
                        <div class="col-md-6 opaline col-md-offset-6">
                            <div class="row">
                                <div class="col-md-offset-1 col-md-10">
                                    <h2 class="red">Rules</h2>
                                    <ul>
                                        <li>You will not attempt to break the ship by renaming default functions and ship name.</li>
                                        <li>One person, One Account.(However, not one account, one person)</li>
                                        <li>Javascript is used. API to control the Spaceship will be provided.</li>
                                        <li>Any attempt at unethical behaviour,such as attempts at XSS attacks and such will result in immediate disqualification.</li>
                                        <li>The codes will be locked at 2:00 A.M., 22/01/2017. All submissions will enter a tournament and one shall emerge victorious.</li>
                                        <li>The battles you fight before the tournament are only for practice. Your win record may factor in only in case of a draw.</li>
                                        <li>You will have fun(Yes, this is a rule)</li>
                                    </ul>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section id="contacts" class="story wow fadeInUp" data-wow-delay="300ms">
            <!-- change the image in style.css to the class .story .container-fluid [approximately row 141] -->
            <div class="container-fluid">
                <div class="container">
                    <div class="row">
                        <div class= "col-md-6 opaline">
                            <div class="row">
                                <div class=" helpblock  col-md-10 col-md-offset-1">
                                    
                                    <h2 class="red">Contact Us</h2>
                                    <p>For any further queries <br>
                                        Call    : 7610000894 <br>
                                        Whatsapp: 9782897321 <br>
                                        E-Mail    : astrowars2017@astrowars.in <br>

                                    </p>


                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

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

<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');
  ga('create', 'UA-34344036-1', 'auto');
  ga('send', 'pageview');
</script>
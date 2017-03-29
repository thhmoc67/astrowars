<!DOCTYPE html>
<html lang="en">
    <head>
	    <meta http-equiv="X-UA-Compatible" content="IE=edge">
	    <meta name="viewport" content="width=device-width, initial-scale=1">
	    <meta name="description" content="">
	    <meta name="author" content="">
	    <title>AstroWars|Fight</title>
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
    <body>

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
            <li><a href="API.php">API</a></li>         
            <li><a href="code.php">Code</a></li>
            <li><a href="fight.php">Fight</a></li>
            <li><a href="#">Record</a></li>
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
	                <<div class="col-md-1 col-xs-10">
                            <div class="menu-btn"><span class="hamburger"><i class="fa fa-bars"></i></span></div>
                        </div>
                        <div class="col-md-4 col-md-offset-8 col-xs-2">
                            <a href="#" class="thumbnail logo">
                                <img src="images/your_logo.png" alt="" class="img-responsive">
                            </a>
                        </div>
	            </div>
	            	  <div class="jumbotron">
	                <div class="list-type2">
 <h3>Spaceships  are getting Ready to Lock Horns</h3>
	          
	                    <ol>
	                    
	                    <!--  -------------------------------Php Code-------------------------- -->
			                    <?php
			        session_start();
				error_reporting( ~E_DEPRECATED & ~E_NOTICE );
				// but I strongly suggest you to use PDO or MySQLi.
			if (!isset($_SESSION['userData']))
			    header("Location: index.php");
				
				define('DBHOST', 'localhost');
				define('DBUSER', 'upasana');
				define('DBPASS', 'anasapu@123');
				define('DBNAME', 'Astrowars');
				
				$conn = mysql_connect(DBHOST,DBUSER,DBPASS);
				$dbcon = mysql_select_db(DBNAME);
				
				if ( !$conn ) {
					die("Connection failed : " . mysql_error());
				}
				
				if ( !$dbcon ) {
					die("Database Connection failed : " . mysql_error());
				}
                                    $userid = $_SESSION[TNKNM];
                                  echo $userid;			            

			            $res = mysql_query(
"select * from fights,users where (fights.player1_id=users.tank_name OR fights.player2_id=users.tank_name) AND users.tank_name='" . $userid."';" ) ; 
					
								 
					while($row = mysql_fetch_array($res)){
								//TODO: properly render things
								
								if($row["player1_id"] ==$userid){
									$p = 1;
									$enemy = 2;
								}
								else{
									$p = 2;
									$enemy = 1;
								}
								
								
								
								if($row["result"] == 0){
									$str = "Pending Result";
									$link = "";
								}
								else{
									
									$link = "viewfight.php?fight_id=".$row["fight_id"];
									
									if($row["winner"] + 1 == $p){
										$str = " you Won Battle";
									}
									elseif($row["winner"] == 2 || $row["winner"] == -1){
										$str = "Draw Battle";
									}
									elseif($row["winner"] == -10){
										$str = "REJECTED!";
										$link = "";
									}
									elseif($row["winner"] == -9){
										$str = "Battle ERROR!!";
										$link = "";
									}
									else{
										$str = "You Lost Battle";
									}
								}
								
							       $name = $row["player".$enemy."_id"];
							     
								$qres = mysql_query("select * from users where tank_name='". $name."'");
								$qres = mysql_fetch_array($qres);
								
								
								?>
			                       
			                       <li><a href="<?php echo $link ?>" ><?php echo $str." from ".$qres["first_name"];  ?></a></li>
			                          <?php
								
								}			

					
 
			?>
			      
			     <?php ob_end_flush(); ?>
	                    <!-- ------------------------------------------------------------ -->
	                    
	                        
	                    </ol>

                             

                            

	                </div>
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
        
        
        
       
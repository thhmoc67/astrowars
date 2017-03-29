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

	<script>
		var tName = "<?php echo $_SESSION["TNKNM"]; ?>";
		var defaultText = `var ` + tName +` = function(){\n	this.collision = function(robot){\n		//Commands for collision\n	};\n	this.inView = function(robot){\n\n		//Commands when the other robot is in View\n	};\n	this.bulletHit = function(robot){\n		//Commands when you are hit by enemy's bullet\n	};\n	this.wallHit = function(robot){\n		//Commands when you hit the wall\n	};\n	this.idle = function(robot){\n		//Commands when your tank has nothing to do\n	};\n}`;
		var mycode=defaultText ;
		
		<?php
			$userid =$_SESSION['TNKNM'];
			include("connect.php");
			$query = "select * from users where tank_name= \"". $userid ."\"";
			$res = mysqli_query($con,$query);
			$num = mysqli_num_rows($res);
			//if($num > 0){
				$row = mysqli_fetch_array($res);
				$code = $row[14];
			if($code){
				$code = str_replace(array("\n","\r\n"),"\\n",$code);
				$code = str_replace(array("\""),"\\\"",$code);
				$code = str_replace(array("\'"),"\\\'",$code);
				echo "mycode= \"" . $code . "\"";
			}
			//}
		
		?>
		
		
		/* `var ` + tName +` = function(){\n	this.collision = function(robot){\n		robot.rotateCannon(360);\n	}\n	this.inView = function(robot){\n		robot.fire();\n		robot.fire();\n		robot.fire();\n	}\n	this.bulletHit = function(robot){\n		robot.forward(200);\n		robot.backward(200);\n	}\n	this.wallHit = function(robot){\n		robot.rotateTank(180);\n		robot.forward(100);\n	}\n	this.idle = function(robot){\n		robot.rotateTank(360);\n		robot.forward(200);\n	}\n}`;*/
		
		
		
		var sampleText =`var ` +tName +` = function(){
    this.collision = function(robot){
        robot.rotatemissileGun(360);
    }
    this.inView = function(robot){
        robot.firefireBall();
        robot.firemissile();
        robot.firemissile();
    }
    this.missileHit = function(robot){
        robot.forward(200);
        robot.backward(200);
    }
    this.wallHit = function(robot){
        robot.rotatespaceShip(180);
        robot.forward(100);
    }
    this.idle = function(robot){
        robot.rotatespaceShip(360);
        robot.forward(200);
    }
}`;
		
		
		
		
		
		
		
		
		
		var saveCode = function(e){
			if(e.innerHTML=="Saving..."){
				return;
			}
			var c = confirm("Save the Code?");
			if(c){
				e.innerHTML="Saving...";
				var codeText = editor.getValue();	
				if(codeText.length > 10240){
					alert("Your code cannot be greater than 10KB");
					return;
				}
				$.ajax({"url":"savecode.php","data":{"code":codeText},"type":"post","error":function(){alert("Could not save code");e.innerHTML = "Save Code";},"success":function(data){console.log(data);e.innerHTML = "Save Code";}});

alert("Ready To GO!!  Code saved");
					return;
			}
		}
	</script>

    <style type="text/css" media="screen">
    #editor {
        margin: 0;
        position: relative;
        top: 0;
        bottom: 0;
        left: 0;
        right: 0;
        height: 400px;
    }
    </style>

</head>
<body class=""onload=" editor.setValue(mycode);">
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
                                <img src="images/your_logo.png" alt="astrowarslogo" class="img-responsive">
                            </a>
                        </div>
            </div>

            <h3 style="color: antiquewhite"> Spaceship  Feeding Time.....</h3>
            <h4>Do not change default varible names and functions. It wrecks your Spaceship.</h4>
            <div id="editor" style="height:600px;margin:20px;"></div>
            <style>
							.btn-primary{
								
								
								margin:25px;
								display:inline-block;
								width:150px;
								margin-bottom:0px;
				
							}
						</style>
						
						
						
						<div class="btn btn-primary"  onClick="editor.setValue(defaultText);"> Opening Tank</div>						
						<div class="btn btn-primary"  onClick="editor.setValue(sampleText);">Need Help</div>						
						<div class="btn btn-primary"  onClick="saveCode(this);">Pump IT</div>						
						<div class="btn btn-primary" > <a  href='/code.php' style="color:#fff;text-decoration: none;">Back to Fueling</a></div>
						
						
						
						
						
            
            <br>
            
            <script src="src-noconflict/ace.js" type="text/javascript" charset="utf-8"></script>
            <script>
                var editor = ace.edit("editor");
                editor.setTheme("ace/theme/twilight");
                editor.session.setMode("ace/mode/javascript");
            </script>


        </div>
    </div>
</header>

    <br>
    <br>	











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
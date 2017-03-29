<?php
		session_start();

		include("connect.php");
		
		$p_id = $_REQUEST["spaceShipname"];
		$spaceShipname= $_REQUEST["player_id"];
		
		$query = "select * from users where oauth_uid={$spaceShipname}";
		$res = mysqli_query($con,$query);
		
		$num = mysqli_num_rows($res);
		if($num == 0){
			
			
			$defaultText = "var " . $p_id . " = function(){\n	this.collision = function(robot){\n		//Commands for collision\n	};\n	this.inView = function(robot){\n\n		//Commands when the other robot is in View\n	};\n	this.bulletHit = function(robot){\n		//Commands when you are hit by enemy's bullet\n	};\n	this.wallHit = function(robot){\n		//Commands when you hit the wall\n	};\n	this.idle = function(robot){\n		//Commands when your tank has nothing to do\n	};\n}";

			echo $defaultText;
			return;
		}
		
		$row = mysqli_fetch_array($res);
		
		echo $row["user_code"];
		
?>
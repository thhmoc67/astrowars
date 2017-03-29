<?php
session_start();
				
			if (!isset($_SESSION['userData']))
			    header("Location: index.php");
	include("connect.php");
	$fight = $_REQUEST["fight_id"];
	$query = "select * from fights where fight_id = {$fight}";
	$res = mysqli_query($con,$query);
	if(mysqli_num_rows($res) <1){
		echo "Improper behaviour will get you nowhere.";
		return;
	}
	$row = mysqli_fetch_array($res);
	if($row["result"] == 0){	
		echo "DO NOT TEST MY PATIENCE!";
		return;
	}
?>
<html>
	<head>
		<script>
		<?php
			echo "var this_fight_id=".$fight;
		?>
		</script>
		<script src="jquery-2.2.1.min.js" type="text/javascript"></script>
		<script src="FrontEnd_of_AstroWars.js" type="text/javascript"></script>
		<link rel="stylesheet" href="game.css" />
	</head>
	<body>
		<div id="arena" style="background-image:url('images/background.svg');" class="arena">
		
			
		
			<div class="healthbars">
				<div style="position:absolute;top:10px;left:10px;width:100px;border:solid 1px white;height:10px;">
					<div id="player_0_health" style="position:absolute;top:0px;left:0px;background-color:#00b4ff;width:100px;height:10px;transition:all 0.2s;"></div>
				</div>
				
				<div style="position:absolute;top:10px;left:380px;width:100px;border:solid 1px white;height:10px;">
					<div id="player_1_health" style="position:absolute;top:0px;left:0px;background-color:#00b4ff;width:100px;height:10px;transition:all 0.2s;"></div>
				</div>
			</div>
			<div class="spaceShip" id="spaceShip1">
				<div class="spaceShipBody" id="spaceShipBody1"></div>
				<div class="missileGun" id="missileBody1"></div>
			</div>
			<div class="spaceShip" id="spaceShip2">
				<div class="spaceShipBody" id="spaceShipbody2"></div>
				<div class="missileGun" id="missileGunBody2"></div>
			</div>
			<div class id="missile">
			</div>
			<div class id="fireball">
			</div>
			
		</div>
	</body>
</html>
<?php
		session_start();
		if(!isset($_SESSION['admin'])){
			header("location:index.php");
		}
		include("connect.php");
		
		$fight = $_REQUEST["fight_id"];
		
		$query = "select `fight_id`, `player1_id`, `player2_id`, `result`, `winner` from fights where fight_id=".$fight . " AND result = 0";
		$res = mysqli_query($con,$query);
		
		$num = mysqli_num_rows($res);
		if($num == 0){
			echo "The fight is already over";
			return;
		}
		
		$row = mysqli_fetch_array($res);
		
		$player1 = $row["player1_id"];
		$player2 = $row["player2_id"];
		
		
		
		$query = "select * from users where tank_name='{$player1}' OR tank_name='{$player2}'";
		
		
		$res = mysqli_query($con,$query);
		
		if(mysqli_num_rows($res) < 2){
			echo "You are a bad bad boy. No cookie for you.";
			return;
		}
		$row = array();
		$row[0] = mysqli_fetch_array($res);
		$row[1] = mysqli_fetch_array($res);
		var_dump($row);
		if($row[0][5] == $player1 ){
			$tank1 = $row[0][2];
			$tank2 = $row[1][2];
		}
		else{
			$tank1 = $row[1][2];
			$tank2 = $row[0][2];
		}
	
		echo "<script>var player_ids=[\"{$tank1}\",\"{$tank2}\"];var fight_id = {$fight};var players = [\"{$player1}\",\"{$player2}\"]</script>";
		
?>
<script src="jquery-2.2.1.min.js" type="text/javascript"></script>
<script src="backend_code.js" type="text/javascript"></script>
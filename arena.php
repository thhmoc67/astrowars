<?php
	 session_start(); //Start the session
 if (!isset($_SESSION['userData']))
    header("Location: index.php");

		include("connect.php");

                /*echo "NO MORE FIGHTING! The results are being compiled now!"; return;*/


		if(!isset($_GET["enemy"])){
			echo "<a href='/' >Oops, there seems to be a problem. Click here to redirect yourself.</a>";
			return;
		}
		
		
		$player1 = $_SESSION[TNKNM];
		$player2 = $_REQUEST["enemy"];
		$query = "select fight_id from fights where player1_id = '{$player1}' AND player2_id = '{$player2}'  AND result = 0";
		
		$res = mysqli_query($con,$query);
		
		if(mysqli_num_rows($res) > 0){
			echo "You already have a fight scheduled with this opponent. Please be patient! Go to your <a href='/record.php'>record</a> or <a href='/fight.php'>pick another fight.</a>";
			return;
		}
		
		
		$query = "INSERT INTO fights( player1_id, player2_id, result, winner) VALUES('$player1','$player2',0,0)";
		
		mysqli_query($con,$query);
		
		echo "Your fight has been initiated. The result will appear on your <a href='/record.php' >record</a> as soon as the tanks are finished blowing each other to smithereens. Until then, take a break, have a cookie.";
	
		
		
?>
<?php
	
		include("connect.php");
		
		$f_id = $_REQUEST["fight_id"];
		
		$query = "select * from fights where fight_id={$f_id}";
		$res = mysqli_query($con,$query);
		
		
		$row = mysqli_fetch_array($res);
		
		echo $row["result_text"];
		
?>
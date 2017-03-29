<script type="text/javascript" src="jquery-2.2.1.min.js"></script>
<?php
	session_start();
	if(!isset($_SESSION["admin"])){
		header("location: index.php");
	}
	include("connect.php");
	
	$query = "select * from fights where result=0";
	
	$res = mysqli_query($con,$query);
	
	echo "<ul id='list'>";
	
	while($row = mysqli_fetch_array($res)){
		echo "<li>";		
		echo "<a href='execute.php?fight_id=". $row["fight_id"] ."' target='_blank'>Click here!</a>";
		echo "</li>";		
	}
	
	echo "</ul>";
?>
<script>
$(document).ready(function(){
	
	var a = $("#list").children().each(function(key,value){
			//console.log($(value).children()[0].href);
			var popup = window.open($(value).children()[0].href);
			popup.blur();
			window.focus();
	});
	setTimeout(function(){
		window.location.href = window.location.href; 
	},10000);
});
</script>
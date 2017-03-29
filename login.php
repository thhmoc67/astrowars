<?php
session_start();
	if(isset($_SESSION["admin"])){
		header("location: index.php");
	}
	
	if(isset($_POST["submit"])){
		if($_POST["username"] == "democ" && $_POST["password"] == "shikhar"){
			$_SESSION["admin"] = "admin";
			header("location: index.php");
		}
	}
?>

<form method="POST" action="login.php">
<input type="text" name="username" /><br>
<input type="password" name="password" /><br>
<input type="submit" name="submit" value="SUBMIT!" />
</form>

<?php
?>
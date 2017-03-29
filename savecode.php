<?php
session_start();

	if(isset($_SESSION['userData'])==""){
		http_response_code(404);
		return;
	}
	include("connect.php");
	$userid = $_SESSION['TNKNM'];
	$code = mysqli_real_escape_string( $con,$_REQUEST["code"]);
	//$query = "select * from codes where user_id = " . $userid;
	//echo $userid;
	//$res = mysqli_query($con,$query);
	//$num = mysqli_num_rows($res);
	
	//if($num == 0){
		//$query = "insert into codes(`user_id`,`user_code`) values(" . $userid. ", \"". $code ."\")";
//	}
	//else{
		//$query = "update users set user_code = \" ". $code ." \" where tank_name= " . $userid;
	//}
	//$query = "update users set user_code = \" ". $code ." \" where tank_name= " . $userid;
	//$query ="UPDATE users SET user_code=\"". $code.\" " WHERE tank_name=/"".$userid.\";
	//$query = "UPDATE users SET user_code= '$code' WHERE tank_name = '$userid' ";
	$query = "update users set user_code = \"". $code ."\" where tank_name = \"". $userid ."\"";
	mysqli_query($con,$query);

?>
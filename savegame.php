<?php

	session_start();
	if(!isset($_SESSION['admin'])){
		http_response_code(404);
		return;
	}
	
	include("connect.php");
	$fight = $_REQUEST["fightid"];
	$winner = $_REQUEST["winner"];
	$_REQUEST["savedata"] = mysqli_real_escape_string($con,$_REQUEST["savedata"]);
	
	$query = "UPDATE `fights` SET `result`=1,`winner`={$winner},`result_text`=\"{$_REQUEST["savedata"]}\" WHERE fight_id = {$fight} AND result = 0";
	$res = mysqli_query($con,$query);
	
	if($winner == -9){
		$p_id = $_REQUEST["player_id"];
		$msg = $_REQUEST["e_msg"];
		$query = "INSERT INTO `messages`( `message`, `user_id`, `status`) VALUES (\"{$msg}\",{$p_id},0)";
		$res = mysqli_query($con,$query);
	}
	
	echo $query;
?>
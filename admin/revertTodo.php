<?php

session_start();
include "../inc/config.php";
setcookie(session_name(),session_id(),time()+$sessionTime,"/");
if(isset($_SESSION["role"]) && $_SESSION["role"]==ADMIN){
	include "../inc/mysql.php";
	
	switch($_GET["type"]){
		case 1: //课程单元
			$mysql->query("update courseunit set deleted=0 where id=".$_GET["id"]);
			echo "<script>location.href=document.referrer;</script>";
			break;
		case 2: //课程单元
			$mysql->query("update course set deleted=0 where id=".$_GET["id"]);
			echo "<script>location.href=document.referrer;</script>";
			break;
		case 3: //课程单元
			$mysql->query("update user set deleted=0 where id=".$_GET["id"]);
			echo "<script>location.href=document.referrer;</script>";
			break;
	}
}else{
	die("What are you doing?");
}


?>
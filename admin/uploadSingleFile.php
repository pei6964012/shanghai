<?php

set_time_limit(0);

session_start();
include "../inc/config.php";
setcookie(session_name(),session_id(),time()+$sessionTime,"/");

if(isset($_SESSION["role"]) && ($_SESSION["role"]==ADMIN || $_SESSION["role"]==TEACHER)){
	$dir = $_POST["dir"];
	if(isset($_FILES['file'])){
		if($_FILES['file']['error'] == 0){
			$srcfile  = $_FILES['file']['tmp_name'];
			$title = $_FILES['file']['name'];
			
			if(move_uploaded_file($srcfile,iconv("utf-8","gb2312",$dir.$title))){			

				echo "<script>location.href=document.referrer;</script>";

			}else{
				die("上传文件时发生错误");
			}
		}
	}
}

?>
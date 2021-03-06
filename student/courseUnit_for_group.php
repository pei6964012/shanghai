<!DOCTYPE html
     PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
     "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<title>商飞学习管理系统</title>

<style type="text/css" media="screen, projection">
/*<![CDATA[*/
@import "../css/default.css";
@import "../css/main.css";
/*]]>*/
</style>

<link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

<script type="text/javascript" src="../js/jquery.js"></script>
<script type="text/javascript" src="../js/function.js"></script>
<script type="text/javascript" src="../js/slides.min.jquery.js"></script>
<script type="text/javascript">
        (function ($) {
           try {
                var a = $.ui.mouse.prototype._mouseMove; 
                $.ui.mouse.prototype._mouseMove = function (b) { 
                b.button = 1; a.apply(this, [b]); 
                } 
            }catch(e) {}
        } (jQuery));
    </script>
 
<script type="text/javascript">
    $(function(){
       $(window).scroll(function(){
         $("#footer").css({"left":"0","bottom":"0"});
       });
    });
	function f_remove(type,cuid,id){
		//if(confirm("确定要移除吗？")){
			$.ajax({				
				type: "GET",
				url: "delete.php",
				data:"type="+type+"&cuid="+cuid+"&id="+id,
				success:function(msg){
					//$("body").html(msg);
				}
			});
		//}
	}
</script>
</head>
<body>

<div id="wrapper">

<?php 
session_start();
setcookie(session_name(),session_id(),time()+600,"/");
if(!(isset($_SESSION["userid"]) && $_SESSION["userid"]!=0)){
	header("Location:index.php");
	exit();
}

include "../inc/mysql.php";
include "../inc/function.php";
include "../inc/student_header_in_studentdir.php";
?>

<div class="clear">&nbsp;</div>
<div id="main"> <!-- start of #main wrapper for #content and #menu divs -->
<!--   Begin Of script Output   -->
<div id="content" class="maxcontent">

	<div id="content_with_menu">
		<div id="container">
		
			<div class="info">【<?php echo $_GET["title"];?>】所含有的课程单元列表</div>
			<div class="list_courseUnit">
			<ul>
			<?php

				$res = $mysql->query("select * from courseunitgroup where groupid=".$_GET["id"]);
				$arr = $mysql->fetch_array($res);
				$ids = substr($arr["courseunitids"],1,-1);
				//die("select * from courseunit where deleted=0 and id in ($ids) order by time desc");
				$res = $mysql->query("select * from courseunit where deleted=0 and id in ($ids) order by time desc");
				while($arr = $mysql->fetch_array($res)){
					if($arr["lpid"]){
						echo "<li><a href='scormShow.php?id=$arr[lpid]' target='_blank'><img src='../img/scorm.png' width='25' title='Scorm'>".$arr["title"]."</a></li>";
					}else{
						//echo getLastestCourseUnitAttachmentidBycuid($arr["id"]);
						//die();
						$attachmentArr = getAttachmentinfoById(getLastestCourseUnitAttachmentidBycuid($arr["id"]));
						$href = $attachmentArr["indexfile"]==""?$attachmentArr["path"]:$attachmentArr["indexfile"];
						echo "<li><a href='".$href."' target='_blank'>".$arr["title"]."</a></li>";
					}
					
				}
			?>
			</ul>
			</div>

			<div class="info">【<?php echo $_GET["title"];?>】所含有的课程单元组列表</div>
			<div class="list_courseUnit">
			<ul>
			<?php

				$res = $mysql->query("select * from courseunitgroup where groupid=".$_GET["id"]);
				$arr = $mysql->fetch_array($res);
				$ids = substr($arr["courseunitgroupids"],1,-1);
				//die("select * from courseunit where deleted=0 and id in ($ids) order by time desc");
				$res = $mysql->query("select * from `group` where deleted=0 and id in ($ids) order by time desc");
				while($arr = $mysql->fetch_array($res)){
					echo "<li><a href='courseUnit_for_group.php?id=$arr[id]&title=$arr[title]'>".$arr["title"]."</a></li>";
				}
			?>
			</ul>
			</div>
		</div>
	</div>

<div class="menu" id="menu">

<div class="create_portal">
<ul>
<li><a href="mycourse.php">我的课程</a></li>
<li><a href="mycourse_in.php">进行中的课程</li>
<li><a href="mycourse_undo.php">未开始的课程</li>
<li><a href="mycourse_expire.php">已过期的课程</li>
</ul>
</div>

</div></div> <div class="clear">&nbsp;</div> <!-- 'clearing' div to make sure that footer stays below the main and right column sections -->
</div> <!-- end of #main" started at the end of banner.inc.php -->

<div class="push"></div>
</div> <!-- end of #wrapper section -->

<?php include "../inc/footer.php";?>

</body>
</html>

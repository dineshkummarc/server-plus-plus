<?php 
session_start();
if(!($_SESSION['login'])){
header('Location: ./login.php');
}
?>
<html>
<head>
<style type="text/css">
#logout{
	position:fixed;
    top:20px;
    right:35px;
	font-weight:bold;
	}
	</style>
<script type="text/javascript" src="jquery.js"> </script>
<script>
$(document).ready(function(){
	$("#content").load("extract.php?call_function=1&function_name=directory_info",function(){ $("#loader").fadeOut(1500);
	$("#content").fadeIn(1500); });
});
</script> </head>
<body>
<div id="logout"><a href="logout.php">Logout</a></div>
<div id="loader"> Retrieving Server Directory Structure.Please be patient!<br />
	<div id="img_loader"><img src="./images/loading.gif"  /> </div>
</div>
<div id="content" style="display:none"> </div>
</body>
</html>

<?php
session_start();
if(filesize('config.php')==0)
{
	header('Location: ./install.php');
}

if($_SESSION['login'])
{
header('Location: ./index.php');
 
}
?>
<html>
<head>
<LINK REL="SHORTCUT ICON" HREF="./images/favicon.ico" />
<link rel="stylesheet" type="text/css" href="style.css" />
<style type="text/css">

#outerBody_containerMiddle{
	height:300px;
}

#body_container{
	height:280px;

}
#outerBody_containerBottom{
	top:387px;
}

#button_quote{
	margin-top:-36px;
}
#container{
	text-align:center;
}
table{
	font-weight:bolder;
	font-variant:small caps;
	font-family:"Palatino Linotype", "Book Antiqua", Palatino, serif;
}

</style>
</head>
<body>
<div id="container">
	<div id="header">
    	<span id="logo"></span><span id="image_logo"></span>
        </div>
<div id="menu_container"><font face="Lucida Sans Unicode, Lucida Grande, sans-serif" id="quote" color="#000000">Log In</font></div>
        <div id="outerBody_containerTop"></div>
	<div id="outerBody_containerMiddle">
    <div id="body_container">

<?php include("config.php"); ?>
<form action="login.php" method="post" id="form">
<table class="table" align="center">
<tr><td align="left" height=50px width=170px>Username<font color="#FF0000">*</font></td><td align="right"><input type="text" name="loginusername" id="loginusername" size=40/></td></tr>
<tr><td align="left" height=50px width=170px>Password<font color="#FF0000">*</font></td><td align="right"><input type="password" name="loginpswd" id="loginpswd" size=40/></td></tr>
</table>
 <input type="image" src="./images/button_green.png" alt="Submit" id="submit"/>
</form>
<div id="button_quote">LOGIN</div>
<div class="error">

<?php

if (isset($_POST['loginusername'])|| isset($_POST['loginpswd'])){  

$username=$_POST['loginusername'];
$password=$_POST['loginpswd'];

if((!$username)||(!$password))
echo "ERROR: A required field was left blank...";

else if(($username==$loginusername)&&($password==$loginpswd)){
 $_SESSION['login']="true"; 
 }
 
 
else
 echo "ERROR: Please check your username and/or password...";
}

if($_SESSION['login']){?>
<meta http-equiv="refresh" content="0; url=./index.php"> <?}
?>
</div>
</div></div><div id="outerBody_containerBottom"></div>
</body>
</html>
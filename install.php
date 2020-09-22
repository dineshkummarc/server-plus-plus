<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<LINK REL="SHORTCUT ICON" HREF="./images/favicon.ico" />
<link rel="stylesheet" type="text/css" href="style.css" />
<style type="text/css">
#container{
	text-align:center;
}
#outerBody_containerMiddle{
	height:720px;
	
}

#body_container{
	height:700px;
	

}
#outerBody_containerBottom{
	top:720px;
	
}

</style>
</head>
<title>Server ++ | Installer</title>
<body>

<div id="container">
<div id="header">
<span id="logo"></span><span id="image_logo"></span></div>
        <div id="menu_container"> </div>
        <div id="menu_container"><font face="Lucida Sans Unicode, Lucida Grande, sans-serif" id="quote" color="#000000">Installer</font></div>
    <div id="outerBody_containerTop"></div>
	<div id="outerBody_containerMiddle">
    <div id="body_container">

<?
$form_chk="false";
$con_chk="false";
$fileaddr = 'config.php';
?>

<?
 if (!file_exists($fileaddr)) 
 echo '<div class="error"> ERROR:The file "config.php" do not exists </div>';
 else if(!(filesize($fileaddr)==0))
 echo '<div class="message"> Installation has already been performed... <br/>  <a href="login.php">Click here</a> to see index page.       </div>'; 
 else if (!is_writable($fileaddr))
echo '<div class="error"> ERROR:config.php is not writable...change it\'s permission to 777 </div>'; 
 else{
 $writable="true";
 }
 

if($writable=="true"){ ?>
<div id="heading">Getting Started With Server++...</div>
<div class="header">(Fields marked <font color="#FF0000" size="-1">*</font> are mandatory)<div>
<form action="install.php" method="post" id="form">
  <table class="display" align="center" >
     <tr><td align="left" height=50px width=170px>Database Name<font color="#FF0000" size="-1">*</font></td><td align="right"><input type="text" name="dbname" id="dbname" size=40/></td></tr>
    <tr><td align="left" height=50px width=170px>Database Username<font color="#FF0000" size="-1">*</font></td><td align="right"><input type="text" name="dbusername" id="dbusername" size=40/></td></tr>
	
    <tr><td align="left" height=50px width=170px>Database Password<font color="#FF0000" size="-1">*</font></td><td align="right"><input type="password" name="dbpswd" id="dbpswd" size=40/></td></tr>
    <tr><td align="left" height=50px width=170px>Host Name<font color="#FF0000" size="-1">*</font></td><td align="right"><input type="text" name="hostname" id="hostname" value="localhost" size=40/></td></tr>
    <tr><td align="left" height=50px width=170px>Port<font color="#FF0000" size="-1">*</font></td><td align="right"><input type="text" name="port" id="port"size=40/></td></tr>
    <tr><td align="left" height=50px width=170px>Alert Time Interval(IN HOURS)<font color="#FF0000" size="-1">*</font></td><td align="right"><input type="text" name="alert_interval" id="alert_interval" value="2" size=40/></td></tr>
	<tr><td align="left" height=50px width=170px>Table Prefix</td><td align="right"><input type="text" name="prefix" id="prefix" size=40/></td></tr>
	<tr><td align="left" height=50px width=170px>E-mail<font color="#FF0000" size="-1">*</font></td><td align="right"><input type="email" name="email_id" id="email_id"size=40/></td></tr>
    <tr><td align="left" height=50px width=170px>Phone<font color="#FF0000" size="-1">*</font></td><td align="right"><input type="text" name="phone_no" id="phone_no"size=40/></td></tr>
    </table><table class="display" align="center">
    <div class="header" font-weight="400">Choose a username and password for your account...</div>
    <tr><td align="left" height=50px width=170px>Username<font color="#FF0000" size="-1">*</font></td><td align="right"><input type="text" name="username" id="username"size=40/></td></tr>
    <tr><td align="left" height=50px width=170px>Password<font color="#FF0000" size="-1">*</font></td><td align="right"><input type="password" name="password" id="password"size=40/></td></tr></table>

    <input type="image" src="./images/button_green.png" alt="Submit" id="submit"/>
    </form>
    <div id="button_quote">INSTALL</div>
    <div class="error">
    


<?php 
  if (isset($_POST['dbname']) || isset($_POST['dbusername']) || isset($_POST['dbpswd']) || isset($_POST['hostname'])|| isset($_POST['port'])|| isset($_POST['prefix'])|| isset($_POST['email_id'])|| isset($_POST['phone_no'])|| isset($_POST['username'])|| isset($_POST['password'])|| isset($_POST['alert_interval'])) {
   
    $dbname=$_POST[dbname];
    $dbusername=$_POST[dbusername];
    $dbpswd=$_POST[dbpswd];
    $hostname=$_POST[hostname];
	$port=$_POST[port];
	$prefix=$_POST[prefix];
	$email_id=$_POST[email_id];
	$phone_no=$_POST[phone_no];
	$loginusername=$_POST[username];
	$loginpswd=$_POST[password];
	$alert_interval=$_POST[alert_interval];
	
	
    if((!$dbname)||(!$dbusername)||(!$dbpswd)||(!$hostname)||(!$port)||(!$loginusername)||(!$loginpswd)||(!$email_id)||(!$phone_no)||(!$alert_interval))
    echo "ERROR: A require field was left blank...";

    else 
    $form_chk="true";


   if($form_chk=="true"){
    
	$server="$hostname".':'."$port";
	$con=@mysql_connect("$server", "$dbusername", "$dbpswd");
    if(!$con)
    echo "ERROR:Wrong username,password or host";
 
    else
    $con_chk="true";
    }

   if(($form_chk=="true") && ($con_chk=="true")){
    $db=@mysql_select_db("$dbname",$con);
	
	if(!$db)
	echo "ERROR:Wrong databasename";
	   
	else{
	
	    $table_name=server;
		if($prefix)
		$table_name="$prefix".'_'.'server';
		
	    $query = 'CREATE TABLE '. "$table_name" . ' (
	    cpu_load FLOAT,
        cpu_loadPercent FLOAT,
        cpu_noLoadPercent FLOAT,
        memory_used INT,
        memory_total INT,
        memory_usedPercent FLOAT,
        memory_freePercent FLOAT,
        swap_used INT,
        swap_total INT,
        swap_usedPercent FLOAT,
        swap_freePercent FLOAT,
        swap_in INT,
        swap_out INT,
        disk_size INT,
        disk_used INT,
        disk_available INT,
        disk_usedPercent INT,
        time_stamp BIGINT)';
		
		$result=@mysql_query("$query");

		if(!$result)
		echo "ERROR:Check administrative privileges of the given user";
		else{
			
			$time_query=@mysql_query("CREATE TABLE timestamp(time_stamp BIGINT)");
		    $file = fopen($fileaddr, "w");
    
	        $values = '<?php'."\n".'$dbname='."$dbname".';'."\n".'$dbusername='."$dbusername".';'."\n".'$dbpswd='."'"."$dbpswd"."'".';'."\n".'$hostname='."'"."$hostname"."'".';'."\n".'$port='."$port".';'."\n".'$table_name='."$table_name".';'."\n".'$loginusername='."$loginusername".';'."\n".'$loginpswd='."'"."$loginpswd"."'".';'."\n".'$email_id='."'"."$email_id"."'".';'."\n".'$phone_no='."$phone_no".';'."\n".'$alert_interval='."$alert_interval".';'."\n".'?>';
	        fwrite($file, $values);
	        fclose($file);
		
		    echo '<div class="message"> Installation has been performed successfully... <br/> <a href="login.php">Click here</a> to continue</div>';
			
			}
		    
		}
	        @mysql_close($con);
    }
 }?></div><?
}
?> 
  </div>
 </div>
</div>
 <div id="outerBody_containerBottom"></div>
 </body></html>
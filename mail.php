
<?php
	include('config.php');
	$con=mysql_connect($hostname,$dbusername,$dbpswd);
	if(!$con)
	{
	die('could not connect:'.mysql_error());
	}
	mysql_select_db($dbname,$con);
	$sql=mysql_query("SELECT time_stamp FROM timestamp ORDER BY time_stamp DESC LIMIT 1");
	$max_timestamp=mysql_fetch_array($sql);
	$sql_query=mysql_query("SELECT * FROM timestamp WHERE time_stamp='$max_timestamp[0]'");
	$time=mysql_fetch_array($sql_query);
	$current_time=time();
	
	
	$difference=$current_time-$time['time_stamp'];
	$alert=$alert_interval*3600;
	if($difference>$alert)
	{
		$sql=mysql_query("SELECT time_stamp FROM $table_name ORDER BY time_stamp DESC LIMIT 1");
		$max_timestamp=mysql_fetch_array($sql);
		$sql_query=mysql_query("SELECT * FROM $table_name WHERE time_stamp='$max_timestamp[0]'");
		$check_value=mysql_fetch_array($sql_query);
		if(($check_value['cpu_loadPercent']>60)||($check_value['swap_usedPercent']>60)||($check_value['disk_usedPercent']>60)||($check_value['memory_usedPercent']>80))
		{
		$txt = "Hello ".$loginusername."! Your server is at RISK!\nCPU LOAD-".$check_value['cpu_loadPercent']."%\nDISK USED-".$check_value['disk_usedPercent']."%\nMEMORY USED-".$check_value['memory_usedPercent']."%";
		$txt = wordwrap($txt,70);
		$subject="Alert! from Server ++ ";
		$headers = 'From: <server-plus-plus@botskool.com>';
		mail($email_id,$subject,$txt,$headers);
		
		}
		mysql_query("INSERT INTO timestamp(time_stamp) VALUES('$current_time')");
		mysql_close($con);
	}	
?>

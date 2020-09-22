<?php
/*extracting database info from config.php
check and connect to db*/
//CPU LOAD
include('config.php');
function cpu_load()
{
	global $cpu_load,$cpu_loadPercent,$cpu_noLoadPercent;
	$cpu_load=`httpd status| grep "CPU load" | awk '{print $8}' `;
    $cpu_loadPercent=`httpd status| grep "CPU load" | awk '{print $8}' | cut -d% -f1`;
	$cpu_noLoadPercent=100-$cpu_loadPercent;
}
function swap_memoryInfo()
{
	global $explode_output,$swap_outputFormat,$memory_outputFormat,$memory_usedPercent,$memory_freePercent,$swap_usedPercent,
		   $swap_freePercent;
	$vimstat_output=array();
	@exec('vmstat', &$vimstat_output);
	$vimstat_output[2]=preg_replace('/\s\s+/', ' ', $vimstat_output[2]); //stripping unwanted white characters
	$explode_output = explode(" ", $vimstat_output[2]);
	$free_output= array();
	$i=0;
	@exec('free -m ', $free_output);

	/***************************
	total used free shared buffers cached
	Mem: 1024 618 405 0 0 0
	-/+ buffers/cache: 618 405
	Swap: 0 0 0
	all of the below  is to produce the required output************************************/
	
	$free_output[3]=preg_replace('/\s\s+/', ' ', $free_output[3]); //stripping unwanted white characters
	$swap_outputFormat= explode(" ", $free_output[3]);
	@$swap_usedPercent=($swap_outputFormat[2]/$swap_outputFormat[1])*100;
	$swap_freePercent=100-$swap_usedPercent;
	/*##############MEMORY USAGE####################################################*/
	$free_output[1]=preg_replace('/\s\s+/', ' ', $free_output[1]); //stripping unwanted white characters
	$memory_outputFormat=explode(" ", $free_output[1]);
	$memory_usedPercent=($memory_outputFormat[2]/$memory_outputFormat[1])*100;
	$memory_freePercent=100-$memory_usedPercent;
}
function disk_usage()
{
	global $disk_size,$disk_used,$disk_available,$disk_usedPercent;
	$disk_size=`df -h | grep '/' | head -1| awk '{print $2}'`;
	$disk_size=explode("G",$disk_size);
	$disk_used=`df -h | grep '/' | head -1| awk '{print $3}'`;
	$disk_used=explode("G",$disk_used);
	$disk_available=`df -h | grep '/' | head -1| awk '{print $4}'`;
	$disk_available=explode("G",$disk_available);
	$disk_usedPercent=`df -h | grep '/' | head -1| awk '{print $5}'`;
}
function check_status()
{
	include('mail.php');
}
function time_stamp()
{
	global $t;
    $t=time();
}

cpu_load();
swap_memoryInfo();
disk_usage();
time_stamp();
check_status();
$con=mysql_connect($hostname,$dbusername,$dbpswd);
	if(!$con)
	{
	die('could not connect:'.mysql_error());
	}
	mysql_select_db($dbname,$con);

	$sql=mysql_query("INSERT INTO $table_name(cpu_load,cpu_loadPercent,cpu_noLoadPercent,memory_used,memory_total,memory_usedPercent,memory_freePercent,swap_used,swap_total,swap_usedPercent,swap_freePercent,swap_in,swap_out,disk_size,disk_used,disk_available,disk_usedPercent,time_stamp) VALUES('$cpu_load','$cpu_loadPercent','$cpu_noLoadPercent','$memory_outputFormat[2]','$memory_outputFormat[1]','$memory_usedPercent','$memory_freePercent','$swap_outputFormat[2]','$swap_outputFormat[1]','$swap_usedPercent','$swap_freePercent','$explode_output[6]','$explode_output[7]','$disk_size[0]','$disk_used[0]','$disk_available[0]','$disk_usedPercent','$t')");
	mysql_close($con);
	?>
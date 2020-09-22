<?php
include('config.php');
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

//operating system
function operating_system()
{
	global $operating_system;
	$operating_system=`uname -s`;
}
//operating system version
function os_version()
{
	global $os_version;
	$os_version=`uname -r`;
	
}
//server architecture
function server_architecture()
{
	global $server_architecture;
	$server_architecture=`arch`;
	
}
//server version
function server_version()
{
	global $server_version;
	$server_details=`httpd status| grep "Server Version:" | awk '{ print $3}' `;
	$server_version=explode('/',$server_details);
	
}
//MYSQL VERSION
function mysql_version()
{
	global $mysql_version;
	$server_details=`httpd status| grep "OpenSSL" | awk '{ print $1} ' `;
	$mysql_version=explode('/',$server_details);
}
//PHP Version
function php_version()
{
	global $php_version;
	$server_details=`httpd status | grep 'PHP' | awk '{print $2}'`;
	$php_version=explode('/',$server_details);
}
//restart time
function restart_time()
{
	global $server_restart;
	$server_restart=`httpd status| grep "Restart Time:"| awk '{print $3,$4,$5,$6}'`;
}
//server uptime
function server_uptime($if_ajaxCall)
{
	global $server_uptime;
	$server_uptime=`httpd status| grep "Server uptime:"| awk '{print $3,$4,$5,$6,$7,$8,$9,$10}' `;
	$server_uptime=preg_replace('/\s\s+/', ' ', $server_uptime);
	$server_uptime=preg_replace( '/\r\n/', ' ', trim($server_uptime) );
	if($if_ajaxCall=='y')
	{
		echo $server_uptime;
	}
}
//Total Traffic
function total_traffic()
{
	global $server_totalTraffic;
	$server_totalTraffic=`httpd status| grep "Total Traffic:" | awk '{print $7}' `;
}
//processor info
function processor_info()
{
	global $counter;
	$output=array();
	@exec('cat /proc/cpuinfo | grep "processor"',$output );
	$counter=0;
	for($i=0; $i<count($output); $i++)
	$counter++;
}
//cpu info
function cpu_info()
{
	global $cpu_info;
	$cpu_info=`grep Hz /proc/cpuinfo |  awk '{print $4,$5,$6,$7,$8,$9}' | grep 'CPU' | head -1`;
}
//cpu speed
function cpu_speed()
{
	global $cpu_speed;
	$cpu_speed=`grep Hz /proc/cpuinfo |  awk '{print $1,$2,$3,$4}' | grep 'MHz' | head -1 | awk '{print $4}' `;
}
//hostname and node name
function host_nodeName()
{
	global $host,$node;
	$host_nodeName=array();
	@exec("hostname", $host_nodeName);
	$host=$host_nodeName[0];
	unset($host_nodeName);
	@exec("uname -n", $host_nodeName);
	$node=$host_nodeName[0];
}
//HEADER
function server_header()
{
	global $server_header,$user_shell;
	$server_header=`curl -I http://theos.in/`;
	$user_shell=`env`;
}

//top 5 processes
function processes_info($this_count)
{
	global $top_processes,$num_ofProcesses;
	$num_ofProcesses=`ps -ef | wc -l`;
	/*if($this_count<$num_ofProcesses)
	{*/
	$top_processes=`ps -eo pcpu,pid,user,args | sort -k 1 -r | head -$this_count`;
    /*}*/
	/*else
	{*/
	//$top_processes=`ps -eo pcpu,pid,user,args | sort -k 1 -r | head -$num_ofProcesses`;
	/*}*/
	echo "<pre class='word-wrap'>$top_processes</pre>";
	echo "<br /><br />Number Of Processes: ".$num_ofProcesses;
}
//network stats
function network_stats($this_count)
{
	global $net_stat;
	$net_stat=`netstat | head -$this_count`;
	echo "<pre>$net_stat</pre>";
}
//PROCESS TREE
function process_tree()
{	
	global $ps_tree;
	$ps_tree=`pstree`;
	echo "<pre>$ps_tree</pre>"; 
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
//SSH LOGINS
function ssh_logins($this_count)
{
	global $user_details;
	$w_output=array();
	$total_users=`w | grep 'load average' | awk '{print $6}'`;
	if($total_users!=0)
	{
	
		@exec('w | grep "pt" ',$w_output);
		@exec('who',$who_output);
		$count_arr=count($w_output);
		$line_no=1;
		for($i=0; $i<$count_arr;$i++)
		{
			$user_details[$i][0]=`w | grep "pt"| head -$line_no | awk '{ print $1 }'`;
			$user_details[$i][1]=`w | grep "pt"| head -$line_no| awk '{ print $3 }' `;
			$user_details[$i][2]=`who | grep "pt"| head -$line_no| awk '{ print $5 }' `;
			$user_details[$i][3]=`who | grep "pt"| head -$line_no| awk '{ print $3,$4 }' `;
			$user_details[$i][4]=`w | grep "pt"| head -$line_no| awk '{ print $6 }' `;
			$user_details[$i][5]=`w | grep "pt"| head -$line_no | awk '{ print $7 }' `;
			$user_details[$i][6]=`w | grep "pt"| head -$line_no| awk '{ print $8,$9,$10 }' `;
			$line_no++;
		}
		echo "USER(s) Currently logged in through ssh<br>";
		$no=1;
		for($i=0; $i<$count_arr; $i++)
		{
			
			echo $no.".) Username: ".$user_details[$i][0]."<br />User Ip: ".$user_details[$i][1]."<br />Login Time: ".$user_details[$i][2]."<br />Login Date: ".$user_details[$i][3]."<br />User currently doing: ".$user_details[$i][6]."<br />CPU Time of Currently Running Processes of User: ".$user_details[$i][4]."<br /> CPU Time Of All Processes of User Since Current Login: ".$user_details[$i][5];
			echo "<br /><br />For More Details & SSH Login Histroy Please Click See More<br />SSH Login History";
			$no++;
		}
	}
	else
	{
		echo "No Current Logins to SSH!<br/><br/>SSh Login History";
	}
	last_sshLogins($this_count);
}
//last ssh logins
function last_sshLogins($this_count)
{
	global $check_login,$last_output;
	$check_sshLogin=0;
	$last_output=`last |head -$this_count`;
	echo "<pre>$last_output</pre>";
}
//to ping to server
function ping_server()
{
	global $ping_server;
	$ip_addr=` /sbin/ifconfig venet0:1 | grep 'inet addr:' | cut -d: -f2 | awk '{ print $1}' `;
	$ping_server= ` ping -c 4 $ip_addr`;
	echo "<pre>$ping_server</pre>";
}
function trace_route()
{
	global $route;
	$ip_addr=` /sbin/ifconfig venet0:1 | grep 'inet addr:' | cut -d: -f2 | awk '{ print $1}' `;
	$route=`traceroute $ip_addr`;
	echo "<pre>$route</pre>";
}
//disk usage 

// CPU PROCESSES
function cpu_processes()
{
	global $proc_run;
	$proc_run=`mpstat -P ALL`;
}
// Network Routing Table
function routing_table()
{
	global $net_rstat;
	$net_rstat=`netstat -rn`;
    echo "<pre>$net_rstat</pre>";
}	
// system activity reporter
function system_activityReporter()
{
	global $sys_actrep;
	$sys_actrep=`sar`;
}	

function apache_processes($this_count)
{	
	$apache_process=array();
	global $apache_process;
	$apache_process=`ps -ef | grep 'apache'| awk '{print $1,"     ",$2,"      ",$5,"             ",$7,"             ",$8}' | head -$this_count`;
	echo "<pre>Username      PID      Starting time       Runing time                 Command</pre>";
	echo "<pre>$apache_process</pre></br>";
}
function mysql_processes($this_count)
{
	$mysql_process=array();
	global $mysql_process;
	$mysql_process=`ps -ef | grep 'mysql'| awk '{print $1,"     ",$2,"      ",$5,"             ",$7,"                 ",$8}' |head -$this_count`;
	echo "<pre>Username      PID      Starting time       Runing time                 Command</pre>";
	echo "<pre>$mysql_process</pre></br>"; 
}
function server_load()
{
/*####################### SERVER LOAD ########################*/
	global $load,$uptime;
	$uptime = @exec('uptime'); 
	preg_match("/averages?: ([0-9\.]+),[\s]+([0-9\.]+),[\s]+([0-9\.]+)/",$uptime,$avgs); 
	$uptime = explode(' up ', $uptime); 
	$uptime = explode(',', $uptime[1]); 
	$uptime = $uptime[0].', '.$uptime[1]; 
	$start=mktime(0, 0, 0, 1, 1, date("Y"), 0); 
	$end=mktime(0, 0, 0, date("m"), date("j"), date("y"), 0); 
	$diff=$end-$start; 
	$days=$diff/86400; 
	$percentage=($uptime/$days) * 100; 
	$load=$avgs[1].",".$avgs[2].",".$avgs[3].""; 
	
}
function directory_info()
{
	$dir_tree=`tree -a /`;
	echo "<pre>$dir_tree</pre>";
}
function service_status()
{
//configure script
global $data; 
$timeout = "1"; 

//set service checks 
$port[1] = "80";       $service[1] = "Apache";                  $ip[1] =""; 
$port[2] = "21";       $service[2] = "FTP";                     $ip[2] =""; 
$port[3] = "3306";     $service[3] = "MYSQL";                   $ip[3] =""; 
$port[4] = "25";       $service[4] = "Email(POP3)";             $ip[4] =""; 
$port[5] = "143";      $service[5] = "Email(IMAP)";             $ip[5] =""; 
$port[6] = "2095";     $service[6] = "Webmail";                 $ip[6] =""; 
$port[7] = "2082";     $service[7] = "Cpanel";                  $ip[7] =""; 
$port[8] = "80";       $service[8] = "Internet Connection";     $ip[8] ="google.com"; 
$port[9] = "2086";     $service[9] = "WHM";                     $ip[9] =""; 

// 
// NO NEED TO EDIT BEYOND HERE  
// UNLESS YOU WISH TO CHANGE STYLE OF RESULTS 
// 

//count arrays 
$ports = count($port); 
$ports = $ports + 1; 
$count = 1; 

while($count < $ports){ 

     if($ip[$count]==""){ 
       $ip[$count] = "localhost"; 
     } 

        $fp = @fsockopen("$ip[$count]", $port[$count], $errno, $errstr, $timeout); 
        if (!$fp) { 
            $data .= "<td><pre>$service[$count] <img src=\"./images/offline.jpg\" height=\"12\" width=\"12\"></pre></td>"; 
        } else { 
            $data .= "<td><pre>$service[$count] <img src=\"./images/online.jpg\" height=\"12\" width=\"12\"></pre></td>"; 
            fclose($fp); 
        } 
    $count++; 
	}
} 

//to respond to particular call particular function

function call_thisFunction($thisFunction_name)
{
	switch($thisFunction_name)
	{
		case 'server_uptime': server_uptime('y');
							break;
		case 'ssh_logins': ssh_logins($_GET[count]);
						   break;
		case 'last_sshLogins': last_sshLogins($_GET[count]);
								break;
		case 'apache_processes':apache_processes($_GET[count]);
								break;
		case 'mysql_processes': mysql_processes($_GET[count]);
							  break;
		case 'processes_info': processes_info($_GET[count]);
								break;
		case 'process_tree': process_tree();
							 break;	
		case 'ping_server': ping_server();
							break;
		case 'routing_table': routing_table();
                             break;
        case 'network_stats': network_stats($_GET[count]);
                             break;
        case 'trace_route': trace_route();
                           break;
		case 'directory_info': directory_info();
								break;
							   		
	}
}

	if($_GET[from_db]==1)
	{
		$con=mysql_connect($hostname,$dbusername,$dbpswd);
		if(!$con)
		{
			die("Could Not Connect: ".mysql_error());
		}
		mysql_select_db($dbname,$con);
		$value=mysql_query("SELECT $_GET[feild_name] FROM $table_name ORDER BY time_stamp DESC LIMIT 1");
		$value_a=mysql_fetch_array($value);
		echo $value_a[0];
	}
	elseif($_GET[call_function]==1)
	{
		call_thisFunction($_GET[function_name]);
	}
	else
	{
		function call_allFunctions()
		{
			restart_time();
			server_uptime('n');
			total_traffic();
			cpu_load();
			swap_memoryInfo();
			server_load();
			cpu_info();
			disk_usage();
			cpu_processes();
			
		}
	}

?>

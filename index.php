<?php 
session_start();
if(filesize('config.php')==0)
{
	header('Location: ./install.php');
}
if(!($_SESSION['login'])){
header('Location: ./login.php');
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Server ++ | Home</title>
<?php 
	include('config.php');
	include('extract.php'); ?>
<script type="text/javascript" src="jquery.js"> </script>
<script type="text/javascript" src="script.js"> </script>
<script type="text/javascript" src="http://www.google.com/jsapi"> </script>
<link rel="stylesheet" type="text/css" href="style.css" />
<link href="facebox/facebox.css" media="screen" rel="stylesheet" type="text/css"/>
<script src="facebox/facebox.js" type="text/javascript"></script>
<script type="text/javascript">
		var i=0;
		
		var memory=new Array();
		var swap=new Array();
		var disk=new Array();
		var hour=new Array();
		var minute=new Array();
		var second=new Array();
		var year=new Array();
		var month=new Array();
		var date=new Array();
		var cpu_load=new Array();
</script>

<script type="text/javascript">
	 jQuery(document).ready(function($) {
  $('a[rel*=facebox]').facebox()
})


    </script>
	
<LINK REL="SHORTCUT ICON" HREF="./images/favicon.ico" />
</head>
<body>
<?php 

	$con=mysql_connect($hostname,$dbusername,$dbpswd);
	if(!$con)
	{
	die('could not connect:'.mysql_error());
	}
	mysql_select_db($dbname,$con);
	$sql=mysql_query("SELECT time_stamp FROM $table_name ORDER BY time_stamp DESC LIMIT 1");
	$max_timestamp=mysql_fetch_array($sql);
	$sql_query=mysql_query("SELECT * FROM $table_name WHERE time_stamp='$max_timestamp[0]'");
	$table_value=mysql_fetch_array($sql_query);
	$sql_timeline=mysql_query("SELECT cpu_loadPercent,memory_usedPercent,swap_usedPercent,disk_usedPercent,time_stamp FROM $table_name");
while($row=mysql_fetch_array($sql_timeline))
{	$t=$row['time_stamp'];
	$count++;
	$hour=date('H', $t);
	$minute=date('i', $t);
	$second=date('s', $t);
	$year=date('Y', $t);
	$month=date('m', $t);
	$date=date('d', $t);

	?>
	<script type="text/javascript">
		cpu_load[i]=<?php echo $row['cpu_loadPercent']; ?>;
		memory[i]=<?php echo $row['memory_usedPercent']; ?>;		
		swap[i]=<?php echo $row['swap_usedPercent']; ?>;
		disk[i]=<?php echo $row['disk_usedPercent']; ?>;
		hour[i]=<?php echo $hour; ?>;
		minute[i]=<?php echo $minute; ?>;
		second[i]=<?php echo $second; ?>;
		year[i]=<?php echo $year; ?>;
		month[i]=<?php echo $month; ?>;
		date[i]=<?php echo $date; ?>;
	i++;
	</script>

<?php
}
?>
	<div id="container">
	<div id="header">
    	<span id="logo"></span><span id="image_logo"></span>
        <div id="sub_header">
        	<div id="welcome_mesaage"> </div>
            <div id="login_info"> </div>
            <div id="warnings"> </div>
        </div>
       	<div id="menu_container"> 
			<div id="menu_options">
				<ul class="menu_links">
				  <li class="menu_links" id="home">
                    	<a title="Home" href="./index.php">Home</a>
                    </li>
					<li class="menu_links" id="timeline_graphs">
					  	<a title="View Detailed Graphs"href="./timeline_graphs.php">Timeline Graphs</a>
						<!--<ul id="show1" class="sub">
							<li >Memory usage</li>
							<li >Swap usage</li>
							<li >Cpu load</li>
							<li >Disk usage</li>
						</ul>-->	
	        		</li>
					<li class="menu_links" id="directory_info">
						<a title="View Directory structure" href="./directory_info.php">
					Directory structure</a>
				  </li>
					<!--<li class="menu_links" id="option3">Option3</li>-->
					<li class="menu_links" id="logout"><a href="logout.php">Logout</a></li>
				</ul>
                
			</div>
		</div>
	</div> 
    <!--<div id="outerBody_container">-->
    <div id="outerBody_containerTop"></div>
	<div id="outerBody_containerMiddle">
    	<div id="body_container">
			<div id="server_basicInfo">
				<div id="server_upTime"><h3>Uptime:</h3><span id="serverUpTime_content"><?php server_uptime('y'); ?></span></div>
				<span id="server_averageLoad"><h3>Average Load:</h3><?php server_load(); echo $load; ?></span><br />
	        	<span id="server_restartTime"><h3>Restart Time:</h3><?php restart_time(); echo $server_restart; ?></span>
			</div>
			<div id="all_graphs">
	        	<div id="cpu_container">
	        		<div id="cpu_load"><h3>Cpu Load:</h3></div>
                    <div class="refresh" id="refresh_cpuGraph"><img src="./images/ajax-loader.gif " /> </div>
					<div id="chart_div"> </div>
					<div id="cpuLoad_content"><?php echo $table_value['cpu_load']; ?>%</div>
	            </div>
                <div id="memory_container">
       				<div id="memory_used"><h3>Memory Used:</h3></div>
					<div id="used_freeMemory">
	                  	<span id="memoryUsed_content"><?php echo $table_value['memory_used']; ?> </span> MB/ 
	           		 	<span id="memoryTotal_content"><?php echo $table_value['memory_total']; ?> </span> MB
					</div>
                    <div class="refresh" id="refresh_memoryGraph"><img src="./images/ajax-loader.gif " /> </div>
					<div id="memory_graph" > </div>
				</div>
	   	        <div id="swap_container">
					<div id="swap"><h3>Swap Used:</h3></div>
					<div id="used_freeSwap">
         		   		<span id="swapUsed_content"> <?php echo $table_value['swap_used']; ?> </span>MB /
        	   			<span id="swapTotal_content"> <?php echo $table_value['swap_total']; ?> </span>MB
					</div>
                    <div class="refresh" id="refresh_swapGraph"><img src="./images/ajax-loader.gif " /> </div>
	           		<div id="swap_graph" > </div>
					<div id="si_soDetails" onclick="showHide_siSoSwap();">Si So Details</div>
					<div id="si_soSwap"><h3>Swapped In From Disk:</h3>$table_value['memory_free']
						<div id="swapSi_content"><?php echo $table_value['swap_in']; ?></div> MB<br /><h3>Swapped Out From Disk:</h3>
						<div id="swapSo_content"><?php echo $table_value['swap_out']; ?> </div> MB
                        <img class="close" src="./images/close.png" height="20" width="20" onclick="showHide_siSoSwap();"/>
					</div> 
			    </div>
            	<div id="diskUsage_container">
            		<div id="disk_usage"><h3> Disk Usage </h3></div>
                    <div class="refresh" id="refresh_diskUsageGraph"><img src="./images/ajax-loader.gif " /> </div>
	                <div id="disk_usageGraph" > </div>
                    <div id="disk_content">
						<span id="diskUsed_content"> <?php echo $table_value['disk_used']; ?> </span> GB /
	                	<span id="diskTotal_content"> <?php echo $table_value['disk_size']; ?></span> GB
                    </div>
	            </div>
			</div>
	        <div id="upper_colourBox"> </div>
	        <div id="cpu_configuration">
				<br /><h3>Cpu:</h3> <?php cpu_info(); echo $cpu_info; ?>
				<br /><h3>Number Of Cpu(s):</h3> <?php processor_info(); echo $counter; ?>
				<br /><h3>Speed:</h3> <?php cpu_speed(); echo $cpu_speed; ?>MHz
			</div>
	      
    	    <div id="service_status">
				<h3>Service Status</h3>
        		<table class="servicestatus">
					<tr>
						<?php service_status(); 
						echo $data; ?>
					</tr>
				</table>
			</div>
			
            <div id="server_version" class="server_versions">
				<ul class="server_versions">
					<li class="server_versions" id="apache_version"><h3>Server/Apache Version</h3> <?php server_version(); echo $server_version[1]; ?>
                    </li>
					<li class="server_versions" id="mysql_version"><h3>Mysql Version:</h3> <?php mysql_version(); echo $mysql_version[1]; ?> </li>
					<li class="server_versions" id="php_version"><h3>Php Version:</h3> <?php php_version(); echo $php_version[1]; ?> </li>
				</ul>
			</div>
	      	<div id="server_osDetails" class="os_details">
				<ul class="os_details">
					<li class="os_details" id="os"><h3>Operating System:</h3> <?php operating_system(); echo $operating_system; ?></li>
					<li class="os_details" id="os_version"> <h3>Linux Version:</h3> <?php os_version(); echo $os_version; ?> </li>
					<li class="os_details" id="server_architecture">
                    	<h3>Server Architectur/Platform:</h3> <?php server_architecture(); echo $server_architecture;?>
					</li>
				</ul>
			</div>
    	    <div id="lowerLeft_colourBox"> </div>
            <div id="lowerRight_colourBox"> </div>
            <div id="host_name"><?php host_nodeName();?><h3>Host Name:</h3> <?php echo $host;?></div>
            <div id="node_name"><h3> Node Name:</h3> <?php echo $node; ?> </div>
			
		</div>
			<div id="links_container">
	        	<ul class="hot_links" onclick="$.is_open=0;">
    	        	<li title="View Higest Memory Consuming Processess" class="hot_links" id="a_top_processes" onclick="$.is_open=0;show_more('processes_info','Top Processes',6);">
                    	Top Processes
                    </li>
	                <li title="View All MYSQL Processes" class="hot_links" id="a_mysqlProcesses" onclick="$.is_open=0;show_more('mysql_processes','Mysql Processes',6);">
                    	Mysql Processes
                    </li>
	                <li title="View All Apache Processes"class="hot_links" id="a_apacheProcesses" onclick="$.is_open=0;show_more('apache_processes','Apache Processes',6);">
                    	Apache Processes
                    </li>
	                <li title="View Complete Process Tree For Currently Running Processes"class="hot_links" id="a_processTree"  onclick="$.is_open=0;show_more('process_tree','Process Tree');">
                    	Process Tree
                    </li>
	                <li title="View Network Statistics Table" class="hot_links" id="a_networkStatistics" onclick="$.is_open=0;show_more('network_stats','Network Stats',15);">
                    	Network Statistics
                    </li>
	                <li title="View Network Routing Table" class="hot_links" id="a_routingTable" onclick="$.is_open=0;$.is_open=0;show_more('routing_table','Routing Table');">
                    	Routing Table
                    </li>
	                <li title="View SSH Logins and SSH Login History" class="hot_links" id="a_ssh" onclick="$.is_open=0;show_more('ssh_logins','SSH Logins',7);">
                    	SSH Logins
                    </li><li title="Test Server Status By Pinging To Server" class="hot_links" id="a_ping" onclick="$.is_open=0;show_more('ping_server','Ping Server');">
                    	Ping Server
                    </li>
	                <li title="Trace Route to Server" class="hot_links" id="a_trace" onclick="$.is_open=0;show_more('trace_route','Trace Route');">
                    	Trace
                   	</li>
					<li title="View Memory usage Timeline chart" class="hot_links">
						<a href="#memoryusedchart_div" rel="facebox">
							Memory usage
						</a>
					</li>
					<li title="View Swap usage Timeline chart" class="hot_links">
						<a href="#swapusedchart_div" rel="facebox">
							Swap usage
						</a>
					</li>
					<li title="View Cpu load Timeline chart" class="hot_links">
						 <a href="#cpuloadchart_div" rel="facebox">
							Cpu load
						</a>
					</li>
					<li title="View Disk usage Timeline chart" class="hot_links">
						<a href="#diskusedchart_div" rel="facebox">
							Disk usage
						</a>
					</li>
	           	</ul>
	        </div>
		<div id="dynamic_div">
			<div id="dynamic_heading">Top 5 Processes </div>
			<div id="dynamic_content" class="word-wrap"> Loading..... </div>
			<img class="close_process" src="./images/close.png" height="20" width="20" onclick="$.is_open=1;close_this();" />
		</div>
	</div>
	<div id="outerBody_containerBottom">
	</div>
</div>

<div id='memoryusedchart_div' style='width: 600px; height: 240px;'></div>
<div id='swapusedchart_div' style='width: 600px; height: 240px;'></div>
<div id='cpuloadchart_div' style='width: 600px; height: 240px;'> </div>
<div id='diskusedchart_div' style='width: 600px; height: 240px;'></div>

 <script type="text/javascript"> 
	google.load('visualization', '1', {'packages':['annotatedtimeline','corechart']});
	//google.load('visualization', '1',{packages: ['corechart']});
</script>
<script type="text/javascript">
function draw_diskUsageChart(ajax_call,used,free)
{
		$(".refresh").css("display","block");
		$("#disk_usageGraph").css("display","none");
		var data = new google.visualization.DataTable();
        data.addColumn('string', 'Disk Usage');
        data.addColumn('number', 'Size');
		if(ajax_call!=1)
		{
        	data.addRows([
          	['Disk Used (in GB)',{v:<?php echo $table_value['disk_used'] ?>,f:'<?php echo $table_value['disk_used'] ?> GB'}],
          	['Disk Free (in GB)',{v:<?php echo $table_value['disk_available'] ?>,f:'<?php echo $table_value['disk_available'] ?> GB'}]
        	]);
		}
      else
	  {
		  	
  			$(".refresh").css("display","block");
			$("#disk_usageGraph").css("display","none");

		  data.addRows([
          	['Disk Used (in GB)',{v:parseFloat(used),f:used+' GB'}],
          	['Disk Free (in GB)',{v:parseFloat(free),f:free+' GB'}]
        	]);
	  }
        // Create and draw the visualization.
        new google.visualization.PieChart(
          document.getElementById('disk_usageGraph')).
           draw(data, {is3D:true, legend:'none',backgroundColor:'#f6f6f2',colors:['#2e602f','#f4af0a'], height:200,
 width:200});
 
  			$(".refresh").css("display","none");
			$("#disk_usageGraph").css("display","block");
}
function draw_memoryChart(ajax_call,used_percent,free_percent) {
		$(".refresh").css("display","block");
		$("#memory_graph").css("display","none");
		 var data = new google.visualization.DataTable();
        data.addColumn('string', 'Memory Usage');
        data.addColumn('number', 'Memory In Use(%)');
	    data.addColumn('number', 'Memory Free(%)');
        data.addRows(1);
        data.setCell(0, 0,'Memory Usage');
        if(ajax_call!=1)
		{
			data.setCell(0, 1, <?php echo $table_value['memory_usedPercent']; ?>);
        	data.setCell(0, 2, <?php echo $table_value['memory_freePercent']; ?>);
		}
                 
 		else
  		{
			$(".refresh").css("display","block");
			$("#memory_graph").css("display","none");
  			data.setCell(0,1,parseFloat(used_percent));
			data.setCell(0,2,parseFloat(free_percent));  
  		}
       
var v=new google.visualization.BarChart(
                document.getElementById('memory_graph')
              );
		     v.draw(data, {isStacked:true, legend:'none',tooltipFontSize:0.1,backgroundColor:'#f6f6f2',colors:['#2e602f','#f4af0a'], height:40,width:260});
			 $(".refresh").css("display","none");
		$("#memory_graph").css("display","block");}
			
function draw_swapChart(ajax_call,used_percent,free_percent) {
			$(".refresh").css("display","block");
			$("#swap_graph").css("display","none");
    		var data = new google.visualization.DataTable();
        	data.addColumn('string', 'Swap Usage');
        	data.addColumn('number', 'Swap in Use (in MB)');
        	data.addColumn('number', 'Swap Free (in MB)');
        	data.addRows(1);
        	data.setCell(0, 0, 'Swap Usage');
        if(ajax_call!=1)
		{	
			data.setCell(0, 1, <?php echo $table_value['swap_usedPercent']; ?> );
        	data.setCell(0, 2, <?php echo $table_value['swap_freePercent']; ?>);
		}
		else
		{
			$(".refresh").css("display","block");
			$("#swap_graph").css("display","none");
			data.setCell(0, 1, parseFloat(used_percent));
        	data.setCell(0, 2, parseFloat(free_percent));
		}
        var chart = new google.visualization.BarChart(document.getElementById('swap_graph'));
        chart.draw(data, {isStacked:true, legend:'none',tooltipFontSize:0.1,backgroundColor:'#f6f6f2', colors:['#2e602f','#f4af0a'], height:40,
 width:260});
 			$(".refresh").css("display","none");
			$("#swap_graph").css("display","block");
     }	
function draw_cpuLoadChart(ajax_call,used_percent,free_percent) {
		$(".refresh").css("display","block");
		$("#chart_div").css("display","none");
		data = new google.visualization.DataTable();
		data.addColumn('number', 'CPU LOAD');
        data.addColumn('number', ' ');
		data.addRows(1);	
        if(ajax_call!=1)
		{	
        	data.setValue(0, 0,<?php echo $table_value['cpu_loadPercent']; ?>);
        	data.setValue(0, 1,<?php echo $table_value['cpu_noLoadPercent']; ?>);
		}
		else
        {
			
      		$(".refresh").css("display","block");
			$("#chart_div").css("display","none");
			data.setValue(0,0,parseFloat(used_percent));
        	data.setValue(0,1,parseFloat(free_percent));
		}
		chart = new google.visualization.BarChart(document.getElementById('chart_div'));
        chart.draw(data, {isStacked:true,legend:'none',tooltipFontSize:0.1,backgroundColor:'#f6f6f2', colors:['#2e602f','#f4af0a'], height:40, width:260});
      		$(".refresh").css("display","none");
			$("#chart_div").css("display","block");
			}


draw_memoryChart();
draw_swapChart();
draw_diskUsageChart();
draw_cpuLoadChart();

var count=<?php echo $count; ?>;
	
     

	function drawmemory_Chart() {
        var data = new google.visualization.DataTable();
        data.addColumn('datetime', 'Datetime');
        data.addColumn('number', 'MEMORY USED');
        
        data.addRows(count);
		for(var j=0;j<count;j++)
		{
		data.setValue(j, 0, new Date(year[j], month[j]-1, date[j], hour[j], minute[j], second[j]));	
		data.setValue(j, 1, memory[j]);
		
		}

        var chart = new google.visualization.AnnotatedTimeLine(document.getElementById('memoryusedchart_div'));
        chart.draw(data, {displayAnnotations: true,allValuesSuffix:'%'});
		$("#memoryusedchart_div").css("display","none");
      }
		function drawswap_Chart() {
        var data = new google.visualization.DataTable();
        data.addColumn('datetime', 'Datetime');
        data.addColumn('number', 'SWAP USED');
        
        data.addRows(count);
		for(var j=0;j<count;j++)
		{
		data.setValue(j, 0, new Date(year[j], month[j]-1, date[j], hour[j], minute[j], second[j]));	
		data.setValue(j, 1, swap[j]);
		
		}

        var chart = new google.visualization.AnnotatedTimeLine(document.getElementById('swapusedchart_div'));
        chart.draw(data, {displayAnnotations: true,allValuesSuffix:'%'});
		$("#swapusedchart_div").css("display","none");
      }
	  function drawcpuload_Chart() {
        var data = new google.visualization.DataTable();
        data.addColumn('datetime', 'Datetime');
        data.addColumn('number', 'CPU LOAD');
        
        data.addRows(count);
		for(var j=0;j<count;j++)
		{
		data.setValue(j, 0, new Date(year[j], month[j]-1, date[j], hour[j], minute[j], second[j]));	
		data.setValue(j, 1, cpu_load[j]);
		
		}

        var chart = new google.visualization.AnnotatedTimeLine(document.getElementById('cpuloadchart_div'));
        chart.draw(data, {displayAnnotations: true,allValuesSuffix:'%'});
		$("#cpuloadchart_div").css("display","none");
      }
	  function drawdiskused_Chart() {
        var data = new google.visualization.DataTable();
        data.addColumn('datetime', 'Datetime');
        data.addColumn('number', 'DISK USED');
        
        data.addRows(count);
		for(var j=0;j<count;j++)
		{
		data.setValue(j, 0, new Date(year[j], month[j]-1, date[j], hour[j], minute[j], second[j]));	
		data.setValue(j, 1, disk[j]);
		
		}

        var chart = new google.visualization.AnnotatedTimeLine(document.getElementById('diskusedchart_div'));
        chart.draw(data, {displayAnnotations: true,allValuesSuffix:'%'});
		$("#diskusedchart_div").css("display","none");
      }
	google.setOnLoadCallback(drawmemory_Chart);
	 google.setOnLoadCallback(drawswap_Chart);
	google.setOnLoadCallback(drawcpuload_Chart);
	google.setOnLoadCallback(drawdiskused_Chart);

</script>
</body>
</html>
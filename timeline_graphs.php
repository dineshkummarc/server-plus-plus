<?php
session_start();
if(!($_SESSION['login'])){
header('Location: ./login.php');
}
include('config.php');
$i=0;
$count=0;

?>
<html>
  <head>
	<script src="./jquery.js" type="text/javascript"></script>
	<link href="./facebox/facebox.css" media="screen" rel="stylesheet" type="text/css"/>
	<script src="./facebox/facebox.js" type="text/javascript"></script>
	<link href="./facebox/faceplant.css" media="screen" rel="stylesheet" type="text/css"/>
	<style type="text/css">
	body
	{
	font-weight:bold;
	}
	#logout{
	position:fixed;
    top:15px;
    right:35px;
	}
	
	
	</style>
	<script type="text/javascript">
	 jQuery(document).ready(function($) {
  $('a[rel*=facebox]').facebox()
})
    </script>
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
		<script type="text/javascript" src="http://www.google.com/jsapi"></script>
    </head>
<body>
	<center>TIMELINE GRAPHS</center>
	<div id="logout"><a href="logout.php">Logout</a></div>
    <a href="#memoryusedchart_div" rel="facebox">
                MEMORY USAGE GRAPH
     </a>
	 <br>
	     <a href="#swapusedchart_div" rel="facebox">
                SWAP USAGE GRAPH
     </a>
	 <br>
	     <a href="#cpuloadchart_div" rel="facebox">
                CPU LOAD CHART
     </a>
	 <br>
	     <a href="#diskusedchart_div" rel="facebox">
                DISK USAGE GRAPH
     </a>
	<div id='memoryusedchart_div' style='width: 600px; height: 240px;'></div>   
	<div id='swapusedchart_div' style='width: 600px; height: 240px;'></div>
	<div id='cpuloadchart_div' style='width: 600px; height: 240px;'> </div>
	<div id='diskusedchart_div' style='width: 600px; height: 240px;'></div>
	  </body>


  
<?php
function total_time($t)
{
global $hour,$minute,$second,$year,$month,$date;
$hour=date('H', $t);
$minute=date('i', $t);
$second=date('s', $t);
$year=date('Y', $t);
$month=date('m', $t);
$date=date('d', $t);

}

	$con=mysql_connect($hostname,$dbusername,$dbpswd);
	if(!$con)
	{
	die('could not connect:'.mysql_error());
	}
	mysql_select_db($dbname,$con);
$current_time=time();
$current_before=$current_time-86400;

$sql=mysql_query("SELECT cpu_loadPercent,memory_usedPercent,swap_usedPercent,disk_usedPercent,time_stamp FROM $table_name");
while($row=mysql_fetch_array($sql))
{	
	$count++;
	//$memory[$i]=$row['memory_usedPercent'];
	total_time($row['time_stamp']);
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




<script type="text/javascript">
	
	var count=<?php echo $count; ?>;
	google.load('visualization', '1', {'packages':['annotatedtimeline']});
     

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
	
          </script>
  <script>
drawmemory_Chart();
drawswap_Chart();
drawcpuload_Chart();
drawdiskused_Chart();
</script>
 </html>
// JavaScript Document
	$.cpu_loadPercent=0;
	$.cpu_noLoadPercent=0;
	$.disk_used=0;
	$.disk_available=0;
	$.memory_usedPercent=0;
	$.memory_freePercent=0;
	$.swap_usedPercent=0;
	$.swap_freePercent=0;
	$.function_name="processes_info";
	$.heading="Top Processes";
	$.runOnce=0;
	$.is_open=0;
	$.counter=0;
	$.top=50;$.keep_noting=1;

	$(document).ready(function(){
		setInterval("call_valuesRefresh()",1000);
		setInterval("call_charts()",25000);
	});
function call_valuesRefresh()
{
	value_refresh("server_restart","restartTime_content");
	//value_refresh("avg_load","averageLoad_content");
	value_refresh("cpu_loadPercent","cpuLoad_content");
	value_refresh("cpu_noLoadPercent");
	value_refresh("memory_used","memoryUsed_content");
	//value_refresh("memory_free","memoryFree_content");
	value_refresh("memory_usedPercent");
	value_refresh("memory_freePercent");
	value_refresh("swap_used","swapUsed_content");				
	value_refresh("swap_free","swapFree_content");
	value_refresh("swap_usedPercent");
	value_refresh("swap_freePercent");
	value_refresh("swap_in","swapSi_content");
	value_refresh("swap_out","swapSo_content");
	value_refresh("disk_used","diskUsed_content");
	value_refresh("disk_available");
}

function value_refresh(this_feild,this_div)
{
	
	switch(this_feild)
	{
		case 'avg_load' 		 :
		case 'memory_used' 		 :
		case 'swap_used' 		 :
		case 'swap_free' 		 :
		case 'swap_in' 			 :
		case 'swap_out' 		 : $("#"+this_div).load("extract.php?from_db=1&feild_name="+this_feild);
								   break;
		case 'cpu_loadPercent'	 : $.get("extract.php?from_db=1&feild_name="+this_feild,function(data){ $.cpu_loadPercent=data; } );
								   $("#"+this_div).html($.cpu_loadPercent+"%");
								   break;	
		case 'cpu_noLoadPercent' : $.get("extract.php?from_db=1&feild_name="+this_feild,function(data){$.cpu_noLoadPercent=data;
 });								 									break;
		case 'memory_usedPercent': $.get("extract.php?from_db=1&feild_name="+this_feild,function(data){ $.memory_usedPercent=data ;});
								   break;
		case 'memory_freePercent': $.get("extract.php?from_db=1&feild_name="+this_feild,function(data){$.memory_freePercent=data; });
									break;
		case 'swap_usedPercent'  : $.get("extract.php?from_db=1&feild_name="+this_feild,function(data){$.swap_usedPercent=data; });
									break;
		case 'swap_freePercent'  : $.get("extract.php?from_db=1&feild_name="+this_feild,function(data){$.swap_freePercent=data; });
									break;
								
		case 'disk_used'		 : $.get("extract.php?from_db=1&feild_name="+this_feild,function(data){ $.disk_used=data; } );
								   $("#"+this_div).html($.disk_used);
								   break;
		case 'disk_available'	 :  $.get("extract.php?from_db=1&feild_name="+this_feild,function(data){ $.disk_available=data; } );
		default 				 : break;
	}
}

function call_charts()
{
	/*alert(cpu_loadPercent);
	alert(cpu_noLoadPercent);
	alert(memory_usedPercent);
	alert(memory_freePercent);
	/*alert(swap_usedPercent);
	alert(swap_freePercent);*/
	draw_cpuLoadChart(1,$.cpu_loadPercent,$.cpu_noLoadPercent);
	draw_memoryChart(1,$.memory_usedPercent,$.memory_freePercent);
	draw_swapChart(1,$.swap_usedPercent,$.swap_freePercent);
	draw_diskUsageChart(1,$.disk_used,$.disk_available);

}
$(document).ready(function(){
	if($.keep_noting!=0){
   $(document).mousemove(function(e){
      $.top=e.pageY;
   }); }
});
function close_this()
{
	if($.is_open==1)
	{
		$("#body_container").fadeIn(1400);
		$("#dynamic_div").animate({ 
			/*top:"180px",
			left:"150px",*/
			width:"0px",
			opacity: 0.1,
			display:"toggle"
			}, {queue:false, duration:1500} );
			$("#links_container").animate({
				opacity:0.2,
				top: "100px",
				opacity:1.0},1500);
		$("#dynamic_div").fadeOut(1600);
		$.is_open=0;
	}
}
function show_more(thisFunction_name,heading,lines)
{

		if($.is_open==0)
		{
			//close_this();
			$("#body_container").fadeOut(1600);
			$("#dynamic_div").animate({ 
			/*top:"0px",
			left:"0px",*/
			width:"635px",
			opacity: 1.0,
			}, 1500 );
			$("#links_container").animate({
				opacity:0.3,
				position:"fixed",
				top:"50px",
				opacity:1.0}, 1500);
			//$("#"+thisDiv).html("Loading....");		
			$("#dynamic_content").html("Loading.....");
			$("#dynamic_heading").html(heading);
			//$("#"+thisDiv).load("logger.php?function_name="+thisFunction_name+"&count="+lines,1500);
			$.get("extract.php?call_function=1&function_name="+thisFunction_name+"&count="+lines,function(data){
				$("#dynamic_content").html('<div id="dynamic_contentTop"> </div>'+data+'<div id="dynamic_seeMore" onclick="$.is_open=1;see_more();">See More </div><div id="dynamic_contentBottom style="position:absolute;bottom:0px;width:792px;height:23px;background:url(\'./images/dynamic_contentBottom.png\') repeat-y;"> </div>');});
			$.function_name=thisFunction_name;
			$.heading=heading;
			$.counter=lines;
			$.is_open=1;
			$.top=50;
		}
		else
		{
				$.top-=150;
				$("#links_container").animate({
				opacity:0.2,
				top: $.top+"px",
				opacity:1.0},1500);
			$.counter+=$.counter;
			$.get("extract.php?call_function=1&function_name="+thisFunction_name+"&count="+$.counter,function(data){
				$("#dynamic_content").html('<div id="dynamic_contentTop" </div>'+data+'<div id="dynamic_seeMore" onclick="$.is_open=1;see_more();">See More </div><div id="dynamic_contentBottom style="position:absolute;bottom:0px;width:792px;height:23px;background:url(\'./images/dynamic_contentBottom.png\') repeat-y;"> </div>');});$.keep_noting=1;
			//$("#"+thisDiv).load("logger.php?function_name="+thisFunction_name+"&count="+$.counter,1500);
		}
}
function see_more()
{
	$.keep_noting=0;
	$.is_open=1;
	show_more($.function_name);
	
}

//*******Jquery to Configure Server Info*********
setInterval('uptime_refresh()',1000);


function uptime_refresh()
{
	
	$("#serverUpTime_content").load("extract.php?call_function=1&function_name=server_uptime");
}
function showHide_serverVersions(){
	if($.sv!=1)
	{
		$("div.server_versions").animate({ 
			width: "400px",
			height:"300px",
			top:"100px",
			opacity: 0.8,
			display: "toggle",
			marginLeft: "0.6in",
		}, 1500 );
		$.sv=1;
	}
	else
	{
		$("div.server_versions").animate({ 
		width: "0px",
		height:"0px",
		opacity: 0.0,
		top:"200px",
		display: "toggle",
		marginLeft: "0.0in",
		}, 1500 );
		$.sv=0;
	}	
}
function showHide_siSoSwap(){
	if($.sw!=1)
	{
		$("div#si_soSwap").animate({ 
			width: "500px",
			height:"40px",
			top:"220px",
			right:"35%",
			opacity: 1,
			display: "toggle",
			marginLeft: "0.6in",
		}, 1500 );
		$.sw=1;
	}
	else
	{
		$("div#si_soSwap").animate({ 
		width: "0px",
		height:"0px",
		opacity: 0.0,
		top:"220px",
		right:"35%",
		display: "toggle",
		marginLeft: "0.0in",
		}, 1500 );
		$.sw=0;
	}	
}

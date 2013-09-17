<?php    /*
    view 
    author: Heqiao Liu
    site: viral_dark_matter data/view
    page: view.php
    last updated: 07/06/2013 by HQ 
        */
require	'common.php';
require './classes/Model.php';
require './classes/View.php';
require './classes/Well.php';
require './classes/DBObject.php';
$dbobject = new DBObject("edwards.sdsu.edu", "heqiaol", "LHQk666!", "viral_dark_matter");
$databaseConnection = $dbobject->getDB();

/***********php object declaration*********************/
$view=new View();
$view->setDatabaseConnection($databaseConnection);
$well=new Well();
$well->setDatabaseConnection($databaseConnection);
//$bact_ext_ids=$bacteria->getBactExtId();
/*******temp for test*********/
$bact_ext_ids=$view->getBactExtId();
$wells=$well->getWells();
/********test end***********/

/**********php object declaration END******************/


?>
<?php  header('Content-type: text/html; charset=utf-8');?>
<!DOCTYPE html>
<html lang="en">
<head> 
<link rel="stylesheet" type="text/css" href="http://vdm.sdsu.edu/data/css/view.css" />
<?php require'head.html'?>
<script src="http://code.highcharts.com/highcharts.js"></script>
<script src="http://code.highcharts.com/highcharts-more.js"></script>
<script src="http://code.highcharts.com/modules/exporting.js"></script>
<script src="tool/JStool.js" type="text/javascript" ></script>
<script src="tool/Stat.js" type="text/javascript" ></script>
<script src="viewsite/PMchart_new.js" type="text/javascript" charset="utf-8"></script>
<script src="viewsite/viewtool_new.js" type="text/javascript" ></script>
<script type="text/javascript">
var dfd=$.Deferred();
var searchString="";
function hideSteps(step){
	$(step).hide();
}

function showStep(step){
	$(step).show();
}
$(function(){
	$("#mainfunctions").accordion();
	$(document).keyup(function(event){
			if(event.which==88)
				
			if(event.which==90)
				alert('z');
	});
});
/*
$(function(){
	$("#bact_steps").slider({
		range:"max",
		min:1,
		max:4,
		value:1,
		slide:function(event,ui){
			dfd.done(hideSteps(".bact_step")).done(showStep("#bact_step"+ui.value))}	
	});
});
$(function(){
	dfd.done(hideSteps(".bact_step")).done(showStep("#bact_step1"));
});
*/
</script>

</head>
	<body>
		<?php require "div_nav.html" ?>
		<div id="mainfunctions" class='posters'>
			<h3 id="bact">Search through bacteria external id</h3>
			<div class='function panel'>
				<div id="bact_id">
					<div id="bact_steps"></div>
					<div id="bact_step1" class="bact_step">
						<div class="ui-widget">
							<label for="tags">bacteria external id: </label>
							<input id="searchin" />
						</div>
						<div id="bact_list">
							<?php	foreach($bact_ext_ids as $id)
								echo "<p class='beid selectable' bact_ext_id='".$id['bact_external_id']."' id='b".$id['bacteria_id']."' bid='".$id['bacteria_id']."'>".$id['bact_external_id']."</p>";?>
						</div>
						<div id="bact_hl">Select All Highlighted Bacteria(s).</div>
					</div>
					<div id="bact_step2" class="bact_step">
						<p>Clicking a plate-tab gives you detail about that plate on right.</p></br>
						<div id='exp_option'></div>
						<div id="exp_info" class="panel_comp"></div>
					</div>
					<div id="bact_step3" class="bact_step">
						<div id="clones" class="panel_comp">
							<table id="Tclones">
								<?php
								$i=0;$j=0;
								foreach($wells as $wellele){
									$j+=1;
									if($i==12){
										echo "</tr>";
										$i=0;
									}
									if($i++==0)
										echo "<tr>";
									echo "<td><div id='c$j' class='well_ele selectable' cid='$j'>".$wellele."</div></td>";
								}	
								?>
							</table>
							<div id="select_options"></div>
						</div>
					</div>
				</div>
			</div>
			<h3 id="files">Looking up files</h3>
			<div id="files_d" class="function"><p>aaa</p></div>
			<h3 id="filess">Looking up files</h3>
			<div id="filess_d"><p>aaa</p></div>
			<h3 id="filesss">Looking up files</h3>
			<div id="filesss_d"><p>aaa</p></div>
		</div>
		<div id="chart_option" class="closeded">
			<h3 >Chart options</h3>
			<button id="toggle_graph" onclick="return false;">Turn On/Off Graph</button></br>
			<button id="get_graph" onclick="return false;">Generate Graph</button></br>
			<input type='checkbox' id='stat_graph'>Show Avg,error bars, and median.</input></br>
			<input type='checkbox' id='stat_only'>Show Avg,error bars, and median only.</input></br>
			<input type="radio" id='log2y'  name='yaxis'>LOG BASE 2 YAxis<br>
			<input type="radio" name='yaxis' checked='checked'>Normal YAxis<br>
			<input type="radio" id='hourx' name='xaxis'>XAxis in hour<br>
			<input type="radio" name='xaxis' checked='checked'>XAxis in minute<br>
			<button id="csv" onclick="return false;" >download data in the chart as csvfile</button>
		</div>
		<div id="chart_box" class='posters'>
			<figure ><div id="container" style="width: 100%; height: 40px"></div></figure>
		</div>
	</body>
</html>

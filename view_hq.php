<?php    /*
    view 
    author: Nick Turner & Heqiao Liu
    site: viral_dark_matter data/view
    page: view_hq.php
    last updated: 27/03/2012 by HQ 
        */
require	'common.php';
require './classes/Model.php';
require './classes/View.php';
require './classes/DBObject.php';
$dbobject = new DBObject("edwards.sdsu.edu", "heqiaol", "LHQk666!", "viral_dark_matter");
$databaseConnection = $dbobject->getDB();

/***********php object declaration*********************/
$view=new View();
$view->setDatabaseConnection($databaseConnection);
//$bact_ext_ids=$bacteria->getBactExtId();
/*******temp for test*********/
$bact_ext_ids=$view->getBactExtId();
/********test end***********/

/**********php object declaration END******************/


?>
<?php  header('Content-type: text/html; charset=utf-8');?>
<!DOCTYPE html>
<html lang="en">
<head> 
<?php require'head.html'?>
<script src="http://code.highcharts.com/highcharts.js"></script>
<script src="http://code.highcharts.com/modules/exporting.js"></script>
<script src="tool/JStool.js" type="text/javascript" ></script>
<script src="viewsite/PMchart.js" type="text/javascript" charset="utf-8"></script>
<script src="viewsite/viewtool_hq.js" type="text/javascript" ></script>
<script src="tool/Stat.js" type="text/javascript" ></script>
<script type="text/javascript">
var jui_accordion_h3="ui-accordion-header ui-helper-reset ui-state-default ui-corner-alli ";
var jui_accordion_div="ui-accordion-content ui-helper-reset ui-widget-content ui-corner-bottom ui-accordion-content-active ";

$(function() {
	var tabTitle = $( "#tab_title" ),
		tabContent = $( "#tab_content" ),
		tabTemplate = "<li><a href='#{href}' >#{label}</a> <span class='ui-icon ui-icon-close'>Remove Tab</span></li>",
		tabCounter = 1;
	var dialog=$("#selected_container").dialog();
	var tabs=$("#selected_info").tabs({
		activate: function( event, ui ) {CURRENT_SEL_DIV=(ui.newTab.children("a").first().attr("href"))}
	});
	// modal dialog init: custom buttons and a "close" callback reseting the form inside

	// actual addTab function: adds new tab using the input from the form above
	function addTab() {
		var label = tabTitle.val() || "Group " + tabCounter,
			id = "group-" + tabCounter,
			li = $( tabTemplate.replace( /#\{href\}/g, "#" + id ).replace( /#\{label\}/g, label ) ),
			tabContentHtml = tabContent.val() || "Tab " + tabCounter + " content.";

		tabs.find( ".ui-tabs-nav" ).append( li );
		tabs.append( "<div id='" + id + "'><p></p></div>" );
		tabs.tabs( "refresh" );
		tabCounter++;
	}

	// addTab button: just opens the dialog
	$( "#add_tab" )
		.button()
		.click(function() {
			addTab();
		});

	// close icon: removing the tab on click
	$( "#tabs span.ui-icon-close" ).live( "click", function() {
		var panelId = $( this ).closest( "li" ).remove().attr( "aria-controls" );
		$( "#" + panelId ).remove();
		tabs.tabs( "refresh" );
	});
});

function quickAccordion(div_id,id,head){
		quickAppend("#"+div_id,"h3",jui_accordion_h3,div_id+"_head_"+id,head);	
		quickAppend("#"+div_id,"div",jui_accordion_div,div_id+"_content_"+id,"");	
} 

</script>

</head>
<div id="selected_container">
<button id="add_tab">add new group</button>
<div id="selected_info">
	<ul id="sel_tabs">
		<li><a href="#individual">Individual</a><span class="ui-icon ui-icon-close"></span></li>
	</ul>
	<div id="individual"></div>
</div>
<div>
	<button id="get_graph" onclick="return false;">Generate Graph</button>
</div>
</div>
<body id="view">
<?php require "div_nav.html" ?>

<div id="over">
<div id="all_panel">
<div id="cloneSelect" class="panel_comp">
<?php

	foreach($bact_ext_ids as $id)
		echo "<div class='bact-widge ui-widget-content'><a name=".$id['bact_external_id']."><p class='beid' id='".$id['bacteria_id']."'>".$id['bact_external_id']."</p></a></div>";
?>
</div>
<div id="plate_rep" class="panel_comp">
</div>
<div id="clones" class="panel_comp">

        <table id="Tclones" width=500px height=200px>
	    <?php
	    	for($i=0;$i<8;$i++){
		    $rowindex=chr(65+$i);
		    echo "<tr id=\"R".$rowindex."\">";
		    for($j=0;$j<13;$j++){
		      if($j!=0){
		        $temp=$rowindex;
				$temp.=$j;
                echo "<td id=\"".$temp."\" class=\"".$rowindex." selectable\" wellId=\"".($j+12*$i)."\">".$temp."</td>";
              }
      		}
		    echo "</tr>";
	    	}
	    ?>
        </table>
		<p id="growth_level_info">Growth Level: </p>
</div>
</div>
</div>
<div id="chart_box">
    <figure >
        <div id="container" style="width: 100%; height: 40px">Click Generate above to see a graph.</div>
    </figure>
        <button id="csv" onclick="return false;">download as csvfile</button>
</div><!-- /#mainarea -->


</body>
</html>

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
//require_authentication(); 
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
<?php require "head.html"; ?>
<script src="http://code.highcharts.com/highcharts.js" type="text/javascript"></script>
<script src="http://code.highcharts.com/modules/exporting.js" type="text/javascript"></script>
<script src="tool/JStool.js" type="text/javascript" ></script>
<script src="viewsite/PMchart.js" type="text/javascript" ></script>
<script src="viewsite/viewtool_hq.js" type="text/javascript" ></script>
<script src="tool/Stat.js" type="text/javascript" ></script>
<script type="text/javascript">
var jui_accordion_h3="ui-accordion-header ui-helper-reset ui-state-default ui-corner-alli ";
var jui_accordion_div="ui-accordion-content ui-helper-reset ui-widget-content ui-corner-bottom ui-accordion-content-active ";

$(function(){
	$("td.selectable").click(function(){
		var bact=$("p.focus").parent().attr("name");
		var exp_id=$("tr.plate_rep_tr.selected").attr("id").substr(6);
		var clone=$(this).attr("wellid");
		var well=$(this).attr("id");
		var well_id=$(this).attr("wellid");
		quickAppend("#individual","div","on_list",exp_id+" "+well_id,bact+" "+exp_id+" "+well);
	});
});


$(function(){
  $("#p_all").live("click",(function(){
   $("tr.plate_rep_tr").show(); 
  }));
});


$(function(){
  $("p.spe").live("click",(function(){
   $("tr.plate_rep_tr").hide();
   $("tr."+(this).id).show(); 
  }));
});

var tabs;
$(function(){
  $("#selected_info").dialog();
  tabs=$("#selected_info").tabs();
});

$(function(){
  $("#get_graph").click(function(){
  })
});

function quickAccordion(div_id,id,head){
		quickAppend("#"+div_id,"h3",jui_accordion_h3,div_id+"_head_"+id,head);	
		quickAppend("#"+div_id,"div",jui_accordion_div,div_id+"_content_"+id,"");	
} 

</script>

</head>
<div id="selected_info">
	<ul id="sel_tabs">
		<li><a href="#individual">Individual</a></li>
	</ul>
	<div id="individual"></div>
	<button id="get_graph" onclick="return false;">Generate Graph</button>
</div>
<body id="view">
<?php require "div_nav.html" ?>

<div id="over">
<div id="all_panel">
<div id="cloneSelect" class="panel_comp">
<?php

	foreach($bact_ext_ids as $id)
		echo "<a name=".$id['bact_external_id']."><p class='beid' id='".$id['bacteria_id']."'>".$id['bact_external_id']."</p></a>";
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
		      if($j==0){
		      }
		      else{
		        $temp=$rowindex;
			$temp.=$j;
                        echo "<td id=\"".$temp."\" class=\"".$rowindex." selectable\" wellId=\"".($i+8*($j-1)+1)."\">".$temp."</td>";
                        }
      		      }
		    echo "</tr>";
	    	}
	    ?>
        </table>

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

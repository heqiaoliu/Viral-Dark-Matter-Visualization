function getFromPM(request,callback){
	$.ajax({
		url:"viewsite/pm_view_factory.php",
		type:"GET",
		data:request,
		dataType:"json",
		success:callback
	});
}


var CUR_DATA;	// very important!!!
/* CUR_DATA is the global variable which store send back data. used by highchart and csv as well.
 *
 */
/*
$(Document).ready(function(){
	$("#closed_list").hide();
});
*/


/* 
 * Trigger event: click.
 * Response area: div#cloneSelect.
 * Response element: p.beid.
 *
 * It remove classes 'focus' and 'selected' of current one,
 * then add them to new select one.
 */
$(function(){
  $("p.beid").click(function(){
        $("p.beid").removeClass("focus");
        $("p.beid").removeClass("selected");
		$(this).addClass("selected");
		$(this).addClass("focus");
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
/* 
 * Trigger event: click.
 * Response area: div#plate_rep.
 * Response element: .plate_rep_tr.
 *
 * It remove class 'selected' of current one,
 * then add it to new select one.
 */
$(function repTog(){
  $(".plate_rep_tr").live("click",function(){
    $(".plate_rep_tr").removeClass("selected");
    classTog("#"+(this).id,"selected");
  }).live("click",getPlateInfo);
});

/* 
 * Trigger event: click
 * Toggle ".selectable"
 */
$(function selectedTog(){
  $(".selectable").live("click",function(){
    classTog("#"+(this).id,"selected");
  });
});


$(function(){
	$("#plate_rep").tabs();
	$(".beid").click(function(){
		$("#plate_rep").empty();
		$("#plate_rep").tabs("destroy");
	    if($(this).hasClass("selected")){
	    	var request="&type=exp_info&bacteria_id="+(this).id;
			console.log(request);
			getFromPM(request,genRepTabs);
	    }
	    else{
		}
	});
});


function getPlateInfo(){
	getFromPM("type=plate_info&exp_id="+(this).id.substring(6),fillSuppInfo);
}

function fillSuppInfo(obj){
	console.log(obj.supp_info);
	var counter=0;
	var map={}
	$(".well_ele").each(function(){
		if(map[obj.supp_info[this.id]['medium_ctrl_name']]==null)
			map[obj.supp_info[this.id]['medium_ctrl_name']]=counter++;
		$(this).attr("title",obj.supp_info[this.id]['supplement']+" "+obj.supp_info[this.id]['supplement_conc']).attr("class","well_ele color"+map[obj.supp_info[this.id]['medium_ctrl_name']]);
	});
//	$(".well_ele").each(function(){$(this).html(obj.supp_info[this.id]['supplement']+" "+obj.supp_info[this.id]['supplement_conc']);});
//
}


function genRepTabs(dataObj){
	console.log(dataObj);
	quickAppend("#plate_rep","ul","","tabs","");
	quickAppend("#plate_rep","div","","list","");
	quickAppend("#list","table","","test","");
	quickAppend("#tabs","li","","li_all","");
	quickAppend("#li_all","a","","a_all","all");
	$("#a_all").attr("href","#list");

	for(x in dataObj){
		quickAppend("#tabs","li","","li_"+x,"");
		quickAppend("#li_"+x,"a","","a_"+x,dataObj[x]['name']);
		$("#a_"+x).attr("href","#list");
		var obj=dataObj[x];
		for(y in obj['data']){
		   var data=obj['data'][y];
		   quickAppend("#test","tr","sel plate_rep_tr li_"+x,"pr_tr_"+data[1],"");
		   quickAppend("#pr_tr_"+data[1],"td","replicate","rep_"+data[1],data[0]);
		   quickAppend("#pr_tr_"+data[1],"td","exp_data","date_"+data[1],data[2]);
		   quickAppend("#pr_tr_"+data[1],"td","file_name","date_"+data[1],data[3]);
		}
	}
	tabReady();
}

function tabReady(){
	$("#plate_rep").tabs({
		activate:function(event,ui){
			if(($("#li_all").hasClass("ui-tabs-active")))
				$("tr.plate_rep_tr").show(); 
			else{
				var cur=$("li.ui-tabs-active").first().attr("id");
				$(".plate_rep_tr").hide();
				$("tr."+cur).show(); 
			}
		}
	});
}

$(function(){
	$("#get_graph").click(function(){
		var request="type=growth_data&data=[";
		var count=0;
		$("div.on_list").each(function(){
			if(count)
				request+=",";
			else
				count=1;
			var sets=(this).id.split(" ");
			 request+="["+sets[0]+","+sets[1]+"]";
		});
		request+="]";
		console.log(request);
	    	$.ajax({
	        	type:"POST",
				url:"viewsite/pm_view_factory.php",
	        	data:request,
	        	dataType:"json",
		       	success:function(dataObj){
				CUR_DATA=dataObj;
				console.log(dataObj);
				genGraph(dataObj);
			}
		});
	
	$(window).scrollTop(400);
	});
});


/* all action about td.selectable //td well_numbers
 * 
 *
 */
var CURRENT_SEL_DIV="#individual";	//constant
$(function(){
	$("td.selectable").live("click",function(){
		var bact=$("p.focus").parent().attr("name");
		var exp_id=$("tr.plate_rep_tr.selected").attr("id").substr(6);
		var clone=$(this).attr("wellid");
		var well=$(this).attr("id");
		var well_id=$(this).attr("wellid");
		quickAppend(CURRENT_SEL_DIV,"div","on_list",exp_id+" "+well_id,bact+" "+exp_id+" "+well);
	});
/*
 * .on("mouseover",function(){
		var request="&type=growth_level";	
		request+="&exp_id="+$("tr.plate_rep_tr.selected").attr("id").substr(6);
		request+="&well_id="+$(this).attr("wellid");		
		getGrowthInfo(request);
	}).on("mouseleave",function(){
		$("#growth_level_info").html("");
	});
*/
});

/* This function post request query to pm_view_factory
 * Query: type=growth_level&exp_id=?&well_id=?
 * 
 */
function getGrowthInfo(request){
	$.ajax({
		url:"viewsite/pm_view_factory.php",
		type:"POST",
		data:request,
		dataType:"json",
		success:function(dataObj){
			var level_info="";
			switch(dataObj.growth_level){
				case 1:
					level_info=dataObj.well_num+" is A";
					break;
				case 2:
					level_info=dataObj.well_num+" is B";
					break;
				case 3:
					level_info=dataObj.well_num+" is C";
					break;
				case 4:
					level_info=dataObj.well_num+" is D";
					break;
				default:
					level_info="this is unknown";
			}
			$("#growth_level_info").html("Growth Level for "+level_info);
		
		}
	});	
}


$(function(){
  $("#csv").click(function(){
	$.ajax({
		url:"viewsite/precsv.php",
		data:"data="+JSON.stringify(CUR_DATA),
		dataType:"json",
		type:"POST",
		success:function(dataObj){
			console.log(dataObj);
          window.location='http://vdm.sdsu.edu/data/viewsite/downCsv.php?filename='+dataObj;
		}
	})
  });
});


$(function(){
  $("p.beid").click(function(){
        $("p.beid").removeClass("focus");
        $("p.beid").removeClass("selected");
		$(this).addClass("selected");
		$(this).addClass("focus");
  });
});

$(function repTog(){
  $(".plate_rep_tr").live("click",function(){
    $(".plate_rep_tr").removeClass("selected");
    classTog("#"+(this).id,"selected");
  });
});

$(function selectedTog(){
  $(".selectable").live("click",function(){
    classTog("#"+(this).id,"selected");
  });
});


$(function(){
	$("#plate_rep").tabs();
	$("p.beid").click(function(){
		$("#plate_rep").empty();
		$("#plate_rep").tabs("destroy");
	    if($(this).hasClass("selected")){
	    	var request="&type=exp_info&bacteria_id="+(this).id;
			console.log(request);
	    	$.ajax({
	        	type:"POST",
//				url:"viewsite/getPlateRep.php",
	        	url:"viewsite/pm_view_factory.php",
	        	data:request,
	        	dataType:"json",
	        	success:function(dataObj){

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
			$("#plate_rep").tabs({
				show:function(event,ui){
					if(($("#li_all").hasClass("ui-tabs-active")))
   						$("tr.plate_rep_tr").show(); 
					else{
						var cur=$("li.ui-tabs-active").first().attr("id");
   						$("tr.plate_rep_tr").hide();
   						$("tr."+cur).show(); 
					}
				}
			});


	              }
	    	});
	    }
	    else{
		}
	});
});


$(function(){
	$("#get_graph").click(function(){
		var request="&data=[";
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
	        	url:"viewsite/getGrowthData.php",
	        	data:request,
	        	dataType:"json",
		       	success:function(dataObj){
				console.log(dataObj);
				genGraph(dataObj);
			}
		});
	
	$(window).scrollTop(400);
	});
});



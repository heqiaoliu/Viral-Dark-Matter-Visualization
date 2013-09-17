/********************
*author:HQ Liu
*file:rosetta_js.js
*
********************/

/****************functionality: gi sequence/blast external link dialog*************


address serves for pop-up dialog of text format sequence info.
an addres here has three part.
a full link of ncbi sequence should be 
<var addr>+gi#+<var text>	address will be text format sequence page for certain gi#
<var addr>+gi#+<var seqview>	address will be full FASTA format sequence page for certain gi#.

@addr : 1st part of ncbi address
@text : 3rd part of a text format address
@seqview : 3rd part of a link addres of full format

2ed part of an address is gi#.
here each gi column has an attribute,'text_val', as gi#.

*********/


var addr="http://www.ncbi.nlm.nih.gov/protein/";
var text="?report=fasta&log$=seqview&format=text";
var seqview="?report=gpwithparts&log$=seqview";

var intSeq="rosetta_factory.php?seq_id=";

var BLAST_PREFIX="http://blast.ncbi.nlm.nih.gov/blast/Blast.cgi?CMD=Put&DATABASE=nr&PROGRAM=blastp&QUERY=";

var width;
var r_most;

//dialog default width 700. dialog box is div id:ncbi_box .
$(document).ready(function(){
	$("#ncbi_box").dialog({width:700,autoOpen:false});
	
});

$(function(){
	$("#search_input").keyup(function(){
		var keys=$(this).val();
		if(keys.length>1){
			var pos=$("td[value*="+keys+"]").position().top;
			var cur=$("#rosetta_table").position().top+$("#rosetta_table").height();
			console.log(pos);
			console.log(cur);
			if(pos!=null&&(pos>cur))
				$("#rosetta_table").scrollTop(pos-$(this).position().top);
		}
	});
})

$(document).ready(function(){
	$("#head_index").html($("#rosettaTableIndex").html());
	$("#head_index td").each(function(){
		$(this).width($("#rosettaTableIndex ."+$(this).attr("class")).width());
		$(this).css("min-width",$("#rosettaTableIndex ."+$(this).attr("class")).width());
	});
});

$(window).resize(sync_scroll);

function sync_scroll(){
	width=$(window).width();
	r_most=$("td.cmt").position().left+$("td.cmt").width();
	console.log(r_most<width);
	if(r_most<width){
		$("#table_bar").slider("disable");
		$("#rosetta_table").offset({left:0});
		$("#table_hhead").offset({left:0});
	}
	else{
		$("#table_bar").slider("enable");
	}
}
/**********this function is to set up sequence***************/
$(function(){
	$("td.gi").click(function(){
		//if there is no gi#, get sequence from rosetta_factory.
		if($(this).attr("text_val")=="-"){
			var seq_id=$(this).parent().find(".name").attr("value");
			var seqstr="";
			$("#dialog_frame").attr("src",intSeq+seq_id);
			$.ajax({
				url:"rosetta_factory.php",
				type:"POST",
				data:{'seq_id':seq_id},
				dataType:'json',
				success:function(dataObj){
					$("#blast_page").attr("href",BLAST_PREFIX+dataObj);
				}
			});
			$("#ncbi_box").dialog('open');
			return;
		}

		//otherwise get from ncbi.
		var src_addr=addr+$(this).attr('text_val')+text;
		var href_addr=addr+$(this).attr('text_val')+seqview;
		$("#dialog_frame").attr("src",src_addr);
		$("#external_ncbi").attr("href",href_addr);
		$("#blast_page").attr("href",BLAST_PREFIX+$(this).attr('text_val'));
		$("#ncbi_box").dialog('open');
	});
});		


$(document).ready(function(){
	$("#table_bar").slider({
		slide:function(event,ui){
			$("#rosetta_table").scrollLeft((r_most-width+10)*ui.value/100);
			$("#table_head").offset({left:0-(r_most-width+10)*ui.value/100});
		}
	});
	sync_scroll();
});


/****************gi sequence dialog END****************/

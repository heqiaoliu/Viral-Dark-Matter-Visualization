/*
 * Object: SelectedList
 *
 */

function SelectedList(){
	this.list={}
	this.csvQuery={};
}

SelectedList.prototype.addBact=function(bactID,bactExtID){
	this.list[bactID]={beid:bactExtID,exps:{}};
}

SelectedList.prototype.addExp=function(bactID,plateID,expID,repID){
	if(this.list[bactID]['exps'][plateID]==null)
		this.list[bactID]['exps'][plateID]={};
	this.list[bactID]['exps'][plateID][expID]=repID;
	this.csvQuery[expID]=[];
}

SelectedList.prototype.removeBact=function(bactID){
	delete this.list[bactID];
}

SelectedList.prototype.removeExp=function(bactID,plateID,expID){
	delete this.list[bactID]['exps'][plateID][expID];
	delete this.csvQuery[expID];
	if(Object.getOwnPropertyNames(this.list[bactID]['exps'][plateID]).length===0)
		delete this.list[bactID]['exps'][plateID];
}

SelectedList.prototype.getCsvQuery=function(){
	return JSON.stringify(this.csvQuery);
}

/***function to talk to PMApi.php
 * var ExpQuery is global, that it is the default query to GET from PMApi.php
 * For PMApi type,param, please check Documentation.
 * @ const
 */
var QUERY_PLATE='type=PMPlateByPlateID&param=';
var expQuery='type=PMExpsByBacteriaID&param=';
var curveQuery='type=PMGrowthByExpWell&param=';
function getFromPMApi(request,callback){
	$.ajax({
		url:"api/PMApi.php",
		type:"POST",
		data:request,
		dataType:"json",
		success:callback
	});
}


var TD='td',BID='bid';
var selectedList=new SelectedList();
var vdmChart;
//jQuery trigges&common used classes
var	CLASS_BEID='.beid',ID_EXP_INFO='#exp_info',CLASS_SEL='.selectable',
	FOCUS='focus',SELECTED='selected';
var chartBox,chartContainer,chartSwitch,beid;

/***Util jQuery functions**********
 * 1. selected is a highly used class that selected elements will be highlighted in yellow.
 *    .hasSelected() telles wheather an element has .selected;
 *    .toggleSel() toggle class selected();
 */
$.fn.hasFocus=function(){
	return $(this).hasClass(FOCUS);
}
$.fn.hasSelected=function(){
	return $(this).hasClass(SELECTED);
}

$.fn.addFocus=function(){
	beid.removeClass(FOCUS);
	return $(this).addClass(FOCUS);
}

$.fn.toggleSel=function(){
	return $(this).toggleClass(SELECTED);
}

$.fn.appendToNew=function(type,classes,id,values){
	$(this).append(genDom(type,classes,id,values));
	return $('#'+id);
}

$.fn.toggleChildren=function(){
	if($(this).hasSelected())
		$('.'+$(this).attr('id')).addClass(SELECTED);
	else
		$('.'+$(this).attr('id')).removeClass(SELECTED);
	return this;
}

function genDom(type,classSet,id,value){
	return '<'+type+' class="'+classSet+'" id="'+id+'">'+value+'</'+type+'>';
}

/*
 * When doms are ready:
 * 	
 */
$(document).ready(function(){
	$(ID_EXP_INFO).append(genDom('ul','','tabs','')).appendToNew('div','','exp_box','').appendToNew('table','','exps','');
	$('#tabs').appendToNew('li','','li_all','').appendToNew('a','','a_all','All Experiences').attr('href','#exp_box');
	$(ID_EXP_INFO).tabs();
	chartBox=$('#chart_box');
	chartContainer=$('#container');
	chartSwitch=$('#toggle_graph');
	beid=$(CLASS_BEID);
	chartSwitch.live('click',function(){chartBox.toggle();});
	chartBox.hide();
	beid.live('click',beidEffect);
});

/***Effects of bacteria external ids
 *  .beids are bacteria exteranl ids.
 *  They are selectable.
 ***/

function beidEffect(){
	$(this).hasFocus() ? $(this).toggleSel().bactSelection().getPMExp(displayExpInfo) : $(this).addFocus();
}

$.fn.bactSelection=function(){
	$(this).hasSelected() ? selectedList.addBact($(this).attr(BID),$(this).attr('bact_ext_id')) : selectedList.removeBact($(this).attr(BID));
	return this;
}

$.fn.getPMExp=function(callback){
	$(this).hasSelected()? getFromPMApi(expQuery+'['+$(this).attr(BID)+']',callback) : $('.'+$(this).attr('id')).remove();
	return this;
}


var tabs={};
function displayExpInfo(dataobj){
	for(var x in dataobj['data']){
		for(var y in dataobj['data'][x]){
			var data=dataobj['data'][x][y];
			if(tabs[data['plate_id']]==null){
				$('#tabs').appendToNew('li','','li_'+data['plate_id'],'').dealTabs(tabs).appendToNew('a','','a'+data['plate_id'],data['plate_name']).attr('href','#exp_box');
				$('#exp_option').append('<botton id="abutton" class="exp_option" pid="'+data['plate_id']+'" onclick="return false;">Selecte All '+data['plate_name']+'</botton></br>');
				tabs[data['plate_id']]={}
			}
			var uniqueId=data['bacteria_id']+'_'+data['replicate_id'];
			$('#exps').appendToNew('tr','b'+data['bacteria_id']+' selectable exp_info li_'+data['plate_id'],'exp_info_'+uniqueId,'').attr('exp_id',data['exp_id']).attr(BID,data['bacteria_id']).attr('pid',data['plate_id']).attr('rid',data['replicate_id']).append(genDom("td","file_name","date_"+data['replicate_id'],data['bacteria_external_id'])).append(genDom("td","replicate","rep_"+data['replicate_id'],data['replicate_id'])).append(genDom(TD,"exp_date","date_"+data['replicate_id'],data['experience_date'])).append(genDom(TD,"file_name","date_"+data['replicate_id'],data['file']));
		}
	}
	tabReady();
}

$.fn.dealTabs=function(arr){
	arr[$(this).attr('id')]=1;
	return this;
}


function tabReady(){
	$(ID_EXP_INFO).tabs('destroy').tabs({
		activate:function(event,ui){
				if(ui.newTab.attr('id')=='li_all')
					$("tr.exp_info").show(); 
				else{
					$('.exp_info').hide();
					$('tr.'+ui.newTab.dealPlates(fillPlate).attr('id')).show();
				}
		}
	});
}

/*
 * exp_info effects
 */

$('.exp_option').live('click',function(){
	$('.li_'+$(this).attr('pid')).toggleSel().each(function(){$(this).expSelection()});
});

$('.exp_info').live('click',function(){$(this).toggleSel().expSelection()});

$.fn.expSelection=function(){
	var bid=$(this).attr(BID),pid=$(this).attr('pid'),exp_id=this.attr('exp_id');
	$(this).hasSelected() ? selectedList.addExp(bid,pid,exp_id,$(this).attr('rid')): selectedList.removeExp(bid,pid,exp_id);
	return this;
}

$.fn.dealPlates=function(callback){
	var pid=$(this).attr('id').substr(3);
	getFromPMApi(QUERY_PLATE+'['+pid+']',callback);
	return this;
}

function fillPlate(dataobj){
	var colors={}
	var colorCounter=-1;
	$('#select_options').empty();
	for(var x in dataobj){
		for(var y in dataobj[x]){
			var well_ele=dataobj[x][y]
			var temp=well_ele['control_name'];
			if(colors[temp]==null){
				colors[temp]=++colorCounter;
				$('#select_options').appendToNew('div','select_option selectable color'+colors[temp],temp,'select all '+temp);
			}
			$('#c'+well_ele['well_id']).attr('class',temp+' well_ele selectable color'+colorCounter).attr('title',well_ele['supplement']+' '+well_ele['conc']);
		}
	}
}

/*
 * well effects
 */
$('.well_ele').live('click',function(){$(this).toggleSel()});
$('.select_option').live('click',function(){$(this).toggleSel().toggleChildren()});


/*
 * generate a graph.
 */

$('#get_graph').live('click',function(){
	var temp=[];
	for(var x in selectedList.csvQuery){
		selectedList.csvQuery[x]=[];
		$('.well_ele.selected').each(function(){
			temp.push([x,$(this).attr('cid')]);
			selectedList.csvQuery[x].push(parseInt($(this).attr('cid')));
		});
	}
	getFromPMApi(curveQuery+JSON.stringify(temp),createStatChart);
	chartBox.show();
});


function createStatChart(dataobj){
	var data=[];
	var option=new VDMHighchartOption('container',$(window).height()*0.8,$(window).width()*0.7,data);
	$('.well_ele.selected').each(function(){
		for(var x in selectedList.list){
			for(var y in selectedList.list[x]['exps']){
				var stat=new StatisticalObject(64);
				for(var z in selectedList.list[x]['exps'][y]){
					stat.pushArr(dataobj[z][$(this).attr('cid')]);
					if(!$('#stat_only').prop('checked'))
						data.push({name:$('#b'+x).html()+' '+$('#a'+y).html()+' '+selectedList.list[x]['exps'][y][z]+' '+$(this).html(),data:convertArray(dataobj[z][$(this).attr('cid')]),type:'line',marker:{radius:1},lineWidth:1.5});
				}
				stat.getAll();
				if($('#stat_graph').prop('checked')){
					var tempColor=option.getNextColor();
					data.push({name:'avg-'+$('#b'+x).html()+' '+$('#a'+y).html()+' '+$(this).html(),data:stat.getTwoDAvg(),type:'line',color:tempColor,marker:{radius:2},stack:0,dashStyle:'DashDot',lineWidth:3});
					data.push({data:stat.getTwoDError(),type:'errorbar',color:tempColor,stack:0});
					data.push({name:'median-'+$('#b'+x).html()+' '+$('#a'+y).html()+' '+$(this).html(),data:stat.getTwoDMedian(),type:'line',color:tempColor,marker:{radius:2},stack:0,dashStyle:'Dash',lineWidth:3});
				}
			}
		}
	});
	if($('#log2y').prop('checked'))
		option.setLogY();
	else
		option.setNormalY();
	if($('#hourx').prop('checked'))
		option.setHourX();
	else
		option.setMinuteX();
	vdmChart=chartContainer.highcharts(option.getOption());
}

$('#test').live('click',function(){ console.log(vdmChart);var temp=vdmChart.series[0]; temp.hide()});

function convertArray(assArray){
	var temp=[];
	for(x in assArray)
		temp.push([parseInt(x),assArray[x]]);
	return temp;
}

$(function(){
  $("#csv").click(function(){
	$.ajax({
		url:"viewsite/csvgenerator.php",
		data:"param="+selectedList.getCsvQuery(),
		dataType:"json",
		type:"GET",
		success:function(dataObj){
          window.location='http://vdm.sdsu.edu/data/viewsite/downCsv.php?filename='+dataObj;
		}
	})
  });
});

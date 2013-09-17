var LOG_BASE_2=parseFloat(Math.LOG10E/Math.LOG2E);
var LOG_YAXIS={
	type:'logarithmic',
	tickInterval:LOG_BASE_2,
	minorTickInterval:0.02,
	title:{text:'O.D.'},	
	labels:{
//		formatter:function(){return parseFloat((this.value).toString().substring(0,3));}
	}
};
var HOUR_XAXIS={labels:{formatter:function(){return Math.round(this.value/6)/10+' hrs';}}}
var MINUTE_XAXIS={labels:{formatter:function(){return this.value+' minutes';}}}
var NORMAL_YAXIS={
	type:'linear'
};
var PLOT_STACK_OPT={
	series: {}
};

function VDMHighchartOption(render,x,y,seriesData){
    this.colors= ['#AA4643','#89A54E','#80699B','#3D96AE','#DB843D','#92A8CD','#A47D7C','#B5CA92'];
	this.curColor=0;
	this.option={
		chart:{
			renderTo:render,
			type:'line',
			height:x,
			width:y
		},
		credits:{text:'vdm.sdsu.edu',href:'http://vdm.sdsu.edu'},
		exporting:{
			enableImages:true
		},
		series:seriesData,
		yAxis:null,
		xAxis:null
	};
}

VDMHighchartOption.prototype.getOption=function(){
	return this.option;
}

VDMHighchartOption.prototype.setLogY=function(){
	this.option.yAxis=LOG_YAXIS;
}

VDMHighchartOption.prototype.setNormalY=function(){
	this.option.yAxis=NORMAL_YAXIS;
}

VDMHighchartOption.prototype.getNextColor=function(){
	if(this.curColor>8)
		this.curColor=0;
	return this.colors[this.curColor++];
}

VDMHighchartOption.prototype.setHourX=function(){
	this.option.xAxis=HOUR_XAXIS;
}
VDMHighchartOption.prototype.setMinuteX=function(){
	this.option.xAxis=MINUTE_XAXIS;
}

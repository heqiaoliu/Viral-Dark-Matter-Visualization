
var chart;
var logBaseTwo=parseFloat(Math.LOG10E/Math.LOG2E);
 
function genGraph(obj) {
    var subtitle=" ";
    $('div#container').addClass('gengraph');
    var options = new Array,
        seriesObj = new Array(); // create series object for chart
    seriesObj=getSeriesObj(obj);
    console.log(seriesObj);
    options = {
        chart: {
            renderTo: 'container',
            defaultSeriesType: 'line',
			height:500,
			width:500,
			style:{
				fontSize:'5px'
			}
        },
	    labels: {
	        items: [{
            }],
            style: {
                color: '#808080',
            }
	    },
        title: {
            text: 'Growth Measurement',
        },
        subtitle: {
            text: "Select clone:"+subtitle,
        },
        xAxis: {
            title: {
                text: 'Time'
            }
        },
        yAxis: [{
	    type: 'logarithmic',
	    tickInterval:logBaseTwo, 
	    minorTickInterval:0.02,	
            title: {
                text: 'Growth (O.D.)'
            },
	    labels:{formatter:function(){return parseFloat((this.value).toString().substring(0,7));}},
            plotLines: [{
                value: 0,
                width: 1,
                color: '#808080'
            }]
        }],
        series: seriesObj,

		exporting:{
			enableImages:true
		}

	};
        chart = new Highcharts.Chart(options);

}

function getSeriesObj(obj){
    var colors= new Array('#4572A7','#AA4643','#89A54E','#80699B','#3D96AE','#DB843D','#92A8CD','#A47D7C','#B5CA92');
    var i=0;
    var seriesObj=new Array();
    $(".on_list").each(function(){
	var current=(this).id.split(" ");
	seriesObj.push({name:(this).id,lineWidth:2.5,marker:{radius:3},color:colors[i++],data:obj[current[0]][current[1]]});
    });
    return seriesObj;
}


function groupsObj(bactId){
  this.extId=bactId;
  this.expDate=new Array();
  this.repArr=new Array();

}
groupsObj.prototype.addRep=function(repDate,repNum){
	var pos=this.expDate.indexOf(repDate);
	if(pos>=0)
		this.repArr[pos].push(repNum);
        else{
		this.expDate.push(repDate);
		this.repArr.push(new Array(repNum));
        }
}

groupsObj.prototype.groupNum=function(){
	return this.expDate.length;
}

groupsObj.prototype.groupSize=function(posDate){
	return this.repArr[posDate].length;
}

groupsObj.prototype.isIdMatch=function(bactId){
	return bactId==this.extId;
}

groupsObj.prototype.getGroup=function(posDate){
	return this.repArr[posDate];
}

groupsObj.prototype.getRep=function(posDate,posRep){
	return this.repArr[posDate][posRep];
}

groupsObj.prototype.getRepNames=function(posDate){
	var temp=this.repArr[posDate];
	console.log(temp);
	var name="";
	for(var i=0;i<temp.length;i++){
		if(i!=0)
			name+=" and ";
		name+=parseInt(temp[i]);
	}
	return name;
}

groupsObj.prototype.getAllInfo=function(){
	var temp="";
	for(var i=0;i<this.repArr.length;i++)
		temp+=this.extId+" "+this.repArr[i].toString()+" ";
	return temp;
}

function groupList(){
  this.groupArr=new Array();
  this.bactIdArr=new Array();
}

groupList.prototype.addRep=function(bactId,repDate,repNum){
 	var pos=this.bactIdArr.indexOf(bactId);
	if(pos>=0)
		this.groupArr[pos].addRep(repDate,repNum);
	else{
		this.bactIdArr.push(bactId);
		var tempObj=new groupsObj(bactId);
		tempObj.addRep(repDate,repNum);
		this.groupArr.push(tempObj);
	}
}

groupList.prototype.getRepNames=function(posBact,posDate){
	return this.groupArr[posBact].getRepNames(posDate);
}

groupList.prototype.getBactId=function(pos){
	return this.bactIdArr[pos];
}

groupList.prototype.getGroup=function(pos,posDate){
	return this.groupArr[pos].getGroup(posDate);
}

groupList.prototype.getReplicate=function(posBact,posDate,posRep){
	return this.groupArr[posBact].getRep(posDate,posRep);
}

groupList.prototype.listSize=function(){
	return this.bactIdArr.length;
}

groupList.prototype.getAllInfo=function(){
	var temp="";
	for (var i=0;i<this.groupArr.length;i++)
		temp+=this.groupArr[i].getAllInfo();
	return temp;
}

groupList.prototype.getGroupNum=function(posBact){
	return this.groupArr[posBact].groupNum();
}

groupList.prototype.getGroupSize=function(posBact,posDate){
	return this.groupArr[posBact].groupSize(posDate);
}

groupList.prototype.popGroup=function(bactId,posDate){
	var pos=this.bactIdArr.indexOf(bactId);
	return this.groupArr[pos].popGroup[posDate];
}

groupList.prototype.popRep=function(bactId,posDate,posRep){
	var pos=this.bactIdArr.indexOf(bactId);
	return this.groupArr[pos].getRep[posDate][posRep];
}

groupList.prototype.popList=function(){
	return this.groupArr;
}

function repIncrease(id){
	var x=id.split(":");
	var y=parseInt(x[1]);
	return x[0]+":"+y;
		
}
//get Sereis


function getSeriesObj(obj,tempList,individualList,wellname){
    var colors= new Array('#4572A7','#AA4643','#89A54E','#80699B','#3D96AE','#DB843D','#92A8CD','#A47D7C','#B5CA92');
    var seriesObj=new Array();
    var counter=0;
    var clock=new Date();
    var start=clock.getSeconds()*1000+clock.getMilliseconds();
    for(var i=0;i<tempList.listSize();i++){
		var cnm=tempList.getBactId(i);
		for(var j=0;j<wellname.length;j++){
			var wnm=wellname[j];
			for(var k=0;k<tempList.getGroupNum(i);k++){
				var seriesName;
				var curColor=colors[counter++%9];
				var curSize=tempList.getGroupSize(i,k);
			    	if(curSize>1){
					var devUp=new Array();
					var devDown=new Array();
				}
				var avgSer=new Array();
				var groupMem=tempList.getGroup(i,k);
				var dataLen=obj[cnm+":"+groupMem[0]][wnm].length;
				for(var l=0;l<dataLen;l++){
					var tempAvg=0;
					var tempSsqure=0;
					var tempSTD=0;
					var tempname;
					for(var m=0;m<curSize;m++){
						tempname=cnm+":"+groupMem[m];
						tempAvg+=parseFloat(obj[tempname][wnm][l][1]);
					}
					tempAvg/=curSize;
					if(curSize>1){
						for(var m=0;m<curSize;m++){
							tempname=cnm+":"+groupMem[m];
							tempSsqure+=Math.pow(parseFloat(obj[tempname][wnm][l][1])-tempAvg,2);
						}
						tempSTD=Math.sqrt(tempSsqure/curSize);
					devUp.push(new Array(parseFloat(obj[tempname][wnm][l][0]),tempAvg+tempSTD));
					devDown.push(new Array(parseFloat(obj[tempname][wnm][l][0]),tempAvg-tempSTD));
					}
				avgSer.push(new Array(parseFloat(obj[tempname][wnm][l][0]),tempAvg));
				}
				seriesName=cnm;

				if(curSize>1){
					seriesName="*Avg of "+cnm+" Rep"+tempList.getRepNames(i,k);
				}
			    	seriesName+="-"+wnm;
			    seriesObj.push({name:seriesName,color:curColor,marker:{radius:3},data:avgSer});
				if(curSize>1){
					var seriesName1="*Dev+ of "+cnm+"Rep"+tempList.getRepNames(i,k)+"-"+wnm;
					var seriesName2="*Dev- of "+cnm+"Rep"+tempList.getRepNames(i,k)+"-"+wnm;
			    		seriesObj.push({name:seriesName1,/*dashStyle:"shortDot",*/lineWidth:0.5,marker:{/*enabled:false,*/radius:2},color:curColor,data:devUp});
			    		seriesObj.push({name:seriesName2,/*dashStyle:"shortDot",*/lineWidth:0.5,marker:{/*enabled:false,*/radius:2},color:curColor,data:devDown});
				}
			}
		}
	}
	for(var i=0;i<individualList.length;i++){
		var cnm=individualList[i];
      		for(var j=0;j<wellname.length;j++){
			var wnm=wellname[j];
			var dataPoints=new Array();
			var curColor=colors[(counter++)%9];
			for(var k=0;k<obj[cnm][wnm].length;k++)
				dataPoints.push(new Array(parseFloat(obj[cnm][wnm][k][0]),parseFloat(obj[cnm][wnm][k][1])));
			seriesObj.push({name:cnm+" "+wnm,lineWidth:2.5,marker:{radius:3},color:curColor,data:dataPoints});
			console.log(dataPoints);
		}
	} 
	return seriesObj;
}
//------------------following is  the methods for JQuery Quick implement
//unique jQuery selector x, and the class want to toggle y.
//class y will be add if the target has it, otherwise removed.
function classTog(x,y){
  if($(x).hasClass(y))
    $(x).removeClass(y);
  else
    $(x).addClass(y);
}

function classTogTwo(varx,vary,classx,classy){
  if($(varx).hasClass(classx)){
    $(varx).removeClass(classx);
    $(vary).removeClass(classy);
  }
  else{
    $(varx).addClass(classx);
    $(vary).addClass(classy);
  }
    
}

function twoClassTog(varx,classOne,classTwo){
  if($(varx).hasClass(classOne)){
    if($(varx).hasClass(classTwo)){
    $(varx).removeClass(classOne);
    $(varx).removeClass(classTwo);}
    else
    $(varx).addClass(classTwo);
  }
  else
    $(varx).addClass(classOne);
    
}

function htmlChange(varx,message){
   $(varx).html(message);
}
//-------------------------------instruction to users
$(document).ready(function(){
  $("p.beid").click(function(){
    $("p#userGuide1").html("All selected IDs will be highlighted by yellow.The black box indicates you are working with which ID.");
    $("p#userGuide2").html("To deselect the ID in the black box, click it again.");
    $("p#userGuide3").html("To continue, select the plate you want on the right by clicking.");
  });
  $("td.plate").click(function(){
    $("p#userGuide1").html("All selected plates will be highlighted by yellow.The black box indicates you are working with which plate.");
    $("p#userGuide2").html("To deselect the plate in the black box, click it again.");
    $("p#userGuide3").html("To continue, select the replicate(s) you want below by clicking.");
  });

  $("td.replicate").click(function(){    
    $("p#userGuide1").html("The growth measurement of each well from the replicate will graph as individual if the replicate is highlighted by yellow;");
    $("p#userGuide2").html("Click the replicate again, it turns to blue, that it will graph the average for this replicate together with ones from same Bacteria with same experiment date.");
    $("p#userGuide3").html("To deselect click until it is not highlighted. To continue, select the well(s) you want below by clicking.");
  });

  $("td.sel").click(function(){
    $("p#userGuide1").html("Click to select well.");
    $("p#userGuide2").html("Click the index at the beginning of each row to select a whole row.");
    $("p#userGuide3").html("Click on other bacteria and plate if you want to make more selection. Otherwise, please click on \"genrate\" to generate the graph.");
  });
});


//----------------following is effect for view.php
$(function(){
  $("td.sel").mouseover(function(){
    var x=(this).id;
    if(x.length>1){
	getWell(x);
    }
  });
  $("#Tclones td").mouseleave(function(){
      htmlChange("p#infoBox","Information");
  });
});


$(function(){
  $("td.replicate.num").mouseover(function(){
    htmlChange("p#infoBox","Replicate"+(parseInt((this).title)+1)+" information: "+(this).getAttribute("expdate"));
  });
  $("td.replicate.num").mouseleave(function(){
    htmlChange("p#infoBox","Information");
  });
});

$(function() {
  $('.selectRow').click(function() {
    classTogTwo(".selectRow#"+(this).id,".sel."+(this).id,"Rselected","selected");
  });
});

$(function() {
  $('#Tclones td.sel').click(function() {
    var x = "td#"+this.id+".sel";
    classTog(x,"selected");
  });
});


$(function() {
  $('#clearButton').click(function() {
    clearVal = $(this).val();
    if (clearVal == "Clear") {
        $('#Tclones td.sel').removeClass('selected');
        $(this).val("Select All");
    } else {
        $('#Tclones td.sel').addClass('selected');
        $(this).val("Clear");
    }
  });
});



/*
function repAccess(x,clickeditem){
      $.ajax({
        type:"POST",
        url:"genRep.php",
        data:clickeditem,
        dataType:"json",
        success:function(dataObj){
	for(var i=0;i<dataObj.length;i++)
          $("tr#replicate").append("<td class=\"replicate "+x+"\" title=\""+i+"\" id=\""+x+dataObj[i]+"\">"+dataObj[i]+"</td>");
      repSelAddon();
        }
      });
}
*/

$(function (){
  $("td.replicate.num").click(function(){
    var item="td.replicate#"+(this).id;
    twoClassTog(item,"selected","grouped");
  });
});

function repInfo(id){
    var x=id;
    $("td.replicate").hide();
    $("td.replicate#desb").html("Replicate for "+x+":");
    $("td.replicate#desb").show();
    if(x.charAt(0)=='R')
	x=x.replace("R.S.","R_S_");
    $("td.replicate."+x).show();
}


$(function(){
/*  $("p.beid").dblclick(function(){
    var x=(this).id;
    classTog("p.beid#"+x,"selected");
    return false;
  });*/
  $("p.beid").click(function(){
   /* var x=(this).id;
    $("p.beid").removeClass("focus");
    $(this).addClass("focus");*/
    if($(this).hasClass("focus")&&$(this).hasClass("selected")){
        $(this).removeClass("selected");
    }
    else{
        $("p.beid").removeClass("focus");
	$(this).addClass("selected");
	$(this).addClass("focus");
    }
    repInfo((this).id);
    plateInfo((this).id);
  });
});

$(function (){
  $("td.plate.name").click(function(){
    var item="td.plate#"+(this).id;
    $("td.replicate").removeClass("focus");
    $(this).addClass("focus");
    classTog(item,"selected");
  });
});
function plateInfo(id){
    var x=id;
    $("td.plate").hide();
    $("td.plate#desc").html("Plate for "+x+":");
    $("td.plate#desc").show();
    if(x.charAt(0)=='R')
	x=x.replace("R.S.","R_S_");
    $("td.plate."+x).show();
}


$(function(){
  var scrollPos=0;
  $("input#edtin").keyup(function(){
    var x=$(this).val();  
    if(x.length==4){
      x="EDT"+x;
      $("p.beid").removeClass("focus");
      $("p.beid#"+x).addClass("focus");
      repInfo(x);
      $(this).val("");
      if(($("p.beid#"+x).position().top-$("p.beid:first").position().top)>225)
        $("div#cloneSelect").scrollTop(($("p.beid#"+x).position().top-$("p.beid:first").position().top)-225);
      else
	$("div#cloneSelect").scrollTop(0);
    }
  });
});
/*
//vcid is no more need to select.
$(function(){
  $("p.vcid").click(function(){
    $this=$(this);
    if($this.hasClass("selected")){
      $this.removeClass("selected");
      $("p#"+(this).title).removeClass("selected");
      }
    else{
      $this.addClass("selected");
      $("p#"+(this).title).addClass("selected");
    }
});
});
*/


function minheight(number) {
    if (number > 20 ){
            var height = (600 + (number-10)*3).toString()+"px";
            $('div#container').css("min-height", height);
    }
}
/*
$(function(){
  var avgshow,errorshow,average,sSquare,x,current;
  //every time,a new chart is generated, all variables re-initialized.
  $("#submit_btn").click(function(){
    avgshow=errorshow=false;
    average=new Array();
    sSquare=new Array();
    x=0;
  });
  function getavg(){
    x=chart.series.length;
    for(var i=0;i<chart.series[0].data.length;i++){
      var tempXaxis=chart.series[0].data[i].x;
      var tempYaxis=0;
      for(var j=0;j<x;j++)
        tempYaxis+=chart.series[j].data[i].y;
      tempYaxis/=x;
      average[i]=[tempXaxis,tempYaxis]; 
    }
      console.log(average);
  }

  function getS(){
    if(average.length==0)
      getavg();
    for(var i=0;i<chart.series[0].data.length;i++){
      sSquare[i]=0;
        for(var j=0;j<x;j++)
          sSquare[i]+=Math.pow((chart.series[j].data[i].y)-(average[i][1]),2);
      sSquare[i]/=x;
    }
  }

  $("button#avg").click(function(){
    if(!avgshow){
      if(average.length==0)
        getavg();
      if(errorshow)
        current=x+2;
      else
        current=x;
      chart.addSeries({name:'average:fortest-test.test',color:'black',dashStyle:'longdash',data:average});
      avgshow=true;
    }
    else
      alert("average has already been shown");
   });  
  $("button#dev").click(function(){
    if(!errorshow){
      getS();
      //chart.addSeries({name:'deviation',type:'column',yAxis:1});
      var temp1=new Array();temp2=new Array();
      for(var i=0;i<average.length;i++){
	var buffer=Math.sqrt(sSquare[i]);
        temp1[i]=[average[i][0],average[i][1]+buffer];
	temp2[i]=[average[i][0],average[i][1]-buffer];	
      }
	console.log(temp1);
	console.log(temp2);
	chart.addSeries({name:'average+error:fortest-test.test',dashStyle:'Dot',data:temp1,fillOpacity:0.2,marker:{enabled:false}});
	chart.addSeries({name:'average-error:fortest-test.test',fillOpacity:0.2,dashStyle:'Dot',data:temp2, marker:{enabled:false}});
	errorshow=true;
    }		
  else
    alert("Standard error has already been generated.");
  });
}); 
*/
$(function() {  
    $('#submit_btn').click(function() { 
	var tempList=new groupList(); 
	// validate and process form here  
        var datastring = "", // For POST value clone, e.g. $_POST['clone'] and its value is a ; seperated string. EDT1111;EDT2222;EDT3333 etc.
            tempstring = "", // For POST value well e.g. A1;A2;A3;A4;E1;E2; ...
            file = $('select#file').val(),
            clone = $('select#clone').val(),
            individualList = new Array(),
            wellname = new Array();

        $('p.selected').each(function() {
	    if($(this).hasClass("beid")){
              var labelInfo= (this).id;
              datastring = datastring+labelInfo;
  	      if(labelInfo.charAt(0)=='R')
		labelInfo=labelInfo.replace("R.S.","R_S_");
	      $("td.replicate."+labelInfo).each(function(){
		if($(this).hasClass("selected")){
		  if($(this).hasClass("grouped"))
		    tempList.addRep(labelInfo,(this).getAttribute("expdate"),(this).title);
                  else
                    individualList.push(labelInfo+":"+(this).title);
		  var tempReplicateNum=(this).title;
		  datastring+=","+tempReplicateNum;
		}
	      });
	      datastring+=";";
	    }
        });
	if(datastring.length!=0)
	  datastring="&clone="+datastring;
	/*this is for Post vc_id to pregraph.php
	if(tempstring.length!=0)
	  datastring+="&vcid="+tempstring;
	*/
	tempstring="";
        // For each well, push onto wellname array and append to POST variable well.  
        $('td.sel.selected').each(function() {
	    var wellId=(this).id
            tempstring+=wellId+";";
            wellname.push(wellId);
        });
        datastring+="&well="+tempstring;
	console.log(tempList);
	console.log(individualList);
	$.ajax({
            type: "POST",
            url: "viewsite/view_pregraph.php",
            data: datastring,
            dataType: "json",
            success: function(dataObj) {

                //display message back to user here
                console.log("inside success");
                console.log(dataObj);
                gengraph(dataObj,tempList,individualList,wellname);
            }
         });
      });
});

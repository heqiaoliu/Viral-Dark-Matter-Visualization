function getWell(wellId){
    var bacteria_id="";
    var plate_id="";
    var info=""
    $("p.beid.focus").each(function(){
	bacteria_id=(this).getAttribute("bacteria_id");
    });
    $("td.plate.focus").each(function(){
	plate_id=(this).getAttribute("plate_id");
    });  
    var askingInfo="&type=wellinfo;&bacteria_id="+bacteria_id+";&plate_id="+plate_id+"; &well="+wellId+";";
    console.log(askingInfo);
    $.ajax({
        type:"POST",
        url:"viewsite/infoTrans.php",
        data: askingInfo,
        dataType: "json",
        success:function(dataObj,info){
    		htmlChange("p#infoBox",wellId+" is "+dataObj);
        }
    });
	return info;
}
